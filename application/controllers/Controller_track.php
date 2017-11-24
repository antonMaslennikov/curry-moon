<?
    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
    use \Exception;
    
    /**
     * отслеживание открытия письма
     */ 
    class Controller_track extends \smashEngine\core\Controller
    {
        public function action_index()
        {
            if (!empty($this->page->reqUrl[1]) && !empty($this->page->reqUrl[2]))
            {
                if (md5($this->page->reqUrl[1] . SALT) == $this->page->reqUrl[2])
                {
                    try
                    {
                        $sth = App::db()->prepare(
                                    "SELECT 
                                        `mail_message_template_id`, `user_id`, `mail_message_email`
                                     FROM
                                        `mail_messages`
                                     WHERE 
                                        `mail_message_id` = :id AND `mail_message_viewed` = '0'
                                     LIMIT 1");
                        
                        $sth->execute(array(
                            'id' => $this->page->reqUrl[1],
                        ));
                        
                        if ($mes = $sth->fetch())
                        {
                            $sth = App::db()->prepare(
                                        "UPDATE
                                            `mail_messages`
                                         SET 
                                            `mail_message_viewed` = '1'
                                         WHERE 
                                            `mail_message_id` = :id
                                         LIMIT 1");
                            
                            $sth->execute(array(
                                'id' => $this->page->reqUrl[1],
                            ));
                            
                            if ($sth->rowCount() == 1)
                            {
                                try
                                {
                                    $mt = new \application\models\mailTemplate($mes['mail_message_template_id']);
        
                                    // плюсуем открытие шаблону
                                    $mt->mail_template_views++;
                                    $mt->save();
                                    
                                    // плюсуем открытие юзеру в мету если это шаблон рассылки
                                    if ($mes['raiting'] = 0 && $mes['user_id'] > 0)
                                    {
                                        $sth = App::db()->prepare(
                                                    "INSERT IGNORE INTO `users_meta` 
                                                        SET `meta_name` = 'maillist_viewed', `meta_value` = '1', `user_id` = :user
                                                     ON DUPLICATE KEY UPDATE 
                                                        `meta_value` =  `meta_value` + '1'");
                                                        
                                        $sth->execute(array(
                                            'user' => $mes['user_id'],
                                        ));
                                    }
                                    
                                    // если открыто письмо коммерческого предложения
                                    if ($mt->mail_template_order == 'compred') 
                                    {
                                        $sth = App::db()->prepare(
                                            "SELECT 
                                                `request_id`
                                             FROM
                                                `" . \application\models\dealersRequests::$metatable . "`
                                             WHERE 
                                                `meta_value` = :id AND `meta_name` = 'mail_message_id'
                                             LIMIT 1");
                                             
                                        $sth->execute(array(
                                            'id' => $this->page->reqUrl[1],
                                        ));
                                        
                                        if ($row = $sth->fetch())
                                        {
                                            $DR = new \application\models\dealersRequests($row['request_id']);
                                            $DR->offer_viewed = NOW;
                                            $DR->save();
                                        }
                                    }
                                }
                                catch (Exception $e)
                                {
                                    printr($e->getMessage());
                                }
                            }
                        }
                    }
                    catch (Exception $e)
                    {
                        printr($e->getMessage());
                    }
                }
            }
            
            $i = createImageFrom(ROOTDIR . '/images/0.gif');
            header('Content-type: image/gif');
            imagejpeg($i, NULL, 98);
            exit();
        }
    }