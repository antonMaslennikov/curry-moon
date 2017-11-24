<?php
    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
    
    use \Exception;
        
    class Controller_mailclick extends \smashEngine\core\Controller
    {
        public function action_index()
        {
            switch ($this->page->reqUrl[1]) 
            {
                case 800:
                    
                    switch ($this->page->reqUrl[2]) {
                        case 'male':
                            $this->user->user_sex = $this->page->reqUrl[2]; 
                            $this->user->save();
                            header('location: /catalog/category,sweatshirts;color,112/new/?utm_source=newsletter&utm_medium=email&utm_campaign=mailsender_800&mid=' . $_GET['mid']);
                            break;
                        case 'female':
                            $this->user->user_sex = $this->page->reqUrl[2];
                            $this->user->save();
                            header('location: /catalog/category,tolstovki/female/new/?utm_source=newsletter&utm_medium=email&utm_campaign=mailsender_800&mid=' . $_GET['mid']);
                            break;    
                        default:
                            header('location: /?utm_source=newsletter&utm_medium=email&utm_campaign=mailsender_800&mid=' . $_GET['mid']);
                        break;
                    }
                    
                    break;
                
                default:
                    header('location: /?utm_source=newsletter&utm_medium=email&utm_campaign=mailsender_800&mid=' . $_GET['mid']);
                    break;
            }
        }
    }