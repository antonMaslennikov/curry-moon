<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 01.12.2017
 * Time: 21:43
 */

namespace admin\application\models;

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

	public $newRecord = true;

	public $slug;

	public $title;

	public $picture;

	public $status;


	public function rules() {

		return [
			[['slug', 'title'], 'required',],
			['status', 'required', 'requiredValue' => 'true', 'allowEmpty'=>true],
			['slug', 'filter', 'filter'=>'mb_strtolower'],
			['slug', 'uniqueSlug', 'allowEmpty'=>false],
			['slug', 'length', 'max'=>100],
			['title', 'length', 'max'=>150],
			['picture', 'file', 'types'=>'jpg, gif, png', 'allowEmpty'=>!$this->newRecord],
			['newRecord', 'unsafe'],
		];
	}

	public function uniqueSlug($attribute, $params) {

		//Создать поиск уникальности поля
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