<?php

namespace admin\application\models;
use smashEngine\core\models\NSModel;
use smashEngine\core\App;

/**
 * Class category
 * @package admin\application\models
 *
 * @property int $id ID
 * @property int $parent_id
 * @property int $level
 * @property int $lft
 * @property int $rft
 * @property string $slug
 * @property string $title
 * @property int $picture_id
 * @property int $status
 */
class category extends \smashEngine\core\models\NSModel {

	/**
	 * @var имя таблицы в БД для хранения экземпляров класса
	 */
	public static $dbtable = 'categorys';


	public function createTree() {

		$className = get_called_class();

		/** @var category $class */
		$class = new $className;
		$class->setAttributes($this->info);
		$class->setAttributes([
			'lft' => self::INITIAL_LEFT,
			'rgt' => self::INITIAL_RIGHT,
			'level' => self::INITIAL_DEPTH,
			'parent_id'=>0,
		]);

		return $this->insertNode($class);
	}


	/**
	 * @param category $node
	 *
	 * @return bool
	 */
	protected function insertNode(NSModel $node)
	{
		$sql = <<<EOD
insert into {$this->tableName()} (slug, title, picture_id, status, parent_id, lft, rgt, level)
values (:slug, :title, :picture_id, :status, :parent_id, :left, :right, :level)
EOD;
		$stmt = App::db()->prepare($sql);
		$res = $stmt->execute([
			':parent_id' => $node->parent_id,
			':left' => $node->lft,
			':right' => $node->rgt,
			':level' => $node->level,
			':slug' => $node->slug,
			':title' => $node->title,
			':picture_id'=> $node->picture_id,
			':status' => $node->status
		]);

		$node->id = App::db()->lastInsertId();

		return $res;
	}
}