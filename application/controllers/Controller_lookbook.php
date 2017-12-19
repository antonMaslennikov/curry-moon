<?php
    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
    use \application\models\post;

    use \PDO;
    use \Exception;
    use \DateTime;
    
    class Controller_lookbook extends Controller_
    {
        public function action_index()
        {
            $this->page->index_tpl = 'index.tpl';
            $this->page->tpl = 'lookbook/index.tpl';
            $this->page->title = 'LookBook';
            $this->page->addBreadCrump($this->page->title);
            
            $onpage = 10;
            
            $trans_id = uniqid();
            
            $this->view->setVar('posts', post::getList([
                 'lang' => 'ru', 
                 'category' => 2, 
                 'status' => 1, 
                 'orderby' => 
                 'publish_date',
                 'limit' => $onpage,
                 'offset' => intval($_GET['limitstart']),
                ], $trans_id));
            
            $total = $_SESSION['pages_total_' . $trans_id];
            unset($_SESSION['pages_total_' . $trans_id]);
            
            $this->view->setVar('base', '/' . $this->page->reqUrl[0] . '/lookbook/');
            $this->view->setVar('pages', range(1, ceil($total / $onpage)));
            $this->view->setVar('page', $_GET['limitstart'] / $onpage + 1);
            $this->view->setVar('onpage', $onpage);
            
            if (!empty($_GET['limitstart'])) {
                $this->page->canonical = $this->page->url;
            }
            
            $this->view->generate($this->page->index_tpl);
        }
    }