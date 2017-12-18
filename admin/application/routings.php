<?php
    
    namespace admin\application;

    class routings extends \smashEngine\core\Routings {
        
        var $classesBase = 'admin\application\controllers\\';
        
        var $data = [
	        'login'=> [
		        'pattern' => '/admin/login',
		        'action' => 'Controller_access:action_login',
		        'schemas' => 'GET|POST'
		    ],

	        'logout'=> [
		        'pattern' => '/admin/logout',
		        'action' => 'Controller_access:action_logout',
		        'schemas' => 'GET'
	        ],

	        'api_transliterate' =>[
		        'pattern' => '/admin/api/transliterate',
		        'action' => 'Controller_api:action_transliterate',
		        'schemas' => 'GET'
		    ],

            'dashboard' => [
                'pattern' => '/admin', 
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

	        'product_list_filter' => [
		        'pattern'=>'/admin/product/list_filter',
		        'action'=> 'Controller_product:action_list_filter',
		        'schemas' => 'GET'
	        ],

	        'product_list_related' => [
		        'pattern'=>'/admin/product/list_related',
		        'action'=> 'Controller_product:action_list_related',
		        'schemas' => 'GET'
	        ],

	        'product_set_related' => [
		        'pattern'=>'/admin/product/set_related',
		        'action'=> 'Controller_product:action_set_related',
		        'schemas' => 'GET'
	        ],

	        'product_main_img' => [
		        'pattern'=>'/admin/product/mainImage',
		        'action'=> 'Controller_product:action_mainImage',
		        'schemas' => 'GET'
	        ],

	        'product_delete_img' => [
		        'pattern'=>'/admin/product/deleteImage',
		        'action'=> 'Controller_product:action_deleteImage',
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

	        'product_delete' =>[
		        'pattern'=>'/admin/product/delete',
		        'action'=> 'Controller_product:action_delete',
		        'schemas' => 'GET'
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
		        'schemas' => 'GET',
	        ],

	        'user_list'=>[
		        'pattern'=>'/admin/user/list',
		        'action'=> 'Controller_user:action_index',
		        'schemas' => 'GET',
	        ],

	        'user_create'=>[
		        'pattern'=>'/admin/user/create',
		        'action'=> 'Controller_user:action_create',
		        'schemas' => 'GET|POST',
	        ],

	        'user_update'=>[
		        'pattern'=>'/admin/user/update',
		        'action'=> 'Controller_user:action_update',
		        'schemas' => 'GET|POST',
	        ],

	        'user_delete'=>[
		        'pattern'=>'/admin/user/delete',
		        'action'=> 'Controller_user:action_delete',
		        'schemas' => 'GET|POST',
			],

	        'user_city'=>[
		        'pattern'=>'/admin/user/city',
		        'action'=> 'Controller_user:action_city',
		        'schemas' => 'GET',
	        ],

	        'user_employees'=>[
		        'pattern'=>'/admin/user/employees',
		        'action'=> 'Controller_user:action_employees',
		        'schemas' => 'GET',
	        ],

	        'user_add_access'=>[
		        'pattern'=>'/admin/user/add_access',
		        'action'=> 'Controller_user:action_add_access',
		        'schemas' => 'GET|POST',
	        ],

	        'user_access'=>[
		        'pattern'=>'/admin/user/access',
		        'action'=> 'Controller_user:action_access',
		        'schemas' => 'GET|POST',
	        ],

            'settings_list'=>[
		        'pattern'=>'/admin/settings(/list)?',
		        'action'=> 'Controller_settings:action_index',
		        'schemas' => 'GET|POST'
	        ],

	        'settings_create'=>[
		        'pattern'=>'/admin/settings/create',
		        'action'=> 'Controller_settings:action_create',
		        'schemas' => 'GET|POST'
	        ],

	        'settings_update'=>[
		        'pattern'=>'/admin/settings/update',
		        'action'=> 'Controller_settings:action_update',
		        'schemas' => 'GET|POST'
	        ],

	        'settings_delete'=>[
		        'pattern'=>'/admin/settings/delete',
		        'action'=> 'Controller_settings:action_delete',
		        'schemas' => 'GET',
	        ],
            
            
            'coupon_list'=>[
		        'pattern'=>'/admin/coupon(/list)?',
		        'action'=> 'Controller_coupon:action_index',
		        'schemas' => 'GET'
	        ],

	        'coupon_create'=>[
		        'pattern'=>'/admin/coupon/create',
		        'action'=> 'Controller_coupon:action_create',
		        'schemas' => 'GET|POST'
	        ],

	        'coupon_update'=>[
		        'pattern'=>'/admin/coupon/update',
		        'action'=> 'Controller_coupon:action_update',
		        'schemas' => 'GET|POST'
	        ],

	        'templates_delete'=>[
		        'pattern'=>'/admin/templates/delete',
		        'action'=> 'Controller_templates:action_delete',
		        'schemas' => 'GET',
	        ],
            
            
            'templates_list'=>[
		        'pattern'=>'/admin/templates(/list)?',
		        'action'=> 'Controller_templates:action_index',
		        'schemas' => 'GET'
	        ],

	        'templates_create'=>[
		        'pattern'=>'/admin/templates/create',
		        'action'=> 'Controller_templates:action_create',
		        'schemas' => 'GET|POST'
	        ],

	        'templates_update'=>[
		        'pattern'=>'/admin/templates/update',
		        'action'=> 'Controller_templates:action_update',
		        'schemas' => 'GET|POST'
	        ],

	        'templates_delete'=>[
		        'pattern'=>'/admin/templates/delete',
		        'action'=> 'Controller_templates:action_delete',
		        'schemas' => 'GET',
	        ],
  
            'orders_list'=>[
		        'pattern'=>'/admin/orders(/list)?',
		        'action'=> 'Controller_orders:action_index',
		        'schemas' => 'GET'
            ],
            
            'orders_view'=>[
		        'pattern'=>'/admin/orders/(view|print)',
		        'action'=> 'Controller_orders:action_view',
		        'schemas' => 'GET|POST|FILES'
            ],

	        'feedback_list'=>[
		        'pattern'=>'/admin/feedback/list',
		        'action'=> 'Controller_feedback:action_list',
		        'schemas' => 'GET',
	        ],

	        'feedback_list_send'=>[
		        'pattern'=>'/admin/feedback/list_send',
		        'action'=> 'Controller_feedback:action_list_send',
		        'schemas' => 'GET',
	        ],
        ];
    }