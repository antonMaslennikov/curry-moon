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
        
        private function __construct() {
            // загрушка, чтобы не могли создать экземпляры класса
        }
        
        public static function run(Routings $rounting)
        {
            $router = new Router(GET_HTTP_HOST());
            
            foreach ($rounting->get() AS $k => $r) {
                if ($r['pattern'] && $r['action']) {
                    $router->add($k, $r['pattern'], $r['action'], $r['schemas']);
                } else {
                    throw new exception\appException('Некорректное правило разобра url: ' . implode(' ', $r));
                }
            }
            
            $route = $router->match(GET_METHOD(), GET_PATH_INFO());
            
            if (null == $route) {
                $route = new MatchedRoute($rounting->classesBase . 'Controller_404:action_index');
            }
            
            list($class, $action) = explode(':', $route->getController(), 2);

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
