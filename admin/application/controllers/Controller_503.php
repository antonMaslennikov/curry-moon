<?php
    namespace admin\application\controllers;
    
    class Controller_503 extends \smashEngine\core\Controller
    {
        public function __construct(\Routing\Router $router)
        {
            $this->router = $router;
            
            $this->view = new \smashEngine\core\View;
	        parent::__construct($router);
        }
        
        public function action_index(\Exception $e = null)
        {
            // выдать заголовок со статусом 503
            header('HTTP/1.1 503 Service Temporarily Unavailable');
            header('Status: 503 Service Temporarily Unavailable');
            header('Retry-After: 1');

	        $this->page->title = '503 Error Page';

	        ob_start();
            // показать ошибку в дев режиме или отправить её на почту в продакшне
            (new \smashEngine\core\errorHandler())->showError(get_class($e) . ' (code: ' . $e->getCode() . ')', $e->getMessage() . '<hr>' . $e->getTraceAsString() . '<hr>' . json_encode($e->getTrace()), $e->getFile(), $e->getLine());
	        $text = ob_get_contents();
	        ob_end_clean();

	        $this->view->setVar('errorMessage', $text);
            
            // показать страничку-заглушку
            $this->view->generate('503.tpl');
            exit();
        }
    }
