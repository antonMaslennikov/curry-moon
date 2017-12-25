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

	public $id;
    protected $old_slug;
	protected $all_tags;
	public $old_category;
    
	public $picture_id;
    public $picture_temp;
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
	public $status = 1;
    public $product_width;
    public $product_height;
    public $product_length;
    public $product_weight;

	protected $productModel;

	public $tags;

	public function setUpdate() {

		$this->newRecord = false;
        
		$this->old_slug = $this->slug;

		$this->product_discount = ($this->product_discount)?$this->setDiscount($this->product_price, $this->product_discount):'';

		$this->productModel = new product($this->id);

		$this->old_category = $this->category;
	}

	protected function setDiscount($price, $discount) {

		return round($price - $price*$discount/100, 2);
	}

	protected function getDiscount($price, $discount) {

		return round((1-$discount/$price)*100, 6);
	}

    public function getData() {

		$attributes = $this->getAttributes();

		$attributes['status'] = empty($attributes['status'])?0:1;

	    if ($attributes['product_price'] && $attributes['product_discount']) {

			$attributes['product_discount'] = $this->getDiscount($attributes['product_price'], $attributes['product_discount']);
	    }

	    unset($attributes['id']);

		unset($attributes['picture']);



	    $pictures = UploadedFile::getInstances($this, 'picture');

	    if (count($pictures)) {

		    File::checkPath(File::uploadedPath());

		    foreach (UploadedFile::getInstances($this, 'picture') as $instance) {

			    $imgPath = File::uploadedPath() . DS. date('His_').$instance->name;
			    $instance->saveAs($imgPath);

			    $attributes['picture_temp'][] = file2db(File::getUrlForAbsolutePath($imgPath));
			}
	    }

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
			['tags', 'saveNewTags'],

			[['product_name'], 'required',],

			['status', 'in', 'range'=>array_keys($this->getListStatus())],

			['category', 'in', 'range'=>array_keys($this->getListCategory())],

			['slug', 'filter', 'filter'=>'mb_strtolower'],
			['slug', 'filter', 'filter'=>'textToTranslit'],
			['slug', 'uniqueSlug', 'allowEmpty'=>false],
			['slug', 'length', 'max'=>150],

			['product_name', 'length', 'max'=>255],

			['product_related', 'safe'],

			['picture', 'file', 'types'=>'jpg, jpeg, gif, png', 'allowEmpty'=>true, 'maxFiles'=>5],
			['newRecord, id', 'unsafe'],
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


	protected function getListStatus() {

		return [
			1 => 'Активен',
			0 => 'Не активен',
		];
	}


	protected function getAllTags() {

		if($this->all_tags === null) {

			$this->all_tags = post::getAllTags();
		}

		return $this->all_tags;
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
            'product_discount' => 'Цена со скидкой',
            'manufacturer' => 'Производитель',
            'description_short' => 'Короткое описание',
            'description_long' => 'Полное описание продукта',
            'meta_keywords' => 'META ключевые слова',
            'meta_description' => 'META описание',
            'product_width' => 'Ширина',
            'product_height' => 'Высота',
            'product_length' => 'Длина',
            'product_weight' => 'Вес',
			'tags' => 'Теги',
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

		$data['listStatus'] = $this->getListStatus();

		$data['tags'] = ($this->newRecord)?[]:$data['tags'];

		$data['listAllTags'] = $this->getAllTags();

		if (!$this->newRecord) {

			$data['listRelated'] = $this->productModel->listProductRelated();
		}

		return $data;
	}


	protected function setListCategory() {

		$this->_listCategory =  (new category())->getList();
	}
}