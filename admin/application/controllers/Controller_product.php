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
        
        $this->view->setVar('products', product::getAll());

		$this->render();
	}
    
    public function action_create()
	{
		$this->setTemplate('product/form.tpl');
		$this->setTitle("Добавить товар");

		$this->setBreadCrumbs([
			'/admin/product/list'=>'<i class="fa fa-fw fa-shopping-bag"></i> Товары',
		]);
        
        $this->setBreadCrumbs([
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
                
                if (!empty($product->picture_id)) {
                    $product->appPicture($product->picture_id);
                }
                
				$this->page->go('/admin/product/list');
			}
		}

		$this->view->setVar('model', $model->getDataForTemplate());
        $this->view->setVar('manufacturers', product::$manufacturers);
        
		$this->render();
	}
    
    public function action_update() 
    {
        $product = new product($_GET['id']);
        
        $this->setTemplate('product/form.tpl');
		$this->setTitle("Добавить товар");

		$this->setBreadCrumbs([
			'/admin/product/list'=>'<i class="fa fa-fw fa-shopping-bag"></i> Товары',
		]);
        
        $this->setBreadCrumbs([
			'/admin/product/create'=>'<i class="fa fa-fw fa-shopping-bag"></i> Редактировать товар',
		]);
        
        $model = new ProductFormModel();
        $model->setAttributes($product->info, false);
		$model->setUpdate();
        
		$postModel = Html::modelName($model);

		if (isset($_POST[$postModel])) {

			$model->setPost($_POST[$postModel]);

			if ($model->validate()) {

				$old_picture = $product->picture_id;
				$new_picture = 0;

				$product->setAttributes($model->getData());

				if ($product->picture_id && $old_picture) {

					$new_picture = $product->picture_id;
					$product->picture_id = $old_picture;
				}

				$product->save();

				$picture_id = $new_picture?$new_picture:$product->picture_id;

				if (!empty($picture_id)) {
                    $product->appPicture($picture_id);
                }
                
				$this->page->go('/admin/product/list');
			}
		}

		$this->view->setVar('model', $model->getDataForTemplate());
        $this->view->setVar('manufacturers', product::$manufacturers);
        
		$this->render();
    }
}