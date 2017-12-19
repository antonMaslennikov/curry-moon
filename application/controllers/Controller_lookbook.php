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
            
            $this->view->setVar('posts', post::getList(['lang' => 'ru', 'category' => 2, 'status' => 1, 'orderby' => 'publish_date']));
            
            $this->view->generate($this->page->index_tpl);
        }
    }