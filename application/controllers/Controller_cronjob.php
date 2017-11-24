<?php
    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
        
    class Controller_cronjob extends \smashEngine\core\Controller
    {
        public function action_index()
        {
           //exit();
            
            try
            {
                $result = App::mail()->sendAllMessages(50, array(0, 10));
            }
            catch (Exception $e)
            {
                printr($e);
                exit($e->getMessage());
            }
            
            echo NOW . "\n";
            if (count($result) > 0)
            print_r($result);
            else echo "Empty";
            
            echo "\n\n";
            die('stop');
        }
    }