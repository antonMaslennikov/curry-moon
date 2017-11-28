<?php
    
    namespace application;

    class routings extends \smashEngine\core\Routings {
        
        var $classesBase = 'application\controllers\\';
        
        var $data = [
            'home' => [
                'pattern' => '/', 
                'action' => 'Controller_main:action_index', 
                'schemas' => 'GET'
            ],
            
            'login' => [
                'pattern' => '/(ru|en)/users/login', 
                'action' => 'Controller_users:action_login', 
                'schemas' => 'GET|POST'
            ],
            
            'registration' => [
                'pattern' => '/(ru|en)/users/registration', 
                'action' => 'Controller_users:action_registration', 
                'schemas' => 'GET|POST'
            ],
            
            'forgot-password' => [
                'pattern' => '/(ru|en)/users/forgot-password', 
                'action' => 'Controller_users:action_forgot_password', 
                'schemas' => 'GET|POST'
            ],
            
            'forgot-username' => [
                'pattern' => '/(ru|en)/users/forgot-username', 
                'action' => 'Controller_users:action_forgot_username', 
                'schemas' => 'GET|POST'
            ],
            
            'contact-us' => [
                'pattern' => '/(ru|en)/contact-us', 
                'action' => 'Controller_contact_us:action_index', 
                'schemas' => 'GET'
            ],
            
            'about' => [
                'pattern' => '/(ru|en)/about', 
                'action' => 'Controller_static_page:action_index', 
                'schemas' => 'GET'
            ],
            'discount' => [
                'pattern' => '/(ru|en)/discount', 
                'action' => 'Controller_static_page:action_index', 
                'schemas' => 'GET'
            ],
            'payment' => [
                'pattern' => '/(ru|en)/payment', 
                'action' => 'Controller_static_page:action_index', 
                'schemas' => 'GET'
            ],
            'delivery' => [
                'pattern' => '/(ru|en)/delivery', 
                'action' => 'Controller_static_page:action_index', 
                'schemas' => 'GET'
            ],
            'sotrudnichestvo' => [
                'pattern' => '/(ru|en)/sotrudnichestvo', 
                'action' => 'Controller_static_page:action_index', 
                'schemas' => 'GET'
            ],
            'terms-and-conditions' => [
                'pattern' => '/(ru|en)/terms-and-conditions', 
                'action' => 'Controller_static_page:action_index', 
                'schemas' => 'GET'
            ],
            
            
        ];
    }