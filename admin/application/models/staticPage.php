<?php
namespace admin\application\models;

use PDO;
use smashEngine\core\App;
use smashEngine\core\Model;

/**
 * Class staticPage
 * @package admin\application\models
 *
 * @property int id
 * @property int status
 * @property string slug
 * @property string h1_ru
 * @property string h1_en
 * @property string text_ru
 * @property string text_en
 * @property string meta_keywords
 * @property string meta_description
 * @property string date
 *
 */
class staticPage extends Model {

	protected static $dbtable = 'static_pages';

	protected $modified_data = [
		'h1_ru' => false,
		'h1_en' => false,
		'text_en' => false,
		'text_ru' => false,
		'title' => false,
		'status' => 'intval',
		'meta_keywords' => false,
		'meta_description' => false,
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


	public function getMenuRoute() {

		$list = $this->getList();

		$menu = [];
		foreach ($list as $page) {

			//if (!$page['status']) continue;

			$title = 'Статическая страница "'.$page['h1_ru'].'"';
			$url = '/(ru|en)/'.$page['slug'];
			$menu[$url] = $title;
		}

		return $menu;
	}


}