<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 04.12.2017
 * Time: 20:14
 */

namespace admin\application\models;


use PDO;
use smashEngine\core\App;
use smashEngine\core\Model;

/**
 * Class menu
 * @package admin\application\models
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property int $status
 */
class menu extends Model{

	protected static $dbtable = 'menu';

	protected $modified_data = [
		'name' => false,
		'slug' => false,
		'description' => false,
		'status' => 'intval',
	];


	/**
	 * Получение списка страниц
	 *
	 * @return array
	 */
	public function getList() {

		$sql = 'SELECT * FROM '.self::$dbtable.' ORDER BY id';

		$stmt = App::db()->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}