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

	private $_listCategory = null;

	protected $id;
    protected $old_slug;
    
	public $picture_id;
    public $picture;
	public $slug;
	public $product_name;
    public $product_sku;
    public $category;
    public $quantity;
    public $product_price;
    public $product_discount;
    public $manufacturer;
    public $description_short;
    public $description_long;
    public $meta_keywords;
    public $meta_description;
	public $status;
    public $product_width;
    public $product_height;
    public $product_length;
    public $product_weight;
   
	public function setUpdate() {
		$this->newRecord = false;
        
		$this->old_slug = $this->slug;
	}

    public function getData() {

		$attributes = $this->getAttributes();

		$attributes['status'] = empty($attributes['status'])?0:1;

		unset($attributes['picture']);

		if (($uploadedFile = UploadedFile::getInstance($this, 'picture')) !== null) {

			File::checkPath(File::uploadedPath());

			$imgPath = File::uploadedPath() . DS. date('His_').$uploadedFile->name;

			$uploadedFile->saveAs($imgPath);

			$attributes['picture_id'] = file2db(File::getUrlForAbsolutePath($imgPath));
		};

		return $attributes;
	}


	public function setPost($data) {
		if (!isset($data['status'])) {
			$data['status'] = 0;
		}

		$this->setAttributes($data, false);
	}


	public function rules() {

		return [
			[['product_name'], 'required',],
			['status', 'required', 'requiredValue' => 'true', 'allowEmpty'=>true],

			['category', 'in', 'range'=>array_keys($this->getListCategory())],

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

		if (!$this->slug) $this->slug = toTranslit($this->product_name);

        if (!$this->newRecord) {

			if ($this->slug == $this->old_slug)	 return;
		}
        
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


	protected function getListCategory() {

		if ($this->_listCategory === null) {

			$this->setListCategory();
		}

		return $this->_listCategory;
	}


	public function getDataForTemplate() {

		$data = parent::getDataForTemplate();

		$data['listCategory'] = $this->getListCategory();

		return $data;
	}


	protected function setListCategory() {

		$this->_listCategory =  (new category())->getList();
	}
}