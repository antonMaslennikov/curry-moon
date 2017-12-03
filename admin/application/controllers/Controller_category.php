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
		$model->newTree = true;
		$postModel = Html::modelName($model);

		if (isset($_POST[$postModel])) {

			$model->setPost($_POST[$postModel]);

			if ($model->validate()) {

				$category->setAttributes($model->getData());

				if ($category->createTree()) {

					if (isset($_POST['apply'])) {

						$this->page->go('/admin/product_category/update?id='.$category->id);
					} else {
						$this->page->go('/admin/product_category/list');
					}
				}
			}
		}

		$this->view->setVar('model', $model->getDataForTemplate());
		$this->view->setVar('button', 'Создать дерево');

		$this->render();
	}


	public function action_update() {

		$category = new category((int) $_GET['id']);

		$parent_id = $category->parent_id;

		$this->setTemplate('category/form.tpl');
		$this->setTitle(sprintf('<i class="fa fa-fw fa-pencil"></i> Изменение категории "%s"', $category->title));

		$this->setBreadCrumbs([
			'/admin/product_category/list'=>'<i class="fa fa-fw fa-shopping-bag"></i> Категории товаров',
		]);

		$model = new CategoryFormModel();
		$model->setAttributes($category->info, false);
		$model->setUpdate();

		$postModel = Html::modelName($model);

		if (isset($_POST[$postModel])) {

			$model->setPost($_POST[$postModel]);

			if ($model->validate()) {

				$category->setAttributes($model->getData());

				$status = ($parent_id == $category->parent_id)?$category->update():$category->updateMove();

				if ($status) {

					if (isset($_POST['apply'])) {

						$this->page->go('/admin/product_category/update?id='.$category->id);
					} else {

						$this->page->go('/admin/product_category/list');
					}
				}
			}
		}

		$this->view->setVar('model', $model->getDataForTemplate());
		$this->view->setVar('button', 'Изменить');

		$this->render();
	}


	public function action_delete() {

		$category = new category((int) $_GET['id']);

		if ($category->deleteNode((int) $_GET['id'])) {

			$this->page->go('/admin/product_category/list');
		} else {

			throw new Exception('Не известная ошибка');
		}
	}


	public function action_create() {

		$category = new category();

		$this->setTemplate('category/form.tpl');
		$this->setTitle('Создание категории');

		$this->setBreadCrumbs([
			'/admin/product_category/list'=>'<i class="fa fa-fw fa-shopping-bag"></i> Категории товаров',
		]);

		$model = new CategoryFormModel();

		$postModel = Html::modelName($model);

		if (isset($_POST[$postModel])) {

			$model->setPost($_POST[$postModel]);

			if ($model->validate()) {

				$category->setAttributes($model->getData());

				if ($category->addChild($category->parent_id, $category)) {

					if (isset($_POST['apply'])) {

						$this->page->go('/admin/product_category/update?id='.$category->id);
					} else {

						$this->page->go('/admin/product_category/list');
					}
				}
			}
		}

		$this->view->setVar('model', $model->getDataForTemplate());
		$this->view->setVar('button', 'Создать');

		$this->render();
	}
}