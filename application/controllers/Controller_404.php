<?php
    namespace application\controllers;
    
    class Controller_404 extends \smashEngine\core\Controller
    {
        public function __construct(\Routing\Router $router)
        {
            $this->router = $router;
            
            $this->view = new \smashEngine\core\View;
        }
        
        public function action_index()
        {
            header('HTTP/1.1 404 Not Found');
            
            $this->view->generate('404.tpl');
            exit();
        }
    }
