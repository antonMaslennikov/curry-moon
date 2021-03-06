<?php
    
    namespace application;

    class routings extends \smashEngine\core\Routings {
        
        var $classesBase = 'application\controllers\\';
        
        var $data = [
            'home' => [
                'pattern' => '/(ru|en)?', 
                'action' => 'Controller_main:action_index', 
                'schemas' => 'GET',
	            'title' => 'Главная',
	            'url' => '/',
            ],
            
            'cart-add' => [
                'pattern' => '/((ru|en)/)?cart/add', 
                'action' => 'Controller_cart:action_add', 
                'schemas' => 'GET|POST',
            ],
            
            'cart' => [
                'pattern' => '/((ru|en)/)?cart', 
                'action' => 'Controller_cart:action_index', 
                'schemas' => 'GET|POST',
            ],
            
            'cart-quick' => [
                'pattern' => '/((ru|en)/)?cart/quick', 
                'action' => 'Controller_cart:action_quick', 
                'schemas' => 'GET',
            ],
            
            'cart-products' => [
                'pattern' => '/((ru|en)/)?cart/products', 
                'action' => 'Controller_cart:action_products', 
                'schemas' => 'GET',
            ],
            
            'cart-delete' => [
                'pattern' => '/((ru|en)/)?cart/delete', 
                'action' => 'Controller_cart:action_delete', 
                'schemas' => 'GET',
            ],
            
            'cart-updatecart' => [
                'pattern' => '/((ru|en)/)?cart/updatecart', 
                'action' => 'Controller_cart:action_updatecart', 
                'schemas' => 'GET|POST',
            ],
            
            'cart-coupon' => [
                'pattern' => '/((ru|en)/)?cart/setcoupon', 
                'action' => 'Controller_cart:action_setcoupon', 
                'schemas' => 'GET|POST',
            ],
            
            'cart-terms' => [
                'pattern' => '/((ru|en)/)?cart/terms-of-service', 
                'action' => 'Controller_cart:action_terms', 
                'schemas' => 'GET',
            ],
            
            'cart-confirm' => [
                'pattern' => '/((ru|en)/)?cart/confirm/(id:num)', 
                'action' => 'Controller_cart:action_confirm', 
                'schemas' => 'GET',
            ],
            
            
            'login' => [
                'pattern' => '/((ru|en)/)?users/login', 
                'action' => 'Controller_users:action_login', 
                'schemas' => 'GET|POST'
            ],
            
            'logout' => [
                'pattern' => '/((ru|en)/)?users/logout', 
                'action' => 'Controller_users:action_logout', 
                'schemas' => 'GET'
            ],
            
            'registration' => [
                'pattern' => '/((ru|en)/)?users/registration', 
                'action' => 'Controller_users:action_registration', 
                'schemas' => 'GET|POST'
            ],
            
            'registration-finish' => [
                'pattern' => '/((ru|en)/)?users/registration-finish', 
                'action' => 'Controller_users:action_registration_finish', 
                'schemas' => 'GET|POST'
            ],
            
            
            'activate' => [
                'pattern' => '/((ru|en)/)?users/activate(.*)', 
                'action' => 'Controller_users:action_activate', 
                'schemas' => 'GET'
            ],
            
            'forgot-password' => [
                'pattern' => '/((ru|en)/)?users/forgot-password(.*)', 
                'action' => 'Controller_users:action_forgot_password', 
                'schemas' => 'GET|POST'
            ],
            
            'forgot-username' => [
                'pattern' => '/((ru|en)/)?users/forgot-username', 
                'action' => 'Controller_users:action_forgot_username', 
                'schemas' => 'GET|POST'
            ],
            
            'subscribe' => [
                'pattern' => '/((ru|en)/)?users/subscribe', 
                'action' => 'Controller_users:action_subscribe', 
                'schemas' => 'GET|POST'
            ],
            
            
            'contact-us' => [
                'pattern' => '/((ru|en)/)?contact-us', 
                'action' => 'Controller_contact_us:action_index', 
                'schemas' => 'GET|POST',
                'title' => 'Контакты',
            ],
            
            
            'shop-openproduct' => [
                'pattern' => '/((ru|en)/)?shop/openproduct/(id:num)', 
                'action' => 'Controller_shop:action_openproduct', 
                'schemas' => 'GET',
            ],
            
            'shop' => [
                'pattern' => '/(((ru|en)/)?)?shop(/?)((openproduct)?)(.*)', 
                'action' => 'Controller_shop:action_index', 
                'schemas' => 'GET',
	            'title' => 'Каталог',
            ],
            
            'aktcii' => [
                'pattern' => '/((ru|en)/)?aktcii$', 
                'action' => 'Controller_blog:action_aktcii', 
                'schemas' => 'GET',
	            'title' => 'Акции',
            ],
            
            'blog' => [
                'pattern' => '/((ru|en)/)?blog$', 
                'action' => 'Controller_blog:action_index', 
                'schemas' => 'GET',
	            'title'=>'Блог',
            ],
            
            'blog-view' => [
                'pattern' => '/((ru|en)/)?(blog|aktcii)/[a-zA-Z0-9-_]*', 
                'action' => 'Controller_blog:action_view', 
                'schemas' => 'GET',
            ],
            
            'blog-archive' => [
                'pattern' => '/((ru|en)/)?date/[0-9]{4}/[0-9]{1,}', 
                'action' => 'Controller_blog:action_archive', 
                'schemas' => 'GET',
            ],
            
            'blog-tegi' => [
                'pattern' => '/((ru|en)/)?tegi/[a-zA-Z0-9-_]*', 
                'action' => 'Controller_blog:action_tegi', 
                'schemas' => 'GET',
            ],
            
            'lookbook' => [
                'pattern' => '/((ru|en)/)?lookbook(/?)(.*)', 
                'action' => 'Controller_lookbook:action_index', 
                'schemas' => 'GET',
	            'title'=>'Lookbook',
            ],
            
            
            'orders-list'=>[
		        'pattern'=> '/((ru|en)/)?orders',
		        'action' => 'Controller_orders:action_index',
		        'schemas' => 'GET'
	        ],
            
            'orders-view'=>[
		        'pattern'=> '/((ru|en)/)?orders/(id:num)',
		        'action' => 'Controller_orders:action_view',
		        'schemas' => 'GET'
	        ],

	        'generate_page'=>[
		        'pattern'=> '/((ru|en)/)?(.*)',
		        'action' => 'Controller_static_page:action_index',
		        'schemas' => 'GET'
	        ],
            
            /*
            'about' => [
                'pattern' => '/((ru|en)/)?about', 
                'action' => 'Controller_static_page:action_index', 
                'schemas' => 'GET'
            ],
            'discount' => [
                'pattern' => '/((ru|en)/)?discount', 
                'action' => 'Controller_static_page:action_index', 
                'schemas' => 'GET'
            ],
            'payment' => [
                'pattern' => '/((ru|en)/)?payment', 
                'action' => 'Controller_static_page:action_index', 
                'schemas' => 'GET'
            ],
            'delivery' => [
                'pattern' => '/((ru|en)/)?delivery', 
                'action' => 'Controller_static_page:action_index', 
                'schemas' => 'GET'
            ],
            'sotrudnichestvo' => [
                'pattern' => '/((ru|en)/)?sotrudnichestvo', 
                'action' => 'Controller_static_page:action_index', 
                'schemas' => 'GET'
            ],
            'terms-and-conditions' => [
                'pattern' => '/((ru|en)/)?terms-and-conditions', 
                'action' => 'Controller_static_page:action_index', 
                'schemas' => 'GET'
            ],*/
        ];


	    public function getMenuRoute() {

		    $menu = [];
		    foreach ($this->data as $route) {

			    if (isset($route['title'])) {

					$url = str_replace('(ru|en)', 'ru_en', $route['pattern']);
				    $url = preg_replace('/\(.*?\)/', '', $url);
				    $url = str_replace('ru_en', '(ru|en)', $url);

				    $menu[$url] = 'Страница сайта "'.$route['title'].'"';
			    }
		    }

		    ksort($menu);

		    return $menu;
		}
	}