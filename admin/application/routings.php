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

            'product_list' => [
		        'pattern'=>'/admin/product/list',
		        'action'=> 'Controller_product:action_index',
		        'schemas' => 'GET'
	        ],
            
            'product_create' => [
		        'pattern'=>'/admin/product/create',
		        'action'=> 'Controller_product:action_create',
		        'schemas' => 'GET'
	        ],
            
	        'product_category_list' => [
		        'pattern'=>'/admin/product_category/list',
		        'action'=> 'Controller_category:action_index',
		        'schemas' => 'GET'
	        ],

	        'product_category_create_tree' => [
		        'pattern'=>'/admin/product_category/createTree',
		        'action'=> 'Controller_category:action_createTree',
		        'schemas' => 'GET|POST'
	        ],

	        [
		        'pattern'=>'/admin/product_category/update',
		        'action'=> 'Controller_category:action_update',
		        'schemas' => 'GET|POST'
	        ]
        ];
    }