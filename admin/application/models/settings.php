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
class settings extends Model {

	protected static $dbtable = 'variables';

	protected $modified_data = [

	];

	/**
	 * Получение списка страниц
	 *
	 * @return array
	 */
	public function getList() {

		$sql = 'SELECT * FROM '.self::$dbtable.' ORDER BY `id` DESC';

		$stmt = App::db()->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
    
    public function delete() {

		$sql = 'DELETE FROM '.self::$dbtable.' WHERE `id` = ? LIMIT 1';

		$stmt = App::db()->prepare($sql);
		$stmt->execute([$this->id]);

		return true;
	}
    
    public static function saveListValues($data) {
        $sth = App::db()->prepare("UPDATE `" . self::$dbtable . "` SET `variable_value` = ? WHERE `id` = ? LIMIT 1");
        foreach ($data AS $k => $v) {
            $sth->execute([$v, $k]);
        }
    }
}