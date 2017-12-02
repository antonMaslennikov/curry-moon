<?
    namespace smashEngine\core;
    
    use \DateTime;
    use \Exception;
    
    class Controller {
        
        public $router;
        public $model;
        public $view;
        public $user;
        public $page;
        public $basket;
        public $VARS = [];
         
        function __construct(\Routing\Router $router)
        {
            session_start();
            
            if (!$_SESSION['csrf_token']) {
                $_SESSION['csrf_token'] = md5(session_id() . CSRF_SALT);
            }

            $this->router = $router;
            
            $this->view = new View;
            
            $this->page = new Page;
        }
        
        /**
         * Перехват несуществующего метода
         */
        function __call($name, array $params)
        {
            header('HTTP/1.1 404 Not Found');
            header("Status: 404 Not Found");
            
            $this->view->generate('', '404.tpl');
        }
        
        function action_index()
        {
        }
    
        function page404()
        {
            header('HTTP/1.1 404 Not Found');
            $this->view->generate('404.tpl');
            exit();
        }
        
        
        public function setTemplate($page) {

            if ($this->layout !== null) {

                $this->page->index_tpl = $this->layout;
            }

            $this->page->tpl = $page;
        }

        public function setTitle($title) {

            $this->page->title = $title;
        }

        public function render() {

            $this->view->generate($this->page->index_tpl);
        }
    }
?>