<?php
    
    namespace application\models;
    
    use \smashEngine\core\App AS App; 
    use \PDO;
    use \Exception;

    class comment extends \smashEngine\core\Model 
    {
        public $id   = 0;
        public $info = array();
        
        /**
         * @param string имя таблицы в БД для хранения экземпляров класса
         */
        protected static $dbtable = 'comments';
        
        
        
        function __construct($id)
        {
            $this->id = (int) $id;
            
            if (!empty($this->id))
            {
                $r = App::db()->query(sprintf("SELECT c.*
                          FROM `" . self::$dbtable . "` c
                          WHERE c.`comment_id` = '%d'
                          LIMIT 1", $this->id));
                          
                if ($r->rowCount() == 1) 
                {
                    $this->info = $r->fetch();

                    return $this->info;
                } 
                else 
                    throw new Exception ('comment ' . $this->id . ' not found');
            }
        }
        
        /**
         * Сохранить текущий экземпляр объекта в базу
         */
        public function save()
        {
            foreach ($this->info as $k => $v) {
                $rows[$k] = "`$k` = '" . addslashes($v) . "'";
            }
            
            // вырезаем все поля которых нет в схеме таблицы
            $r = App::db()->query(sprintf("SHOW COLUMNS FROM `%s`", self::$dbtable));
            
            foreach ($r->fetchAll() AS $f) {
                $fields[$f['Field']] = $f['Field'];
            }
            
            $rows = array_intersect_key($rows, $fields);
            // end вырезаем все поля которых нет в схеме таблицы
            
            
            // редактирование
            if (!empty($this->id))
            {
                App::db()->query(sprintf("UPDATE `%s` SET %s WHERE `comment_id` = '%d' LIMIT 1", self::$dbtable, implode(',', $rows), $this->id));
            }
            // создание
            else
            {
                App::db()->query(sprintf("INSERT INTO `%s` SET %s", self::$dbtable, implode(',', $rows)));
                $this->id = App::db()->lastInsertId();
            }
        }
        
        /**
         * Добавить новый комменарий
         * @param string текст сообщения
         * @param string к чему оставляем камент (id)
         * @param string к чему оставляем камент (тип работа/фотография/пост)
         * @param int автор
         */
        public function add($text, $to, $type, $parent = 0, $u) 
        {
            $User  = new User($u);
            
            $type = trim($type);
            $to = intval($to);
            $parent = intval($parent);
            
            if (empty($to))
            {
                throw new Exception('Недостаточно данных для добавления комментария', 1);
            }
            
            $text = str_replace("&nbsp;", ' ', $text);
            
            if (!in_array($User->meta->mjteam, array('super-admin'))) 
            {
                $text = strip_tags($text, "<a><b><i><u><em><p><strong><img><blockquote><cite><br>");
                
                if (strlen(strip_tags($text)) > 3000) 
                {
                    $text = substr($text, 0 , 3000);
                }
            }
            
            $visible = 1;
            
            if ($type == 'newgood') 
            {
                $visible   = 'hudsovet';
            }
            
            if (strlen($text) > 0)
            {
                if ($User->id != 6199 || $type != 'blog') 
                {
                    $link_chars = "a-zA-Z0-9АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя\%\.\_\.\/\-\#\:\?=\&";
                    $text = preg_replace("'<a[^>]*?>(.*?)</a>'si",'\\1', stripslashes(stripslashes($text))); // заменить ссылку в тегах - на ссылку без тегов
                    $text = preg_replace("'src=[\"]*(http://www.|https?://|http://|www.)([$link_chars]*).*?>'si",'img:\\2">', $text); // заменить ссылки в рисунках на псведо рисуночную ссылку
                    $text = preg_replace("#(https://|http://|www.)[$link_chars]*#",'<a href="\\0" rel="nofollow">\\0</a>',$text); // превращаем урлы в ХТМЛ ссылки
                    $text = preg_replace("'img:([$link_chars]*).*?>'si",'src="http://www.\\1">', $text); // заменить ссылки на картинки обратно на нормальные
                }
                
                $this->comment_text = $text;
                $this->user_id = $User->id;
                $this->object_id = $to;
                $this->object_type = $type;
                $this->comment_parent = $parent;
                $this->comment_ip = $_SERVER['REMOTE_ADDR'];
                $this->comment_visible = $visible;
            
                $this->save();
                
                /**
                 * РАССЫЛКА УВЕДОМЛЕНИЙ
                 */ 
                switch ($type)
                {
                    // худсовет
                    case 'newgood':
                        
                        $r = App::db()->query("SELECT `user_id` FROM `users_meta` WHERE `meta_name`  = 'hudsovet' AND `meta_value` = '1' AND `user_id` != '" . $User->id . "'");
                        
                        if ($r->rowCount() > 0)
                        {
                            foreach ($r->fetchAll() AS $v) { 
                                $userArray[] = $v['user_id'];
                            }
                            
                            $link = "http://www.maryjane.ru/hudsovet/" . ((!empty($type)) ? 'good/' . $to . '/#comment' . $this->id : '');
    
                            App::mail()->send($userArray, 201, array(
                                        'link' => "<a href=\"$link\">$link</a>"));
                        }
                        
                    break;
                    
                    case 'good':
                    
                        $G = new good($to);
                        
                        $G->change(array(
                            'comments' => $G->comments + 1,
                        ));
                        
                        $link = "http://www.maryjane.ru/voting/view/{$to}/";
        
                        $r = App::db()->query("SELECT `user_id` FROM `user_watches` WHERE `object_id` = '$to' AND `object_type` = '1' AND `user_id` != '" . $User->id . "' AND `user_notified` = 'false'");

                        if ($r->rowCount() > 0)
                        {
                            foreach ($r->fetchAll() AS $v) {
                                $userArray[] = $v['user_id'];
                            }
                            
                            App::mail()->send($userArray, 455, array(
                                'good_id'    => $to,
                                'link'       => $link,
                                'goodName'   => $G->good_name,
                                'comment'    => stripslashes($text),
                                'user_login' => $User->user_login, 
                                'user_avatar'=> user::userAvatar($User->id, 50, '', 1),
                                'date'       => datefromdb2textdate(date('Y-m-d H:i:s'), 1)
                            ));
                        }
                    break;
            
                    case 'gallery':
                    
                        $r = App::db()->query("UPDATE `gallery` SET `comments` = `comments` + 1 WHERE `gallery_picture_id` = '$to'");
                    
                        $r = App::db()->query("SELECT `gallery_picture_id`, `good_id` FROM `gallery` WHERE `gallery_picture_id`='$to' LIMIT 1");
                        
                        if ($r->rowCount() > 0)
                        {
                            $pic = $r->fetch();
                            
                            $r = App::db()->query("SELECT `user_id` FROM `user_watches` WHERE `object_id` = '$to' AND `object_type` = '2' AND `user_id` != '" . $User->id . "' AND `user_notified` = 'false'");
                            
                            if ($r->rowCount() > 0)
                            {
                                foreach ($r->fetchAll() AS $v) {
                                    $userArray[] = $v['user_id'];
                                }
            
                                $link = "http://www.maryjane.ru/gallery/view/$to/";
                                $reparray['goodName'] = goodId2goodName($pic['good_id']);
                                $reparray['link']     = $link;
                                App::mail()->send($userArray, 101, $reparray);
                            }
                        }
                    break;
            
                    case 'blog':
            
                        $r = App::db()->query("UPDATE `user_posts` SET `comments` = `comments` + 1 WHERE `id` = '$to'");
                    
                        $r = App::db()->query("SELECT `post_title`, `post_author`  FROM `user_posts` WHERE `id` = '$to' LIMIT 1");
                
                        if ($r->rowCount() > 0)
                        {
                            $post = $r->fetch();
                
                            // если это новый камент к топику
                            if (!$_POST['parent'])
                            {
                                // то оповещаем всех подписавшихся
                                $r = App::db()->query("SELECT `user_id` FROM `user_watches` WHERE `object_id` = '$to' AND `object_type` = '0' AND `user_id` != '" . $User->id . "' AND `user_notified` = 'false'");
                                $tpl = 454;
                            }
                            // если это ответ на какой-то камент
                            else
                            {
                                // то оповещаем только автора камента на который ответили
                                $r = App::db()->query("SELECT c.`user_id`, c.`comment_text` FROM `user_watches` uw, `comments` c WHERE uw.`object_id` = '$to' AND uw.`object_type` = '0' AND uw.`user_id` != '" . $User->id . "' AND uw.`user_notified` = 'false' AND uw.`user_id` = c.`user_id` AND c.`comment_id` = '" . intval($_POST['parent']) . "' LIMIT 1");
                                $tpl = 454;
                            }
                            
                            $reparray['link1'] = $link = "http://www.maryjane.ru/blog/view/$to/";
                            $reparray['newsSubject'] = htmlspecialchars_decode($post['post_title']);
                            
                            if ($r->rowCount() > 0)
                            {
                                foreach ($r->fetchAll() AS $v) {
                                    $userArray[] = $v['user_id'];
                                    $citate      = $v['comment_text'];
                                }
                                
                                $comp = App::db()->query("SELECT `competition_slug` FROM `competitions` WHERE `blog_id` = '$to' LIMIT 1")->fetch();
                                
                                if ($comp)
                                {
                                    $reparray['link2'] = "http://www.maryjane.ru/voting/competition/" . $comp['competition_slug'] . "/#comment" . $this->id;
                                }
                                else
                                {
                                    $reparray['link2'] = "http://www.maryjane.ru/blog/view/$to/#comment" . $this->id;
                                }
                                
                                $reparray['post_id'] = $to;
                                $reparray['author']  = $User->user_login;
                                $reparray['text']    = stripslashes($text);
                                $reparray['citate']  = stripslashes($citate);
                                
                                App::mail()->send($userArray, $tpl, $reparray);
                            }
                        }
                    break;
        
                    $User->watch($to, $type);
                }
                
                
                // Поднимаем карму автору камента на +1
                //$carma->updateUserCarma($User->id, 'comment', $carma->carmaFor["comment"]);
            
                // Запоминаем время последнего комментария
                $User->change(array('user_last_comment' => NOW));
                
                // отправляем алерт лерке
                /*
                if ($type != 'newgood')
                {
                    App::mail()->send(array(63250), 469, array(
                        'text' => stripslashes($text),
                        'link' => $link,
                    ));
                }*/
            }
            else {
                throw new Exception('Нельзя добавить пустой комментарий', 1);
            }
        }
        
        /**
         * Удалить комментарий
         */
        public function delete(user $User)
        {
            $r = App::db()->query("UPDATE `" . self::$dbtable . "` SET `comment_visible` = '-1' WHERE `comment_id` = '" . $this->id . "' AND (`user_id` = '" . $User->id . "' OR '" . $User->meta->mjteam . "' = 'super-admin') LIMIT 1");
            
            if ($r->rowCount() == 1)
            {
                switch ($this->object_type)
                {
                    // КОММЕНТАРИИ К РАБОТЕ
                    case 'good':
                    case 'newgood':
                        App::db()->query("UPDATE `goods` SET `comments` = `comments` - 1 WHERE `good_id` = '" . $this->object_id . "' LIMIT 1");
                    break;
            
                    // КОММЕНТАРИИ К ФОТОГРАФИИ
                    case 'gallery':
                        App::db()->query("UPDATE `gallery` SET `comments` = `comments` - 1 WHERE `gallery_picture_id` = '" . $this->object_id . "' LIMIT 1");
                    break;
            
                    // КОММЕНТАРИИ К ПОСТУ В БЛОГЕ
                    case 'blog':
                        App::db()->query("UPDATE `user_posts` SET `comments` = `comments` - 1 WHERE `id` = '" . $this->object_id . "' LIMIT 1");
                    break;
            
                    default:
                    break;
                }
            }
            //else 
                //throw new Exception ('comment ' . $this->id . ' not deleted');
        }
    }