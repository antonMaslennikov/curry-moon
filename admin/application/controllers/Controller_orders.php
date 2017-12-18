<?php
namespace admin\application\controllers;

use \application\models\basket;
use \application\models\basketAddress;
use \application\models\basketItem;
use admin\application\models\orders;
use admin\application\models\ordersFormModel;
use smashEngine\core\helpers\Html;
use smashEngine\core\App;

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
            
            $this->page->title = 'Заказ №' . $o->id . ' от ' . datefromdb2textdate($o->user_basket_date, 1);
        
            $sth = App::db()->query("SELECT * FROM `countries` ORDER BY `raiting` ASC, `country_name`");
            $countries = $sth->fetchAll();
            
            if (isset($_POST['save']))
            {
                if ($_POST['delivery_type'] && $_POST['delivery_type'] != $o->user_basket_delivery_type && orders::$deliveryTypes[$_POST['delivery_type']]) {
                    $o->user_basket_delivery_type = $_POST['delivery_type'];
                }
                
                if ($_POST['delivery_cost']) {
                    $o->user_basket_delivery_cost = $_POST['delivery_cost'];
                }
                
                if ($_POST['payment_type'] && $_POST['payment_type'] != $o->user_basket_payment_type && orders::$paymentTypes[$_POST['payment_type']]) {
                    $o->user_basket_payment_type = $_POST['payment_type'];
                }
                
                if ($_POST['address']) {
                    
                    if (!empty($_POST['address']['city'])) {
                        $_POST['address']['city'] = cityName2id($_POST['address']['city'], $_POST['address']['country'], 1);
                    }
                    
                    $a = new basketAddress($o->user_basket_delivery_address);
                    $a->setAttributes($_POST['address']);
                    
                    $a->save();
                }
                
                $o->save();
                
                $this->page->refresh();
            }
            
            if (isset($_POST['pay']))
            {
                if ($_POST['pay'] > 0) {
                    $o->pay($_POST['pay']);
                    $this->page->setFlashSuccess('Заказ оплачен на ' . orders::$orderStatus[$_POST['ch-status']]);
                }
                
                $this->page->refresh();
            }
            
            if ($_POST['ch-status']) 
            {
                if (orders::$orderStatus[$_POST['ch-status']]) 
                {
                    $o->user_basket_status = $_POST['ch-status'];
                    
                    switch ($_GET['ch-status'])
                    {
                        case 'prepared':
                            break;

                        case 'canceled':
                            break;

                        case 'delivered':
                            $o->pay($o->basketSum - $o->alreadyPayed);
                            break;
                    }
                    
                    $o->save();
                    
                    $this->page->setFlashSuccess('Статус заказа изменён на ' . orders::$orderStatus[$_POST['ch-status']]);
                    
                } else {
                    $this->page->setFlashError('Не корректный статус заказа!');
                }
                
                $this->page->refresh();
            }
            
            if (isset($_POST['addcomment'])) 
            {    
                if (!empty($_POST['comment'])) {
                    $o->log('admin_comment', $_POST['comment'], '', $this->user->id);
                        
                    if ($_POST['to'] == 'client') {
                        
                    }
                }
                
                $this->page->refresh();
            }
            
            if (isset($_POST['savepos']))
            {
                foreach ($_POST['pos'] AS $k => $p) {
                    $item = new basketItem($k);
                    
                    if (!empty($p['price']))
                        $item->user_basket_good_price = $p['price'];
                    
                    if (!empty($p['quantity']))
                        $item->user_basket_good_quantity = $p['quantity'];
                    
                    $item->user_basket_good_discount = $p['discount'];
                    
                    if (!empty($p['price']) && !empty($p['quantity'])) {
                        $item->user_basket_good_total_price = round($p['price'] * (1 - $p['discount'] / 100)) * $p['quantity'];
                    }
                    
                    $item->save();
                }
                
                $this->page->go('/admin/orders/view?id=' . $o->id);
            }
            
            foreach (array_merge((array) $o->logs['user_comment'], (array) $o->logs['admin_comment']) AS $k => $l) {
                $comments[$l['id']] = $l;
            }
            
            krsort($comments);
            
            $o->comments = $comments;
            
            $this->view->setVar('order', $o);
            $this->view->setVar('deliveryTypes', orders::$deliveryTypes);
            $this->view->setVar('paymentTypes', orders::$paymentTypes);
            $this->view->setVar('countries', $countries);
        } 
        else {
            $this->page404();
        }
        
		$this->render();
	}
}