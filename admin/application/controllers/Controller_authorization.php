<?php
    namespace admin\application\controllers;
    
    use \smashEngine\core\App AS App;
    
    use \PDO;
    use \Exception;
    use \DateTime;
    
    class Controller_authorization extends \smashEngine\core\Controller
    {
        public function __construct(\Routing\Router $router)
        {
            parent::__construct($router);
            
            Controller_common::execute($this);
        }
        
        public function action_index()
        {
            $this->page->index_tpl = 'index.tpl';
            $this->page->tpl = 'authorization/index.tpl';
        
            /*
            $this->page->import(array(
                '/public/js/p/main.js',
                '/public/css/p/main.css', 
            ));
            */
            
            $this->view->generate($this->page->index_tpl);
        }
    }