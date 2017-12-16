<?php
namespace admin\application\controllers;

use \application\models\basket;
use admin\application\models\orders;
use admin\application\models\ordersFormModel;
use smashEngine\core\helpers\Html;

class Controller_orders extends Controller_ {

	protected $layout = 'index.tpl';

    public function __construct($r)
    {
        parent::__construct($r);
        
        $this->setBreadCrumbs([
			'/admin/orders/list'=>'<i class="fa fa-fw fa-files-o"></i> Заказы',
		]);
    }
    
    public function action_index() {

		$this->setTemplate('orders/index.tpl');
		
        $this->page->title = 'Заказы';
        
        $this->page->import([
            '/public/plugins/iCheck/icheck.min.js',
            '/public/plugins/iCheck/all.css',
        ]);
        
		$this->view->setVar('orders', orders::getAll([
            'statusNot' => 'active',
            
        ]));

		$this->render();
	}
}