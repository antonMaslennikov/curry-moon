<?php
    
    namespace admin\application;

    class routings extends \smashEngine\core\Routings {
        
        var $classesBase = 'admin\application\controllers\\';
        
        var $data = [

	        'api_transliterate' =>[
		        'pattern' => '/admin/api/transliterate',
		        'action' => 'Controller_api:action_transliterate',
		        'schemas' => 'GET'
		    ],

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

	        'product_list' => [
		        'pattern'=>'/admin/product/list',
		        'action'=> 'Controller_product:action_index',
		        'schemas' => 'GET'
	        ],
            
            'product_create' => [
		        'pattern'=>'/admin/product/create',
		        'action'=> 'Controller_product:action_create',
		        'schemas' => 'GET|POST|FILES'
	        ],

	        'product_update' => [
		        'pattern'=>'/admin/product/update',
		        'action'=> 'Controller_product:action_update',
		        'schemas' => 'GET|POST'
	        ],
            
            
	        'category_list' => [
		        'pattern'=>'/admin/product_category/list',
		        'action'=> 'Controller_category:action_index',
		        'schemas' => 'GET'
	        ],

	        'category_create_tree' => [
		        'pattern'=>'/admin/product_category/createTree',
		        'action'=> 'Controller_category:action_createTree',
		        'schemas' => 'GET|POST'
	        ],

	        'category_update' =>[
		        'pattern'=>'/admin/product_category/update',
		        'action'=> 'Controller_category:action_update',
		        'schemas' => 'GET|POST'
	        ],

	        'category_create' =>[
		        'pattern'=>'/admin/product_category/create',
		        'action'=> 'Controller_category:action_create',
		        'schemas' => 'GET|POST'
	        ],

	        'category_delete' =>[
		        'pattern'=>'/admin/product_category/delete',
		        'action'=> 'Controller_category:action_delete',
		        'schemas' => 'GET'
	        ],

	        'page_list' =>[
		        'pattern'=>'/admin/page',
		        'action'=> 'Controller_page:action_index',
		        'schemas' => 'GET'
	        ],

	        'page_update' =>[
		        'pattern'=>'/admin/page/update',
		        'action'=> 'Controller_page:action_update',
		        'schemas' => 'GET|POST'
	        ],

	        'page_create' =>[
		        'pattern'=>'/admin/page/create',
		        'action'=> 'Controller_page:action_create',
		        'schemas' => 'GET|POST'
	        ],

	        'page_delete' =>[
		        'pattern'=>'/admin/page/delete',
		        'action'=> 'Controller_page:action_delete',
		        'schemas' => 'GET'
	        ],

	        'menu_list' =>[
		        'pattern'=>'/admin/menu',
		        'action'=> 'Controller_menu:action_index',
		        'schemas' => 'GET'
	        ],

	        'menu_update' =>[
		        'pattern'=>'/admin/menu/update',
		        'action'=> 'Controller_menu:action_update',
		        'schemas' => 'GET|POST'
	        ],

	        'menu_create' =>[
		        'pattern'=>'/admin/menu/create',
		        'action'=> 'Controller_menu:action_create',
		        'schemas' => 'GET|POST'
	        ],

	        'menu_delete' =>[
		        'pattern'=>'/admin/menu/delete',
		        'action'=> 'Controller_menu:action_delete',
		        'schemas' => 'GET'
	        ],

	        'menu_item_list' =>[
		        'pattern'=>'/admin/menu/item',
		        'action'=> 'Controller_menu:action_item',
		        'schemas' => 'GET'
	        ],

	        'menu_item_update' =>[
		        'pattern'=>'/admin/menu/item/update',
		        'action'=> 'Controller_menu:action_item_update',
		        'schemas' => 'GET|POST'
	        ],

	        'menu_item_create' =>[
		        'pattern'=>'/admin/menu/item/create',
		        'action'=> 'Controller_menu:action_item_create',
		        'schemas' => 'GET|POST'
	        ],

	        'menu_item_delete' =>[
		        'pattern'=>'/admin/menu/item/delete',
		        'action'=> 'Controller_menu:action_item_delete',
		        'schemas' => 'GET'
	        ],

	        'blog_list'=>[
		        'pattern'=>'/admin/blog/list',
		        'action'=> 'Controller_blog:action_index',
		        'schemas' => 'GET'
	        ],

	        'blog_create'=>[
		        'pattern'=>'/admin/blog/create',
		        'action'=> 'Controller_blog:action_create',
		        'schemas' => 'GET|POST'
	        ],

	        'blog_update'=>[
		        'pattern'=>'/admin/blog/update',
		        'action'=> 'Controller_blog:action_update',
		        'schemas' => 'GET|POST'
	        ],

	        'blog_delete'=>[
		        'pattern'=>'/admin/blog/delete',
		        'action'=> 'Controller_blog:action_delete',
		        'schemas' => 'GET'
	        ],
        ];
    }