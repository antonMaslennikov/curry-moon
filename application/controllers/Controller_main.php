<?php
    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
    use \application\models\product;

    use \PDO;
    use \Exception;
    use \DateTime;
    
    class Controller_main extends Controller_
    {
        public function action_index()
        {   
            $this->page->index_tpl = 'index.tpl';
            $this->page->ogUrl = '/';
            $this->page->title = 'Интернет-магазин одежды и украшений';
            
            if (($this->user->client->ismobiledevice == '1' && $this->user->client->istablet == 0) || $_COOKIE['MobilePageVersion']) {
                $this->page->tpl = 'main/main.mobile.tpl';
            } else {
                $this->page->tpl = 'main/main.tpl';
            }
            
            $this->page->import(array(
            ));
            
            $products = product::getAll([
                'status' => 'active', 
                'picture' => true,
                'orderBy' => 'pr.`id`',
                'orderDir' => 'DESC',
                'limit' => 4,
            ]);
            
            $this->view->setVar('products', $products);
            
            $this->view->generate($this->page->index_tpl);
        }
    }