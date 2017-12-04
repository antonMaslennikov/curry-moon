<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 04.12.2017
 * Time: 20:25
 */

namespace admin\application\models;

use smashEngine\core\App;
use smashEngine\core\models\FormModel;


class MenuItemFormModel extends FormModel{

	protected $old_url;

	private $_menuList = [];

	public $id;

	public $newRecord = true;

	public $title_ru;

	public $title_en;

	public $url;

	public $menu_id;

	public $sort;

	public $status;

	public function setUpdate() {

		$this->newRecord = false;

		$this->old_url = $this->url;
	}


	public function getData() {

		return $this->getAttributes();
	}


	public function rules() {

		return [
			[['title_ru', 'menu_id', 'url'], 'required',],

			['status', 'in', 'range'=>array_keys($this->getListStatus())],

			['menu_id', 'in', 'range'=>array_keys($this->getListMenu())],

			['url', 'uniqueUrl', 'allowEmpty'=>false],

			[['title_ru', 'title_en'], 'length', 'max'=>100],
			[['url'], 'length', 'max'=>200],

			['newRecord', 'unsafe'],

			[['sort'], 'getSort', 'allowEmpty'=>false],
		];
	}


	public function uniqueUrl($attribute, $params) {

		if (!$this->url) $this->url = '/';

		if (!$this->newRecord) {

			if ($this->url == $this->old_url)	 return;
		}

		$r = App::db()->prepare("SELECT id FROM `" . menuItem::getDbTableName() . "` WHERE `url` = ? LIMIT 1");

		$r->execute([$this->url]);

		if ($r->rowCount() == 1)
		{
			$this->addError('slug', sprintf('Атрибут "%s" уже существует в пунктах меню!', $this->getAttributeLabel('url')));
		}
	}


	public function getSort($attribute, $params) {

		if ($this->sort) return;

		$r = App::db()->prepare("SELECT MAX(sort) as max_sort FROM `" . menuItem::getDbTableName() . "` WHERE `menu_id` = ? AND LIMIT 1");

		$r->execute([$this->menu_id]);

		if ($data = $r->fetch()) {

			$sort = (int)$data['max_sort'];
		} else {

			$sort = 0;
		}

		$this->sort = $sort + 100 - ($sort % 100);
	}


	public function setListMenu($menu) {

		foreach ($menu as $item) {

			$this->_menuList[$item['id']] = $item['name'];
		}
	}


	protected function getListStatus() {

		return [
			1 => 'Активен',
			0 => 'Не активен',
		];
	}


	protected function getListMenu() {

		return $this->_menuList;
	}


	public function getDataForTemplate() {

		$data = parent::getDataForTemplate();

		$data['listStatus'] = $this->getListStatus();
		$data['listMenu'] = $this->getListMenu();

		return $data;
	}


	public function attributeLabels() {

		return [
			'title_ru'=>'Заголовок (ru)',
			'title_en'=>'Заголовок (en)',
			'url'=>'Aдрес',
			'menu_id'=>'Меню',
			'sort'=>'Сортировка',
			'status'=>'Статус',
		];
	}
}