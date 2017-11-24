<?php
    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
    
    use \application\models\carma AS carma;
    use \application\models\user AS user;
    use \application\models\basket AS basket;
    use \application\models\basketItem;
    use \application\models\styleCategory AS styleCategory;
    use \application\models\style AS style;
    use \application\models\good AS good;
    use \application\models\review AS review;
    
    use \PDO;
    use \Imagick;
    use \ImagickPixel;
    use \Exception;
    use \ZipArchive;
    
        
    class Controller_ajax extends \smashEngine\core\Controller
    {
        public function action_index()
        {
            $action = trim($_GET['action']);

            if (empty($action)) 
                $action = trim($this->page->reqUrl[1]);
                
            if (empty($action)) 
                exit('error:unknown action');
            
            header("Status: 404 Not Found");
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );  // disable IE caching
            header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
            header("Cache-Control: no-cache, must-revalidate" );
            header("Pragma: no-cache" );
            header("Content-Type: text/html; charset=utf-8");
            
            $SNGcountry = array(675,685,693,734,759,807,865,876,879,880);
            
            switch ($action) 
            {
                case 'getOrderhistoryLink':
                    
                    if (!empty($_GET['user']))
                    {
                        $code = md5(rand());
                        $user = intval($_GET['user']);
                        
                        if (!$_GET['order']) {
                            $sth = App::db()->prepare("SELECT `user_basket_id` FROM `" . basket::$dbtable . "` WHERE `user_id` = ? AND `user_basket_status` NOT IN ('active', 'canceled', 'returned') ORDER BY IF (`user_basket_status` = 'delivered', 0, 1) DESC, `user_basket_date` DESC LIMIT 1");
                            $sth->execute([$user]);
                            if ($foo = $sth->fetch()) {
                                $_GET['order'] = $foo['user_basket_id'];
                            }
                        }
                        
                        $order = new basket($_GET['order']);
                        
                        App::db()->query("INSERT INTO `user_quick_login` SET `hash` = '{$code}', `user_id` = '{$user}'");
                        
                        if($order->id > 0)
                            exit("http://www." . ($order->user_basket_domain == 'MJbasket' ?  'maryjane' : 'allskins') . '.ru/login/quick/?user_id=' . $user . '&code=' . $code . "&next=/orderhistory/" . $order->id . "/");
                        else
                            exit(mainUrl . "/login/quick/?user_id=$user&code=" . $code . "&next=/");
                    }
                    
                    exit();
                    
                    break;
                    
                case 'rememberMe':
                    
                    if (count($_POST) == 0) {
                        
                        $this->view->generate('catalog/rememberMe.tpl');
                        exit();
                        
                    } else {
                        
                        $sth = App::db()->prepare("SELECT `mail_list_id` FROM `mail_list_subscribers` WHERE `user_email` = :email AND ABS(`mail_list_id`) = 700 LIMIT 1");
                        $sth->execute(['email' => $_POST['email']]);
                        $foo = $sth->fetch();
                        
                        if (!$foo['mail_list_id']) {
                            
                            $sth = App::db()->prepare("INSERT INTO `mail_list_subscribers` SET `user_id` = :user_id, `user_email` = :email, `user_ip` = INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "'), `mail_list_id` = '700'");
                            $sth->execute(['user_id' => $this->user->id, 'email' => $_POST['email']]);
                            
                            $out = ['status' => 'add'];
                        } else {
                            $out = ['status' => 'already'];
                        }
                        
                        if ($this->page->isAjax) {
                            exit(json_encode($out));
                        }
                        
                        $this->page->refresh();
                    }
                    
                    break;
                    
                /**
                 * Добавление в корзину позиции из конструктора
                 */
                case 'add2basketCustomize':
                
                    $good_stock_id = intval($_POST['good_stock_id']);
                    $style_id      = intval($_POST['style_id']);
                    $quantity      = (isset($_POST['quantity']) && is_numeric($_POST['quantity'])) ? intval($_POST['quantity']) : 1;
                
                    $sth = App::db()->query("SELECT gs.`good_stock_id`, gs.`style_id`, s.`style_color` AS color_id, s.`style_sex`, s.`style_front_picture`, s.`style_back_picture`, sc.`cat_parent`, s.`style_category` 
                                FROM `good_stock` gs, `styles` s, `styles_category` sc 
                                WHERE 
                                        " . (!empty($good_stock_id) ? "gs.`good_stock_id` = '{$good_stock_id}'" :  "s.`style_id` = '{$style_id}'") . "
                                    AND gs.`style_id` = s.`style_id` 
                                    AND (
                                        gs.`good_stock_visible` > '0' " 
                                        . 
                                        ($this->user->id == 27278 || $this->user->id == 96976 ? " OR gs.`style_id` = '760'OR gs.`style_id` = '784'" : '')
                                        .
                                        ($this->user->meta->mjteam || in_array($this->user->id, [225651, 239341, 81706, 215735]) ? " OR s.`style_category` = '113' OR s.`style_category` = '119'" : '') 
                                     . ") 
                                    AND gs.`good_id` = '0'
                                    AND s.`style_category` = sc.`id` 
                                LIMIT 1");
                    
                    if ($sth->rowCount() == 0)
                    {
                        exit(json_encode(array('error' => 'Не указан носитель')));
                    }
                    
                    $stock = $sth->fetch();
                
                    if (empty($good_stock_id))
                        $good_stock_id = $stock['good_stock_id'];
                
                    $cat = styleCategory::$BASECATS[styleCategory::$BASECATSid[$stock['cat_parent'] == 1 ? $stock['style_category'] : $stock['cat_parent']]]['src_name'];
                    
                    if (!$cat) {
                        $cat = 'ps_src';
                    }
                    
                    // грудь
                    if (!empty($stock['style_front_picture']) && ($_POST['front']['image']['layers'] || $_POST['front']['text']['layers']))
                    {
                        $sides['front'] = $cat;
                    }
                    
                    // спина
                    if (!empty($stock['style_back_picture']) && ($_POST['back']['image']['layers'] || $_POST['back']['text']['layers']))
                    {
                        if (in_array($cat, array('phones', 'laptops', 'touchpads', 'ipodmp3', 'cases', 'poster')))
                            $sides['back'] = $cat;
                        else
                            $sides['back'] = $cat . '_back';
                    }
                    
                    if (count($sides) == 0)
                    {
                        // для тряпок разрешаем создание самоделок без исходников и надписей
                        if ($stock['cat_parent'] == 1) 
                            $sides = array('front' => $cat, 'back' => $cat . '_back');
                        // для гаджетов в таком случае - ошибка
                        else
                            $msg['error'] = 'Нельзя заказать пустую самоделку';
                    }
                    
                    if (!$msg['error'])
                    {
                        try
                        {
                            /**
                             * saving
                             */
                            if (!empty($_POST['good_id']))
                            {
                                // если это кастомизация авторского дизайна
                                $original = new \application\models\good($_POST['good_id']);
                            }
                            
                            $g = new \application\models\good;
                            
                            //$g->good_name    = 'Самоделка';
                            $g->good_status  = 'customize';
                            $g->good_visible = 'false';
                            
                            if ($original)
                                 $g->user_id = $original->user_id;
                            else 
                                 $g->user_id = $this->user->id;
                    
                            if ($_POST['casesFontColor']) 
                            {
                                $_POST['casesFontColor'] = str_replace('#', '', $_POST['casesFontColor']);
                                
                                if (!$g->ps_onmain_id = colorHex2id($_POST['casesFontColor']))
                                {
                                    $sth = App::db()->prepare("INSERT INTO `good_stock_colors` SET `hex` = :hex");
                                    
                                    $sth->execute(array('hex' => $_POST['casesFontColor']));
                                    
                                    $g->ps_onmain_id = App::db()->lastInsertId();
                                }
                            }
                    
                            $g->save();
                        
                            if ($original)
                                $g->setMeta('customization_of', $original->id);
                        
                            foreach ($sides AS $side => $fn)
                            {
                                // сохраняем исходник
                                if (!empty($_COOKIE['customize_img'][$side]))
                                {
                                    $path = pictureId2path($_COOKIE['customize_img'][$side]);
                                    
                                    $name[$side] = substr(basename($path), 11);
                                    $foo = explode('.', $path);
                                    $ext = end($foo);
                                    
                                    if ($stock['style_id'] == 712 && in_array($ext, array('zip', 'rar'))) {
                                        $w = $h = 0;
                                    } else {
                                        $i = new Imagick(ROOTDIR . $path);              
                                        $w = $i->getImageWidth();       
                                        $h = $i->getImageHeight();
                                    }
                                    
                                    $g->addPic($fn, $_COOKIE['customize_img'][$side], $w, $h);
                                    
                                    // сохраняем координаты и размеры
                                    App::db()->query("INSERT IGNORE INTO 
                                                    `good__positions`
                                                SET 
                                                    `good_id`         = '" . $g->id . "',
                                                    `good_stock_id`   = '" . $stock['style_id'] . "',
                                                    `side`            = '$side',
                                                    `x` = '" . intval($_POST[$side]['image']['layers']['0']['left']) . "',
                                                    `y` = '" . intval($_POST[$side]['image']['layers']['0']['top']) . "',
                                                    `w` = '" . intval($_POST[$side]['image']['layers']['0']['width']) . "',
                                                    `h` = '" . intval($_POST[$side]['image']['layers']['0']['height']) . "',
                                                    `a` = '" . intval($_POST[$side]['image']['layers']['0']['rotate']) . "'
                                                ON DUPLICATE KEY UPDATE 
                                                    `x` = '" . intval($_POST[$side]['image']['layers']['0']['left']) . "',
                                                    `y` = '" . intval($_POST[$side]['image']['layers']['0']['top']) . "',
                                                    `w` = '" . intval($_POST[$side]['image']['layers']['0']['width']) . "',
                                                    `h` = '" . intval($_POST[$side]['image']['layers']['0']['height']) . "',
                                                    `a` = '" . intval($_POST[$side]['image']['layers']['0']['rotate']) . "'");      
                                }
                                
                                // сохраняем текстовые слои для данной стороны если они есть
                                if (count($_POST[$side]['text']['layers']) > 0)
                                {
                                    foreach ($_POST[$side]['text']['layers'] as $k => $l) 
                                    {
                                        // убираем все отступы. переделали
                                        $l['offsetTopText'] = 0;
                                        
                                        App::db()->query("INSERT
                                                    INTO `good__texts`
                                                    SET
                                                        `good_id` = '" . $g->id . "',
                                                        `side`  = '" . $side . "',
                                                        `layer` = '" . $k . "',
                                                        `text`  = '" . addslashes($l['text']) . "',
                                                        `x` = '" . intval($l['left']) . "',
                                                        `y` = '" . intval($l['top']) . "',
                                                        `w` = '" . intval($l['width']) . "',
                                                        `h` = '" . intval($l['height']) . "',
                                                        `a` = '" . intval($l['rotate']) . "',
                                                        `offset_y`   = '" . intval($l['offsetTopText']) . "',
                                                        `font_size`  = '" . addslashes($l['fontSize']) . "',
                                                        `font_name`  = '" . addslashes($l['fontName']) . "',
                                                        `font_style` = '" . addslashes(serialize($l['style'])) . "',
                                                        `font_color` = '" . addslashes($l['color']) . "'
                                                    ");
                                    }
                                }
                                
                                // генерим превью
                                // для тряпок на каждой стороне по отдельности    
                                if ($stock['cat_parent'] == 1)
                                {
                                    $prv = $g->preview($stock['style_id'], 250, 256, $side, UPLOADTODAY . md5(uniqid()) . '.jpeg');
                                        
                                    $g->addPic('catalog_preview_' . $style_id . (($side == 'back') ? '_back' : ''), $prv['id']);    
                                }
                                // для гаджетов только на дефолтной стороне
                                elseif ($stock['cat_parent'] > 1 && $side == styleCategory::$BASECATS[styleCategory::$BASECATSid[$stock['cat_parent']]]['def_side'])
                                {
                                    if ($stock['style_id'] == 712 && in_array($ext, array('zip', 'rar'))) {
                                        $prv = array('id' => 8574313, 'path' => '/images/icons/icon_archive.gif');
                                    } else {
                                        $prv = $g->preview($stock['style_id'], '', '', $side, UPLOADTODAY . md5(uniqid()) . '.jpeg', 1, array('form' => $_POST['form']));
                                    }
                                    
                                    $g->addPic('catalog_preview_' . $style_id, $prv['id']);
                                }
                                
                                if (!empty($_COOKIE['customize_img'][$side]) || count($_POST[$side]['text']['layers']) > 0)
                                {
                                    if ($side == 'front')
                                        $g->ps_src = 1;
                                    else
                                        $g->ps_src_back = 1;
                                    
                                    $g->save();
                                }
                            }
                    
                            if (empty($g->good_name))
                            {
                                if ($original)
                                    $g->change(array(
                                        'good_name' => $original->good_name . ' + кастомизация'));
                                else
                                    $g->change(array(
                                        'good_name' => 'Самоделка ' . implode(' ', $name)));
                            }
                
                            $basketItem = new basketItem;
                            $basketItem->good_id = $g->id;
                            $basketItem->good_stock_id = $good_stock_id;
                            $basketItem->quantity = $quantity;
                            $basketItem->price = $_POST['price'][$good_stock_id];
                            $basketItem->comment = $_POST['comment'] != 'null' && !empty($_POST['comment']) ? addslashes($_POST['comment']) : '';
                            
                            $msg = $this->basket->addToBasket($basketItem);
                            
                            // если выбрана ещё и защитка на экран
                            if ($_POST['safetySkin']) {
                                $basketItem = new basketItem;
                                $basketItem->good_id = $g->id;
                                $basketItem->good_stock_id = $_POST['safetySkin'];
                                $basketItem->quantity = 1;
                                
                                $msg = $this->basket->addToBasket($basketItem);
                            }
                        
                            setcookie('text_layers_front', '', time() - 3600);
                            setcookie('text_layers_back', '', time() - 3600);
                        }
                        catch (Exception $e) 
                        {
                            $msg['error'] = $e->getMessage(); 
                        }
                    }
                    
                    if ($this->page->lang == 'en')
                    {
                        $msg['price'] = round($msg['price'] / $this->VARS['usdRate'], 1);
                    }
                
                    $msg['good_id'] = $g->id;
                    exit(json_encode($msg));
                
                    break;
                
                /**
                 * Сохранить дефолтную сторону кружки и перегенерить превью для списка
                 */
                case 'set_cup_default_side':
                    
                    try
                    {
                        if ($this->user->meta->mjteam != 'super-admin')
                            throw new Exception('no access', 1);
                            
                        if (!in_array($this->page->reqUrl[4], array('front', 'side', 'lside')))
                            throw new Exception('Неизвестная сторона', 2);
                            
                        $g = new \application\models\good($this->page->reqUrl[2]);
                        $s = new \application\models\style($this->page->reqUrl[3]);
                        
                        $r = $g->generateCupPreview($s->id, 382, 391, $this->page->reqUrl[4], '/J/catalog/' . date('Y/m/d/') . toTranslit($g->good_name) . '_' . $s->style_slug . '_' . substr(md5(time() . rand()), 0, 4) . '.jpeg', TRUE);
                        
                        $g->setMeta('cup_default_side_' . $s->id, $this->page->reqUrl[4]);
                        
                        $g->addPic('catalog_preview_' . $s->id, $r['id'], 382, 391);
                        
                        exit($r['path']);
                    }
                    catch (Exception $e)
                    {
                        exit($e->getMessage());
                    }
                    
                    exit();
                    
                    break;
                    
                case 'exportallgood':
                    
                    if (in_array($this->user->meta->mjteam, array('grand_manager', 'super-admin')))
                    {
                        if (!empty($this->page->reqUrl[2]))
                        {
                            try
                            {
                                $z = new ZipArchive;
                            
                                $zipfile = tempnam(sys_get_temp_dir(), 'zip_') . '.zip';
                                
                                if ($z->open($zipfile, ZipArchive::CREATE) === TRUE) {}
                                else
                                    throw new Exception ('cannot save outputed file');
                                
                                
                                $sth = App::db()->prepare("SELECT 
                                                        g.`good_id`,
                                                        gp.`pic_name`,
                                                        p.`picture_path`
                                                    FROM
                                                        `goods` g,
                                                        `good_pictures` gp,
                                                        `pictures` p
                                                    WHERE
                                                            g.`user_id` = :user
                                                        AND g.`good_status` IN ('printed', 'pretendent', 'archived')
                                                        AND g.`good_visible` = 'true'
                                                        AND gp.`good_id` = g.`good_id`
                                                        AND gp.`pic_id` = p.`picture_id`
                                                        AND gp.`pic_name` IN ('" . implode("', '", array_keys(good::$srcs)) . "')");
                                
                                $sth->execute(array('user' => $this->page->reqUrl[2]));
                                
                                foreach ($sth->fetchAll() as $p) {
                                    $z->addFile(ROOTDIR . $p['picture_path'], $p['good_id'] . '_' . $p['pic_name'] . '.jpg');
                                }
                                               
                                $z->close();
                                
                                file_force_download($zipfile);
                                
                                unlink($zipfile);
                                
                                exit('stop');
                            }
                            catch (Exception $e)
                            {
                                exit($e->getMessage());
                            }
                        }
                        else 
                        {
                            exit('Не указан автор');
                        }
                    }
                    
                    break;  
                    
                case 'exportgood':
                    
                    $g = new \application\models\good($this->page->reqUrl[2]);
                    
                    if (($this->user->meta->mjteam && $this->user->meta->mjteam != 'fired') || $g->user_id == $this->user->id) 
                    {
                        $g->exportPics($_GET['style']);
                    }
                    
                    exit();
                    
                    break;
                    
                case 'savePromoLinkComment':
                    
                    if ($_POST['id']) 
                    {
                        try
                        {
                            $i = new \application\models\informer($_POST['id']);
                            if ($i->user_id == $this->user->id) {
                                $i->informer_comment = $_POST['comment'];
                                $i->save();
                            } else {
                                throw new Exception('No access', 1);
                            }
                        }
                        catch (Exception $e)
                        {
                            
                        }
                    }
                    
                    exit();
                    
                    break;
                    
                /**
                 * установить гендерную принадлежость принта
                 */
                case 'setsex':
                case 'setsexalt':
                        
                    try
                    {
                        if (!in_array($_POST['sex'], array('male', 'female', 'kids'))) {
                            throw new Exception('недопустимый пол');
                        }
                        
                        $g = new \application\models\good($_POST['id']);
                        
                        if ($this->user->meta->mjteam == 'super-admin' || $g->user_id == $this->user->id)
                        {
                            if (empty($g->good_sex) && empty($g->good_sex_alt)) {
                                $g->good_sex = $_POST['sex'];
                            } elseif (!empty($g->good_sex) && !empty($g->good_sex_alt) && $g->good_sex != $_POST['sex'] && $g->good_sex_alt != $_POST['sex']) {
                                $g->good_sex = $g->good_sex_alt = '';
                            } elseif ($g->good_sex == $_POST['sex']) {
                                $g->good_sex = '';
                            } elseif ($g->good_sex_alt == $_POST['sex']) {
                                $g->good_sex_alt = '';
                            } elseif (!empty($g->good_sex) && $g->good_sex != $_POST['sex']) {
                                $g->good_sex_alt = $_POST['sex'];
                            } elseif (empty($g->good_sex) && $g->good_sex_alt != $_POST['sex']) {
                                $g->good_sex = $_POST['sex'];
                            }
                            
                            $g->save();
                            
                            exit($g->{$field});
                        } else {
                            exit('no access');
                        }
                    }
                    catch (Exception $e)
                    {
                        printr($e->getMessage());
                    }
                    
                    exit();
                    
                    break;
                    
                /**
                 * загрузка 3х превьюшек на моделях телефонов
                 */
                case 'getNewCategoryDesigns':
                    
                    if ($this->page->reqUrl[2]) {
                        $c = addslashes(trim($this->page->reqUrl[2]));
                    }
                    
                    if ($this->page->reqUrl[3]) {
                        $s = addslashes(trim($this->page->reqUrl[3]));
                    }
                    
                    if ($_GET['quantity']) {
                        $q = intval($_GET['quantity']);
                    } else {
                        $q = 3;
                    }
                    
                    if ($c == 'laptops' || $c == 'touchpads') {
                        $q = 2;
                    } else {
                        $q = 3;
                    }
                    
                    try
                    {
                        $sth = App::db()->prepare("SELECT s.`style_id`, s.`style_slug`, s.`style_name`, s.`style_composition`, s.`style_" . styleCategory::$BASECATS[$c]['def_side'] . "_picture` AS model_picture 
                                              FROM 
                                                `styles` s, 
                                                `styles_category` sc, 
                                                `good_stock` gs 
                                              WHERE 
                                                    sc.`cat_parent` = :cat 
                                                AND sc.`cat_slug` = :slug 
                                                AND sc.`id` = s.`style_category` 
                                                AND s.`style_visible` = '1' 
                                                AND gs.`style_id` = s.`style_id` 
                                                AND gs.`good_stock_visible` = '1' 
                                                " . ($_GET['style_id'] ? "AND gs.`style_id` = '" . intval($_GET['style_id']) . "'" : '') . "
                                             ORDER BY s.`style_order` DESC , s.`style_id` DESC 
                                             LIMIT 1");
                        
                        $sth->execute(array(
                            'cat' => styleCategory::$BASECATS[$c]['id'],
                            'slug' => $s,
                        ));
                        
                        $style = $sth->fetch();
                        
                        if ($style['model_picture']) 
                        {
                            $sth = App::db()->prepare("SELECT SQL_CALC_FOUND_ROWS g.`good_id`, g.`good_name`, p.`picture_path`, u.`user_login`
                                      FROM `pictures` AS p, `users` AS u, `goods` AS g, `good_pictures` AS gp 
                                      WHERE 
                                            gp.`pic_name`      = :name
                                        AND gp.`good_id`      = g.`good_id`
                                        AND gp.`pic_id`       = p.`picture_id`
                                        AND g.`user_id`       = u.`user_id`
                                        AND g.`good_visible` = 'true'
                                        AND g.`good_status` IN ('printed', 'pretendent')
                                        AND g.`good_domain` IN ('all', 'mj')
                                    GROUP BY g.`good_id`
                                    ORDER BY g.`good_id` DESC
                                    LIMIT " . $q);
                        
                            $sth->execute(array(
                                'name' => 'catalog_preview_' . $style['style_id'],
                            ));
                            
                            $goods = $sth->fetchAll(PDO::FETCH_ASSOC);
                            
                            foreach($goods AS $k => $g)
                            {
                                $goods[$k]['good_name']    = stripslashes($g['good_name']);
                                $goods[$k]['category']     = $c;
                                $goods[$k]['subcategory']  = $s;
                                $goods[$k]['style_id']     = $style['style_id'];
                                $goods[$k]['style_slug']   = $style['style_slug'];
                                $goods[$k]['style_name']   = $style['style_name'];
                                $goods[$k]['style_composition'] = $style['style_composition'];
                            }
                            
                            exit(json_encode($goods));
                        }
                        else 
                        {
                            $S = new \application\models\style($style['style_id']);
                            
                            if ($S->pics['observ']) 
                            {
                                exit(json_encode(array(
                                    'observ' => $S->pics['observ']['path'],
                                    'style_id' => $S->style_id,
                                    'style_slug' => $S->style_slug,
                                    'style_name' => $S->style_name,
                                    'category' => $S->category,)));
                            }
                        }
                        
                    }
                    catch (Exception $e)
                    {
                        printr($e->getMessage());
                    }
                    
                    
                    
                break;
                
                case 'buyDiskSticker':
                    
                    if (!empty($_POST['color']) && $this->user->authorized)
                    {
                        if ($_SESSION['last_disk_order'] && time() - $_SESSION['last_disk_order'] <= 5) {}
                        else
                        {
                            App::mail()->send(6199, 548, array(
                                'user_id' => $this->user->id,
                                'user_login' => $this->user->user_login,
                                'user_email' => $this->user->user_email,
                                'user_phone' => $this->user->user_phone,
                                'color' => $_POST['color'],
                            ));
                            
                            $_SESSION['last_disk_order'] = time();
                        }
                        exit('success');
                    }
                    else
                        exit('error');
                    
                    break;
                
                /**
                 * Быстрый заказ готовой наклейки на весь мотоцикл
                 */
                case 'buyEnduroSticker':
                    
                    if (!empty($_POST['img']))
                    {
                        if ($_SESSION['last_disk_order'] && time() - $_SESSION['last_disk_order'] <= 60) {
                            $out = array('status' => 'error', 'message' => 'Повторите попытку позже');
                        }
                        else
                        {
                            App::mail()->send(6199, 660, array(
                                'user_id'    => $this->user->id,
                                'user_login' => $this->user->user_login,
                                'user_email' => $_POST['email'],
                                'user_phone' => $_POST['phone'],
                                'img'        => $_POST['img'],
                            ));
                            
                            $_SESSION['last_disk_order'] = time();
                            
                            $out = array('status' => 'ok');
                        }
                    }
                    else
                        $out = array('status' => 'error', 'message' => 'Не указан дизайн наклейки');
                    
                    exit(json_encode($out));
                    
                    break;
                    
                /**
                 * показать причины отклонения работы Худсоветом
                 * только для автора работы
                 */
                case 'showCancelReasone':
                    
                    if (!empty($this->page->reqUrl[2])) 
                    {
                        try
                        {
                            $good = new \application\models\good($this->page->reqUrl[2]);
                            
                            if ($good->user_id == $this->user->id || $this->user->meta->mjteam == 'super-admin')
                            {
                                if ($good->good_status == 'deny')
                                {
                                    $comments = array();
                                    
                                    foreach ($good->logs['hudsovet_vote'] as &$hvc) 
                                    {
                                        if ($hvc['result'] < 0)
                                        {
                                            $hvc['comment'] = (is_numeric($hvc['info'])) ? getVariableDescription('hudsovetAnswer_' . $hvc['info']) : $hvc['info']; 
                                                
                                            if (empty($hvc['comment']))
                                                unset($hvc);
                                            
                                            $hvc['comment'] = stripslashes($hvc['comment']);
                                            
                                            $comments[] = $hvc;
                                        }
                                    }
                                    
                                    $this->view->setVar('comments', $comments);
                                    $this->view->generate('catalog/cancelReasons.tpl');
                                    
                                    exit();
                                }
                                else {
                                    exit('good is not deny');
                                }
                            }
                            else {
                                exit('no access');
                            }
                        }
                        catch (Exception $e) {
                            exit($e->getMessage());
                        }
                    }
                    else {
                        exit('unknown good id');
                    }
                    
                    break;
                    
                case 'plus1':
                
                    // херовая идея
                    exit('0');
                
                    if (!empty($_GET['url']))
                    {
                        $url = $_GET['url'];
                    
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_HTTPHEADER => array('Content-type: application/json'),
                            CURLOPT_POST => true,
                            CURLOPT_POSTFIELDS => '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_SSL_VERIFYPEER => false,
                            CURLOPT_URL => 'https://clients6.google.com/rpc?key=AIzaSyCKSbrvQasunBoV16zDH9R33D88CeLr9gQ'
                        ));
                        $result = curl_exec($curl);
                        curl_close($curl);
            
                        if ($result) {
                            $json = json_decode($result, true);
                            exit((string) $json[0]['result']['metadata']['globalCounts']['count']);
                        }
                    }
            
                    exit();
                    
                    break;
                
                /**
                 * чит
                 * переброс работы в нужный статус
                 * только для супер-админов 
                 */
                case 'promote2':
                    
                    if ($this->user->meta->mjteam == 'super-admin')
                    {
                        if (!empty($_POST['id']) && in_array($_POST['to'], array('pretendent', 'archived'))) 
                        {
                            try
                            {
                                $g = new \application\models\good($_POST['id']);
                                
                                $g->change(array('good_status' => $_POST['to']));
                            }
                            catch (Exception $e) 
                            {
                                printr($e->getMessage()); 
                            }
                        }
                    }
                    
                    break;
                    
                /**
                 * скрытие работы на выбранном носителе
                 * только для супер-админов и автора работы
                 */
                case 'hideDesign':
                    
                    if ((!empty($_POST['id']) && !empty($_POST['style'])) || (!empty($this->page->reqUrl[2]) && !empty($this->page->reqUrl[3]))) 
                    {
                        if (empty($_POST['id']) && !empty($this->page->reqUrl[2]))
                            $_POST['id'] = $this->page->reqUrl[2];
                        
                        if (empty($_POST['style']) && !empty($this->page->reqUrl[3]))
                            $_POST['style'] = $this->page->reqUrl[3];
                        
                        try
                        {
                            $g = new \application\models\good($_POST['id']);
                            $s = new \application\models\style($_POST['style']);
                            
                            if ($this->user->meta->mjteam == 'super-admin' || $this->user->id == $g->user_id)
                            {
                                $g->src_on_of('catalog_preview_' . $s->id);
                                $g->src_on_of('catalog_art_preview_' . $s->id);
                                
                                // если работу скрывает сам автор
                                // то уведомление серёге
                                //if ($this->user->meta->mjteam != 'super-admin' && $g->pics['catalog_preview_' . $_POST['style']]['id'] < 0)
                                if ($this->user->meta->mjteam != 'super-admin' && $g->pics['catalog_preview_' . $s->id]['id'] < 0)
                                {
                                    App::mail()->send(6199, 500, array(
                                        'good_id' => $g->id,
                                        'good_name' => $g->good_name,
                                        'user_id' => $g->user_id,
                                        'user_login' => $g->user_login,
                                        'style_id' => $s->id,
                                        'style_name' => $s->style_name,
                                        'preview' => $g->pics['catalog_preview_' . $s->id]['path'],
                                    ));
                                }
                                
                                exit('hide');
                            }
                            else 
                            {
                                exit('no access');
                            }
                        }
                        catch (Exception $e) 
                        {
                            printr($e->getMessage()); exit();
                        }
                        
                        if ($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest')
                        {
                            header('location: /catalog/' . $g->user_login . '/');
                            exit();
                        }
                    }
                    
                    break;
                    
                /**
                 * скрытие работы на выбранном носителе
                 * только для супер-админов и автора работы
                 */
                case 'disableDesign':
                    
                    //if ((!empty($_POST['id']) && !empty($_POST['category'])) || (!empty($this->page->reqUrl[2]) && !empty($this->page->reqUrl[3])))
                    if (!empty($_POST['id']) || !empty($this->page->reqUrl[2])) 
                    {
                        if (empty($_POST['id']) && !empty($this->page->reqUrl[2]))
                            $_POST['id'] = $this->page->reqUrl[2];
                        
                        if (empty($_POST['category']) && !empty($this->page->reqUrl[3]))
                            $_POST['category'] = $this->page->reqUrl[3];
                        
                        if (!empty($_POST['category']))
                        {
                            if (!$c = styleCategory::$BASECATS[$_POST['category']]['id'])
                                exit('unknown category');
                        }
                        
                        try
                        {
                            $g = new \application\models\good($_POST['id']);
                            
                            if ($this->user->meta->mjteam == 'super-admin' || $this->user->id == $g->user_id)
                            {
                                if ($_POST['category'])
                                {
                                    if (styleCategory::$BASECATS[$_POST['category']]['src_name'] != $_POST['category'])
                                        $_POST['category'] = styleCategory::$BASECATS[$_POST['category']]['src_name'];
                                    
                                    // выключаем исходник
                                    $g->src_on_of($_POST['category']);
                                    
                                } else {
            
                                    $g->good_visible = $g->good_visible == 'true' ? 'false' : 'true';
                                    
                                    $g->save();
                                }
                                
                                // если работу скрывает сам автор
                                // то уведомление серёге
                                if ($this->user->meta->mjteam != 'super-admin' && $g->pics[$_POST['category']]['id'] < 0)
                                {
                                    App::mail()->send(6199, 500, array(
                                        'good_id' => $g->id,
                                        'good_name' => $g->good_name,
                                        'user_id' => $g->user_id,
                                        'user_login' => $g->user_login,
                                        'style_id' => $s->id,
                                        'style_name' => $s->style_name,
                                        'preview' => $g->pics['catalog_preview_' . $s->id]['path'],
                                    ));
                                }
                                
                                exit('hide');
                            }
                            else 
                            {
                                exit('no access');
                            }
                        }
                        catch (Exception $e) 
                        {
                            printr($e->getMessage()); exit();
                        }
                        
                        if (!$this->page->isAjax)
                        {
                            header('location: /catalog/' . $g->user_login . '/');
                            exit();
                        }
                    } else {
                        exit('not enogth data');
                    }
                    
                    break;
                
                case 'goNext':
                
                    if (!empty($this->page->reqUrl[2]))
                    {
                        $next_id = nextOnVoting($this->page->reqUrl[2], 'voting', $this->user);
                        
                        if ($next_id == 'finish')
                            exit(json_encode(array('finish' => 1)));
                        
                        try
                        {
                            $next = new \application\models\good($next_id);
                            
                            $next->picture_path = $next->pics['good_preview']['path'];
                            $next->picture_big_path = $next->pics['voting_preview']['path'];
                            
                            $nextAuthor = new \application\models\user($next->user_id);
                    
                            $next->city_name = cityId2name($nextAuthor->user_city);
                        
                            //$sth = App::db()->prepare("SELECT `negative` FROM `good_likes` WHERE good_id = :good AND user_id = :user LIMIT 1");
                            //$sth->execute(array('good' => $next->id, 'user' => $this->user->id));
                            //$r = $sth->fetch();
                            
                            $this->view->setVar('g', $next->info);
                
                            $this->view->generate('main/vote.tpl');
                        }
                        catch (Exception $e) 
                        {
                            exit(json_encode(array('error' => 'Работа с таким номером не найдена')));
                        }
                    }
                
                    break;
                    
                case 'goNextV2':
                
                    if (!empty($this->page->reqUrl[2]))
                    {
                        $next_id = nextOnVoting($this->page->reqUrl[2], 'voting', $this->user);
                        
                        if ($next_id == 'finish')
                            exit(json_encode(array('finish' => 1)));
                        
                        try
                        {
                            $next = new \application\models\good($next_id);
                            
                            if ($next->pics['voting_preview']) {
                                $next->picture_path = $next->pics['voting_preview']['path'];
                            } else {
                                foreach (styleCategory::$BASECATS as $cat => $c) 
                                {
                                    if ($next->pics[$c['src_name']]) 
                                    {
                                        if ($c['sexes']) {
                                            $style = array_shift($c['def_style']);
                                        } else {
                                            if ($cat == 'poster') {
                                                if ($next->pics['poster']['pic_w'] > $next->pics['poster']['pic_h'])
                                                    $style = $c['def_style_p']['horizontal'];
                                                elseif ($next->pics['poster']['pic_w'] < $next->pics['poster']['pic_h'])
                                                    $style = $c['def_style_p']['vertical'];
                                                else
                                                    $style = $c['def_style_p']['square'];
                                            } else {
                                                $style = $c['def_style'];
                                            }
                                        }
                                                
                                        if ($c['sexes']) {
                                            $next->picture_path = $next->pics[$cat]['cache_back'] = 'http://cache.maryjane.ru/' . $cat . '/' . $style . '/' . $next->id . '.model.' . $next->pics[$c['src_name']]['update_timestamp'] . '.jpeg';
                                        } else {
                                            $next->picture_path = 'http://cache.maryjane.ru/' . $cat . '/' . $style . '/' . $next->id . '.' . $next->pics[$c['src_name']]['update_timestamp'] . '.' . ($next->meta->cup_default_side_725 ? $next->meta->cup_default_side_725 : $c['def_side']) . '.jpeg';
                                        }
                                        
                                        break;
                                    }
                                }
                            }
                            
                            $this->view->setVar('g', $next);
                
                            $this->view->generate('main/vote2.tpl');
                        }
                        catch (Exception $e) 
                        {
                            exit(json_encode(array('error' => 'Работа с таким номером не найдена')));
                        }
                    }
                
                    break;
            
                /** 
                 * ОКОШКО С ВЫБОРОМ ГОРОДА
                 */
                case 'citySelect':
                
                    $city_name  = $this->user->city;
                    $city_id    = cityName2id($this->user->city, $cntry, true);
                    $country_id = cityId2country($city_id);
                
                    $sth = App::db()->Query("SELECT `country_id`, `country_name` FROM `countries` WHERE `country_id` NOT IN ('838','880','693','759') ORDER BY `raiting`, `country_name`");
                        
                    foreach ($sth->fetchAll() as $c) {
                        $countrys[$c['country_id']] = $c;
                    }
                    
                    $this->view->setVar('country', $countrys);
            
                    // Вытаскиваем список пунктов самовывоза по городам
                    $sth = App::db()->query("SELECT `id`, `city_id`, `service`, `name`, `address`, `schema` FROM `delivery_points` WHERE 1");
                    
                    foreach ($sth->fetchAll() as $r) {
                        $dp[$r['city_id']][$r['service']][] = $r;
                    }
            
                    // сервисы доставки (по городам)
                    $sth = App::db()->query("SELECT `service`, `city_id`, `time`, `time1`, `time2`, `cost` FROM `delivery_services` WHERE 1");
                    
                    foreach ($sth->fetchAll() as $r) {
                        
                        if ($dp[$r['city_id']][$r['service']]) {
                            $r['self-delivery-points'] = json_encode($dp[$r['city_id']][$r['service']]);
                        }
                        
                        $ds[$r['city_id']][$r['service']] = $r;
                    }
            
                    // сервисы доставки (по странам)
                    $sth = App::db()->query("SELECT `service`, `country_id`, `time`, `time1`, `time2`, `cost` FROM `delivery_services_dpd_country` WHERE 1");
                    
                    foreach ($sth->fetchAll() as $r) {
                        $dsc[$r['country_id']][$r['service']] = $r;
                    }
            
                    // Список городов с группировкой по первой букве
                    $sth = App::db()->query("SELECT c.`id`, c.`name`, SUBSTR(`name`, 1, 1) AS letter, c.`country`, c.`gmt`
                                FROM `city` c
                                WHERE c.`status` = '1' 
                                GROUP BY c.`id`
                                ORDER BY letter, c.`rating`");
                                
                    $rs = $sth->fetchAll();
                    $cc = 1;
                    
                    // цена доставки почтой россии по россии и зарубеж
                    $deliverycost_post_russia = getVariableValue('deliverycost_post');
                    $deliverycost_post_world  = getVariableValue('deliverycost_post_world');
                    
                    if (empty($id)) {
                        $hour = date('G');
                        $day  = date('w');
                    } else {
                        $hour = date('G', strtotime($order['user_basket_date']));
                        $day  = date('w', strtotime($order['user_basket_date']));
                    }
                    
                    foreach ($rs as $ck => $c) 
                    {
                        $c['postcost'] = (($c['country'] == 838 || in_array($c['country'], $SNGcountry)) ? $deliverycost_post_russia : $deliverycost_post_world);
                        
                        // dpd, если возможна доставка до конечного города
                        if ($ds[$c['id']]['dpd'])
                        {
                            $c['dpd_cost'] = $ds[$c['id']]['dpd']['cost'];
                            $c['time1']    = $ds[$c['id']]['dpd']['time1'];
                            $c['time2']    = $ds[$c['id']]['dpd']['time2'];
                        }
                        // цены расчитываются из учёта доставки до страны
                        elseif (!$ds[$c['id']]['dpd'] && $dsc[$c['country']]['dpd']) 
                        {
                            $c['dpd_cost'] = $dsc[$c['country']]['dpd']['cost'];
                            $c['time1']    = $dsc[$c['country']]['dpd']['time1'];
                            $c['time2']    = $dsc[$c['country']]['dpd']['time2'];
                        }
                        
                        $c['IMlog_cost'] = $ds[$c['id']]['IMlog']['cost'];
                        $c['IMtime']     = $ds[$c['id']]['IMlog']['time'];
                        
                        $c['IMlog_self_cost']      = $ds[$c['id']]['IMlog_self']['cost'];
                        $c['IMself_time']          = $ds[$c['id']]['IMlog_self']['time'];
                        $c['IMselfDeliveryPoints'] = $ds[$c['id']]['IMlog_self']['self-delivery-points'];
                        
                        $c['baltick_cost'] = $ds[$c['id']]['baltick']['cost'];
                        $c['baltickTime']  = $ds[$c['id']]['baltick']['time'];
                                        
                        // время доставки dpd
                        $dpdtime = array_diff(array($c['time1'], $c['time2']), array('0', ''));
                        foreach ($dpdtime as $k => $v) $dpdtime[$k] += (($hour < 17) ? 1 : 2);
                        $c['time'] = implode(' - ', array_unique($dpdtime));
                        // \ время доставки dpd
                        
                        $jcitys[$c['country']][$c['id']] = $c;
                        
                        if ($c['country'] == 838 && !empty($c['gmt']) || $c['country'] != 838) { 
                            $citys[$c['country']][$c['letter']][] = $c;
                            $cc++;
                        } elseif ($c['info'] == '') {
                            $citysAdd[$c['country']][$c['letter']][] = $c;
                        }
                    }
            
                    // дополняем буквы городами до ровного количества
                    foreach ($citys AS $ck => $country)
                    {
                        foreach ($country as $lk => $l) {
                            if (count($l) < 15 && count($citysAdd[$ck][$lk]) > 0) {
                                $citys[$ck][$lk] = array_merge($citys[$ck][$lk], array_slice($citysAdd[$ck][$lk], 0, 16 - count($l)));
                            }
                        }
                    }
                    
                    // разбиваем на колонки 
                    $columns = 3;
                    
                    foreach ($citys AS $ck => $country) {
                        if (count($country) >= $columns - 1)
                            $citys[$ck]['columns'] = array_chunk($country, round(count($country) / $columns), true);
                        else
                            $citys[$ck]['columns'][0] = $country;
                    }
                    
                    $this->view->setVar('citys', $citys);
                    $this->view->setVar('jcitys', json_encode($jcitys));
                
                    $this->page->module    = 'order.v3';
                    $this->page->translate = $this->page->getLangVariables();
            
                    $this->view->setVar('L', $this->page->translate);
            
                    $this->view->setVar('default_city_id', $city_id);
                    $this->view->setVar('default_city_name', $city_name);
                    $this->view->setVar('order_country', $country_id);
                    
                    $this->view->generate('order/citySelect.tpl');
                    
                    
                    exit();
                
                    break;
            
            
                /**
                 * скачать обои на рабочий стол 
                 */
                case 'getWallpapper':
                    
                    if (!empty($this->page->reqUrl[2]))
                    {
                        $good_id  = intval($this->page->reqUrl[2]);
                        
                        $Style = new \application\models\style($this->page->reqUrl[3]);
                        
                        if ($Style)
                        {
                            // получаем исходник для данной категории
                            $sth = App::db()->query("SELECT p.`picture_path`
                                        FROM `good_pictures` AS gp, `pictures` AS p 
                                        WHERE 
                                                gp.`good_id`  = '{$good_id}' 
                                            AND gp.`pic_name` = '" . styleCategory::$BASECATS[$Style->category]['src_name'] . "'
                                            AND gp.`pic_id`   = p.`picture_id` 
                                        LIMIT 1");
                
                            if ($sth->rowCount() == 1)
                            {
                                $src = $sth->fetch();
                                
                                $src_sizes = getimagesize(ROOTDIR . $src['picture_path']);
                                
                                $s = new Imagick(ROOTDIR . $src['picture_path']);
                                
                                $pb = $Style->print_block;
                                
                                $p  = round(($src_sizes[0] / $pb['front']['w']), 1);
                                                
                                if ($pb['wall']) 
                                {
                                    // Уменьшаем изображение из исходника на передок
                                    if ($pb['wall']['w'] >= $pb['wall']['h'])
                                    {
                                        $w = $pb['wall']['w'];
                                        $h = floor($s->getimageheight() / ($s->getimagewidth() / $w));
                                        
                                        if ($h < $pb['wall']['h'])
                                        {
                                            $h = $pb['wall']['h'];
                                            $w = floor($s->getimagewidth() / ($s->getimageheight() / $h));
                                        }
                                    }
                                    else
                                    {
                                        $h = $pb['wall']['h'];
                                        $w = floor($s->getimagewidth() / ($s->getimageheight() / $h));
                                        
                                        if ($w < $pb['wall']['w'])
                                        {
                                            $w = $pb['wall']['w'];
                                            $h = floor($s->getimageheight() / ($s->getimagewidth() / $w));
                                        }
                                    }
                                
                                    try
                                    {
                                        $s->scaleImage($w, $h);
            
                                        $i = new Imagick();
                                        $i->newImage($pb['wall']['w'], $pb['wall']['h'], new ImagickPixel("white"));
                                        $i->compositeImage($s, imagick::COMPOSITE_OVER, $pb['wall']['w'] / 2 - $w / 2, $pb['wall']['h'] / 2 - $h / 2);
                                        
                                        //imagecopyresampled($wall, $s, 0, 0, ($w - $pb['wall']['w']) / 2, ($h - $pb['wall']['h']) / 2, $w - ($w - $pb['wall']['w']) / 2, $h - ($h - $pb['wall']['h']) / 2, $src_sizes[0], $src_sizes[1]);
                                        
                                        $i->setCompression(Imagick::COMPRESSION_JPEG);
                                        $i->setCompressionQuality(100); 
                                        $i->setImageCompression(100); 
                                        $i->setImageCompressionQuality(95);
                                        $i->setImageFormat('png');
                                    }
                                    catch (Exception $e) { printr($e); exit(); }
                                    
                                    header('Content-type: image/png');
                                    exit($i);
                                }
                            }
                        }
                    }
                    
                break;
            
                /**
                 * Прикрепить фотографию к носителю
                 * могут только супер-админы
                 */
                case 'setgallerystyleid':
            
                    if ($this->user->meta->mjteam == 'super-admin' || $this->user->meta->mjteam == 'manager')
                    {
                        if (!empty($_GET['id']))
                        {
                            if (!empty($_GET['style_id']))
                            {
                                $sth = App::db()->prepare("UPDATE `gallery` SET `style_id` = :sid WHERE `gallery_picture_id` = :id LIMIT 1");
                                $sth->execute(array('sid' => $_GET['style_id'], 'id' => $_GET['id']));
                            }
                            else
                                exit('error:3');
                        }
                        else
                            exit('error:2');
                    }
                    else
                        exit('error:1');
            
                    break;
            
                /**
                 * проверка занятости названия работы
                 */
                case 'checkAvalibleGoodName':
                    
                    if ($_GET['q']) {
                        $sth = App::db()->prepare("SELECT COUNT(*) AS c FROM `goods` WHERE `good_name` = :name AND `good_visible` = 'true' LIMIT 1");
                        $sth->execute(array('name' => urldecode(trim($_GET['q']))));
                        $f = $sth->fetch();
                    }
                    
                    exit((string) $f['c']);
                    
                    break;
                    
                    
                case 'getBlogPopularTags':
                    
                    $sth = App::db()->query(sprintf("SELECT t.`tag_id`, t.`name`, t.`slug`, COUNT(up.`id`) AS count    
                                        FROM `tags` AS t, `tags_relationships` AS tr, `user_posts` AS up    
                                        WHERE  
                                                tr.`tag_id`    = t.`tag_id` 
                                            AND tr.`object_id`   = up.`id`
                                            AND tr.`object_type` = '0' 
                                            AND up.`post_status` = 'publish' 
                                            AND t.`raiting`      = '0' 
                                            AND t.`tag_id` NOT IN ('13775') 
                                            AND t.`synonym_id_blog` NOT IN ('13775')   
                                        GROUP BY t.`tag_id`   
                                        HAVING count > 5   
                                        LIMIT 100"));
                    
                    $this->view->setVar('topBlogTags', $sth->fetchAll());
                    $this->view->generate('blog/news.sidebar.tagCloud.tpl');
                
                    break;
                    
                    
                case 'getCatalogPopularTags':
                    
                    $sth = App::db()->Query(sprintf("SELECT t.`tag_id`, t.`name`, t.`slug`, t.`tag_ps_goods` as `count`   
                                        FROM `tags` AS t    
                                        WHERE t.`tag_ps_goods` >= '1' AND t.`tag_id` NOT IN ('13775')   
                                        GROUP BY t.`tag_id`    
                                        ORDER BY count DESC    
                                        LIMIT %d", (($_GET['n']) ? (int) $_GET['n'] : 20)));
                                
                    $this->view->setVar('tags', $sth->fetchAll());
                    $this->view->generate('catalog/list.sidebar.tagCloud.tpl');
                    
                    break;
                    
                    
                case 'city_autocomplit':
                    
                    $out = '';
                    
                    $q = addslashes(trim($_GET['q']));
                    
                    $sth = App::db()->query("SELECT c.`id`, c.`name`, c.`country`
                                FROM `city` c
                                WHERE c.`name` LIKE '$q%' AND c.`status` = '1'
                                LIMIT 10");
            
                    if ($sth->rowCount() > 0)
                    {
                        $rs = $sth->fetchAll();
            
                        if (getVariableValue('deliveryfree'))
                            $deliveryfreemin = getVariableValue('deliveryfreemin_post_allskins');
            
                        $post_cost_russia = getVariableValue('deliverycost_post');
                        $post_cost_world  = getVariableValue('deliverycost_post_world');
            
                        $sth = App::db()->prepare("SELECT `service`, `cost`, `time` FROM `delivery_services` WHERE `city_id` = :city");
            
                        foreach ($rs AS $c)
                        {
                            $sth->execute(array(
                                'city' => $c['id'],
                            ));
                            
                            $services = $sth->fetchAll();
            
                            foreach ($services as $s)
                            {
                                // если есть пункты самовывоза в  этом городе
                                if (strpos($s['service'], 'self') !== false)
                                {
                                    $sth2 = App::db()->query(sprintf("SELECT `id`, `city_id`, `service`, `name`, `address`, `schema` FROM `delivery_points` WHERE `city_id` = '%d' AND `service` = '%s'", $c['id'], $s['service']));
                                    $s['self-delivery-points'] = $sth2->fetchAll();
                                }
            
                                switch ($s['service'])
                                {
                                    case 'user':
                                        $s['time'] = $this->basket->getDeliveryTime('user');
                                        break;
            
                                    case 'deliveryboy':
                                    case 'metro':
                                        $s['time'] = $this->basket->getDeliveryTime('deliveryboy');
                                        break;
            
                                    case 'dpd':
                                    case 'baltick':
            
                                        if (is_numeric($s['time']))
                                        {
                                            $s['time'] .= ' ' . declineDay($s['time']);
                                        }
            
                                        break;
                                }
            
                                $c[$s['service']] = $s;
                            }
            
                            // не для москвы
                            if ($c['id'] != 1)
                            {
                                $c['post']['cost'] = (($c['country'] == 838 || in_array($c['country'], $SNGcountry)) ? $post_cost_russia : $post_cost_world);
                                $c['post']['time'] = '1-2 недели';
            
                                // если акция "бесплатная доставка" и не москва
                                if ($id != 1 && $deliveryfreemin && ($basket_sum) >= $deliveryfreemin)
                                {
                                    if ($country == 838)
                                    {
                                        // dpd бесплатно только по россии
                                        $c['dpd']['cost'] = 0;
                                    }
            
                                    // почта бесплатная везде
                                    $c['post']['cost'] = 0;
                                }
            
                                // если цена доставки dpd в конкретный город не известна
                                if ($c['country'] != 838 && !$c['dpd'] == 0)
                                {
                                    $sth2 = App::db()->query(sprintf("SELECT `time`, `cost` FROM `delivery_services_dpd_country` WHERE `country_id` = '%d' LIMIT 1", $country));
                                    $dpd = $sth2->fetch();
                                    $c['dpd']['cost'] = $dpd['cost'];
                                    $c['dpd']['time'] = $dpd['time'];
                                }
                            }
            
                            $out .= $c['name'] . '|' . $c['id'] . '|' . $c['dpd']['cost'] . '|' . $c['dpd']['time'] . '|' . $c['post']['cost'] . '|' . $c['country'] . '|' . $c['IMlog']['cost'] . '|' . $c['IMlog']['time'] . '|' . $c['IMlog_self']['cost'] . "\n";
                        }
                    }
                    else
                    {
                        $c['post']['cost'] = 400;
                        $c['post']['time'] = '1-2 недели';
                    
                        $out .= $q . '|0|||' . $c['post']['cost'].'|0||||';
                    }
                    
                    exit(trim($out));
                    
                break;
                
                case 'city_search':
                    
                    $post_cost_world  = getVariableValue('deliverycost_post_world');
                    
                    $q = addslashes(trim($_GET['q']));
                    
                    $sth = App::db()->query("SELECT c.`id`, c.`name`, c.`country`
                                FROM `city` c
                                WHERE c.`name` LIKE '$q%' AND c.`status` = '1'
                                LIMIT 10");
            
                    if ($sth->rowCount() > 0)
                    {
                        $rs = $sth->fetchAll();
            
                        if (getVariableValue('deliveryfree'))
                            $deliveryfreemin = getVariableValue('deliveryfreemin_post_allskins');
            
                        $post_cost_russia = getVariableValue('deliverycost_post');
            
                        $out = '';
            
                        $sth = App::db()->prepare("SELECT `service`, `cost`, `cost2`, `time` FROM `delivery_services` WHERE `city_id` = :city");
            
                        foreach ($rs AS $c)
                        {
                            $sth->execute(array(
                                'city' => $c['id'],
                            ));
                            
                            $services = $sth->fetchAll();
            
                            foreach ($services as $s)
                            {
                                // если есть пункты самовывоза в  этом городе
                                if (strpos($s['service'], 'self') !== false)
                                {
                                    $sth2 = App::db()->Query(sprintf("SELECT `id`, `city_id`, `service`, `name`, `address`, `schema` FROM `delivery_points` WHERE `city_id` = '%d' AND `service` = '%s'", $c['id'], $s['service']));
                                    $s['self-delivery-points'] = $sth2->fetchAll();
                                }
            
                                switch ($s['service'])
                                {
                                    case 'user':
                                        $s['time'] = $this->basket->getDeliveryTime('user');
                                        break;
            
                                    case 'deliveryboy':
                                    case 'metro':
                                        $s['time'] = $this->basket->getDeliveryTime('deliveryboy');
                                        break;
            
                                    case 'dpd':
            
                                        if (is_numeric($s['time']))
                                        {
                                            $s['time'] .= ' ' . declineDay($s['time']);
                                        } else {
                                            $foo = explode(' - ', $s['time']);
                                            
                                            if (count($foo) == 2)
                                                $s['time'] .= ' ' . declineDay(end($foo));
                                        }
                                        
                                        if ($basket_sum >= basket::$dpdMarginLimit && $s['cost2'])
                                            $s['cost'] = $s['cost2'];
            
                                        break;
                                }
            
                                $c[$s['service']] = $s;
                            }
            
                            // не для москвы
                            if ($c['id'] != 1)
                            {
                                $c['post']['cost'] = (($c['country'] == 838 || in_array($c['country'], $SNGcountry)) ? $post_cost_russia : $post_cost_world);
                                $c['post']['time'] = '1-2 недели';
            
                                // если акция "бесплатная доставка" и не москва
                                if ($id != 1 && $deliveryfreemin && $basket_sum >= $deliveryfreemin)
                                {
                                    if ($c['country'] == 838)
                                    {
                                        // dpd бесплатно только по россии
                                        $c['dpd']['cost'] = 0;
                                    }
            
                                    // почта бесплатная везде
                                    $c['post']['cost'] = 0;
                                }
            
                                // если цена доставки dpd в конкретный город не известна
                                if ($c['country'] != 838 && !$c['dpd'] == 0)
                                {
                                    $sth2 = App::db()->query(sprintf("SELECT `time`, `cost`, `cost2` FROM `delivery_services_dpd_country` WHERE `country_id` = '%d' LIMIT 1", $c['country']));
                                    $dpd = $sth2->fetch();
                                    
                                    $c['dpd']['cost'] = $dpd['cost'];
                                    $c['dpd']['time'] = $dpd['time'];
                                }
                                
                                if ($this->page->lang == 'en') {
                                    $c['post']['cost'] = round($c['post']['cost'] / $this->VARS['usdRate'], 1);
                                    $c['dpd']['cost'] = round($c['dpd']['cost'] / $this->VARS['usdRate'], 1);
                                }
                            }
            
                            $out[] = $c;
                        }
                    }
                    else
                    {
                        $c['post']['cost'] = $post_cost_world;
                        $c['post']['time'] = '1-2 недели';
            
                        if ($this->page->lang == 'en') 
                            $c['post']['cost'] = round($c['post']['cost'] / $this->VARS['usdRate'], 1);
            
                        $out[] = $c;
                    }
            
                    exit(json_encode($out));
            
                break;
                
                /**
                 * Автодополнение тегов
                 */
                case 'tag_autocomplit':
                    
                    $q = trim($_GET['q']);
                    
                    $sth = App::db()->query("SELECT `name` FROM `tags` WHERE `name` LIKE '" . addslashes(trim($_GET['q'])) . "%' AND `synonym_id` = '0' AND `tag_ps_goods` > '0' AND `name` NOT LIKE '% %' ORDER BY `tag_ps_goods` DESC LIMIT 10");
                    
                    foreach ($sth->fetchAll() AS $c) 
                    {
                        $out[] = stripslashes($c['name']);
                    }
            
                    exit(json_encode($out));
                    
                    break;
                    
                case 'good_autocomplit':
                
                    $q = trim(urldecode($_GET['q']));
                    $out = '';
                    
                    $sth = App::db()->prepare("SELECT `good_id`, `good_name`, u.`user_login` FROM `goods` g, `users` AS u WHERE g.`good_name` LIKE :q AND g.`good_visible` = 'true' AND g.`good_status` != 'customize' AND g.`user_id` = u.`user_id` ORDER BY g.`good_date` DESC LIMIT " . ($_GET['limit'] && $_GET['limit'] < 200 ? $_GET['limit'] : 30));
                    
                    $sth->execute(['q' => $q . '%']);
                    
                    foreach ($sth->fetchAll() AS $c)    {
                        $out .= stripslashes($c['good_name']) . ' от ' . $c['user_login'] . '|' . $c['good_id'] . "\n";
                    }
                    
                    exit($out);
                    
                    break;
                
                /**
                 * сгенерить картинку с капчёй
                 */
                case 'genCaptcha':
            
                    $src = "http://www.maryjane.ru/vendor/kcaptcha/index.php?".session_name()."=".session_id()."&r=".rand();
                    $img = "<img src='$src' alt='captcha' style='border:1px solid #ACAFB0;' height='50' width='108' />";
            
                    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
                    header('Cache-Control: no-store, no-cache, must-revalidate');
                    header('Cache-Control: post-check=0, pre-check=0', FALSE);
                    header('Pragma: no-cache');
                    header("Content-Type: text/html; charset=utf-8");
            
                    echo $img;
                    exit();
            
                break;
                
                
                /**
                 * подписаться на анонс
                 * подписка на уведомление о поступлении на склад какого-то носителя
                 */
                case 'announce_me':
            
                    if (!empty($_POST['email']) && !empty($_POST['id']))
                    {
                        $id  = (int) $_POST['id'];
                        $sid = (int) $_POST['sid'];
                        
                        try
                        {
                            $sth = App::db()->prepare("SELECT 
                                                    COUNT(*) AS already
                                                  FROM 
                                                    `good_stock__preorder`
                                                  WHERE 
                                                    `good_stock_id` = :id AND `good_id` = :gid AND `user_ip` = INET_ATON(:ip)");
                            
                            $sth->execute(array(
                                'ip'  => $_SERVER['REMOTE_ADDR'],
                                'id'  => $id,
                                'gid' => intval($_POST['good_id']),
                            ));
                            
                            $r = $sth->fetch();
                            
                            // принимаем максимум 3 предзаказа с одного ip
                            if ($r['already'] < 3)
                            {
                                $sth = App::db()->prepare("INSERT IGNORE INTO `good_stock__preorder`
                                            SET
                                                `user_id`    = :user,
                                                `user_email` = :email,
                                                `user_ip`    = INET_ATON(:ip),
                                                `style_id`   = :sid,
                                                `good_stock_id` = :id,
                                                `good_id` = :gid,
                                                `source` = :source
                                            ON DUPLICATE KEY UPDATE 
                                                `time` = NOW()");
                                                
                                $sth->execute(array(
                                    'user' => $this->user->id,
                                    'email' => addslashes(trim($_POST['email'])),
                                    'ip' => $_SERVER['REMOTE_ADDR'],
                                    'id' => $id,
                                    'sid' => $sid,
                                    'gid' => intval($_POST['good_id']),
                                    'source' => $_POST['source'],
                                ));
                
                                echo $sth->rowCount();
                            }
                        }
                        catch (Exception $e)
                        {
                            printr($e->getMessage());
                        }
                    }
            
                    if (empty($_SERVER['HTTP_X_REQUESTED_WITH']))
                        header('location: ' . $_SERVER['HTTP_REFERER']);
                        
                    exit();
            
                break;
            
                /**
                 * вкл / выкл смс информирование о смене статусов заказа
                 */
                case 'order_sms_info':
            
                    if ($this->user->authorized)
                    {
                        echo $this->user->setMeta('order_sms_info', (($_GET['sms_info']) ? 1 : 0));
                    }
                    
                    break;
            
                /**
                 * проверить получал ли пользователь бонусы за промо в выбранной соц.сети
                 */
                case 'social_promote_check':
                    
                    $socialNets = array(0 => 'facebook', 1 => 'vk', 2 => 'google');
            
                    if ($this->user->authorized && in_array($this->page->reqUrl[2], $socialNets)) 
                    {
                        $sth = App::db()->query("SELECT `user_bonus_id` FROM `user_bonuses` WHERE `user_id` = '" . $this->user->id . "' AND `user_bonus_comment` = 'пост в соцсети' AND `user_bonus_info` = '" . array_search($this->page->reqUrl[2], $socialNets) . "' LIMIT 1");
                        echo $sth->rowCount();
                        exit();
                    }
                    else 
                        exit('-1');
                    
                break;
                
                /**
                 * Выдать 100 бонусов за ссылку в соцсети
                 */
                case 'social_promote_register':
                    
                    $out = array('status' => 'ok', 'message' => 'Успех! получите распишитесь');
                    
                    $socialNets = array(0 => 'facebook', 1 => 'vk', 2 => 'google');
                    
                    if (!empty($this->page->reqUrl[3]))
                    {
                        if (!empty($this->page->reqUrl[2]) && in_array($this->page->reqUrl[2], $socialNets))
                        {
                            $email  = addslashes(trim($this->page->reqUrl[3]));
                            $social = addslashes(trim($this->page->reqUrl[2]));
                            $social_user_id = addslashes(trim($this->page->reqUrl[4])); // айди юзера в соцсети
                            
                            // если пользователь не авторизован
                            if (!$this->user->authorized)
                            {
                                $sth = App::db()->query("SELECT `user_id` FROM `users` WHERE `user_email` = '$email' AND `user_status` = 'active'");
                                
                                $users = $sth->rowCount();
                                
                                // если на этот email зарегистрирован один пользователь 
                                if ($users == 1) 
                                {
                                    $u = $sth->fetch();
                                    
                                    $this->user->id = $u['user_id'];
                                    
                                    // если на этот аккаунт уже прикреплён ФБ и он совпадает с пришедшим
                                    if ($social == 'facebook' && !empty($this->user->meta['user_facebook']) && $this->user->meta['user_facebook'] == $social_user_id)
                                    {
                                        $this->user->authorize();
                                        
                                        $out['info'] = 'user_found';
                                        $out['user'] = $this->user->id;
                                    }
                                    else
                                    {
                                        $out['info'] = 'comeback';
                                        $out['key'] = md5($email);
                                        $out['next'] = '/ajax/social_promote_register/' . $social . '/' . $email . '/' . $social_user_id . '/success/'; 
                                    }
                                }
                                // отправляем на страницу объединения аккаунтов
                                elseif ($users > 1) 
                                {
                                    $out['info'] = 'merge';
                                    $out['key'] = md5($email);
                                    $out['next'] = '/ajax/social_promote_register/' . $social . '/' . $email . '/' . $social_user_id . '/success/'; 
                                }
                                // если email не занят
                                // производим быструю регистрацию
                                else
                                {
                                    $this->user->create(array('user_email' => $email));
                                    $this->user->authorize();
                                    
                                    // Отправка уведомления
                                    App::mail()->send(array($this->user->id), 156, array(
                                        'code' => md5($this->user->id),
                                        'user_id' => $this->user->id,
                                        'user_login' => $this->user->user_login,
                                        'user_password' => $this->user->password
                                    ));
                                    
                                    $out['info'] = 'user_added';
                                }
                            }
            
            
                            $sth2 = App::db()->query("SELECT `user_id` FROM `users_meta` WHERE `meta_name` = 'user_" . $social . "' AND `meta_value` = '$social_user_id' LIMIT 1");
                                    
                            if ($sth2->rowCount() == 0)
                                $this->user->setMeta('user_' . $social, $social_user_id);
                            
                            
                            // проверяем не выдавали ли мы уже этому пользователю 100р ранее
                            $sth2 = App::db()->query("SELECT `user_bonus_id` FROM `user_bonuses` WHERE `user_id` = '" . $this->user->id . "' AND `user_bonus_comment` = 'пост в соцсети' AND `user_bonus_info` = '" . array_search($social, $socialNets) . "' LIMIT 1");
                            
                            if ($sth2->rowCount() == 0)
                            {
                                $this->user->addBonus(getVariableValue($social . '_bonusesForLink'), 'пост в соцсети', array_search($social, $socialNets));
                                
                                if ($this->page->reqUrl[5] == 'success')
                                {
                                    header('location: /basket/');
                                    exit();
                                }
                            }
                            else
                            {
                                $out['status']  = 'error';
                                $out['message'] = 'Вы уже получали бонусы за ссылку в данной социальной сети';
                            }
                        }
                        else
                        {
                            $out['status']  = 'error';
                            $out['message'] = 'Не указана социальная сеть';
                        }
                    }
                    else 
                    {
                        $out['status']  = 'error';
                        $out['message'] = 'Не указан адресс электронной почты';
                    }
                    
                    exit(json_encode($out));
                    
                break;
                
                /**
                 * получить фотографии к работам с данным тегом
                 */
                case 'get_tag_photo':
                    
                    $p      = intval($_GET['page']);
                    $onpage = 8;
                    
                    if ($this->page->reqUrl[2] == 'detskie')
                    {
                        $sth = App::db()->query("SELECT CEIL(COUNT(*) / $onpage) AS c FROM `gallery` AS ga WHERE ga.`gallery_picture_visible` = 'true' AND ga.`gallery_small_picture` > '0' AND ga.`sex` = '-2'");
                        $foo = $sth->fetch();
                        $tp = $foo['c'];
                        
                        $q = "SELECT ga.`gallery_picture_id`, ga.`good_id`, p.`picture_path`, u.`user_login`
                              FROM `gallery` AS ga, `pictures` AS p, `goods` g, `users` u
                              WHERE ga.`gallery_picture_visible` = 'true' AND p.`picture_id` = ga.`gallery_small_picture` AND ga.`sex` = '-2' AND ga.`good_id` = g.`good_id` AND g.`user_id` = u.`user_id` 
                              GROUP BY ga.`gallery_picture_id` 
                              ORDER BY ga.`gallery_picture_date` %s 
                              LIMIT %d, %d";
                              
                        $sth = App::db()->query(sprintf($q, (($p >= 0) ? 'DESC' : 'ASC'), (($p >= 0) ? $p : $tp + $p) *  $onpage, $onpage));
            
                        if ($sth->rowCount() == 0)
                        {
                            if ($p >= 0) {
                                $p = $p % $tp;
                            } else {
                                if (($p % $tp) == 0)
                                    $p = 0;
                                else
                                    $p = $tp + ($p % $tp);
                            }
                            
                            $sth = App::db()->query(sprintf($q, (($p >= 0) ? 'DESC' : 'ASC'), $p *  $onpage, $onpage));
                        }
                                
                        exit(json_encode($sth->fetchAll()));
                    }
                    
                break;
                
                case 'like':
                    
                    try
                    {
                        $G = new \application\models\good($this->page->reqUrl[2]);
                        
                        //if ($this->user->id == 27278 || $this->user->id == 105091) {
                            $result = $G->likev2($this->user, ($_POST['style_id'] && $_POST['style_id'] != 'NaN') ? 'catalog_preview_' . $_POST['style_id'] : 'good_preview', ($this->page->reqUrl[4] == 'negative') ? 1 : 0);
                        //} else
                        //  $result = $G->like($this->user->id, 'good_preview', ($this->page->reqUrl[4] == 'negative') ? 1 : 0);
                        
                        // Если Сергей что-то добавляет себе в избранное, то отмечаем эту работу как выбор арт-дирректора 
                        if ($this->user->id == 6199)
                        {
                            if ($result == 'added')
                                $G->mark = 'artdir'; 
                            
                            if ($result == 'deleted')
                                $G->mark = '';
                            
                            $G->save();
                        }
                        
                        if ($this->page->isAjax) {
                            exit('ok:' . $result);
                        } else {
                            if (!empty($_SERVER['HTTP_REFERER'])) {
                                header('location:' . $_SERVER['HTTP_REFERER']);
                           } else {
                                header('location: /');
                           }
                        }
                    }
                    catch (Exception $e)
                    {
                        exit('error:' . $e->getMessage());
                    }
                         
                break;
                
                /**
                 * проверить лайкал ли пользователь эту работу (и как лайкал)
                 */
                case 'checklike':
                    
                    if ($this->user->authorized) {
                        $sth = App::db()->query(sprintf("SELECT `negative` FROM `good_likes` WHERE `user_id` = '%d' AND `good_id` = '%d' LIMIT 1", $this->user->id, trim($this->page->reqUrl[2])));
                        $foo = $sth->fetch();
                    }
                    
                    exit((string) $foo['negative']);
                    
                    break;
            
            
                case 'sale_clock':
                    
                    
                   $sth = App::db()->query("SELECT COUNT(gs.`good_stock_quantity` +- gs.`good_stock_inprogress_quantity`) AS c
                                     FROM `good_stock` gs, `goods` g, `styles` AS s, `styles_category` AS sc
                                     WHERE 
                                            gs.`good_stock_status` = 'few'
                                        AND gs.`good_stock_quantity` +- gs.`good_stock_inprogress_quantity` > '0'
                                        AND gs.`good_stock_price`   = '150'
                                        AND gs.`good_id` > '0'
                                        AND gs.`good_stock_visible` > '0'
                                        AND gs.`good_id` = g.`good_id`
                                        AND g.`good_visible` = 'true'
                                        AND g.`good_status` != 'customize'
                                        AND gs.`style_id` = s.`style_id`
                                        AND sc.`id` = s.`style_category`
                                        AND sc.`cat_parent` = '1'");
                    
                    $sale_count = $sth->fetch();
                                        
                    exit((string) $sale_count['c']);
                    
                break;
            
                case 'gwh':
                
                    $type = (!empty($this->page->reqUrl[2])) ? trim($this->page->reqUrl[2]) : 'com_blog';
                    //$num  = (!empty($this->page->reqUrl[3])) ? intval($this->page->reqUrl[3]) : 6;
                    $num = 6;
                
                    $data          = array();
                    $data['data']  = array();
                    $data['error'] = '';
            
                    $q = "SELECT
                            ubc.`comment_id` AS `id`,
                            ubc.`comment_text` AS `text`,
                            ubc.`user_id` AS `user_id`,
                            ubc.`comment_date` AS `date`,
                            ubc.`object_id` AS `to`,
                            ubc.`object_type`,
                            u.`user_login`
                            "
                            .
                                ($_GET['tpl'] == 'com_blog' ? ", up.`post_slug`" : '')
                            .
                            "
                          FROM 
                            `comments` AS ubc, `users` AS u
                            "
                            .
                                ($_GET['tpl'] == 'com_blog' ? ", `user_posts` up" : '')
                            .
                            "
                          WHERE
                                ubc.`user_id` = u.`user_id`
                            AND ubc.`comment_text` <> ''
                            AND ubc.`comment_visible` = '1'
                            AND IFNULL((SELECT SUM(`carma`) FROM `carma_comments` WHERE `comment_id` = ubc.`comment_id`), 0) > '".carma::$carmaHideComment."'
                            "
                            .
                                ($_GET['tpl'] == 'com_blog' ? " AND ubc.`object_id` = up.`id`" : '')
                            .
                            "
                          ORDER BY ubc.`comment_date` DESC, ubc.`comment_id` DESC
                          LIMIT $num";
            
                    try
                    {
                        $sth = App::db()->query($q);
                
                        if ($sth->rowCount() == 0)
                        {
                            $data['error'] = 'Ничего не извлечено';
                        }
                        else
                        {
                            $maxlen = 50;
                
                            foreach ($sth->fetchAll() as $row)
                            {
                                switch ($row['object_type']) {
                    
                                    case 'blog':
                                        $row['to_word'] = 'к посту';
                                        $row['to_link'] = '/blog/view/';
                                    break;
                
                                    case 'good':
                                        $row['to_word'] = 'к работе';
                                        $row['to_link'] = '/voting/view/';
                                    break;
                
                                    case 'gallery':
                                        $row['to_word'] = 'к фотографии';
                                        $row['to_link'] = '/gallery/view/';
                                    break;
                
                                    default:
                                        $data['error'] = 'Непонятно, что показывать?';
                                    break;
                                }
                
                                $text = strip_tags(stripslashes($row['text']));
                
                                if (iconv_strlen($text, 'UTF-8') > $maxlen)
                                {
                                    $cutPos = iconv_strpos($text, ' ', $maxlen, 'UTF-8');
                                    $text = iconv_substr($text, 0, (empty($cutPos)) ? $maxlen : $cutPos, 'UTF-8') . ' ...';
                                }
                            
                                $title = stripslashes($row['to_title']);
                                if (iconv_strlen($title, 'UTF-8') > 30)
                                {
                                    $cutPos = iconv_strpos($title, ' ', 30, 'UTF-8');
                                    $title = iconv_substr($title, 0, (empty($cutPos)) ? 30 : $cutPos, 'UTF-8') . ' ...';
                                }
                                
                                $COMMENTS[] = array(
                                    'ID'        => $row['id'],
                                    'TEXT'      => $text,
                                    'DATE'      => datefromdb2textdate($row['date'], 1),
                                    'USERID'    => $row['user_id'],
                                    'USER'      => str_replace('.livejournal.com', '', $row['user_login']),
                                    'AVATAR'    => userId2userGoodAvatar($row['user_id'], 50),
                                    'TO'        => $row['to'],
                                    'TOTITLE'   => $tow['title'],
                                    'TO_WORD'   => $row['to_word'],
                                    'TO_LINK'   => $row['to_link'],
                                    'post_slug' => $row['post_slug'],
                                );
                            }
                            
                            $this->view->setVar('COMMENTS', $COMMENTS);
                        }
                    }
                    catch (Exception $e)
                    {
                        $data['error'] = $e->getMessage();
                    }
                
                    $this->view->generate('blog/news.sidebar.comments.tpl');
                    exit ();
                break;
                        
                case 'social_get':
                    $net = addslashes(trim($this->page->reqUrl[2]));
                    
                    $sth = App::db()->query("SELECT `meta_value` FROM `users_meta` WHERE `user_id` = '" . $this->user->id . "' AND `meta_name` = 'user_" . $net . "_active' LIMIT 1");
                    if ($sth->rowCount() == 0)
                        exit('1');
                    else {
                        $row = $sth->fetch();
                        echo $row['meta_value'];
                    }
                    exit();
                break;
                
                case 'social_set':
                    $net = addslashes(trim($this->page->reqUrl[2]));
                    App::db()->query("INSERT INTO `users_meta` SET `user_id` = '" . $this->user->id . "', `meta_name` = 'user_" . $net . "_active', `meta_value` = '1' ON DUPLICATE KEY UPDATE `meta_value` = '1'");
                    App::db()->query("INSERT INTO `users_meta` SET `user_id` = '" . $this->user->id . "', `meta_name` = 'user_" . $net . "', `meta_value` = '" . intval($this->page->reqUrl[3]) . "' ON DUPLICATE KEY UPDATE `meta_value` = '" . intval($this->page->reqUrl[3]) . "'");
                    exit();
                break;
                
                case 'social_disconect':
                    $net = addslashes(trim($this->page->reqUrl[2]));
                    App::db()->query("INSERT INTO `users_meta` SET `user_id` = '" . $this->user->id . "', `meta_name` = 'user_" . $net . "_active', `meta_value` = '0' ON DUPLICATE KEY UPDATE `meta_value` = '0'");
                    exit();
                break;
                
                // Удалить СТ из корзины
                case 'delete_gift':
                    $this->basket->removeGift(intval($this->page->reqUrl[2]), 10);
                    header('location: /' . $this->page->module . '/');
                break;
            
                // Удалить товар из корзины
                case 'delete_good':
                    $this->basket->removeGood(intval($this->page->reqUrl[2]), intval($this->page->reqUrl[3]), 10);
                    
                    if (!$_GET['ajax'])
                        header('location: /' . $this->page->module . '/');
                    
                    exit(json_encode(array($this->basket->getBasketSum() - min($this->user->user_bonus, round($basket_sum / 100 * $this->VARS['maxParticalPayPercent'])), $this->basket->getBasketSumBonusBack())));
                break;
                
                case 'getQuickBasket':
                
                    $k                     = 0;
                    $totalPrice            = 0;
                    $totalPriceWithoutDisc = 0;
                    $totalDiscount         = 0;
                    
                    $goods = array();
                
                    foreach ($this->basket->basketGoods as $g)
                    {
                        $totalPriceWithoutDisc += $g['price'] * $g['quantity'];
                        $totalPrice += $g['price'] = $g['tprice'];
                        $totalDiscount         += ($g['price'] * $g['quantity']) - $price;
                
                        $g['discount'] = intval($g['discount']);
                
                        $goods[] = $g;
                        
                        if ($g['cat_parent'] > 1) 
                            $gadgets++;
                        else
                            $wear++;
                    }
                    
                    foreach ($this->basket->basketGifts as $v)
                    {
                        $totalPrice += $v['priceTotal'];
                        $totalPriceWithoutDisc += $v['priceTotal'];
                        
                        $gifts[] = $v;
                    }
                
                    $this->view->setVar('goods', $goods);
                    $this->view->setVar('gifts', $gifts);
                
                    $bonusPresent = getVariableValue('bonusPresent');
            
                    if ($this->user->user_bonus > 0)
                    {
                        // дизайнерам разрешаем оплачивать заказы бонусами полностью
                        // всем остальным можно оплатить юонусами только часть заказа
                        $this->user->user_bonus = min($this->user->user_bonus, round($totalPrice / 100 * $this->VARS['maxParticalPayPercent']));
                            
                        $this->view->setVar('particalPayPercent', $this->VARS['maxParticalPayPercent']);
                        $this->view->setVar('currentuser', objectToArray($this->user->info));
                        
                    }
            
                    $this->view->setVar('totalDiscount', $totalDiscount);
                    $this->view->setVar('basket_sum_minus_bonuses', round((($basket_sum - $this->user->user_bonus > 0) ? $basket_sum - $this->user->user_bonus : 0) / ($this->page->lang == 'en' ? $this->VARS['usdRate'] : 1), 1));
                    $this->view->setVar('totalPriceWithoutDisc', $totalPriceWithoutDisc);
                    
                    /*
                    if ($gadgets > 0)
                        $this->view->setVar('deliver_srok', $this->basket->getDeliveryTimeGadgets('user'));
                    else 
                        $this->view->setVar('deliver_srok', $this->basket->getDeliveryTime('user'));
                    */
                    
                    // сообщение о  бесплатной доставке при достижении определённого порога
                    if ($this->user->city == 'Москва')
                    //if ($this->user->id == 27278 || $this->user->id == 6199 || $this->user->id == 105091 || $this->user->id == 63250)
                    {
                        $this->view->setVar('freedelivery_rest', getVariableValue('deliveryfreemin_deliveryboy') - $this->basket->basketSum);
                    }
            
                    // случайный стикерсет
                    if (!$stickersets = App::memcache()->get('stickersets'))
                    {
                        try
                        {
                            $sth = App::db()->prepare("SELECT 
                                                    g.`good_id`, g.`good_name`, g.`visits`, g.`good_likes`, p.`picture_path`, u.`user_id`, u.`user_login`, u.`user_designer_level`, u.`user_name`, u.`user_show_name`, u.`user_url`, u.`user_city`
                                                  FROM 
                                                    `goods` g, `users` u, `good_pictures` gp, `pictures` p, `good_pictures` gp2
                                                  WHERE
                                                        gp.`pic_name` = 'stickerset_preview'
                                                    AND gp.`pic_id` = p.`picture_id`
                                                    AND gp.`good_id` = g.`good_id`
                                                    
                                                    AND gp2.`pic_name` = 'stickerset'
                                                    AND gp2.`pic_id` > '0'
                                                    AND gp2.`good_id` = g.`good_id`
                                                    
                                                    AND g.`user_id` = u.`user_id`
                                                  GROUP BY 
                                                    g.`good_id`");
                        }
                        catch(PDOException $e) 
                        {  
                            printr($e->getMessage());  
                        }
            
                        $sth->execute();
                        $stickersets = $sth->fetchAll(PDO::FETCH_ASSOC);
                        
                        App::memcache()->set('stickersets', $stickerset, false, 10 * 7200);
                    }
                    
                    shuffle($stickersets);
                    
                    $this->view->setVar('stickerset', array_slice($stickersets, 0, 3));  
                    
                    
                    // случайная упаковочная бумага
                    if (!$packing = App::memcache()->get('QBpacking')) 
                    {
                        try
                        {
                            $sth = App::db()->prepare("SELECT 
                                                    g.`gift_id`, g.`gift_name`, p.`picture_path`
                                                  FROM 
                                                    `gifts` g, `pictures` p
                                                  WHERE
                                                        g.`gift_type` = 'packing'
                                                    AND g.`picture_id` = p.`picture_id`
                                                    AND g.`gift_visible` = '1'
                                                GROUP BY g.`gift_id`");
                                                
                            $sth->execute();
                            $packing = $sth->fetchAll(PDO::FETCH_ASSOC);
                            
                            App::memcache()->set('QBpacking', $packing, false, 10 * 7200);
                        }
                        catch(PDOException $e) 
                        {  
                            printr($e->getMessage());  
                        }
                    }
                    $this->view->setVar('QBpacking', $packing);
                
                    $this->view->generate('order/basket.quick.v2.tpl');
                    
                break;
                
                case 'getBasketSum':
                
                    $totalPrice =(int) $this->basket->getBasketSum();
                    
                    if ($this->user->meta->goodApproved)
                        $this->VARS['maxParticalPayPercent'] = 100;
                
                    $final = $totalPrice - min($this->user->user_bonus - $exchengedBonuses, round($totalPrice / 100 * $this->VARS['maxParticalPayPercent']));
                    //echo $totalPrice - min($this->user->user_bonus - $exchengedBonuses, round($totalPrice / 100 * $this->VARS['maxParticalPayPercent']));
                
                    echo json_encode(array(
                        'total'    => $totalPrice, 
                        'final'    => $final,
                        'partical' => min($this->user->user_bonus - $exchengedBonuses, round($totalPrice / 100 * $this->VARS['maxParticalPayPercent'])),
                        'back'     => ceil($totalPrice / 100 * $this->user->buyerLevel2discount())
                    ));
                
                    break;
                
                /**
                 * ajax
                 * получить блок рекомендаций для каталога (внутряк работы)
                 * новинки + популярные + победители + похожие
                 */
                case 'recommendations':
                    
                    $columns    = 4;        
                    $x_offset   = 14;
                    $y_offset   = 0;
                    $x_paddings = 0;
                    $y_paddings = 14;
                    
                    try
                    {
                        /**
                         * похожие
                         * работы пересекающиеся по тегам
                         */
                        if (!empty($_GET['good_id']))
                        {
                            $sth = App::db()->prepare("SELECT 
                                        g.`good_id`, g.`good_name`, g.`good_discount`, c.`hex` AS bg, p.`picture_path` AS picture, u.`user_login`, gp.`pic_w`, gp.`pic_h`
                                    FROM 
                                        `tags_relationships` gtr, `tags_relationships` gtr2, `tags` t, `goods` g, `good_stock_colors` AS c, `good_pictures` gp, `pictures` AS p, `users` AS u
                                    WHERE 
                                            gtr.`object_id`   = :id
                                        AND gtr.`object_type` = '1'
                                        and gtr.`tag_id` = t.`tag_id` 
                                        and t.`tag_ps_goods` > '1'
                                        and gtr.`tag_id` = gtr2.`tag_id`
                                        AND gtr2.`object_id` = g.`good_id`
                                        AND gtr2.`object_type` = '1'
                                        AND (g.`good_status` = 'printed' OR g.`good_status` = 'pretendent')
                                        AND gp.`good_id` = g.`good_id`
                                        AND gp.`pic_name` = 'good_preview'
                                        AND gp.`pic_id`     = p.`picture_id`
                                        AND g.`good_visible`   = 'true'
                                        AND g.`ps_onmain_id`   = c.`id`
                                        AND g.`user_id`        = u.`user_id`
                                    group by gtr2.`object_id`
                                    ORDER BY g.`good_likes`
                                    LIMIT 8");
                            
                            $sth->execute(array(
                                'id' => $_GET['good_id'],
                            ));
                            
                            $goods['similar']['title'] = 'Похожие';
                            $goods['similar']['goods'] = $sth->fetchAll(PDO::FETCH_ASSOC);
                        }
                        
                        $goods['new']['title'] = 'Новинки';
                        
                        if (!$goods['new']['goods'] = App::memcache()->get('recNew'))
                        {
                            $sth = App::db()->prepare("SELECT g.`good_id`, g.`good_name`, g.`good_discount`, c.`hex` AS bg, p.`picture_path` AS picture, u.`user_login`, IFNULL(gw.`place`, 0) place, g.`good_likes` AS likes, gp.`pic_w`, gp.`pic_h`
                                            FROM `good_stock_colors` AS c, `good_pictures` gp, `pictures` AS p, `users` AS u, `goods` AS g
                                                LEFT JOIN `good_winners` AS gw ON g.`good_id` = gw.`good_id`
                                            WHERE
                                                    (g.`good_status` = 'printed' OR g.`good_status` = 'pretendent')
                                                AND g.`good_visible`   = 'true'
                                                AND gp.`good_id` = g.`good_id`
                                                AND gp.`pic_name` = 'good_preview'
                                                AND gp.`pic_id`     = p.`picture_id`
                                                AND g.`ps_onmain_id`   = c.`id`
                                                AND g.`user_id`        = u.`user_id`
                                            GROUP BY g.`good_id`
                                            ORDER BY  g.`good_voting_end` DESC, gw.`place` DESC, likes DESC, g.`good_date` DESC, g.`user_id`
                                            LIMIT 0, 4");
                            
                            $sth->execute();
                            
                            $goods['new']['goods'] = $sth->fetchAll(PDO::FETCH_ASSOC);
                            
                            App::memcache()->set('recNew', $goods['new']['goods'], false, 10800);
                        }
                        
                        
                        $goods['popular']['title'] = 'Популярные';
                        
                        if (!$goods['popular']['goods'] = App::memcache()->get('recPopular'))
                        {
                            $sth = App::db()->prepare("SELECT g.`good_id`, g.`good_name`, g.`good_discount`, c.`hex` AS bg, p.`picture_path` AS picture, u.`user_login`, IFNULL(gw.`place`, 0) place, g.`good_likes` AS likes, gp.`pic_w`, gp.`pic_h`
                                        FROM `good_stock_colors` AS c, `good_pictures` gp, `pictures` AS p, `users` AS u, `goods` AS g
                                            LEFT JOIN `good_winners` AS gw ON g.`good_id` = gw.`good_id`
                                        WHERE
                                                (g.`good_status` = 'printed' OR g.`good_status` = 'pretendent')
                                            AND g.`good_visible`   = 'true'
                                            AND gp.`good_id` = g.`good_id`
                                            AND gp.`pic_name` = 'good_preview'
                                            AND gp.`pic_id`     = p.`picture_id`
                                            AND g.`ps_onmain_id`   = c.`id`
                                            AND g.`user_id`        = u.`user_id`
                                        GROUP BY g.`good_id`
                                        ORDER BY  g.`good_sold_printshop` DESC, g.`user_id`
                                        LIMIT 0, 4");
                                        
                            $sth->execute();
                            
                            $goods['popular']['goods'] = $sth->fetchAll(PDO::FETCH_ASSOC);
                            
                            App::memcache()->set('recPopular', $goods['popular']['goods'], false, 10800);
                        }
                        
                        
                        $goods['winners']['title'] = 'Победители';
                        
                        if (!$goods['winners']['goods'] = App::memcache()->get('recWinners'))
                        {
                            $sth = App::db()->prepare("SELECT g.`good_id`, g.`good_name`, g.`good_discount`, c.`hex` AS bg, p.`picture_path` AS picture, u.`user_login`, IFNULL(gw.`place`, 0) place, g.`good_likes` AS likes, gp.`pic_w`, gp.`pic_h`
                                        FROM `good_stock_colors` AS c, `good_pictures` gp, `pictures` AS p, `users` AS u, `goods` AS g
                                            LEFT JOIN `good_winners` AS gw ON g.`good_id` = gw.`good_id`
                                        WHERE
                                                (g.`good_status` = 'printed' OR g.`good_status` = 'pretendent')
                                            AND g.`good_visible`   = 'true'
                                            AND gp.`good_id` = g.`good_id`
                                            AND gp.`pic_name` = 'good_preview'
                                            AND gp.`pic_id`     = p.`picture_id`
                                            AND g.`ps_onmain_id`   = c.`id`
                                            AND g.`user_id`        = u.`user_id`
                                        GROUP BY g.`good_id`
                                        ORDER BY  g.`good_likes` DESC, g.`user_id`
                                        LIMIT 0, 4");
                            
                            $sth->execute();
                            
                            $goods['winners']['goods'] = $sth->fetchAll(PDO::FETCH_ASSOC);
                        
                            App::memcache()->set('recWinners', $goods['winners']['goods'], false, 10800);
                        }
                        
                        // всё в одну кучу
                        $goods = array('all' => array('goods' => array_merge($goods['similar']['goods'], $goods['new']['goods'], $goods['popular']['goods'], $goods['winners']['goods'])));
                        
                        foreach ($goods as $k1 => $r) {
                                
                            $goods[$k1]['height'] = 0;
                            
                            foreach ($r['goods'] as $k => &$g) {
                                
                                $g['good_name'] = stripslashes($g['good_name']);
                                
                                // расчитываем координаты
                                $goods[$k1]['goods'][$k]['i'] = $k % $columns;
                                $goods[$k1]['goods'][$k]['h'] = ($g['pic_h'] + $y_paddings) + $y_offset;
                                $goods[$k1]['goods'][$k]['x'] = ($goods[$k1]['goods'][$k]['i'] * $g['pic_w']) + ($goods[$k1]['goods'][$k]['i'] * $x_offset) + ($goods[$k1]['goods'][$k]['i'] * $x_paddings);
                                $goods[$k1]['goods'][$k]['y'] = $goods[$k1]['goods'][$k - $columns]['y'] + $goods[$k1]['goods'][$k - $columns]['h'];
                                
                                if ($goods[$k1]['goods'][$k]['y'] + $goods[$k1]['goods'][$k]['h'] >= $goods[$k1]['height']) {
                                    $goods[$k1]['height'] = $goods[$k1]['goods'][$k]['y'] + $goods[$k1]['goods'][$k]['h'];
                                }
                            }
                        }
                        
                        $this->view->setVar('recommendations', $goods);
                    }
                    catch (Exception $e)
                    {
                        printr($e->getMessage());
                    }
                    
                    $this->view->generate('catalog/recomended.v2.tpl');
                    exit();
                    
                    break;
                
                
                case 'getNeighborGoods':
                    
                    $p = intval($_GET['page']);
                    $onpage = 8;
                     
                    switch($_GET['orderby']) 
                    {
                        case 'new':
                            $orderBy = " g.`good_voting_end` DESC, gw.`place` DESC, likes DESC, g.`good_date`";
                        break;
                    
                        case 'grade':
                            $orderBy = " g.`good_likes`";
                        break;
                    
                        // похожие
                        case 'similar':
                            $orderBy = " g.`good_likes`";
                            $p = 0;
                            $onpage = 1000;
                        break;
                    
                        default:
                        case 'popular':
                            $orderBy = " g.`good_sold_printshop`";
                        break;
                    }
                    
                    /**
                     * похожие
                     * работы пересекающиеся по тегам
                     */
                    if ($_GET['orderby'] == 'similar' && !empty($_GET['good_id']))
                    {
                        $q = "SELECT g.`good_id`, g.`good_name`, c.`hex` AS bg, p.`picture_path`, u.`user_login`, gp.`pic_w`, gp.`pic_h`
                                FROM 
                                    `tags_relationships` gtr, `tags_relationships` gtr2, `tags` t, `goods` g, `good_stock_colors` AS c, `good_pictures` gp, `pictures` AS p, `users` AS u
                                WHERE 
                                        gtr.`object_id` = '" . intval($_GET['good_id']) . "'
                                    and t.`tag_id` = gtr.`tag_id`
                                    and t.`tag_ps_goods` > '1'
                                    and gtr.`tag_id` = gtr2.`tag_id`
                                    AND gtr2.`object_id` = g.`good_id`
                                    AND gtr.`object_type` = '1'
                                    AND gtr2.`object_type` = '1'
                                    AND (g.`good_status` = 'printed' OR g.`good_status` = 'pretendent')
                                    AND g.`good_visible`   = 'true'
                                    AND gp.`good_id` = g.`good_id`
                                    AND gp.`pic_name` = 'good_preview'
                                    AND gp.`pic_id`     = p.`picture_id`
                                    AND g.`ps_onmain_id`   = c.`id`
                                    AND g.`user_id`        = u.`user_id`
                                group by gtr2.`good_id`
                                ORDER BY $orderBy
                                LIMIT 6";
                        
                        $sth = App::db()->query($q);
                    }
                    else 
                    {
                        $q = "SELECT g.`good_id`, g.`good_name`, c.`hex` AS bg, p.`picture_path`, u.`user_login`, IFNULL(gw.`place`, 0) place, g.`good_likes` AS likes, gp.`pic_w`, gp.`pic_h`
                                FROM `good_stock_colors` AS c, `good_pictures` gp, `pictures` AS p, `users` AS u, `goods` AS g
                                    LEFT JOIN `good_winners` AS gw ON g.`good_id` = gw.`good_id`
                                WHERE
                                        (g.`good_status` = 'printed' OR g.`good_status` = 'pretendent')
                                    AND g.`good_visible`   = 'true'
                                    AND gp.`good_id` = g.`good_id`
                                    AND gp.`pic_name` = 'good_preview'
                                    AND gp.`pic_id`     = p.`picture_id`
                                    AND g.`ps_onmain_id`   = c.`id`
                                    AND g.`user_id`        = u.`user_id`
                                GROUP BY g.`good_id`
                                ORDER BY $orderBy " . (($p >= 0) ? 'DESC' : 'ASC') . ", g.`user_id`"
                                . 
                                (($_GET['alltopage']) ? "LIMIT 0, " . $p * $onpage : "LIMIT " . ((($p >= 0) ? $p : -1 * ($p + 1)) *  $onpage) . ", $onpage");
                                
                        $sth = App::db()->query($q);
            
                        if ($sth->rowCount() == 0)
                        {
                            $p = 0;
                            
                            $q = "SELECT g.`good_id`, g.`good_name`, c.`hex` AS bg, u.`user_login`,
                                        IFNULL(gw.`place`, 0) place,
                                        COUNT(DISTINCT(gl.`id`)) AS likes, 
                                        p.`picture_path`, gp.`pic_w`, gp.`pic_h`
                                    FROM `good_stock_colors` AS c, `good_pictures` gp, `pictures` AS p, `users` AS u, `goods` AS g
                                        LEFT JOIN `good_winners` AS gw ON g.`good_id` = gw.`good_id`
                                        LEFT JOIN `good_likes` AS gl ON gl.`good_id` = g.`good_id` AND gl.`user_id` > '0'
                                    WHERE
                                            (g.`good_status` = 'printed' OR g.`good_status` = 'pretendent')
                                        AND gp.`good_id` = g.`good_id`
                                        AND gp.`pic_name` = 'good_preview'
                                        AND gp.`pic_id`     = p.`picture_id`
                                        AND g.`good_visible`   = 'true'
                                        AND g.`ps_onmain_id`   = c.`id`
                                        AND g.`user_id`        = u.`user_id`
                                    GROUP BY g.`good_id`
                                    ORDER BY $orderBy " . (($p >= 0) ? 'DESC' : 'ASC') . ", g.`user_id`
                                    LIMIT " . ((($p >= 0) ? $p : -1 * ($p + 1)) *  $onpage) . ",  $onpage";
                                    
                            $sth = App::db()->query($q);
                        }
                    }
                    
                    $goods = $sth->fetchAll();
                    
                    foreach ($goods as &$g) {
                        $g['good_name'] = stripslashes($g['good_name']);
                    }
            
                    exit(json_encode($goods));
                    
                break;
                
                case 'getGoodNeighbor':
                    
                    $p = intval($_GET['page']);
                    $onpage = 8;
                     
                    switch($_GET['orderby']) 
                    {
                        case 'new':
                            $orderBy = " g.`good_voting_end` DESC, gw.`place` DESC, likes DESC, g.`good_date`";
                        break;
                    
                        // по оценке
                        case 'grade':
                            $orderBy = " g.`good_likes`";
                        break;
                    
                        // похожие
                        case 'similar':
                            $orderBy = " g.`good_likes`";
                            $p = 0;
                            $onpage = 6;
                        break;
                    
                        default:
                        case 'popular':
                            $orderBy = " g.`good_sold_printshop`";
                        break;
                    }
                    
                    /**
                     * похожие
                     * работы пересекающиеся по тегам
                     */
                    if ($_GET['orderby'] == 'similar' && !empty($_GET['good_id']))
                    {
                        $q = "SELECT g.`good_id`, g.`good_name`, g.`good_discount`, c.`hex` AS bg, p.`picture_path` AS picture, u.`user_login`, gp.`pic_w`, gp.`pic_h`
                                FROM 
                                    `tags_relationships` gtr, `tags_relationships` gtr2, `tags` t, `goods` g, `good_stock_colors` AS c, `good_pictures` gp, `pictures` AS p, `users` AS u
                                WHERE 
                                        gtr.`object_id`   = '" . intval($_GET['good_id']) . "'
                                    AND gtr.`object_type` = '1'
                                    and gtr.`tag_id` = t.`tag_id` 
                                    and t.`tag_ps_goods` > '1'
                                    and gtr.`tag_id` = gtr2.`tag_id`
                                    AND gtr2.`object_id` = g.`good_id`
                                    AND gtr2.`object_type` = '1'
                                    AND (g.`good_status` = 'printed' OR g.`good_status` = 'pretendent')
                                    AND gp.`good_id` = g.`good_id`
                                    AND gp.`pic_name` = 'good_preview'
                                    AND gp.`pic_id`     = p.`picture_id`
                                    AND g.`good_visible`   = 'true'
                                    AND g.`ps_onmain_id`   = c.`id`
                                    AND g.`user_id`        = u.`user_id`
                                group by gtr2.`object_id`
                                ORDER BY $orderBy
                                LIMIT 6";
                        
                        $sth = App::db()->query($q);
                    }
                    else 
                    {
                        $q = "SELECT g.`good_id`, g.`good_name`, g.`good_discount`, c.`hex` AS bg, p.`picture_path` AS picture, u.`user_login`, IFNULL(gw.`place`, 0) place, g.`good_likes` AS likes, gp.`pic_w`, gp.`pic_h`
                                FROM `good_stock_colors` AS c, `good_pictures` gp, `pictures` AS p, `users` AS u, `goods` AS g
                                    LEFT JOIN `good_winners` AS gw ON g.`good_id` = gw.`good_id`
                                WHERE
                                        (g.`good_status` = 'printed' OR g.`good_status` = 'pretendent')
                                    AND g.`good_visible`   = 'true'
                                    AND gp.`good_id` = g.`good_id`
                                    AND gp.`pic_name` = 'good_preview'
                                    AND gp.`pic_id`     = p.`picture_id`
                                    AND g.`ps_onmain_id`   = c.`id`
                                    AND g.`user_id`        = u.`user_id`
                                GROUP BY g.`good_id`
                                ORDER BY $orderBy " . (($p >= 0) ? 'DESC' : 'ASC') . ", g.`user_id`"
                                . 
                                (($_GET['alltopage']) ? "LIMIT 0, " . $p * $onpage : "LIMIT " . ((($p >= 0) ? $p : -1 * ($p + 1)) *  $onpage) . ", $onpage");
                        
                        $sth = App::db()->query($q);
            
                        if ($sth->rowCount() == 0)
                        {
                            $p = 0;
                            
                            $q = "SELECT g.`good_id`, g.`good_name`, g.`good_discount`, c.`hex` AS bg, p.`picture_path` AS picture, u.`user_login`,
                                        IFNULL(gw.`place`, 0) place,
                                        COUNT(DISTINCT(gl.`id`)) AS likes,
                                        gp.`pic_w`, gp.`pic_h`
                                    FROM `good_stock_colors` AS c, `good_pictures` gp, `pictures` AS p, `users` AS u, `goods` AS g
                                        LEFT JOIN `good_winners` AS gw ON g.`good_id` = gw.`good_id`
                                        LEFT JOIN `good_likes` AS gl ON gl.`good_id` = g.`good_id` AND gl.`user_id` > '0'
                                    WHERE
                                            g.`ps_src`         > '0'
                                        AND (g.`good_status` = 'printed' OR g.`good_status` = 'pretendent')
                                        AND gp.`good_id` = g.`good_id`
                                        AND gp.`pic_name` = 'good_preview'
                                        AND gp.`pic_id`     = p.`picture_id`
                                        AND g.`good_visible`   = 'true'
                                        AND g.`ps_onmain_id`   = c.`id`
                                        AND g.`user_id`        = u.`user_id`
                                    GROUP BY g.`good_id`
                                    ORDER BY $orderBy " . (($p >= 0) ? 'DESC' : 'ASC') . ", g.`user_id`
                                    LIMIT " . ((($p >= 0) ? $p : -1 * ($p + 1)) *  $onpage) . ",  $onpage";
                                    
                            $sth = App::db()->query($q);
                        }
                    }
                    
                    $goods = $sth->fetchAll();
                    
                    foreach ($goods as &$g) {
                        $g['good_name'] = stripslashes($g['good_name']);
                    }
            
                    $this->view->setVar('related', $goods);
                    
                    $this->view->generate('catalog/recomended.tpl');
                    exit();
                    
                break;
                
                case 'getRecomended':
                    
                    $good_id = intval($_GET['good_id']);
                    
                    // получаем минимальную цену на новинки
                    $sth = App::db()->query("SELECT gs.`good_stock_price`, gs.`good_stock_discount` FROM `good_stock` AS gs WHERE gs.`style_id` = '210' AND gs.`good_id` = '0' AND gs.`good_stock_visible` = '1' GROUP BY gs.`style_id`");
                    $price = $sth->fetch(); 
                    
                    // блок "РЕКОМЕНДУЕМ"
                    // 2 "новинки" принтшопа
                    if (!$goods = App::memcache()->get('psNew'))
                    {
                        $sth = App::db()->query("SELECT 
                                        u.`user_id`,
                                        u.`user_login`,
                                        g.`good_id`,
                                        g.`good_name`,
                                        g.`good_discount`,
                                        g.`good_instock_status`,
                                        p.`picture_path` AS picture,
                                        c.`hex` AS bg,
                                        gw.`place`
                                    FROM 
                                        `users` AS u,
                                        `goods` AS g LEFT JOIN `good_winners` AS gw ON g.`good_id` = gw.`good_id`,
                                        `pictures` AS p,
                                        `good_stock_colors` AS c,
                                        `good_pictures` gp
                                    WHERE     
                                            g.`ps_src`           > '0'
                                        AND g.`user_id`          = u.`user_id`
                                        AND g.`good_visible`     = 'true'
                                        AND g.`good_id`          != '" . $good_id . "'
                                        AND (g.`good_status` = 'pretendent' OR g.`good_status` = 'printed') 
                                        AND p.`picture_id`          = gp.`pic_id`
                                        AND gp.`pic_name` = 'good_preview'
                                        AND gp.`good_id` = g.`good_id`  
                                        AND c.`id`                  = g.`ps_onmain_id`
                                    GROUP BY g.`good_id`  
                                    ORDER BY g.`good_date` DESC
                                    LIMIT 10");
                                    
                        $goods = $sth->fetchAll();
                        
                        App::memcache()->set('psNew', $goods, false, 10800);
                    }
                    
                    shuffle($goods);
                    
                    foreach (array_slice($goods, 0, 4) as $k => $g)
                    {
                        $d = max($price['good_stock_discount'], $g['good_discount']);
                        $g['good_name']  = stripslashes($g['good_name']);
                        $g['user_login'] = str_replace('.livejournal.com', '', $g['user_login']);
                        $g['price']      = ceil($price['good_stock_price'] * (1 - $d / 100));
            
                        $new[] = $g;
                    }
                    $this->view->setVar('new', $new);
                    
                    // 2 схожие по самому "непопулярному" тегу самые продаваемые
                    $min = 10000;
                    
                    $sth = App::db()->query("SELECT t.* FROM `tags` AS t, `tags_relationships` AS  tr WHERE tr.`object_id` = '$good_id' AND t.`tag_id` = tr.`tag_id` AND tr.`object_type` = '1'");
                    $good['tags'] = $sth->fetchAll();
                    
                    foreach ($good['tags'] AS $k => $tag) {
                        if ($tag['tag_ps_goods'] > 0 && $tag['tag_ps_goods'] < $min) {
                            $topTag = $tag;
                            $min = $tag['tag_ps_goods'];
                        }
                    }
            
                    $sth= App::db()->query("SELECT 
                                    u.`user_id`,
                                    u.`user_login`,
                                    g.`good_id`,
                                    g.`good_name`,
                                    g.`good_instock_status`,
                                    g.`good_discount`,
                                    gs.`good_stock_price`, 
                                    gs.`good_stock_discount`,
                                    p.`picture_path` AS picture,
                                    c.`hex` AS bg
                                FROM 
                                    `users` AS u,
                                    `goods` AS g,
                                    `pictures` AS p,
                                    `good_stock_colors` AS c,
                                    `tags_relationships` AS tr,
                                    `good_pictures` gp
                                WHERE     
                                        g.`ps_src`        > '0'
                                    AND g.`user_id`       = u.`user_id`
                                    AND g.`good_visible`  = 'true'
                                    AND g.`good_id`      != '" . $good_id . "'
                                    AND gp.`good_id`      = g.`good_id`
                                    AND gp.`pic_name`     = 'good_preview' 
                                    AND p.`picture_id`    = gp.`pic_id`  
                                    AND c.`id`            = g.`ps_onmain_id`
                                    AND tr.`object_id`    = g.`good_id`
                                    AND tr.`tag_id`       = " . $topTag['tag_id'] . "
                                    AND tr.`object_type`  = '1'
                                GROUP BY g.`good_id`");
                                
                    $goods = $sth->fetchAll();
            
                    shuffle($goods);
            
                    foreach (array_slice($goods, 0, 2) as $k => $g)
                    {
                        $d = max($price['good_stock_discount'], $g['good_discount']);
                        $g['good_name']  = stripslashes($g['good_name']);
                        $g['user_login'] = str_replace('.livejournal.com', '', $g['user_login']);
                        $g['price']      = ceil($price['good_stock_price'] * (1-($d)/100));
                        
                        $related[] = $g;
                    }
                    
                    if (count($related) < 2)
                    {
                        // 2 самые продаваемые
                        if (!$goods = App::memcache()->get('psBest'))
                        {
                            $sth = App::db()->query("SELECT 
                                            u.`user_id`,
                                            u.`user_login`,
                                            g.`good_id`,
                                            g.`good_name`,
                                            g.`good_instock_status`,
                                            g.`good_discount`,
                                            p.`picture_path` AS picture,
                                            c.`hex` AS bg
                                        FROM 
                                            `users` AS u,
                                            `goods` AS g,
                                            `pictures` AS p,
                                            `good_stock_colors` AS c,
                                            `good_pictures` gp
                                        WHERE     
                                                g.`ps_src`       != '0'
                                            AND g.`user_id`       = u.`user_id`
                                            AND g.`good_visible`  = 'true'
                                            AND g.`good_id`      != '" . $good_id . "'
                                            AND gp.`good_id`      = g.`good_id`
                                            AND gp.`pic_name`     = 'good_preview' 
                                            AND p.`picture_id`    = gp.`pic_id`  
                                            AND c.`id`            = g.`ps_onmain_id`
                                        GROUP BY g.`good_id`  
                                        ORDER BY g.`good_sold_printshop` DESC
                                        LIMIT 2");
                            $goods = $sth->fetchAll();
                            
                            App::memcache()->set('psBest', $goods, false, 10800);
                        }
                        
                        $goods = array_slice($goods, 0, 2 - count($related));
                        
                        foreach ($goods as $g)
                        {
                            $d = max($price['good_stock_discount'], $g['good_discount']);
                            $g['good_name']  = stripslashes($g['good_name']);
                            $g['user_login'] = str_replace('.livejournal.com', '', $g['user_login']);
                            $g['price']      = ceil($price['good_stock_price'] * (1 - $d /100));
                            
                            $related[] = $g;
                        }
                    }
                    $this->view->setVar('related', $related);
                    
                    $this->view->generate('catalog/recomended.tpl');
                    
                break;
                    
                /**
                 * ajax : получить содержимое табов на странице работы
                 */
                case 'getTab':
                
                    switch ($_GET['tab']) 
                    {
                        case 'reviews':
                            
                            $this->page->tpl = 'catalog/good.reviews.tpl';
                            
                            if ($this->user->client->ismobiledevice && !$this->user->client->istablet) {
                                if (!$_GET['page'])
                                   $onpage = 2;
                                else
                                   $onpage = 5;
                            } else {
                               $onpage = 20;
                            }
                            
                            $stars = array(0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0);
                            
                            $foo = App::db()->query("SELECT t.`rating`, COUNT(*) AS c FROM `" . review::$dbtable . "` t WHERE t.`approved` = '1' AND t.`dealers` = '0' GROUP BY t.`rating`")->fetchALL(PDO::FETCH_KEY_PAIR);
        
                            foreach ($stars AS $k => $v) {              
                                if ($foo[$k])
                                    $stars[$k] = $foo[$k];
                                
                                if ($k != 0) {
                                    $total_stars += $foo[$k];
                                }
                                
                                $sum_stars += $k * $foo[$k];
                                $total += $foo[$k];
                            } 
                            
                            $reviews = review::findAll(array('approved' => 1, 'dealers' => 0, 'offset' => ($_GET['page'] ? ($_GET['page'] - 1) * $onpage : 0), 'limit' => $onpage));
                            
                            $this->view->setVar('page', $_GET['page'] ? $_GET['page'] : 1);
                            $this->view->setVar('reviews', $reviews);
                            $this->view->setVar('onpage', $onpage);
                            $this->view->setVar('count', count($reviews));
                            $this->view->setVar('stars', array_reverse($stars, 1));
                            $this->view->setVar('total', $total);
                            $this->view->setVar('total_stars', $total_stars);
                            $this->view->setVar('avg_stars', round($sum_stars / $total_stars, 1));
                            $this->view->setVar('rest', min($onpage, $total - (($_GET['page'] - 1) * $onpage)));
                            
                            $this->view->generate($this->page->tpl);
                            
                            exit();
                            
                            break;
                            
                        case 'sizes':
                            $sth = App::db()->prepare("SELECT `text` FROM `faq` WHERE `id` = :id LIMIT 1");
                            $sth->execute(array('id' => $_GET['faq_id']));
                            $foo = $sth->fetch();
                            $out = stripslashes($foo['text']);
                        break;
                        
                        // Состав и уход
                        case 'composition':
                            
                            if ($_GET['style_id'])
                            {
                                $s = new \application\models\style($_GET['style_id']);
                                
                                if ($s->style_description) {
                                    $out[] = '<div class="style-composition">' . $s->style_description . '</div>';
                                }
                                
                                //if ($s->style_composition) {
                                //    $out[] = '<p>' . $s->style_composition . '</p>';
                                //}
                            }
                        
                            // для внутряка каталога
                            if ($_GET['good_id'] && $_GET['good_id'] > 1)
                            {
                                try
                                {
                                    $g = new \application\models\good($_GET['good_id']);
                                }
                                catch (Exception $e) {
                                }
                                
                                $sth = App::db()->query("SELECT `text` FROM `faq` WHERE (`id` = '100' " . ($g && $g->pics['patterns'] ? " || `id` = '181'" : '') . ") AND `visible` = '1'");
                            
                                foreach ($sth->fetchAll() as $f) {
                                    $out[] = $f['text'];
                                }
                            }
                            // для конструктора 
                            else 
                            {
                                
                                $sth = App::db()->prepare("SELECT `text` FROM `faq` WHERE `id` = :id AND `visible` = '1'");
                            
                                $sth->execute(array('id' => in_array($s->style_category, array(86, 88, 100)) ? 198 : 100));
                            
                                foreach ($sth->fetchAll() as $f) {
                                    $out[] = $f['text'];
                                }
                            }
                            
                            $out = implode('<br />', $out);
                            
                        break;
                            
                        case 'delivery':
                        
                            $this->view->generate('order/basket.calc.tpl');
                        
                            $sth = App::db()->query("SELECT `title`, `text` FROM `faq` WHERE `group` = '9' AND `visible` = '1' ORDER BY `order`");
                            $faq = $sth->fetchAll();
                            
                            foreach ($faq as $k => $v) {
                                $out .= '<dt class="faq-title" onclick="showDeliveryFaq(this)">' . stripslashes($v['title']) . '</dt><dd class="faq-text">' . stripslashes($v['text']) . '</dd>';                   
                            }
                            
                        break;
                        
                        // оплата
                        case 'payment':
                            $out = '<ul id="tab-payment-content">';
                            foreach (\application\models\faq::getGroup(7) AS $r) {
                                $out .= '<li><div>' . $r['title'] . '</div><div>' . $r['text'] . '</div></li>';
                            }
                            $out .= '</ul>';
                            break;
                        
                        case 'moneyback':
                            $sth = App::db()->prepare("SELECT `text` FROM `faq` WHERE `id` = :id LIMIT 1");
                            $sth->execute(array('id' => 203));
                            $foo = $sth->fetch();
                            $out = stripslashes($foo['text']);
                        break;
                        
                        case 'moneyback-sale':
                            $sth = App::db()->prepare("SELECT `text` FROM `faq` WHERE `id` = :id LIMIT 1");
                            $sth->execute(array('id' => 150));
                            $foo = $sth->fetch();
                            $out = stripslashes($foo['text']);
                        break;
                            
                        default:
                        case 'comments':
                            
                            if (!empty($_GET['good_id']))
                            {
                                $id   = intval($_GET['good_id']);
                                $page = intval($_GET['page']);
                                
                                
                                $comments = array();
                            
                                function print_comments($parrent) 
                                {
                                    global $id, $level, $comments;
            
                                    $r = App::db()->query("SELECT 
                                                         c.`comment_id`,
                                                         c.`comment_text` AS `text`,
                                                         c.`comment_date` AS `date`,
                                                         c.`user_id`,
                                                         c.`comment_parent`, 
                                                         u.`user_login`,
                                                         u.`user_designer_level`,
                                                         u.`user_email`,
                                                         u.`user_phone`,
                                                         u.`user_name`,
                                                         u.`user_show_name`
                                                      FROM `comments` AS c, `users` AS u
                                                      WHERE 
                                                             c.`object_id`       = '$id'
                                                         AND c.`object_type`     = 'good' 
                                                         AND c.`user_id`         = u.`user_id` 
                                                         AND c.`comment_visible` = '1'
                                                         AND c.`comment_parent`  = '$parrent'
                                                         AND u.`user_status`     = 'active'
                                                      ORDER BY c.`comment_date` DESC");
                                    
                                    $level++;
                                    
                                    if ($r->rowCount() > 0)
                                    {
                                        foreach ($r->fetchAll() AS $v) 
                                        {
                                            $v['level'] = $level;
                                            $v['class'] = 'level-' . $level;
                                            
                                            $comments[] = $v;
                                            
                                            print_comments($v["comment_id"]);
                                        }
                                    }
                
                                    $level--;
                                }
                                      
                                $sth = App::db()->query("SELECT
                                             c.`comment_id`,
                                             c.`comment_text` AS `text`,
                                             c.`comment_date` AS `date`,
                                             c.`user_id`,
                                             u.`user_login`,
                                             u.`user_designer_level`,
                                             u.`user_email`,
                                             u.`user_phone`,
                                             u.`user_name`,
                                             u.`user_show_name`
                                          FROM `comments` AS c, `users` AS u
                                          WHERE
                                                 c.`object_id`       = '" . $id . "'
                                             AND c.`object_type`     = 'good' 
                                             AND c.`user_id`         = u.`user_id` 
                                             AND c.`comment_visible` = '1'
                                             AND c.`comment_parent`  = '0'
                                             AND u.`user_status`     = 'active'
                                          ORDER BY c.`comment_date` DESC
                                          LIMIT " . ($page * 10) . ", 10");
                                
                                $count = $sth->rowCount();
                                
                                if ($count > 0)
                                {
                                    $good_author = goodId2goodAuthor(intval($_GET['good_id']));
                                    
                                    $level = 0;
                                
                                    foreach ($sth->fetchAll() as $k => $v)
                                    {
                                        $v['level'] = $level;
                                        $v['class'] = 'level-' . $level;
                                        
                                        $comments[] = $v;
                                        
                                        print_comments($v['comment_id']);
                                    }
                                    
                                    $carma  = new \application\models\carma;
                                    
                                    foreach($comments AS &$v)
                                    {
                                        $v['user_avatar']         = userId2userGoodAvatar($v['user_id'], 50);
                                        $v['user_designer_level'] = designerLevelToPicture($v['user_designer_level']);
                                        $v['date']                = datefromdb2textdate($v['date'], 1);
                                
                                        $car = $carma->getCommentCarma('good_comment', $v['comment_id']);
                                
                                        if ($car < carma::$carmaHideComment)
                                            $v['text'] = "<a href='javascript:void(0);' onclick='showHiddenComment(this);' class='hiddenControl'>Показать</a>&nbsp;&nbsp;<span class='hiddenComment'><br />".stripslashes($v['text']).'</span>';
                                        else
                                            $v['text'] = stripslashes($v['text']);
                                
                                        $v['carma'] = $carma->generateCarmaBlock('good_comment', $v['comment_id'], $car, $this->user);
                                
                                        if ($good_author === $v['user_id']) 
                                            $v['author_comment'] = 'author';
                                
                                        if ($this->user->id == $v['user_id'] || $this->user->id == 6199) 
                                            $v['editable'] = TRUE;
                                        
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
                                    }
                                    
                                    $this->view->setVar('comments', $comments);
                                }
            
                                if ($_GET['total'])
                                {
                                    $rest = intval($_GET['total']) - (($page + 1) * 10);
                                    $this->view->setVar('rest', min($rest, $count)); 
                                }
                                
                                // WATCHING
                                $this->view->setVar('WFT', array(
                                    'watchForThisChecked' => $this->user->isWatching($id, 'good'), 
                                    'to' => $id, 
                                    'type' => 'good'
                                ));
                                
                                $this->view->setVar('page', $page);
                                $this->view->setVar('good_id', intval($_GET['good_id']));
                                $this->view->setVar('type', 'good');
                                $this->view->setVar('COMMENT_TYPE_FORM', 'good');
                                
                                $this->view->generate('catalog/comments.tpl');
                            }
                            else
                                $out = 'error';
                            
                        break;
                    }
                    
                    exit($out);
                break; 
                
                /**
                 * Сохранить спозиционированное превью
                 */
                case 'saveImg':
                    break;
                
                /**
                 * КАСТОМАЙЗ
                 * "инициализация" изображения
                 * Поворот изображения на 90 градусов по часовой
                 * для редактора превьюх
                 */
                case 'initImg':
                case 'rotateImg':
            
                    if (!empty($_GET['url']))
                    {
                        $a = intval($_GET['angle']);
            
                        try 
                        {
                            $img = new Imagick(ROOTDIR . trim(is_numeric($_GET['url']) ? pictureId2path($_GET['url']) : $_GET['url']));
            
                            // уменьшаем изображение
                            if ($_GET['width'])
                            {
                                $geo = $img->getImageGeometry();
                                $_GET['width'] = min(300, $_GET['width']);
                                $w = (!empty($_GET['width'])) ? intval($_GET['width']) : $geo['width'];
                                $h = round($geo['height'] * ($w / $geo['width']));
                            }
                            else
                                $w = 300;
                            
            
                            $img->scaleImage($w, $h);
                            
                            if (!empty($a)) 
                            {
                                $img->rotateImage('', 360 - $a);
                            }
            
                            $img->setImageFormat("png");
                            
                            header('Content-type: image/png');
                            exit($img);
                        }
                        catch (Exception $e) { exit($e->getMessage()); }
                        
                    } else {
                        exit('url is empty');
                    }
                
                break;
                
                case 'initImgSticker':
            
                    if (!empty($_GET['url']))
                    {
                        $a   = intval($_GET['angle']);
            
                        try 
                        {
                            $img = new Imagick(ROOTDIR . trim($_GET['url']));
                            $geo = $img->getImageGeometry(); 
                            
                            // уменьшаем изображение
                            $w = (!empty($_GET['width'])) ? intval($_GET['width']) : $geo['width'];
                            $h = round($geo['height'] * ($w / $geo['width']));
            
                            $img->scaleImage($w, $h);
                            
                            $i = new Imagick();
                            $i->newImage($w, $h, new ImagickPixel('white'));
                            $i->compositeImage($img, imagick::COMPOSITE_OVER, 0, 0);
                            
                            if (!empty($a)) 
                            {
                                $i->rotateImage('', 360 - $a);
                            }
                        }
                        catch (exception $e) { printr($e); }
                        
                        $i->setImageFormat("png");
                        header('Content-type: image/png');
                        exit($i);
                    } else {
                        exit('url is empty');
                    }
                
                break;
                
                /**
                 * КАСТОМАЙЗ 
                 * удалить картинку
                 */
                case 'customize_deleteImg':
                    
                    $gid = intval($this->page->reqUrl[2]);
                    $side = addslashes(trim($this->page->reqUrl[3]));
                    
                    if (!empty($gid) && $this->user->id == goodId2goodAuthor($gid))
                    {
                        if ($side == 'back')
                            $srcfn = 'ps_src_back';
                        else 
                            $srcfn = 'ps_src';
                        
                        $sth = App::db()->query("SELECT `pic_id` FROM `good_pictures`    WHERE `good_id` = '$gid' AND `pic_name` = '$srcfn' LIMIT 1");
                        $foo = $sth->fetch();
                        $pic_id = $foo['pic_id'];
                        
                        if (deletePicture($pic_id)) 
                        {
                            App::db()->query("DELETE FROM `good_pictures`
                                        WHERE 
                                                `good_id`     = '$gid' 
                                            AND `pic_name`    = '$srcfn'
                                        LIMIT 1");
                                        
                            exit('ok');
                        } else {
                            exit('file not found');
                        }
                    } else {
                        exit('no');
                    }
                    
                break;
                
                case 'allskins_prv_upload':
                    
                    // если загружает картинку флешка, 
                    // то с ней должна прийти сессия чтобы проверить авторизованность автора
                    if ($_POST['sid'])
                    {
                         $authorized = (get_session_value(addslashes(trim($_GET['sid'])), 'session_logged') == 1) ? TRUE : FALSE;
                    }
                    
                    if (TRUE || $authorized)
                    {
                        $n      = md5(time());
                        $result = catchFileNew('Filedata', SRCUPLOAD . date('/Y/m/d/'), $n, 'jpe,jpeg,jpg,png');
                        
                        if ($result['status'] == 'ok')
                        {
                            $name = explode('.', $_FILES['Filedata']['name']);
                            $ext  = end($name);
                            
                            unset($name[count($name) - 1]);
                            
                            // если исходник жипег, пересохраняем его в пнг
                            if (in_array($ext, array('jpe','jpeg','jpg'))) 
                            {
                                try
                                {
                                    $src = new Imagick(ROOTDIR . $result['path']);
                                    $src->setImageFormat("png");
                                    $src->writeImage(ROOTDIR .  dirname($result['path']) . '/' . $n . '.png');
                                }
                                catch (exception $e) { }
                            }
                            
                            $result['oldname']  = implode('.', $name);
                            $result['filename'] = basename($result['path']);
                            setcookie('customize', '', time() - 3600, '/');
                            
                            $side = addslashes($_POST['side']);
                        }
                    } else {
                        $result['status'] = 'error';
                        $result['message'] = 'Вы не авторизованы';
                    } 
                    
                    
                    exit(json_encode($result));
                break;
            }
            
            // ======================================================================================
            // Инфо о корзине
            // ======================================================================================
            if ($action == "getbasketinfo")
            {
                $s = $this->basket->getBasketSum();
                if ($s == 0) {
                    echo "Ваша корзина пуста ";
                } else {
                    echo "Товаров на $s руб. ";
                }
            }
            elseif ($action == 'addgoodtobasket')
            {
                $basketItem = new basketItem;
                $basketItem->good_id = $_GET['good_id'];
                $basketItem->good_stock_id = $_GET['good_stock_id'];
                $basketItem->quantity = (!isset($_GET['quantity']) || empty($_GET['quantity']) || $_GET['quantity'] == 'undefined' || $_GET['quantity'] == 'NaN') ? 1 : intval($_GET['quantity']);
                $basketItem->price = intval($_GET['price']);
                $basketItem->comment = $_GET['comment'];
                
                $msg = $this->basket->addToBasket($basketItem);
                
                if (!$this->page->isAjax) {
                    if ($msg['error']) {
                        $this->page->setFlashMessage($msg['error']);
                    }
                    
                    if ($_SERVER['HTTP_REFERER'])
                        header('location: ' . $_SERVER['HTTP_REFERER']);
                    else
                        header('location: /basket/');
                }
                
                exit(json_encode($msg));
            }
            elseif ($action == 'addgoodstobasket')
            {
                $gid = intval($_GET['good_id']);
                $k = 0;
                
                foreach($_GET['hash'] AS $gsid => $q) 
                {
                    $basketItem = new basketItem;
                    $basketItem->good_id = $gid;
                    $basketItem->good_stock_id = $gsid;
                    $basketItem->quantity = $q;
                    
                    $msg = explode(':', $this->basket->addToBasket($basketItem));
                    $out[$k]['id']     = $gsid;
                    $out[$k]['status'] = $msg[0];
                    $out[$k]['count']  = $msg[1];
                    
                    $k++;
                }
                
                exit(json_encode($out));
            }
            elseif ($action == 'add2basket')
            {
                if (!isset($_POST['quantity']) || empty($_POST['quantity']) || $_POST['quantity'] == 'NaN') 
                    $q = 1;
                else 
                    $q = intval($_POST['quantity']);
            
                $good_id = intval($_POST['good_id']);
                
                foreach($_POST['good_stocks'] AS $good_stock_id) 
                {
                    $basketItem = new basketItem;
                    $basketItem->good_id = $good_id;
                    $basketItem->good_stock_id = $good_stock_id;
                    $basketItem->quantity = $q;
                    $basketItem->price = $_POST['price'][$good_stock_id];
                    $basketItem->comment = $_POST['comment'][$good_stock_id];
                    
                    $msg = $this->basket->addToBasket($basketItem);
                }
                
                foreach($_POST['gift_stocks'] AS $gift_id) 
                {
                    $msg = $this->basket->addGift2basket($gift_id, $q);
                }
                
                if ($this->page->lang == 'en')
                {
                    $msg['price'] = round($msg['price'] / $this->VARS['usdRate'], 1);
                }
                
                exit(json_encode($msg));
            }
            elseif ($action == "getcatalogpicture")
            {
                if (isset($_GET["pid"]) && $_GET["pid"] != "") {
            
                    $pid = intval($_GET["pid"]);
            
                    $query = "SELECT * FROM `gallery` WHERE `gallery_picture_id` = '$pid' LIMIT 1";
                    $result = App::db()->query($query);
            
                    if ($row = $result->fetch())
                    {
                        $out  = "<a href='#' onClick=\"javascript:window.open('/?action=galleryzoom&goodId=".$row["goodId"]."&pictureId=".$pid."', 'Галерея', 'width=750, height=480')\">";
                        $out .= "<img border=0 src=" . pictureId2path($row['gallery_medium_picture']) . ">";
                        $out .= "</a>";
            
                        echo $out;
            
                    } else echo "no";
            
                } else echo "no";
            
            }
            elseif ($action == "checkavaillogin") 
            {
                $query = "SELECT `user_id` FROM `users` WHERE `user_login` = '" . addslashes($_GET['login']) . "' AND `user_login` != '" . $this->user->user_login . "'";
                $result = App::db()->query($query);
                
                $num = $result->rowCount();
                
                if ($num == 0 && $_GET['login'] != "")
                {
                    if (!preg_match("/^[\+_a-zA-Z0-9-]+$/", $_GET['login']))
                    {
                        echo "Логин содержит недопустимые символы." ;
                        die();
                    }
                    echo "ok";
                } else {
                    echo "Пользователь с таким именем уже зарегистрирован.";
                }
            }
            // проверка на незанятость логина
            // если занят, придумывается другой
            elseif ($action == "checkavailloginfb") 
            {
                $l = addslashes(trim($_GET['login']));
                
                $r = App::db()->query("SELECT `user_id` FROM `users` WHERE `user_login` = '" . $l . "'");
            
                if ($r->rowCount() > 0)
                {
                    $foo = App::db()->query("SELECT MAX(`user_id`) AS m FROM `users`")->fetch();
                    $max = $foo['m'];
                }
                
                exit($l . $max);
            }
            elseif ($action == 'checkemail')
            {
                $return[0]=true;
                if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", urldecode(strtolower($_GET['email'])))) {
                    $return[0]=false;
                    $return[1]="{$_GET['email']} адрес почты введен неверно.";
                }
                //$return = CheckMail(iconv("utf-8", "cp1251", $_GET['email']));
                if ($return[0] == false)
                {
                    echo $return[1];
                } else {
                    $query = "SELECT `user_email`, `user_login`, `user_activation` FROM users WHERE `user_email` = '" . urldecode(addslashes($_GET['email'])) . "' AND `user_status` = 'active' AND `user_email` != '" . $this->user->user_email . "'";
                    $result = App::db()->query($query);
            
                    $founded = $result->rowCount();
            
                    if ($founded > 0)
                    {
                        $row = $result->fetch();
                        if (strstr($row['user_login'], "ljuser"))
                        {
                            echo "Пользователь с таким email уже зарегистрирован.";
                        } else {
                            if ($row['user_activation'] == 'done')
                            {
                                echo 'Этот email уже зарегистрирован. <a class="error" href="/registration/' . (($founded == 1 && !$this->user->authorized) ? 'comeback' : 'merge') . '/?key=' . md5($_GET['email']) . '&next=/editprofile/">Это Вы</a>?';
                            } else {
                                echo 'Этот email уже зарегистрирован. <a class="error" href="/registration/' . (($founded == 1 && !$this->user->authorized) ? 'comeback' : 'merge') . '/?key=' . md5($_GET['email']) . '&next=/editprofile/">Это Вы</a>?';
                            }
                        }
                    } else {
                        echo "ok";
                    }
                }
            }
            elseif ($action == 'checkemailquick')
            {
                $email = addslashes($_GET['email']);
                $r = App::db()->query("SELECT `user_id` FROM `users` WHERE `user_email` = '$email' AND `user_is_fake` = 'false'");
                echo $r->rowCount();
                exit();
            }
            elseif ($action == 'checkphone')
            {
                $phone = str_replace(array(' ', '(', ')', '-', '+'), '', addslashes(trim($_GET['phone'])));
                $sth = App::db()->query("SELECT COUNT(*) AS c FROM `users` WHERE `user_phone` = '$phone' LIMIT 1");
                $foo = $sth->fetch();
                exit((string) $foo['c']);
            }
            elseif ($action == "checkrecoveremail")
            {
                $query = "SELECT user_email, user_login, user_activation FROM users WHERE user_email='" . $_GET['email'] . "'";
                $result = App::db()->query($query);
                
                if ($result->rowCount() > 0)
                {
                    $row = $result->fetch();
                    
                    if (strstr($row['user_login'], "ljuser"))
                    {
                        echo "Пользователь с тяким email зарегистрирован. <br> Если вы ранее делали заказ через shop.livejournal.ru и указывали в контактной информации этот email - пройдите по <a href=\"/registration/recover/\">этой ссылке</a>";
                    } else {
                        if ($row['user_activation'] == 'done')
                        {
                            echo "ok";
                        } else {
                            echo "Пользователь с тяким email уже зарегистрирован но не активен. <br>Если Вам не пришло активационное письмо перейдите по <a href=\"/registration/resend/\">этой ссылке</a> для повторного запроса";
                        }
                    }
                } else {
                    echo 'Данный адрес не обнаружен в нашей базе, попробуйте ввести еще один адрес или перейдите к <a href="/registration/">регистрации</a></span>';
                }
            }
            elseif ($action == "registrationresend")
            {
                $query = "SELECT user_activation, user_id, user_email FROM users WHERE user_email='" . $_GET['email'] . "' AND user_activation!='done'";
                $result = App::db()->query($query);
                $row = $result->fetch();
                
                $code = md5($row['user_id']);
                $userid = $row['user_id'];
                $email = $row['user_email'];
                if ($row['user_activation'] == "waiting") {
            
                    $try = 1;
                    $query = "UPDATE users SET user_activation='fail1' WHERE user_id='$userid'";
                    App::db()->query($query);
            
                } elseif ($row['user_activation'] == "fail1") {
                    $try = 2;
            
                    $query = "UPDATE users SET user_activation='fail2' WHERE user_id='$userid'";
                    App::db()->query($query);
            
                } elseif ($row['user_activation'] == "fail2") {
                    $try = 3;
                    $query = "UPDATE users SET user_activation='failed' WHERE user_id='$userid'";
                    App::db()->query($query);
                    
                } elseif ($row['user_activation'] == "failed") {
                    echo "fail";
                }
                if ($try == 1 || $try == 2 || $try == 3)
                {
                    $link  = "http://maryjane.ru/registration/activate/?userid=$userid&key=$code";
                    $link2 = "http://maryjane.ru/registration/activate/resend/?email=$email";
            
                    $vararray['activateLink'] = $link;
                    $vararray['mail'] = userId2userEmail($row['user_id']);
                    $vararray['userLogin'] = userId2userLogin($row['user_id']);
                    //$vararray['userPassword'] = $row['user_password'];
                    $to = userId2userEmail($row['user_id']);
            
                    $query = "SELECT * FROM mail_templates WHERE mail_template_id='2'";
                    $result = App::db()->query($query);
                    $row = $result->fetch();
            
                    $subject = $row['mail_template_subject'];
                    $text = $row['mail_template_text'];
                    preg_match_all("/%(.*)%/U", $subject, $variables);
                    foreach ($variables[0] as $key => $value)
                    {
                        $subject = str_replace("$value", $vararray[$variables[1][$key]], $subject);
                    }
                    preg_match_all ("/%(.*)%/U", $text, $variables);
                    foreach ($variables[0] as $key => $value)
                    {
                        $text = str_replace("$value", $vararray[$variables[1][$key]], $text);
                    }
            
                    $text = "<html><body>" . nl2br(stripslashes($text)) . "</body></html>";
                    $subject = stripslashes($subject);
                    $headers = "From: info@maryjane.ru <info@maryjane.ru>\r\n";
                    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
                    $headers .= "Content-type: text/html; charset=windows-1251\r\n";
                    mail($to, $subject, $text, $headers);
                    echo $try;
                }
            
            }
            elseif ($action == 'commentCarmaPlus')
            {
                $carma  = new \application\models\carma;
                
                if ($this->user->authorized)
                {
                    $carma->addCommentCarma($_GET['cid'], $_GET['type'], $this->user->id, $_GET['carma']);
                }
            
            } elseif ($action == "postCarmaPlus") {
            
                $carma  = new \application\models\carma;
                
                if ($this->user->authorized)
                {
                    $ca = $_GET["carma"];
                    
                    $carma->addPostCarma($_GET["pid"], $_GET["type"], $this->user->id, $ca);
                }
                
                $car = $carma->generateCarmaBlock($_GET["type"], $_GET["pid"], 0, $this->user);
            
                echo $car;
            }
            elseif ($action == "userCarmaPlus")
            {
                $carma  = new \application\models\carma;
                if ($this->user->authorized)
                {
                    $carma->addUserCarma($_GET['uid'], $this->user->id,$_GET['type'],$_GET["carma"]);
                }
                $car = $carma->generateCarmaBlock('users', $_GET['uid'], 0, $this->user);
                echo $car;
            }
            elseif ($action == "getCommentText")
            {
                if (!empty($_GET['cid']))
                {
                    $cid  = intval($_GET['cid']);
                    $type = trim($_GET['type']);
            
                    $data = array();
                    $data['message']    = '';
                    $data['data']       = array();
                    $data['error']      = '';
            
                    $sth = App::db()->query("SELECT c.`comment_text` AS `text`, c.`user_id`, u.`user_login` FROM `comments` AS c, `users` AS u WHERE c.`comment_id` = '$cid' AND c.`user_id` = u.`user_id` LIMIT 1");
            
                    if ($sth->rowCount() > 0) {
            
                        $comment = $sth->fetch();
                        $data['message'] = 'success';
                        $data['data']['user_id'] = $comment['user_id'];
                        $data['data']['user_login'] = stripslashes($comment['user_login']);
                        $data['data']['text'] = strip_tags(stripslashes($comment['text']), '<a><img><p><br><div><blockquote><cite>');
            
                    } else  $data['error'] = 'Comment not found';
                } else $data['error'] = 'unknow comment';
            
                $data['message'] = $data['message'];
                $data['data']['text'] = $data['data']['text'];
                $data['data']['user_login'] = $data['data']['user_login'];
                $data['error']   = $data['error'];
                $dataJSON = json_encode($data);
                exit($dataJSON);
            
            }
            elseif ($action == "deleteUserImage")
            {
                exit('action deleted');
            }
            elseif ($action == "getImgList")
            {
                // -----------------------------------------------------------------------
                // ПОЛУЧЕНИЕ СПИСКА ПЕРСОНАЛЬНЫХ КАРТИНОК
                // -----------------------------------------------------------------------
                $data = array();
                $data['data']       = array();
                $data['error']      = '';
            
                if (isset($_GET['page']) && !empty($_GET['page'])) $page = intval($_GET['page']); else $page = 1;
                $num = 20;
            
                $r = App::db()->query("SELECT `picture_path` FROM `pictures` AS p, `user_pictures` AS up WHERE p.`picture_id` = up.`picture_id` AND up.`user_id` = '".$this->user->id."' AND up.`type` = 'personal' ORDER BY up.`user_picture_id` DESC LIMIT ".(($page - 1) * $num).", $num");
            
                foreach ($r->fetchAll() AS $row)
                {
                    $p = explode('/', $row[0]);
                    $path = $p[3];
                    $p = explode('.', $path);
                    $data['data'][] = $p[0] . '_tbn_100.' . $p[1];
                }
            
                $r = App::db()->query("SELECT COUNT(*) FROM `pictures` AS p, `user_pictures` AS up WHERE p.`picture_id` = up.`picture_id` AND up.`user_id` = '".$this->user->id."' AND up.`type` = 'personal'");
                $row = $r->fetch();
                $data['pages'] = ceil($row[0] / $num);
            
                //  $data['error'] = $data['error']);
                $dataJSON = json_encode($data);
                echo $dataJSON;
            
            }
            // -----------------------------------------------------------------------
            // ЗАГРУЗКА ЛИЧНЫХ КАРТИНОК
            // -----------------------------------------------------------------------
            elseif ($action == 'uploadUserImage')
            {
                $result = catchFile('upload', IMAGEUPLOAD . DS . 'blogs' . DS . date('Y/m/d/'));
                
                if ($result['status'] == 'ok')
                {
                    $fileSize = @getimagesize(ROOTDIR . $result['path']);
                
                    // Resize img
                    if ($fileSize[0] > 550)
                        createThumbNew(ROOTDIR . $result['path'], '', 550);
                    
                    //$tbn_id = createThumbNew(ROOTDIR . $result['path'], ROOTDIR . $file_path . '/tbn/', 100, 0, 90, 1, 1);
                    
                    App::db()->query("INSERT INTO `user_pictures` (`picture_id`, `tbn_id`, `user_id`, `type`) VALUES ('" . $result['id'] . "', '{$tbn_id}', '" . $this->user->id . "', 'personal')");
                }
                
                echo json_encode($result);
            }
            elseif ($action == 'uploadNewImage')
            {
                // -----------------------------------------------------------------------
                // ЗАГРУЗКА КАРТИНКИ "ПРИСЛАТЬ РАБОТУ"
                // -----------------------------------------------------------------------
                $file = trim($_GET['fileElementName']);
            
                if ($file == 'srcPicture' || $file == 'ps_src') 
                {
                    $uploadPath = '/pictures_src/' . date('Y/m/d');
                }
                else
                {
                    $uploadPath = IMAGEUPLOAD2 . '/uploaded/' . date('Y/m/d');
                }
                
            
                // Определяем доступные расширения
                if ($file == 'srcPicture') {
                    $allowed_ext = 'ai,eps,psd,zip,rar,png';
                } elseif ($file == 'allskins_posters') {
                    $allowed_ext = 'ai';        
                } elseif($file == 'ps_src' || $file == 'allskins_phones' || $file == 'allskins_laptops' || $file == 'allskins_touchpads') {
                    $allowed_ext = 'png';
                } elseif ($file == 'smallPicture') {
                    $allowed_ext = 'png,jpg,jpeg';
                } else {
                    $allowed_ext = 'gif,png';
                }
                
                // Определяем доступные размеры изображений
                if ($file == 'allskins_posters')
                {
                    $minx = 6602;
                    $miny = 10205;
                }
                elseif ($file == 'allskins_phones') {
                    $minx = 1200;
                    $miny = 2150;
                }
                elseif ($file == 'allskins_laptops') {
                    $minx = 5000;
                    $miny = 3500;
                }
                elseif ($file == 'allskins_touchpads') {
                    $minx = 2210;
                    $miny = 2850;
                }
                elseif ($file == 'ps_src') {
                    $minx = 1800;
                    $miny = 2500;
                }
                elseif ($file == 'smallPicture') {
                    $minx = 180;
                    $miny = 184;
                    $maxx = 180;
                    $maxy = 184;
                }
                else
                {
                    $minx = 0;
                    $miny = 0;  
                }
                
                $f = fopen(ROOTDIR . '/debug.txt', 'a');
                fwrite($f, $minx);
                fwrite($f, $minx);
                
                $result = catchFileNew($file, "$uploadPath/", md5(time()), $allowed_ext, $minx, $miny, $maxx, $maxy);
                
                if ($result['status'] == 'ok')
                {
                    // ПРИ НЕОБХОДИМОСТИ ОБРЕЗАЕМ КАРТИНКУ
                    if ($file == 'mediumVotePicture' || $file == 'smallPicture' || $file == 'bigVotePicture')
                    {
                        $file_size = @getimagesize(ROOTDIR . $result['path']);
                
                        if (($file_size[0] != $senddrawingSizes[$file]['width']) || ($file_size[1] != $senddrawingSizes[$file]['height']))
                        {
                            createThumb(ROOTDIR . $result['path'], '', $senddrawingSizes[$file]['width'], $senddrawingSizes[$file]['height'], 100, 0);
                        }
                    }
                }
                
                $result['info'] = $file;
                
                $dataJSON = json_encode($result);
                echo $dataJSON;
            }
            elseif ($action == 'getMyphotoBigPictures')
            {
                if (!empty($_GET['pictureId'])) {
                    $pid = intval($_GET['pictureId']);
                    $r = App::db()->query("SELECT p.`picture_path` FROM `gallery` AS g, `pictures` AS p WHERE p.`picture_id` = g.`gallery_full_picture` AND g.`gallery_picture_id` = '$pid' LIMIT 1");
            
                    if ($row = $r->fetch()) {
            
                        echo "<img src='".$row[0]."' alt='' />";
            
                    } else echo 'Извините, но картинка не найдена';
                }
                else echo 'Извините, но картинка не найдена';
            }
            elseif ($action == 'makewatermark')
            {
                if (isset($_GET['pid']))
                    $big_path = ROOTDIR . pictureId2path(intval($_GET['pid']));
                elseif (isset($_GET['ppath']))
                    $big_path = ROOTDIR . trim($_GET['ppath']);
                else 
                    exit('picture not found');
                
                //exit($big_path);
                
                $src = createImageFrom($big_path);
                
                if (!isset($_GET['mark']))
                    $wm = createImageFrom( ROOTDIR . '/images/watermark-empty.png' );
                elseif ($_GET['mark'] == 'gallery')
                    $wm = createImageFrom( ROOTDIR . '/images/watermark0.png' );
                
                $i = imagecreatetruecolor(imagesx($src), imagesy($src));
                
                imagealphablending($src, true);
                imagesavealpha($src, true);
                
                imagealphablending($wm, true);
                imagesavealpha($wm, true);
                
                imagealphablending($i, true);
                imagesavealpha($i, true);
                
                $rgb = HexToRGB(trim($_GET['bg']));
                $color = imagecolorallocate($i, $rgb['r'], $rgb['g'], $rgb['b']);
                imagefill($i, 0, 0, $color);
            
                imagecopy($i, $src, 0, 0, 0, 0, imagesx($src),imagesy($src));
                imagecopy($i, $wm, 10, imagesy($i) - (imagesy($wm) + 10), 0, 0, imagesx($wm),imagesy($wm)); 
                
                header('Content-Type: image/png');
                imagepng($i);
                
                imagedestroy($i);
                imagedestroy($src);
                imagedestroy($wm);
                exit();
            }
            
            elseif ($action == 'watchForThis')
            {
                if ($this->user->authorized && !empty($_GET['type']))
                {
                    $type = trim($_GET['type']);
                    $to = intval($_GET['to']);
                    
                    if ($this->user->isWatching($to, $type)) { 
                        $this->user->unwatch($to, $type);
                        exit('0');
                    } else {
                        $this->user->watch($to, $type);
                        exit('1');
                    }
                }
            }
            elseif ($action == 'note_add') 
            {
                $text = trim($_POST['note']);
                
                if (!empty($text))
                {
                    $delimiter = 20;
                    $can = false;
                    
                    $foo = App::db()->query("SELECT COUNT(*) AS c FROM `notees` WHERE `user_id` = '".$this->user->id."' AND `visible` = '1'")->fetch();
                    $his_notees = $foo['c'];
            
                    if ($his_notees != 0) {
                        $foo = App::db()->query("SELECT COUNT(*) AS c FROM `notees_grades` WHERE `user_id` = '".$this->user->id."'")->fetch();
                        $his_grades = $foo['c'];
                        
                        if ($his_grades >= $his_notees * $delimiter) 
                            $can = true;
                    } else {
                        $can = true;
                    }
            
                    if ($can)
                    {
                        if (iconv_strlen($text, 'UTF-8') > 105) 
                            $text = iconv_substr($text, 105, 'UTF-8');
                         
                        $text = addslashes($text);
                        
                        $r = App::db()->query("SELECT `id` FROM `notees` WHERE `text` = '{$text}' LIMIT 1");
                        
                        if (0 == $r->rowCount())
                        {
                            $timeinterval = 1;
                            // Проверка на частоту добавления (раз в 5 минут)
                            $r = App::db()->query("SELECT `id` FROM `notees` WHERE " . ($this->user->authorized ? "`user_id` = '" . $this->user->id . "'" : "`user_ip` = '" . $_SERVER['REMOTE_ADDR'] . "'") . " AND `add_date` > NOW() - INTERVAL " . $timeinterval . " MINUTE");
                            
                            if ($r->rowCount() == 0) {
                                $r = App::db()->query("INSERT INTO `notees` 
                                                  SET
                                                    `text` = '{$text}', 
                                                    `user_id` = '" . $this->user->id . "',
                                                    `user_ip` = INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "')");
                                
                                $out = 'Добавлено';
                                $id = App::db()->lastInsertId();
                                
                                App::mail()->send(array(6199), 635, array(
                                    'id' => $id,
                                    'text' => $_POST['note'],
                                    'User' => $this->user,
                                ));
                            } else {
                                $out = "Вы не можете добавлять фразы чаще чем раз в {$timeinterval} (мин.)";
                            }
                        } else {
                            $out = 'Такой слоган у нас уже есть';
                        }
                    } else {
                        $out = 'Чтобы добавить слоган вы должны проголосовать ещё минимум за ' . ($his_notees * $delimiter - $his_grades) . ' (фраз)';
                    }
                } else {
                    $out = 'Текст фразы пуст';
                }
            
                exit($out);
            }
            elseif ($action == 'note_view')
            {
                $q = "SELECT n.`id`, n.`text`, n.`user_id`, u.`user_login` 
                      FROM `users` AS u, `notees` AS n 
                      LEFT OUTER JOIN `notees_grades` AS ng ON (n.`id` = ng.`note_id` AND " . (($this->user->authorized == 1) ? "ng.`user_id` = '".$this->user->id."'" : "ng.`add_IP` = '" . $_SERVER['REMOTE_ADDR']. "'") . ") 
                      WHERE 
                            n.`round` = '0' 
                        AND n.`user_id` = u.`user_id` 
                        AND n.`user_id` <> '".$this->user->id."' 
                        AND n.`visible` = '1' 
                        AND ng.`id` IS null
                      LIMIT 1";
                $r = App::db()->query($q);
            
                if ($r->rowCount() == 1) {
                    $data = $r->fetch();
                    $data['text'] = stripslashes($data['text']);
                } else {
                    $data['text'] = '';
                }
                $data['user_login'] = str_replace('.livejournal.com', '', $data['user_login']);
                $dataJSON = json_encode($data);
                exit($dataJSON);
            }
            elseif ($action == 'note_view_new')
            {
                try
                {
                    $sth = App::db()->prepare("SELECT n.`id`, n.`text`, n.`user_id`, u.`user_login` 
                          FROM 
                            `notees` AS n
                                LEFT JOIN `users` AS u ON n.`user_id` = u.`user_id` 
                                LEFT OUTER JOIN `notees_grades` AS ng ON (n.`id` = ng.`note_id` AND " . (($this->user->authorized == 1) ? "ng.`user_id` = '".$this->user->id."'" : "ng.`add_IP` = '" . $_SERVER['REMOTE_ADDR']. "'") . ") 
                          WHERE 
                            1
                            " . ($this->user->authorized ? "AND n.`user_id` <> '" . $this->user->id . "'" : "AND n.`user_ip` != INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "')") . " 
                            AND n.`round` = '0' 
                            AND n.`visible` = '1' 
                            AND ng.`id` IS null
                          ORDER BY n.`round` DESC
                          LIMIT 1");
                          
                    $sth->execute();
                
                    if ($data = $sth->fetch()) 
                    {
                        $data['text']       = stripslashes($data['text']);
                        $data['user_login'] = str_replace('.livejournal.com', '', $data['user_login']);
                        
                        $this->view->setVar('note', $data);
                    } else {
                        $this->view->setVar('empty', TRUE);
                    }
                    
                    /**
                     * ограничение на добавление надписей
                     * после 3го доабвленного за 20 минут показываем капчу
                     */
                    $sth = App::db()->prepare("SELECT COUNT(*) AS c
                          FROM 
                            `notees` AS n
                          WHERE 
                            " . ($this->user->authorized ? " n.`user_id` = '" . $this->user->id . "'" : " n.`user_ip` = INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "')") . " 
                            AND n.`add_date` >= NOW() - INTERVAL 20 MINUTE");
                         
                    $sth->execute();
                
                    if ($last = $sth->fetch()) 
                    {
                        if ($last['c'] >= 3) {
                            $this->view->setVar('notees_captcha', TRUE);
                        }
                    }
                }
                catch (Exception $e)
                {
                    printr($e);
                }
                
                $this->view->generate('notees.widget.tpl');
                exit();
            }
            elseif ($action == 'vote_for_note')
            {
                if (!empty($_GET['id']) && !empty($_GET['grade']))
                {
                    $id = intval($_GET['id']);
                    $grade = intval($_GET['grade']);
                    $r = App::db()->query("SELECT * FROM `notees` WHERE `id` = '$id' AND `user_id` = '".$this->user->id."'");
                    
                    if ($r->rowCount() == 0)
                    {
                        $note = $r->fetch();
                        
                        // Если пользователь авторизован проверяем по его id
                        // иначе по IP пользователя
                        $r = App::db()->query("SELECT * FROM `notees_grades` WHERE `note_id` = '$id' AND " . (($this->user->authorized == 1) ? "`user_id` = '".$this->user->id."'" : "`add_IP` = '" . $_SERVER['REMOTE_ADDR']. "'"));
                        
                        if ($r->rowCount() == 0) {
                            $r = App::db()->query("INSERT INTO `notees_grades` (`note_id`, `grade`, `user_id`, `add_IP`) VALUES ('$id', '$grade', '".$this->user->id."', '" . $_SERVER['REMOTE_ADDR']. "')");
                            $out = 'added';
                        } else {
                            $grade_row = $r->fetch();
                            $r = App::db()->query("UPDATE `notees_grades` SET `grade` = '$grade' WHERE `id` = '".$grade_row['id']."' LIMIT 1");
                            $out = 'updated';
                        }
            
                        if ($this->user->authorized == 1)
                        {
                            $p_field = 'grades_plus_count';
                            $m_field = 'grades_minus_count';
                        }   
                        else
                        {
                            $p_field = 'grades_plus_na_count';
                            $m_field = 'grades_minus_na_count';
                        }   
                        
                        if ($out == 'added')
                        {
                            if ($grade > 0)
                                $r = App::db()->query("UPDATE `notees` SET `$p_field` = `$p_field` + 1 WHERE `id` = '$id'");
                            else {
                                $r = App::db()->query("UPDATE `notees` SET `$m_field` = `$m_field` + 1 WHERE `id` = '$id'");                
                            }
                        }
                        else
                        {
                            if ($grade != $grade_row['grade'])
                            {
                                if ($grade > 0)
                                    $r = App::db()->query("UPDATE `notees` SET `$p_field` = `$p_field` + 1, `$m_field` = `$m_field` - 1 WHERE `id` = '$id'");
                                else {
                                    $r = App::db()->query("UPDATE `notees` SET `$p_field` = `$p_field` - 1, `$m_field` = `$m_field` + 1  WHERE `id` = '$id'");              
                                }
                            }
                        }
                        
                        // Проверки на Скрытие нотиса
                        if ($note['grades_plus_count'] + $note['grades_minus_count'] >= getVariableValue('noteMinGrades'))
                        {
                            if (($note['grades_plus_count'] / ($note['grades_minus_count'] + $note['grades_plus_count']))  < getVariableValue('noteMinDiff'))
                            {
                                $r = App::db()->query("UPDATE `notees` SET `visible` = '0' WHERE `id` = '$id'");                
                            }
                        }
                        
                        exit($out);
                    } else {
                        exit('Нельзя голосовать за свой слоган');
                    }
                } else {
                    exit('Не указана фраза или оценка');
                }
            }
            elseif ($action == 'get_promocode')
            {
                // получить промо-код для страницы
                try
                {
                    $out = $this->user->getPromoUrl($_POST['url']);
                    $out['link'] = 'http://www.maryjane.ru' . $out['link'];
                }
                catch (Exception $e)
                {
                    $out['error'] = $e->getMessage();
                }
                
                exit(json_encode($out));
            } 
            elseif ($action == 'checkCarmaForComment')
            {
                if ($this->user->user_carma < 0)
                {
                    if (getDateDiff($this->user->user_last_comment) <= 1)
                    {
                        exit("no");
                    }
                }
                exit();
            }
            elseif ($action == 'gettags')
            {
                $q = addslashes(trim($_GET['q']));
                
                if ($this->page->reqUrl[2] == 'blog')
                {
                    $q = "SELECT `name` 
                          FROM `tags` 
                          WHERE `name` LIKE '$q%' " . (($_GET['raiting']) ? " AND `raiting` = '" . intval($_GET['raiting']) . "'" : '') . " AND `tag_posts` >= '2' AND `synonym_id_blog` = '0'
                          LIMIT 30";
                }
                else  
                {
                    $q = "SELECT t.`name` 
                          FROM `tags` AS t
                          WHERE t.`name` LIKE '$q%' " . (($_GET['raiting']) ? " AND t.`raiting` = '" . intval($_GET['raiting']) . "'" : '') . " AND t.`tag_ps_goods` >= '2' AND `synonym_id` = '0'
                          GROUP BY t.`tag_id` 
                          LIMIT 30";
                          
                }
                
                $r = App::db()->query($q);
                
                $out = '';
                
                foreach ($r->fetchAll() AS $tag) {
                    $out .= $tag['name'] . "\n";
                }
                
                exit(trim($out));
            }
            /**
             * Получить информацию о следующей работе на голосовании
             */
            elseif ($action == 'getNextOnVotingInfo')
            {
                
            }
            /**
             * сгенерировать превью дизайна на носителе
             */
            elseif($action == 'generatePreview')
            {
                $good_id = intval($_GET['good_id']);
                $gsId    = intval($_GET['gsId']);
                $side    = (!$_GET['side']) ? 'front' : addslashes(trim($_GET['side']));
                $cat     = addslashes(trim($_GET['category']));
            
                if ($_GET['width']) {
                    $w = $_GET['width'] ? min(500, intval($_GET['width'])) : 500;
                    $h = round($w / 500 * 512);
                } else {
                    $w = $h = '';
                }
                
                if ($good_id > 0 || true) 
                {
                    $good = new \application\models\good($good_id);
            
                    if ($_GET['style_id'])
                    {
                        $style = new \application\models\style($_GET['style_id']);
                    }
                    else
                    {
                        $sth = App::db()->query("SELECT gs.`style_id` FROM `good_stock` AS gs WHERE gs.`good_stock_id` = '{$gsId}' LIMIT 1");
                        
                        $stock = $sth->fetch();
                        
                        $style = new \application\models\style($stock['style_id']);
                    }
                                
                    
                    switch ($style->category)
                    {
                        /**
                         * служебные носители вроде оптовой мелочёвки
                         */
                        case 'neispol_zuemue':
                            
                            header('Content-type: image/jpeg');
                            imagejpeg(createImageFrom(ROOTDIR . '/images/0.gif'), NULL, 98);
                            exit();
                            
                            break;
                            
                        /**
                         * гаджеты
                         */
                        case 'phones':
                        case 'laptops':
                        case 'touchpads':
                        case 'ipodmp3':
                        case 'cases':
                        case 'moto':
                        case 'poster':
                        case 'posters':
                        case 'stickers':
                        case 'boards':
                        case 'patterns':
                        case 'patterns-sweatshirts':
                        case 'patterns-tolstovki':
                        case 'patterns-bag':
                        case 'bag':
                        case 'textile':
                        case 'pillows':
                            
                                // для гаджетов у которых нет основной картинки показываем уменьшенное превью исходника
                                if (empty($style->{'style_' . styleCategory::$BASECATS[$style->category]['def_side'] . '_picture'}) && !empty($style->pics['rez']['path']))
                                {
                                    //$prv = $good->generateSrcPreview('phones', $path);
                                    
                                    if (!good::$srcs[$style->category]) {
                                        throw new Exception('Не существует такого исходника ' . $S->category, 11);
                                    }
                                        
                                    if (!$good->pics[$style->category]) {
                                        throw new Exception('Исходник ' . $style->category . ' у данной работы не загружен', 1);
                                    }
                                    
                                    $i = new Imagick();
                                    $i->newImage(85, 147, new ImagickPixel('white'));
                                    
                                    $src = new Imagick(ROOTDIR . $good->pics[$style->category]['path']);
                                    $src->scaleImage($src->getImageWidth() * ($i->getImageHeight() / $src->getImageHeight()), $i->getImageHeight());
                                    
                                    $i->compositeImage($src, imagick::COMPOSITE_OVER, $i->getImageWidth() / 2 - $src->getImageWidth() / 2, $i->getImageHeight() / 2 - $src->getImageHeight() / 2);
                                    
                                    $i->setImageFormat('jpeg');
                                    
                                    header('Content-type: image/jpeg');
                                    exit($i);
                                    
                                } 
                                else 
                                {
                                    //if ($this->user->id == 27278)
                                    //  $good->generateGadgetPreviewBAK($style->id, $w, $h, ($_GET['side'] ? $_GET['side'] : 'both'), '', false, array('quality' => 100));
                                    //else
                                    
                                    $foo = explode('.', $good->pics['stickers']['path']);
                                    $ext = end($foo);
                                    
                                    if ($style->id == 712 && in_array($ext, array('zip', 'rar'))) {
                                        imagejpeg(createImageFrom(ROOTDIR . '/images/icons/icon_archive.gif'), NULL, 98);
                                        exit();
                                    } else {
                                        $good->generateGadgetPreview($style->id, $w, $h, ($_GET['side'] ? $_GET['side'] : 'both'), '', false, array('quality' => 100));
                                    }
                                }
    
                            exit();
                            
                        break;
                        
                        case 'cup':
                            if ($good->id > 0) {
                                $pic = $good->generateCupPreview($style->id, 500, 512, ($_GET['side'] ? $_GET['side'] : 'front'));
                            }
                            //header('Content-type: image/jpeg');
                            //imagejpeg(ROOTDIR . $pic['path'], '', 98);
                            //exit();
                            break;
                        
                        /**
                         * авто
                         */
                        case 'auto':
                        
                            if (!$_GET['sticker']) {
                                foreach ($good->pics['as_oncar'] as $k => $pic) 
                                {
                                    if ($pic['id'] > 0) {
                                        continue;
                                    }
                                }
                            } else
                                $pic = $good->pics['as_sticker'];
                                
                            header('Content-type: image/jpeg');
                            imagejpeg(createImageFrom(ROOTDIR . $pic['path']), NULL, 98);
                            exit();
                            
                        break;
                        
                        /**
                         * авто
                         */
                        case 'postcards':
                        
                            // стикерсеты
                            if (in_array($style->id, array(537, 584)))
                                $pic = $good->pics['stickerset_preview'];
                            //  открытки
                            else
                                $pic = array_shift($good->pics['postcards']);
                                
                            header('Content-type: image/png');
                            imagepng(createImageFrom(ROOTDIR . $pic['path']));
                            exit();
                            
                        break;
                        
                        /**
                         * Стикерсет
                         */
                        case 'stickerset':
                                
                            if (!$good->pics['stickerset_zoom'])
                                throw new Exception('Zoom image not founded', 1);
                            
                            header('Content-type: image/jpeg');
                            imagepng(createImageFrom(ROOTDIR . $good->pics['stickerset_zoom']['path']));
                            exit();
                                        
                            break;
                            
                        /**
                         * превью на тряпках
                         */
                        default:
                
                            /*
                            $cache = IMAGECACHE . '/fittingroom/' . $stock['cat_slug'] . '/' . $stock['style_id'] . '/' . $good_id . '.jpeg';
                
                            // смотрим в кэше
                            if (file_exists(ROOTDIR . $cache))
                            {
                                $i = createImageFrom(ROOTDIR . $cache);
                                header('Content-type: image/jpeg');
                                imagejpeg($i, NULL, 98);
                                exit();
                            }
                            else
                            {
                            */
                                //if (empty($stock['good_id']) || ($stock['good_id'] > 0 && $stock['good_stock_status'] == 'few'))
                                //{
                                    $good->generatePreview($style->id, $w, $h, $side);
                                //}
                                /*
                                else
                                {
                                    $i = createImageFrom(ROOTDIR . pictureId2path($stock['style_' . $side . '_picture']));
                                    header('Content-type: image/jpeg');
                                    imagejpeg($i, NULL, 98);
                                    exit();
                                }
                                */  
                            //}
                            
                        break;
                    }
                } else {
                    $i = createImageFrom('images/0.gif');
                    header('Content-type: image/gif');
                    imagegif($i, NULL);
                }
            }
            /**
             * увеличенная фотография для каталога
             */
            elseif ($action == 'getpicture') 
            {
                if (!empty($_GET['pid'])) {
                    try
                    {
                        $r = App::db()->prepare("SELECT p.`picture_path` FROM `gallery` g, `pictures` p WHERE g.`gallery_picture_id` = :pid AND p.`picture_id` = g.`gallery_catalog_picture` LIMIT 1");
                        $r->execute(['pid' => intval($_GET['pid'])]);
                        if ($row = $r->fetch()) {
                            echo $row['picture_path'];
                        } else {
                            echo 'no';
                        }
                    }
                    catch (Exception $e)
                    {
                        printr($e);
                        echo 'no';
                    }
                } else {
                    echo 'no';
                } 
                exit();
            }
            elseif ($action == 'one_click_order')
            {
                /**
                 * заказ в один шаг
                 * - добавляем товар в корзину
                 * - берём данные последней корзины и применяем их к новой
                 * - сохраняем корзину
                 * - кидаем на последнйи шаг (/order/confirm/)
                 */
                if (!empty($_GET['good_id']))
                {
                    $good_id       = intval($_GET['good_id']);
                    $good_stock_id = intval($_GET['good_stock_id']);
                    
                    $basketItem = new basketItem;
                    $basketItem->good_id = $good_id;
                    $basketItem->good_stock_id = $good_stock_id;
                    $basketItem->quantity = 1;
                    
                    $msg = $this->basket->addToBasket($basketItem);
                    
                    $result = explode(':', $msg);
                    
                    if ($result[0] == 'ok')
                    {
                        $sth = App::db()->query("SELECT `user_basket_delivery_type`, `user_basket_delivery_cost`, `user_basket_delivery_address`, `user_basket_payment_type` FROM `user_baskets` WHERE `user_id` = '" . $this->user->id . "' AND `user_basket_delivery_address` != '0' ORDER BY `user_basket_date` DESC LIMIT 1");
                        
                        if ($sth->rowCount() == 1)
                        {
                            $last = $sth->fetch();
                    
                            $this->basket->basketChange(array(
                                'user_basket_delivery_type'    => $last['user_basket_delivery_type'],
                                'user_basket_delivery_cost'    => $last['user_basket_delivery_cost'],
                                'user_basket_delivery_address' => $last['user_basket_delivery_address'],
                                'user_basket_payment_type'     => $last['user_basket_payment_type']
                            ));
                            
                            $this->basket->saveBasket();
                            
                            logBasketChange($this->basket->id, 'one_click', TRUE);
                            
                            exit('ok');
                            
                        } else {
                            exit('error: не обнаружено ранее оформленных заказов');
                        }
                    } else {
                        exit('error: ' . $result[1]);
                    }
                }
            }
            elseif ($action == 'one_step_order')
            {
                /**
                 * Заказ в один шаг
                 */
                if ($_POST['submit'])
                {
                    if (!empty($_POST['good_id']) && !empty($_POST['good_stock_id']))
                    {
                        $good_id       = intval($_POST['good_id']);
                        $good_stock_id = intval($_POST['good_stock_id']);
                        
                        $basketItem = new basketItem;
                        $basketItem->good_id = $good_id;
                        $basketItem->good_stock_id = $good_stock_id;
                        $basketItem->quantity = 1;
                        
                        $msg = $this->basket->addToBasket($basketItem);
                        
                        $result = explode(':', $msg);
                        
                        if ($result[0] == 'ok')
                        {
                            if (!$this->user->authorized)
                            {
                                $email = addslashes($_POST['email']);
                                
                                $result = App::db()->query("SELECT MAX(`user_id`) AS m FROM `users` LIMIT 1")->fetch();
                                $nextid = $result['m'] + 1;
                                
                                $login    = 'user' . $nextid;
                                $password = getPassword();
                                
                                $query = "INSERT INTO `users`
                                          SET
                                            `user_login` = '" . $login . "', 
                                            `user_password_md5` = '" . md5(SALT . $password) . "',
                                            `user_name` = '" . addslashes(trim($_POST['name'])) . "',   
                                            `user_postal_address` = '" . addslashes(trim($_POST['address'])) . "',  
                                            `user_email` = '" . $email . "', 
                                            `user_description` = 'Быстрая регистрация. Произведена " . datefromdb2textdate(NOW) . "', 
                                            `user_is_fake` = 'true', 
                                            `user_last_login` = NOW(), 
                                            `user_ip` = INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "')";
                                $result = App::db()->query($query);
                        
                                $id = App::db()->lastInsertId();
                                
                                $SESSIONID = md5(time() . mt_rand());
                                $r = App::db()->query("INSERT INTO `sessions` (`session_id`, `user_id`, `session_time`, `session_logged`) VALUES ('" . $SESSIONID . "', '" . $id . "', '" . time() . "', '1')");
                                setcookie('session', $SESSIONID, (time() + 2592000), "/", AppDomain);
                        
                                // подписываем на уведомления, "если попросил"
                                if ($_POST['news'] == 'true')
                                {
                                    $r = App::db()->query("INSERT INTO `mail_list_subscribers` SET `user_id` = '$id', `mail_list_id` = '6'");
                                }
                                
                                // Отправка уведомления
                                $reparray['code'] = md5($id);
                                $reparray['user_id'] = $id;
                                $reparray['user_login'] = $login;
                                $reparray['user_password'] = $password;
                        
                                App::mail()->send(array($id), 156, $reparray);
                                
                                $this->basket->basketChange(array(
                                    'user_id' => $id
                                ));
                            }
                            
                            $this->basket->MJbasketChange(array(
                                'name'    => addslashes(trim($_POST['name'])),
                                'phone'   => addslashes(trim($_POST['phone'])),
                                'address' => addslashes(trim($_POST['address']))
                            ));
                            
                            $this->basket->saveBasket();
                            
                            logBasketChange($this->basket->id, 'one_step', true);
                            
                            if (!empty($_POST['comment']))
                            logBasketChange($this->basket->id, 'user_comment', addslashes(trim($_POST['comment'])), $this->basket->user_id);
                            
                            header('location: http://www.maryjane.ru/order/confirm/' . $this->basket->id . '/');
                        }
                        else 
                        {
                            
                        }
                        
                        
                        exit();
                    }
                }
                
                $this->view->setVar('content_tpl', 'order/one_step_order.tpl');
                    
                $this->view->setVar('good_id', intval($_GET['good_id']));
                $this->view->setVar('good_stock_id', intval($_GET['good_stock_id']));
                
                $this->view->setVar('basketId', $this->basket->id);
                
                $this->view->generate('index.popup.tpl');
                    
                exit();
            } 
            elseif ($action == 'catalog_one_click_order')
            {
                /**
                 * заказ в один шаг
                 * - добавляем товар в корзину
                 * - берём данные последней корзины и применяем их к новой
                 * - сохраняем корзину
                 * - кидаем на последнйи шаг (/order/confirm/)
                 */
                $good_id = intval($_POST['good_id']);
                
                $s = 'ok';
                
                foreach($_POST['good_stocks'] AS $good_stock_id) 
                {
                    $basketItem = new basketItem;
                    $basketItem->good_id = $good_id;
                    $basketItem->good_stock_id = $good_stock_id;
                    $basketItem->quantity = 1;
                    $basketItem->price = $_POST['price'][$good_stock_id];
                    $basketItem->comment = $_POST['comment'][$good_stock_id];
                        
                    $msg    = $this->basket->addToBasket($basketItem);
                    $result = explode(':', $msg);
                    $s      = $result[0];
                }
                
                if ($s == 'ok')
                {
                    $sth = App::db()->query("SELECT * FROM `user_baskets` WHERE `user_id` = '" . $this->user->id . "' AND `user_basket_delivery_address` != '0' ORDER BY `user_basket_delivered_date` DESC, `user_basket_date` DESC LIMIT 1");
                    
                    if ($sth->rowCount() == 1)
                    {
                        $last = $sth->fetch();
                
                        $this->basket->basketChange(array(
                            'user_basket_delivery_type'    => $last['user_basket_delivery_type'],
                            'user_basket_delivery_cost'    => $last['user_basket_delivery_cost'],
                            'user_basket_delivery_address' => $last['user_basket_delivery_address'],
                            'user_basket_payment_type'     => $last['user_basket_payment_type']
                        ));
                        
                        $this->basket->saveBasket();
                        
                        logBasketChange($this->basket->id, 'one_click', TRUE);
                        
                        exit('ok');
                        
                    } else {
                        exit('error');
                    }
                } else {
                    exit('error');
                }
            }
            elseif ($action == 'catalog_one_step_order')
            {
                /**
                 * Заказ в один шаг
                 */
                if ($_POST['submit'])
                {
                    $good_id = intval($_POST['good_id']);
                    $gs      = explode(',', $_POST['good_stock_id']);
                   
                    foreach ($gs as $good_stock_id) {
                            
                        $basketItem = new basketItem;
                        $basketItem->good_id = $good_id;
                        $basketItem->good_stock_id = $good_stock_id;
                        $basketItem->quantity = 1;
                        $basketItem->price = $_POST['price'][$good_stock_id];
                        $basketItem->comment = $_POST['comment'][$good_stock_id];
                        
                        $msg = $this->basket->addToBasket($basketItem);
                        $result = explode(':', $msg);
                    }
                        
                    if ($result[0] == 'ok')
                    {
                        if (!$this->user->authorized)
                        {
                            $email = addslashes($_POST['email']);
                            $phone = addslashes($_POST['phone']);
                            
                            $password = getPassword();
                            
                            // логин генерим из мыла или телефона
                            if (!empty($email))
                            {
                                $full_login = $email;
                                $login = ((strpos($email, '@') > 3) ? substr($email, 0, strpos($email, '@') - 4) : '') . '****' . substr($email, strpos($email, '@'));
                            }
                            else 
                            {
                                $full_login = $phone;
                                $login = substr($phone, 0, strlen($phone) - 4) . '****';
                            }
                            
                            $query = "INSERT INTO `users`
                                      SET
                                        `user_login` = '" . $login . "', 
                                        `user_password_md5` = '" . md5(SALT . $password) . "',
                                        `user_email` = '" . $email . "', 
                                        `user_description` = 'Быстрая регистрация. Произведена " . datefromdb2textdate(NOW) . "', 
                                        `user_is_fake` = 'true', 
                                        `user_last_login` = NOW(), 
                                        `user_ip` = INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "')";
                            $result = App::db()->query($query);
                    
                            $this->user->id = $id = App::db()->lastInsertId();
                            
                            $SESSIONID = md5(time() . mt_rand());
                            $r = App::db()->query("INSERT INTO `sessions` (`session_id`, `user_id`, `session_time`, `session_logged`) VALUES ('" . $SESSIONID . "', '" . $id . "', '" . time() . "', '1')");
                            setcookie('session', $SESSIONID, (time() + 2592000), "/", AppDomain);
                    
                            // подписываем на уведомления, "если попросил"
                            if ($_POST['news'] == 'true')
                            {
                                $r = App::db()->query("INSERT INTO `mail_list_subscribers` SET `user_id` = '$id', `mail_list_id` = '6'");
                            }
                            
                            // Отправка уведомления
                            if (!empty($email))
                            {
                                $reparray['code'] = md5($id);
                                $reparray['user_id'] = $id;
                                $reparray['user_login'] = $full_login;
                                $reparray['user_password'] = $password;
                        
                                App::mail()->send(array($id), 156, $reparray);
                            }
                            
                            $this->basket->basketChange(array(
                                'user_id' => $id
                            ));
                        }
                        
                        $this->basket->MJbasketChange(array(
                            'name'    => addslashes(trim($_POST['name'])),
                            'phone'   => addslashes(trim($_POST['phone'])),
                            'address' => addslashes(trim($_POST['address']))
                        ));
                        
                        $this->basket->saveBasket();
                        
                        logBasketChange($this->basket->id, 'one_step', TRUE);
                        
                        if (!empty($_POST['user_comment']))
                            logBasketChange($this->basket->id, 'user_comment', addslashes(trim($_POST['user_comment'])), $this->user->id);
                    }
                    
                    header('location: /order/confirm/' . $this->basket->id . '/');
                    exit();
                }
                
                $this->view->setVar('content_tpl', 'catalog/one_step_order.tpl');
                    
                $this->view->setVar('good_id', intval($_GET['good_id']));
                $this->view->setVar('good_stock_id', $_GET['good_stock_id']);
                
                $this->view->setVar('basketId', $this->basket->id);
                
                $this->view->generate('index.popup.tpl');
                    
                exit();
            } 
            elseif ($action == 'getdCalculatorData')
            {
                $sth = App::db()->query("SELECT c.`name`, dpd.`cost`, dpd.`time`
                            FROM `city` c, `delivery_services` dpd
                            WHERE c.`id` = dpd.`city_id` AND `service` = 'dpd'");
                
                exit(json_encode($sth->fetchAll()));
            }
            
            exit();
        }
    }