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
 * Class ProductFormModel
 * @package admin\application\models
 *
 * @property string $slug
 * @property string $title
 * @property UploadedFile $picture_id
 * @property int $status
 */
class ProductFormModel extends FormModel {

	public $newRecord = true;

	public $picture_id;
	public $slug;
	public $product_name;
	public $picture;
	public $status;
    public $product_width;
    public $product_height;
    public $product_length;


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

			if ($attributes['picture_id']) {

				File::deletePicture($attributes['picture_id']);
			}

			$attributes['picture_id'] = file2db(File::getUrlForAbsolutePath($imgPath));
		};

		return $attributes;
	}


	public function setPost($data) {

		printr($data, 1);
	}


	public function rules() {

		return [
			[['product_name', 'slug'], 'required',],
			['status', 'required', 'requiredValue' => 'true', 'allowEmpty'=>true],
			['slug', 'filter', 'filter'=>'mb_strtolower'],
			['slug', 'filter', 'filter'=>'textToTranslit'],
			['slug', 'uniqueSlug', 'allowEmpty'=>false],
			['slug', 'length', 'max'=>150],
			['product_name', 'length', 'max'=>255],
			['picture', 'file', 'types'=>'jpg, jpeg, gif, png', 'allowEmpty'=>true],
			['newRecord', 'unsafe'],
		];
	}

	public function uniqueSlug($attribute, $params) {

		if (!$this->newRecord) return;

		$r = App::db()->prepare("SELECT id FROM `" . product::getDbTableName() . "` WHERE `slug` = ? LIMIT 1");

		$r->execute([$this->slug]);

		if ($r->rowCount() == 1)
		{
			$this->addError('slug', sprintf('Продукт с таким "%s" уже существует!', $this->getAttributeLabel('slug')));
		}
	}


	public function attributeLabels() {

		return [
			'slug'=>'Slug (для URL)',
			'product_name'=>'Название',
			'picture'=>'Изображение (jpg, gif, png)',
			'status'=>'Статус',
            'product_sku' => 'Артикул',
            'category' => 'Категория товаров',
            'quantity' => 'Количество на складе',
            'product_price' => 'Цена',
            'product_discount' => 'Скидка',
            'manufacturer' => 'Производитель',
            'description_short' => 'Короткое описание',
            'description_long' => 'Полное описание продукта',
            'meta_keywords' => 'META ключевые слова',
            'meta_description' => 'META описание',
            'product_width' => 'Ширина',
            'product_height' => 'Высота',
            'product_length' => 'Длина',
            'product_weight' => 'Вес',
		];
	}
}