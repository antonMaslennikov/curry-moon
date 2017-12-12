<?php
namespace application\models;

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

	use \admin\application\models\TagsTrait;

	/**
	 * Получение списка страниц
	 *
	 * @return array
	 */
	public function getList($params) {

		$sql = 'SELECT t.*, p.`picture_path` 
                FROM '.self::$dbtable.' t, `' . picture::$dbtable . '` p
                WHERE p.`picture_id` = t.`image`
                    '
                    .
                    (in_array($params['lang'], ['ru', 'en']) ? " AND `lang` = '" . $params['lang'] . "'" : '')
                    .
                    ($params['status'] ? " AND `status` = '" . (int) $params['status'] . "'" : '')
                    .
                    ($params['is_special'] ? " AND `is_special` = '" . (int) $params['is_special'] . "'" : '')
                    .
                    '
                ORDER BY ' . ($params['orderby'] ? $params['orderby'] : 'publish_date');

		$stmt = App::db()->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

}