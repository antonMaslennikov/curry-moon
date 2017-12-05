<?php
    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
    use \smashEngine\core\exception\appException;

    use \application\models\user;
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
           
            if ($_POST['login'])
            {
                if (!empty($_POST['password']))
                {   
                    $sth = App::db()->prepare("SELECT `id`, `user_login`, `user_status`, `user_email`, `user_phone`, `user_activation`
                                      FROM `users` 
                                      WHERE 
                                            (`user_login` = :login OR `user_email` = :login OR `user_phone` = :login) 
                                        AND `user_password` = :password
                                        AND `user_status` != 'deleted' 
                                      LIMIT 1");

                    $sth->execute(array(
                        'login' => $_POST['login'],
                        'password' => md5(SALT . $_POST['password']),
                    ));

                    if (!$row = $sth->fetch())
                    {
                        $this->page->setFlashMessage('Указан неправильный логин или пароль');

                        $this->page->refresh();
                    } 
                    else 
                    {
                        if ($row['user_status'] == 'banned')
                        {
                            $this->page->setFlashMessage('Данный пользователь забанен');

                            $this->page->refresh();
                        }
                        else
                        {
                            if (!empty($row['user_email']) && $row['user_activation'] != 'done')
                            {
                                $this->page->setFlashMessage('Активация данного профиля ещё не выполнена');

                                $this->page->refresh();
                            }
                            else
                            {
                                $this->user->id = $row['id'];
                                $this->user->authorize();
                                
                                if (!$_POST['remember']) 
                                    $this->user->setSessionValue(['session_short' => '1']);

                                $this->page->refresh();
                            }
                        }
                    }
                } else {
                    $this->view->setVar('ERROR', array('text' => 'Пароль не можен быть пустым.'));
                }
            }
            
            $this->view->generate($this->page->index_tpl);
        }
        
        public function action_logout()
        {
            App::memcache()->delete('user' . $this->user->id);
    
            $this->user->setSessionValue(['user_id' => -1, 'session_logged' => 0]);
            
            $this->page->refresh();
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
            
            // сохранение
            if ($_POST['submit'])
            {
                try
                {
                    if ($_POST['csrf_token'] != $_SESSION['csrf_token']) {
                        throw new appException('Ошибка при проверке токена', 1);
                    }
                    
                    if (empty($_POST['email'])) {
                        throw new appException("Вы не указали ваш Email");
                    }

                    if (!validateEmail($_POST['email'])) {
                        throw new appException("Cкорее всего Вы ошиблись при вводе почты, пожалуйста проверьте эти данные. Если введено все верно, напишите нам для ручной активации.");
                    }
                    
                    $user_email = substr(strtolower(trim(strip_tags($_POST['email']))), 0, 50);
                    $user_phone = normalizePhone(substr(trim(strip_tags($_POST['phone_1'])), 0, 20));
                    $user_name  = substr(trim(strip_tags($_POST['last_name'])), 0, 50) . ' ' . substr(trim(strip_tags($_POST['first_name'])), 0, 50) . ' ' . substr(trim(strip_tags($_POST['middle_name'])), 0, 50);
                    $user_zip = substr(strtolower(trim(strip_tags($_POST['zip']))), 0, 50);
                    $user_address = substr(strtolower(trim(strip_tags($_POST['address_1']))), 0, 200);
                    $user_country_id = intval($_POST['country_id']);
                    $user_city_id = cityName2id($_POST['city'], '', true);
                        
                    $sth = App::db()->prepare("SELECT * FROM `" . user::$dbtable . "` WHERE `user_email` = ?");
                    $sth->execute([$user_email]);

                    $users = $sth->rowCount();
                    
                    // если активных аккаунтов однин
                    if ($users == 1)
                    {
                        $u = $sth->fetch();

                        if ($u['user_status'] == 'banned' || $u['user_status'] == 'deleted')
                        {
                            throw new appException('Регистрация на указанный почтовый адрес невозможна. Он заблокирован.');
                        }
                        elseif ($u['user_status'] == 'active')
                        {
                            throw new appException('Данный Email уже занят одним из наших пользователей');
                        }
                    }
                    // если активных аккаунтов более одного
                    elseif ($users > 1)
                    {
                        throw new appException('Данный Email уже занят одним из наших пользователей');
                    }
                    // если email не занят
                    else
                    {
                        $dog = strpos($user_email, '@');
                        $user_login = (($dog > 3) ? substr($user_email, 0, $dog - 4) : '') . '****' . substr($user_email, $dog);

                        // если указан телефон, то придумываем код из  цифр и отправляем смс
                        // Втыкаем юзера.
                        $this->user->create(array(
                            'user_login'        => $user_login,
                            'user_email'        => $user_email,
                            'user_name'         => $user_name,
                            'user_zip'          => $user_zip,
                            'user_address'      => $user_address,
                            'user_country_id'   => $user_country_id,
                            'user_city_id'      => $user_city_id,
                        ));
                        
                        $this->page->go('/ru/users/activate/' . $this->user->id);
                    }
                }
                catch (appException $e) 
                {
                    $this->view->setVar('error', $e->getMessage());

                    $this->view->setVar('POST', $_POST);
                }
            }
            
            $this->view->generate($this->page->index_tpl);
        }
        
        /**
         * успешное окончание регистрации
         */
        public function action_registration_finish()
        {
            $this->page->index_tpl = 'index.tpl';
            $this->page->tpl = 'users/registration-finish.tpl';
            
            $this->page->breadcrump[] = ['caption' => 'Регистрации подтверждена'];
            
            $this->view->generate($this->page->index_tpl);   
        }
        
        /**
         * подтверждение регистрации пользователя
         */ 
        public function action_activate() 
        {
            $this->page->index_tpl = 'index.tpl';
            $this->page->tpl = 'users/registration-activate.tpl';
            
            $this->page->breadcrump[] = ['caption' => 'Подтверждение регистрации'];
            
            try
            {
                if ($_GET['key']) {
                    if ($_GET['userid'] && $_GET['key']) {
                        if (md5(SALT . $_GET['userid']) != $_GET['key']) {
                            throw new appException('Указан неверный код активации');
                        }

                        $U = new user($_GET['userid']);
                        $U->activate();

                        $this->user->setSessionValue(['user_id' => $U->id, 'session_logged' => 1]);

                        $this->page->go('/ru/users/registration-finish');

                    } else {
                        throw new appException('Не достаточно данных для активации');
                    }
                }
            }
            catch (appException $e)
            {
                $this->view->setVar('error', $e->getMessage());
            }
            
            $this->view->generate($this->page->index_tpl);
        }
        
        /**
         * забыли пароль
         */
        public function action_forgot_password()
        {
            $this->page->index_tpl = 'index.tpl';
            $this->page->tpl = 'users/forgot_password.tpl';
            $this->page->title = 'Восстановить пароль';
            $this->page->addBreadCrump('Восстановить пароль');
            
            try
            {
                if ($_POST['csrf_token'] != $_SESSION['csrf_token']) {
                    throw new appException('Ошибка при проверке токена', 1);
                }
                
                // подтверждение
                if ($this->page->reqUrl[3] == 'confirm') 
                {
                    if ($_POST['jform']['token'] || $_GET['token']) 
                    {

                    }
                }
                // отправка кода
                elseif (empty($this->page->reqUrl[3]))
                {
                    if ($_POST['jform']['email']) 
                    {

                    }
                }
            }
            catch (appException $e)
            {
                $this->view->setVar('error', $e->getMessage());
            }
            
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