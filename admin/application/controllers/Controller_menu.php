<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 04.12.2017
 * Time: 20:10
 */

namespace admin\application\controllers;

use admin\application\models\menu;
use admin\application\models\MenuFormModel;
use smashEngine\core\helpers\Html;

class Controller_menu extends Controller_ {

	protected $layout = 'index.tpl';

	public function action_index() {

		$this->setTemplate('menu/menu.tpl');
		$this->setTitle('<i class="fa fa-fw fa-list"></i> Меню');

		$this->setBreadCrumbs([
			//'<i class="fa fa-fw fa-shopping-bag"></i> Категории товаров',
		]);

		$this->view->setVar('list', (new menu())->getList());

		$this->render();
	}

	public function action_menu() {


	}

	public function action_create() {

		$menu = new menu();

		$this->setTemplate('menu/menu_form.tpl');
		$this->setTitle('Новое меню');

		$this->setBreadCrumbs([
			'/admin/menu'=>'<i class="fa fa-fw fa-list-o"></i> Меню',
		]);

		$model = new MenuFormModel();

		$postModel = Html::modelName($model);

		if (isset($_POST[$postModel])) {

			$model->setAttributes($_POST[$postModel]);

			if ($model->validate()) {

				$menu->setAttributes($model->getData());
				$menu->save();

				if (isset($_POST['apply'])) {

					$this->page->go('/admin/menu/update?id='.$menu->id);
				} else {

					$this->page->go('/admin/menu');
				}
			}
		}

		$this->view->setVar('model', $model->getDataForTemplate());
		$this->view->setVar('button', 'Создать');

		$this->render();
	}

	public function action_update() {

		$menu = new menu((int) $_GET['id']);

		$this->setTemplate('menu/menu_form.tpl');
		$this->setTitle(sprintf('Меню "%s"', $menu->slug));

		$this->setBreadCrumbs([
			'/admin/menu'=>'<i class="fa fa-fw fa-list-o"></i> Меню',
		]);

		$model = new MenuFormModel();
		$model->setAttributes($menu->info, false);
		$model->setUpdate();

		$postModel = Html::modelName($model);

		if (isset($_POST[$postModel])) {

			$model->setAttributes($_POST[$postModel]);

			if ($model->validate()) {

				$menu->setAttributes($model->getData());

				if ($menu->update()) {

					if (isset($_POST['apply'])) {

						$this->page->go('/admin/menu/update?id='.$menu->id);
					} else {

						$this->page->go('/admin/menu');
					}
				}
			}
		}

		$this->view->setVar('model', $model->getDataForTemplate());
		$this->view->setVar('button', 'Изменить');

		$this->render();
	}

	public function action_delete() {


	}

	public function action_item_create() {


	}

	public function action_item_update() {


	}

	public function action_item_delete() {


	}
}