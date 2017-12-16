<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 15.12.2017
 * Time: 22:25
 */

namespace admin\application\controllers;

use admin\application\models\feedback;


class Controller_feedback extends Controller_ {

	protected $layout = 'index.tpl';

	public function action_list() {

		$this->setTemplate('feedback/list_new.tpl');
		$this->setTitle('<i class="fa fa-envelope-o"></i> Обратная связь');

		$this->view->setVar('list', (new feedback())->listData() );

		$this->render();
	}


	public function action_list_send() {

		$this->setTemplate('feedback/list_old.tpl');
		$this->setTitle('<i class="fa fa-envelope-o"></i> Обратная связь');

		$this->view->setVar('list', (new feedback())->listData(true) );

		$this->render();
	}
}