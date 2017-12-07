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

	const TYPE_TAGS = 'blog';

	protected static $dbtable = 'posts';

	protected $modified_data = [

	];

	use TagsTrait;

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





}