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
 * @property string $description
 * @property string $meta_keywords
 * @property string $meta_description
 */
class category extends \application\models\category {

	/**
	 * @var имя таблицы в БД для хранения экземпляров класса
	 */
	protected static $dbtable = 'categorys';

	protected $modified_data = [
		'slug' => false,
        'title' => false,
        'picture_id' => 'intval',
        'status' => 'intval',
        'description' => false,
        'meta_keywords' => false,
        'meta_description' => false,
	];


	public function getList($id = 0) {

		$tree = [];
		foreach ($this->getTree() as $node) {

			$tree[$node['id']] = $node['title'];
		}

		if ($id) {

			foreach ($this->getAllChildren($id) as $node) {

				unset($tree[$node['id']]);
			}

			unset($tree[$id]);
		}

		return $tree;
	}


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

		$this->id = App()->db()->lastInsertId();

		return $this->insertNode($class);
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


	public function updateMove() {

		if ($this->update()) {

			return $this->moveNode($this->id, $this->parent_id);

		}

		return false;
	}


	/**
	 * @param category $node
	 *
	 * @return bool
	 */
	protected function insertNode(NSModel $node)
	{
		$sql = <<<EOD
insert into {$this->tableName()}
	( slug, title, picture_id, status, parent_id, lft, rgt, level, description, meta_keywords, meta_description)
values
    (:slug, :title, :picture_id, :status, :parent_id, :left, :right, :level, :description, :meta_keywords, :meta_description)
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
			':status' => $node->status,
			':description' => $this->description,
			':meta_keywords' => $this->meta_keywords,
			':meta_description' => $this->meta_description,
		]);

		$node->id = App::db()->lastInsertId();

		return $res;
	}
}