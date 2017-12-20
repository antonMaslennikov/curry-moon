<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 01.12.2017
 * Time: 19:31
 */

namespace admin\application\controllers;


use application\models\product;
use admin\application\models\ProductFormModel;
use smashEngine\core\helpers\Html;

class Controller_product extends Controller_ 
{
    protected $layout = 'index.tpl';

	public function action_index()
	{
		$this->setTemplate('product/index.tpl');
		$this->setTitle("Товары");

		$this->setBreadCrumbs([
			'/admin/product/list'=>'<i class="fa fa-fw fa-shopping-bag"></i> Товары',
		]);
        
        if (!$_GET['filter']['orderBy']) {
            $_GET['filter']['orderBy'] = 'status';
            $_GET['filter']['orderDir'] = 'DESC';
        }
        
        $this->view->setVar('products', product::getAll($_GET['filter']));

		$this->render();
	}
    
    public function action_create()
	{
		$this->setTemplate('product/form.tpl');
		$this->setTitle("Добавить товар");

		$this->setBreadCrumbs([
			'/admin/product/list'=>'<i class="fa fa-fw fa-shopping-bag"></i> Товары',
			'/admin/product/create'=>'<i class="fa fa-fw fa-shopping-bag"></i> Добавить новый',
		]);
        
        $product = new product;
        
        $model = new ProductFormModel();
		$postModel = Html::modelName($model);

		if (isset($_POST[$postModel])) {

			$model->setPost($_POST[$postModel]);

			if ($model->validate()) {
                
				$product->setAttributes($model->getData());
                $product->save();
                
                if (!empty($product->picture_temp)) {

	                if (count($product->picture_temp)) {
		                $product->addPictures($product->picture_temp);
	                }

                    $product->picture_id = $product->pictures[0]['thumb_id'];
                    $product->save();
                }
                
				if (isset($_POST['apply'])) {
                    $this->page->refresh();
                } else {
				    $this->page->go('/admin/product/list');
                }
			}
		}

		$this->view->setVar('model', $model->getDataForTemplate());
        $this->view->setVar('manufacturers', product::$manufacturers);

		$this->page->import([
			'/public/packages/select2/css/select2.min.css',
			'/public/packages/select2/js/select2.min.js',
			'/public/packages/select2/js/i18n/ru.js'
		]);
        
		$this->render();
	}

	public function action_list_filter() {

		$product = new product((int) $_GET['id']);

		if (!$product->id) die('Неизвестен продукт...');

		$this->view->setVar('list', $product->related_search($_GET['name']));

		$this->view->generate("product/search.tpl");
		die();
	}


	public function action_list_related() {

		$product = new product((int) $_GET['id']);

		if (!$product->id) die('Неизвестен продукт...');

		$this->view->setVar('listRelated', $product->listProductRelated());

		$this->view->generate("product/list_related.tpl");
		die();
	}


	public function action_set_related() {

		$product = new product((int) $_GET['id']);

		if (!$product->id) die();

		if ((int)$_GET['action']) $product->setRelated((int) $_GET['related']);
		else $product->removeRelated((int) $_GET['related']);
	}


    public function action_update() 
    {
        $product = new product($_GET['id']);
        
        $this->setTemplate('product/form.tpl');
		$this->setTitle('Товар "'.$product->product_name.'"');

        $this->setBreadCrumbs([
	        '/admin/product/list'=>'<i class="fa fa-fw fa-shopping-bag"></i> Товары',
			'/admin/product/create'=>'<i class="fa fa-fw fa-shopping-bag"></i> Редактировать товар',
		]);
        
        $model = new ProductFormModel();
        $model->setAttributes($product->info, false);

		$model->setUpdate();
        
		$postModel = Html::modelName($model);

		if (isset($_POST[$postModel])) {

			$model->setPost($_POST[$postModel]);

			if ($model->validate()) {

				$product->setAttributes($model->getData());

                if (count($product->picture_temp)) {
                    $product->addPictures($product->picture_temp);
                }
                
                // основная картинка ещё не создана, загружается первая картинка
				if (!$product->picture_id && count($product->picture_temp)) {
					$product->picture_id = $product->pictures[0]['thumb_id'];
				}
                
                $product->save();
                
                if (isset($_POST['apply'])) {
                    $this->page->refresh();
                } else {
				    $this->page->go('/admin/product/list');
                }
			}
		}

		$this->view->setVar('model', $model->getDataForTemplate());
        $this->view->setVar('manufacturers', product::$manufacturers);
        $this->view->setVar('product', $product);

	    $this->page->import([
		    '/public/packages/select2/css/select2.min.css',
		    '/public/packages/select2/js/select2.min.js',
		    '/public/packages/select2/js/i18n/ru.js',
	    ]);
        
		$this->render();
	}


	public function action_delete() {

		$product = new product((int) $_GET['id']);

		if ($product->delete()) {

			$this->page->go('/admin/product/list');
		} else {

			throw new Exception('Неизвестная ошибка');
		}
	}



	public function action_mainImage() {

		$thumb_img = (int) $_GET['image'];

		return (new product((int) $_GET['product']))->mainPicture($thumb_img);
	}


	public function action_deleteImage() {

		$thumb_img = (int) $_GET['image'];

		return (new product((int) $_GET['product']))->deletePicture($thumb_img);
	}
}