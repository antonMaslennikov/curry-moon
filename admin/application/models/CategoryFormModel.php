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
 * @property string $slug
 * @property string $title
 * @property UploadedFile $picture_id
 * @property int $status
 */
class CategoryFormModel extends FormModel {

	protected $newRecord = true;

	public $slug;

	public $title;

	public $picture;

	public $status;


	public function setUpdate() {

		$this->newRecord = false;
	}



	public function getData() {

		$attributes = $this->getAttributes();

		$attributes['status'] = is_null($attributes['status'])?0:1;

		unset($attributes['picture']);

		if (($uploadedFile = UploadedFile::getInstance($this, 'picture')) !== null) {

			File::checkPath(File::uploadedPath());

			$imgPath = File::uploadedPath() . DS. date('His_').$uploadedFile->name;

			$uploadedFile->saveAs($imgPath);

			$attributes['picture_id'] = file2db($imgPath);
		};

		return $attributes;
	}


	public function rules() {

		return [
			[['slug', 'title'], 'required',],
			['status', 'required', 'requiredValue' => 'true', 'allowEmpty'=>true],
			['slug', 'filter', 'filter'=>'mb_strtolower'],
			['slug', 'filter', 'filter'=>'textToTranslit'],
			['slug', 'uniqueSlug', 'allowEmpty'=>false],
			['slug', 'length', 'max'=>100],
			['title', 'length', 'max'=>150],
			['picture', 'file', 'types'=>'jpg, jpeg, gif, png', 'allowEmpty'=>true],
			['newRecord', 'unsafe'],
		];
	}

	public function uniqueSlug($attribute, $params) {

		$r = App::db()->prepare("SELECT id FROM `" . category::getDbTableName() . "` WHERE `slug` = ? LIMIT 1");

		$r->execute([$this->slug]);

		if ($r->rowCount() == 1)
		{
			$this->addError('slug', sprintf('Атрибут "%s" уже существует в категориях!', $this->getAttributeLabel('slug')));
		}
	}


	public function attributeLabels() {

		return [
			'slug'=>'Slug (для URL)',
			'title'=>'Название',
			'picture'=>'Изображение (jpg, gif, png)',
			'status'=>'Статус',
		];
	}
}