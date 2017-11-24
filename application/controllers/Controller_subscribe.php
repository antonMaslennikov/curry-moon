<?
    
    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
    use \Exception;
        
    class Controller_subscribe extends \smashEngine\core\Controller
    {
        /**
         * Подписаться на рассылку 
         * "акции"
         */
        public function action_subscribe()
        {
            $this->page->tpl = 'message.tpl';
            $this->page->noindex = TRUE;
            $this->page->title = 'Подписка подтверждена';
            
            if (!empty($this->page->reqUrl[1]) && !empty($this->page->reqUrl[2]))
            {
                $user = trim($this->page->reqUrl[1]);
                $code = trim($this->page->reqUrl[2]);
                $ml   = intval($this->page->reqUrl[3]);
            
                if (\application\models\mail::$mailLists[abs($ml)])
                {
                    $sth = App::db()->prepare("SELECT `user_email`, `user_register_date` FROM `users` WHERE `user_id` = :user LIMIT 1");
                    $sth->execute(array('user' => $user));
                    $row = $sth->fetch();
                    
                    if ($code == md5($user . $row['user_email'] . $row['user_register_date']))
                    {
                        // Ищем всех пользователей с таким же email как и у отписываемого
                        $sth = App::db()->query("SELECT u.`user_id`, ml.`mail_list_id` AS subscribe
                                    FROM `users` u 
                                        LEFT JOIN `mail_list_subscribers` ml ON ml.`user_id` = u.`user_id` AND ABS(`mail_list_id`) = ABS('".$ml."')
                                    WHERE 
                                        u.`user_email` = '" . $row['user_email'] . "'");
                                        
                        foreach ($sth->fetchAll() AS $u) {
                            
                            if ($ml > 0) 
                            {
                                App::db()->query("UPDATE `users` SET `user_subscription_status` = 'active' WHERE `user_id` = '" . $u['user_id'] . "' LIMIT 1");
                            }
                            
                            if ($ml != $u['subscribe']) {
                                if (empty($u['subscribe'])) {
                                    App::db()->query("INSERT INTO 
                                                    `mail_list_subscribers` 
                                                SET 
                                                    `user_id` = '" . $u['user_id'] . "', 
                                                    `mail_list_id` = '{$ml}'");
                                } else {
                                    App::db()->query("UPDATE `mail_list_subscribers` SET `mail_list_id` = '" . $ml . "' WHERE `user_id` = '" . $u['user_id'] . "' AND ABS(`mail_list_id`) = ABS('{$ml}') LIMIT 1");
                                }
                            }
                        }
                
                        if (!$this->page->isAjax) {
                            if ($ml > 0)
                                $this->view->setVar('MESSAGE', array('text' => 'Ваша подписка на новостные уведомления подтверждена.<br />Вы всегда сможете отменить её в редактировании своего профиля <a href="http://www.maryjane.ru/editprofile/">http://www.maryjane.ru/editprofile/</a>'));
                            else
                                $this->view->setVar('MESSAGE', array('text' => 'Вы отписаны от данных уведомлений.<br />Вы всегда сможете восстановить её в редактировании своего профиля <a href="http://www.maryjane.ru/editprofile/">http://www.maryjane.ru/editprofile/</a>'));
                        } else {
                            if ($ml > 0)
                                $out = array('status' => 'ok', 'added' => true);
                            else
                                $out = array('status' => 'ok', 'removed' => true);
                        }
                    }
                    else
                    {
                        if (!$this->page->isAjax) {
                            $this->view->setVar('MESSAGE', array('text' => 'Код подтверждения указан неверно. <a href="#TB_inline?height=530&width=300&inlineId=feedbackcenter" class="thickbox">Свяжитесь с администрацией</a>, если вы считаете что произошла ошибка.'));
                        } else {
                            $out = array('status' => 'error', 'message' => 'Код подтверждения указан неверно');
                        }
                    }
                }
                else 
                {
                    if (!$this->page->isAjax) {
                        $this->view->setVar('MESSAGE', array('text' => 'Данный вид подписки не существует'));
                    } else {
                        $out = array('status' => 'error', 'message' => 'Данный вид подписки не существует');
                    }
                }
            }
            else
            {
                if (!$this->page->isAjax) {
                    $this->view->setVar('MESSAGE', array('text' => 'Недостаточно данных для оформления подписки'));
                } else {
                    $out = array('status' => 'error', 'message' => 'Недостаточно данных для оформления подписки');
                }
            }
            
            if ($this->page->isAjax) {
                exit(json_encode((array) $out));
            } else {
                $this->view->generate('index.tpl');
            }
        }
        
        /**
         * отмена подписки
         */
        public function action_unsubscribe()
        {
            $this->page->tpl = 'message.tpl';
    
            if (!empty($this->page->reqUrl[1]) && !empty($this->page->reqUrl[2]))
            {
                $user = trim($this->page->reqUrl[1]);
                $code = trim($this->page->reqUrl[2]);
                
                $sth = App::db()->prepare("SELECT `user_email`, `user_register_date` FROM `users` WHERE `user_id` = :user LIMIT 1");
                $sth->execute(array('user' => $user));
                $row = $sth->fetch();
                
                if ($code == md5($user . $row['user_email'] . $row['user_register_date']))
                {
                    // Ищем всех пользователей с таким же email как и у отписываемого
                    $sth = App::db()->prepare("SELECT `user_id` FROM `users` WHERE `user_email` = :email");
                    $sth->execute(array('email' => $row['user_email']));
                    $users = $sth->fetchAll();
                
                    foreach ($users AS $u) 
                    {
                        $U = new \application\models\User($u['user_id']);
                        
                        switch ($this->page->reqUrl[3]) 
                        {
                            /**
                             * отписака от каментов к работе (на голосовании / в каталоге)
                             */
                            case 'good':
        
                                if (!empty($this->page->reqUrl[4]))
                                {
                                    $id = intval($this->page->reqUrl[4]);
                                    $U->unwatch($id, 'good');
                                    $this->view->setVar('MESSAGE', array('text' => '<span style="color:red">Вы отписаны от уведомлений о комментариях к работе "' . goodId2goodName($id) .'"</span>.'));
                                }
                                else 
                                    $this->view->setVar('MESSAGE', array('text' => '<span style="color:red">Такой работы не найдено</span>.'));
                                    
                                break;
                            
                            /**
                             * отписака от каментов к посту
                             */
                            case 'blog':
                            
                                if (!empty($this->page->reqUrl[4]))
                                {
                                    $id = intval($this->page->reqUrl[4]);
                                    $U->unwatch($id, 'blog');
                                    $this->view->setVar('MESSAGE', array('text' => '<span style="color:red">Вы отписаны от уведомлений о комментариях к посту "' . postId2title($id) .'"</span>.'));
                                }
                                else 
                                    $this->view->setVar('MESSAGE', array('text' => '<span style="color:red">Такой поста не найдено</span>.'));
                            
                                break;  
                            
                            /**
                             * 2, selected: Избранный автор добавил новую работу
                             */
                            case 2:
                            case 'selected':
                                
                                $U->unsubscribe($this->page->reqUrl[3] == 'selected' ? 2 : $this->page->reqUrl[3]);
                                
                                $this->view->setVar('MESSAGE', array('text' => '<span style="color:red">Вы отписаны от уведомлений о новых работах избранных авторов</span>.'));
                                
                                break;
                                
                            /**
                             * 10: Работа избранного победила на 2х недельном конкурсе
                             * 4: Избранный добавил пост в блог
                             * 2, selected: Избранный автор добавил новую работу
                             */
                            case 10:
                            case 4: 
                                
                                $U->unsubscribe($this->page->reqUrl[3] == 'selected' ? 2 : $this->page->reqUrl[3]);
                                
                                $this->view->setVar('MESSAGE', array('text' => '<span style="color:red">Вы отписаны от данных уведомлений</span>.'));
                                
                                break;
                            
                            /**
                             * полная отписка от всего на свете
                             */
                            default:
                                
                                $U->change(array('user_subscription_status' => 'canceled'));
                                $U->unwatch();
                                
                                App::db()->query("DELETE FROM `mail_list_subscribers` WHERE `user_id` = '" . $u['user_id'] . "'");
                                
                                $this->view->setVar('MESSAGE', array('text' => '<span style="color:red">Вы отписаны от всех рассылок</span>.<br />Вы всегда сможете восстановить её в редактировании своего профиля <a href="http://www.maryjane.ru/editprofile/">http://www.maryjane.ru/editprofile/</a>'));
                                
                                break;
                        }
                        
                    }
        
                }
                else
                {
                    $this->view->setVar('MESSAGE', array('text' => 'Код подтверждения указан неверно. <a href="#TB_inline?height=530&width=300&inlineId=feedbackcenter" class="thickbox">Свяжитесь с администрацией</a>, если вы считаете что произошла ошибка.'));
                }
            }
            else
            {
                $this->view->setVar('MESSAGE', array('text' => 'Код подтверждения не указан. <a href="#TB_inline?height=530&width=300&inlineId=feedbackcenter" class="thickbox">Свяжитесь с администрацией</a>, если вы считаете что произошла ошибка.'));
            }
            
            $this->view->generate('index.tpl');
        }
    }
        