<?
    namespace smashEngine\core;
    
    use \DateTime;
    use \Exception;
    
    class Controller {
        
        public $router;
        public $model;
        public $view;
        public $user;
        public $page;
        public $basket;
        public $VARS = [];
         
        function __construct(\Routing\Router $router)
        {
            session_start();
            
            if (!$_SESSION['csrf_token']) {
                $_SESSION['csrf_token'] = md5(session_id() . CSRF_SALT);
            }

            $this->router = $router;
            
            $this->view = new View;
            
            // Текущий пользователь
            $this->user = \application\models\user::load();
            
            // Объект страницы
            $this->page = new Page;
            
            // Пользовательская корзина
            try
            {
                $this->basket = new \application\models\basket($this->user->session['user_basket_id'], $this->user);
                
                if ($this->page->lang == 'en') {
                    $this->basket->setCurrency('usd');
                }
                
                // Если пользователь авторизовался после создания корзины меняем её владельца
                if ($this->basket->user_basket_status == 'active' && $this->user->id != $this->basket->user_id && $this->user->authorized) {
                    $this->basket->user_id = $this->user->id;
                    $this->basket->save();
                }
                
                // если вдруг по какой-то причине не обнулилась сессия после оформления заказа, делаем это вручную
                if ($this->basket->user_basket_status != 'active') {
                    $this->user->setSessionValue(['user_basket_id' => 0]);
                    $this->basket = new \application\models\basket('', $this->user);
                }
            }
            catch (Exception $e) 
            {
                // Корзина не обнаружена
                if ($e->getCode() == 1)
                {
                    $this->user->setSessionValue(['user_basket_id' => 0]);
                    $this->basket = new \application\models\basket('', $this->user);
                }
            }
            
            // кэшируем переменные
            if (!$this->VARS = App::memcache()->get('VARS')) 
            {
                $sth = App::db()->query("SELECT `variable_name`, `variable_value` FROM `variables`");
                foreach($sth->fetchAll() AS $v) {
                    $this->VARS[$v['variable_name']] = $v['variable_value'];
                }
                App::memcache()->set('VARS', $this->VARS, false, 7200);
            }
            
            // получаем курс доллара
            $this->VARS['usdRate'] = usdRateDaily();
            
            // импортируем статику на страницу
            $this->page->import(array(
                '/public/js/jquery.2.2.4.min.js', 
                '/public/js/jquery-migrate-1.1.1.min.js',
                '/public/js/jquery.cookie.min.js',
                '/public/js/jquery-ui.js',
                '/public/css/styles.css', 
            ));
            
            if (!$this->page->isAjax) 
            {
                // импортируем статику на страницу (только не для ajax)
                $this->page->import(array(
                    '/public/js/main.js', 
                    '/public/js/basket.quick.js',
                )); 
            }
            
            // =====================================================================================================================
            // определение языковой версии сайта
            // =====================================================================================================================
            if (!$_COOKIE['language']) 
            {
                if ($this->user->country && !in_array(strtolower($this->user->country), array('ru','by','kz','ua','az','am','kg','md','tj','tm','uz')))
                    $this->page->setLanguage('en');
                else
                    $this->page->setLanguage('ru');
            }
            
            // =====================================================================================================================
            // Отправляем все глобальные объекты в шаблон
            // =====================================================================================================================
            $this->view->setVar('CONTROLER', $this);
            $this->view->setVar('USER', $this->user);
            $this->view->setVar('PAGE', $this->page);
            $this->view->setVar('L', $this->page->translate);
            $this->view->setVar('basket', $this->basket);

            $this->view->setVar('CURRENT_URL', urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
            $this->view->setVar('REQUEST_URI', $_SERVER['REQUEST_URI']);
            $this->view->setVar('HTTP_REFERER', $_SERVER['HTTP_REFERER']);
            $this->view->setVar('PAGE_URI', toTranslit(urldecode($_SERVER['REQUEST_URI'])));
            $this->view->setVar('reqUrl', $this->page->reqUrl);
            $this->view->setVar('ogUrl', $this->page->ogUrl);
            $this->view->setVar('ogUrlAlt', rtrim((strpos($this->page->url, '/' . $this->page->lang) === 0 ? substr($this->page->url, 3) : $this->page->url), '/'));
            
            $this->view->setVar('dollarRate', $this->VARS['usdRate']);
            $this->view->setVar('datetime', array('year' => date('Y'), 'month' => date('m'), 'day' => date('d'), 'hour' => date('H'), 'minute' => date('i'), 'dayofweek' => date('w')));
            
            $this->view->setVar('contact_phone', $this->VARS['contactPhone1']);
            $this->view->setVar('operation_time', $this->VARS['operation_time']);
            $this->view->setVar('operation_time_1', $this->VARS['operation_time_1']);
            $this->view->setVar('operation_time_2', $this->VARS['operation_time_2']);
            $this->view->setVar('operation_time_3', $this->VARS['operation_time_3']);
            
            $this->view->setVar('session_name', session_name());
            $this->view->setVar('session_id', session_id());
            
            $this->view->setVar('rand', rand(1,2));
            $this->view->setVar('random', rand(1,1000));
            
            $this->view->setVar('csrf_token', $_SESSION['csrf_token']);
        }
        
        /**
         * Перехват несуществующего метода
         */
        function __call($name, array $params)
        {
            header('HTTP/1.1 404 Not Found');
            header("Status: 404 Not Found");
            
            $this->view->generate('', '404.tpl');
        }
        
        function action_index()
        {
        }
    
        function page404()
        {
            header('HTTP/1.1 404 Not Found');
            $this->view->generate('404.tpl');
            exit();
        }
    }
?>