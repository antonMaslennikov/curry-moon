<?
    namespace application\controllers;
    
    /**
     * смена языка страниц
     */    
    class Controller_language extends \smashEngine\core\Controller
    {
        public function action_index()
        {
            if (empty($this->page->reqUrl[1]) || !in_array($this->page->reqUrl[1], array('ru', 'en')))
                $l = 'ru';
            else
                $l = $this->page->reqUrl[1];
        
            $this->page->setLanguage($l);
        
            if ($_SERVER['HTTP_REFERER']) {
                $this->page->go(str_replace('/en/', '/', $_SERVER['HTTP_REFERER']));
            } else {
                $this->page->go('/');
            }
        }
    }