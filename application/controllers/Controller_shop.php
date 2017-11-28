<?php
    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
    
    use \PDO;
    use \Exception;
    use \DateTime;
    
    class Controller_shop extends \smashEngine\core\Controller
    {
        public function __construct(\Routing\Router $router)
        {
            parent::__construct($router);
            
            Controller_common::execute($this);
        }
        
        public function action_index()
        {
            $this->page->index_tpl = 'index.tpl';
            $this->page->tpl = 'shop/index.tpl';
            
            $this->page->import(array(
                '/public/js/p/catalog.js',
                '/public/css/p/catalog.css', 
            ));
            
            printr($this->page->reqUrl);
            
            $this->view->generate($this->page->index_tpl);
        }
    }