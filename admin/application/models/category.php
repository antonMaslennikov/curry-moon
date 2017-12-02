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
	 * Трансформирование данных для вывода
	 *
	 * @param array $data
	 * @return array
	 */
	public function transformData($data) {

		$data['picture_id'] = pictureId2path($data['picture_id']);

		return $data;
	}


	public function update() {

		$sql = <<<EOD
update {$this->tableName()}
set
    slug = :slug,
    title = :title,
    picture_id = :picture_id,
    status = :status

where id = :id
limit 1;
EOD;

		$stmt = App::db()->prepare($sql);
		return $stmt->execute([
			':slug' => $this->slug,
			':title' => $this->title,
			':picture_id'=> $this->picture_id,
			':status' => $this->status,
			':id'=>$this->id,
		]);
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
			':parent_id' => (int) $node->parent_id,
			':left' => (int) $node->lft,
			':right' => (int) $node->rgt,
			':level' => (int) $node->level,
			':slug' => $node->slug,
			':title' => $node->title,
			':picture_id'=> (int) $node->picture_id,
			':status' => $node->status
		]);

		$node->id = App::db()->lastInsertId();

		return $res;
	}
}