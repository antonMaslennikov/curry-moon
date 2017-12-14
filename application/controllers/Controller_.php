<?php
    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
    
    use \PDO;
    use \Exception;
    use \DateTime;
    
    class Controller_ extends \smashEngine\core\Controller
    {
        public function __construct(\Routing\Router $router)
        {
            parent::__construct($router);
            
            // Текущий пользователь
            $this->user = \application\models\user::load();
            
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
            catch (appException $e) 
            {
                // Корзина не обнаружена
                if ($e->getCode() == 1)
                {
                    $this->user->setSessionValue(['user_basket_id' => 0]);
                    $this->basket = new \application\models\basket('', $this->user);
                }
            }
            
            // кэшируем переменные
            //if (!$this->VARS = App::memcache()->get('VARS')) 
            //{
                $sth = App::db()->query("SELECT `variable_name`, `variable_value` FROM `variables`");
                foreach($sth->fetchAll() AS $v) {
                    $this->VARS[$v['variable_name']] = $v['variable_value'];
                }
            //    App::memcache()->set('VARS', $this->VARS, false, 7200);
            //}
            
            // получаем курс доллара
            //$this->VARS['usdRate'] = usdRateDaily();
            
            // импортируем статику на страницу
            $this->page->import(array(
                '/public/css/styles.css',
            ));
            
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
            // всплывающие сообщения
            // =====================================================================================================================
            if ($message = $this->page->getFlashMessage()) {
                $this->view->setVar('FlashMessage', $message);
            }
            
            // =====================================================================================================================
            // Отправляем все глобальные объекты в шаблон
            // =====================================================================================================================
            $this->view->setVar('CONTROLER', $this);
            $this->view->setVar('USER', $this->user);
            $this->view->setVar('PAGE', $this->page);
            $this->view->setVar('L', $this->page->translate);
            $this->view->setVar('basket', $this->basket);
            $this->view->setVar('VARS', $this->VARS);
            
            $this->view->setVar('CURRENT_URL', urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
            $this->view->setVar('REQUEST_URI', $_SERVER['REQUEST_URI']);
            $this->view->setVar('HTTP_REFERER', $_SERVER['HTTP_REFERER']);
            $this->view->setVar('PAGE_URI', toTranslit(urldecode($_SERVER['REQUEST_URI'])));
            $this->view->setVar('reqUrl', $this->page->reqUrl);
            $this->view->setVar('ogUrl', $this->page->ogUrl);
            $this->view->setVar('ogUrlAlt', rtrim((strpos($this->page->url, '/' . $this->page->lang) === 0 ? substr($this->page->url, 3) : $this->page->url), '/'));
            $this->view->setVar('mainUrl', mainUrl);
                
            //$this->view->setVar('dollarRate', $this->VARS['usdRate']);
            $this->view->setVar('datetime', array('year' => date('Y'), 'month' => date('m'), 'day' => date('d'), 'hour' => date('H'), 'minute' => date('i'), 'dayofweek' => date('w')));
            
            $this->view->setVar('contact_phone', $this->VARS['contactPhone1']);
            $this->view->setVar('operation_time', $this->VARS['operation_time']);
            $this->view->setVar('operation_time_1', $this->VARS['operation_time_1']);
            $this->view->setVar('operation_time_2', $this->VARS['operation_time_2']);
            $this->view->setVar('operation_time_3', $this->VARS['operation_time_3']);
            $this->view->setVar('contact_email', $this->VARS['contactEmail']);
            
            $this->view->setVar('session_name', session_name());
            $this->view->setVar('session_id', session_id());
            
            $this->view->setVar('rand', rand(1,2));
            $this->view->setVar('random', rand(1,1000));
            
            $this->view->setVar('csrf_token', $_SESSION['csrf_token']);
            $this->view->setVar('appMode', appMode);
        }
    }