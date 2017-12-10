<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 08.12.2017
 * Time: 22:05
 */

namespace admin\application\controllers;

use admin\application\models\user;

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
}