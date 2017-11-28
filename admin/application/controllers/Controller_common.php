<?php
    namespace admin\application\controllers;
    
    use \smashEngine\core\App AS App;
    
    use \PDO;
    use \Exception;
    use \DateTime;
    
    class Controller_common extends \smashEngine\core\Controller
    {
        public function execute(\smashEngine\core\Controller $c)
        {
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
            $this->view->setVar('mainUrl', mainUrl);
                
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
            $this->view->setVar('appMode', appMode);
        }
    }