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
                
                $this->page->breadcrump[] = ['link' => $this->page->url, 'caption' => $page->h1];
                
                $this->page->title = $page->h1;
                $this->page->keywords = $page->meta_keywords;
                $this->page->description = $page->meta_description;
                
                $this->view->setVar('sPage', $page);
                $this->view->setVar('PAGE', $this->page);
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