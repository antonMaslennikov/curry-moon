<?
    
    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
    use \application\models\fb AS fb;
    use \application\models\vk AS vk;
    use \application\models\gplus AS gplus;
    use \application\models\instagram AS instagram;
    
    use \Exception;
        
    class Controller_Login extends \smashEngine\core\Controller
    {
        public function action_logout()
        {
            App::memcache()->delete('user' . $this->user->id);
    
            $this->user->setSessionValue(['user_id' => -1, 'session_logged' => 0]);
            
            unset($_SESSION['fb_access_token']);
            unset($_SESSION['vk_access_token']);
            unset($_SESSION['instagram_access_token']);
            unset($_SESSION['facebook_access_token']);
            
            $this->page->go('/');
        }
        
        public function action_login_mobile()
        {
            $this->page->tpl = 'login/login.mobile.tpl';
            $this->view->setVar('HTTP_REFERER', '/' . $this->page->module . '/');
            $this->view->generate('index.tpl');
        }
        
        public function action_quick()
        {
            if ($_GET['user_id'])
            {
                if (strpos($_SERVER['HTTP_FROM'], 'googlebot') === false)
                {
                    $uid  = intval($_GET['user_id']);
                    $user = App::db()->query("SELECT `user_email`, `user_login`, `user_status` FROM `users` WHERE `user_id` = '{$uid}' ORDER BY `user_last_login` DESC LIMIT 1")->fetch();
            
                    // переход по ссылке из письма
                    if ($user) 
                    {
                        if ($_GET['code'])
                        {
                            if ($user['user_status'] == 'active')
                            {
                                $r = App::db()->prepare("SELECT `id` FROM `user_quick_login` WHERE `hash` = :hash AND `user_id` = :user AND `time_use` = '0000-00-00 00:00:00' AND `time` >= NOW() - INTERVAL 2 DAY");
                    
                                $r->execute(['hash' => $_GET['code'], 'user'  => $uid]);
                    
                                if ($hash = $r->fetch())
                                {
                                    App::db()->query("UPDATE `user_quick_login` SET `time_use` = NOW() WHERE `id` = '" . $hash['id'] . "' LIMIT 1");
                                } 
                                else {
                                    $this->page->setFlashMessage('К сожалению данная ссылка для входа устарела');
                                }
                            } else {
                                $this->page->setFlashMessage('Ваш аккаунт не активирован. Вы не можете войти под ним');
                            }
                        } else {
                            $this->page->setFlashMessage('Не указан проверочный код');
                        }
                    } else {
                        $this->page->setFlashMessage('Такой пользователь не найден');
                    }
                    
                    if (!empty($uid) && $hash)
                    {
                        // логиним пользователя
                        $this->user->id = $uid;
                        $this->user->authorize();
                            
                        header('location: ' . (($_GET['next']) ? $_GET['next'] : '/'));
                        exit('yes');
                    } else {
                        if ($this->user->authorized)
                            header('location: ' . (($_GET['next']) ? $_GET['next'] : '/'));
                        else
                            header('location: /login/');
                        
                        exit('no');
                    }
                } else {
                    exit();
                }
            }
        
            $this->view->generate('login/quick2.tpl');
            exit();
        }

        public function action_quick2()
        {
            if ($_GET['user_id'])
            {
                $uid  = intval($_GET['user_id']);
                $user = App::db()->query("SELECT `user_email`, `user_login` FROM `users` WHERE `user_id` = '$uid' LIMIT 1")->fetch();
                
                // переход по ссылке из письма
                if ($_GET['code']) 
                {
                    $r = App::db()->query("SELECT `id` FROM `user_quick_login` WHERE `hash` = '" . addslashes($_GET['code']) . "' AND `user_id` = '$uid' AND `time_use` = '0000-00-00 00:00:00'");
                    $confirm = $r->rowCount();
        
                    if ($confirm)
                    {
                        $foo = $r->fetch();
                        $hashid = $foo['id'];
                        App::db()->query("UPDATE `user_quick_login` SET `time_use` = NOW() WHERE `id` = '$hashid' LIMIT 1");
                    }
                }
                
                if (!empty($uid) && $confirm)
                {
                    // логиним пользователя
                    $this->user->id = $uid;
                    $this->user->authorize();
                } else {
                }
                
                $this->page->go(($_GET['next']) ? $_GET['next'] : '/');
            }
        
        
            $this->view->generate('login/quick2.tpl');
            exit();
        }

        public function action_fb()
        {
            $fb = new fb(getVariableValue('FB_APP_ID'), getVariableValue('FB_SEC_KEY'));
                
            if ($_GET['code'])
            {
                if ($_REQUEST['state'] == $_SESSION['state']) 
                {
                    $fb->setCode(trim($_GET['code']));
                    
                    if (!$fb->getToken())
                    {
                        $this->view->setVar('ERROR', array('text' => 'Произошла ошибка авторизации (' . $fb->error->error_description . '). Обратитесь к администрации сайта'));
                    }
                    else
                    {
                        $_SESSION['facebook_access_token'] = $fb->token;
                        
                        $fb->getProfile();
                        
                        $sth = App::db()->prepare("SELECT `user_id` FROM `users_meta` WHERE `meta_name` = 'user_facebook' AND `meta_value` = :id LIMIT 1");
                        $sth->execute(array('id' => $fb->profile->id));
                        $row = $sth->fetch(); 
                        $uid = $row['user_id'];
                        
                        // аккаунт ещё ни за кем не закреплён
                        if (empty($uid))
                        {
                            // пользватель не авторизован
                            if (!$this->user->authorized)
                            {
                                $sth = App::db()->prepare("SELECT `user_id` FROM `users` WHERE `user_email` = :email AND `user_status` = 'active' AND `user_is_fake` = 'false' AND `user_activation` = 'done' LIMIT 1");
                                $sth->execute(array('email' => $fb->profile->email));
                                $row = $sth->fetch(); 
                                $this->user->id = (int) $row['user_id'];
                                
                                // пользователь с таким мылом найден у нас в базе
                                if (empty($fb->profile->email) || empty($this->user->id))
                                {
                                    $this->user->user_login        = 'fb-' . $fb->profile->id;
                                    $this->user->user_password_md5 = md5(SALT . $fb->profile->id);
                                    
                                    $this->user->user_name   = $fb->profile->name;
                                    $this->user->user_email  = $fb->profile->email;
                                    $this->user->user_sex    = $fb->profile->gender;
                                    $this->user->user_status = 'active';
                                    
                                    $this->user->user_birthday   = date('Y-m-d', strtotime($fb->profile->birthday));
                                    $this->user->user_activation = 'done';
                                    
                                    $this->user->save();
                                }
                            }
                            
                            $this->user->setMeta('user_facebook', $fb->profile->id);
                        }
                        else
                            $this->user->id = $uid;
                        
                        try
                        {
                            $this->user->authorize();
                            
                            exit("<!DOCTYPE html><html><head></head><body onbeforeunload='refreshAndClose();'><script>function refreshAndClose() { window.opener.location.reload(true); window.close(); } window.opener.location.reload(true); window.close()</script></body></html>");
                        }
                        catch (Exception $e)
                        {
                            $this->view->setVar('ERROR', array('text' => 'Произошла ошибка авторизации. ' . $e->getMessage()));
                        }
                    }
                }
                else 
                    $this->view->setVar('ERROR', array('text' => 'Произошла ошибка авторизации. Обратитесь к администрации сайта'));
            }
            else
            {
                exit($fb->loginDialog());
            }
        }
        
        public function action_gplus()
        {
            $gplus = new gplus(getVariableValue('GPLUS_APP_ID'), getVariableValue('GPLUS_SEC_KEY'));
                
            
            if ($_GET['code'])
            {
                $gplus->setCode(trim($_GET['code']));
                
                if (!$gplus->getToken())
                {
                    $this->view->setVar('ERROR', array('text' => 'Произошла ошибка авторизации (' . $gplus->error->error_description . '). Обратитесь к администрации сайта'));
                }
                else
                {
                    $_SESSION['access_token'] = $gplus->token;
                    
                    $gplus->getProfile();
                    
                    $sth = App::db()->prepare("SELECT `user_id` FROM `users_meta` WHERE `meta_name` = 'user_gplus' AND `meta_value` = :id LIMIT 1");
                    $sth->execute(array('id' => $gplus->profile->id));
                    $row = $sth->fetch(); 
                    $uid = (int) $row['user_id'];
                    
                    //echo("um: $uid");
                    
                    // аккаунт ещё ни за кем не закреплён
                    if (empty($uid))
                    {
                        if (!$this->user->authorized)
                        {
                            $sth = App::db()->prepare("SELECT `user_id` FROM `users` WHERE `user_email` = :email AND `user_status` = 'active' AND `user_is_fake` = 'false' AND `user_activation` = 'done' LIMIT 1");
                            $sth->execute(array('email' => $gplus->profile->emails[0]->value));
                            $row = $sth->fetch(); 
                            $this->user->id = (int) $row['user_id'];
                                
                            // пользователь с таким мылом найден у нас в базе
                            if (empty($gplus->profile->emails[0]->value) || empty($this->user->id))
                            {
                                $this->user->user_login        = 'gplus-' . $gplus->profile->id;
                                $this->user->user_password_md5 = md5(SALT . $gplus->profile->id);
                                
                                $this->user->user_name  = $gplus->profile->displayName;
                                $this->user->user_email = $gplus->profile->emails[0]->value;
                                $this->user->user_sex   = $gplus->profile->gender;
                                
                                $this->user->user_activation = 'done';
                                
                                $this->user->save();
                            }
                        }
                        
                        $this->user->setMeta('user_gplus', $gplus->profile->id);
                    }
                    else
                        $this->user->id = $uid;
                    
                    try
                    {
                        $this->user->authorize();
                        
                        exit("<!DOCTYPE html><html><head></head><body onbeforeunload='refreshAndClose();'><script>function refreshAndClose() { window.opener.location.reload(true); window.close(); } window.opener.location.reload(true); window.close()</script></body></html>");
                    }
                    catch (Exception $e)
                    {
                        $this->view->setVar('ERROR', array('text' => 'Произошла ошибка авторизации. ' . $e->getMessage()));
                    }
                }
        
                //  $this->view->setVar('ERROR', array('text' => 'Произошла ошибка авторизации. Обратитесь к администрации сайта'));
            }
            else
            {
                exit($gplus->loginDialog());
            }
        }
        
        public function action_vk()
        {
            $vk = new vk(getVariableValue('VK_APP_ID'), getVariableValue('VK_SEC_KEY')); 
            
            if ($_GET['code'])
            {
                $vk->setCode($_GET['code']);
                
                if (!$vk->getToken())
                {
                    $this->view->setVar('ERROR', array('text' => 'Произошла ошибка авторизации (' . $vk->error->error_description . '). Обратитесь к администрации сайта'));
                }
                else
                {
                    $_SESSION['vk_access_token'] = $vk->token;
                    
                    $vk->getProfile();
         
                    if (!empty($vk->profile[0]->uid))
                    {
                        $sth = App::db()->prepare("SELECT `user_id` FROM `users_meta` WHERE `meta_name` = 'user_vk' AND `meta_value` = :id LIMIT 1");
                        $sth->execute(array('id' => $vk->profile[0]->uid));
                        $row = $sth->fetch(); 
                        $uid = (int) $row['user_id'];
                            
                        // пользователь ещё не проходил авторизацию через данную соц.сеть
                        if (empty($uid))
                        {
                            // пользователь не атворизован
                            if (!$this->user->authorized)
                            {
                                // регистрируем новый аккаунт
                                $sex  = array(1 => 'female', 2 => 'male');
                                
                                $this->user->user_login        = 'vk-' . $vk->profile[0]->uid;
                                $this->user->user_password_md5 = md5(SALT . $vk->profile[0]->uid);
                                
                                $this->user->user_name  = $vk->profile[0]->last_name . " " . $vk->profile[0]->first_name;
                                $this->user->user_sex   = $sex[$vk->profile[0]->sex];
                                $this->user->user_phone = $vk->profile[0]->mobile_phone;
                                $this->user->user_city  = cityName2id($vk->profile[0]->city_name, $country, true);
                                
                                $this->user->user_birthday   = date('Y-m-d', strtotime($vk->profile[0]->bdate));
                                $this->user->user_status     = 'active';
                                $this->user->user_activation = 'done';
                                $this->user->user_is_fake    = 'true';
                                
                                try
                                {
                                    $this->user->save();
                                    $notauth = true;
                                }
                                catch (Exception $e) 
                                {
                                    printr($e->etMessage());
                                }
                            }
                            
                            $this->user->setMeta('user_vk', $vk->profile[0]->uid);
                            
                            try
                            {
                                $this->user->authorize();
                                    
                                if ($notauth) {
                                    exit ("<!DOCTYPE html><html><head></head><body onbeforeunload='refreshAndClose();'><script>function refreshAndClose() { window.close(); window.opener.tb_show('', '/editprofile/socialGetMore/?width=490&height=500'); } window.close();</script></body></html>");
                                } else {
                                    exit ("<!DOCTYPE html><html><head></head><body onbeforeunload='refreshAndClose();'><script>function refreshAndClose() { window.opener.location.reload(true); window.close(); } window.opener.location.reload(true); window.close()</script></body></html>");
                                }
                            }
                            catch (Exception $e)
                            {
                                $this->view->setVar('ERROR', array('text' => 'Произошла ошибка авторизации. ' . $e->getMessage()));
                            }
                        }
                        else 
                        {
                            if (!$this->user->authorized)
                            {
                                $this->user->id = $uid;
                                
                                try
                                {
                                    $this->user->authorize();
                                
                                    exit ("<!DOCTYPE html><html><head></head><body onbeforeunload='refreshAndClose();'><script>function refreshAndClose() { window.close(); window.opener.location.reload(true); window.close(); } window.opener.location.reload(true); window.close(); refreshAndClose();</script></body></html>");
                                }
                                catch (Exception $e)
                                {
                                    $this->view->setVar('ERROR', array('text' => 'Произошла ошибка авторизации. ' . $e->getMessage()));
                                }
                            }
                            else 
                            {
                                if ($uid != $this->user->id)
                                    $this->view->setVar('ERROR', array('text' => 'Произошла ошибка. Данный аккаунт уже привязан к другому нашему пользователю.'));
                                else
                                    exit ("<!DOCTYPE html><html><head></head><body onbeforeunload='refreshAndClose();'><script>function refreshAndClose() { window.close(); window.opener.location.reload(true); window.close(); } window.opener.location.reload(true); window.close(); refreshAndClose()</script></body></html>");
                            }
                        }
                    } else {
                        $this->view->setVar('ERROR', array('text' => 'Произошла ошибка. Такой аккаунт не существует.'));
                        exit('Произошла ошибка. Такой аккаунт не существует.');
                    }
                }
            }
            elseif ($_GET['error']) 
            {
                exit($_GET['error_description']);
            } else {
                exit($vk->loginDialog());
            }
        }
        
        public function action_instagram()
        {
            $instagram = new instagram(getVariableValue('INSTAGRAM_APP_ID'), getVariableValue('INSTAGRAM_SEC_KEY'));
                
            if ($_GET['code'])
            {
                $instagram->setCode(trim($_GET['code']));
                
                try
                {
                    $token = $instagram->getToken();
                }
                catch (Exception $e)
                {
                    printr($e, 1);
                }
                
                if (!$token)
                {
                    $this->view->setVar('ERROR', array('text' => 'Произошла ошибка авторизации (' . $instagram->error->error_description . '). Обратитесь к администрации сайта'));
                }
                else
                {
                    $_SESSION['instagram_access_token'] = $instagram->token;
                    
                    try
                    {
                        $instagram->getProfile();
                        //$instagram->getMedia();
                    }
                    catch (Exception $e)
                    {
                        printr($e, 1);
                        $this->view->setVar('ERROR', array('text' => $instagram->error->error_description));
                    }
                    
                    $sth = App::db()->prepare("SELECT `user_id` FROM `users_meta` WHERE `meta_name` = 'user_instagram' AND `meta_value` = :id LIMIT 1");
                    $sth->execute(array('id' => $instagram->profile->data->id));
                    $row = $sth->fetch(); 
                    $uid = (int) $row['user_id'];
                    
                    //exit("um:" . $instagram->profile->data->id . " - " . $uid);
                    
                    // аккаунт ещё ни за кем не закреплён
                    if (empty($uid))
                    {
                        /*
                        if (!$this->user->authorized)
                        {
                            $sth = App::db()->prepare("SELECT `user_id` FROM `users` WHERE `user_email` = :email AND `user_status` = 'active' AND `user_is_fake` = 'false' AND `user_activation` = 'done' LIMIT 1");
                            $sth->execute(array('email' => $instagram->profile->emails[0]->value));
                            $row = $sth->fetch(); 
                            $this->user->id = (int) $row['user_id'];
                         
                            // пользователь с таким мылом найден у нас в базе
                            if (empty($instagram->profile->emails[0]->value) || empty($this->user->id))
                            {
                                $this->user->user_login        = 'instagram-' . $instagram->profile->id;
                                $this->user->user_password_md5 = md5(SALT . $instagram->profile->id);
                                
                                $this->user->user_name  = $instagram->profile->displayName;
                                $this->user->user_email = $instagram->profile->emails[0]->value;
                                $this->user->user_sex   = $instagram->profile->gender;
                                
                                $this->user->user_activation = 'done';
                                
                                $this->user->save();
                            }
                        }
                        */
                        
                        $this->user->setMeta('user_instagram', $instagram->profile->data->id);
                        $this->user->setMeta('user_instagram_name', $instagram->profile->data->username);
                    }
                    else
                    {
                        if ($this->user->id != $uid)
                            $this->view->setVar('ERROR', array('text' => 'Произошла ошибка. Данный аккаунт уже привязан к другому нашему пользователю.'));
                    }
                    
                    //$this->user->authorize();
                    
                    exit("<!DOCTYPE html><html><head></head><body onbeforeunload='refreshAndClose();'><script>function refreshAndClose() { if(window.parent !== undefined && typeof window.opener.InstagramCallback === 'function') { window.opener.InstagramCallback(); }} window.close(); </script></body></html>");
                }
        
                //  $this->view->setVar('ERROR', array('text' => 'Произошла ошибка авторизации. Обратитесь к администрации сайта'));
            }
            else
            {
                exit($instagram->loginDialog());
            }
        }
        
        public function action_index()
        {
            $this->page->tpl = 'login/login.tpl';
            $this->page->footer_tpl = 'order/footer.tpl';
            
            $this->view->setVar('HTTP_REFERER', (($_GET['next']) ? urldecode($_GET['next']) : $_SERVER['HTTP_REFERER']));
            
            // =====================================================================================================================
            // обработка ошибки авторизации
            // =====================================================================================================================
            if ($_GET['loginerror'])
            {
                if ($_GET['login'])
                    $this->view->setVar('login', urldecode($_GET['login']));
            
                $this->view->setVar('ERROR', array(
                    'num' => intval($_GET['loginerror']),
                ));
            }
            
            if (!$_POST['submit'])
            {
                if ($this->user->authorized) {
                    $this->page->go('/');
                }
                
                // запоминаем откуда пользователь пришёл на страницу авторизации
                if ($_GET['ref'])
                    $this->view->setVar('HTTP_REFERER', urldecode($_GET['ref']));
                
                if ($_GET['next'])
                    $this->view->setVar('HTTP_REFERER', urldecode($_GET['next']));
                
                $this->view->setVar('loginForm', TRUE);
                $this->view->setVar('error', array('ERROR' => $this->page->getFlashMessage()));
            }
            else
            {
                $_POST['HTTP_REFERER'] = str_replace('http://www.maryjane.ru', '', $_POST['HTTP_REFERER']);
                
                if (!empty($_POST['HTTP_REFERER']) && $_POST['HTTP_REFERER'] != 'http://www.maryjane.ru/login' && $_POST['HTTP_REFERER'] != 'http://www.maryjane.ru/login/' && $_POST['HTTP_REFERER'] != '/login.mobile/') {
                    $ref = substr($_POST['HTTP_REFERER'], 0, ((strpos($_POST['HTTP_REFERER'], '?')) === false ) ? strlen($_POST['HTTP_REFERER']) : strpos($_POST['HTTP_REFERER'], '?'));
                } else {
                    $ref = '/';
                }
                
                if ($this->user->client->ismobiledevice == 1) {
                    $error_ref = '/login';
                } else {
                    $error_ref = ($_POST['HTTP_REFERER'] == '/') ? '' : '/' . trim($_POST['HTTP_REFERER'], '/');
                }
            
                if (!empty($_POST['login']))
                {
                    if (!empty($_POST['password']))
                    {
                        $query = parse_url($error_ref);
                        parse_str($query['query'], $arr);
                                
                        $pphone = addslashes(str_replace(array(' ', '(', ')', '-', '+'), '', $_POST['login']));
                        
                        if (strpos($pphone, '8') === 0)
                            $pphone = '7' . substr($pphone, 1);
                        
                        $sth = App::db()->prepare("SELECT `user_id`, `user_login`, `user_status`, `user_email`, `user_phone`, `user_activation`
                                          FROM `users` 
                                          WHERE 
                                                (`user_login` = :login OR `user_email` = :login OR `user_phone` = :login) 
                                            AND `user_password_md5` = :password
                                            AND `user_status` != 'deleted' 
                                          LIMIT 1");
                                          
                        $sth->execute(array(
                            'login' => $_POST['login'],
                            'password' => md5(SALT . $_POST['password']),
                        ));
                        
                        if (!$row = $sth->fetch())
                        {
                            $arr['loginerror'] = 1; 
                            $arr['login'] = urlencode($_POST['login']);
                            $arr['next'] = $ref;
                            
                            header('location: /login/?' . http_build_query($arr));
                            exit();
                        } 
                        else 
                        {
                            /**
                             * пользователь забанен
                             */
                            if ($row['user_status'] == 'banned')
                            {
                                $arr['loginerror'] = 2; 
                                $arr['login'] = urlencode($_POST['login']);
                                $arr['next'] = $ref;
                                
                                header('location: /login/?' . http_build_query($arr));
                                exit();
                            }
                            else
                            {
                                /**
                                 *  
                                 * Если активация профиля ещё не выполнена
                                 * пользователю отправляется письмо со ссылкой на активацию 
                                 */
                                if (!empty($row['user_email']) && empty($row['user_phone']) && $row['user_activation'] != 'done')
                                {
                                    $this->view->setVar('loginNeedActivation', TRUE);
                                    $this->view->setVar('email', $row['user_email']);
                                    
                                    $code   = md5($row['user_id']);
                                    $userid = $row['user_id'];
                                    $email  = $row['user_email'];
                                    
                                    App::mail()->send(array($row['user_id']), 2, array(
                                        'activateLink' => 'http://www.maryjane.ru/registration/activate/?userid=' . $userid . '&key=' . $code,
                                        'activateCode' => $code,
                                        'code' => $code,
                                        'mail' => $row['user_email'],
                                        'user_id' => $row['user_id'],
                                        'user_login' => $row['user_login'],
                                    ));
                                    
                                    $this->page->tpl = 'message.tpl';
                                    $this->view->setVar('MESSAGE', array('text' => 'Ваш аккаунт ещё не активирован.<br />На указанный при регистрации почтовый ящик отправлено письмо с инструкцией по активации.'));
                                }
                                else
                                {
                                    $this->user->id = $row['user_id'];
                                    
                                    try
                                    {
                                        $this->user->authorize();
                                    }
                                    catch (Exception $e)
                                    {
                                        $this->view->setVar('ERROR', array('text' => 'Произошла ошибка авторизации. ' . $e->getMessage()));
                                    }
                                    
                                    if ($_POST['notMyComputer'] == 'true') 
                                        $this->user->setSessionValue(['session_short' => '1']);
            
                                    header("Location: " . $ref);
                                    exit();
                                }
                            }
                        }
                    } else {
                        $this->view->setVar('ERROR', array('text' => 'Пароль не можен быть пустым.'));
                    }
                }
                else 
                {
                    header("Location: /login/"); exit();
                }
            }

            $this->view->generate('index.tpl');
        }
    }
        