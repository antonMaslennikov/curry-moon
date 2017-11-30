<?php
    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
    use \smashEngine\core\exception\appException;

    use \application\models\mailSubscription;

    use \PDO;
    use \Exception;
    use \DateTime;
    
    class Controller_users extends Controller_
    {   
        /**
         * Вход
         */
        public function action_login()
        {
            $this->page->index_tpl = 'index.tpl';
            $this->page->tpl = 'users/login.tpl';
           
            $this->view->generate($this->page->index_tpl);
        }
        
        /**
         * регистрация
         */
        public function action_registration()
        {
            $this->page->index_tpl = 'index.tpl';
            $this->page->tpl = 'users/registration.tpl';
            $this->page->import(['/public/css/onepage.css']);
            
            $this->page->breadcrump[] = ['caption' => 'Регистрация'];
            
            $this->view->generate($this->page->index_tpl);
        }
        
        /**
         * забыли пароль
         */
        public function action_forgot_password()
        {
            $this->page->index_tpl = 'index.tpl';
            $this->page->tpl = 'users/forgot_password.tpl';
            
            $this->view->generate($this->page->index_tpl);
        }
        
        /**
         * забыли логин
         */
        public function action_forgot_username()
        {
            $this->page->index_tpl = 'index.tpl';
            $this->page->tpl = 'users/forgot_username.tpl';
            
            $this->view->generate($this->page->index_tpl);
        } 
        
        /**
         * подписка на рассылки
         */
        public function action_subscribe()
        {
            try
            {
                if ($_POST['csrf_token'] != $_SESSION['csrf_token']) {
                    throw new appException('Ошибка при проверке токена', 1);
                }
                
                if (!$_POST['user']['email']) {
                    throw new appException('Не указан адес электронной почты для подписки', 2);
                }
                
                if (!validateEmail($_POST['user']['email'])) {
                    throw new appException('То что вы указали не похоже на адрес электронной почты', 3);
                }
                
                if (mailSubscription::checkEmailSubscriptionStatus($_POST['user']['email']) > 0) {
                    throw new appException('Спасибо, Вы уже оформили подписку на новости', 4);
                }
                
                mailSubscription::addEmailSubscription($_POST['user']['email']);
                
                if ($this->page->isAjax) {
                    exit(json_encode(['status' => 'ok']));
                } else {
                    exit('Подписка офрмлена'); 
                }
            }
            catch (appException $e) 
            {
                if ($this->page->isAjax) {
                    exit(json_encode(['status' => 'error', 'message' => $e->getMessage()]));
                } else {
                    //  TODO сделать нормальный вывод ошибок
                    exit($e->getMessage()); 
                }
            }
        }
    }