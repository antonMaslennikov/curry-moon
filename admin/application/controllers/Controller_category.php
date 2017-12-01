<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 01.12.2017
 * Time: 19:31
 */

namespace admin\application\controllers;


use admin\application\models\category;
use admin\application\models\CategoryFormModel;
use smashEngine\core\helpers\Html;

class Controller_category extends Controller_ {

	protected $layout = 'index.tpl';

	public function action_index()
	{
		$this->setTemplate('category/index.tpl');
		$this->setTitle("Категории товаров");

		$this->view->setVar('tree', (new category())->getTree());

		$this->render();
	}


	public function action_createTree() {

		$category = new category();

		if (count($category->getTree())) $this->page404();

		$this->setTemplate('category/form.tpl');
		$this->setTitle("Создание дерева каталогов");

		$model = new CategoryFormModel();
		$postModel = Html::modelName($model);

		if (isset($_POST[$postModel])) {

			$model->setAttributes($_POST[$postModel]);

			if ($model->validate()) {

				$category->setAttributes($model->getData());

				if ($category->createTree())

					$this->page->go('/admin/product_category/list', 301);
			}
		}

		$this->view->setVar('model', $model->getDataForTemplate());
		$this->view->setVar('button', 'Сформировать дерево');

		$this->render();
	}
}