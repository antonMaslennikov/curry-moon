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
use smashEngine\core\helpers\File;
use smashEngine\core\helpers\Html;

class Controller_category extends Controller_ {

	protected $layout = 'index.tpl';

	public function action_index()
	{
		$this->setTemplate('category/index.tpl');
		$this->setTitle('<i class="fa fa-fw fa-shopping-bag"></i> Категории товаров');

		$this->setBreadCrumbs([
			'<i class="fa fa-fw fa-shopping-bag"></i> Категории товаров',
		]);

		$this->view->setVar('tree', (new category())->getTree());

		$this->render();
	}


	public function action_createTree() {

		$category = new category();

		if (count($category->getTree())) $this->page404();

		$this->setTemplate('category/form.tpl');
		$this->setTitle("Создание дерева");

		$this->setBreadCrumbs([
			'/admin/product_category/list'=>'<i class="fa fa-fw fa-shopping-bag"></i> Категории товаров',
		]);

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
		$this->view->setVar('button', 'Создать дерево');

		$this->render();
	}


	public function action_update() {

		$category = new category((int) $_GET['id']);

		$this->setTemplate('category/form.tpl');
		$this->setTitle('<i class="fa fa-fw fa-pencil"></i> Изменение категории');

		$this->setBreadCrumbs([
			'/admin/product_category/list'=>'<i class="fa fa-fw fa-shopping-bag"></i> Категории товаров',
		]);

		$model = new CategoryFormModel();
		$model->setAttributes($category->info, false);
		$model->newRecord = false;

		$postModel = Html::modelName($model);

		if (isset($_POST[$postModel])) {

			$model->setAttributes($_POST[$postModel]);

			if ($model->validate()) {

				$category->setAttributes($model->getData());

				printr($category, 1);
			}
		}

		$this->view->setVar('model', $model->getDataForTemplate());
		$this->view->setVar('button', 'Изменить');
		$this->view->setVar('cancel', 'Отмена');

		$this->render();
	}
}