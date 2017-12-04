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
use admin\application\models\menuItem;
use admin\application\models\MenuItemFormModel;
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

	public function action_item() {

		$this->setTemplate('menu/menu_item.tpl');
		$this->setTitle('<i class="fa fa-fw fa-list"></i> Пункты меню');

		$this->setBreadCrumbs([
			'/admin/menu'=>'<i class="fa fa-fw fa-list"></i> Меню',
		]);

		$data = (new menuItem())->getList(new menu(), (int) $_GET['menu_id']);

		if (!count($data)) {

			$this->page->go('/admin/menu');
		}

		$this->view->setVar('menu', $data);

		$this->render();
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

		$menu = new menu((int) $_GET['id']);

		if ($menu->delete()) {

			$this->page->go('/admin/menu');
		} else {

			throw new Exception('Неизвестная ошибка');
		}
	}

	public function action_item_create() {

		$menuItem = new menuItem();

		$menu = new menu((int) $_GET['menu_id']);

		$this->setTemplate('menu/menu_item_form.tpl');
		$this->setTitle('Новый пункт меню');

		$this->setBreadCrumbs([
			'/admin/menu'=>'<i class="fa fa-fw fa-list-o"></i> Меню',
			'/admin/menu/item'=>'<i class="fa fa-fw fa-list-o"></i> Пункты меню',
		]);

		$model = new MenuItemFormModel();
		$model->menu_id = $menu->id;
		$model->setListMenu($menu->getList());

		$postModel = Html::modelName($model);

		if (isset($_POST[$postModel])) {

			$model->setAttributes($_POST[$postModel]);

			if ($model->validate()) {

				$menuItem->setAttributes($model->getData());
				$menuItem->save();

				if (isset($_POST['apply'])) {

					$this->page->go('/admin/menu/item/update?id='.$menuItem->id);
				} else {

					$this->page->go('/admin/menu/item?menu_id='.$menuItem->menu_id);
				}
			}
		}

		$this->view->setVar('model', $model->getDataForTemplate());
		$this->view->setVar('button', 'Создать');

		$this->render();
	}

	public function action_item_update() {

		$menuItem = new menuItem((int) $_GET['id']);

		$menu = new menu();

		$this->setTemplate('menu/menu_item_form.tpl');
		$this->setTitle(sprintf('Пункт меню "%s"', $menuItem->title_ru));

		$this->setBreadCrumbs([
			'/admin/menu'=>'<i class="fa fa-fw fa-list-o"></i> Меню',
			'/admin/menu/item'=>'<i class="fa fa-fw fa-list-o"></i> Пункты меню',
		]);

		$model = new MenuItemFormModel();
		$model->setAttributes($menuItem->info, false);
		$model->setListMenu($menu->getList());
		$model->setUpdate();

		$postModel = Html::modelName($model);

		if (isset($_POST[$postModel])) {

			$model->setAttributes($_POST[$postModel]);

			if ($model->validate()) {

				$menuItem->setAttributes($model->getData());

				if ($menuItem->update()) {

					if (isset($_POST['apply'])) {

						$this->page->go('/admin/menu/item/update?id='.$menuItem->id);
					} else {

						$this->page->go('/admin/menu/item?menu_id='.$menuItem->menu_id);
					}
				}
			}
		}

		$this->view->setVar('model', $model->getDataForTemplate());
		$this->view->setVar('button', 'Изменить');

		$this->render();
	}

	public function action_item_delete() {


	}
}