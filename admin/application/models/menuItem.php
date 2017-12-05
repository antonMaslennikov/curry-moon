<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 04.12.2017
 * Time: 21:58
 */

namespace admin\application\models;

use PDO;
use smashEngine\core\App;
use smashEngine\core\Model;

/**
 * Class menuItem
 * @package admin\application\models
 *
 * @property int $id
 * @property string $title_ru
 * @property string $title_en
 * @property string $url
 * @property int $menu_id
 * @property int $sort
 * @property int $status
 */
class menuItem extends Model {

	protected static $dbtable = 'menu_item';

	protected $modified_data = [
		'title_ru' => false,
		'title_en' => false,
		'url' => false,
		'menu_id' => 'intval',
		'sort' => 'intval',
		'status' => 'intval',
	];


	/**
	 * Получение списка страниц
	 *
	 * @return array
	 */
	public function getList($menu, $menu_id = 0) {

		$menuList =$menu->getList();

		if (!count($menuList)) return [];

		if ($menu_id === 0 || !isset($menuList[$menu_id]) ) {

			$menu_id = $menu->getMinID();
		}

		$sql = 'SELECT * FROM '.self::$dbtable.' WHERE menu_id = '.$menu_id.' ORDER BY `sort`';

		$stmt = App::db()->prepare($sql);
		$stmt->execute();

		$data = [
			'menu'=>$menuList,
			'list'=>$stmt->fetchAll(PDO::FETCH_ASSOC),
			'menu_id'=>$menu_id,
		];

		return $data;
	}
}