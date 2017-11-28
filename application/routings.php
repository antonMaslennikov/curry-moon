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
        ];
    }