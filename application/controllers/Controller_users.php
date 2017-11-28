<?php
    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
    
    use \PDO;
    use \Exception;
    use \DateTime;
    
    class Controller_users extends \smashEngine\core\Controller
    {
        public function __construct(\Routing\Router $router)
        {
            parent::__construct($router);
            
            Controller_common::execute($this);
        }
        
        public function action_login()
        {
            $this->page->index_tpl = 'index.tpl';
            $this->page->tpl = 'users/login.tpl';
           
            
            $this->view->generate($this->page->index_tpl);
        }
        
        public function action_registration()
        {
            $this->page->index_tpl = 'index.tpl';
            $this->page->tpl = 'users/registration.tpl';
            
            $this->view->generate($this->page->index_tpl);
        }
    }