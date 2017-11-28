<?php
    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
    
    use \PDO;
    use \Exception;
    use \DateTime;
    
    class Controller_main extends \smashEngine\core\Controller
    {
        public function __construct(\Routing\Router $router)
        {
            parent::__construct($router);
            
            Controller_common::execute($this);
        }
        
        public function action_index()
        {
            $tp_folder = 'main/';
    
            $this->page->index_tpl = 'index.tpl';
            $this->page->ogImage = mainUrl . '';
            $this->page->ogUrl = '/';
            
            if (($this->user->client->ismobiledevice == '1' && $this->user->client->istablet == 0) || $_COOKIE['MobilePageVersion']) {
                $this->page->tpl = $tp_folder . 'main.mobile.tpl';
            } else {
                $this->page->tpl = $tp_folder . 'main.tpl';
            }
        
            $this->view->setVar('mobile_tpl', $tp_folder. 'main.mobile.tpl');
            $this->view->setVar('desktop_tpl', $tp_folder . 'main.tpl');
            
            $this->page->import(array(
                '/public/js/p/main.js',
                '/public/css/p/main.css', 
            ));
            
            $this->view->generate($this->page->index_tpl);
        }
    }