<?php
    namespace application\controllers;
    
    class Controller_static_page extends \smashEngine\core\Controller
    {
        public function action_privacy_policy()
        {
            $this->page->tpl = 'privacy-policy.tpl';
            $this->view->generate('index.tpl');
        }
        
        public function action_private_policy()
        {
            $this->page->tpl = 'private_policy.tpl';
            $this->view->generate('index.tpl'); 
        }
    
        public function action_vk_help_maryjane()
        {
            $this->page->tpl = 'vk_help_maryjane.tpl';
            $this->view->generate('index.tpl');
        }
        
        public function action_blog_promo_1()
        {
            $this->view->generate('blog/promo/1/index.tpl');
        }
    }