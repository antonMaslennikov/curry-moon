<?php
    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
    use \application\models\product;
    use \application\models\staticPage;

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
            $this->page->tpl = 'main/main.tpl';
            
            $page = new staticPage(7);
                
            $this->page->title = $page->h1;
            $this->page->keywords = $page->meta_keywords;
            $this->page->description = $page->meta_description;

            $this->view->setVar('sPage', $page);
            $this->view->setVar('PAGE', $this->page);
            
            
            $products = product::getAll([
                'status' => 'active', 
                'picture' => true,
                'orderBy' => 'pr.`id` DESC',
                'limit' => 4,
            ]);
            
            $this->view->setVar('products', $products);
            
            $this->view->generate($this->page->index_tpl);
        }
    }