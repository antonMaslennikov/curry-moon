<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 04.12.2017
 * Time: 0:17
 */

namespace admin\application\controllers;

use admin\application\models\menuItem;
use admin\application\models\PageFormModel;
use admin\application\models\staticPage;
use smashEngine\core\helpers\Html;

class Controller_page extends Controller_ {

	protected $layout = 'index.tpl';


	public function action_index()
	{
		$this->setTemplate('page/index.tpl');
		$this->setTitle('<i class="fa fa-fw fa-files-o"></i> Список страниц');

		$this->setBreadCrumbs([
			//'<i class="fa fa-fw fa-shopping-bag"></i> Категории товаров',
		]);

		$this->view->setVar('static_pages', (new staticPage())->getList());

		$this->render();
	}


	public function action_delete() {

		$page = new staticPage((int) $_GET['id']);

		if ($page->delete()) {

			$this->page->go('/admin/page');
		} else {

			throw new Exception('Неизвестная ошибка');
		}
	}


	public function action_update() {

		$page = new staticPage((int) $_GET['id']);

		$this->setTemplate('page/form.tpl');
		$this->setTitle(sprintf('Страница "%s"', $page->h1_ru));

		$this->setBreadCrumbs([
			'/admin/page'=>'<i class="fa fa-fw fa-files-o"></i> Список страниц',
		]);

		$model = new PageFormModel();
		$model->setAttributes($page->info, false);
		$model->setUpdate();

		$postModel = Html::modelName($model);

		if (isset($_POST[$postModel])) {

			$model->setAttributes($_POST[$postModel]);

			if ($model->validate()) {

				$page->setAttributes($model->getData());

				if ($page->update()) {

					if (isset($_POST['apply'])) {

						$this->page->go('/admin/page/update?id='.$page->id);
					} else {

						$this->page->go('/admin/page');
					}
				}
			}
		}

		$this->view->setVar('model', $model->getDataForTemplate());
		$this->view->setVar('button', 'Изменить');

		$this->render();
	}


	public function action_create() {

		$page = new staticPage();

		$this->setTemplate('page/form.tpl');
		$this->setTitle('Новая страница');

		$this->setBreadCrumbs([
			'/admin/page'=>'<i class="fa fa-fw fa-files-o"></i> Список страниц',
		]);

		$model = new PageFormModel();

		$postModel = Html::modelName($model);

		if (isset($_POST[$postModel])) {

			$model->setAttributes($_POST[$postModel]);

			if ($model->validate()) {

				$page->setAttributes($model->getData());
				$page->save();

				if (isset($_POST['apply'])) {

					$this->page->go('/admin/page/update?id='.$page->id);
				} else {

					$this->page->go('/admin/page/list');
				}
			}
		}

		$this->view->setVar('model', $model->getDataForTemplate());
		$this->view->setVar('button', 'Создать');

		$this->render();
	}
}