<?php 

    namespace smashEngine\core;
    
    use \PDO;
    use \Routing\Router;
    use \Routing\MatchedRoute;
    
    class App 
    {
        public static $db;
        public static $mail;
        public static $url;
        public static $sms;
        public static $memcache;
        
        private function __construct()
        {
        }
        
        public static function run()
        {
            $router = new Router(GET_HTTP_HOST());
            
            $router->add('home', '/', 'Controller_main:action_index', 'GET|POST');
            
            $router->add('about', '/about(/?)((us|projects|where)?)(/?)', 'Controller_about:action_index', 'GET|POST');
            
            $router->add('ajax', '/ajax/(.*)', 'Controller_ajax:action_index', 'GET|POST');
            
            $router->add('basket', '/basket/(.*)', 'Controller_basket:action_index', 'GET|POST');
            
            $router->add('blog', '/blog/(.*)', 'Controller_blog:action_index', 'GET|POST|FILES');
            
            $router->add('catalog', '/catalog/(.*)', 'Controller_catalog:action_index', 'GET|POST');
            
            $router->add('comment add', '/comment/add/(.*)', 'Controller_comment:action_add', 'GET|POST');
            $router->add('comment del', '/comment/delete/(.*)', 'Controller_comment:action_delete', 'GET');
            
            $router->add('contacts', '/contacts(/?)((us|projects|where)?)(/?)', 'Controller_about:action_index', 'GET|POST');

            $router->add('cronjob', '/cronjob/', 'Controller_cronjob:action_index');
            
            $router->add('coupon_activate', '/coupon/activate/([0-9а-яА-ЯёЁa-zA-Z_-]{2,50})/', 'Controller_coupon:action_activate', 'GET');
            
            $router->add('editprofile', '/editprofile/(.*)', 'Controller_editprofile:action_index', 'GET|POST|FILES');
            
            $router->add('faq', '/faq/(.*)', 'Controller_faq:action_index', 'GET|POST');
            
            $router->add('feedbackSave',    '/feedback/saveNew/', 'Controller_feedback:action_save', 'GET|POST');
            $router->add('feedbackUpload',  '/feedback/uploadPict/', 'Controller_feedback:action_upload', 'GET|POST|FILES');
            $router->add('feedbackIndex',   '/feedback/', 'Controller_feedback:action_index', 'GET|POST');
            
            $router->add('goto', '/goto/(.*)', 'Controller_goto:action_index', 'GET');
            
            $router->add('login',           '/login/', 'Controller_login:action_index', 'GET|POST');
            $router->add('logout',          '/logout/', 'Controller_login:action_logout', 'GET|POST');
            $router->add('logoutMobile',    '/login.mobile/', 'Controller_login:action_login_mobile');
            $router->add('loginQuick',      '/login/quick/', 'Controller_login:action_quick');
            $router->add('loginQuick2',     '/login/quick2/', 'Controller_login:action_quick2');
            $router->add('loginFb',         '/login/fb/(.*)', 'Controller_login:action_fb', 'GET|POST');
            $router->add('loginVk',         '/login/vk/(.*)', 'Controller_login:action_vk', 'GET|POST');
            $router->add('loginGplus',      '/login/gplus/(.*)', 'Controller_login:action_gplus', 'GET|POST');
            $router->add('loginInstagramm', '/login/instagram/(.*)', 'Controller_login:action_instagram', 'GET|POST');
            
            $router->add('language',        '/language/(.*)', 'Controller_language:action_index');
            
            $router->add('mailclick',        '/mailclick/(.*)', 'Controller_mailclick:action_index');
            
            $router->add('order', '/order/(.*)', 'Controller_order:action_index', 'GET|POST');
            $router->add('orderhistory', '/orderhistory/(.*)', 'Controller_orderhistory:action_index', 'GET|POST');
            
            $router->add('profile',        '/profile/(([0-9]{1,7})?)(/?)', 'Controller_profile:action_index');
            
            $router->add('private_policy',   '/private_policy/', 'Controller_static_page:action_private_policy');
            
            $router->add('registration',   '/registration/(.*)', 'Controller_registration:action_index', 'GET|POST|FILES');
            
            $router->add('subscribe',       '/subscribe/(.*)', 'Controller_subscribe:action_subscribe', 'GET|POST');
            $router->add('unsubscribe',     '/unsubscribe/(.*)', 'Controller_subscribe:action_unsubscribe', 'GET|POST');
            
            $router->add('s3thumbs', '/s3/thumbs/(.*)', 'Controller_s3:action_thumbs');
            $router->add('s3cache', '/s3/cache/(.*)', 'Controller_s3:action_cache');
            
            $router->add('sitemap main', '/sitemap/', 'Controller_sitemap:action_index');
    
			$router->add('set_location', '/set_location/((id:num)?)(/?)', 'Controller_catalog:action_set_location', 'GET');
           
            $router->add('search', '/search/((?!autocomplite).)*', 'Controller_catalog:action_index', 'GET|POST');
            
			$router->add('track', '/track/(.*)', 'Controller_track:action_index');
            
            $route = $router->match(GET_METHOD(), GET_PATH_INFO());
            
            if (null == $route) {
                $route = new MatchedRoute('Controller_404:action_index');
            }
            
            list($class, $action) = explode(':', $route->getController(), 2);

            $class = 'application\controllers\\' . $class;

            call_user_func_array(array(new $class($router), $action), $route->getParameters());
        }
        
        public static function db()
        {
            if (self::$db == NULL)
            {
                try
                {
                    self::$db = new PDO("mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS, array(
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", 
                        PDO::ATTR_PERSISTENT => true,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
                }
                catch (\PDOException $e)
                {
                    self::page503($e);
                }
            }

            return self::$db;
        }
        
        public static function mail()
        {
            if (self::$mail == NULL)
            {
                self::$mail = new \application\models\mail;
            }

            return self::$mail;
        }
        
        public static function sms()
        {
            if (self::$sms == null)
            {
                self::$sms = new \sms(SMSuser, SMSpassword, SMSsender);
            }

            return self::$sms;
        }
        
        public static function memcache() {
            if (self::$memcache == null) {
                self::$memcache = new \Memcache;
                self::$memcache->pconnect('unix:///tmp/memcached.sock',0);
            }
            
            return self::$memcache;
        }
        
        public static function url()
        {
            if (self::$url == NULL)
            {
                if (!$url = @parse_url($_SERVER['REQUEST_URI']))
                    $url['path'] = $_SERVER['REQUEST_URI'];
                
                self::$url = new stdClass;
                
                foreach (array_slice(explode('/', $url['path']), 1) as $k => $v) {
                    self::$url->{$k} = $v;
                } 
            }

            return self::$url;
        }
    
        /**
         * Показ страницы-заглушки "сайт не доступен"
         * @param Exception $e исключение, которое привело к недоступности сайта
         */
        public static function page503(\Exception $e = null) {
            
            $router = new Router(GET_HTTP_HOST());
            $route = new MatchedRoute('Controller_503:action_index', ['e' => $e]);
            
            list($class, $action) = explode(':', $route->getController(), 2);

            $class = 'application\controllers\\' . $class;

            call_user_func_array(array(new $class($router), $action), $route->getParameters());
        }
    
        /**
         * Показ страницы-заглушки "страница не найдена"
         */
        public static function page404() {
            $router = new Router(GET_HTTP_HOST());
            $route = new MatchedRoute('Controller_404:action_index');
            
            list($class, $action) = explode(':', $route->getController(), 2);

            $class = 'application\controllers\\' . $class;

            call_user_func_array(array(new $class($router), $action), $route->getParameters());
        }
    }
