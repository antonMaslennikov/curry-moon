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
        
        if (count($_GET['filters']) == 0) {
            $_GET['filters'] = ['statusNot' => 'active'];
        }
        
		$this->view->setVar('orders', orders::getAll($_GET['filters']));

		$this->render();
	}
    
    public function action_view() {

		$this->setTemplate('orders/view.tpl');
		
        $this->page->import([
            '/public/plugins/iCheck/icheck.min.js',
            '/public/plugins/iCheck/all.css',
        ]);
        
        if ($_GET['id'])
        {
            $o = new orders($_GET['id']);
            
            $this->page->title = 'Заказ №' . $o->id;
        
            $this->view->setVar('order', $o);
            $this->view->setVar('deliveryTypes', orders::$deliveryTypes);
            $this->view->setVar('paymentTypes', orders::$paymentTypes);
        } 
        else {
            $this->page404();
        }
        
		$this->render();
	}
}