<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 01.12.2017
 * Time: 18:44
 */

namespace smashEngine\core\models;

use PDO;
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
 * @property int $level;
 */
class NSModel extends Model implements AdapterInterface {

	const INITIAL_LEFT = 0;

	const INITIAL_RIGHT = 1;

	const INITIAL_DEPTH = 0;

	public function tableName() {

		return self::$dbtable;
	}


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
	protected function resizeAt($position, $value)
	{
		$position = (int) $position;
		$value = (int) $value;

		$sql = <<<EOD
update {$this->tableName()}
set
    lft = (select case when lft > :position_1 then lft + :value_1 else lft end),
    rgt = (select case when rgt > :position_2 then rgt + :value_2 else rgt end)
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
	protected function insertNode(NSModel $node)
	{
		throw new \RuntimeException("Реализуйте метод insertNode");

		$sql = <<<EOD
insert into {$this->tableName()} (parent_id, lft, rgt, level)
values (:parent_id, :left, :right, :level)
EOD;
		$stmt = App::db()->prepare($sql);
		$res = $stmt->execute([
			':parent_id' => $node->parent_id,
			':left' => $node->lft,
			':right' => $node->rgt,
			':level' => $node->level
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
		$sql = <<<EOD
delete from {$this->tableName()}
where lft >= :left and rgt <= :right
EOD;
		$stmt = App::db()->prepare($sql);
		$r1 = $stmt->execute([
			':left' => (int) $node->lft,
			':right' =>(int) $node->rgt,
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
	public function addChild($parent_id, NSModel $child) {

		$parent = $this->getNode($parent_id);

		$child->lft = $parent->rgt;
		$child->rgt = $child->lft + 1;
		$child->level = $parent->level + 1;
		$child->parent_id = $parent->id;

		$r1 = $this->resizeAt($parent->rgt - 1, 2);
		$r2 = $this->insertNode($child);

		return $r1 && $r2;
	}

	/**
	 * @param int $id
	 * @return []
	 */
	public function getChildren($id) {

		$parent = $this->getNode($id);

		$sql = <<<EOD
select *
from {$this->tableName()}
where lft > :left
    and rgt < :right
    and level = :level
EOD;

		$stmt = App::db()->prepare($sql);
		$stmt->execute([
			':left' => $parent->lft,
			':right' => $parent->rgt,
			':level' => $parent->level + 1,
		]);
		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return array_map([$this, 'transformData'], $data);
	}

	/**
	 * @param int $id
	 * @return array
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

		return array_map([$this, 'transformData'], $data);
	}


	public function createTree() {

		throw new \RuntimeException("Реализуйте метод createTree");

		$className = get_called_class();

		$class = new $className;
		$class->setAttributes(array_merge([
			'lft' => self::INITIAL_LEFT,
			'rgt' => self::INITIAL_RIGHT,
			'level' => self::INITIAL_level,
			'parent_id'=>0,
		], $data));

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
		$levelDelta = $target->level + 1 - $node->level;

		//update parent_id
		$sql = <<<EOD
update {$this->tableName()}
set
    parent_id = :parent
	where id = :node
EOD;
		$stmt = App::db()->prepare($sql);
		$r4 = $stmt->execute([
			':parent' =>(int) $target->id,
			':node' =>(int) $node->id,
		]);

		// use the deltas to move the node and its children to the parent
		$sql = <<<EOD
update {$this->tableName()}
set
    lft = lft + :move_delta_1,
    rgt = rgt + :move_delta_2,
    level = level + :level_delta
where lft >= :left
and rgt <= :right
EOD;
		$stmt = App::db()->prepare($sql);
		$r2 = $stmt->execute([
			':move_delta_1' =>(int) $moveDelta,
			':move_delta_2' =>(int) $moveDelta,
			':left' =>(int) $node->lft,
			':right' =>(int) $node->rgt,
			':level_delta' =>(int) $levelDelta,
		]);

		// remove the leftover space after moving the node
		$r3 = $this->resizeAt($node->rgt, $node->tree_id, -$growth);

		return $r1 && $r2 && $r3 && $r4;
	}


	/**
	 * Трансформирование данных для вывода
	 *
	 * @param array $data
	 * @return array
	 */
	public function transformData($data) {

		return $data;
	}


	public function getTree() {

		$sql = <<<EOD
SELECT *
FROM {$this->tableName()}
ORDER BY lft
EOD;

		$stmt = App::db()->prepare($sql);
		$stmt->execute();
		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return array_map([$this, 'transformData'], $data);
	}
}