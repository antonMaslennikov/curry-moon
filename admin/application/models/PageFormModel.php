<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 04.12.2017
 * Time: 0:52
 */

namespace admin\application\models;

use smashEngine\core\App;
use smashEngine\core\models\FormModel;

/**
 * Class PageFormModel
 * @package admin\application\models
 *
 * @property string slug
 * @property string h1_ru
 * @property string h1_en
 * @property string text_ru
 * @property string text_en
 * @property string meta_keywords
 * @property string meta_description
 */
class PageFormModel extends FormModel{

	protected $old_slug;

	protected $old_status;

	public $id;

	public $newRecord = true;

	public $menu;

	public $h1_ru;

	public $h1_en;

	public $slug;

	public $text_ru;

	public $text_en;

	public $meta_keywords;

	public $meta_description;

	public $status;


	public function setUpdate() {

		$this->newRecord = false;

		$this->old_slug = $this->slug;
	}


	public function getOldSlug() {

		return $this->old_slug;
	}


	public function getOldStatus() {

		return $this->old_slug;
	}


	public function getData() {

		return $this->getAttributes();
	}


	public function rules() {

		return [
			['h1_ru', 'required',],

			['status', 'in', 'range'=>array_keys($this->getListStatus())],

			['slug', 'safe'],
			['slug', 'uniqueSlug', 'allowEmpty'=>false],
			['slug', 'filter', 'filter'=>'mb_strtolower'],
			['slug', 'filter', 'filter'=>'textToTranslit'],

			[['h1_ru', 'h1_en', 'slug'], 'length', 'max'=>100],

			['newRecord', 'unsafe'],

			[['text_ru', 'text_en', 'description', 'meta_keywords', 'meta_description'], 'safe'],
		];
	}


	public function uniqueSlug($attribute, $params) {

		if (!$this->slug) $this->slug = toTranslit($this->title);

		if (!$this->newRecord) {

			if ($this->slug == $this->old_slug)	 return;
		}

		$r = App::db()->prepare("SELECT id FROM `" . staticPage::getDbTableName() . "` WHERE `slug` = ? LIMIT 1");

		$r->execute([$this->slug]);

		if ($r->rowCount() == 1)
		{
			$this->addError('slug', sprintf('Атрибут "%s" уже существует в статических страницах!', $this->getAttributeLabel('slug')));
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
			'menu'=>'Меню',
			'slug'=>'URL',
			'h1_ru'=>'Заголовок (ru)',
			'h1_en'=>'Заголовок (en)',
			'text_ru'=>'Текст (ru)',
			'text_en'=>'Текст (en)',
			'status'=>'Статус',
			'meta_keywords'=>'Meta ключевые слова',
			'meta_description'=>'Meta описание',
		];
	}

	public function getMenu() {


	}


	public function getDataForTemplate() {

		$data = parent::getDataForTemplate();

		$data['listStatus'] = $this->getListStatus();

		return $data;
	}
}