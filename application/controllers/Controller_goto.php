<?php
    namespace application\controllers;
    
    class Controller_goto extends \smashEngine\core\Controller
    {
        public function action_index()
        {
            if ($_GET['target']) {
                header("HTTP/1.1 301 Moved Permanently");
                header("location: " . urldecode($_GET['target']));
                exit();
            } else {
                $this->page->go('/');
            }
        }
    }