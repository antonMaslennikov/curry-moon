<?php
    
    namespace application;

    class routings extends \smashEngine\core\Routings {
        
        var $classesBase = 'application\controllers\\';
        
        var $data = [
            'home' => [
                'pattern' => '/', 
                'action' => 'Controller_main:action_index', 
                'schemas' => 'GET',
	            'title' => 'Главная',
	            'url' => '/',
            ],
            
            'login' => [
                'pattern' => '/(ru|en)/users/login', 
                'action' => 'Controller_users:action_login', 
                'schemas' => 'GET|POST'
            ],
            
            'logout' => [
                'pattern' => '/(ru|en)/users/logout', 
                'action' => 'Controller_users:action_logout', 
                'schemas' => 'GET'
            ],
            
            'registration' => [
                'pattern' => '/(ru|en)/users/registration', 
                'action' => 'Controller_users:action_registration', 
                'schemas' => 'GET|POST'
            ],
            
            'registration-finish' => [
                'pattern' => '/(ru|en)/users/registration-finish', 
                'action' => 'Controller_users:action_registration_finish', 
                'schemas' => 'GET|POST'
            ],
            
            
            'activate' => [
                'pattern' => '/(ru|en)/users/activate/(.*)', 
                'action' => 'Controller_users:action_activate', 
                'schemas' => 'GET'
            ],
            
            'forgot-password' => [
                'pattern' => '/(ru|en)/users/forgot-password(.*)', 
                'action' => 'Controller_users:action_forgot_password', 
                'schemas' => 'GET|POST'
            ],
            
            'forgot-username' => [
                'pattern' => '/(ru|en)/users/forgot-username', 
                'action' => 'Controller_users:action_forgot_username', 
                'schemas' => 'GET|POST'
            ],
            
            'subscribe' => [
                'pattern' => '/(ru|en)/users/subscribe', 
                'action' => 'Controller_users:action_subscribe', 
                'schemas' => 'GET|POST'
            ],
            
            
            'contact-us' => [
                'pattern' => '/(ru|en)/contact-us', 
                'action' => 'Controller_contact_us:action_index', 
                'schemas' => 'GET|POST',
                'title' => 'Контакты',
            ],
            
            'shop' => [
                'pattern' => '/(ru|en)/shop(/?)(.*)', 
                'action' => 'Controller_shop:action_index', 
                'schemas' => 'GET',
	            'title' => 'Каталог',
            ],
            
            'aktcii' => [
                'pattern' => '/(ru|en)/aktcii(/?)(.*)', 
                'action' => 'Controller_aktcii:action_index', 
                'schemas' => 'GET',
	            'title' => 'Акции',
            ],
            
            'blog' => [
                'pattern' => '/(ru|en)/blog(/?)(.*)', 
                'action' => 'Controller_blog:action_index', 
                'schemas' => 'GET',
	            'title'=>'Блог',
            ],
            
            'lookbook' => [
                'pattern' => '/(ru|en)/lookbook(/?)(.*)', 
                'action' => 'Controller_lookbook:action_index', 
                'schemas' => 'GET',
	            'title'=>'Lookbook',
            ],

	        'generate_page'=>[
		        'pattern'=> '/(ru|en)/(.*)',
		        'action' => 'Controller_static_page:action_index',
		        'schemas' => 'GET'
	        ],
            /*
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