<?php
    namespace application\controllers;
    
    class Controller_contact_us extends Controller_
    {
        public function action_index()
        {
            $this->page->tpl = 'contact_us/index.tpl';
            $this->view->generate('index.tpl'); 
        }
    }