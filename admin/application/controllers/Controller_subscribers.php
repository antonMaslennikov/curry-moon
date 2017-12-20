<?php

namespace admin\application\controllers;

use \smashEngine\core\App;
use \smashEngine\core\helpers\Html;
use \admin\application\models\mailSubscription;

class Controller_subscribers extends Controller_ {

	protected $layout = 'index.tpl';
    
    public function action_index() {

		$this->setTemplate('subscribers/index.tpl');
		$this->setTitle('<i class="fa fa-fw fa-users"></i> Подписались на рассылку');


		$this->view->setVar('records', (new mailSubscription)->getList());

		$this->render();
	}
    
    public function action_delete() {
        $s = new mailSubscription($_GET['id']);
        $s->delete();
        $this->page->refresh();
    }
}