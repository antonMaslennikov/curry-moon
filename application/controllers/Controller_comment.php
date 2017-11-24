<?php

    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
    use \smashEngine\core\exception\appException AS appException;
    use \application\models\comment AS comment;
    
    class Controller_comment extends \smashEngine\core\Controller
    {
        public function action_add()
        {
            if (!$this->user->authorized || $this->user->user_status != 'active' || $this->user->user_is_fake == 'true' || $this->user->meta->nocomments) {
                //$this->page404();
                exit('Вы не можете оставлять комментарии.');
            }
            
            if ($this->user->authorized)
            {
                $text = str_replace("&nbsp;", ' ', $_POST['commentText']);
                
                if (!in_array($this->user->id, array(6199, 27278))) 
                {
                    $text = strip_tags($text, "<a><b><i><u><em><s><p><strong><img><blockquote><cite><br>");

                    if (strlen(strip_tags($text)) > 3000) 
                    {
                        $text = substr($text, 0, 3000);
                    }
                }
                
                $to = intval($this->page->reqUrl[3]);
                $comment_id = intval($_POST['comment_id']);
            
                if ($this->user->user_is_fake == 'true')
                {
                    exit('Вы не можете оставлять комментарии.');
                }
                
                if (in_array($this->user->id, array(81177)))
                {
                    header('location: /' . trim($this->page->reqUrl[2]) . '/view/' . $to . '/?error=no_access#editor');
                    exit('Вы не можете оставлять комментарии.');
                }
                
                
            
                if ($this->user->user_carma < 0)
                {
                    if (getDateDiff($this->user->user_last_comment) <= 1)
                    {
                        header('location: /' . trim($this->page->reqUrl[2]) . '/view/' . $to . '/?error=carma_no_access#editor');
                        exit("Поскольку ваша карма отрицательна, Вы не можете оставлять комментарии чаще чем раз в час.");
                    }
                }
            
                $visible = 1;
                
                if (trim($this->page->reqUrl[2]) == 'newgood') 
                {
                    $visible   = 'hudsovet';
                }
                
                if (strlen($text) > 0)
                {
                    if ($this->user->id != 6199 || $this->page->reqUrl[2] != 'blog') 
                    {
                        $link_chars = "a-zA-Z0-9АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя\%\.\_\.\,\;\/\-\#\:\?=\&";
                        $text = preg_replace("'<a[^>]*?>(.*?)</a>'si",'\\1', stripslashes(stripslashes($text))); // заменить ссылку в тегах - на ссылку без тегов
                        $text = preg_replace("'src=[\"]*(http://www.|https?://|http://|www.)([$link_chars]*).*?>'si",'img:\\2">', $text); // заменить ссылки в рисунках на псведо рисуночную ссылку
                        $text = preg_replace("#(https://|http://|www.)[$link_chars]*#",'<a href="\\0" rel="nofollow">\\0</a>',$text); // превращаем урлы в ХТМЛ ссылки
                        $text = preg_replace("'img:([$link_chars]*).*?>'si",'src="http://www.\\1">', $text); // заменить ссылки на картинки обратно на нормальные
                    }
                    
                    // ADD NEW
                    if (empty($comment_id))
                    {
                        $sth = App::db()->prepare("INSERT INTO `comments`
                              SET
                                `comment_text`    = :text, 
                                `user_id`         = :user, 
                                `object_id`       = :to,
                                `object_type`     = :type,
                                `comment_parent`  = :parent, 
                                `comment_ip`      = :ip, 
                                `comment_visible` = :visible");
                                
                        $sth->execute(array(
                            'text'    => $text,
                            'user'    => $this->user->id,
                            'to'      => $to,
                            'type'    => trim($this->page->reqUrl[2]),
                            'parent'  => intval($_POST['parent']),
                            'ip'      => $_SERVER['REMOTE_ADDR'],
                            'visible' => $visible,
                        )); 
                        
                        $cid = App::db()->lastInsertId();
                    }
                    // EDIT COMMENT
                    else
                    {
                        App::db()->query("UPDATE `comments` 
                              SET `comment_text` = '" . addslashes($text) . "' 
                              WHERE `comment_id` = '" . $comment_id . "' AND (`user_id` = '".$this->user->id."' OR " . (int) in_array($this->user->id, array(6199,27278,105091)) . ")  
                              LIMIT 1");
                    }
                    
                    /**
                     * РАССЫЛКА УВЕДОМЛЕНИЙ
                     */ 
                    if (empty($comment_id) && $this->user->id != 27278)
                    {
                        switch (trim($this->page->reqUrl[2]))
                        {
                            // худсовет
                            case 'newgood':
                                
                                if (empty($this->page->reqUrl[3])) {
                                    $sth = App::db()->query("SELECT `user_id` FROM `users_meta` WHERE `meta_name`  = 'hudsovet' AND `meta_value` = '1' AND `user_id` != '" . $this->user->id . "'");
                                    
                                    if ($sth->rowCount() > 0)
                                    {
                                        foreach ($sth->fetchAll() as $v) $userArray[] = $v['user_id'];
                                        
                                        $link = "http://www.maryjane.ru/hudsovet/" . ((!empty($this->page->reqUrl[3])) ? 'good/' . $this->page->reqUrl[3] . '/#comment' . $cid : '');
                
                                        App::mail()->send($userArray, 201, array(
                                                    'link' => "<a href=\"$link\">$link</a>"));
                                    }
                                }
                                
                            break;
                            
                            case 'good':
                            
                                App::db()->query("UPDATE `goods` SET `comments` = `comments` + 1 WHERE `good_id` = '{$to}'");
                            
                                $sth = App::db()->query("SELECT g.`good_id`, g.`good_name`, g.`good_status`, u.`user_login` FROM `goods` g, `users` u WHERE g.`good_id`='{$to}' AND g.`user_id` = u.`user_id` LIMIT 1");
                                
                                if (1 == $sth->rowCount())
                                {
                                    $good = $sth->fetch();
                    
                                    if ($good['good_status'] == 'voting') {
                                        $link = mainUrl . '/voting/view/' . $to .'/#comments';
                                    } else {
                                        $link = mainUrl . '/catalog/' . $good['user_login'] . '/' . $to .'/#comments';
                                    }
                    
                                    $sth = App::db()->query("SELECT `user_id` FROM `user_watches` WHERE `object_id` = '{$to}' AND `object_type` = '1' AND `user_id` != '" . $this->user->id . "' AND `user_notified` = 'false'");
            
                                    if ($sth->rowCount() > 0)
                                    {
                                        foreach ($sth->fetchAll() as $v) $userArray[] = $v['user_id'];
                                        
                                        // сергею копию всех уведомлений
                                        $userArray[] = 6199;
                                        
                                        // чтобы вставленные картинки нормально отображались в письме-уведомлении
                                        $text = str_replace('<img ', '<img style="max-width: 440px;"', $text);
                                        
                                        App::mail()->send($userArray, 455, array(
                                            'good_id'    => $to,
                                            'link'       => $link,
                                            'goodName'   => stripslashes($good['good_name']),
                                            'comment'    => stripslashes($text),
                                            'user_login' => $this->user->user_login, 
                                            'user_avatar'=> userId2userGoodAvatar($this->user->id, 50, '', 1),
                                            'date'       => datefromdb2textdate(date('Y-m-d H:i:s'), 1)
                                        ));
                                    }
                                }
                            break;
                    
                            case 'gallery':
                            
                                App::db()->query("UPDATE `gallery` SET `comments` = `comments` + 1 WHERE `gallery_picture_id` = '$to'");
                            
                                $sth = App::db()->query("SELECT `gallery_picture_id`, `good_id` FROM `gallery` WHERE `gallery_picture_id`='$to' LIMIT 1");
                                
                                if (1 == $sth->rowCount())
                                {
                                    $pic = $sth->fetch();
                                    
                                    $sth = App::db()->query("SELECT `user_id` FROM `user_watches` WHERE `object_id` = '$to' AND `object_type` = '2' AND `user_id` != '" . $this->user->id . "' AND `user_notified` = 'false'");
                                    
                                    if ($sth->rowCount() > 0)
                                    {
                                        foreach ($sth->fetchAll() as $v) $userArray[] = $v['user_id'];
                    
                                        // сергею копию всех уведомлений
                                        $userArray[] = 6199;
                    
                                        $link = "http://www.maryjane.ru/gallery/view/$to/";
                                        $reparray['goodName'] = goodId2goodName($pic['good_id']);
                                        $reparray['link']     = $link;
                                        App::mail()->send($userArray, 101, $reparray);
                                    }
                                }
                            break;
                    
                            case 'blog':
                    
                                App::db()->query("UPDATE `user_posts` SET `comments` = `comments` + 1 WHERE `id` = '$to'");
                            
                                $sth = App::db()->query("SELECT `post_title`, `post_author`  FROM `user_posts` WHERE `id` = '$to' LIMIT 1");
                        
                                if (1 == $sth->rowCount())
                                {
                                    $post = $sth->fetch();
                        
                                    // если это новый камент к топику
                                    if (!$_POST['parent'])
                                    {
                                        // то оповещаем всех подписавшихся
                                        $sth = App::db()->query("SELECT `user_id` FROM `user_watches` WHERE `object_id` = '$to' AND `object_type` = '0' AND `user_id` != '" . $this->user->id . "' AND `user_notified` = 'false'");
                                        $tpl = 454;
                                    }
                                    // если это ответ на какой-то камент
                                    else
                                    {
                                        // то оповещаем только автора камента на который ответили
                                        $sth = App::db()->query("SELECT c.`user_id`, c.`comment_text` FROM `user_watches` uw, `comments` c WHERE uw.`object_id` = '$to' AND uw.`object_type` = '0' AND uw.`user_id` != '" . $this->user->id . "' AND uw.`user_notified` = 'false' AND uw.`user_id` = c.`user_id` AND c.`comment_id` = '" . intval($_POST['parent']) . "' LIMIT 1");
                                        $tpl = 454;
                                    }
                                    
                                    $reparray['link1'] = $link = "http://www.maryjane.ru/blog/view/$to/";
                                    $reparray['newsSubject'] = htmlspecialchars_decode($post['post_title']);
                                    
                                    if ($sth->rowCount() > 0)
                                    {
                                        foreach ($sth->fetchAll() as $v) {
                                            $userArray[] = $v['user_id'];
                                            $citate      = $v['comment_text'];
                                        }
                                    
                                        $foo = App::db()->query("SELECT `competition_slug` FROM `competitions` WHERE `blog_id` = '$to' LIMIT 1")->fetch();
                                        $comp = $foo['competition_slug'];
                                        
                                        if ($comp)
                                        {
                                            $reparray['link2'] = "http://www.maryjane.ru/voting/competition/{$comp}/#comment" . $cid;
                                        }
                                        else
                                        {
                                            $reparray['link2'] = "http://www.maryjane.ru/blog/view/$to/#comment" . $cid;
                                        }
                                        
                                        $reparray['post_id'] = $to;
                                        $reparray['author']  = $this->user->user_login;
                                        $reparray['text']    = str_replace('<img ', '<img style="max-width: 440px;"', stripslashes($text));
                                        $reparray['citate']  = stripslashes($citate);
                                        
                                        // сергею копию всех уведомлений
                                        $userArray[] = 6199;
                                        
                                        App::mail()->send($userArray, $tpl, $reparray);
                                    }
                                }
                            break;
                        }
            
                        $this->user->watch($to, trim($this->page->reqUrl[2]));
                    }
                    
                    // Запоминаем время последнего комментария
                    $this->user->change(array('user_last_comment' => NOW));
                    
                    // отправляем алерт лерке
                    /*
                    if ($this->page->reqUrl[2] != 'newgood' && empty($comment_id) && $this->user->id != 27278)
                    {
                        App::mail()->send(array(63250), 469, array(
                            'text' => stripslashes($text),
                            'user_id' => $this->user->id,
                            'user_login' => $this->user->login,
                            'link' => $link,
                            'good_name' => $good['good_name'],
                            'good_author' => userId2userLogin(goodId2goodAuthor($to)),
                        ));
                    }*/
                }
            }
            
            if (!$this->page->isAjax) {
                header("location: " . $_SERVER['HTTP_REFERER'] . '#comments');
            } else {
                exit('ok');
            }
        }
        
        public function action_delete()
        {
            if (!empty($this->page->reqUrl[2]))
            {
                try
                {
                    $C = new comment($this->page->reqUrl[2]);
                    $C->delete($this->user);
                    
                    if (!$this->page->isAjax) {
                        $this->page->refresh();
                    } else {
                        exit(json_encode(['status' => 'ok']));  
                    }
                }
                catch (appException $e)
                {
                    if (!$this->page->isAjax) {
                        exit($e->getMessage());
                    } else {
                        exit(json_encode(['status' => 'error', 'message' => $e->getMessage()]));  
                    }
                }
            }
        }
    }