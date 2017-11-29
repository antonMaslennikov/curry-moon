<?php
    
    namespace admin\application;

    class routings extends \smashEngine\core\Routings {
        
        var $classesBase = 'admin\application\controllers\\';
        
        var $data = [
            'dashboard' => [
                'pattern' => '/admin/', 
                'action' => 'Controller_dashboard:action_index', 
                'schemas' => 'GET'
            ],

            'authorization' => [
                'pattern' => '/admin/authorization/', 
                'action' => 'Controller_authorization:action_index', 
                'schemas' => 'GET|POST'
            ],

	        'example' => [
		        'pattern'=>'/admin/example',
		        'action'=> 'Controller_example:action_index',
		        'schemas' => 'GET|POST'
	        ],
        ];
    }