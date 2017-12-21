<?php
    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
    use \application\models\product;
    use \application\models\basket;

    use \PDO;
    use \Exception;
    use \DateTime;
    
    class Controller_orders extends Controller_
    {
        public function action_index()
        {
            if (!$this->user->authorized) {
                $this->page404();
            }
            
            $this->page->index_tpl = 'index.tpl';
            $this->page->tpl = 'orders/list.tpl';
            $this->page->title = 'Мои заказы';
            $this->page->addBreadCrump($this->page->title);
            
            $orders = basket::getAll([
                'user' => $this->user->id,
                'statusNot' => 'active', 
                'orderBy' => 'b.`id`',
                'orderDir' => 'DESC',
                'limit' => 100,
            ]);
                
            $this->view->setVar('orders', $orders);
            
            $this->view->generate($this->page->index_tpl);
        }
        
        public function action_view()
        {   
            $this->page->index_tpl = 'index.tpl';
            $this->page->tpl = 'orders/details.tpl';
            
            $order = new basket($this->page->reqUrl[2]);
            
            $this->page->title = 'Заказ #' . $order->id;
            
            $this->page->addBreadCrump('Мои заказы', '/ru/orders');
            $this->page->addBreadCrump($this->page->title);
            
            if ($order->user_id != $this->user->id) {
                $this->page404();
            }
            
            $this->view->setVar('order', $order);
            
            $this->view->generate($this->page->index_tpl);
        }
    }