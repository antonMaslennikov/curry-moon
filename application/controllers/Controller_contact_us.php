<?php
    namespace application\controllers;
    
    class Controller_contact_us extends \smashEngine\core\Controller
    {
        public function __construct(\Routing\Router $router)
        {
            parent::__construct($router);
            
            Controller_common::execute($this);
        }
        
        public function action_index()
        {
            $this->page->tpl = 'contact_us/index.tpl';
            $this->view->generate('index.tpl'); 
        }
    }