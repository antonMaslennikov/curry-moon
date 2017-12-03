<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 01.12.2017
 * Time: 21:43
 */

namespace admin\application\models;

use smashEngine\core\App;
use smashEngine\core\helpers\File;
use smashEngine\core\helpers\UploadedFile;
use smashEngine\core\models\FormModel;

/**
 * Class CategoryFormModel
 * @package admin\application\models
 *
 */
class CategoryFormModel extends FormModel {

	private $_listNode = null;

	protected $old_slug;

	public $id;

	public $newTree = false;

	public $newRecord = true;

	public $picture_id;

	public $slug;

	public $parent_id;

	public $title;

	public $picture;

	public $status;

	public $description;

	public $meta_keywords;

	public $meta_description;


	public function setUpdate() {

		$this->newRecord = false;

		$this->old_slug = $this->slug;
	}


	public function getData() {

		$attributes = $this->getAttributes();

		unset($attributes['picture']);

		if (($uploadedFile = UploadedFile::getInstance($this, 'picture')) !== null) {

			File::checkPath(File::uploadedPath());

			$imgPath = File::uploadedPath() . DS. date('His_').$uploadedFile->name;

			$uploadedFile->saveAs($imgPath);

			if ($attributes['picture_id']) {

				File::deletePicture($attributes['picture_id']);
			}

			$attributes['picture_id'] = file2db(File::getUrlForAbsolutePath($imgPath));
		};

		return $attributes;
	}


	public function rules() {

		return [
			[['title'], 'required',],

			['status', 'in', 'range'=>array_keys($this->getListStatus())],

			['parent_id', 'in', 'range'=>array_keys($this->getListNode()), 'allowEmpty'=>$this->newTree],

			['slug', 'safe'],
			['slug', 'uniqueSlug', 'allowEmpty'=>false],
			['slug', 'filter', 'filter'=>'mb_strtolower'],
			['slug', 'filter', 'filter'=>'textToTranslit'],
			['slug', 'length', 'max'=>100],

			['title', 'length', 'max'=>150],
			['picture', 'file', 'types'=>'jpg, jpeg, gif, png', 'allowEmpty'=>true],

			[['newRecord', 'newTree'], 'unsafe'],

			[['description', 'meta_keywords', 'meta_description'], 'safe'],
		];
	}

	public function uniqueSlug($attribute, $params) {

		if (!$this->slug) $this->slug = toTranslit($this->title);

		if (!$this->newRecord) {

			if ($this->slug == $this->old_slug)	 return;
		}

		$r = App::db()->prepare("SELECT id FROM `" . category::getDbTableName() . "` WHERE `slug` = ? LIMIT 1");

		$r->execute([$this->slug]);

		if ($r->rowCount() == 1)
		{
			$this->addError('slug', sprintf('Атрибут "%s" уже существует в категориях!', $this->getAttributeLabel('slug')));
		}
	}


	protected function getListNode() {

		if ($this->_listNode === null) {

			$category = new category();
			$this->_listNode = $category->getList(!$this->newRecord?$this->id:0);
		}

		return $this->_listNode;
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
			'title'=>'Название',
			'picture'=>'Изображение (jpg, gif, png)',
			'parent_id'=>'Родительская категория',
			'status'=>'Статус',
			'description'=>'Описание',
			'meta_keywords'=>'Meta ключевые слова',
			'meta_description'=>'Meta описание',
		];
	}


	public function getDataForTemplate() {

		$data = parent::getDataForTemplate();

		$data['listNode'] = $this->getListNode();
		$data['listStatus'] = $this->getListStatus();

		return $data;
	}
}