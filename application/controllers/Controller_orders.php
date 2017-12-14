<?php
    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
    use \application\models\product;

    use \PDO;
    use \Exception;
    use \DateTime;
    
    class Controller_orders extends Controller_
    {
        public function action_index()
        {   
            $this->page->index_tpl = 'index.tpl';
            $this->page->tpl = 'orders/index.tpl';
            $this->page->title = 'Мои заказы';
            
            /*
            $products = product::getAll([
                'status' => 'active', 
                'picture' => true,
                'orderBy' => 'pr.`id`',
                'orderDir' => 'DESC',
                'limit' => 4,
            ]);
            */
            $this->view->setVar('orders', $orders);
            
            $this->view->generate($this->page->index_tpl);
        }
        
        public function action_view()
        {   
            $this->page->index_tpl = 'index.tpl';
            $this->page->tpl = 'orders/view.tpl';
            $this->page->title = 'Заказы #';
            
            /*
            $products = product::getAll([
                'status' => 'active', 
                'picture' => true,
                'orderBy' => 'pr.`id`',
                'orderDir' => 'DESC',
                'limit' => 4,
            ]);
            */
            $this->view->setVar('order', $order);
            
            $this->view->generate($this->page->index_tpl);
        }
    }