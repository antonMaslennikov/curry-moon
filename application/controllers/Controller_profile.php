<?
    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
    use \application\models\carma AS carma;
    use \application\models\user AS user;
    use \application\models\good AS good;
    
    use \Exception;
    
    class Controller_profile extends \smashEngine\core\Controller
    {
        public function action_index()
        {
            $this->page->tpl = 'profile/index.tpl';
            $this->page->footer_tpl = 'order/footer.tpl';
            
            $this->page->import(array(
                '/css/catalog/styles.css',
                '/js/vote_catalog.js',
            ));
            
            if (empty($this->page->reqUrl[1]) && $this->user->authorized) {
                $this->page->go('/' . $this->page->module . '/' . $this->user->id . '/', 301);  // Профиль смотрит хозяин
            } elseif (!empty($this->page->reqUrl[1])) {
                $headerUserId = $user_id = intval($this->page->reqUrl[1]);     // Профиль смотрит посетитель
            } else {
                $this->page->go('/');                                              // Неавторизованный идёт в свой профиль
            }
            
            $carma = new carma;
            
            $this->view->setVar('userId', $user_id);
            $this->view->setVar('user_id', $user_id);
            
            
            $sth = App::db()->prepare("SELECT u.*, MAX(s.`session_time`) AS lv
                        FROM `users` AS u
                            LEFT JOIN `sessions` AS s ON (s.`user_id` = u.`user_id` AND s.`user_id` <> '-1')  
                        WHERE 
                            (u.`user_id` = :user || u.`user_login` = :user) AND u.`user_status` != 'deleted' AND u.`user_status` != 'banned'
                        LIMIT 1");
                    
            $sth->execute(array('user' => $user_id));   
                    
            if ($user = $sth->fetch())
            {
                if ($user['user_id'])
                {
                    // если к профилю обратились перез логин пользователя
                    if ($user_id != $user['user_id'])
                    {
                        // то кидаем на профиль через номер
                        $this->page->go('/' . $this->page->module . '/' . $user['user_id'] . '/', 301);
                    }
                    
                    $user_id = $user['user_id'];
                    
                    $U = new user($user_id);
                    
                    if ($U->user_is_fake == 'false')
                    {
                        $user['user_login']   = $U->user_login;
                        $user['user_name']    = ($U->user_show_name == 'true') ? $U->user_name : 'скрытo';
                        $user['user_email']   = ($U->user_show_email == 'true') ? $U->user_email : 'скрыт';
                        $user['user_sex']     = ($U->user_sex == 'male') ? 'мужчина' : 'женщина';
                        $user['city']         = (!empty($U->user_city)) ? cityId2name($U->user_city) : "&nbsp;";
                        $user['country']      = countryId2countryName(cityId2country($U->user_city));
                        $user['registerDate'] = ($user['user_register_date'] != "0000-00-00") ? datefromdb2textdate($user['user_register_date']) : "&nbsp;";
                        $user['birthday']     = (!empty($U->user_birthday) && $U->user_birthday != '0000-00-00') ? datefromdb2textdate($U->user_birthday) : "неизвестно";
                        $user['lastVisit']    = ($user['lv']) ? datefromdb2textdate(date("Y-m-d", $user['lv'])) : "&nbsp;";
                        $user['user_designer_level']    = designerLevel2Picture($user['user_designer_level']);
                        $user['user_personal_discount'] = intval($user['user_personal_discount']); 
                        $user['buyerDiscount']          = $U->buyerLevel2discount();
                        $user['buyerDiscountColor']     = userId2buyLevelColor($user_id);
                        $user['pretendents']            = $U->getUserGoodsCount(array('printed', 'pretendent'));
                        
                        $this->page->breadcrump[] = array(
                            'link' => '/people/designers/',
                            'caption' => 'Все авторы');
                        
                        $this->page->breadcrump[] = array(
                            'link' => '/catalog/' . $user['user_login'] . '/',
                            'caption' => $user['user_login']);
                        
                        // Personal discount
                        $bdiscount = userId2userBirthdayDiscount($user['user_id']);
                        
                        if ($bdiscount > $user['user_personal_discount'])
                            $user['user_personal_discount'] = $bdiscount;
                        
                        // User links
                        if (!empty($U->user_url)) {
                            $user_url = str_replace(array('http://', 'https://'), '', $U->user_url);
                            $url = parse_url($U->user_url);
                            if (!$url['scheme'])
                                $url['scheme'] = 'http';
                            if (!empty($user_url)) $user_url = "<p><a href='" . $url['scheme'] . "://" . stripslashes($user_url)."' target='_blank' " . ((strpos($url['host'], 'maryjane.ru') === false) ? "rel='nofollow'" : '') . ">" . $url['scheme'] . "://$user_url</a></p>";
                            else $user_url = '';
                        } else $user_url = '';
                        
                        $user['url']          = $user_url;
                        $user['user_lj']      = $U->meta->user_lj;
                        $user['user_lastfm']  = $U->meta->user_lastfm;
                        $user['user_flickr']  = $U->meta->user_flickr;
                        $user['admin_comment']  = $U->meta->admin_comment;
                        
                        // Raiting / Carma
                        $user['user_raiting'] = $carma->getUserRaiting($user_id);
                        $user['user_carma']   = $carma->generateCarmaBlock('users',$user_id, 0, $this->user);
                        $user['carma_points'] = $carma->getUserCarmaPoints($user_id);
                        
                        // кол-во лет регистрации
                        if (!empty($U->user_register_date) && $U->user_register_date != '0000-00-00')
                        {
                            $user['registered_years'] = floor((time() - strtotime($U->user_register_date)) / (365 * 24 * 3600));
                        }
                        
                        // кол-во комментариев пользователя
                        $sth = App::db()->prepare("SELECT COUNT(*) AS c FROM `comments` WHERE `user_id` = :user AND `comment_visible` = '1'");
                        $sth->execute(array('user' => $U->id));
                        $row = $sth->fetch();
                        
                        $user['comments_count'] = (int) $row['c'];
                    
                        // кол-во лайков пользователя
                        /*
                        $sth = App::db()->prepare("SELECT COUNT(*) FROM `good_likes` WHERE `user_id` = :user");
                        $sth->execute(array('user' => $U->id));
                        $row = $sth->fetch();
                        
                        $user['likes_count'] = (int) $row['c'];
                        */
                        
                        // сколько раз работы автора становились дизайном дня
                        $sth = App::db()->prepare("SELECT COUNT(gl.`id`) AS c FROM `good__log` gl, `goods` AS g WHERE gl.`action` = 'designOfday' AND gl.`good_id` = g.`good_id` AND g.`user_id` = :user");
                        $sth->execute(array('user' => $U->id));
                        $row = $sth->fetch();
                        
                        $user['toptees_count'] = (int) $row['c'];
                        
                        // средняя оценка пользотвателя на голосовании и общее кол-во оценок
                        $sth = App::db()->prepare("SELECT COUNT(gv.`id`) AS c, ROUND(AVG(gv.`vote`)) AS a FROM `" . good::$votetable . "` gv WHERE gv.`user_id` = :user");
                        $sth->execute(array('user' => $U->id));
                        $row = $sth->fetch();
                        
                        $user['all_vote'] = (int) $row['c'];
                        $user['avg_vote'] = (int) $row['a'];
                    
                        // скольким работам помог выиграть (если поставил 5 и работа победила)
                        $sth = App::db()->prepare("SELECT COUNT(DISTINCT(g.`good_id`)) AS a FROM `" . good::$votetable . "` gv, `good_winners` gw, `goods` g WHERE gv.`user_id` = :user AND (gv.`vote` = '5' OR gv.`vote` = '4') AND g.`good_id` = gw.`good_id` AND gv.`good_id` = g.`good_id` AND g.`good_status` IN ('printed', 'pretendent') AND g.`good_visible` = 'true'");
                        $sth->execute(array('user' => $U->id));
                        $row = $sth->fetch();
                        
                        $user['help_to_win'] = (int) $row['a'];
                        
                        // просмотров работ пользователя
                        //$sth = App::db()->prepare("SELECT COUNT(gv.`id`) AS a FROM `good__visits` gv, `goods` g WHERE g.`user_id` = :user AND gv.`good_id` = g.`good_id` AND gv.`good_id` = g.`good_id` AND g.`good_visible` = 'true'");
                        $sth = App::db()->prepare("SELECT SUM(g.`visits` + g.`visits_catalog`) AS a FROM `goods` g WHERE g.`user_id` = :user AND g.`good_visible` = 'true'");
                        $sth->execute(array('user' => $U->id));
                        $row = $sth->fetch();
                        
                        $user['good_visits'] = (int) $row['a'];
                        
                        
                        if ($this->user->id != $user_id) 
                        {
                        } 
                        else 
                        {
                            // если логин пользователя зашифрован через ****
                            preg_match("/\*\*\*\*/", $user['user_login'], $matches);
                                
                            if ($matches[0] && !empty($user['user_phone'])) {
                                $user['user_login'] = $user['user_phone'];
                            } elseif ($matches[0] && !empty($this->user->info['user_email'])) {
                                $user['user_login'] =$user['user_email'];
                            }
                            
                            try 
                            {
                                $promoUrl = $this->user->getPromoUrl($this->user->meta->goodApproved > 0 ? '/catalog/' . $this->user->user_login . '/' : '');
                                $this->view->setVar('promoUrl',  $promoUrl['link']);
                            }
                            catch (Exception $e) 
                            {
                                printr($e->getMessage());
                            }
                        }
                        
                        $this->view->setVar('User', $user);
                        $this->view->setVar('U', $U);
                        
                        $this->page->title = 'Профиль - ' . $user['user_login'];
                        
                        // Avatar
                        $this->view->setVar('avatar', userId2userGoodAvatar($user_id, 100, '', '', true));
                        
                        // About me
                        if ($U->meta->about_text) {
                            $this->view->setVar('aboutMe', stripslashes($U->meta->about_text));
                        }
                        
                        if ($U->meta->interview_link) {
                            $this->view->setVar('interview', stripslashes($U->meta->interview_link));
                        }
                        
                        
                        // Photos
                        $sth = App::db()->prepare("SELECT up.`user_picture_id`, p.`picture_path`
                                  FROM `user_pictures` AS up, `pictures` AS p
                                  WHERE up.`user_id` = :user AND up.`picture_id` = p.`picture_id` AND up.`type` <> 'personal'
                                  ORDER BY up.`picture_id` DESC");
                                  
                        $sth->execute(array('user' => $U->id));       
                                  
                        $countPic = $sth->rowCount();
                        
                        if ($countPic > 0)
                        {
                            $pics = $sth->fetchAll();
                            $this->view->setVar('PHOTOS', $pics);
                        }
                        
                        // Blog posts
                        $sth = App::db()->prepare("SELECT SQL_CALC_FOUND_ROWS up.`id`, up.`post_date`, up.`post_title`, up.`post_sticked`, up.`post_status`, up.`post_author`,up.`post_author` AS user_id, up.`comments`, p.`picture_path` AS path
                              FROM `user_posts` AS up
                              LEFT JOIN `pictures` AS p ON up.`post_pic_small` = p.`picture_id`
                              WHERE up.`post_author` = :user AND up.`post_status` = 'publish' AND `r301` = '0'
                              GROUP BY up.`id`
                              ORDER BY up.`post_date` DESC
                              LIMIT 3");
                    
                        $sth->execute(array('user' => $U->id));       
                    
                        $posts = $sth->fetchAll();
                        
                        $sth = App::db()->query("SELECT FOUND_ROWS() AS c");
                        $total = $sth->fetch();
                        
                        if ($total['c'] > 0)
                        {
                            $this->view->setVar('blogTABZ', TRUE);
                            $this->view->setVar("BLOGcount", $total['c']);
            
                            $user['posts_count'] = $total['c'];
            
                            foreach($posts AS $k => $p)
                            {
                                $posts[$k]['post_date']    = datefromdb2textdate($p['post_date']);
                                $posts[$k]['post_title']   = stripslashes($p['post_title']);
                                $posts[$k]['post_tags']    = postId2tags_sring($p['id'], 1);
                                $posts[$k]['post_theme']   = postId2theme($p['id']);
                                $posts[$k]['postCarma']    = $carma->generateCarmaBlock('blog_post', $p['id'], 0, $this->user);
                                $posts[$k]['WFTChecked']   = (empty($p['WFTChecked']) ? '0' : '1');
                                $posts[$k]['WFTTitle']     = (empty($p['WFTChecked']) ? 'Следить за комментариями' : 'Слежу за комментариями');
                                $posts[$k]['class']        = ($p['post_sticked'] == '1') ? 'activeBorder' : ''; 
                            }
            
                            $this->view->setVar('posts', $posts);
                            //$this->view->setVar('blogTABZ', TRUE);
                        }
                        
                        // выбираем работы победители
                        $sth = App::db()->query("SELECT g.`good_id` FROM `good_winners` gw, `goods` g WHERE gw.`good_id` = g.`good_id` AND g.`good_visible` = 'true'");
                        foreach ($sth->fetchAll() as $g) {
                            $winners[$g['good_id']]++;
                        }
                        
                        $page       = 1;
                        $columns    = 3; // во сколько колонок выводяться принты
                        $x_offset   = 0;
                        $y_offset   = 0;
                        $x_paddings = 16;
                        $y_paddings = 87;
                        
                        unset($_SESSION[$this->page->module . 'ccolumns'][$page]);
                        unset($_SESSION[$this->page->module . 'ccolumns2'][$page]);
                        
                        // работы автора
                        $q = "SELECT
                              SQL_CALC_FOUND_ROWS 
                                g.`good_id`, g.`good_name`, g.`good_date`, g.`good_modif_date`, g.`good_status`, g.`good_visible`, g.`good_likes` AS likes, g.`visits` + g.`visits_catalog` AS visits, p.`picture_path`, c.`hex` AS bg, u.`user_login`, gl.`id` AS liked, gp.`pic_w`, gp.`pic_h`
                              FROM 
                                `goods` AS g 
                                    LEFT JOIN `good_likes` gl ON g.`good_id` = gl.`good_id` AND gl.`user_id` = '" . $this->user->id . "', 
                                `good_stock_colors` c, 
                                `good_pictures` gp,
                                `pictures` p, 
                                `users` u
                              WHERE 
                                    g.`user_id`      = '{$user_id}'
                                AND g.`good_visible` = 'true'
                                AND g.`good_domain`  IN ('all', 'mj')
                                AND g.`good_status`  NOT IN (" . (in_array($this->user->id, array($user_id, 6199, 27278)) ? '' : "'new', " ) . "'customize', 'deny') 
                                AND g.`user_id`      = u.`user_id` 
                                AND g.`ps_onmain_id` = c.`id` 
                                AND gp.`good_id`     = g.`good_id` 
                                AND gp.`pic_name`    = 'good_preview'
                                AND gp.`pic_id`      = p.`picture_id`
                              GROUP BY g.`good_id`
                              ORDER BY g.`good_date` DESC
                              LIMIT 6";
                        
                        $sth = App::db()->query($q);
                
                        if ($sth->rowCount() > 0)
                        {
                            $goods = $sth->fetchAll();
                            
                            $sth = App::db()->query("SELECT FOUND_ROWS() AS c");
                            $row = $sth->fetch();
                            $goods_count = $row['c'];
                            
                            foreach($goods AS $k => &$gg)
                            {
                                $gg['good_name'] = stripslashes($gg['good_name']);
                                
                                if ($gg['good_status'] == 'new' || $gg['good_visible'] == 'modify')
                                {
                                    $dd = getDateDiff(($gg['good_modif_date'] != '0000-00-00 00:00:00') ? $gg['good_modif_date'] : $gg['good_date']);
                                    $gg['timetoend'] = ($dd > 24) ? 0 : 24 - $dd;
                                }
                                
                                if ($winners[$gg['good_id']])
                                    $gg['place'] = $winners[$gg['good_id']];
                                
                                // расчитываем координаты
                                if (empty($gg['pic_w']) || empty($gg['pic_h']))
                                    list($iw, $ih) = getimagesize(ROOTDIR . $gg['picture_path']);
                                else {
                                    $iw = $gg['pic_w'];
                                    $ih = $gg['pic_h'];
                                }
                                    
                                $i = $k % $columns;
                                $gg['i'] = $i;
                                $gg['h'] = ($ih + $y_paddings) + $y_offset;
                                $gg['x'] = ($i * $iw) + ($i * $x_offset) + ($i * $x_paddings);
                                $gg['y'] = (int) $_SESSION[$this->page->module . 'ccolumns'][$page][$i] + ((!isset($_SESSION[$this->page->module . 'ccolumns'][$page][$i])) ? (int) $_SESSION[$this->page->module . 'ccolumns'][$page - 1][$i] : 0);
                                
                                $_SESSION[$this->page->module . 'ccolumns'][$page][$i] += $gg['h']  + ((!isset($_SESSION[$this->page->module . 'ccolumns'][$page][$i])) ? (int) $_SESSION[$this->page->module . 'ccolumns'][$page - 1][$i] : 0) + 24;
                            }
                            
                            $this->view->setVar('author_goods', $goods);
                            $this->view->setVar('goodsCount', $goods_count); 
                            $this->view->setVar('goodTABZ', TRUE);   
                            $this->view->setVar('printshop_link', TRUE);
                            $this->view->setVar('ULheight', max($_SESSION[$this->page->module . 'ccolumns'][$page]));
                            
                            $user['goods_count'] = $goods_count;
                        }
                        else
                            $this->view->setVar('no_printshop_link', array('user_login' => $user['user_login']));
                            
                        
                        // -- мне нравится --
                        $y_paddings = 35;
                        
                        $all_requests = array();
                    
                        $q = "SELECT 
                              SQL_CALC_FOUND_ROWS
                                g.`good_id`, g.`good_status`, g.`good_name`, g.`user_id`, (g.`visits` + g.`visits_catalog`) as visits , p.`picture_path`, u.`user_login`, g.`good_likes` as likes, gp.`pic_w`, gp.`pic_h`
                              FROM `good_likes` gl, `pictures` AS p, `users` u, `goods` g, `good_pictures` gp
                              WHERE 
                                    gl.`user_id`     = '".$user_id."' 
                                AND gl.`good_id`     = g.`good_id`
                                AND gl.`negative`    = '0'
                                AND g.`good_visible` = 'true' 
                                AND g.`user_id`      = u.`user_id`
                                AND g.`user_id`     != '" . $user_id . "'
                                AND g.`good_status` != 'deny' 
                                AND gp.`good_id`     = g.`good_id` 
                                AND gp.`pic_name`    = 'good_preview'
                                AND gp.`pic_id`      = p.`picture_id`
                              GROUP BY gl.`good_id`
                              ORDER BY gl.`time` DESC
                              LIMIT 6";
                        
                        $sth = App::db()->query($q);
                        
                        $goodSelected = $sth->rowCount();   
                    
                        if ($goodSelected > 0) 
                        {
                            $goods = $sth->fetchAll();
                            
                            $sth = App::db()->query("SELECT FOUND_ROWS() AS c");
                            $row = $sth->fetch();
                            $all = (int) $row['c'];
                            
                            foreach($goods AS $k => &$ggg)
                            {
                                $ggg['good_name'] = stripslashes($ggg['good_name']);
                                
                                if ($winners[$ggg['good_id']])
                                    $ggg['place'] = $winners[$ggg['good_id']];
                                    
                                // расчитываем координаты
                                if (empty($ggg['pic_w']) || empty($ggg['pic_h'])) {
                                    list($iw, $ih) = getimagesize(ROOTDIR . $ggg['picture_path']);
                                } else {
                                    $iw = $ggg['pic_w'];
                                    $ih = $ggg['pic_h'];
                                }
                                    
                                $i = $k % $columns;
                                $ggg['i'] = $i;
                                $ggg['h'] = ($ih + $y_paddings) + $y_offset;
                                $ggg['x'] = ($i * $iw) + ($i * $x_offset) + ($i * $x_paddings);
                                $ggg['y'] = (int) $_SESSION[$this->page->module . 'ccolumns2'][$page][$i] + ((!isset($_SESSION[$this->page->module . 'ccolumns2'][$page][$i])) ? (int) $_SESSION[$this->page->module . 'ccolumns2'][$page - 1][$i] : 0);
                                //printr($ggg);
                                $_SESSION[$this->page->module . 'ccolumns2'][$page][$i] += $ggg['h']  + ((!isset($_SESSION[$this->page->module . 'ccolumns2'][$page][$i])) ? (int) $_SESSION[$this->page->module . 'ccolumns2'][$page - 1][$i] : 0) + 24;
                            }
                            
                            
                            $this->view->setVar("selected_goods", $goods);
                            $this->view->setVar('selected_goods_count', $all);
                            $this->view->setVar('ULheight2', max($_SESSION[$this->page->module . 'ccolumns2'][$page]));
                            
                            if ($all >= 6) {
                                $this->view->setVar('selectedTABZ', TRUE);
                            }
                        }
                        
                        
                        // Selected Add / Remove (Звёздочка)
                        if ($this->user->authorized && $this->user->id != $user_id) 
                        {
                            $sth = App::db()->query("SELECT `selected_id` FROM `selected` WHERE `user_id` = '".$this->user->id."' AND `selected_id` = '$user_id'");
                            
                            if ($sth->rowCount() == 0)
                                $this->view->setVar('add_to_selected', TRUE);
                            else
                                $this->view->setVar('remove_from_selected', TRUE);
                        }
                        
                    
                        // -- LATESTS 6 PICTURES --
                        $sth = App::db()->query("SELECT SQL_CALC_FOUND_ROWS ga.`gallery_picture_id`, p.`picture_path`
                                    FROM `gallery` AS ga, `goods` AS g, `pictures` AS p
                                    WHERE ga.`gallery_picture_visible` = 'true' AND ga.`good_id` = g.`good_id` AND ga.`gallery_picture_author` = '" . $user_id . "'  AND p.`picture_id` = ga.`gallery_small_picture`
                                    ORDER BY ga.`gallery_picture_date` DESC
                                    LIMIT 6");
                        
                        $pics = $sth->fetchAll();
                        
                        $sth = App::db()->query("SELECT FOUND_ROWS() AS c");
                        $row = $sth->fetch();
                        
                        $user['photo_count'] = (int) $row['c'];
                        
                        $this->view->setVar('PICTUREScount', $user['photo_count']);
                        $this->view->setVar('PICTURES', $pics);
                        
                        if ($user['photo_count'] > 2) 
                            $this->view->setVar('pictTABZ', TRUE);
                        // -- LATESTS 6 PICTURES --
                        
                        // -- GET WHO SELECTED ME --
                        $sth = App::db()->query("SELECT SQL_CALC_FOUND_ROWS s.`user_id`, u.`user_login`, u.`user_designer_level` 
                                    FROM `selected` AS s, `users` AS u 
                                    WHERE s.`selected_id` = '".$user_id."' AND u.`user_id` = s.`user_id` 
                                    ORDER BY `id` DESC 
                                    LIMIT 10");
                    
                        if ($sth->rowCount() > 0)
                        {
                            $authors = $sth->fetchAll();
                            
                            $sth = App::db()->query("SELECT FOUND_ROWS() AS c");
                            $row = $sth->fetch();
                            
                            $user['me_selected'] = (int) $row['c'];
                                            
                            foreach($authors AS $k => $row) {
                                $authors[$k]['user_designer_level'] = designerLevelToPicture($row['user_designer_level']);
                                $authors[$k]['user_login'] = str_replace('.livejournal.com', '', stripslashes($row['user_login']));
                            }
                            
                            $this->view->setVar('WHO_SEL_MEcount', $user['me_selected']);
                            $this->view->setVar('WHO_SEL_ME', $authors);
                        }
                        // -- END GET WHO SELECTED ME --
                        
                        // -- GET SELECTED AUTHOR --
                        $sth = App::db()->query("SELECT SQL_CALC_FOUND_ROWS s.`selected_id` AS user_id, u.`user_login`, u.`user_designer_level`
                                    FROM `selected` AS s, `users` AS u
                                    WHERE s.`user_id` = '$user_id' AND u.`user_id` = s.`selected_id`
                                    GROUP BY s.`selected_id`
                                    ORDER BY `id` DESC
                                    LIMIT 10");
                    
                        if ($sth->rowCount() > 0)
                        {
                            $authors = $sth->fetchAll();
                            
                            $sth = App::db()->query("SELECT FOUND_ROWS() AS c");
                            $row = $sth->fetch();
                            
                            $user['selected_authors_count'] = (int) $row['c'];
                            
                            foreach($authors AS $k => $row) {
                                $authors[$k]['user_designer_level'] = designerLevelToPicture($row['user_designer_level']);
                                $authors[$k]['user_login'] = str_replace('.livejournal.com', '', stripslashes($row['user_login']));
                            }
                            
                            $this->view->setVar('selected_authors_count', $user['selected_authors_count']);
                            $this->view->setVar('selected_authors', $authors);
                        } 
                        // -- END GET SELECTED AUTHOR --
                        
                        // сколько "я" лайкнул работ
                        $sth = App::db()->query("SELECT COUNT(*) AS c FROM `good_likes` gl, `goods` g WHERE g.`good_id` = gl.`good_id` AND g.`user_id` = '" . $user_id . "' AND gl.`negative` = '0' AND g.`good_status` != 'voting'");
                        $row = $sth->fetch();
                        $user['me_liked'] = (int) $row['c'];
                        
                        /**
                         * взаимные друзья
                         */ 
                        $sth = App::db()->query("SELECT SQL_CALC_FOUND_ROWS s.`selected_id` AS user_id, u.`user_login` , u.`user_designer_level` 
                                    FROM `selected` AS s, `users` AS u
                                    WHERE s.`user_id` =  '$user_id' AND (SELECT COUNT(*) FROM  `selected` AS s2 WHERE s2.`selected_id` = '$user_id' AND s2.`user_id` = s.`selected_id`) >= 1  AND u.`user_id` = s.`selected_id`
                                    GROUP BY s.`selected_id` 
                                    ORDER BY  `id` DESC 
                                    LIMIT 0 , 30");
                    
                        if ($sth->rowCount() > 0)
                        {
                            $friends = $sth->fetchAll();
            
                            $sth = App::db()->query("SELECT FOUND_ROWS() AS c");
                            $row = $sth->fetch();
            
                            $user['friends_count'] = (int) $row['c'];
                            
                            foreach($friends AS $k => $row) {
                                $friends[$k]['user_designer_level'] = designerLevelToPicture($row['user_designer_level']);
                                $friends[$k]['user_login'] = str_replace('.livejournal.com', '', stripslashes($row['user_login']));
                            }
                            
                            $this->view->setVar('friends_count', $user['friends_count']);
                            $this->view->setVar('friends', $friends);
                        }
                        
                        
                        
                        // банер "Я участвую в конкусе"
                        $sth = App::db()->prepare("SELECT c.`competition_id`, c.`competition_name`, c.`competition_slug`, c.`competition_status`
                                    FROM `competitions` AS c, `goods` AS g
                                    WHERE 
                                            g.`competition_id` = c.`competition_id`
                                        AND g.`user_id` = :user
                                        AND c.`competition_visible` = 'true'
                                        AND g.`good_status` != 'deny'
                                        AND g.`good_visible` = 'true'
                                    GROUP BY c.`competition_id`
                                    ORDER BY c.`competition_end` DESC");
                                    
                        $sth->execute(array('user' => $user_id));           
                                    
                        if ($sth->rowCount() > 0)
                        {
                            $c  = array();
                            $rs = $sth->fetchAll();
                            
                            foreach ($rs as $r) {
                                $r['competition_name'] = stripslashes($r['competition_name']);
                                $r['competition_slug'] = (!empty($r['competition_slug'])) ? stripslashes($r['competition_slug']) : $r['competition_id'];
                                if ($r['competition_status'] == 'active')
                                    $c['active'][] = $r;
                                else {
                                    $c['archive'][] = $r;
                                }
                            }
                            
                            $this->view->setVar('i_participate_in_competition', $c['active']);
                            $this->view->setVar('i_participate_in_competition_archive', $c['archive']);
                        }
                    }
                    else
                    {
                        // пользователей пришедших с контакта кидаем в редактирование профиля для указания своего мыла
                        if (empty($this->user->user_email))
                        {
                            header('location: /editprofile/');
                            exit();
                        }
                        
                        $this->view->setVar('USER_NOT_ACTIVATED', array('user_id' => $user['user_id'], 'user_login' => $user['user_login'], 'user_email' => $user['user_email']));
                        $this->view->setVar('PAGE_TITLE', 'Профиль ещё не активирован');
                    }
                }
                else
                {
                    $this->view->setVar('USER_NOT_FOUND', array('user_id' => $user_id));
                    $this->view->setVar('PAGE_TITLE', 'Пользователь не найден');
                }
                
                if ($postsCount == 0 && $goodsCount == 0 && $pictCount == 0 && $goodSelected == 0)
                    $this->page->noindex = true;
            }
            else
            {
                $this->view->setVar('USER_NOT_FOUND', array('user_id' => $user_id));
            }
            
            $this->view->setVar('userInfo', $user);
            
            $this->view->generate('index.tpl');
        }
    }