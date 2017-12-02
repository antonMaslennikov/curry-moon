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

		$this->render();
	}
    
    public function action_create()
	{
		$this->setTemplate('product/create.tpl');
		$this->setTitle("Добавить товар");

		$this->setBreadCrumbs([
			'/admin/product/list'=>'<i class="fa fa-fw fa-shopping-bag"></i> Товары',
		]);
        
        $this->setBreadCrumbs([
			'/admin/product/create'=>'<i class="fa fa-fw fa-shopping-bag"></i> Добавить новый',
		]);
        
        $model = new ProductFormModel();
		$postModel = Html::modelName($model);

		if (isset($_POST[$postModel])) {

			$model->setPost($_POST[$postModel]);

			if ($model->validate()) {

				$product->setAttributes($model->getData());

				$this->page->go('/admin/product/list');
			}
		}

		$this->view->setVar('model', $model->getDataForTemplate());

		$this->render();
	}
}