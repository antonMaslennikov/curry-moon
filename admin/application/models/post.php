<?php
namespace admin\application\models;

use PDO;
use smashEngine\core\App;
use smashEngine\core\Model;

/**
 * Class post
 * @package admin\application\models
 *
 * @property date publish_date
 * @property string slug
 * @property string title
 * @property string content
 * @property string status
 * @property string keywords
 * @property string description
 * @property integer image
 * @property string tags
 */
class post extends Model {

	protected static $dbtable = 'posts';

	protected static $tag_db_table = 'tags';

	protected static $relation_db_table = 'tags_relation';

	protected $modified_data = [

	];

	/**
	 * Получение списка страниц
	 *
	 * @return array
	 */
	public function getList() {

		$sql = 'SELECT * FROM '.self::db().' ORDER BY publish_date';

		$stmt = App::db()->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function save() {

		parent::save();

		$currentTags = self::getTags($this->id);

		printr($this->values, 1);
	}



	public static function createTag($tag) {

		App::db()->query(sprintf("INSERT INTO `%s` (`name`) VALUES ('%s')", self::$tag_db_table, $tag));

		return App::db()->lastInsertId();
	}


	public static function getTags($post_id) {

		App::db()->query(sprintf("INSERT INTO `%s` (`name`) VALUES ('%s')", self::$tag_db_table, $tag));

		return App::db()->lastInsertId();
	}


	public static function getAllTags() {

		$sql = 'SELECT * FROM '.self::$tag_db_table.' ORDER BY id';

		$stmt = App::db()->prepare($sql);
		$stmt->execute();

		$temp = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$data = [];

		foreach ($temp as $v) {
			$data[$v['id']] = $v['name'];
		}

		return $data;

	}
}