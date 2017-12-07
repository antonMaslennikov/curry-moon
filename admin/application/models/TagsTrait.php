<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 07.12.2017
 * Time: 21:08
 */

namespace admin\application\models;


use PDO;
use smashEngine\core\App;

trait TagsTrait {

	protected static $tag_db_table = 'tags';

	protected static $relation_db_table = 'tags_relation';

	public function save() {

		parent::save();

		self::setTags($this->id, $this->tags);
	}

	public function __construct($id = null)
	{
		parent::__construct($id);

		$this->info['tags'] = self::getTags($this->id);
	}

	public function delete() {

		parent::delete();

		self::deleteTags($this->id);

		return true;
	}

	public static function createTag($tag) {

		App::db()->query(sprintf("INSERT IGNORE INTO `%s` (`name`) VALUES ('%s')", self::$tag_db_table, $tag));

		return App::db()->lastInsertId();
	}

	public static function deleteTags($post_id) {

		App::db()->prepare('DELETE FROM '.self::$relation_db_table.' WHERE post_id = :post AND `type` = :t')
			->execute([':post'=>$post_id, ':t'=>self::TYPE_TAGS]);
	}


	public static function setTags($post_id, $tags = []) {

		self::deleteTags($post_id);

		$smtp = App::db()->prepare('INSERT INTO '.self::$relation_db_table.' (`post_id`, `tag_id`, `type`) VALUES (:post, :tag_id, :t)');


		foreach ($tags as $tag) {

			$smtp->execute([
				':post'=>(int) $post_id,
				':tag_id'=>(int) $tag,
				':t'=>self::TYPE_TAGS
			]);
		}
	}


	public static function getTags($id) {

		$smtp = App::db()->prepare('SELECT tag_id FROM '.self::$relation_db_table.' WHERE post_id = :post AND `type` = :t');

		$smtp->execute([':post'=>(int) $id, ':t'=>self::TYPE_TAGS]);

		$temp = $smtp->fetchAll(PDO::FETCH_ASSOC);

		$data = [];

		foreach ($temp as $v) {
			$data[] = $v['tag_id'];
		}

		return $data;
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