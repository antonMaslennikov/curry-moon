<?php
    namespace application\controllers;
    
    use \application\models\User AS User;
    use \application\models\good AS good;
    use \application\models\blog AS blog;
    use \application\models\carma AS carma;
    use \application\models\CRss AS CRss;
    use \smashEngine\core\App AS App;
    
    use \PDO;
    use \Imagick;
    use \ImagickPixel;
    use \Exception;
    
    class Controller_blog extends \smashEngine\core\Controller
    {
        public function action_index()
        {
            if (!empty($this->page->reqUrl[1])) {
                $action = addslashes($this->page->reqUrl[1]);
            } else {
                $action = 'news';
            }
            
            $this->page->breadcrump[] = array(
                'link' => '/voting/competition/main/',
                'caption' => 'Сообщество');
             
            $this->page->breadcrump[] = array(
                'link' => '/' . $this->page->module . '/',
                'caption' => 'Блог');
            
            $this->page->footer_tpl = 'order/footer.tpl';
        
            $this->view->setVar('tabzNoFollow', 'rel="nofollow"');
            $this->view->setVar('task', $action);
        
            $carma  = new carma;
        
            switch ($action) 
            {
                case 'dizayn':
                case 'otklonennuerabotu':
                case 'reklama':
                case 'pogelaniyaipredlogeniya':
                case 'novosti':
                case 'izuotovlenie-futbolok':
                case 'winners':
                case 'competitions':
                case 'interview':
                case 'portfolio':
                case 'mail':
                case 'teaching':
                case 'samorazvitie':
                    
                    $this->page->tpl = 'blog/news.tpl';
                    $this->page->sidebar_tpl = 'blog/news.sidebar.tpl';
                    
                    // page -----------------------------------------------------------------------
                    if (empty($this->page->reqUrl[3])) 
                        $page = 1;
                    else {
                        $page = intval($this->page->reqUrl[3]);
                        
                        if ($page == 1) {
                            $this->page->go('/' . implode('/', array_slice($this->page->reqUrl, 0, count($this->page->reqUrl) - 1)) . '/', 301);
                            exit();
                        }
                    }
                    
                    $this->view->setVar('page', $page);
                    // ----------------------------------------------------------------------------
        
                    if ($page > 1) {
                        $this->page->title .= ', страница ' . $page;
                    }
                    
                    switch ($filter)
                    {
                        case 'all':
                        default:
                            if ($theme != 'all')
                                $oq = " up.`post_date`";
                            else
                                $oq = " ma";
                        break;
                        
                        case 'watching':
                            $fq = " HAVING WFTChecked != '0'";
                            if ($theme != 'all')
                                $oq = " up.`post_date`";
                            else
                                $oq = " ma";
                        break;
                        case 'topcommented':
                            $oq = " up.`comments`";
                        break;
                        case 'topviewing':
                            $oq = " up.`visits`";
                        break;
                        case 'topcarming':
                            $oq = " carma";
                        break;
                    }
                    // ----------------------------------------------------------------------------
        
                    $theme_id = blog::slug2themeId($this->page->reqUrl[1]);
                    $th = blog::$themes[$theme_id];
                    
                    /**
                     * посты
                     */
                    $q = "SELECT
                            up.*,
                            u.`user_login`,
                            uw.`id` AS WFTChecked,
                            SUM(pc.`carma`) AS carma
                          FROM
                            `user_posts` AS up
                                LEFT JOIN `user_watches` AS uw ON uw.`object_id` = up.`id` AND uw.`object_type` = '0' AND uw.`user_id` = '" . $this->user->id . "'
                                LEFT JOIN `carma_posts` AS pc      ON pc.`post_id`  = up.`id` AND pc.`post_type` = 'blog_post',
                            `users` AS u
                          WHERE
                                up.`post_author` = u.`user_id`
                            AND up.`post_status` = 'publish'
                            AND up.`post_theme`  = '{$theme_id}'
                          GROUP BY up.`id`
                          {$fq}
                          ORDER BY {$oq} DESC";
                    
                    $this->view->setVar('theme_name', $th['name']);
                    $this->view->setVar('theme_slug', $th['slug']);
                    
                    $this->page->title = 'Блог - ' . $th['name'];
            
                    $sth = App::db()->query($q);
                    $totalPosts = $sth->rowCount();
            
                    $q .= " LIMIT " . strval((15 * (($page ? $page : 1) - 1))) . ", 15";
                    $sth = App::db()->query($q);
                    $posts = $sth->fetchAll();
                    
                    $PD = array();
            
                    foreach($posts as $k => $row)
                    {
                        $row['post_date']    = datefromdb2textdate($row['post_date'], 1);
                        $row['post_title']   = stripslashes($row['post_title']);
                        $row['post_theme']   = postId2theme($row['id'], $this->page->module . '/news');
                        $row['post_carma']   = $carma->getPostCarma('blog_post', $row['id']);
        
                        if ($k <= 5)
                            $PD[] = $row['post_title'];
                        
                        $block_name = 'big';
        
                        if (!empty($row['post_pic']))
                            $row['path'] = pictureId2path(($block_name == 'big') ? abs($row['post_pic']) : $row['post_pic_small']);
                        
                        if (empty($row['path'])) 
                            $row['path'] = '/images/noimg.gif';
                         
                        $postsResult[$block_name][] = $row;
                    }
        
                    $this->view->setVar('POSTS', $postsResult);
                    
            
                    $tpages = ceil($totalPosts / 15);
                    $pages = pagination($tpages, $page, '/' . $this->page->module . '/' . $action . '/all', 3, TRUE);
        
                    $this->view->setVar('tpages', $tpages); // всего страниц
                    $this->view->setVar('PAGES', $pages);   // пагинация
                    /**
                     * end посты
                     */
                    
                    /**
                     * subcribe status
                     */
                    if ($this->user->authorized)
                    {
                        $sth = App::db()->query("SELECT COUNT(*) AS c FROM `mail_list_subscribers` WHERE `user_id` = '" . $this->user->id . "' AND `mail_list_id` = '4'");
                        $row = $sth->fetch();
                        
                        if ($row['c'] == 0) {
                            $this->view->setVar('subscribe', TRUE);
                        } else {
                            $this->view->setVar('unsubscribe');
                        }
                    }
                    
                    /**
                     * Кол-во моих постов, чтобы показать таб "мои посты" или скрыть
                     */
                    $sth = App::db()->query("SELECT COUNT(*) AS c FROM `user_posts` WHERE `post_author` = '" . $this->user->id . "' AND `post_status` = 'publish'");
                    $row = $sth->fetch();
                    
                    $this->view->setVar('myPosts', $row['c']);
                    
                    break;
                
                /**
                 * посты по тегам
                 */
                case 'tag':
                
                    $this->page->tpl = 'blog/tags.tpl';
                    $this->page->sidebar_tpl = 'tag.sidebar.tpl';
                    
                    /**
                     * глобальное облако тегов
                     */
                    if (empty($this->page->reqUrl[2]))
                    {
                        $q = "SELECT t.`tag_id`, t.`name`, t.`slug`, COUNT(DISTINCT(tr.`object_id`)) as count
                              FROM `tags` AS t, `tags_relationships` AS tr, `user_posts` AS up
                              WHERE tr.`tag_id` = t.`tag_id` AND tr.`object_id` = up.`id` AND tr.`object_type` = '0' AND up.`post_status` = 'publish' AND t.`tag_id` NOT IN ('13775') AND up.`r301` = '0'
                              GROUP BY t.`tag_id`
                              HAVING count > 1
                              ORDER BY t.name";
                          
                        $sth = App::db()->query($q);
                        
                        foreach ($sth->fetchAll() AS $tag) {
                            $counts[$tag['name']] = $tag['count'];
                            $tag_links[trim($tag['name'])] = ($tag['slug']);
                        }
        
                        $min_count = min($counts);
                        $max_count = max($counts);
                        $largest   = 37;
                        $smallest  = 16;
        
                        $spread = $max_count - $min_count;
                        if ($spread <= 0 ) $spread = 1;
                        $font_spread = $largest - $smallest;
                        if ( $font_spread <= 0 ) $font_spread = 1;
                        $font_step = $font_spread / $spread;
        
                        $a = array();
                        $i = 1;
        
                        foreach ( $counts as $tag => $count )
                        {
                            if ($count == 1) {
                                $fsize = 8;
                            } elseif ($count > 1 && $count <= 5) {
                                $fsize = 10;
                            } else {
                                $fsize = $smallest + (($count - $min_count) * $font_step);
                            }
        
                            $row['font']  = $fsize;
                            $row['name']  = trim(stripslashes($tag));
                            $row['slug']  = trim($tag_links[$tag]);
                            $row['count'] = $count;
        
                            $tags[] = $row;
                        }
        
                        $this->view->setVar('TAGS', $tags);
        
                    }   
                    else
                    {
                        $tag = urldecode($this->page->reqUrl[2]);
                        
                        $tag_info = getTagInfoBySlug($tag);
                        
                        if (!empty($tag_info['synonym_id']) || !empty($tag_info['synonym_id_blog']))
                        {
                            $sth = App::db()->query("SELECT `slug` FROM `tags` WHERE `tag_id` = '" . $tag_info['synonym_id_blog'] . "' LIMIT 1");
                            $slug = $sth->fetch();
                            
                            if (!empty($slug['slug']))
                            {
                                header("HTTP/1.1 301 Moved Permanently");
                                header('location: /blog/tag/' . $slug['slug'] . '/');
                                exit();
                            }
                        }
                        
                        $this->page->title = 'Посты - ' . stripslashes($tag_info['name']);
        
                        $PD[] = stripslashes($tag_info['name']);
                            
                        // 2 .GET BLOG POSTS BY TAG SLUG
                        $sth = App::db()->prepare("SELECT up.*, u.`user_login`, p.`picture_path`
                                FROM 
                                    `tags` t, 
                                    `tags_relationships` tr, 
                                    `users` u, 
                                    `user_posts` AS up
                                        LEFT JOIN `pictures` AS p ON p.`picture_id` = up.`post_pic`
                                WHERE 
                                        t.`slug` = :slug 
                                    AND t.`tag_id` = tr.`tag_id` 
                                    AND up.`id` = tr.`object_id` 
                                    AND tr.`object_type` = '0' 
                                    AND up.`post_status` = 'publish' 
                                    AND up.`post_author` = u.`user_id` 
                                    AND up.`r301` = '0'
                                GROUP BY up.`id`
                                ORDER BY up.`post_pic` DESC, tr.`object_id` DESC");
                        
                        $sth->execute(array(
                            'slug' => urldecode($tag),
                        ));
                        
                        $total = $sth->rowCount();
                        
                        if ($total > 0) 
                        {
                            if ($total == 1)
                            {
                                $p = $sth->fetch($r);
                                header('location: /blog/view/' . $p['id'] . '/');
                                exit();
                            }
                            else 
                            {
                                foreach ($sth->fetchAll() AS $p)
                                {
                                    if (iconv_strlen($p['post_title'], 'UTF-8') > 50) 
                                        $post_title = iconv_substr($p['post_title'], 0, 50, 'UTF-8') . ' ...';
                                    else 
                                        $post_title = $p['post_title'];
                                    
                                    $data = array( 
                                            "ID"            => $p['id'],
                                            "AVATAR"        => userId2userGoodAvatar($p['post_author'], 50),
                                            "NAME"          => stripslashes($post_title),
                                            "AUTHORID"      => $p['post_author'],
                                            "AUTHORLOGIN"   => $p['user_login'],
                                            "COMMENTS"      => $p['comments'],
                                            'PIC'           => $p['picture_path'],
                                            'DATE'          => datefromdb2textdate($p['post_date']),
                                            'post_theme'    => postId2theme($row['id'], 'blog/news'),
                                            'post_slug'     => $p['post_slug']);
                        
                                    if (!empty($p['post_pic']))
                                        $POSTS[strtotime($p['post_date']) . $p['id']] = $data;
                                    else
                                        $POSTS_short[] = $data;
                                }
        
                                krsort($POSTS);
        
                                $this->view->setVar('POSTS', $POSTS);
                                $this->view->setVar('POSTS_short', $POSTS_short);
                    
                                if (!empty($tag_info['title'])) {
                                    $this->page->title = $tag_info['title'];
                                } else {
                                    $this->page->title = $tag_info['name'];   
                                }
        
                                $this->page->title .= ' - Блог';
                            }
                        } else {
                            header("HTTP/1.1 301 Moved Permanently");
                            header('location: /blog/');
                            exit();
                        }
                    }
                    
                    break;
                
                
                /**
                 * удаление картинки из галлереи
                 */
                case 'deletepic':
                    
                    if ($_GET['hash'] == sha1(SALT . $this->user->id . $_GET['id']))
                    {
                        try
                        {
                            foreach (explode(',', $_GET['id']) as $id) {
                                deletePicture($id);
                                unset($_SESSION['blog']['gallery'][$id]);
                                App::db()->query(sprintf("DELETE FROM `user_blog_gallery` WHERE `big` = '%d' LIMIT 1", $id));
                            }
                        }
                        catch (Exception $e) { printr($e); }
                    }
                    
                    exit();
                    
                    break;
                
                /**
                 * загрузка картинок в галлерею поста
                 */
                case 'upload':
                    
                    $pic        = catchFileNew('Filedata', IMAGEUPLOAD . DS . 'blogs' . DS . date('Y' . DS . 'm' . DS . 'd') . DS, '', 'gif,png,jpeg,jpg', 300, 300, 5000, 5000);
                    $pic['prv'] = thumb($pic['path'], dirname($pic['path']) . DS . uniqid() . '.jpeg', 100);
        
                    if ($_POST['post_id'])
                    {
                        App::db()->query(sprintf("INSERT INTO `user_blog_gallery` SET `post_id` = '%d', `big` = '%d', `small` = '%d'", $_POST['post_id'], $pic['id'], $pic['prv']['id']));
                    }
                    
                    $pic['id']      .= ',' . $pic['prv']['id'];
                    $pic['preview']  = $pic['prv']['path'];
                    $pic['hash']     = sha1(SALT . $this->user->id . $pic['id']);
                    
                    $_SESSION['blog']['gallery'][$pic['id']] = $pic;
                    
                    exit(json_encode(array(0 => $pic)));
                    
                    break;
                
                /**
                 * Подписаться / отписаться на уведомления о появлении новых постов
                 */
                case 'unsubscribe':
                case 'subscribe':
                    
                    $this->page->noindex = TRUE;
                    
                    header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );  // disable IE caching
                    header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
                    header( "Cache-Control: no-cache, must-revalidate" );
                    header( "Pragma: no-cache" );
                    header( "Content-Type: text/html; charset=utf-8");
                    
                    $sth = App::db()->query("SELECT `mail_list_subscriber_id` AS id FROM `mail_list_subscribers` WHERE `user_id` = '" . $this->user->id . "' AND ( `mail_list_id` = '4' OR `mail_list_id` = '-4')");
                    
                    if ($action == 'subscribe') {
                        
                        if (!$sth->fetch()) {
                            App::db()->query("INSERT  INTO `mail_list_subscribers` SET `user_id` = '" . $this->user->id . "', `mail_list_id` = '4'");
                        } else {
                            App::db()->query("UPDATE `mail_list_subscribers` SET `mail_list_id` = '4' WHERE `user_id` = '" . $this->user->id . "' AND ( `mail_list_id` = '4' OR `mail_list_id` = '-4')");
                        }
                        
                    } else {
                        if (!$sth->fetch()) {
                            App::db()->query("INSERT INTO `mail_list_subscribers` SET `user_id` = '" . $this->user->id . "', `mail_list_id` = '-4'");
                        } else {
                            App::db()->query("UPDATE `mail_list_subscribers` SET `mail_list_id` = '-4' WHERE `user_id` = '" . $this->user->id . "' AND `mail_list_id` = '4'");
                        }
                    }
                    
                    //header('location: ' . $_SERVER['HTTP_REFERER']);
                    exit();
                    
                break;
                    
                case 'year':
                
                    if (!empty($this->page->reqUrl[2]))
                    {
                        if (!empty($this->page->reqUrl[3]) && is_numeric($this->page->reqUrl[3])) 
                        {
                            if ($this->page->reqUrl[3] == 1)
                            {
                                unset($this->page->reqUrl[3]);
                                
                                header("HTTP/1.1 301 Moved Permanently");
                                header('Location: /' . implode('/', $this->page->reqUrl) . '/');
                                exit();
                            }
                            
                            $page = intval($this->page->reqUrl[3]);
                        }
                        else 
                        {
                            $page = 1;
                        }
            
                        $this->view->setVar('page', $page);
                    
                        $onpage = 15;
                        
                        $this->page->tpl = 'blog/news.tpl';
                        $this->page->sidebar_tpl = 'blog/news.sidebar.tpl';
                    
                        $y = intval($this->page->reqUrl[2]);
                        
                        $q = "SELECT
                              SQL_CALC_FOUND_ROWS
                                up.*,
                                'post_new',
                                u.`user_login`,
                                uw.`id` AS WFTChecked
                              FROM
                                `user_posts` AS up
                                    LEFT JOIN `user_watches` AS uw ON uw.`object_id` = up.`id` AND uw.`object_type` = '0' AND uw.`user_id` = '" . $this->user->id . "',
                                `users` AS u
                              WHERE
                                up.`post_author` = u.`user_id`
                                AND up.`post_status` = 'publish'
                                AND up.`post_sticked` = '-1'
                                AND EXTRACT(YEAR FROM up.`post_date`) = '$y'
                                AND up.`r301` = '0'
                              GROUP BY up.`id`
                              ORDER BY up.`post_date` DESC
                              LIMIT " . strval(($onpage * ($page - 1))) . ", $onpage";
                        $sth = App::db()->query($q);
                        $rs = $sth->fetchAll();
                        
                        $sth = App::db()->query("SELECT FOUND_ROWS() AS t");
                        $row = $sth->fetch();
                        $totalPosts = $row['t'];
        
                        foreach ($rs as $row) 
                        {
                            $row['post_date']    = datefromdb2textdate($row['post_date'], 1);
                            $row['post_title']   = stripslashes($row['post_title']);
                            $row['post_theme']   = postId2theme($row['id'], $this->page->module . '/news');
                            $row['post_carma']   = $carma->getPostCarma('blog_post', $row['id']);
                            
                            
                            if (!empty($row['post_pic']))
                                $row['path'] = pictureId2path($row['post_pic']);
                            elseif (!empty($row['post_pic_small']))
                                $row['path'] = pictureId2path($row['post_pic_small']);
                            else 
                                $row['path'] = '/images/noimg.gif';
                            
                            if (!empty($row['post_pic']))
                                $posts['big'][] = $row;
                            else
                                $posts['post'][] = $row;
                        }
                        
                        $this->view->setVar("POSTS", $posts);
                    
                        $tpages = ceil($totalPosts / $onpage);
                        $pages = pagination($tpages, $page, '/' . $this->page->module . '/year/' . $y, 3, TRUE);
            
                        $PT = array("Блог &mdash; {$y} год" . (($page > 1) ? ' - страница ' . $page : ''));
            
                        $this->view->setVar('year', $y);
                        $this->view->setVar('task', 'news');
                        $this->view->setVar('theme', 'all');
                        $this->view->setVar('tpages', $tpages); // всего страниц
                        $this->view->setVar('PAGES', $pages);   // пагинация
                        
                        /**
                         * subcribe status
                         */
                        if ($this->user->authorized)
                        {
                            $foo = App::db()->query("SELECT COUNT(*) AS c FROM `mail_list_subscribers` WHERE `user_id` = '" . $this->user->id . "' AND `mail_list_id` = '4'")->fetch();
                            
                            if ($foo['c'] >= 1) {
                                $this->view->setVar('unsubscribe');
                            } else {
                                $this->view->setVar('subscribe', TRUE);
                            }
                        }
                        
                        // архив
                        $sth = App::db()->query("SELECT DISTINCT(EXTRACT(YEAR FROM `post_date`)) AS y FROM `user_posts` WHERE `post_status` = 'publish' ORDER BY y DESC");
                        $years = $sth->fetchAll();
                        
                        $this->view->setVar('years', $years);
                    }
                    else
                    {
                        header('loaction: /blog/');
                        exit();
                    }
                break;  
                
                /**
                 * общая лента
                 */
                case 'news':
                case 'search':
                    
                    $this->page->tpl = 'blog/news.tpl';
                    //$this->page->sidebar_tpl = 'blog/news.sidebar.tpl';
                    
                    // theme -----------------------------------------------------------------------
                    if (empty($this->page->reqUrl[2])) 
                        $theme = 'all'; 
                    else {
                        $theme = trim($this->page->reqUrl[2]);
                        
                        if (!in_array($theme, array('other', 'all')))
                        {
                            header("HTTP/1.1 301 Moved Permanently");
                            header("Location: /tag/posts/$theme/");
                            exit();
                        }
                    }
                    
                    $this->view->setVar('theme', $theme);
                    // ----------------------------------------------------------------------------
                    
                    // page -----------------------------------------------------------------------
                    if (empty($this->page->reqUrl[3])) 
                        $page = 1;
                    else 
                        $page = intval($this->page->reqUrl[3]);
        
                    $this->view->setVar('page', $page);
                    // ----------------------------------------------------------------------------
        
                    // search ---------------------------------------------------------------------
                    if ($this->page->reqUrl[1] == 'search')
                    {
                        $squery = addslashes(trim($_GET['q']));
        
                        $at[] = '`tags` AS t';
                        $at[] = '`tags_relationships` AS gtr';
                        $aq[] = " gtr.`tag_id` = t.`tag_id` AND gtr.`object_id` = up.`id` AND gtr.`object_type` = '0'";
        
                        $search = array();
                        $search[] = " MATCH (up.`post_title`, up.`post_content`) AGAINST ('{$squery}') ";
        
                        if ($tag = getTagInfoBySlug(toTranslit($squery)))
                        {
                            $search[] = " t.`tag_id` = '" . $tag['tag_id'] . "'";
                            $search[] = " t.`synonym_id` = '" . $tag['tag_id'] . "'";
                        }
        
                        $aq[] = sprintf(" (%s)", implode(' OR ', $search));
        
                        $this->page->title = 'Результаты поиска по блогам по запросу: ' . $_GET['q'];
                        
                        $this->view->setVar('H1', $this->page->title);
                        $this->page->noindex = true;
                        $this->view->setVar('SEARCH', TRUE);
                    }
                    // ----------------------------------------------------------------------------
                    
                    if ($this->page->reqUrl[2] == 'all' && $page == 1) {
                        header("HTTP/1.1 301 Moved Permanently");
                        header("Location: /" . $this->page->module . '/');
                        exit(); 
                    }
                    
                    if ($this->page->reqUrl[3] == 'all' && empty($page)) {
                        header("HTTP/1.1 301 Moved Permanently");
                        header("Location: /" . $this->page->module . "/news/" . $this->page->reqUrl[2] . '/');
                        exit(); 
                    }
                    
                    if ($page > 1) {
                        $this->page->title .= ', страница ' . $page;
                    }
                    
                    switch ($filter)
                    {
                        case 'all':
                        default:
                            if ($theme != 'all')
                                $oq = " up.`post_date`";
                            else
                                $oq = " ma";
                        break;
                        
                        case 'watching':
                            $fq = " HAVING WFTChecked != '0'";
                            if ($theme != 'all')
                                $oq = " up.`post_date`";
                            else
                                $oq = " ma";
                        break;
                        case 'topcommented':
                            $oq = " up.`comments`";
                        break;
                        case 'topviewing':
                            $oq = " up.`visits`";
                        break;
                        case 'topcarming':
                            $oq = " carma";
                        break;
                    }
                    // ----------------------------------------------------------------------------
        
                    /**
                     * посты
                     */
                    
                    $postsSummary = array(); 
                    $stPosts      = array();      // Прилепленные посты
                    $newPosts     = array();      // Новые посты
                    
                    //printr($filter);
                    
                    // первая страница
                    if ($theme == 'all')
                    {
                        if ($page == 1) 
                        {
                            //if ($filter == 'all')
                            //{
                                $q = "SELECT
                                        up.*,
                                        u.`user_login`,
                                        uw.`id` AS WFTChecked
                                      FROM
                                        `user_posts` AS up
                                            LEFT JOIN `user_watches` AS uw ON uw.`object_id` = up.`id` AND uw.`object_type` = '0' AND uw.`user_id` = '" . $this->user->id . "',
                                        `users` AS u
                                        " . (($at) ? ', ' . implode(', ', $at) : '') . "
                                      WHERE
                                            up.`post_author` = u.`user_id`
                                        AND up.`post_status` = 'publish'
                                        AND up.`post_sticked` = '1'
                                        AND up.`post_theme` = '0'
                                        " . (count($aq) > 0 ? ' AND ' . implode('AND', $aq) : '') . "
                                      GROUP BY up.`id`
                                      ORDER BY up.`post_date` DESC";
                                
                                $sth = App::db()->query($q);
                                
                                $stPosts = $sth->fetchAll();
                            //}
                        }
                
                        
                        // СПИСОК ПОСЛЕДНИХ ПОСТОВ отсортированный по дате
                        $q = "SELECT
                                up.*,
                                u.`user_login`,
                                MAX(upc.`comment_date`) ma,
                                uw.`id` AS WFTChecked
                              FROM
                                `user_posts` AS up
                                    LEFT JOIN `comments` AS upc ON upc.`object_id` = up.`id` AND upc.`object_type` = 'blog'
                                    LEFT JOIN `user_watches` AS uw ON uw.`object_id` = up.`id` AND uw.`object_type` = '0' AND uw.`user_id` = '" . $this->user->id . "',
                                `users` AS u
                                " . (($at) ? ', ' . implode(', ', $at) : '') . "
                              WHERE
                                    up.`post_author` = u.`user_id`
                                AND up.`post_status` = 'publish'
                                AND up.`post_sticked` = '-1'
                                " . (count($aq) > 0 ? ' AND ' . implode('AND', $aq) : '') . "
                                AND up.`r301` = '0'
                              GROUP BY up.`id`
                              {$fq}
                              ORDER BY up.`post_date` DESC";
                        // $oq DESC,
                        //printr($q);
                    }
                    else
                    {
                        $this->view->setVar('blog_menu_follow', 'rel="nofollow"');
                        
                        // выбор постов с определённой тематикой
                        if ($theme != 'other') 
                        {
                            foreach (blog::$themes as $k => $v) {
                                if ($v['slug'] == $theme) {
                                    $th = $v;
                                    continue;
                                }
                            }
                            
                            $q = "SELECT
                                    up.*,
                                    u.`user_login`,
                                    uw.`id` AS WFTChecked,
                                    SUM(pc.`carma`) AS carma
                                  FROM
                                    `user_posts` AS up
                                        LEFT JOIN `user_watches` AS uw ON uw.`object_id` = up.`id` AND uw.`object_type` = '0' AND uw.`user_id` = '" . $this->user->id . "'
                                        LEFT JOIN `carma_posts` AS pc      ON pc.`post_id`  = up.`id` AND pc.`post_type` = 'blog_post',
                                    `users` AS u
                                  WHERE
                                        up.`post_author` = u.`user_id`
                                    AND up.`post_status` = 'publish'
                                    AND up.`post_theme`  = '{$theme_id}'
                                  GROUP BY up.`id`
                                  $fq
                                  ORDER BY $oq DESC";
                            
                            $theme_name = stripslashes($th['name']);
                            $theme_slug = stripslashes($th['slug']);
                            
                            $this->view->setVar('theme_name', $theme_name);
                            $this->view->setVar('theme_slug', $theme_slug);
                            
                            $this->page->title = 'Блог - ' . $theme_name;
                        } 
                        else 
                        {
                            $q = "SELECT
                                    up.*,
                                    u.`user_login`,
                                    uw.`id` AS WFTChecked,
                                    SUM(pc.`carma`) AS carma
                                  FROM
                                    `user_posts` AS up
                                        LEFT JOIN `user_watches` AS uw ON uw.`object_id` = up.`id` AND uw.`object_type` = '0' AND uw.`user_id` = '" . $this->user->id . "'
                                        LEFT JOIN `carma_posts` AS pc      ON pc.`post_id`  = up.`id` AND pc.`post_type` = 'blog_post',
                                    `users` AS u
                                  WHERE
                                        up.`post_author` = u.`user_id`
                                    AND up.`post_status` = 'publish'
                                    AND up.`post_theme`  = '0' 
                                  GROUP BY up.`id`
                                  $fq
                                  ORDER BY $oq DESC";
                        }
                    }
            
                    $sth = App::db()->query($q);
                    $totalPosts = $sth->rowCount();
            
                    $q .= " LIMIT " . strval((15 * ($page-1))) . ", 15";
                    
                    $sth = App::db()->query($q);
                    $posts = $sth->fetchAll();
                    
                    $postsSummary = array_merge((array) $stPosts, (array) $newPosts, (array) $posts); 
            
                    # счётчик постов с картинками
                    $big_k = 1;
            
                    $PD = array();
            
                    foreach($postsSummary as $k => $row)
                    {
                        $row['post_date']    = datefromdb2textdate($row['post_date'], 1);
                        $row['post_title']   = stripslashes($row['post_title']);
                        $row['post_tizer']   = stripslashes($row['post_tizer']);
                        $row['post_theme']   = postId2theme($row['id'], $this->page->module . '/news');
                        $row['post_carma']   = $carma->getPostCarma('blog_post', $row['id']);
        
                        if ($k <= 5)
                            $PD[] = $row['post_title'];
                        
                        if ($theme != 'all')
                            $block_name = 'post';
                        else {
                            if ($big_k <= 5 && ($row['post_pic']) > 0) {
                                $block_name = 'big';
                                $big_k++;
                            } else 
                                $block_name = 'post';
                        }
        
                        if (!empty($row['post_pic']))
                            $row['path'] = pictureId2path(($block_name == 'big') ? abs($row['post_pic']) : $row['post_pic_small']);
                        
                        if (empty($row['path'])) 
                            $row['path'] = '/images/noimg.gif';
                         
                        $postsResult[$block_name][] = $row;
                    }
        
                    $this->view->setVar("POSTS", $postsResult);
                    
            
                    $tpages = ceil($totalPosts / 15);
                    $pages = pagination($tpages, $page, '/' . $this->page->module . '/' . $action . '/' . $theme, 3, TRUE, $_GET);
        
                    $this->view->setVar('tpages', $tpages); // всего страниц
                    $this->view->setVar('PAGES', $pages);   // пагинация
                    /**
                     * end посты
                     */
        
                    /**
                     * Кол-во моих постов, чтобы показать таб "мои посты" или скрыть
                     */
                    $sth = App::db()->query("SELECT COUNT(*) AS c FROM `user_posts` WHERE `post_author` = '" . $this->user->id . "' AND `post_status` = 'publish'");
                    $row = $sth->fetch();
                    
                    $this->view->setVar('myPosts', $row['c']);
                    
                    /**
                     * subcribe status
                     */
                    if ($this->user->authorized)
                    {
                        if ($this->user->authorized)
                        {
                            $foo = App::db()->query("SELECT COUNT(*) AS c FROM `mail_list_subscribers` WHERE `user_id` = '" . $this->user->id . "' AND `mail_list_id` = '4'")->fetch();
                            
                            if ($foo['c'] >= 1) {
                                $this->view->setVar('unsubscribe');
                            } else {
                                $this->view->setVar('subscribe', TRUE);
                            }
                        }
                    }
                                
                break;
                    
                case 'archive':
                    
                    $sth = App::db()->query("SELECT DISTINCT(EXTRACT(YEAR FROM `post_date`)) AS y FROM `user_posts` WHERE `post_status` = 'publish' HAVING y > 0 ORDER BY y DESC");
                    $years = $sth->fetchAll();
                    
                    $this->view->setVar('years', $years);
                    $this->view->generate('blog/news.sidebar.archive.tpl');
                    exit();
                    
                    break;  
                    
                    
                /**
                 * просмотр поста
                 */
                case 'view':
                
                    if (!empty($this->page->reqUrl[3]))
                    {
                        header("HTTP/1.1 301 Moved Permanently");
                        header("Location: /" . $this->page->module . "/view/" . intval($this->page->reqUrl[2]) . '/');
                        exit();
                    }
                
                    $this->page->tpl = 'blog/view.tpl';
        
                    $this->page->import(array(
                        '/css/jquery.fancybox-1.3.4.css',
                        '/js/jquery.fancybox-1.3.4.pack.js',
                    ));
                                
                    
                    if (!empty($this->page->reqUrl[2]))
                    {
                        // ищем в логах постов не "старый" ли это заголовок какого-то поста
                        // если да - то редирект на последний вариант
                        $sth = App::db()->prepare("SELECT p.`id`, p.`post_slug`
                            FROM `" . blog::$dbtable_logs . "` l, `" . blog::$dbtable . "` p 
                            WHERE l.`result` = :slug AND l.`action` = 'change_title' AND l.`post_id` = p.`id` AND l.`result` != p.`post_slug`
                            LIMIT 1");
                            
                        $sth->execute(array(
                            'slug' => trim($this->page->reqUrl[2]),
                        ));
                        
                        if ($last = $sth->fetch(PDO::FETCH_ASSOC)) {
                            $this->page->go('/' . $this->page->module . '/view/' . $last['post_slug'] . '/', 301);
                        }
                        
                        $sth = App::db()->query("SELECT up.*, 
                                        COUNT(uw.`id`) AS lookers,
                                        COUNT(DISTINCT(s.`user_id`)) AS selected, 
                                        c.`competition_slug`, 
                                        u.`user_id`, 
                                        u.`user_login`, 
                                        u.`user_designer_level`
                                    FROM 
                                        `user_posts` AS up
                                            LEFT JOIN `user_watches` AS uw ON uw.`object_id` = up.`id` AND uw.`object_type` = '0'
                                            LEFT JOIN `competitions` AS c ON c.`blog_id` = up.`id` AND `competition_visible` = 'true',
                                        `users` u
                                            LEFT JOIN `selected` s ON s.`selected_id` = u.`user_id`
                                    WHERE 
                                            up.`" . (is_numeric($this->page->reqUrl[2]) ? 'id' : 'post_slug') . "` = '" . addslashes($this->page->reqUrl[2]) . "' 
                                        AND up.`post_author` = u.`user_id`
                                    GROUP BY up.`id`
                                    LIMIT 1");
            
                        if ($sth->rowCount() == 1)
                        {
                            $post = $sth->fetch();
        
                            if (!empty($post['r301']))
                            {
                                if ($post['r301'] > 0)
                                    $this->page->go('/' . $this->page->module . '/view/' . $post['r301'] . '/', 301);
                                elseif ($post['r301'] < 0)
                                    $this->page->go('/', 301);
                            }
                            
                            // смотрим не прикреплён ли пост к какому-то конкурсу
                            if (!empty($post['competition_slug']))
                            {
                                // считаем открытие поста
                                $sth = App::db()->query("SELECT `id` FROM `user_blog_visits` WHERE `ip` = '" . $_SERVER['REMOTE_ADDR'] . "' AND `post_id` = '" . $post['id'] . "' AND `date` > NOW() - INTERVAL 30 MINUTE");
                                
                                if ($sth->rowCount() == 0)
                                {
                                    App::db()->query("INSERT INTO `user_blog_visits` (`ip`, `post_id`) VALUES ('" . $_SERVER['REMOTE_ADDR'] . "','" . $post['id'] . "')");
                                    //App::db()->query("UPDATE `user_posts` SET `visits` = `visits` + 1 WHERE `id` = '" . $post['id'] . "' LIMIT 1");
                                }
                                
                                $this->page->go('/voting/competition/' . $post['competition_slug'] . '/#competition-post', 301);
                            }
                            
                            if ($post['id'] == $this->page->reqUrl[2])
                            {
                                $this->page->go('/' . $this->page->module . '/view/' . $post['post_slug'] . '/', 301);
                            }
                            
                            $id = $post['id'];
                            
                            if ($post['post_status'] == 'draft' && !in_array($post['id'], array(5120, 5121, 5118)) && ($post['post_author'] != $this->user->id && $this->user->meta->mjteam != 'grand_manager' && $this->user->meta->mjteam != 'super-admin' && $this->user->meta->mjteam != 'developer'))
                            {
                                //$this->page404();
                                
                                header("HTTP/1.1 301 Moved Permanently"); 
                                header('location: /blog/');
                                exit();
                            }
                            
                            
                            if ($post['post_status'] == 'deleted') 
                            {
                                header("HTTP/1.1 301 Moved Permanently"); 
                                header('location: /');
                                exit();
                                $post['post_content'] = '<p class="deleted_post_content">К сожалению пост был удалён его автором</p>';
                            }
                            
                            if ($post['post_status'] == 'blocked'){
                                $this->page->canonical = '/' . $this->page->module . '/';
                            } else {
                                $this->page->canonical = '/' . $this->page->module . '/view/' . $post['post_slug'] . '/';
                            }
                            
                            $headerUserId = $post['post_author'];
                            
                            $this->page->title = stripslashes($post['post_title']) . ' - Блог';
                            
                            $this->view->setVar('userId', $post['post_author']);
                            
                            // Статистика посещений
                            if ($this->user->id != $post['post_author'])
                            {
                                $sth = App::db()->query("SELECT `id` FROM `user_blog_visits` WHERE `ip` = '" . $_SERVER['REMOTE_ADDR'] . "' AND `post_id` = '" . $post['id'] . "' AND `date` > NOW() - INTERVAL 30 MINUTE");
                                
                                if ($sth->rowCount() == 0)
                                {
                                    App::db()->query("INSERT INTO `user_blog_visits` (`ip`, `post_id`) VALUES ('" . $_SERVER['REMOTE_ADDR'] . "','" . $post['id'] . "')");
                                    
                                    $post['visits']++;
                                }
                                
                                // код для подписки или отписки на уведомления
                                $this->view->setVar('subscribe_code', md5($this->user->id . $this->user->user_email . $this->user->user_register_date));
                            }
                            
                            $post['post_title']       = stripslashes($post['post_title']);
                            $post['post_author_name'] = stripslashes($post['user_login']);
                            if ($post['post_date'] != '0000-00-00 00:00:00')
                                $post['post_date']        = datefromdb2textdate($post['post_date']);
                            $post['post_content']     = str_replace('[break]', '', stripslashes($post['post_content']));
                            $post['postCarma']        = $carma->generateCarmaBlock('blog_post', $post['id'], 0, $this->user);
                            $post['post_picture']     = (!empty($post['post_pic_large'])) ? pictureId2path($post['post_pic_large']) : '';
                            
                            if (empty($post['post_picture']))
                                $post['post_picture'] = pictureId2path($post['post_pic']);
        
                            if (!empty($post['post_picture'])) {
                                $this->page->ogImage = (strpos($post['post_picture'], 'http') === 0 ? '' : 'http://www.maryjane.ru') . $post['post_picture'];
                            }   
                            
                            $this->page->ogUrl = '/blog/view/' . $post['post_slug'] . '/';
                                
                            $post['post_theme']       = postId2theme($post['id'], $this->page->module . '/' . $action);
                            $post['user_designer_level'] = designerLevel2Picture($post['user_designer_level']);
        
                            $sth = App::db()->query("SELECT COUNT(*) AS c FROM `user_posts` WHERE `post_author` = '" . $post['post_author'] . "' AND `post_status` = 'publish'");
                            $foo = $sth->fetch();
                            $published = $foo['c'];
        
                            if ($published > 0)
                                $this->page->breadcrump[] = array(
                                    'link' => '/' . $this->page->module . '/user/' . $post['user_login'] . '/',
                                    'caption' => $post['user_login']);
                            
                            $this->page->breadcrump[] = array(
                                'link' => '/' . $this->page->module . '/view/' . $post['id'] . '/',
                                'caption' => $post['post_title']);
                            
                            $PD[] = $post['post_title'];
                            $PD[] = $post['post_author_name'];
                            
                            $PD[] = trim(crop_str(strip_tags($post['post_content']), 100));
                            
                            // theme
                            if (!empty($post['post_theme'])) 
                            {
                                $theme = blog::$themes[$post['post_theme']];
                            }
                            
                            // Tags
                            $sth = App::db()->query("SELECT t.`tag_id`, t.`name`, t.`slug`, t.`raiting`, t.`tag_posts`
                                        FROM `tags_relationships` tr, `tags` t
                                        WHERE tr.`tag_id` = t.`tag_id` AND tr.`object_id` = '" . $post['id'] . "' AND tr.`object_type` = '0' AND t.`tag_id` != '13775'");
        
                            $post_tags = '';
                            
                            $tags = $sth->fetchAll();
                            
                            foreach ($tags AS $tag)
                            {
                                if ($tag['tag_posts'] > 1) {
                                    $post_tags .= '<a href="/blog/tag/' . $tag['slug'] . '/">' . $tag['name'] . '</a>, ';
                                    $tagCounts[$tag['tag_id']] = $tag['tag_posts'];
                                } else
                                    $post_tags .= $tag['name'] . ', ';
                                    
                                //$PD[] = $tag['name'];
                            }
                            
                            $post['post_tags'] = trim(trim($post_tags), ',');
        
                            if (!$theme)
                            {
                                $theme['name'] = 'другое';
                                $theme['slug'] = 'other';
                            }
        
                            $post['post_theme']      = $theme['name'];
                            $post['post_theme_slug'] = $theme['slug'];
        
                            $this->view->setVar('post', $post);
        
                            // next / prev post
                            try
                            {
                                $sth = App::db()->prepare("SELECT  up.`id`, up.`post_title`, up.`post_slug` FROM `user_posts` up, `users` u  WHERE up.`id` > :id and up.`post_status` = 'publish' AND up.`post_author` = u.`user_id` AND u.`user_status` = 'active' order by up.`id` limit 1;");
                                $sth->execute(array('id' => $post['id']));
                                $next = $sth->fetch();
                                
                                if (empty($next)) {
                                    $sth = App::db()->query("SELECT  up.`id`, up.`post_title`, up.`post_slug` FROM `user_posts` up, `users` u  WHERE up.`post_status` = 'publish' AND up.`post_author` = u.`user_id`    AND u.`user_status` = 'active' order by up.`id` limit 1;");
                                    $next = $sth->fetch();
                                }
                                
                                $sth = App::db()->prepare("SELECT  up.`id`, up.`post_title`, up.`post_slug` FROM `user_posts` up, `users` u  WHERE up.`id` < :id and up.`post_status` = 'publish' AND up.`post_author` = u.`user_id` AND u.`user_status` = 'active' order by up.`id` DESC limit 1;");
                                $sth->execute(array('id' => $post['id']));
                                $prev = $sth->fetch();
                                
                                if (empty($prev)) {
                                    $sth = App::db()->Query("SELECT up.`id`, up.`post_title`, up.`post_slug` 
                                                FROM `user_posts` up, `users` u 
                                                WHERE 
                                                        up.`post_status` = 'publish' 
                                                    AND up.`post_author` = u.`user_id` 
                                                    AND u.`user_status` = 'active'
                                                ORDER BY up.`id` DESC 
                                                LIMIT 1;");
                                    $prev = $sth->fetch();
                                }
                                
                                $this->view->setVar('next', $next);
                                $this->view->setVar('prev', $prev);
                            }
                            catch (Exception $e)
                            {
                                printr($e->getMessage());
                            }
                            
                            // Comments
                            if ($post['post_status'] != 'deleted')
                            {
                                $comments = array();
                        
                                function print_comments($parrent, $id, $level, $carma, $level, &$comments, $user) 
                                {
                                    $r = App::db()->query("SELECT 
                                                         c.`comment_id`,
                                                         c.`comment_text` AS `text`,
                                                         c.`comment_date` AS `date`,
                                                         c.`user_id`,
                                                         c.`comment_parent`, 
                                                         c.`comment_visible`,
                                                         u.`user_login`,
                                                         u.`user_designer_level`,
                                                         u.`user_email`,
                                                         u.`user_phone`,
                                                         u.`user_name`,
                                                         u.`user_show_name`
                                                      FROM `comments` AS c, `users` AS u
                                                      WHERE 
                                                             c.`object_id`       = '{$id}'
                                                         AND c.`object_type`     = 'blog' 
                                                         AND c.`user_id`         = u.`user_id` 
                                                         AND c.`comment_parent`  = '{$parrent}'
                                                         AND u.`user_status`     = 'active'
                                                      ORDER BY c.`comment_date` DESC");
                                    
                                    $level++;
                                    
                                    if ($r->rowCount() > 0)
                                    {
                                        foreach ($r->fetchAll() AS $v) 
                                        {
                                            $v['level'] = $level;
                                            $v['class'] = 'level-' . $level;
                                            $v['date'] = ((strpos($_SERVER['HTTP_USER_AGENT'], 'Googlebot')!==false) || (strpos($_SERVER['HTTP_USER_AGENT'],'Mediapartners-Google')!==false) || (strpos($_SERVER['HTTP_USER_AGENT'],'Google Search Appliance')!==false)) ? '' : datefromdb2textdate($v['date']);
                                            $v['user_avatar']         = userId2userGoodAvatar($v['user_id'], 50);
                                            $v['user_designer_level'] = designerLevelToPicture($v['user_designer_level']);
                                    
                                            $car = $carma->getCommentCarma('blog_comment', $v['comment_id']);
                                    
                                            if ($v['comment_visible'] == -1) {
                                                $v['text'] = '<span class="comment-deleted">Комментарий удалён</span>';
                                            } else {
                                                if ($car < carma::$carmaHideComment)
                                                    $v['text'] = "<a href='javascript:void(0);' onclick='showHiddenComment(this);' class='hiddenControl'>Показать</a>&nbsp;&nbsp;<span class='hiddenComment'><br />".stripslashes($v['text']).'</span>';
                                                else
                                                    $v['text'] = stripslashes($v['text']);
                                            }
                                    
                                            $v['carma'] = $carma->generateCarmaBlock('blog_comment', $v['comment_id'], $car, $user);
                                    
                                            if ($good['user_id'] === $v['user_id']) $v['author_comment'] = 'author';
                                            
                                            if (preg_match("/^user[0-9]*/", $v['user_login']) == 1)
                                            {
                                                if (!empty($v['user_name']) && $v['user_name'] != 'NULL' && $v['user_show_name'] == 'true') {
                                                    $v['user_login'] = $v['user_name'];
                                                } elseif (!empty($v['user_email'])) {
                                                    $dog = strpos($v['user_email'], '@');
                                                    $v['user_login'] = (($dog > 3) ? substr($v['user_email'], 0, $dog / 2) : '') . '****' . substr($v['user_email'], $dog);
                                                } else {
                                                    $v['user_login'] = substr($v['user_phone'], 0, strlen($v['user_phone']) - 5) . '****';
                                                }
                                            }
                                            
                                            $comments[] = $v;
                                            
                                            print_comments($v["comment_id"], $id, $level, $carma, $level, $comments, $user);
                                        }
                                    }
                
                                    $level--;
                                }
                                
                                $level = -1;
                                print_comments(0, $id, $level, $carma, $level, $comments, $this->user);
        
                                $this->view->setVar('comments', $comments);
                                $this->view->setVar('COMMENT_TYPE_FORM', 'blog');
                                $this->view->setVar('COMMENT_TO_ID', $id); 
                        
                                // WATCHING
                                $this->view->setVar('WFT', array('watchForThisChecked' => $this->user->isWatching($post['id'], 'blog'), 'to' => $post['id']));
                                
                                
                                // gallery
                                $sth = App::db()->query("SELECT ubg.`big`, p1.`picture_path` AS path, ubg.`small` AS prv_id, p2.`picture_path` AS preview 
                                            FROM `user_blog_gallery` ubg 
                                                    LEFT JOIN `pictures` p1 ON p1.`picture_id` = ubg.`big`
                                                    LEFT JOIN `pictures` p2 ON p2.`picture_id` = ubg.`small` 
                                            WHERE ubg.`post_id` = '" . $post['id'] . "'");
                        
                                if ($sth->rowCount() > 0)
                                {   
                                    $gallery = $sth->fetchAll();
                                
                                    foreach ($gallery AS &$ga)
                                    {
                                        $ga['id'] = implode(',', array($ga['big'], $ga['prv_id'])); 
                                    }
        
                                    $this->view->setVar('gallery', $gallery);
                                }
                                
                                $sth = App::db()->query("SELECT `selected_id` FROM `selected` WHERE `user_id` = '". $this->user->id . "' AND `selected_id` = '" . $post['post_author'] . "'");
        
                                if ($sth->rowCount() == 0)
                                    $this->view->setVar('add_to_selected', TRUE);
                                else
                                    $this->view->setVar('remove_from_selected', TRUE);
                            }
                        }
                        else
                        {
                            //header("HTTP/1.1 301 Moved Permanently"); 
                            //header("Location: /404/");
                            //exit();
                            $this->page404();
                        }
                    }
                    else
                    {
                        header("HTTP/1.1 301 Moved Permanently"); 
                        header('location: /blog/');
                        exit();
                    }
                break;
                
                case 'publish':
                
                    if (!empty($this->page->reqUrl[2]))
                    {
                        $pid = (int) $this->page->reqUrl[2];
                        
                        $sth = App::db()->query("SELECT `post_title` FROM `user_posts` WHERE `id` = '$pid' LIMIT 1");
                        $post = $sth->fetch();
                        
                        $r = App::db()->query("UPDATE `user_posts` 
                                    SET `post_status` = 'publish', `post_date` = '" . NOW . "' 
                                    WHERE `id` = '$pid' AND (`post_author` = '" . $this->user->id . "' OR " . ($this->user->meta->mjteam == 'super-admin') . ") 
                                    LIMIT 1");
                        
                        if ($r->rowCount() == 1) {
                            //newPostNotifications($pid);
                        }
                    }
                    
                    header('location: ' .  $_SERVER['HTTP_REFERER']);
                    exit();
                
                break;
                
                case 'new':
                case 'edit':
                
                    $this->page->noindex = true;
                    $this->page->tpl = 'blog/add.tpl';
                    
                    //if (getLifeTime($this->user->id) >= getVariableValue('blogLifeTime')) 
                    if ($this->user->authorized)
                    {
                        $this->view->setVar('FORM', TRUE);
                    
                        // SAVING
                        if (isset($_POST['post_submit']) || isset($_POST['post_submit_draft']))
                        {
                            $post = $_POST;
        
                            $blog = new blog($_POST['post_id']);
                            
                            // -- GET POST TITLE --
                            if (!isset($_POST['post_title']) || (strlen($_POST['post_title']) < 1)) {
                                $error[] = "Заголовок сообщения не указан";
                            } else {
                                $post_title = substr(trim($_POST['post_title']), 0, 255);
                                $post_title = htmlspecialchars($post_title);
                                $post_title = addslashes($post_title);
                            }
                            
                            // -- GET POST CONTENT --
                            if (!isset($_POST['post_content']) || (strlen($_POST['post_content']) < 1)) {
                                $error[] = "Текст сообщения отсутствует";
                            } else {
                                $post_content = trim($_POST['post_content']);
                                if ($this->user->id != 105091 && $this->user->meta->mjteam != 'super-admin')
                                {
                                    $post_content = strip_JS($post_content);
                                    $post_content = strip_tags($post_content, '<a><b><i><u><em><strong><img><span><p><div><object><param><embed><br><table><tr><td><iframe>');
                                }
                                $post_content = addslashes($post_content);
                            }
                    
                            // -- GET POST TAGS --
                            if (isset($_POST['tags']) && !empty($_POST['tags']) && (count($_POST['tags']) > 0))
                            {
                                //$tags = array_unique($_POST['tags']);
                                $tags = array_unique(explode(', ', $_POST['tags']));
                                foreach ($tags as $k => $tag) {
                                    $tags[$k] = addslashes(trim($tag));
                                }
                            }
                            else
                                $error[] = 'Теги к сообщению отсутствуют';
                            
                            // -- ПРОВЕРКА НА ЗАНЯТОСТЬ ЗАГОЛОВКА ПОСТА --
                            $sth = App::db()->prepare("SELECT `id` FROM `user_posts` WHERE `post_slug` = :slug AND `id` != '" . $_POST['post_id'] . "' AND `post_status` IN ('publish', 'draft') LIMIT 1");
                            
                            $sth->execute(array(
                                'slug' => blog::rus2lat(trim($_POST['post_title']))
                            ));
                            
                            if ($sth->fetch()) {
                                $error[] = 'Пост с таким заголовком уже существует';
                            }
                            
                            // -- ЗАГРУЗКА ТИТУЛЬНОЙ КАРТИНКИ --
                            if (empty($_FILES['pic']['error']))
                            {
                                $minPicW = 300;
                                $mixPicH = 200;
                                $folder  = IMAGEUPLOAD . DS . 'blogs' . DS . date('Y/m/d/');
                                
                                $pic = catchFileNew('pic', $folder, '', 'gif,png,jpeg,jpg', $minPicW, $mixPicH);
                                
                                if ($pic['status'] == 'error')
                                {
                                    $error[] = $pic['message'];
                                }
                                else 
                                {
                                    $post['post_pic']     = $pic['id'];
                                    $post['post_picture'] = $pic['path'];
                                    
                                    $i = new Imagick(ROOTDIR . $pic['path']);
                                    
                                    // если есть возможность делаем большую картинку, 500 * Xxx
                                    if ($i->getimagewidth() >= 550)
                                    {
                                        $i->scaleImage(550, (550 / $i->getimagewidth()) * $i->getimageheight());
                                    }
                                    
                                    $i->setImageFormat('jpeg');
                                    $post['post_picture_large'] = $folder . md5(uniqid()) . '.jpeg';
                                    $i->writeImage(ROOTDIR . $post['post_picture_large']);
                                    
                                    // средняя картинка, 300 * 200
                                    $i->scaleImage(300, (300 / $i->getimagewidth()) * $i->getimageheight());
                                    $c = new Imagick();
                                    $c->newImage(300, 200, new ImagickPixel("white") );
                                    $c->compositeImage($i, imagick::COMPOSITE_OVER, 300 / 2 - $i->getimagewidth() / 2, 200 / 2 - $i->getimageheight() / 2);
                                    $c->setImageFormat('jpeg');
                                    $c->writeImage(ROOTDIR . $pic['path']);
                                    
                                    // мелкая картинка, 100 * 67
                                    $post['post_picture_small'] = $folder . md5(uniqid()) . '.jpeg';
                                    $c->scaleImage(100, (100 / $c->getimagewidth()) * $c->getimageheight());
                                    $c->writeImage(ROOTDIR . $post['post_picture_small']);
                                }
                            }
        
                            if (isset($_POST['post_visible']))
                                $visible = (int) $_POST['post_visible'];
                            else
                                $visible = 0;
                            
                            if (isset($error) && !empty($error))
                            {
                                $this->view->setVar('error', $error);
                                $post['post_title']   = htmlspecialchars(stripslashes($post['post_title']));
                                $post['post_content'] = stripslashes($post['post_content']);
                            }
                            else
                            {
                                if (isset($_POST['post_submit'])) 
                                    $post_status = 'publish';
                                else
                                    $post_status = 'draft';
                                
                                // SAVE NEW
                                if (!isset($_POST['post_id']) || $_POST['post_id'] == '')
                                {
                                    $query = "INSERT
                                              INTO `user_posts`
                                              SET
                                                `post_author`    = '". $this->user->id ."',
                                                `post_content`   = '". $post_content ."',
                                                `post_tizer`     = '". addslashes(strip_tags($_POST['post_tizer'])) ."',
                                                `post_title`     = '". $post_title ."',
                                                `post_theme`     = '". intval($_POST['theme']) ."',
                                                `post_slug`      = '". blog::rus2lat(trim($_POST['post_title'])) ."',
                                                `post_pic`       = '". intval($post['post_pic']) ."',
                                                `post_pic_large` = '". (($post['post_picture_large']) ? file2db($post['post_picture_large']) : 0) ."',
                                                `post_pic_small` = '". file2db($post['post_picture_small']) ."',
                                                `post_status`    = 'draft', 
                                                `post_visible`   = '{$visible}', 
                                                `poll_id`        = '". intval($_POST['poll_id']) ."'";
                                    App::db()->query($query);
                                    
                                    $last_id = App::db()->lastInsertId();
                    
                                    // WATCH
                                    $this->user->watch($last_id, 'blog');
                    
                                    if ($post_status == 'publish')
                                    {
                                        // Отмечаем дату последнего поста автора
                                        $this->user->change(array('user_last_post' => NOW));
                                    }
                                    
                                    // gallery
                                    if ($_POST['galleryfile'])
                                    {
                                        foreach ($_POST['galleryfile'] as $f) 
                                        {
                                            $pic = explode(',', $f);
                                            App::db()->query(sprintf("INSERT INTO `user_blog_gallery` SET `post_id` = '%d', `big` = '%d', `small` = '%d'", $last_id, $pic[0], $pic[1]));
                                        }
                                    }
                                    
                                    // уведомляем модераторов
                                    App::mail()->send(6199, 350, array(
                                        'post_author' => $this->user->user_login,
                                        'post_id'     => $last_id,
                                        'post_title'  => stripslashes($post_title)
                                    ));
                                }
                                // EDIT POST
                                else
                                {
                                    $post['post_slug'] = blog::rus2lat(trim($_POST['post_title'])); 
                                    
                                    // заголовок поста изменился
                                    // пишем это в лог
                                    if ($post['post_slug'] != $blog->post_slug) {
                                        $blog->log('change_title', $blog->post_slug, '', $this->user);
                                    }
                                    
                                    $last_id = intval($_POST['post_id']);
                                    
                                    $foo = App::db()->query("SELECT `post_date` FROM `user_posts` WHERE `id` = '$last_id' LIMIT 1")->fetch();
                                    
                                    $pdate = $foo['post_date'];
                                    
                                    $query = "UPDATE
                                                `user_posts`
                                              SET
                                                `post_title`   = '$post_title', 
                                                `post_theme`   = '". intval($_POST['theme']) ."',
                                                `post_slug`    = '". $post['post_slug'] ."',
                                                `post_content` = '$post_content', 
                                                `post_tizer`     = '". addslashes(strip_tags($_POST['post_tizer'])) ."',
                                                `poll_id`      = '". intval($_POST['poll_id']) ."',
                                                `post_visible` = '{$visible}'
                                                 " . (($post['post_pic'])           ? ", `post_pic` = '". intval($pic['id']) ."'" : '') . "
                                                 " . (($post['post_picture_large']) ? ", `post_pic_large` = '". file2db($post['post_picture_large']) . "'" : '') . "
                                                 " . (($post['post_picture_small']) ? ", `post_pic_small` = '". file2db($post['post_picture_small']) . "'" : '') . "
                                                 " . (($pdate != '0000-00-00 00:00:00') ? ", `post_status`  = '$post_status' " : '') . "
                                              WHERE
                                                    `id` = '{$last_id}'
                                                AND (`post_author` = '" . $this->user->id . "'" . (in_array($this->user->meta->mjteam, array('super-admin', 'developer')) ? " OR 1" : '') . ")
                                              LIMIT 1";
                                              
                                    App::db()->query($query);
                                }
                    
                                // -- SAVE TAGS --
                                $r = App::db()->query("DELETE FROM `tags_relationships` WHERE `object_id` = '" . $last_id . "' AND `object_type` = '0' LIMIT 11");
                                
                                foreach ($tags as $tag)
                                {
                                    if (!empty($tag)) 
                                    {
                                        $sth = App::db()->query("SELECT IF(`synonym_id_blog` > 0, `synonym_id_blog`, `tag_id`) AS tag_id FROM `tags` WHERE `name` like '". $tag ."'");
                                        
                                        // new tag is not isset
                                        if ($sth->rowCount() == 0) {
                                            $r      = App::db()->query("INSERT INTO `tags` (`name`,`slug`) VALUES ('".$tag."','". toTranslit($tag)."')");
                                            $tag_id = App::db()->lastInsertId();
                                            
                                            // если совпали слаги у тегов
                                            if (empty($tag_id)) {
                                                $sth = App::db()->query("SELECT IF(`synonym_id_blog` > 0, `synonym_id_blog`, `tag_id`) AS tag_id FROM `tags` WHERE `slug` like '". toTranslit($tag) ."'");
                                                $foo = $sth->fetch();
                                                $tag_id = $foo['tag_id'];
                                            }
                                            
                                        // new tag already isset
                                        } else {
                                            $row = $sth->fetch();
                                            $tag_id = $row['tag_id']; 
                                        }
                    
                                        $r = App::db()->query("INSERT INTO `tags_relationships` (`object_id`, `tag_id`, `object_type`) VALUES ($last_id, $tag_id, '0')");
                                    }
                                }
                                
                                // SAVE main tag
                                //$r = App::db()->query("INSERT INTO `tags_relationships` (`object_id`, `tag_id`, `object_type`) VALUES ('$last_id', '$post_theme', '0')");
                                
                                header("location: /" . $this->page->module . "/view/" . $last_id . '/');
                                exit();
                            }
                        }
                        
                        if ($action == 'edit' && !empty($this->page->reqUrl[2]))
                        {
                            $sth = App::db()->query("SELECT * FROM `user_posts` WHERE `id` = '" . intval($this->page->reqUrl[2]) . "' AND (`post_author` = '" . $this->user->id . "'" . (in_array($this->user->meta->mjteam, array('super-admin', 'developer')) ? ' || 1' : '') . ") LIMIT 1");
        
                            if ($sth->rowCount() > 0)
                            {
                                $post = $sth->fetch();
        
                                $post['post_title']   = stripslashes($post['post_title']);
                                $post['post_content'] = stripslashes($post['post_content']);
                                $post['post_picture'] = pictureId2path($post['post_pic']);
        
                                $headerUserId = $post['post_author'];
        
                                // theme
                                if (!empty($post['post_theme']))
                                {
                                    $post['theme'] = $post['post_theme'];
                                }
                                
                                // tags
                                $sth = App::db()->query("SELECT t.`tag_id`, t.`name`, t.`raiting` FROM `tags_relationships` tr, `tags` t WHERE tr.`tag_id` = t.`tag_id` AND tr.`object_id` = '" . $post['id'] . "' AND tr.`object_type` = '0'");
        
                                if ($sth->rowCount() > 0)
                                {
                                    $tags = $sth->fetchAll();
        
                                    foreach ($tags AS $tag)
                                    {
                                        //else
                                        //{
                                        $post['tags'][$tag['tag_id']] = stripslashes($tag['name']);
                                        //}
                                    }
                                }
                                
                                $post['post_tags'] = implode(', ', $post['tags']);
        
                                // gallery
                                $sth = App::db()->query("SELECT ubg.`big`, p1.`picture_path` AS path, ubg.`small` AS prv_id, p2.`picture_path` AS preview
                                            FROM `user_blog_gallery` ubg
                                                    LEFT JOIN `pictures` p1 ON p1.`picture_id` = ubg.`big`
                                                    LEFT JOIN `pictures` p2 ON p2.`picture_id` = ubg.`small`
                                            WHERE ubg.`post_id` = '" . $post['id'] . "'");
        
                                if ($sth->rowCount() > 0)
                                {
                                    $gallery = $sth->fetchAll();
        
                                    foreach ($gallery AS &$ga)
                                    {
                                        $ga['id']   = implode(',', array($ga['big'], $ga['prv_id']));
                                        $ga['hash'] = sha1(SALT . $this->user->id . implode(',', array($ga['big'], $ga['prv_id'])));
                                    }
                                }
                            }
                            else
                            {
                                header('location: /');
                                exit();
                            }
                        }
                        // новый пост
                        else
                        {
                            $headerUserId = $this->user->id;
                            
                            if (!empty($_COOKIE['poll_id'])) 
                            {
                                $post['poll_id'] = intval($_COOKIE['poll_id']);
                            }
                            
                            $this->page->breadcrump[] = array(
                                'link' => '/' . $this->page->module . '/new/',
                                'caption' => 'Создать пост');
                                
                            $gallery = $_SESSION['blog']['gallery'];
                        }
                        
                        //printr($gallery);
        
                        $this->view->setVar('post', $post);
                        $this->view->setVar('gallery', $gallery);
                        $this->view->setVar('userId', $headerUserId);
                            
                        // Themes
                        $this->view->setVar('themes', blog::$themes);
                    }
                    
                    include('profile.quickinfo.php');
                    
                break;
                
                case 'delete':
                    if (!empty($this->page->reqUrl[2])) 
                    {
                        $post_id = intval($this->page->reqUrl[2]);
            
                        $r = App::db()->query("SELECT `id` FROM `user_posts` WHERE `id` = '$post_id' AND (`post_author` = '" . $this->user->id . "' OR '" . $this->user->id . "' = '6199')  LIMIT 1");
                        
                        if ($r->rowCount() == 1)
                        {
                            App::db()->query("UPDATE `user_posts` SET `post_status` = 'deleted' WHERE `id` = '$post_id' LIMIT 1");
                        }
                    }
                    
                    header('location: /blog/user/' .  $this->user->user_login . '/');
                    exit();
                break;
                
                case 'delete_post_pic':
                
                    if (!empty($this->page->reqUrl[2])) 
                    {
                        $post_id = intval($this->page->reqUrl[2]);
                        
                        $r = App::db()->query("SELECT `post_pic`, `post_pic_large`, `post_pic_small` FROM `user_posts` WHERE `id` = '$post_id' AND (`post_author` = '" . $this->user->id . "' OR '" . in_array($this->user->id, array(6199, 27278)) . "')  LIMIT 1");
                        
                        if (1 == $r->rowCount())
                        {
                            $row = $r->fetch();
                            
                            deletePicture($row[0]);
                            deletePicture($row[1]);
                            deletePicture($row[2]);
                            
                            App::db()->query("UPDATE `user_posts` SET `post_pic` = '0', `post_pic_large` = '0', `post_pic_small` = '0' WHERE `id` = '$post_id' LIMIT 1");
                        }
                    }
                    
                    header('location: ' .  $_SERVER['HTTP_REFERER']);
                    exit();
                
                break;
                
                case 'block':
                    
                    if (!empty($this->page->reqUrl[2])) 
                    {
                        $post_id = intval($this->page->reqUrl[2]);
            
                        $r = App::db()->query("SELECT `id`, `post_status` FROM `user_posts` WHERE `id` = '$post_id' AND (`post_author` = '" . $this->user->id . "' OR '" . $this->user->id . "' = '6199')  LIMIT 1");
                        
                        if (1 == $r->rowCount())
                        {
                            $post = $r->fetch();
                            
                            if ($post[1] != 'blocked')
                                App::db()->query("UPDATE `user_posts` SET `post_status` = 'blocked' WHERE `id` = '$post_id' LIMIT 1");
                            else
                                App::db()->query("UPDATE `user_posts` SET `post_status` = 'publish' WHERE `id` = '$post_id' LIMIT 1");
                        }
                    }
                    
                    header('location: ' . $_SERVER['HTTP_REFERER']);
                    exit();
                    
                break;
                
                /**
                 * список постов конкретного пользователя
                 */
                case 'user':
        
                    $this->page->tpl = 'blog/list.tpl';
                    
                    if (empty($this->page->reqUrl[2]) && $this->user->authorized)
                    {
                        header('location: /blog/user/' . $this->user->user_login . '/');
                        exit();
                    } 
                    elseif (!empty($this->page->reqUrl[2])) 
                    {
                        $r = App::db()->query("SELECT * FROM `users` WHERE (`user_id` = '" . addslashes(trim($this->page->reqUrl[2])) . "' OR `user_login` = '" . addslashes(trim($this->page->reqUrl[2])) . "') LIMIT 1");
        
                        if ($r->rowCount() == 0) {
                            header("HTTP/1.1 301 Moved Permanently"); 
                            header('location: /blog/');
                            exit();
                        }
        
                        $user = $r->fetch();    
        
                        if ($this->page->reqUrl[2] == $user['user_id'])
                        {
                            header("HTTP/1.1 301 Moved Permanently");
                            header('location: /blog/user/' . $user['user_login'] .  '/');
                            exit();
                        }
        
                        $headerUserId = $userId = $user['user_id'];
                    }
                    else
                    {
                        header('location: /');
                        exit();
                    }
                    
                    if ($userId == $this->user->id && $this->page->reqUrl[3] != 'drafts') {
                        $see_draft = "up.`post_status` != 'deleted' AND up.`post_status` != 'draft'";
                    }
                    elseif (($userId == $this->user->id || $this->user->meta->mjteam == 'super-admin') && $this->page->reqUrl[3] == 'drafts') {
                        $see_draft = "up.`post_status` = 'draft'";
        
                        $this->page->noindex = true;
                    }
                    else {
                        $see_draft = "up.`post_status` != 'deleted' AND up.`post_status` != 'draft'";
                    }
                    
                    if ($this->user->authorized)
                    {
                        if (getDateDiff($this->user->user_register_date) / 24 >= getVariableValue('blogLifeTime')) {
                            $this->view->setVar('new_post_link_ok', TRUE);
                        }
                    }
        
                    $this->view->setVar('userId', $userId);
                    $this->view->setVar('user', $user);
        
                    $this->page->title = 'Блог - ' . stripslashes($user['user_login']) . ' - дизайнерские футболки';
                    
                    if (!empty($this->page->reqUrl[3]) && $this->page->reqUrl[3] != 'drafts')
                        $page = intval($this->page->reqUrl[3]);
                    else 
                        $page = 1;
                    
                    $onPage = 10;
        
                    
                    $sth = App::db()->query("SELECT SQL_CALC_FOUND_ROWS 
                                    up.`id`, up.`post_date`, up.`post_title`, up.`post_author`, up.`post_slug`, up.`post_sticked`, up.`post_status`, up.`comments`, up.`visits`, u.`user_id`, u.`user_login`, uw.`id` AS WFTChecked, p.`picture_path` AS path
                                FROM `users` AS u, `user_posts` AS up
                                    LEFT JOIN `user_watches` AS uw ON uw.`object_id` = up.`id` AND uw.`object_type` = '0' AND uw.`user_id` = '" . $this->user->id . "'
                                    LEFT JOIN `pictures` p ON p.`picture_id` = up.`post_pic_small`
                                WHERE up.`post_author` = '". $userId ."' AND u.`user_id` = up.`post_author` AND up.`r301` = '0' AND ($see_draft) " 
                                . 
                                ((!in_array($this->user->id, array(6199, 27278, 105091))) ? " AND up.`post_status` != 'blocked'" : '')
                                .
                                (($this->user->id == $userId || $this->user->meta->mjteam) ? "" : "AND up.`post_theme` != '11'") 
                                .
                                "
                                ORDER BY up.`post_date` DESC
                                LIMIT " . (($page - 1) * $onPage) . ", $onPage");
        
                    if ($sth->rowCount() == 0 && $this->user->id != $userId) 
                    {
                        if ($userId != 45268) {
                            header("HTTP/1.1 301 Moved Permanently"); 
                            header('location: /blog/');
                            exit();
                        }
                        
                        $this->page->noindex = TRUE;
                    }
                    else
                    {
                        $posts = $sth->fetchAll();
        
                        $sth = App::db()->query("SELECT FOUND_ROWS() AS c");
                        $foo = $sth->fetch();
                        $totalposts = $foo['c'];
                        
                        $this->view->setVar('tpages', ceil($totalposts / $onPage));
                        $this->view->setVar('PAGES', pagination(ceil($totalposts / $onPage), $page, "/" . $this->page->module . "/user/" . $user['user_login'] , 10));
                        
                        foreach($posts AS &$pp)
                        {
                            $pp['post_date']    = datefromdb2textdate($pp['post_date']);
                            $pp['post_title']   = stripslashes($pp['post_title']);
                            $pp['post_tags']    = postId2tags_sring($pp['id'], 1);
                            $pp['post_theme']   = postId2theme($pp['id'], $this->page->module . '/news');
                            $pp['postCarma']    = $carma->generateCarmaBlock('blog_post', $pp['id'], 0, $this->user);
                            $pp['WFTChecked']   = (empty($pp['WFTChecked']) ? '0' : '1');
                            $pp['WFTTitle']     = (empty($pp['WFTChecked']) ? 'Следить за комментариями' : 'Слежу за комментариями');
                            $pp['class']        = ($pp['post_sticked'] == '1') ? 'activeBorder' : ''; 
                            
                            $user_login = $pp['user_login'];
                        }
                
                        $this->view->setVar('posts', $posts);
                        
                        $this->page->breadcrump[] = array(
                            'link'    => '/' . $this->page->module . '/' . $userId . '/',
                            'caption' => $user_login);
        
                        // извлекаем количество ченовиков
                        $sth = App::db()->query("SELECT COUNT(up.`id`) AS c
                                    FROM `user_posts` AS up
                                    WHERE up.`post_author` = '" . $userId . "' AND up.`post_status` = 'draft' AND up.`r301` = '0'
                                    ORDER BY up.`post_date` DESC");
                        
                        $foo = $sth->fetch();
                        
                        $drafts = (int) $foo['c'];
        
                        $this->view->setVar('drafts', $drafts);
                    }
                    
                    $this->view->setVar('userInfo', $user);
                    include('profile.quickinfo.php');
                    
                break;
                
                case 'rss':
        
                    $Rss= new CRss();
                    
                    $Rss->Title             = "Maryjane Rss";
                    $Rss->Link              = mainUrl;
                    $Rss->Copyright         = "© Копирайт.";
                    $Rss->Description       = "Персональные блоги Maryjane.ru";
                    $Rss->Category          = "Креативная одежда";
                    $Rss->Language          = "ru";
                    
                    $Rss->ManagingEditor    = "info@maryjane.ru";
                    $Rss->WebMaster         = "info@maryjane.ru";
                    
                    $Rss->Query="SELECT * FROM `user_posts` WHERE `post_status` = 'publish' AND `id` NOT IN ('5147') ORDER BY `post_date` DESC LIMIT 0,40";
                    
                    $Rss->LastBuildDate=date("r");
                    
                    // получаем последнюю дату публикации
                    $line = App::db()->query("SELECT `post_date` FROM `user_posts` WHERE `post_status` = 'publish' ORDER BY `post_date` desc LIMIT 0,1")->fetch();
        
                    $Date = date("r",strtotime($line["post_date"]));
                    
                    $Rss->LastBuildDate=$Date;
                    $Rss->PubDate=$Rss->LastBuildDate;
                    
                    $Rss->PrintHeader();
                    $Rss->Query();
                    
                    foreach ($Rss->Result AS $line)
                    {   
                        $Title = stripslashes($line["post_title"]);
                        
                        if ($pos = iconv_strpos($line['post_content'], '[break]', 'UTF-8'))
                            $cut = iconv_substr(stripslashes($line['post_content']), 0, $pos, 'UTF-8');
                        else
                            $cut = stripslashes($line['post_content']);
                        
                        $Description     = '<p>Автор: ' . userId2userLogin($line['post_author'], 1) . "</p>";
                        $Description    .= '<p>Опубликовано: ' . datefromdb2textdate($line['post_date']) . '</p>';
                        $Description    .= $cut;
                        $Description    .= '<p><a href="' . mainUrl . "/blog/view/" . $line['post_slug'] . '/">Прочитать и обсудить</a></p>';
                        
                        $Link           = mainUrl . "/blog/view/" . $line["post_slug"] . '/';
                        $PubDate        = date("r",strtotime($line["post_date"]));
                        $Category       = "Последние посты";
                        
                        $Rss->PrintBody($Title,$Link,$Description,$Category,$PubDate);
                    }
                    
                    $Rss->PrintFooter();
                    
                    exit();
                break;
                
                case 'addTag':
                    $pid = intval($_GET['pid']);
                    $tag = addslashes($_GET['tag']);
                    
                    if (postId2author($pid) == $this->user->id)
                    {
                        $r = App::db()->query("SELECT `tag_id` FROM `tags` WHERE `name` = '$tag'");
                        
                        if ($r->rowCount() == 0)
                        {
                            $r = App::db()->query("INSERT INTO `tags` (`name`,`slug`) VALUES ('$tag', '".toTranslit($tag)."')");
                            $tid = App::db()->lastInsertId();
                        }
                        else
                        {
                            $foo = $r->fetch();
                            $tid = $foo['tag_id'];
                        }
                        
                        $r = App::db()->query("SELECT * FROM `tags_relationships` WHERE `object_id` = '$pid' AND `tag_id` = '$tid' AND `object_type` = '0'");
                        
                        if ($r->rowCount() == 0)
                        {
                            App::db()->query("INSERT INTO `tags_relationships` (`object_id`, `tag_id`, `object_type`) VALUES ('$pid', '$tid', '0')");
                            exit("$tid");
                        }
                        else
                            exit('already');
                    }
                break;
                
                case 'removeTagFromPost':
                    $pid = intval($_GET['pid']);
                    $tid = intval($_GET['tid']);
                    
                    if (postId2author($pid) == $this->user->id) {
                        App::db()->query("DELETE FROM `tags_relationships` WHERE `object_id` = '$pid' AND `tag_id` = '$tid' AND `object_type` = '0' LIMIT 1");
                        exit('removed');
                    }
                break;
                
                /**
                 * сохранение тегов "налету"
                 */
                case 'saveTags':
                    
                    $post_id = intval($this->page->reqUrl[2]);
                    $tags    = array_slice(array_unique($_POST['tags']), 0, 10);
        
                    foreach ($tags as $k => $tag)
                    {
                        $tag = trim($tag);
                        if (empty($tag) || trim($tag) == $this->user->user_login)
                            unset($tags[$k]);
                    }
        
                    if (count($tags) > 0 && !empty($post_id) && postId2author($post_id) == $this->user->id)
                    {
                        App::db()->query("DELETE FROM `tags_relationships` WHERE `object_id` = '$post_id' AND `object_type` = '0'");
                        
                        foreach ($tags as $tag)
                        {
                            $tag = addslashes(trim($tag));
                            
                            $sth = App::db()->Query("SELECT IF(`synonym_id_blog` > 0, `synonym_id_blog`, `tag_id`) AS tag_id FROM `tags` WHERE `name` like '". $tag ."'");
                            
                            if ($sth->rowCount() == 0)
                            {
                                App::db()->query("INSERT INTO `tags` (`name`,`slug`) VALUES ('". $tag . "','" . toTranslit($tag) . "')");
                                $tag_id = App::db()->lastInsertId();
                                
                                // если совпали слаги у тегов
                                if (empty($tag_id)) {
                                    $sth = App::db()->query("SELECT IF(`synonym_id_blog` > 0, `synonym_id_blog`, `tag_id`) AS tag_id FROM `tags` WHERE `slug` like '". toTranslit($tag) ."'");
                                    $foo = $sth->fetch();
                                    $tag_id = $foo['tag_id'];
                                }
                            }
                            else
                            {
                                $res    = $sth->fetch();
                                $tag_id = $res['tag_id'];
                            }
        
                            $r = App::db()->query("INSERT INTO `tags_relationships` (`object_id`, `tag_id`, `object_type`) VALUES ('$post_id', '$tag_id', '0')");
                        }
                    }
                    
                    exit();
                    
                break;
            
                /**
                 * получить список "похожих" постов 
                 * Ajax
                 */
                case 'related':
                
                    if (!empty($this->page->reqUrl[2]))
                    {
                        try
                        {
                            $b = new blog($this->page->reqUrl[2]);
                            
                            //printr($b);
                            
                            $sth = App::db()->prepare("SELECT t.`tag_id`, t.`name`, t.`slug`, t.`raiting`, t.`tag_posts`
                                        FROM `tags_relationships` tr, `tags` t
                                        WHERE tr.`tag_id` = t.`tag_id` AND tr.`object_id` = :id AND tr.`object_type` = '0' AND t.`raiting` != '100' AND t.`tag_posts` > 1 AND t.`tag_id` != '13775'");
            
                            $sth->execute(array(
                                'id' => $this->page->reqUrl[2],
                            ));
            
                            $tags = $sth->fetchAll();
                        
                            foreach ($tags AS $tag)
                            {
                                $tagCounts[$tag['tag_id']] = $tag['tag_posts'];
                            }
                            
                            $rTag = array_search(min($tagCounts), $tagCounts);
            
                            $sth = App::db()->prepare("SELECT up.`id`, up.`post_title`, u.`user_id`, u.`user_login`, up.`post_date`, up.`post_slug`, up.`post_theme`
                                        FROM `user_posts` up, `users` u, `tags_relationships` tr 
                                        WHERE 
                                                up.`post_author` = u.`user_id` 
                                            AND tr.`object_id` = up.`id` 
                                            AND tr.`object_type` = '0' 
                                            AND tr.`tag_id` = :rtag 
                                            AND up.`post_status` = 'publish' 
                                            AND up.`id` != :this 
                                            AND `r301` = '0' 
                                            AND up.`post_theme` != '11'
                                            " . (!empty($b->post_theme) ? "AND up.`post_theme` = '" . $b->post_theme . "'" : '') . "
                                        GROUP BY up.`id`
                                        ORDER BY up.`post_date` DESC
                                        LIMIT 5");
                                        
                            $sth->execute(array(
                                'rtag' => $rTag,
                                'this' => $this->page->reqUrl[2],
                            ));
                            
                            $rPosts = $sth->fetchAll();
                            
                    foreach ($rPosts as $k => $v) {
                        $rPosts[$k]['post_title'] = stripslashes($v['post_title']);
                    }
                    
                    if (count($rPosts) < 5)
                    {
                        $sth = App::db()->query("SELECT up.`id`, up.`post_title`, up.`post_author`, up.`post_slug`, COUNT(DISTINCT(v.`ip`)) AS c
                                    FROM `user_blog_visits` v, `user_posts` up
                                    WHERE 
                                            v.`date` >= NOW() - INTERVAL 6 MONTH
                                        AND v.`post_id` = up.`id`
                                        AND up.`post_status` = 'publish'
                                    GROUP BY v.`post_id`
                                    ORDER BY c DESC
                                    LIMIT " . (5 - count((array) $rPosts)));
                        
                        $popular = $sth->fetchAll();    
                                
                        foreach ($popular as &$pp) {
                            $pp['post_title'] = stripslashes($pp['post_title']);
                        }           
                                    
                        $rPosts = array_merge((array) $rPosts, (array) $popular);
                    }
                    
                    $this->view->setVar('relatedPosts', $rPosts);
                    $this->view->generate('blog/view.related.tpl');
                    exit();
                    //$out = array('status' => 'ok', 'posts' => $rPosts);
                }
                catch (Exception $e)
                {
                    $out = array('status' => 'error', 'message' =>$e->getMessage());
                }
            }
            else 
            {
                $out = array('status' => 'error', 'message' => 'Не указан пост');
            }
        
            exit(json_encode((array) $out));
            
            break;
            
        default:
        
            $this->page404();
        
                break;
            }
    
            //printr($PD);
            
            if (count($PD) > 0)
                $this->page->description = htmlspecialchars(implode(', ', $PD));
            
            if (count($PT) > 0)
                $this->page->title =  implode(', ', $PT);
            
            $this->view->generate('index.tpl');
        }
    }