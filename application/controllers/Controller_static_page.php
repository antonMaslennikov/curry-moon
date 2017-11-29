<?php
    namespace application\controllers;
    
    use \application\models\staticPage;
    use \smashEngine\core\exception\appException;

    class Controller_static_page extends Controller_
    {
        public function action_index()
        {
            $this->page->tpl = 'static_pages/content.tpl';
            
            try
            {
                $page = staticPage::findBySlug($this->page->reqUrl[1]);
                
                $this->view->setVar('sPage', $page);
            }
            catch (appException $e)
            {
                if ($e->getCode() == 1) {
                    $this->page404();
                }
            }
            
            $this->view->generate('index.tpl'); 
        }
    }