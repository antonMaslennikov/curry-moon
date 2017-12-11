<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 08.12.2017
 * Time: 22:05
 */

namespace admin\application\controllers;

use admin\application\models\user;
use admin\application\models\UserFormModel;
use smashEngine\core\helpers\Html;

class Controller_user extends Controller_ {

	protected $layout = 'index.tpl';

	public function action_index() {

		$this->setTemplate('user/index.tpl');
		$this->setTitle('<i class="fa fa-fw fa-users"></i> Пользователи');

		$this->setBreadCrumbs([
			//'<i class="fa fa-fw fa-shopping-bag"></i> Категории товаров',
		]);

		$this->view->setVar('users', (new user())->getList());

		$this->render();
	}


	public function action_create() {


	}


	public function action_update() {

		$user = new user((int) $_GET['id']);

		$this->setTemplate('user/form.tpl');
		$this->setTitle(sprintf('Изменение пользователя "%s"', $user->user_name));

		$this->setBreadCrumbs([
			'/admin/user/list'=>'<i class="fa fa-fw fa-users"></i> Пользователи',
		]);

		$model = new UserFormModel();
		$model->setAttributes($user->info, false);
		$model->setUpdate();

		$postModel = Html::modelName($model);

		if (isset($_POST[$postModel])) {

			$model->setAttributes($_POST[$postModel]);

			if ($model->validate()) {

				printr($model->getData(), 1);

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

		$this->page->import([
			'/public/packages/bootstrap-datepicker/css/bootstrap-datepicker.css',
			'/public/packages/bootstrap-datepicker/js/bootstrap-datepicker.js',
			'/public/packages/bootstrap-datepicker/locales/bootstrap-datepicker.ru.min.js',
			'/public/packages/select2/css/select2.min.css',
			'/public/packages/select2/js/select2.min.js',
			'/public/packages/select2/js/i18n/ru.js'
		]);

		$this->render();
	}
}