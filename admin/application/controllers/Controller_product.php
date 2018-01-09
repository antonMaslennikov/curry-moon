<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 01.12.2017
 * Time: 19:31
 */

namespace admin\application\controllers;


use admin\application\models\product as adminProduct;
use application\models\product;
use application\models\productOption;
use admin\application\models\ProductFormModel;
use smashEngine\core\helpers\Html;
use smashEngine\core\App;

class Controller_product extends Controller_ 
{
    protected $layout = 'index.tpl';

    protected function setSortValue() {

        $move = $_GET['moveTo'];
        $id = key($move);
        $value = array_shift($move);

        switch ($value) {

            case 'up':

                (new adminProduct($id))->setUpSorting();

                break;

            case 'down':

                (new adminProduct($id))->setDownSorting();

                break;

            case 'value':

                if (!empty($_GET['move'][$id])) {

                    (new adminProduct($id))->setValueSorting((int) $_GET['move'][$id]);
                }
                break;
        }

        unset($_GET['moveTo']);
        unset($_GET['move']);

        $link = '/admin/product/list?'.http_build_query($_GET, 'flags_');

        $this->page->go($link);

        die();
    }

	public function action_index()
	{
	    //Раскоментировать для установки значений сортировок
	    //printr((new adminProduct())->updateAllSorting(), 1);

        if (isset($_GET['moveTo'])) {

            $this->setSortValue();
            //printr($_GET, 1);
        }

		$this->setTemplate('product/index.tpl');
		$this->setTitle("Товары");

		$this->setBreadCrumbs([
			'/admin/product/list'=>'<i class="fa fa-fw fa-shopping-bag"></i> Товары',
		]);
        
        if (!$_GET['filter']['orderBy']) {
            $_GET['filter']['orderBy'] = 'status';
            $_GET['filter']['orderDir'] = 'DESC';
        }

		$this->view->setVar('products', (new adminProduct())->getList($_GET['filter']));

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
        
        $product = new adminProduct();
        
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

                    $product->sorting = $product->setNewSorting();

                    $product->save();
                }
                
				if (isset($_POST['apply'])) {
                    $this->page->go('/admin/product/update?id=' . $product->id);
                } else {
				    $this->page->go('/admin/product/list?filter[categoryfull]=' . $product->category);
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
        $product = new adminProduct($_GET['id']);
        
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

				if ($model->old_category != $product->category) {

                    $product->sorting = $product->setNewSorting();
                }

                $product->save();

                $product->fixSorting($model->old_category);

                foreach ($_POST['options'] AS $o => $v) {
                    if (!empty($o) && !empty($v)) {
                        $opt = new productOption;
                        $opt->product_id = $product->id;
                        $opt->option = $o;
                        $opt->value = $v;
                        $opt->save();
                    }
                }
                
                if (isset($_POST['apply'])) {
                    $this->page->refresh();
                } else {
				    $this->page->go('/admin/product/list?filter[categoryfull]=' . $product->category);
                }
			}
		}
        
		$this->view->setVar('model', $model->getDataForTemplate());
        $this->view->setVar('manufacturers', product::$manufacturers);
        $this->view->setVar('product', $product);
        $this->view->setVar('productOptions', $product->getOptions());

	    $this->page->import([
		    '/public/packages/select2/css/select2.min.css',
		    '/public/packages/select2/js/select2.min.js',
		    '/public/packages/select2/js/i18n/ru.js',
	    ]);
        
		$this->render();
	}


	public function action_delete() {

		$product = new adminProduct((int) $_GET['id']);

		if ($product->delete()) {

		    $product->fixSorting($product->category);

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
    
    public function action_save_pic_data() {
        
        $sth = App::db()->prepare("UPDATE " . product::$dbtable_pictures . " 
            SET 
                `alt` = :alt,
                `title` = :title
            WHERE 
                `id` = :id
            LIMIT 1");
        
        $sth->execute([
           'alt' => $_POST['alt'],
           'title' => $_POST['title'],
           'id' => $_POST['id'],
        ]);
        
        if (!$this->page->isAjax) {
            $this->page->refresh();
        }
        
        exit();
    }
}