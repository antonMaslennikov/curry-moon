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


class MenuFormModel extends FormModel{

	protected $old_slug;

	public $id;

	public $newRecord = true;

	public $name;

	public $slug;

	public $description;

	public $status;


	public function setUpdate() {

		$this->newRecord = false;

		$this->old_slug = $this->slug;
	}


	public function getData() {

		return $this->getAttributes();
	}


	public function rules() {

		return [
			['name', 'required',],

			['status', 'in', 'range'=>array_keys($this->getListStatus())],

			['slug', 'safe'],
			['slug', 'uniqueSlug', 'allowEmpty'=>false],
			['slug', 'filter', 'filter'=>'mb_strtolower'],
			['slug', 'filter', 'filter'=>'toTranslit'],

			[['name', 'slug'], 'length', 'max'=>100],

			['newRecord', 'unsafe'],

			[['description'], 'safe'],
		];
	}


	public function uniqueSlug($attribute, $params) {

		if (!$this->slug) $this->slug = toTranslit($this->title);

		if (!$this->newRecord) {

			if ($this->slug == $this->old_slug)	 return;
		}

		$r = App::db()->prepare("SELECT id FROM `" . menu::getDbTableName() . "` WHERE `slug` = ? LIMIT 1");

		$r->execute([$this->slug]);

		if ($r->rowCount() == 1)
		{
			$this->addError('slug', sprintf('Атрибут "%s" уже существует в меню!', $this->getAttributeLabel('slug')));
		}
	}


	protected function getListStatus() {

		return [
			1 => 'Опубликован',
			0 => 'Черновик',
		];
	}


	public function attributeLabels() {

		return [
			'slug'=>'Псевдоним',
			'name'=>'Название',
			'description'=>'Описание',
			'status'=>'Статус',
		];
	}


	public function getDataForTemplate() {

		$data = parent::getDataForTemplate();

		$data['listStatus'] = $this->getListStatus();

		return $data;
	}
}