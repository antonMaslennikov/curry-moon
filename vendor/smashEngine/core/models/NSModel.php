<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 01.12.2017
 * Time: 18:44
 */

namespace smashEngine\core\models;

use smashEngine\core\App;
use smashEngine\core\Model;
use smashEngine\core\services\AdapterInterface;

/**
 * Class NSModel
 * @package smashEngine\core\models
 *
 * @property int $id;
 * @property int $parent_id;
 * @property int $lft;
 * @property int $rgt;
 * @property int $depth;
 */
class NSModel extends Model implements AdapterInterface {

	const INITIAL_LEFT = 0;

	const INITIAL_RIGHT = 1;

	const INITIAL_DEPTH = 0;

	public function tableName() {

		return self::$dbtable;
	}

	/**
	 * @var int
	 */
	public $id;


	/**
	 * @return int
	 */
	public function getChildCount()
    {
        return (int) floor(($this->rgt - $this->lft) / 2);
    }


	/**
	 * Grow or shrink the tree from the specified position. If $value is positive, the tree grows from $position
	 * to the right. If $value is negative, the tree shrinks from $position to the left.
	 * @param int $position
	 * @param int $treeId
	 * @param int $value
	 * @return bool
	 */
	private function resizeAt(int $position, int $value)
	{
		$sql = <<<EOD
update {$this->tableName()}node
set
    lft = (select case when lft > :position_1 then lft + :value_1 else lft end),
    rgt = (select case when rgt > :position_2 then rgt + :value_2 else rgt end)
where tree_id = :tree_id
EOD;

		$stmt = App::db()->prepare($sql);
		return $stmt->execute([
			':position_1' => $position,
			':position_2' => $position,
			':value_1' => $value,
			':value_2' => $value,
		]);
	}


	/**
	 * @param NSModel $node
	 *
	 * @return bool
	 */
	private function insertNode(NSModel $node)
	{
		throw new \RuntimeException("Реализуйте данный метод");

		$sql = <<<EOD
insert into {$this->tableName()} (parent_id, lft, rgt, depth)
values (:parent_id, :left, :right, :depth)
EOD;
		$stmt = App::db()->prepare($sql);
		$res = $stmt->execute([
			':parent_id' => $node->parent_id,
			':left' => $node->lft,
			':right' => $node->rgt,
			':depth' => $node->depth
		]);

		$node->id = App::db()->lastInsertId();

		return $res;
	}


	/**
	 * @param int $id
	 *
	 * @return NSModel
	 */
	public function getNode($id)
    {
        $stmt = App::db()->prepare('select * from '.self::$dbtable.' where id = :id');

        $stmt->execute([':id' => $id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($data)) {
	        throw new \RuntimeException("Node with id {$id} not found");
        }

	    $className = get_called_class();
	    $class = new $className;
	    $class->setAttributes($data);

        return $class;
    }


	/**
	 * @param int $id
	 *
	 * @return bool
	 */
	public function deleteNode($id)
	{
		$node = $this->getNode($id);
		$sql = '
delete from {$this->tableName()}
where lft >= :left
and rgt <= :right
';
		$stmt = App::db()->prepare($sql);
		$r1 = $stmt->execute([
			':left' => $node->lft,
			':right' => $node->rgt,
		]);

		// since a node was deleted we must shrink the tree to remove the gap
		$r2 = $this->resizeAt($node->rgt, -(2 + $node->getChildCount() * 2));

		return $r1 && $r2;
	}


	/**
	 * @param int $id
	 * @param array $data
	 * @return bool
	 */
	public function setData($id, array $data) {

		throw new \RuntimeException("Реализуйте данный метод");
	}

	/**
	 * @param int $parentId
	 * @param NSModel $child
	 * @return bool
	 */
	public function addChild($parentId, NSModel $child) {

		$parent = $this->getNode($parentId);

		$child->lft = $parent->rgt;
		$child->rgt = $child->lft + 1;
		$child->depth = $parent->depth + 1;
		$child->parent_id = $parentId;

		$r1 = $this->resizeAt($parent->rgt - 1, 2);
		$r2 = $this->insertNode($child);

		return $r1 && $r2;
	}

	/**
	 * @param int $id
	 * @return NSModel[]
	 */
	public function getChildren($id) {

		$parent = $this->getNode($id);

		$sql = <<<EOD
select *
from {$this->tableName()}
where lft > :left
    and rgt < :right
    and depth = :depth
EOD;

		$stmt = App::db()->prepare($sql);
		$stmt->execute([
			':left' => $parent->lft,
			':right' => $parent->rgt,
			':depth' => $parent->depth + 1,
		]);
		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return array_map(function($row) {

			$className = get_called_class();
			$class = new $className;
			$class->setAttributes($row);

			return $class;
		}, $data);
	}

	/**
	 * @param int $id
	 * @return NSModel[]
	 */
	public function getAllChildren($id) {

		$parent = $this->getNode($id);
		$sql = <<<EOD
select *
from {$this->tableName()}
where lft > :left
    and rgt < :right
EOD;

		$stmt = App::db()->prepare($sql);
		$stmt->execute([
			':left' => $parent->lft,
			':right' => $parent->rgt,
		]);
		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return array_map(function($row) {

			$className = get_called_class();
			$class = new $className;
			$class->setAttributes($row);

			return $class;
		}, $data);
	}


	public function createTree() {

		throw new \RuntimeException("Реализуйте данный метод");

		$className = get_called_class();

		$class = new $className;
		$class->setAttributes([
			'lft' => Node::INITIAL_LEFT,
			'rgt' => Node::INITIAL_RIGHT,
			'depth' => Node::INITIAL_DEPTH,
			'parent_id'=>0,
		]);

		$this->insertNode($class);
	}


	/**
	 * @param int $id
	 * @param int $parent_id
	 * @return bool
	 */
	public function moveNode($id, $parent_id) {

		$node = $this->getNode($id);
		$target = $this->getNode($parent_id);

		// expand the target parent to fit the node and its children
		$growth = 2 + $node->getChildCount() * 2;
		$r1 = $this->resizeAt($target->rgt - 1, $growth);

		$moveDelta = $target->rgt + $growth - $node->rgt - 1;
		$depthDelta = $target->depth + 1 - $node->depth;

		//update parent_id
		$sql = <<<EOD
update {$this->tableName()}
set
    parent_id = :parent,
	where id = :node
EOD;
		$stmt = App::db()->prepare($sql);
		$r4 = $stmt->execute([
			':parent' => $target->id,
			':node' => $node->id,
		]);

		// use the deltas to move the node and its children to the parent
		$sql = <<<EOD
update {$this->tableName()}
set
    lft = lft + :move_delta_1,
    rgt = rgt + :move_delta_2,
    depth = depth + :depth_delta
where lft >= :left
and rgt <= :right
EOD;
		$stmt = App::db()->prepare($sql);
		$r2 = $stmt->execute([
			':move_delta_1' => $moveDelta,
			':move_delta_2' => $moveDelta,
			':left' => $node->lft,
			':right' => $node->rgt,
			':depth_delta' => $depthDelta,
		]);

		// remove the leftover space after moving the node
		$r3 = $this->resizeAt($node->rgt, $node->tree_id, -$growth);

		return $r1 && $r2 && $r3 && $r4;
	}
}