<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 06.12.2017
 * Time: 23:09
 */

namespace admin\application\models;

use application\models\product;
use smashEngine\core\App;
use smashEngine\core\helpers\File;
use smashEngine\core\helpers\UploadedFile;
use smashEngine\core\models\FormModel;

/**
 * Class PostFormModel
 * @package admin\application\models
 *
 * @property string publish_date
 * @property string slug
 * @property string title
 * @property string content
 * @property string status
 * @property string keywords
 * @property string description
 * @property integer image
 * @property string tags
 */
class PostFormModel extends FormModel {

	protected $all_tags;

	protected $old_slug;

	public $newRecord = true;

	public $publish_date;

	public $slug;

	public $title;

	public $content;

	public $status = 1;

	public $keywords;

	public $description;

	public $image;

	public $image_file;

	public $tags;

	public $category = 0;

	public $lang = 'ru';

	public function rules() {

		return [
			['tags', 'saveNewTags'],

			[['title', 'slug', 'publish_date'], 'required',],

			['status', 'in', 'range'=>array_keys($this->getListStatus())],
			['category', 'in', 'range'=>array_keys($this->getListCategory())],

			['slug', 'safe'],
			['slug', 'uniqueSlug', 'allowEmpty'=>false],
			['slug', 'filter', 'filter'=>'mb_strtolower'],
			['slug', 'filter', 'filter'=>'textToTranslit'],
			['slug', 'length', 'max'=>100],


			[['lang', 'image'], 'unsafe'],

			['image_file', 'file', 'types'=>'jpg, jpeg, gif, png', 'allowEmpty'=>true],

			[['content', 'keywords', 'description'], 'safe'],

			['publish_date', 'dateFormat'],

		];
	}

	public function setUpdate() {

		$this->newRecord = false;

		$this->old_slug = $this->slug;

		$this->publish_date = \DateTime::createFromFormat('Y-m-d', $this->publish_date)->format('d.m.Y');
	}

	protected function getListStatus() {

		return [
			1 => 'Активен',
			0 => 'Не активен',
		];
	}


	protected function getListCategory() {

		return [
			2 => 'Lookbook',
			1 => 'Акция',
			0 => 'Блог',
		];
	}


	public function saveNewTags() {

		$all_tags = $this->getAllTags();

		foreach ($this->tags as $key=> $tag) {

			$tag = trim($tag,  " \t\n\r\0\x0B,");
			if (!isset($all_tags[$tag])) {

				$this->tags[$key] = product::createTag($tag);
				$this->all_tags[$key] = $tag;
			}
		}
	}


	public function uniqueSlug($attribute, $params) {

		if (!$this->slug) $this->slug = toTranslit($this->title);

		if (!$this->newRecord) {

			if ($this->slug == $this->old_slug)	 return;
		}

		$r = App::db()->prepare("SELECT id FROM `" . post::getDbTableName() . "` WHERE `slug` = ? AND `lang` = ? LIMIT 1");

		$r->execute([$this->slug, $this->lang]);

		if ($r->rowCount() == 1)
		{
			$this->addError('slug', sprintf('Атрибут "%s" уже существует в записях!', $this->getAttributeLabel('slug')));
		}
	}


	protected function getAllTags() {

		if($this->all_tags === null) {

			$this->all_tags = post::getAllTags();

		}

		return $this->all_tags;
	}


	public function dateFormat($attribute, $params) {

		if ($this->getErrorSummary()) return;

		if (!$this->publish_date) return;

		$this->publish_date = \DateTime::createFromFormat('d.m.Y', $this->publish_date)->format('Y-m-d');
	}


	public function attributeLabels() {

		return [
			'publish_date'=>'Дата публикации',
			'slug'=>'URL',
			'title'=>'Заголовок',
			'content'=>'Содержимое',
			'status'=>'Статус',
			'keywords'=>'META ключевые слова',
			'description'=>'META описания',
			'image_file'=>'Изображение',
			'category'=>'Категория',
			'tags'=>'Теги',
		];
	}


	public function getDataForTemplate() {

		$data = parent::getDataForTemplate();

		$data['listStatus'] = $this->getListStatus();

		$data['listCategory'] = $this->getListCategory();

		$data['tags'] = ($this->newRecord)?[]:$data['tags'];

		$data['listAllTags'] = $this->getAllTags();

		return $data;
	}


	public function getData() {

		$attributes = $this->getAttributes();

		unset($attributes['image_file']);

		if (($uploadedFile = UploadedFile::getInstance($this, 'image_file')) !== null) {

			File::checkPath(File::uploadedPath());

			$imgPath = File::uploadedPath() . DS. date('His_').$uploadedFile->name;

			$uploadedFile->saveAs($imgPath);

			if ($attributes['image']) {

				File::deletePicture($attributes['image']);
			}

			$attributes['image'] = file2db(File::getUrlForAbsolutePath($imgPath));
		};

		return $attributes;
	}
}