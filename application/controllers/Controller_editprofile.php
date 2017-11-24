<?
    namespace application\controllers;
    
    use \smashEngine\core\App AS App;

    use \application\models\mail AS mail;
    use \application\models\user AS user;

    use \Exception;
                 
    class Controller_editprofile extends \smashEngine\core\Controller
    {
        public function action_index()
        {
            if (!$this->user->authorized) {
                header('location: /'); 
                exit(); 
            }
            
            $this->page->tpl = 'editprofile/index.tpl';
            $this->page->footer_tpl = 'order/footer.tpl';
            
            try
            {
                $this->page->import(array(
                    '/css/editprofile_2013.css',
                    '/js/registration.v2.js',
                    '/js/jquery.autocomplete.pack.js',
                ));
            }
            catch (Exception $e)
            {
                printr($e->getMessage());
            }
            
            $this->page->breadcrump[] = array(
                'link' => ($this->user->user_is_fake == 'false') ? '/profile/' : '#',
                'caption' => 'Профиль');
            
            $this->page->breadcrump[] = array(
                'link' => '/' . $this->page->module . '/',
                'caption' => 'Редактировать');
            
            if ($_SESSION['registration_complite'])
            {
                $this->view->setVar('registration_complite', 1);
                unset($_SESSION['registration_complite']);
            }
            
            /**
             * Сохранние дополнительных данных после регистрации через  соц.сети
             */
            if ($_POST['social_submit'])
            {
                $this->user->user_birthday = intval($_POST['birthdayYear']) . '-' . intval($_POST['birthdayMonth']) . '-' . intval($_POST['birthdayDay']);
                $this->user->user_sex  = addslashes($_POST['sex']);
                $this->user->user_name = addslashes($_POST['name']);
                $this->user->user_email = addslashes($_POST['email']);
                
                $this->user->save();
                
                // --------------------------------------------------------------------------------------------------------------
                // смена мыла
                // --------------------------------------------------------------------------------------------------------------
                if (!empty($_POST['email']))
                {
                    $_POST['email'] = trim(strtolower($_POST['email']));
                    
                    $sth = App::db()->query("SELECT `user_id` FROM `users` WHERE `user_email` = '".addslashes($_POST['email'])."' AND `user_is_fake` = 'false'");
            
                    if ($sth->rowCount() == 0)
                    {
                        $this->user->change(array('user_email' => addslashes($_POST['email'])));
                        
                        $code = user::getSecretCode();
                        
                        $this->user->setMeta('email_temp', json_encode(array('email' => addslashes($_POST['email']), 'code' => $code, 'expired' => time() + (3600 * 3))));
                        
                        App::mail()->send(array(), 135, array(
                            'confirmLink' => 'http://www.maryjane.ru/' . $this->page->module . '/confirmemail/' . $this->user->id . '/' . $code . '/', 
                            'changeTo' => $_POST['email']), 
                            array($_POST['email']));
            
                        //$this->view->setVar('CHANGE_EMAIL', array('email' => $_POST['email']));
                        $this->view->setVar('emailMessage', 'На указанный Вами адрес ' . $_POST['email'] . ' выслано письмо со ссылкой активации нового email.');
                        $success = TRUE;
                    } else 
                        $error = 'Извините, но вы пытаетесь сохранить e-mail, который уже используется другим пользователем. <a href="/registration/merge/?key=' . md5($_POST['email']) . '" style="color:red">Возможно это Вы?</a>.';
                }
            
                // --------------------------------------------------------------------------------------------------------------
                // смена телефона
                // --------------------------------------------------------------------------------------------------------------
                if (!empty($_POST['phone']))
                {
                    $_POST['phone'] = str_replace(array(' ', '(', ')', '-', '+'), '', trim(strtolower($_POST['phone'])));
                                
                    if (strpos($_POST['phone'], '8') === 0)
                        $_POST['phone'] = '7' . substr($_POST['phone'], 1);
                    
                    $sth = App::db()->query("SELECT `user_id` FROM `users` WHERE `user_phone` = '".addslashes($_POST['phone'])."'");
            
                    if ($sth->rowCount() == 0)
                    {
                        $code = user::getSecretCode(5, TRUE);
                        
                        try
                        {
                            $sms_id = App::sms()->send($_POST['phone'], $code);
                
                            if (is_numeric($sms_id))
                            {
                                $this->user->setMeta('phone_temp', json_encode(array('phone' => addslashes($_POST['phone']), 'sms_id' => $sms_id, 'code' => $code, 'expired' => time() + (3600 * 3))));
                                
                                header('location: /' . $this->page->module . '/changephone/');
                                exit();
                            }
                            else
                                $error = 'К сожалению нам не удалось отправить СМС на указанный вами номер ' . $_POST['phone']. '. Возможно вы ошиблись при его вводе.';
                        }
                        catch (Exception $e)
                        {
                        //  $error = 'Не удаётся отправить смс на указанный номер':
                        }
                    } 
                    else 
                    {
                        $row = $sth->fetch();
                        $error = 'Извините, но вы пытаетесь сохранить телефон, который уже используется другим пользователем.<a href="/registration/phonemerge/?key=' . md5($row['user_id']) . '&next=/editprofile/">Возможно это вы?</a>';
                    }
                }
                
                if (!empty($error))
                    $this->view->setVar('ERROR', array('text' => $error));
                elseif (!empty($success)) {}
                else {
                    header('location: /' . $this->page->module . '/'); 
                }
            }
            
            
            
            if ($_POST['save'])
            {
                // ОСНОВНЫЕ ДАННЫЕ ПРОФИЛЯ
                // --------------------------------------------------------------------------------------------------------------
                if (!empty($_POST['birthdayMonth']) && !empty($_POST['birthdayDay']) && !empty($_POST['birthdayYear']) && checkdate($_POST['birthdayMonth'], $_POST['birthdayDay'], $_POST['birthdayYear']))
                    $data['user_birthday'] = intval($_POST['birthdayYear']) . "-" . intval($_POST['birthdayMonth']) . "-" . intval($_POST['birthdayDay']);
            
                if (!empty($_POST['sex']))
                    $data['user_sex'] = addslashes($_POST['sex']);
                
                if (!$data['user_city'] = cityName2id($_POST['city'], '', 0, 1))
                {
                    $error = 'Такого города нет в нашей базе. Если Вы хотите указать именно его, <a href="http://www.maryjane.ru/feedback/?width=300&height=450" class="showFeedback thickbox dashed">свяжитесь с администрацией сайта</a>';
                }
                
                $data['user_name']      = addslashes($_POST['name']);
                $data['user_show_name'] = ((!$_POST['showRealName']) ? 'false' : 'true');
                
                $url = parse_url($_POST['url']);
                $data['user_url'] = addslashes(($url['scheme'] ? $url['scheme'] : 'http') . '://' . str_replace(array('http://', 'https://'), '', trim($_POST['url'])));
            
                
                // --------------------------------------------------------------------------------------------------------------
                // смена логина
                // --------------------------------------------------------------------------------------------------------------
                if ($_POST['login'])
                {
                    $can = false;
                    
                    preg_match("/^user[0-9]{5,}/", $this->user->user_login, $matches);
                    if ($matches[0]) {
                        $can = TRUE;
                    }
                    
                    preg_match("/\*\*\*\*/", $this->user->user_login, $matches);
                    if ($matches[0]) {
                        $can = TRUE;
                    }
                    
                    
                    preg_match("/^vk-[0-9]*/", $this->user->user_login, $matches);
                    if ($matches[0]) {
                        $vk = $this->user->meta->user_vk;
                        if (!empty($vk))
                            $can = TRUE;
                    }
                    
                    preg_match("/^fb-[0-9]*/", $this->user->user_login, $matches);
                    if ($matches[0]) {
                        $fb = $this->user->meta->user_facebook; 
                        if (!empty($fb))
                            $can = TRUE;
                    } 
                    
                    if ($can)   
                        $data['user_login'] = addslashes(substr(trim(strip_tags($_POST['login'])), 0, 255));
                }
                
                $this->user->change($data);
                
                // --------------------------------------------------------------------------------------------------------------
                // О СЕБЕ
                // --------------------------------------------------------------------------------------------------------------
                if (!empty($_POST['aboutMe']))
                    $this->user->setMeta('about_text', nl2br(strip_tags(trim($_POST['aboutMe']))));
                
                if (!empty($_POST['whoami']))
                    $this->user->setMeta('whoami', strip_tags(trim($_POST['whoami'])));
                
                if (!empty($_POST['personal_title']))
                    $this->user->setMeta('personal_title', strip_tags(trim($_POST['personal_title'])));
            
                // --------------------------------------------------------------------------------------------------------------
                // ПОДПИСКИ
                // --------------------------------------------------------------------------------------------------------------
                try
                {
                    if ($_POST['subscriptions']) {
                        $this->user->change(array('user_subscription_status' => 'active'));
                        App::db()->query("UPDATE `mail_list_subscribers` SET `mail_list_id` = ABS(`mail_list_id`) WHERE `user_id` = '" . $this->user->id . "'");
                        App::db()->query("INSERT IGNORE INTO `mail_list_subscribers` (`user_id`, `mail_list_id`) VALUES ('" . $this->user->id . "','6')");
                    } else {
                        $this->user->change(array('user_subscription_status' => 'canceled'));
                        App::db()->query("UPDATE `mail_list_subscribers` SET `mail_list_id` = -1 * ABS(`mail_list_id`) WHERE `user_id` = '" . $this->user->id . "'");
                    }
                }
                catch (Exception $e)
                {
                    printr($e, 1);
                }
                
                // --------------------------------------------------------------------------------------------------------------
                // УВЕДОМЛЕНИЯ
                // --------------------------------------------------------------------------------------------------------------
                try
                {
                    if ($_POST['notifications'])
                    {
                        App::db()->query("DELETE FROM `messages_unwatch` WHERE `user_id` = '". $this->user->id ."'");
                    }
                    else 
                    {
                        $this->user->unwatch();
                        App::db()->query("INSERT IGNORE INTO `messages_unwatch` (`user_id`) VALUES ('". $this->user->id ."')");
                    }
                }
                catch (Exception $e)
                {
                    printr($e, 1);
                }
                
                // --------------------------------------------------------------------------------------------------------------
                // ОТЧЁТ ПО ПРОДАЖАМ
                // --------------------------------------------------------------------------------------------------------------
                if (in_array($_POST['saleReport'], array('everyday', 'everyweek', 'everymonth', 'no'))) {
                    if ($this->page->reqUrl[2] != $this->user->meta->saleReport) {
                        $this->user->setMeta('saleReport', $_POST['saleReport']);
                    }
                }
                
                // --------------------------------------------------------------------------------------------------------------
                // СМЕНА ПОЧТЫ
                // --------------------------------------------------------------------------------------------------------------
                if (strtolower(trim($_POST['email'])) != strtolower($this->user->user_email))
                {
                    if (!empty($_POST['email']))
                    {
                        $_POST['email'] = trim(strtolower($_POST['email']));
                        
                        $sth = App::db()->query("SELECT `user_id` FROM `users` WHERE `user_email` = '".addslashes($_POST['email'])."' AND `user_is_fake` = 'false'");
                
                        if ($sth->rowCount() == 0)
                        {
                            $code = user::getSecretCode();
                            
                            $this->user->setMeta('email_temp', json_encode(array('email' => addslashes($_POST['email']), 'code' => $code, 'expired' => time() + (3600 * 3))));
                            
                            App::mail()->send(array(), 135, array(
                                'confirmLink' => 'http://www.maryjane.ru/' . $this->page->module . '/confirmemail/' . $this->user->id . '/' . $code . '/', 
                                'changeTo' => $_POST['email']), 
                                array($_POST['email']));
                
                            $this->user->user_email = $_POST['email'];
                            
                            //$this->view->setVar('CHANGE_EMAIL', array('email' => $_POST['email']));
                            $this->view->setVar('emailMessage', 'На указанный Вами адрес ' . $_POST['email'] . ' выслано письмо со ссылкой активации нового email.');
                            $success = TRUE;
                        } else 
                            $error = 'Извините, но вы пытаетесь сохранить e-mail, который уже используется другим пользователем. <a href="/registration/comeback/?key=' . md5($_POST['email']) . '" style="color:red">Возможно это Вы?</a>.';
                    } else 
                        $error = 'Извините, но вы пытаетесь сохранить пустой e-mail или он совпадает с текущим';
                }
                    
                if (!empty($error))
                    $this->view->setVar('ERROR', array('text' => $error));
                elseif (!empty($success)) {}
                else
                    header('location: /' . $this->page->module . '/'); 
            }
            
            switch (trim($this->page->reqUrl[1])) 
            {
                /**
                 * Получение доп.информации после первой авторизации через соц сеть
                 */
                case 'socialGetMore':
                    
                    $this->view->setVar('content_tpl', 'login/vk.getmore.tpl');
                    
                    $this->view->setVar('bdays', range(1, 31));
                    $this->view->setVar('bmonths', range(1, 12));
                    $this->view->setVar('byears', range(1950, date('Y') - 10));
                    $this->view->setVar('birthday', explode('-', date('Y-n-j', strtotime($this->user->user_birthday))));   
                    
                    $this->view->generate('index.popup.tpl');
                    exit();
                    
                    break;
                
                /**
                 * Отвязать от пользователя соц.сеть
                 */
                case 'socialUnlink':
                    
                    if (in_array($this->page->reqUrl[2], array('facebook', 'vk', 'gplus', 'instagram')))
                    {
                        $this->user->delMeta('user_' . addslashes(trim($this->page->reqUrl[2])));
                    }
                    
                    header('location: ' . $_SERVER['HTTP_REFERER']);
                    exit();
                    
                    break;  
                
                /**
                 * смена пароля
                 */
                case 'changePassword':
                    
                    $oldPass            = trim($_POST["pass"]);
                    $newPass            = trim($_POST["password1"]);
                    $newPass_confirm    = trim($_POST["password2"]);
            
                    if (!empty($oldPass) && !empty($newPass) && !empty($newPass_confirm)) {
            
                        if (md5(SALT . $oldPass) === $this->user->user_password_md5) {
            
                            if ($newPass === $newPass_confirm) {
            
                                $this->user->change(array('user_password_md5' => md5(SALT . $_POST['password1'])));
            
                                if ($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest')
                                    $this->view->setVar('changePasswordSuccess', TRUE);
                                else
                                    exit(json_encode(array('success' => 1)));
            
                            } else {
                                $error = "Новый пароль и его подтверждение не совпадают";
                            }
                        } else {
                            $error = "Старый пароль указан неверно";
                        }
            
                    } else {
                        $error = "Не все поля обязательные для смены пароля заполнены";
                    }
                    
                    if (!empty($error))
                    {
                        if ($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest')
                            $this->view->setVar("changePasswordError", array('text' => $error));
                        else
                            exit(json_encode(array('error' => $error)));
                    }
                break;
            
                /**
                 * смена логина
                 */
                case 'changeLogin':
                    
                    if (isset($_POST['login']) && !empty($_POST['login']))
                    {
                        $login = addslashes(substr(trim($_POST['login']),0,255));
            
                        if (validateLogin($login))
                        {
                            $r = App::db()->query("SELECT `user_id` FROM `users` WHERE `user_login` = '$login' AND `user_status` = 'active'");
                            
                            if ($r->rowCount() == 0)
                            {
                                $this->user->change(array('user_login' => $login));
            
                                App::mail()->send(array($this->user->id), 134, array(
                                    'userLogin' => $login
                                ));
                                
                                $this->view->setVar('changeLoginSuccess', TRUE);
                                
                                exit('ok');
                            }
                            else $error = "Пользователь с таким именем уже зарегистрирован.";
                        }
                        else $error = "Указанное имя не соответствует требованиям. Оно может содержать только латинские символы, цифры и символ нижнего подчёркивания и быть не длиннее 15 символов";
                    }
                    else $error = "Извините, но нельзя сохранить пустой логин";
                    
                    exit($error);
                break;
            
                
                // ПОДТВЕРЖДЕНИЕ СМЕНЫ EMAIL
                case 'confirmemail':
            
                    if ($this->user->authorized) 
                    {
                        if ($this->user->id == $this->page->reqUrl[2]) 
                        {
                            if (!empty($this->page->reqUrl[2]))
                            {
                                if (!empty($this->page->reqUrl[3]))
                                {
                                    $info = json_decode($this->user->meta->email_temp, 1);
                    
                                    if (trim($this->page->reqUrl[3]) == $info['code'])
                                    {
                                        if (time() <= $info['expired']) 
                                        {
                                            $this->user->change(array('user_email' => $info['email']));
                                            $this->user->delMeta('email_temp');
                                            
                                            if ($this->user->user_is_fake == 'true')
                                            {
                                                $this->user->activate();
                                            }
                                            
                                            $this->view->setVar('currentuser', objectToArray($this->user->info));
                                        } 
                                        else $error = 'Проверочный код уже недействителен';
                                    }
                                    else $error = 'Проверочный код неверен';
                                }
                                else $error = 'Проверочный код отсутствует';
                            }
                            else $error = 'Неизвестен пользователь';
                        }
                        else $error = 'Вы не можете выполнить  данную операцию';
                    } 
                    else $error = 'Вы должны быть авторизованы';   
                                     
                    if (!empty($error))
                        $this->view->setVar('ERROR', array('text' => $error));
                    else
                        $this->view->setVar('CHANGE_EMAIL_SUCCESS', array('email' => $info['email']));
    
                break;
                // END : ПОДТВЕРЖДЕНИЕ СМЕНЫ EMAIL
            
                /**
                 * смена телефона
                 */
                case 'changephone':
                    
                    // запрос на добавление телефона
                    if (isset($_POST['phone']))
                    {
                        if (trim($_POST['phone']) != $this->user->user_phone)
                        {
                            if (!empty($_POST['phone']))
                            {
                                $_POST['phone'] = str_replace(array(' ', '(', ')', '-', '+'), '', trim(strtolower($_POST['phone'])));
                                
                                if (strpos($_POST['phone'], '8') === 0)
                                    $_POST['phone'] = '7' . substr($_POST['phone'], 1);
                                
                                $this->user->user_phone = $currentuser['user_phone'] = $_POST['phone'];
                                
                                $sth = App::db()->query("SELECT `user_id` FROM `users` WHERE `user_phone` = '".addslashes($_POST['phone'])."' LIMIT 1");
                        
                                if ($sth->rowCount() == 0)
                                {
                                    $code = user::getSecretCode(5, TRUE);
                                    
                                    try
                                    {
                                        $sms_id = App::sms()->send($_POST['phone'], $code);
                                    }
                                    catch (Exception $e) { $error = 'К сожалению нам не удалось отправить СМС на указанный Вами номер ' . $_POST['phone']. '. ' . App::sms()->getError(); }
                                    
                                    if (empty($error))
                                    {
                                        if (is_numeric($sms_id))
                                        {
                                            $this->user->setMeta('phone_temp', json_encode(array('phone' => addslashes($_POST['phone']), 'sms_id' => $sms_id, 'code' => $code, 'expired' => time() + (3600 * 3))));
                                            
                                            header('location: /' . $this->page->module . '/changephone/sended/');
                                            exit();
                                        }
                                        else
                                            $error = 'К сожалению нам не удалось отправить СМС на указанный вами номер ' . $_POST['phone']. '. Возможно вы ошиблись при его вводе.' ;
                                    }
                                } else {
                                    $row = $sth->fetch();
                                    $error = 'Извините, но вы пытаетесь сохранить телефон, который уже используется другим пользователем. <a href="/registration/phonemerge/?key=' . md5($row['user_id']) . '&next=/editprofile/" class="error"">Возможно это вы?</a>';
                                }
                            } else 
                                $error = 'Извините, но вы пытаетесь сохранить пустой телефон.';
                        }
                    }
                    else
                    {
                        if ($this->page->reqUrl[2] == 'success') 
                        {
                            $this->view->setVar('phoneMessage', 'Вы успешно подтвердили свой телефон');
                        }
                        elseif ($this->page->reqUrl[2] == 'checkcode')
                        {
                            $info = json_decode($this->user->meta->phone_temp);
                            exit (($info->code == $_GET['code']) ? 'true' : 'false');
                        }
                        elseif ($this->page->reqUrl[2] == 'reedit') 
                        {
                            $this->user->delMeta('phone_temp');
                            
                            header('location: /' . $this->page->module . '/');
                            exit();
                        }
                        elseif ($this->page->reqUrl[2] == 'resend') 
                        {
                            $phone = json_decode($this->user->meta->phone_temp);
                            $sms_id = App::sms()->send($phone->phone, $phone->code);
                            exit("$sms_id");
                        }
                        else
                        {
                            $info = json_decode($this->user->meta->phone_temp, 1);
                            
                            if (!empty($info) && count($info) > 0)
                            {
                                // форма
                                if ($this->page->reqUrl[2] == 'sended')
                                {
                                    $this->view->setVar('phoneMessage', 'На указанный вам телефон ' . $info['phone'] . ' выслано смс с кодом подтверждения.');
                                    $this->view->setVar('CHANGE_PHONE', array('phone' => $info['phone']));
                                }
                              
                                // проверка
                                if ($_POST['code'])
                                {
                                    if (trim($_POST['code']) == $info['code'])
                                    {
                                        if (time() <= $info['expired']) 
                                        {
                                            $this->user->change(array('user_phone' => $info['phone']));
                                            $this->user->delMeta('phone_temp');
                                            
                                            if ($this->user->user_is_fake == 'true')
                                            {
                                                $this->user->activate();
                                            }
                                            
                                            if (!empty($_POST['password']))
                                            {
                                                $this->user->setPassword($_POST['password']);
                                            }
                                            
                                            if ($_POST['next'] == 'back' && $_SERVER['HTTP_REFERER']) {
                                                $this->page->go(str_replace('changephone-success/', '', $_SERVER['HTTP_REFERER']) . 'changephone-success/');
                                            } else {
                                                $this->page->go('/' . $this->page->module . '/changephone/success/');
                                            }
                                        } 
                                        else $error = 'Проверочный код уже недействителен';
                                    }
                                    else $error = 'Проверочный код неверен';
                                }
                            }
                            else $error = 'Извините, но Вы не запрашивали смену телефона';
                        }
                    }
                    
                    if (!empty($error))
                        $this->view->setVar('phoneError', $error);
                    
                    break;
                
                // ЗАГРУЗКА АВАТАРА
                case 'adduseravatar':
                    
                    if (empty($error))
                    {
                        $file_name = 'avatar_' . substr(md5($this->user->id),0,6) .  '.gif';
                        $folder    = IMAGEUPLOAD . '/avatars/';
            
                        $r = catchFileNew('Filedata', $folder, '', 'gif,png,jpeg,jpg');
            
                        if ($r['status'] == 'ok')
                        {
                            $is = getimagesize(ROOTDIR . $r['path']);
            
                            $img  = createImageFrom(ROOTDIR . $r['path']);
            
                            $w = 100;
                            $h = round(($w / $is[0]) * $is[1]);
            
                            if ($h < 100)
                            {
                                $h = 100;
                                $w = round(($h / $is[1]) * $is[0]);
                            }
            
                            $ri = imagecreatetruecolor($w, $h);
                            imagecopyresampled($ri, $img, 0, 0, 0, 0, $w, $h, $is[0], $is[1]);
            
                            $ti = imagecreatetruecolor(100, 100);
                            imagecopy($ti, $ri, 0, 0, ($w - 100) / 2, ($h - 100) / 2, $w, $h);
            
                            imagegif($ti, ROOTDIR . $folder . $file_name, 100);
            
                            deletePicture($r['id']);
            
                            $this->user->change(array('user_picture' => file2db($folder . $file_name)));
            
                            createThumb(ROOTDIR . $folder . $file_name, ROOTDIR . $folder, 50, 50, 100);
                            createThumb(ROOTDIR . $folder . $file_name, ROOTDIR . $folder, 25, 25, 100);
            
                            exit(json_encode(array('ok' => $folder . $file_name)));
                        }
                        else
                            exit(json_encode(array('error' => $r['message'])));
                    }
                    else
                        exit(json_encode(array('error' => $error)));
                    break;
                // END : ЗАГРУЗКА АВАТАРА
            
                case 'deleteuseravatar':
                    
                    if (deletepicture($this->user->info['user_picture']))
                    {
                        $this->user->change(array('user_picture' => 0));
                        exit(json_encode(array('status' => 'ok')));
                    }
                    else
                        exit(json_encode(array('status' => 'ok')));
                        //exit(json_encode(array('status' => 'error')));
                    
                    break;
                
                // Загрузка фотографии
                case 'saveUserPhoto':
                
                    $uploadPath = IMAGEUPLOAD . '/userphotos/' . date("Y/m/d/");
                
                    $result = catchFileNew('userpicture', $uploadPath, time(), 'png,gif,jpg,jpeg,jpe');
                    
                    if ($result['status'] == 'ok')
                    {
                        createThumbNew(ROOTDIR . $result['path'], '', 220, 0, 100, 1);
                        
                        // Сохранение информации о картинке в таблицу user_pictures
                        App::db()->query("INSERT INTO `user_pictures` (`user_id`, `picture_id`) VALUES ('" . $this->user->id . "', '".$result['id']."')");
            
                        if (!isset($_POST['next'])) {
                            $this->view->setVar('uploadSuccess');
                        } else {
                            if ($_POST['next'] == 'profile') header('location: /profile/');
                        }
                    }
                    else
                    {
                        $this->view->setVar("uploadError", array('text' => $result['message']));
                    }
                   break;
                
                // удалить фотографию
                case 'deleteUserPhoto':
                    
                    $r = App::db()->prepare("SELECT `picture_id` FROM `user_pictures` WHERE `user_picture_id` = :pid AND `user_id` = :uid LIMIT 1");
                    
                    $r->execute(array('pid' => intval($this->page->reqUrl[2]), 'uid' => $this->user->id));
                    
                    if ($pic = $r->fetch())
                    {
                        deletepicture($pic[0]);
                    }
                    
                    header('location:/profile/');
                    exit();
                    
                   break;
                
                /**
                 * Сохранить интервал отравки авторам отчёта о продажах
                 */
                case 'saleReport':
                
                    if ($this->page->reqUrl[2] != 'saved') {
                        if (in_array($this->page->reqUrl[2], array('everyday', 'everyweek', 'everymonth', 'no'))) {
                            if ($this->page->reqUrl[2] != $this->user->meta->saleReport) {
                                $this->user->setMeta('saleReport', $this->page->reqUrl[2]);
                            }
                        }
            
                        if (!$this->page->isAjax)
                            $this->page->go('/editprofile/saleReport/saved/#saleReport');
                        
                        exit();
                    }
                    
                    //$this->page->tpl = 'editprofile/saleReport.tpl';
                
                    break;  
               
                case '':
                
                    break;
                    
                default:
                    
                   $this->page404();
                    
                   break;
            }
            
            
            $this->view->setVar('aboutMe', strip_tags($this->user->meta->about_text));
            $this->view->setVar('whoami', $this->user->meta->whoami);
            
            $this->user->birthday  = explode('-', ($this->user->user_birthday != '0000-00-00' ? date('Y-n-j', strtotime($this->user->user_birthday)) : $this->user->user_birthday));    
            $this->user->user_lj   = stripslashes($this->user->user_lj);
            $this->user->user_city = cityId2name($this->user->user_city);
            
            $this->view->setVar('currentuser', objectToArray($this->user->info));
            
            // NEW BIRTHDAY DATA
            $this->view->setVar('bdays', range(1, 31));
            $this->view->setVar('bmonths', range(1, 12));
            $this->view->setVar('byears', range(1950, date('Y') - 10));
            
            
            // ФОРМА ДЛЯ СМЕНИЫ ЛОГИНА
            preg_match("/^user[0-9]{5,}/", $this->user->user_login, $matches);
            if ($matches[0]) {
                $this->view->setVar('CHANGELOGIN', TRUE);
            }
            
            preg_match("/\*\*\*\*/", $this->user->user_login, $matches);
            if ($matches[0]) {
                $this->view->setVar('CHANGELOGIN', TRUE);
            }
            
            preg_match("/^vk-[0-9]*/", $this->user->user_login, $matches);
            if ($matches[0]) {
                $vk = $this->user->meta->user_vk;
                if (!empty($vk))
                    $this->view->setVar('CHANGELOGIN', TRUE);
            }
            
            preg_match("/^fb-[0-9]*/", $this->user->user_login, $matches);
            if ($matches[0]) {
                $fb = $this->user->meta->user_facebook; 
                if (!empty($fb))
                    $this->view->setVar('CHANGELOGIN', TRUE);
            }    
            
            
            // SUBSCRIBE
            $sth = App::db()->query("SELECT `mail_list_id` FROM `mail_list_subscribers` WHERE `user_id` = '" . $this->user->id . "' AND `mail_list_id` > '0'");
            $mySubscribe = $sth->fetchAll();
            foreach (mail::$mailLists as $k => $v) {
                if (is_array($v)) {
                    unset(mail::$mailLists[$k]);
                    continue;
                }
                $mL[$k]['name'] = $v;
            }
            foreach ($mySubscribe AS $id) {
                if ($mL[$id['mail_list_id']])
                $mL[$id['mail_list_id']]['checked'] = (in_array($id['mail_list_id'], array_keys(mail::$mailLists))) ? TRUE : FALSE;
            }
            $this->view->setVar('newsLetters', $mL);
            
            // WATCHES
            $sth = App::db()->query("SELECT COUNT(*) AS `sum` FROM `user_watches` WHERE `user_id` = '". $this->user->id . "'");
            $row = $sth->fetch();
            if ($row['sum'] > 0) 
                $watches['comments'] = TRUE;
            
            $sth = App::db()->query("SELECT * FROM `messages_unwatch` WHERE `user_id` = '" . $this->user->id . "'");
            if ($sth->rowCount() == 0) 
                $watches['messages'] = TRUE;
            
            $this->view->setVar('watches', $watches);
            
            $this->view->setVar('avatar', userId2userGoodAvatar($this->user->id, 100, '', '', true));
            
            if (!empty($this->user->meta->phone_temp))
            {
                $phone_temp = json_decode($this->user->meta->phone_temp, 1);
                
                $this->view->setVar('CHANGE_PHONE', $phone_temp);
            }
            
            
            /**
             * соц. сети
             */
            if (!empty($this->user->meta->user_facebook))
            {
                $this->view->setVar('user_facebook', TRUE);
            } 
            
            if (!empty($this->user->meta->user_vk))
            {
                $this->view->setVar('user_vk', TRUE);
            }
            
            $this->view->generate('index.tpl');
        }
    }