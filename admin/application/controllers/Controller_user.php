<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 08.12.2017
 * Time: 22:05
 */

namespace admin\application\controllers;

use admin\application\models\user;
use admin\application\models\UserAccessFormModel;
use admin\application\models\userEmployees;
use admin\application\models\UserFormModel;
use smashEngine\core\App;
use smashEngine\core\helpers\Html;

class Controller_user extends Controller_ {

	protected $layout = 'index.tpl';

	protected $query_template = 'SELECT {select} FROM {table} {where} ORDER BY user_name ASC';

	public function action_city() {

		die(json_encode((new user())->cityList($_GET['data'], true)));
	}

	public function action_index() {

		$this->setTemplate('user/index.tpl');
		$this->setTitle('<i class="fa fa-fw fa-users"></i> Пользователи');

		$this->setBreadCrumbs([
			//'<i class="fa fa-fw fa-shopping-bag"></i> Категории товаров',
		]);

		$this->view->setVar('users', (new user())->getList());

		$this->view->setVar('statusList', [
			'active'=>'<span class="label label-success">Активный</span>',
			'banned'=>'<span class="label label-danger">Заблокированный</span>',
			'deleted'=>'<span class="label label-warning">Удаленный</span>',
		]);
		$this->view->setVar('activationList', [
			'done' => '<span class="label label-success">Выполнена</span>',
			'waiting' => '<span class="label label-warning">Ожидает выполнения</span>',
			'failed' => '<span class="label label-danger">Провалена</span>',
		]);

		$this->render();
	}


	public function action_employees() {

		$this->setTemplate('user/employees.tpl');
		$this->setTitle('<i class="fa fa-fw fa-users"></i> Сотрудники');

		$this->setBreadCrumbs([
			'/admin/user/list'=>'<i class="fa fa-fw fa-users"></i> Пользователи',
		]);

		$this->view->setVar('users', (new user())->getEmployees());

		$this->view->setVar('statusList', [
			'fired'=>'<span class="label label-danger">Уволенный</span>',
			'manager'=>'<span class="label label-warning">Сотрудник</span>',
			'admin'=>'<span class="label label-success">Администратор</span>',
		]);


		$this->render();
	}


	public function action_add_access() {

		$access = new userEmployees();

		$this->setTemplate('user/formEmployees.tpl');
		$this->setTitle('Новый сотрудник');

		$this->setBreadCrumbs([
			'/admin/user/list'=>'<i class="fa fa-fw fa-users"></i> Пользователи',
			'/admin/user/employees'=>'<i class="fa fa-fw fa-users"></i> Сотрудники',
		]);

		$model = new UserAccessFormModel();
		$model->setAttributes($access->info, false);

		$postModel = Html::modelName($model);

		if (isset($_POST[$postModel])) {

			$model->setAttributes($_POST[$postModel]);

			if ($model->validate()) {

				$access->setAttributes($model->getData());

				if ($access->insert()) {

					if (isset($_POST['apply'])) {

						$this->page->go('/admin/user/access?id=' . $access->id);
					} else {

						$this->page->go('/admin/user/employees');
					}
				}
			}
		}

		$this->view->setVar('model', $model->getDataForTemplate());
		$this->view->setVar('button', 'Добавить');

		$this->page->import([
			'/public/packages/select2/css/select2.min.css',
			'/public/packages/select2/css/select2-bootstrap.min.css',
			'/public/packages/select2/js/select2.min.js',
			'/public/packages/select2/js/i18n/ru.js'
		]);

		$this->render();
	}


	public function action_access() {

		$access = new userEmployees((int) $_GET['id']);

		$this->setTemplate('user/formEmployees.tpl');
		$this->setTitle('Редактирование сотрудника');

		$this->setBreadCrumbs([
			'/admin/user/list'=>'<i class="fa fa-fw fa-users"></i> Пользователи',
			'/admin/user/employees'=>'<i class="fa fa-fw fa-users"></i> Сотрудники',
		]);

		$model = new UserAccessFormModel();
		$model->setAttributes($access->info, false);
		$model->setUpdate();

		$postModel = Html::modelName($model);

		if (isset($_POST[$postModel])) {

			$model->setAttributes($_POST[$postModel]);

			if ($model->validate()) {

				$access->setAttributes($model->getData());

				if ($access->update()) {

					if (isset($_POST['apply'])) {

						$this->page->go('/admin/user/access?id=' . $access->id);
					} else {

						$this->page->go('/admin/user/employees');
					}
				}
			}
		}

		$this->view->setVar('model', $model->getDataForTemplate());
		$this->view->setVar('button', 'Добавить');

		$this->page->import([
			'/public/packages/select2/css/select2.min.css',
			'/public/packages/select2/css/select2-bootstrap.min.css',
			'/public/packages/select2/js/select2.min.js',
			'/public/packages/select2/js/i18n/ru.js'
		]);

		$this->render();
	}


	public function action_create() {

		$user = new user();

		$this->setTemplate('user/form.tpl');
		$this->setTitle('Новый пользователь');

		$this->setBreadCrumbs([
			'/admin/user/list'=>'<i class="fa fa-fw fa-users"></i> Пользователи',
		]);

		$model = new UserFormModel();
		$model->setAttributes($user->info, false);

		$postModel = Html::modelName($model);

		if (isset($_POST[$postModel])) {

			$model->setAttributes($_POST[$postModel]);

			if ($model->validate()) {

				$user->setAttributes($model->getData());

				if ($user->save()) {

					if (isset($_POST['apply'])) {

						$this->page->go('/admin/user/update?id='.$user->id);
					} else {

						$this->page->go('/admin/user/list');
					}
				}
			}
		}

		$this->view->setVar('model', $model->getDataForTemplate());
		$this->view->setVar('button', 'Добавить');

		$this->page->import([
			'/public/packages/bootstrap-datepicker/css/bootstrap-datepicker.css',
			'/public/packages/bootstrap-datepicker/js/bootstrap-datepicker.js',
			'/public/packages/bootstrap-datepicker/locales/bootstrap-datepicker.ru.min.js',
			'/public/packages/select2/css/select2.min.css',
			'/public/packages/select2/css/select2-bootstrap.min.css',
			'/public/packages/select2/js/select2.min.js',
			'/public/packages/select2/js/i18n/ru.js'
		]);

		$this->render();
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

				$user->setAttributes($model->getData());

				if ($user->update()) {

					if (isset($_POST['apply'])) {

						$this->page->go('/admin/user/update?id='.$user->id);
					} else {

						$this->page->go('/admin/user/list');
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
			'/public/packages/select2/css/select2-bootstrap.min.css',
			'/public/packages/select2/js/select2.min.js',
			'/public/packages/select2/js/i18n/ru.js'
		]);

		$this->render();
	}
}