<?php

    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
    
    use \application\models\user AS user;
    
    use \Exception;
    
    class Controller_registration extends \smashEngine\core\Controller
    {
        public function action_index()
        {
            $this->page->onindex = TRUE;
            $this->page->tpl = $this->page->module . '/registration.tpl';
            $this->page->footer_tpl = 'order/footer.tpl';
            
            $this->page->breadcrump[] = array(
                'link'    => '/' . $this->page->module . '/',
                'caption' => 'Регистрация');
            
            $this->page->import(['/js/registration.v2.js']);
            
            $this->view->setVar('action', $this->page->reqUrl[1]);
            
            if ($m = $this->page->getFlashMessage()) {
                $this->view->setVar('message', $m);
            }
            
            /**
             * Форма регистрации
             */
            if (empty($this->page->reqUrl[1]))
            {
                $this->page->tpl = $this->page->module . '/form.tpl';
                
                $this->page->import([
                    '/css/jquery.autocomplete.css', 
                    '/js/jquery.autocomplete.pack.js',
                    '/css/registration.css', 
                ]);
                
                // пользователь авторизован
                if ($this->user->authorized && !$this->user->meta->mjteam) {
                    $this->page->go('/');
                }
                
                $this->view->setVar('byears', range(1950, date('Y')));
                
                // Форма
                if (!$_POST['submit'])
                {
                    $this->view->setVar('ref', $_SERVER['HTTP_REFERER']);
                    
                    if ($_GET['email'])
                        $this->view->setVar('POST', array('email' => $_GET['email']));
                }
                // Обработчик формы
                else
                {
                    $error = '';
            
                    $user_login = addslashes(substr(trim(strip_tags($_POST['login'])), 0, 25));
                    $user_email = addslashes(substr(strtolower(trim(strip_tags($_POST['email']))), 0, 50));
                    $user_sex   = addslashes(substr(trim(strip_tags($_POST['sex'])), 0, 6));
                    $user_phone = addslashes(substr(trim(strip_tags($_POST['phone'])), 0, 20));
                    $user_name  = addslashes(substr(trim(strip_tags($_POST['fio'])), 0, 50));
            
            
                    if (empty($_POST['login'])) {
                        
                    }
                    elseif (!preg_match("/^[\+_a-zA-Z0-9-]+$/", $_POST['login'])) {
                        $error .= "Логин содержит недопустимые символы (Только латинские буквы и цифры, пожалуйста.).<br />";
                    }
                    elseif (in_array($_POST['login'], array('catalog', 'partner', 'category', 'selected'))) {
                        $error .= 'Вы не можете использовать данную фразу в качестве логина (зарезервированное слово).<br />';
                    }
                    else
                    {
                        if (strlen($_POST['login']) > 15)
                            $error .= 'Длина логина не должна превышать 15 символов';
                        else
                        {  
                            $sth = App::db()->query("SELECT `user_login` FROM `users` WHERE `user_login` = '" . $user_login . "' LIMIT 1");
                
                            if ($sth->rowCount() > 0) {
                                $error .= "Пользователь с таким именем уже существует.<br />";
                            }
                        }
                    }
            
            
                    if ($_POST['password1'] != $_POST['password2']) {
                         $error .= "Введенные пароли не совпадают.<br />";
                    }
                    if (empty($_POST['password1'])) {
                         $error .= "Пароль не может быть пустым.<br />";
                    }
                    if (!preg_match("/^[\+_a-zA-Z0-9-]+$/", $_POST['password1'])) {
                         $error .= "Пароль содержит недопустимые символы (Только латинские буквы и цифры, пожалуйста).<br />"; 
                    }
            
            
                    if (!empty($_POST['email'])) {
                    
                        if (strpos($_POST['email'], 'hotmail.com') !== false)
                            $error .= "Извините, но у нас запрещена регистрация с домена hotmail.com";
                        else
                            if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $user_email)) {
                                $error .= "Cкорее всего Вы ошиблись при вводе почты, пожалуйста проверьте эти данные. Если введено все верно, напишите нам для ручной активации.<br />";
                            } else {
                                $sth = App::db()->query("SELECT `user_email` FROM `users` WHERE `user_email` = '" . $user_email . "' AND `user_is_fake` <> 'true' AND `user_status` = 'active' LIMIT 1");
                                if ($sth->rowCount() > 0) {
                                    $error .= "Данный email уже занят нашим пользователем.<br />Если он принадлежит Вам, вы можете <a href='http://www.maryjane.ru/" . $this->page->module . "/recover/?to=$user_email'>выслать инструкцию по смене пароля себе на почту</a>.<br />";
                                }
                            }
                    }
            
                    if (empty($user_email) && empty($user_phone)) {
                        $error .= "Вы должны указать либо Ваш телефон либо адрес электронной почты.<br />";
                    }
            
                    if (!empty($user_phone))
                    {
                        // убираем плюсик в начала, скобки и тирешки
                        $user_phone = normalizePhone($user_phone);
            
                        $sth = App::db()->query("SELECT `user_id` FROM `users` WHERE `user_phone` = '" . $user_phone . "' LIMIT 1");
            
                        if ($sth->rowCount() > 0) {
                            $error .= "Данный номер телефона уже занят другим пользователем.<br /> Если Вы уже регистрировались, воспользуйтесь <a href='http://www.maryjane.ru/" . $this->page->module . "/recover/'>формой напоминания пароля</a><br /> ";
                        }
                    }
            
            
                    if (!empty($error))
                    {
                        $this->view->setVar('error', array("text" => $error));
            
                        $_POST[$_POST['sex'] . '_selected'] = 'selected';
            
                        $this->view->setVar('POST', $_POST);
                    }
                    else
                    {
                        if (!empty($_POST['city'])) {
                            $user_city = cityName2id($_POST['city'], 0, true);
                        }
            
                        // если на этот email зарегистрирован пользователь
                        $r = App::db()->query("SELECT `user_id`, `user_login`, `user_status` FROM `users` WHERE `user_email` = '$user_email'");
            
                        $users = $r->rowCount();
                        
                        if (!empty($user_email) && $users == 1)
                        {
                            $u = $r->fetch();
            
                            if ($u['user_status'] == 'banned' || $u['user_status'] == 'deleted')
                            {
                                $this->view->setVar('error', array("text" => 'Регистрация на указанный почтовый адрес невозможна. Он заблокирован.'));
                            }
                            else
                            {
                                // высылаем пароль сразу на почту
                                App::mail()->send(array($u['user_id']), 28, array(
                                    'user_id'      => $u['user_id'],
                                    'userLogin'    => $u['user_login'],
                                    'link'         => 'http://www.maryjane.ru/' . $this->page->module . '/comeback/confirm/?user_id=' . $u['user_id'] . '&code=' . substr(md5($u['user_password']), 0, 10)
                                ));
            
                                header('location: /' . $this->page->module . '/comeback/?key=' . md5($user_email) . (($_POST['next']) ? '&next=' . $_POST['next'] : ''));
                                exit();
                            }
                        }
                        // отправляем на страницу объединения аккаунтов
                        // если активных аккаунтов более одного
                        elseif (!empty($user_email) && $users > 1)
                        {
                            foreach ($r->fetchAll() AS $u) {
                                if ($u['user_status'] == 'active')
                                    $active++;
                            }
                            
                            if ($active >= 2) {
                                header('location: /' . $this->page->module . '/merge/?key=' . md5($user_email) . (($_POST['next']) ? '&next=' . $_POST['next'] : ''));
                                exit();
                            } else {
                                $this->view->setVar('error', array("text" => 'Регистрация на указанный почтовый адрес невозможна. Он заблокирован.'));
                            }
                        }
                        // если email не занят
                        // производим быструю регистрацию
                        else
                        {
                            // логин не указан, генерим из мыла или телефона
                            if (empty($user_login))
                            {
                                if (!empty($user_email))
                                {
                                    $dog = strpos($user_email, '@');
                                    $user_login = (($dog > 3) ? substr($user_email, 0, $dog - 4) : '') . '****' . substr($user_email, $dog);
                                }
                                else 
                                {
                                    $user_login = substr($user_phone, 0, strlen($user_phone) - 5) . '****';
                                }
                            }
                            
                            // если указан телефон, то придумываем код из  цифр и отправляем смс
                            // Втыкаем юзера.
                            $this->user->create(array(
                                'user_login'        => $user_login,
                                'user_email'        => $user_email,
                                'user_name'         => $user_name,
                                'user_sex'          => $user_sex,
                                'user_city'         => $user_city,
                                'user_birthday'     => ((!empty($_POST['day']) && !empty($_POST['month']) && !empty($_POST['year'])) ? (intval($_POST['year']) . '-' . intval($_POST['month']) . '-' . intval($_POST['day'])) : ''),
                                'user_password_md5' => md5(SALT . $_POST['password1'])
                            ));
                            
                            setcookie('mjuid', $this->user->id, time() + (7 * 24 * 3600), '/', '.maryjane.ru');
            
                            if (!empty($user_phone))
                            {
                                $code = user::getSecretCode(5, TRUE);
            
                                $sms_text = 'Код для подтверджения регистрации: ' . $code . "\r\n Для смартфона " . file_get_contents("http://clck.ru/--?url=" . urlencode("http://www.maryjane.ru/" . $this->page->module . '/activate/?userid=' . $this->user->id  . '&smskey=' . $code));
            
                                // отправляем смс
                                try
                                {
                                    $sms_id = App::sms()->send($user_phone, $sms_text);
                                }
                                catch (Exception $e) {}
            
                                // если смс не дошло
                                if (is_numeric($sms_id))
                                {
                                    $this->user->setMeta('phone_temp', json_encode(array('phone' => addslashes($user_phone), 'sms_id' => $sms_id, 'code' => $code, 'expired' => time() + (3600 * 3))));
                                }
                                else
                                {
                                    $error = 'К сожалению нам не удалось отправить СМС на указанный вами номер ' . $user_phone. '. Возможно вы ошиблись при его вводе. ';
                                    $this->view->setVar('error', array("text" => $error));
                                    $_POST[$_POST['sex'] . '_selected'] = 'selected';
                                    $this->view->setVar('POST', $_POST);
                                }
                            }
            
                            if (empty($error))
                            {
                                // Проверяем, не зарегистрировался ли он по инвайту
                                if (isset($_COOKIE['mjinvite']) && !empty($_COOKIE['mjinvite']))
                                {
                                    $r = App::db()->query("SELECT * FROM `user_invites` WHERE `code` = '". addslashes($_COOKIE['mjinvite']) ."' AND `status` = 'activate' LIMIT 1");
                                    
                                    if ($invite = $r->fetch())
                                    {
                                        if ($invite['email'] == $_POST['email'])
                                        {
                                            App::db()->query("UPDATE `user_invites` SET `status` = 'finished', `result_user_id` = '" . $this->user->id . "' WHERE `id` = '".$invite['id']."' LIMIT 1");
                                            
                                            sendMessage2user($invite['user_id'], '27278', "По вашему приглашению зарегистрировался пользователь " . $user_login);
                                        }
                                    }
                                }
            
            
                                $code = md5($this->user->id);
            
                                if (!empty($user_email))
                                {
                                    $link  = "http://www.maryjane.ru/" . $this->page->module . "/activate/?userid=$userid&key=$code";
                                    $link2 = "http://www.maryjane.ru/" . $this->page->module . "/activate/resend/?userid=$userid&key=$code";
            
                                    $vararray['activateLink'] = $link;
                                    $vararray['activateCode'] = $vararray['code'] = $code;
                                    $vararray['mail']         = $_POST['email'];
                                    $vararray['user_id']      = $this->user->id;
                                    $vararray['userLogin']    = $vararray['user_login'] = $_POST['login'];
            
                                    $to = $_POST['email'];
            
                                    App::mail()->send($this->user->id, 453, $vararray);
                                }
            
            
                                header('location: /' . $this->page->module . '/needActivation/' . $this->user->id . '/' . $code . '/');
                                exit();
                            }
                            else
                            {
                                $sth = App::db()->query("DELETE FROM `users` WHERE `user_id` = '" . $this->user->id . "' LIMIT 1");
                            }
                        }
                    }
                }
            }
            /**
             * БЫСТРАЯ РЕГИСТРАЦИЯ
             */ 
            elseif ($this->page->reqUrl[1] == 'quick')  // Быстрая регистрация. Необходим только email|телефон
            {
                if (!$_POST['email'])
                {
                    if (empty($_GET['next']))
                        $next = $_SERVER['HTTP_REFERER'];
                    else
                        $next = trim($_GET['next']);
            
                    $this->view->setVar('next', $next);
                    $this->view->setVar('content_tpl', 'registration/registration.tpl');
            
                    $this->view->generate('index.popup.tpl');
                    exit();
                }
                else
                {
                    $out = array(
                        'status' => 'success', 
                        'message' => 'Быстрая регистрация успешно произведена.',
                    );
            
                    if (!empty($_POST['email']))
                    {
                        $to = str_replace(array(' ', '(', ')', '-', '+'), '', addslashes(trim($_POST['email'])));
            
                        if (strpos($_POST['email'], 'hotmail.com') !== false)
                        {
                            $error = "Извините, но у нас запрещена регистрация с домена hotmail.com";
                            $this->view->setVar('ERROR', array('text' => $error));
                        } 
                        else 
                        {
                            if (!validateEmail($to)) {
                                if (strpos($to, '8') === 0) {
                                    $to = '7' . substr($to, 1);
                                }
                            }
                
                            $sth = App::db()->query("SELECT `user_id`, `user_email` FROM `users` WHERE `" . (validateEmail($to) ? 'user_email' : 'user_phone') . "` = '{$to}' AND `user_status` != 'deleted' AND `user_status` != 'banned'");
                
                            // если на этот email зарегистрирован пользователь
                            $users = $sth->rowCount();
            
                            // 1 пользователь найден
                            if ($users == 1)
                            {
                                $u = $sth->fetch();
                                
                                $U = new user($u['user_id']);
                                    
                                // быстрый вход по почте
                                if (validateEmail($to)) 
                                {
                                    // высылаем пароль сразу на почту
                                    App::mail()->send($U->id, 28, array(
                                        'user_id'      => $U->id,
                                        'userLogin'    => $U->user_login,
                                        'link'         => 'http://www.maryjane.ru/' . $this->page->module . '/comeback/confirm/?user_id=' . $U->id . '&code=' . substr(md5($U->user_password_md5), 0, 10)
                                    ));
                                    
                                    $this->page->go('/' . $this->page->module . '/comeback/?key=' . md5($to) . (($_POST['next']) ? '&next=' . urlencode($_POST['next']) : ''));
                                }
                                // быстрый вход по телефону
                                else 
                                {
                                    // отправляем код
                                    $code = user::getSecretCode(5, TRUE);
                                    
                                    $sms_id = App::sms()->send($to, $code);
                                    
                                    $U->setMeta('phone_temp', json_encode(array('phone' => addslashes($to), 'sms_id' => $sms_id, 'code' => $code, 'expired' => time() + (3600 * 3))));
                                    
                                    $this->page->go('/' . $this->page->module . '/phonecomeback/?key=' . md5($U->id) . (($_POST['next']) ? '&next=' . urlencode($_POST['next']) : ''));
                                }
                                
                                exit();
                            }
                            // отправляем на страницу объединения аккаунтов
                            elseif ($users > 1)
                            {
                                $this->page->go('/' . $this->page->module . '/merge/?key=' . md5($to) . (($_POST['next']) ? '&next=' . urlencode($_POST['next']) : '') . (!validateEmail($to) ? '&phone=1' : ''));
                                exit();
                            }
                            // если email / телефон не занят
                            // производим быструю регистрацию
                            else
                            {
                                if (validateEmail($to))
                                {
                                    $this->user->create(array(
                                            'user_email'   => $to, 
                                            'user_is_fake' => 'true', 
                                            'user_login'   => ((strpos($to, '@') > 3) ? substr($to, 0, round(strpos($to, '@') / 2)) : '') . '****' . substr($to, strpos($to, '@'))));
                                    
                                    App::mail()->send($this->user->id, 453, array(
                                        'code'          => md5($this->user->id),
                                        'user_id'       => $this->user->id,
                                        'user_login'    => $this->user->info['user_email'],
                                        'user_password' => $this->user->password
                                    ));
                                }
                                else
                                {
                                    $code = user::getSecretCode(5, TRUE);
                                    
                                    $this->user->create(array(
                                            'user_login' => substr($to, 0, 2) . '****' . substr($to, -4), 
                                            'user_is_fake' => 'true'));
                                            
                                    $this->user->setPassword($code);
                                    
                                    try
                                    {
                                        $sms_id = App::sms()->send($to, $code . "\nЭто ещё и Ваш пароль для входа. Не забудьте его изменить." );
                                    }
                                    catch (Exception $e) 
                                    {
                                        printr($e);
                                    }
                                    
                                    $this->user->setMeta('phone_temp', json_encode(array('phone' => addslashes($to), 'sms_id' => $sms_id, 'code' => $code, 'expired' => time() + (3600 * 3))));
                                }
                                
                                $this->user->authorize();
                                    
                                $sth = App::db()->query("INSERT IGNORE INTO `mail_list_subscribers` SET `user_id` = '$id', `mail_list_id` = '6'");
                
                                //if (empty($_POST['next']) && empty($_POST['HTTP_REFERER']))
                                //    header("Location: /");
                                //else
                                //    header("Location: " . (!empty($_POST['HTTP_REFERER']) ? $_POST['HTTP_REFERER'] : $_POST['next']));
                
                                header("Location: /editprofile/");
                                exit();
                            }
                        }
                    }
                    else
                    {
                        header("Location: /login/"); exit();
                    }
                }
            }
            // ПОДТВЕРЖДЕНИЕ ПРИЁМА РЕГИСТРАЦИОННЫХ ДАННЫХ. ОЖИДАНИЕ АКТИВАЦИИ
            elseif ($this->page->reqUrl[1] == 'needActivation')
            {
                header("Status: 404 Not Found");
                
                $this->page->noindex = TRUE;
                
                if (!empty($this->page->reqUrl[2]) && !empty($this->page->reqUrl[3]))
                {
                    $uid  = intval($this->page->reqUrl[2]);
                    $code = trim($this->page->reqUrl[3]);
            
                    if (md5($uid) === $code)
                    {
                        $this->page->breadcrump[] = array(
                            'link'    => '/' . $this->page->module . '/needActivation/' . $uid . '/' . $code . '/',
                            'caption' => 'Активация аккаунта');
                            
                        $User = new user($uid);
                            
                        $sth = App::db()->query("SELECT `user_id`, `user_email` FROM `users` WHERE `user_id` = '$uid' AND `user_status` = 'active' LIMIT 1");
                        $user = $sth->fetch();
            
                        $user['user_phone'] = $this->user->meta->phone_temp;
            
                        if (!empty($user['user_phone'])) {
                            $this->view->setVar('byPhone', TRUE);
                            $user_phone = json_decode($user['user_phone']);
                            $user['user_phone'] = $user_phone->phone;
                        }
                        else
                            $this->view->setVar('byEmail', TRUE);
            
                        if ($_GET['error'])
                            $this->view->setVar('error', $_GET['error']);
            
                        $this->view->setVar('U', $user);
                    }
                    else
                    {
                        $this->view->setVar('error', 'Указан неверный код подтверждения регистрации');
                        //header('location: /');
                        //exit();
                    }
                }
                else
                {
                    $this->view->setVar('error', 'Не достаточно данных для подтверждения регистрации');
                    //header('location: /');
                    //exit();
                }
            }
            // ПОДТВЕРЖДЕНИЕ ПРИЁМА РЕГИСТРАЦИОННЫХ ДАННЫХ. ОЖИДАНИЕ АКТИВАЦИИ
            elseif ($this->page->reqUrl[1] == 'needActivationModal')
            {
                $this->page->tpl = $this->page->module . '/needActivationModal.tpl';
                $this->view->generate($this->page->tpl);
                exit();
            }
            // Экшен - активация - т.е переход по ссылке из письма.
            elseif ($this->page->reqUrl[1] == 'activate')
            {
                $userid = intval($_GET['userid']);
                
                $result = App::db()->query("SELECT * FROM `users` WHERE `user_id` = '$userid' LIMIT 1");
            
                if ($user = $result->fetch())
                {
                    $U = new user($userid);
                    
                    $user['user_phone'] = $U->meta->phone_temp;
                    
                    // активаци по телефону
                    if (!empty($user['user_phone']))
                    {
                        $user['user_phone'] = json_decode($user['user_phone']);
                    }
            
                    if (md5($user['user_id']) == trim($_GET['key']) || $user['user_phone']->code == trim($_POST['smskey']) || $user['user_phone']->code == trim($_GET['smskey']))
                    {
                        if ($user['user_activation'] <> 'done')
                        {
                            $userid        = $user['user_id'];
                            $to            = $user['user_email'];
                            $user_was_fake = $user['user_email'];
            
                            // ищем пользователей с таким мылом в базе
                            // с цельую если это дубль - предложить объединить его аккаунты
                            if (!empty($user['user_email']))
                            {
                                $sth = App::db()->query("SELECT `user_id` FROM `users` WHERE `user_email` = '" . $user['user_email'] . "' AND `user_status` != 'deleted' AND `user_status` != 'banned'");
            
                                if ($sth->rowCount() > 1)
                                {
                                    header('location: /' . $this->page->module . '/merge/?key=' . md5(strtolower($user['user_email'])));
                                    exit();
                                }
            
                                App::mail()->send($userid, 457);
                            }
            
                            $U->activate();
                            
                            // активаци по телефону подтверждена
                            // заменяем служебную инфу постоянным телефоном
                            if (!empty($user['user_phone']))
                            {
                                $sth = App::db()->query("UPDATE `users` SET `user_phone` = '" . $user['user_phone']->phone . "' WHERE `user_id` = '$userid' LIMIT 1");
                            }
            
                            //Информеры
                            $informercookie = unserialize(stripslashes($_COOKIE['informer']));
            
                            if (sizeof($informercookie)>1)
                            {
                                $infocomment = "Регистрация произведена после клика по баннеру #" . $informercookie['informerid'] . " юзера " . $informercookie['informeruser'] . "; реферрер: " . $informercookie['referrer'] . "; IP: " . $informercookie['refip'];
                                
                                $query = "INSERT
                                          INTO `informer_action_log` (`informer_action_type`, `informer_id`, `informer_action_target`, `informer_action_comment`)
                                              VALUES ('registration', '" . $informercookie['informerid'] . "', '" . $userid . "', '" . $infocomment . "')";
                                              
                                $result = App::db()->query($query);
                            }
            
                            // подписка
                            $sth = App::db()->query("INSERT IGNORE INTO `mail_list_subscribers` (`user_id`, `mail_list_id`) VALUES ('" . $userid . "','6')");
            
                            $this->user->setSessionValue(['user_id' => $user['user_id'], 'session_logged' => 1]);
            
                            $_SESSION['registration_complite'] = TRUE;
            
                            header('location: /editprofile/#registration-success');
                            exit();
                        }
                        // активация уже выполнена
                        else
                        {
                            // авторизуем
                            $this->user->setSessionValue(['user_id' => $user['user_id'], 'session_logged' => 1]);
                            
                            header("Location:/editprofile/");
                            exit();
                        }
                    }
                    else
                    {
                        header("Location: /" . $this->page->module . "/needActivation/$userid/" . md5($user['user_id']) . '/?error=1');
                        exit();
                    }
                }
                else
                {
                    header("Location:/login/");
                    exit();
                }
            }
            elseif($this->page->reqUrl[1] == 'complit')
            {
                header('location: /profile/');
                exit();
            }
            // ПОВТОРНАЯ ОТПРАВКА ПИСЬМА С АКТИВАЦИЕЙ
            elseif ($this->page->reqUrl[1] == 'resend')
            {
                header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );  // disable IE caching
                header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
                header( "Cache-Control: no-cache, must-revalidate" );
                header( "Pragma: no-cache" );
                header( "Content-Type: text/html; charset=utf-8");
            
                if (empty($_GET['email']) && empty($_POST['email'])) 
                {
                    if ($this->user->authorized)
                    {
                        if ($this->user->user_activation == 'done') {
                            $error = "error:Активация данного аккаунта уже выполнена";
                        } else {
                            if ($this->user->user_activation != 'failed') {
                                $_GET['email'] = $this->user->user_email;
                            } else {
                                $error = "error:Активация данного аккаунта не возможна";
                            }
                        }
                    } else {
                        $error = "error:Не указан почтовый адрес.";
                    }
                }
                
                if (empty($error))
                {
                    $sth = App::db()->prepare("SELECT `user_activation`, `user_id`, `user_email`, `user_login`
                              FROM `users`
                              WHERE `user_email` = :email
                              LIMIT 1");
                
                    $sth->execute(array(
                        'email' => $_GET['email'],
                    ));
                    
                    $row = $sth->fetch();
                    
                    if (!$row)
                    {
                        $error = "error:E-mail в базе данных не найден.";
                    }
                    else
                    {
                        $code   = md5($row['user_id']);
                        $userid = intval($row['user_id']);
                        $email  = $row['user_email'];
                
                        if ($row['user_activation'] == "done") 
                        {
                            $error = "error:Данный e-mail активирован, входите!";
                        }
                        else 
                        {
                            if ($row['user_activation'] == "waiting")
                            {
                                $try = 1;
                                App::db()->query("UPDATE `users` SET `user_activation` = 'fail1' WHERE user_id='$userid' LIMIT 1");
                    
                            } elseif ($row['user_activation'] == "fail1") {
                                
                                $try = 2;
                                App::db()->query("UPDATE users SET `user_activation` = 'fail2' WHERE user_id='$userid' LIMIT 1");
                    
                            } elseif ($row['user_activation'] == "fail2") {
                                
                                $try = 3;
                                App::db()->query("UPDATE users SET `user_activation` = 'failed' WHERE user_id='$userid' LIMIT 1");
                                
                            } elseif ($row['user_activation'] == 'failed') {
                                
                                $error = "error:Ошибка. Слишком много повторных автиваций, напишите нам.";
                            } else {
                                
                                $error = "error:Неизвестная ошибка, напишите нам.";
                            }
                            
                            if ($try == 1 || $try == 2 || $try == 3)
                            {
                                App::mail()->send($userid, 453, array(
                                    'activateLink' => "http://www.maryjane.ru/" . $this->page->module . "/activate/?userid=$userid&key=$code",
                                    'activateCode' => $code,
                                    'code'         => $code,
                                    'mail'         => $row['user_email'],
                                    'user_id'      => $userid,
                                    'userLogin'    => $row['user_login'],
                                    'user_login'   => $row['user_login'],
                                ));
                    
                                $error = "ok:Письмо с активационными данными выслано повторно. Попытка $try из 3";
                            }
                        }
                    }
                }
            
                if ($this->page->isAjax) {
                    exit($error);
                } else {
                    
                    $this->page->setFlashMessage(str_replace('error:', '', $error));
                    
                    header('location:' . $_SERVER['HTTP_REFERER']);
                }
            
                exit();
            }
            // ВОССТАНОВЛЕНИЕ ПАРОЛЯ 
            elseif ($this->page->reqUrl[1] == 'recover')
            {
                //printr($_SESSION);
                
                $this->page->breadcrump[] = array(
                    'link'    => '/' . $this->page->module . '/recover/',
                    'caption' => 'Восстановление пароля');
                
                $this->view->setVar('recover_try', $_SESSION['recover']['try']);
                
                /**
                 * запрос ссылки на смену пароля
                 */
                if ($_POST['to'])
                {
                    $to = trim(addslashes($_POST['to']));
                    
                    $this->view->setVar('to', $_POST['to']);
                    
                    // если попытка восстановления не первая проверяем капчу
                    if ($_SESSION['recover']['try'] > 1)
                    {
                        if (isset($_POST['keystring']) && empty($_POST['keystring']))
                        {
                            $error = 'Не указан проверочный код';
                        }
                        elseif (isset($_POST['keystring']) && $_POST['keystring'] != $_SESSION['captcha_keystring'])
                        {
                            $error = 'Проверочный код введён неверно';
                        }
                    }
                    
                    if (empty($error))
                    {
                        // проверка на логин
                        $sth = App::db()->prepare("SELECT `user_email` FROM `users` WHERE `user_login` = :login AND `user_status` NOT IN ('deleted', 'banned') LIMIT 1");
                        
                        $sth->execute(array('login' => $to));
                        
                        if ($sth->rowCount() == 1)
                        {
                            $row = $sth->fetch();
                            $to = stripslashes($row['user_email']);
                        }
                        
                        // если то что ввели похоже на мыло
                        if (validateEmail($to))
                        {
                            $email = $to;
                            
                            $sth = App::db()->prepare("SELECT `user_id`, `user_email`, `user_phone`, `user_login`, `user_activation`, `user_is_fake`
                                        FROM `users` 
                                        WHERE `user_email` = :email AND `user_status` NOT IN ('deleted', 'banned')");
            
                            // убрал это условие 27.06.2016 
                            // AND `user_is_fake` = 'false'
                
                            $sth->execute(array(
                                'email' => $email,
                            ));
                
                            // один пользователь на мыло / телефон
                            if ($users = $sth->fetchAll())
                            {
                                foreach ($users AS $u)
                                {
                                    // высылаем пароль только не фейку
                                    App::mail()->send(array($u['user_id']), 109, array(
                                        'mail'      => $u['user_email'],
                                        'userLogin' => $u['user_login'],
                                        'code'      => md5(NOWDATE . $u['user_id'])
                                    ));
                                }
                
                                header('location: /' . $this->page->module . '/recover/?to=' . $to . '&sended=1');
                                exit();
                            }
                            else
                            {
                                $error = 'Такой email в базе данных не найден.';
                                $this->view->setVar('not_found_email', TRUE);
                            }
                        }
                        // восстановление по телефону
                        else
                        {
                            $phone = $to;
                            
                            if (strlen($phone) >= 9)
                            {
                                if (strpos($phone, '8') === 0)
                                    $phone = 7 . substr($phone, 1);
                                
                                $sth = App::db()->query("SELECT `user_id`, `user_email`, `user_phone`, `user_login`, `user_activation`, `user_is_fake`
                                            FROM `users` 
                                            WHERE `user_phone` = '" . str_replace(array(' ', '(', ')', '-', '+'), '', $phone) . "'");
                                            
                                if ($sth->rowCount() == 1)
                                {
                                    $u = $sth->fetch();
                                     
                                    $tmp = str_split(md5($u['user_id'] . time()));
                        
                                    foreach ($tmp AS $k => $l)
                                    {
                                        if (!is_numeric($l))
                                            unset($tmp[$k]);
                                    }
                        
                                    $code = implode('', array_slice($tmp, 0, 5));
                                    
                                    //printr($code);
                                    
                                    $r = App::sms()->send($u['user_phone'], 'Код для восстановления пароля - ' . $code);
                                
                                    if (is_numeric($r) && $r > 0) {
                                        $ok = $okPhone = 'Пароль успешно выслан на указанный Вами телефон ' . $phone;
                                        setcookie('MJrecover', base64_encode(json_encode(array('user_id' => $u['user_id'], 'code' => sha1(SALT . $code)))));
                                        
                                        header('location: /' . $this->page->module . '/recover/?to=' . $to . '&sended=1');
                                        exit();
                                        
                                    } else {
                                        $error = 'На указанный вами номер не удалось выслать смс'; 
                                    }
                                    
                                } else {
                                     $error = 'Такой телефона в базе данных не найден. Зарегистрироваться?';
                                     $this->view->setVar('not_found', TRUE);
                                }
                            } else {
                                $error = 'То что Вы ввели содержит слишком мало цифр для телефоны.';
                            }
                        }
                    }
                }
                
                
                
                /**
                 * форма запрос ссылки на смену пароля
                 */
                if ($_POST['to'] || $_GET['to'] || $_GET['email'] || $_GET['login'])
                {
                    header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );  // disable IE caching
                    header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
                    header( "Cache-Control: no-cache, must-revalidate" );
                    header( "Pragma: no-cache" );
                    header( "Content-Type: text/html; charset=utf-8");
            
                    if (!$_GET['login'])
                    {
                        $to = trim(addslashes($_POST['to']));
                        
                        if (empty($to))
                            $to = trim(addslashes($_GET['to']));
            
                        if (empty($to))
                            $to = trim(addslashes($_GET['email']));
                    }
                    else 
                    {
                        $sth = App::db()->prepare("SELECT `user_id`, `user_email`, `user_login` FROM `users` WHERE (`user_login` = :login OR `user_email` = :login) AND `user_is_fake` = 'false' LIMIT 1");
                        
                        $sth->execute(array(
                            'login' => $_GET['login'],
                        ));
                        
                        $u = $sth->fetch();
                        
                        $to = $u['user_email'];
                    }
            
                    $this->view->setVar('EMAIL', $to);
                    $this->view->setVar('TO', $to);
            
                    if ($_GET['email'] || $_POST['to'] || $_GET['to'] || $_GET['login'])
                    {
                        if (!empty($error))
                        {
                            $this->view->setVar('error', array('text' => $error));
                        }
                        else
                        {
                            $_SESSION['recover']['try'] = $_SESSION['recover']['try'] + 1;
                            
                            if (validateEmail($to))
                            {
                                // если это первая попытка восстановления или с момента последней попытки прошло более 5 минут
                                if (empty($_SESSION['recover']['last']) || getDateDiff($_SESSION['recover']['last'], '', true, 'm') >= 5)
                                {
                                    // отправляем письмо со ссылкой на сброс пароля
                                    App::mail()->send($u['user_id'], 109, array(
                                        'mail'      => $u['user_email'],
                                        'userLogin' => $u['user_login'],
                                        'code'      => md5(NOWDATE . $u['user_id'])
                                    ));
                                    
                                    // запоминаем время последней попытки
                                    $_SESSION['recover']['last'] = NOW;
                                }
                                else 
                                {
                                    $this->view->setVar('error', array('text' => 'Повторите попытку восстановления позже или свяжитесь с администрацией'));
                                }
                                
                                $this->view->setVar('byEmail', TRUE);
                            }
                            else 
                            {
                                $this->view->setVar('byPhone', TRUE);
                            }   
                        }
                    }
                    else
                    {
                        if (!empty($error))
                            exit("error:$error");
            
                        if (!empty($ok))
                            exit("ok:$ok");
                    }
                }
            
                /**
                 * проверка кода и форма смены пароля
                 */
                if ($_GET['code'] || $_POST['code'])
                {
                    if ($_GET['code'])
                        $code = trim($_GET['code']);
                    
                    if ($_POST['code'])
                        $code = trim($_POST['code']);
                    
                    $this->view->setVar('code', $code);
            
                    // восстановление по мылу
                    if ($code)
                    {
                        $sth = App::db()->query("SELECT `user_id`, `user_login` FROM `users` WHERE md5(CONCAT('" . NOWDATE . "', `user_id`)) = '" . addslashes(trim($_GET['code'])) . "'");
                
                        if ($sth->rowCount() == 1)
                        {
                           $user = $sth->fetch();
                           $this->view->setVar('user', $user);
                        }
                    }
                    
                    // восстановление по телефону
                    if (!$user)
                    {
                        $user = objectToArray(json_decode(base64_decode($_COOKIE['MJrecover'])));
            
                        if ($user['code'] != sha1(SALT . $code))
                        {
                            unset($user);
                        }
                    }
                    
                    
                    if (!$user) 
                    {
                         $this->view->setVar('error', array('text' => 'Код указан неверно.'));
                         
                         if ($_POST['code'])
                            $this->view->setVar('byPhone', TRUE);
                         else
                            $this->view->setVar('byEmail', TRUE);
                    }
                    else
                    {
                        $this->view->setVar('action', 'chPassword');
                        
                        $this->page->breadcrump[] = array(
                            'link'    => '/' . $this->page->module . '/recover/',
                            'caption' => 'Смена пароля');
                        
                        // сохранение паролей
                        if (isset($_POST['p1']))
                        {
                            if (!empty($_POST['p1']) && !empty($_POST['p2']))
                            {
                                if ($_POST['p1'] != $_POST['p2'])
                                {
                                    $this->view->setVar('error', array('text' => 'Пароли не совпадают.'));
                                }
                                else
                                {
                                    $sth = App::db()->query("UPDATE `users` SET `user_password_md5` = '" . addslashes(md5(SALT . trim($_POST['p1']))) . "' WHERE `user_id` = '" . $user['user_id'] . "' LIMIT 1");
                
                                    // авторизуем пользователя
                                    $this->user->setSessionValue(['user_id' => $user['user_id'], 'session_logged' => 1]);
                
                                    //setcookie('MJrecover', '', (time() - 2592000));
                
                                    //header('location: /' . $this->page->module . '/recover/?code=' . $code . '&success=true');
                                    header('location: /profile/');
                                    exit();
                                }
                            } else {
                                $this->view->setVar('error', array('text' => 'Пароль не может быть пустым.'));
                            }
                        }
                
                        if ($_GET['success'])
                            $this->view->setVar('success', 'Пароль успешно изменён.');
                    }
                }
            }
            // Экшен - "Камбэк ма френд" - на мыле один аккаунт
            elseif ($this->page->reqUrl[1] == 'comeback')
            {
                $this->page->breadcrump[] = array(
                    'link'    => '/' . $this->page->module . '/comeback/',
                    'caption' => 'Восстановление пароля');
            
            
                if ($this->page->reqUrl[2] == 'resend')
                {
                    $uid = intval($_POST['user_id']);
            
                    $u = App::db()->query("SELECT `user_id`, `user_email`, `user_login`, `user_password_md5` FROM `users` WHERE `user_id` = '$uid' LIMIT 1")->fetch();
            
                    // высылаем пароль сразу на почту
                    App::mail()->send(array($u['user_id']), 28, array(
                        'user_id'      => $u['user_id'],
                        'userLogin'    => $u['user_login'],
                        'link'         => 'http://www.maryjane.ru/' . $this->page->module . '/comeback/confirm/?user_id=' . $u['user_id'] . '&code=' . substr(md5($u['user_password_md5']), 0, 10) . '&next=' . $_GET['next']
                    ));
            
                    exit('ok');
                }
            
                // проверка пароля перед "возвращением" (AJAX)
                if ($this->page->reqUrl[2] == 'check_confirm')
                {
                    $uid  = intval($_GET['user_id']);
                    $user = App::db()->query("SELECT `user_password_md5`, `user_email`, `user_login` FROM `users` WHERE `user_id` = '$uid' LIMIT 1")->fetch();
            
                    // переход по ссылке из письма
                    if ($_GET['password']) {
                        $confirm = (md5(SALT . $_GET['password']) == $user['user_password_md5']);
                    } else {
                        $confirm = false;
                    }
            
                    exit (($confirm) ? 'true' : 'false');
                }
            
                // Подтвердить выбор предложенного аккаунта
                // Подтвердить выбор одного из предложенных аккаунтов для продолжения покупки
                if ($this->page->reqUrl[2] == 'confirm')
                {
                    $uid  = intval($_GET['user_id']);
                    $user = App::db()->query("SELECT `user_password_md5`, `user_email`, `user_login` FROM `users` WHERE `user_id` = '$uid' LIMIT 1")->fetch();
            
                    // переход по ссылке из письма
                    if ($_GET['code']) {
                        $confirm = ($_GET['code'] == substr(md5($user['user_password_md5']), 0, 10));
                        // ручной ввод пароля
                    } elseif ($_GET['password']) {
                        $confirm = (md5(SALT . $_GET['password']) == $user['user_password_md5']);
                    } else {
                        $confirm = false;
                    }
            
                    if (!empty($uid) && $confirm)
                    {
                        $U = new user($uid);
                        $U->activate();
                    
                        // логиним пользователя
                        $SESSIONID = md5(time() . mt_rand());
                        App::db()->query("INSERT INTO `sessions` (`session_id`, `user_id`, `session_time`, `session_logged`) VALUES ('" . $SESSIONID . "', '" . $uid . "', '" . time() . "', '1')");
                        setcookie('session', $SESSIONID, (time() + 2592000), "/", AppDomain);
            
                        header('location:' . (($_GET['next']) ? $_GET['next'] : '/'));
                        exit('yes');
                    } else {
                        $this->view->setVar('ERROR', array('text' => 'Указан неверный код подтверждения'));
                        $this->view->generate('index.tpl');
                        exit();
                    }
                }
            
            
                // подробная информация о пользователе
                if ($_GET['key'])
                {
                    $key  = addslashes(strtolower(trim($_GET['key'])));
                    $next = addslashes(trim($_GET['next']));
            
                    $r = App::db()->query("SELECT u.`user_id`, u.`user_name`, u.`user_login`, u.`user_email`, u.`user_phone`, u.`user_last_login`, u.`user_activation`, u.`user_bonus`, u.`user_register_date`, MAX(ub.`user_basket_date`) AS user_last_order, COUNT(ub.`user_basket_id`) AS orders_count, COUNT(g.`good_id`) AS goods_count
                                      FROM `users` AS u 
                                      LEFT JOIN `user_baskets` AS ub ON u.`user_id` = ub.`user_id`
                                      LEFT JOIN `goods` AS g ON u.`user_id` = g.`user_id` AND g.`good_visible` = 'true' AND g.`good_status` != 'deny' AND g.`good_status` != 'customize'
                                      WHERE MD5(LOWER(u.`user_email`)) = '$key' AND u.`user_status` != 'deleted' AND u.`user_status` != 'banned'
                                      GROUP BY u.`user_id`
                                      ORDER BY u.`user_activation`, user_last_order DESC");
                                      
                    if ($r->rowCount() == 1)
                    {
                        $u = $r->fetch();
            
                        $u['orders_count'] = intval($u['orders_count']);
                        $u['goods_count']  = intval($u['goods_count']);
                        $u['user_avatar']  = userId2userGoodAvatar($u['user_id'], 100);
                        $u['user_login']   = stripslashes($u['user_login']);
                        $u['user_name']    = stripslashes($u['user_name']);
                        $u['user_phone']     = '+' . substr($u['user_phone'], 0, -6) . '****' . substr($u['user_phone'], -2);
                        $u['user_register_date'] = datefromdb2textdate($u['user_register_date'], 1);
            
                        if ($u['user_last_login'] != '0000-00-00 00:00:00')
                            $u['user_last_login'] = datefromdb2textdate($u['user_last_login']);
                        else
                            $u['user_last_login'] = 'неизвестно';
            
                        if (!empty($u['user_last_order'])) {
                            $u['user_last_order'] = datefromdb2textdate($u['user_last_order']);
                        }
            
                        $this->view->setVar('u', $u);
            
                        $email = $u['user_email'];
            
                        $this->view->setVar('email', $email);
                        $this->view->setVar('next', $next);
            
            
                        if ($_GET['social'])
                        {
                            $this->view->setVar('social', $_GET['social']);
                            $this->view->setVar('social_user_id', $_GET['social_user_id']);
                        }
                    }
                    else
                    {
                        header('location: ' . $_SERVER['HTTP_REFERER']);
                        exit();
                    }
                }
                else
                {
                    header('location: ' . $_SERVER['HTTP_REFERER']);
                    exit();
                }
            }
            elseif ($this->page->reqUrl[1] == 'phonecomeback')
            {
                // неча здесь делать авторизованным
                //if ($this->user->authorized)
                //{
                 //   header('location: /');
                 //   exit();
                //}
                
                $this->page->breadcrump[] = array(
                    'link'    => '/' . $this->page->module . '/comeback/',
                    'caption' => 'Восстановление пароля');
            
            
                // меняем код подтверждения, отправляем юзеру
                if ($this->page->reqUrl[2] == 'resend')
                {
                    $U = new user(intval($_POST['user_id']));
                    
                    $info = json_decode($U->meta->phone_temp, 1);
                
                    // отправка сообщения не чаще чем 1 раз в 5 минут
                    if ($info['sended'] && $info['sended'] > time() - 300)
                        exit('wait');
                        
                    $info['code']    = user::getSecretCode(5, TRUE);
                    $info['expired'] = time() + (3600 * 3);
                    $info['sended']  = time();
                    
                    $U->setMeta('phone_temp', json_encode($info));
            
                    $sms_id = App::sms()->send($info['phone'], $info['code']);
            
                    exit('ok');
                }
            
                // проверка пароля перед "возвращением" (AJAX)
                if ($this->page->reqUrl[2] == 'check_confirm')
                {
                    $U = new user(intval($_GET['user_id']));
                    
                    $info = json_decode($U->meta->phone_temp, 1);
            
                    exit (($info['code'] == $_GET['code']) ? 'true' : 'false');
                }
            
                // Подтвердить выбор предложенного аккаунта
                // Подтвердить выбор одного из предложенных аккаунтов для продолжения покупки
                if ($this->page->reqUrl[2] == 'confirm')
                {
                    $U = new user(intval($_POST['user_id']));
            
                    $info = json_decode($U->meta->phone_temp, 1);
            
                    if (time() <= $info['expired'])
                    {
                        $confirm = ($info['code'] == $_POST['code']) ? true : false;
                
                        if (!empty($U->id) && $confirm)
                        {
                            $this->user->id = $U->id;
                            
                            $this->user->authorize();
                            $this->user->activate();
                            $this->user->delMeta('phone_temp');
                            
                            if (!empty($_POST['password']))
                            {
                                $this->user->setPassword($_POST['password']);
                            }
                            
                            header('location:' . (($_GET['next']) ? $_GET['next'] : '/'));
                            exit('yes');
                        } else {
                            $this->view->setVar('ERROR', array('text' => 'Указан неверный код подтверждения'));
                        }
                    } else {
                        $this->view->setVar('ERROR', array('text' => 'Срок действия проверочного кода истёк'));
                    }
                }
            
            
                // подробная информация о пользователе
                if ($_GET['key'])
                {
                    $key  = addslashes(strtolower(trim($_GET['key'])));
                    $next = addslashes(trim($_GET['next']));
            
                    $r = App::db()->query("SELECT u.`user_id`, u.`user_name`, u.`user_login`, u.`user_email`, u.`user_last_login`, u.`user_activation`, u.`user_bonus`, u.`user_register_date`, MAX(ub.`user_basket_date`) AS user_last_order, COUNT(ub.`user_basket_id`) AS orders_count, COUNT(g.`good_id`) AS goods_count
                                      FROM `users` AS u 
                                      LEFT JOIN `user_baskets` AS ub ON u.`user_id` = ub.`user_id`
                                      LEFT JOIN `goods` AS g ON u.`user_id` = g.`user_id` AND g.`good_visible` = 'true' AND g.`good_status` != 'deny' AND g.`good_status` != 'customize'
                                      WHERE MD5(LOWER(u.`user_id`)) = '$key' AND u.`user_status` != 'deleted' AND u.`user_status` != 'banned'
                                      GROUP BY u.`user_id`
                                      ORDER BY u.`user_activation`, user_last_order DESC");
            
                    if ($r->rowCount() == 1)
                    {
                        $u = $r->fetch();
            
                        $User = new user($u['user_id']); 
            
                        $phone_temp = json_decode($this->user->meta->phone_temp, 1);
                        
                        $u['orders_count'] = intval($u['orders_count']);
                        $u['goods_count']  = intval($u['goods_count']);
                        $u['user_avatar']  = userId2userGoodAvatar($u['user_id'], 100);
                        $u['user_login']   = stripslashes($u['user_login']);
                        $u['user_name']    = stripslashes($u['user_name']);
                        $u['user_register_date'] = datefromdb2textdate($u['user_register_date'], 1);
                        //$u['user_phone']   = '+' . substr($phone_temp['phone'], 0, -6) . '****' . substr($u['phone'], -2);
                        $u['user_phone']   = $phone_temp['phone'];
                            
                        if ($u['user_last_login'] != '0000-00-00 00:00:00')
                            $u['user_last_login'] = datefromdb2textdate($u['user_last_login']);
                        else
                            $u['user_last_login'] = 'неизвестно';
            
                        if (!empty($u['user_last_order'])) {
                            $u['user_last_order'] = datefromdb2textdate($u['user_last_order']);
                        }
                        
                        $this->view->setVar('u', $u);
            
                        $email = $u['user_email'];
            
                        $this->view->setVar('phone', $u['user_phone']);
                        $this->view->setVar('next', $next);
                    }
                    else
                    {
                        header('location: ' . $_SERVER['HTTP_REFERER']);
                        exit();
                    }
                }
                else
                {
                    header('location: ' . $_SERVER['HTTP_REFERER']);
                    exit();
                }
            }
            // Экшен - объединение нескольких аккаунтов с одним мылом
            elseif ($this->page->reqUrl[1] == 'merge')
            {
                $this->page->breadcrump[] = array(
                    'link'    => '/' . $this->page->module . '/merge/',
                    'caption' => 'Объединение аккаунтов');
                    
                // проверка пароля перед объединением аккаунтов (AJAX)
                if ($this->page->reqUrl[2] == 'check_confirm')
                {
                    $user = App::db()->query(sprintf("SELECT `user_password_md5` FROM `users` WHERE `user_id` = '%d' LIMIT 1", $_GET['user_id']))->fetch();
                    
                    exit ((trim($_GET['password']) == substr(md5($user['user_password_md5']), 0, 10)) ? 'true' : 'false');
                }
            
            
                // список доступных пользователей для выбора
                if ($_GET['key'])
                {
                    $key  = addslashes(strtolower(trim($_GET['key'])));
                    $next = addslashes(trim($_GET['next']));
            
                    $this->view->setVar('key', $key);
            
                    $sth = App::db()->query("SELECT u.`user_id`, u.`user_name`, u.`user_password_md5`, u.`user_login`, u.`user_email`, u.`user_phone`, u.`user_last_login`, u.`user_activation`, u.`user_bonus`, u.`user_register_date`, MAX(ub.`user_basket_date`) AS user_last_order, COUNT(ub.`user_basket_id`) AS orders_count, COUNT(g.`good_id`) AS goods_count
                                FROM `users` AS u 
                                    LEFT JOIN `user_baskets` AS ub ON u.`user_id` = ub.`user_id` AND ub.`user_basket_status` NOT IN ('active', 'returned')
                                    LEFT JOIN `goods` AS g ON u.`user_id` = g.`user_id` AND g.`good_visible` = 'true' AND g.`good_status` != 'deny' AND g.`good_status` != 'customize'
                                WHERE MD5(LOWER(u.`" . ($_GET['phone'] ? 'user_phone' : 'user_email') . "`)) = '$key' AND u.`user_status` != 'deleted' AND u.`user_status` != 'banned'
                                GROUP BY u.`user_id`
                                ORDER BY u.`user_activation`, user_last_order DESC");
            
                    // WHERE MD5(LOWER(u.`user_email`)) IN ('$key'" . (($this->user->authorized) ? ", '" . md5(strtolower($this->user->info['user_email'])) . "'" : '') . ") AND u.`user_status` != 'deleted' AND u.`user_status` != 'banned'
                    if ($sth->rowCount() > 0)
                    {
                        $users = $sth->fetchAll();
                        
                        // Выбрать один из предложенных аккаунтов для продолжения покупки
                        // - отправка на почту письма с кодом и паролем от аккаунта
                        if ($_POST['select'])
                        {
                            $uid  = intval($_POST['id']);
                            $user = App::db()->query("SELECT `user_id`, `user_email`, `user_login`, `user_password_md5` FROM `users` WHERE `user_id` = '$uid' LIMIT 1")->fetch();
                            $code = substr(md5($user['user_password_md5']), 0, 10);
                            
                            App::mail()->send(array($users[0]['user_id']), 254, array(
                                'link'   => mainUrl . '/' . $this->page->module . '/merge/confirm/?key=' . $key . '&user_id=' . $uid . '&code=' . $code . ($_GET['phone'] ? '&phone=1' : ''),
                                'code'   => $code,
                                'login'  => $user['user_login'],
                                'email'  => $user['user_email']
                            ));
                    
                            exit();
                        }
                        
                        // Подтвердить выбор одного из предложенных аккаунтов для продолжения покупки
                        if ($this->page->reqUrl[2] == 'confirm')
                        {
                            $uid = intval($_GET['user_id']);
                            
                            foreach ($users AS $b) {
                                if ($b['user_id'] == $uid) {
                                    $selected = $b;
                                }
                            }
                    
                            if ($_GET['code']) {
                                $confirm = (trim($_GET['code']) == substr(md5($selected['user_password_md5']), 0, 10));
                            } else {
                                $confirm = false;
                            }
                    
                            // если пользователь выбран и пароль введён верно
                            if (!empty($uid) && $confirm)
                            {
                                if ($uid != $this->user->id)
                                {
                                    $this->user->id = $uid;
                                    $this->user->authorize();
                                    $this->user->activate();
                                }
                                
                                $this->user->change(array('user_email' => $users[0]['user_email']));
                                
                                $sth2 = App::db()->prepare("UPDATE `user_baskets` SET `user_id` = :uid WHERE `user_id` = :id");
                                $sth3 = App::db()->prepare("UPDATE `goods` SET `user_id` = :uid WHERE `user_id` = :id");
                                $sth4 = App::db()->prepare("UPDATE `users` SET `user_status` = 'deleted' WHERE `user_id` = :id LIMIT 1");
                    
                                // сливаем все бонусы с "отключённых" аккаунтов на выбранный
                                // и обнуляем бонусы "отключённых"
                                foreach ($users AS $b)
                                {
                                    if ($b['user_id'] == $uid) {
                                        continue;
                                    }
                                    
                                    if ($b['user_bonus'] > 0) {
                                        addBonusJournalRecord ($uid, $b['user_bonus'], "Объединение аккаунтов по email. с отключённого - " . $b['user_id'], 'inc');
                                        addBonusJournalRecord ($b['user_id'], $b['user_bonus'], "Объединение аккаунтов по email. ушли подтверждённому - " . $b['user_id'], 'dec');
                                    }
                    
                                    // переводим заказы "отключённых" пользователей на выбранного
                                    $sth2->execute(['uid' => $uid, 'id' => $b['user_id']]);
                                    
                                    // переводим работы "отключённых" пользователей на выбранного
                                    $sth3->execute(['uid' => $uid, 'id' => $b['user_id']]);
                                    
                                    // "выключаем" все остальные аккаунты
                                    $sth4->execute(['id' => $b['user_id']]);
                                }
            
                                header('location:' . (($_GET['next']) ? $_GET['next'] : '/'));
                                exit('yes');
                            }
                            else
                            {
                                exit('Проверочный код указан не верно');
                            }
                    
                            exit();
                        }
                        
                        
                        /**
                         * форма
                         */
                        if ($this->user->authorized)
                        {
                            $foo = App::db()->query(sprintf("SELECT COUNT(*) AS c FROM `user_baskets` AS ub WHERE ub.`user_id` = '%d' AND ub.`user_basket_status` NOT IN ('active', 'returned')", $this->user->id))->fetch();
                            $orders_count = $foo['c']; 
                            
                            $foo = App::db()->query(sprintf("SELECT COUNT(*) AS c FROM `goods` AS g WHERE g.`user_id` = '%d' AND g.`good_visible` = 'true' AND g.`good_status` NOT IN ('new', 'deny', 'customize')", $this->user->id))->fetch();
                            $goods_count = $foo['c'];
                            
                            $founded = false;
                            
                            foreach ($users AS $b) {
                                if ($b['user_id'] == $this->user->id) {
                                    $founded = true;
                                    break;
                                }
                            }
                            
                            if (!$founded)
                                $users[] = array(
                                    'user_id'            => $this->user->id,
                                    'user_name'          => $this->user->info['user_name'],
                                    'user_login'         => $this->user->info['user_login'],
                                    'user_phone'         => $this->user->info['user_phone'], 
                                    'user_last_login'    => $this->user->info['user_last_login'],
                                    'user_activation'    => $this->user->info['user_activation'],
                                    'user_bonus'         => $this->user->info['user_bonus'],
                                    'user_register_date' => $this->user->info['user_register_date'],
                                    'orders_count'       => $orders_count,
                                    'goods_count'        => $goods_count,
                                );
                        }
            
                        foreach ($users as &$u)
                        {
                            $u['orders_count'] = intval($u['orders_count']);
                            $u['goods_count']  = intval($u['goods_count']);
                            $u['user_avatar']  = userId2userGoodAvatar($u['user_id'], 100);
                            $u['user_login']   = stripslashes($u['user_login']);
                            $u['user_name']    = stripslashes($u['user_name']);
                            $u['user_phone']   = '+' . substr($u['user_phone'], 0, -6) . '****' . substr($u['user_phone'], -2);
                            $u['user_register_date'] = datefromdb2textdate($u['user_register_date']);
            
                            if ($u['user_last_login'] != '0000-00-00 00:00:00')
                                $u['user_last_login'] = datefromdb2textdate($u['user_last_login']);
                            else
                                $u['user_last_login'] = 'неизвестно';
            
                            if (!empty($u['user_last_order']))
                                $u['user_last_order'] = datefromdb2textdate($u['user_last_order']);
                        }
            
                        $this->view->setVar('user', $users);
                        $this->view->setVar('email', $users[0]['user_email']);
                        $this->view->setVar('next', $next);
                    }
                    else
                    {
                        header('location: ' . $_SERVER['HTTP_REFERER']);
                        exit();
                    }
                }
                else
                {
                    header('location: ' . $_SERVER['HTTP_REFERER']);
                    exit();
                }
            }
            // Экшен - объединение нескольких аккаунтов с одним телефоном
            elseif ($this->page->reqUrl[1] == 'phonemerge')
            {
                // неча здесь делать не авторизованным
                if (!$this->user->authorized)
                {
                   header('location: /');
                   exit();
                }
            
                $this->page->breadcrump[] = array(
                    'link'    => '/' . $this->page->module . '/merge/',
                    'caption' => 'Объединение аккаунтов');
                    
                // проверка пароля перед объединением аккаунтов (AJAX)
                if ($this->page->reqUrl[2] == 'check_confirm')
                {
                    $info = json_decode($this->user->meta->merge_phone_temp, 1);
            
                    exit (($info['code'] == $_GET['password']) ? 'true' : 'false');
                }
            
            
                // список доступных пользователей для выбора
                if ($_GET['key'])
                {
                    $key  = addslashes(strtolower(trim($_GET['key'])));
                    $next = addslashes(trim($_GET['next']));
            
                    $this->view->setVar('key', $key);
            
                    $sth = App::db()->query("SELECT u.`user_id`, u.`user_name`, u.`user_login`, u.`user_email`, u.`user_phone`, u.`user_last_login`, u.`user_activation`, u.`user_bonus`, u.`user_register_date`, MAX(ub.`user_basket_date`) AS user_last_order, COUNT(ub.`user_basket_id`) AS orders_count, COUNT(g.`good_id`) AS goods_count
                                     FROM `users` AS u 
                                          LEFT JOIN `user_baskets` AS ub ON u.`user_id` = ub.`user_id`
                                          LEFT JOIN `goods` AS g ON u.`user_id` = g.`user_id` AND g.`good_visible` = 'true' AND g.`good_status` != 'deny' AND g.`good_status` != 'customize'
                                     WHERE MD5(LOWER(u.`user_id`)) = '$key' AND u.`user_status` != 'deleted' AND u.`user_status` != 'banned'
                                     GROUP BY u.`user_id`
                                     ORDER BY u.`user_activation`, user_last_order DESC");
                                      
                    if ($sth->rowCount() > 0)
                    {
                        $users = $sth->fetchAll();
                        
                        $foo = App::db()->query(sprintf("SELECT COUNT(*) AS c FROM `user_baskets` AS ub WHERE ub.`user_id` = '%d' AND ub.`user_basket_status` NOT IN ('active', 'returned')", $this->user->id))->fetch();
                        $orders_count = $foo['c']; 
                        
                        $foo = App::db()->query(sprintf("SELECT COUNT(*) AS c FROM `goods` AS g WHERE g.`user_id` = '%d' AND g.`good_visible` = 'true' AND g.`good_status` NOT IN ('new', 'deny', 'customize')", $this->user->id))->fetch();
                        $goods_count = $foo['c'];
                        
                        $users[] = array(
                            'user_id'            => $this->user->id,
                            'user_name'          => $this->user->user_name,
                            'user_login'         => $this->user->user_login,
                            'user_phone'         => $this->user->user_phone, 
                            'user_last_login'    => $this->user->user_last_login,
                            'user_activation'    => $this->user->user_activation,
                            'user_bonus'         => $this->user->user_bonus,
                            'user_register_date' => $this->user->user_register_date,
                            'orders_count'       => $orders_count,
                            'goods_count'        => $goods_count,
                        );
                         
                        
                        $phone = $users[0]['user_phone'];
                        
                        // Подтвердить выбор одного из предложенных аккаунтов для продолжения покупки
                        if ($this->page->reqUrl[2] == 'confirm')
                        {
                            // id выбранного аккаунта
                            $uid  = intval($_GET['user_id']);
                            
                            $info = json_decode($this->user->meta->merge_phone_temp, 1);
                            
                            if ($_GET['code']) {
                                $confirm = (trim($_GET['code']) == $info['code']);
                            } else {
                                $confirm = false;
                            }
                            
                            if (time() <= $info['expired'])
                            {
                                // если пользователь выбран и пароль введён верно
                                if (!empty($uid) && $confirm)
                                {
                                    $this->user->delMeta('merge_phone_temp');
                                    
                                    if ($uid != $this->user->id)
                                    {
                                        $this->user->id = $uid;
                                        $this->user->activate();
                                        $this->user->authorize();
                                    }
                                    
                                    $this->user->change(array('user_phone' => $info['phone']));
                                    
                                    // сливаем юзеров
                                    foreach ($users AS $b)
                                    {
                                        if ($b['user_id'] != $uid)
                                        {
                                            // переводим все бонусы с "отключённых" аккаунтов на выбранный и обнуляем бонусы "отключённых"
                                            addBonusJournalRecord ($uid, $b['user_bonus'], "Объединение аккаунтов по телефону. с отключённого - " . $b['user_id'], 'inc');
                                            addBonusJournalRecord ($b['user_id'], $b['user_bonus'], "Объединение аккаунтов по телефону. ушли подтверждённому - " . $uid, 'dec');
                            
                                            // переводим заказы "отключённых" пользователей на выбранного
                                            App::db()->query("UPDATE `user_baskets` SET `user_id` = '" . $uid ."' WHERE `user_id` = '" . $b['user_id'] . "'");
                            
                                            // переводим работы "отключённых" пользователей на выбранного
                                            App::db()->query("UPDATE `goods` SET `user_id` = '" . $uid ."' WHERE `user_id` = '" . $b['user_id'] . "'");
                                            
                                            // "выключаем" все остальные аккаунты
                                            App::db()->query("UPDATE `users` SET `user_status` = 'deleted', `user_phone` = '' WHERE `user_id` = '" . $b['user_id'] . "'");
                                        }
                                    }
                                    
                                    header('location:' . (($_GET['next']) ? $_GET['next'] : '/'));
                                    exit('yes');
                                }
                                else
                                {
                                    exit('no');
                                }
                            } else {
                                $this->view->setVar('ERROR', array('text' => 'Срок действия проверочного кода истёк'));
                            }
                            
                            exit();
                        }
                        
                        
                        // Выбрать один из предложенных аккаунтов (клик)
                        if ($_POST['select'])
                        {
                            $info = json_decode($this->user->meta->merge_phone_temp, 1);
                        
                            // отправка сообщения не чаще чем 1 раз в 5 минут
                            if ($info['sended'] && $info['sended'] > time() - 300)
                                exit('wait');
                        
                            $info['phone']   = $phone;
                            $info['code']    = user::getSecretCode(5, TRUE);
                            $info['expired'] = time() + (3600 * 3);
                            $info['sended']  = time();
                            
                            $this->user->setMeta('merge_phone_temp', json_encode($info));
                            
                            try
                            {
                                $sms_id = App::sms()->send($info['phone'], $info['code']);
                            }
                            catch (Exception $e) { exit($e->getMessage()); }
                            
                            exit("$sms_id");
                        }
                        
            
                        /**
                         * форма
                         */
                        
                        foreach ($users as &$u)
                        {
                            $u['orders_count'] = intval($u['orders_count']);
                            $u['goods_count']  = intval($u['goods_count']);
                            $u['user_avatar']  = userId2userGoodAvatar($u['user_id'], 100);
                            $u['user_login']   = stripslashes($u['user_login']);
                            $u['user_name']    = stripslashes($u['user_name']);
                            $u['user_phone']   = (!empty($u['user_phone'])) ? '+' . substr($u['user_phone'], 0, -6) . '****' . substr($u['user_phone'], -2) : '-';
                            $u['user_register_date'] = datefromdb2textdate($u['user_register_date']);
            
                            if ($u['user_last_login'] != '0000-00-00 00:00:00')
                                $u['user_last_login'] = datefromdb2textdate($u['user_last_login']);
                            else
                                $u['user_last_login'] = 'неизвестно';
            
                            if (!empty($u['user_last_order']))
                                $u['user_last_order'] = datefromdb2textdate($u['user_last_order']);
                        }
            
                        $this->view->setVar('user', $users);
                        $this->view->setVar('next', $next);
                        $this->view->setVar('phone', $users[0]['user_phone']);
                    }
                    else
                    {
                        header('location: ' . $_SERVER['HTTP_REFERER']);
                        exit();
                    }
                }
                else
                {
                    header('location: ' . $_SERVER['HTTP_REFERER']);
                    exit();
                }
            }

            $this->view->generate('index.tpl');
        }
    }