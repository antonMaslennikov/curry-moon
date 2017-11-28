<?php
    namespace application\controllers;
    
    class Controller_static_page extends \smashEngine\core\Controller
    {
        public function __construct(\Routing\Router $router)
        {
            parent::__construct($router);
            
            Controller_common::execute($this);
            
            printr($this->page->reqUrl[0]);
            printr($this->page->reqUrl[1]);
        }
        
        public function action_index()
        {
            $this->page->tpl = 'static_pages/content.tpl';
            $this->view->generate('index.tpl'); 
        }
    }