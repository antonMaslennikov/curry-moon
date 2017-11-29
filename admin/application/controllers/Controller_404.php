<?php
    namespace admin\application\controllers;
    
    class Controller_404 extends Controller_
    {
        public function __construct(\Routing\Router $router)
        {

	        $this->router = $router;

	        $this->view = new \smashEngine\core\View;
	        parent::__construct($router);
        }
        
        public function action_index()
        {
            header('HTTP/1.1 404 Not Found');

	        $this->page->title = '404 Error Page';
            
            $this->view->generate('404.tpl');
            //exit();
        }
    }
