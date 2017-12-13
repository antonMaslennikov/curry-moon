<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 13.12.2017
 * Time: 22:55
 */

namespace admin\application\controllers;


use admin\application\models\LoginFormModel;
use smashEngine\core\helpers\Html;

class Controller_access extends Controller_ {

	public function action_login()
	{
		if ($this->user->authorized) {
			$this->page->go('/admin/');
		}

		$this->page->index_tpl = 'login.tpl';
		$this->page->tpl = 'access/login.tpl';

		$this->setTitle('Авторизация');

		$model = new LoginFormModel();

		$postModel = Html::modelName($model);

		if (isset($_POST[$postModel])) {

			$model->setAttributes($_POST[$postModel]);

			if ($model->validate()) {

				$data =  $model->getData();

				$this->user->id = $data['id'];
				$this->user->authorize();

				if (!$data['rememberMe']=='on')
					$this->user->setSessionValue(['session_short' => '1']);

				$this->page->refresh();
			}
		}

		$this->view->setVar('model', $model->getDataForTemplate());

		$this->view->generate($this->page->index_tpl);
	}


	public function action_logout() {

		$this->user->setSessionValue(['user_id' => -1, 'session_logged' => 0]);

		$this->page->go('/admin/login');
	}
}