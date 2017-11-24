<?php

    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
    use \application\models\styleCategory AS styleCategory;
    use \application\models\good AS good;
    use \application\models\certificate AS certificate;
    
    use S3Thumb;
    use \Exception;
    use \PDO;
        
    class Controller_basket extends \smashEngine\core\Controller
    {
        public function action_index()
        {
            $this->page->tpl = 'basket/basket.tpl';
            $this->page->index_tpl = 'index.tpl';
            $this->page->footer_tpl = 'order/footer.tpl';
            $this->page->noindex = TRUE;
            
            $this->page->import(array(
                '/js/mj.polls.js',
                '/js/p/basket.js',
                '/js/glissegallery.js',
                '/css/basket_2013.css',
                '/css/glissegallery.css',
                'version2016.css',
            ));
            
            $this->page->addLangPage('basket');
            
            $this->page->breadcrump[] = array(
                'link' => '#', 
                'caption' => 'Корзина');
            
            $oncar_stiker_sizes = array(
                63  => array('height' => 10, 'koef' => 2),
                64  => array('height' => 15, 'koef' => 3),
                65  => array('height' => 30),
                103 => array('height' => 50),
                104 => array('height' => 70),
                105 => array('height' => 100),
            );
            
            switch ($this->page->reqUrl[1]) 
            {
                /**
                 * Редактировать цену на товар
                 * доступно только партнёрам
                 */
                case 'edit_price':
                    
                    try
                    {
                        if ($this->user->user_partner_status <= 0) {
                            throw new Exception('Вы не можете редактировать позиций', 0);
                        }
                        
                        if (empty($this->page->reqUrl[2]) || !is_numeric($this->page->reqUrl[2])) {
                            throw new Exception('Не указан номер редактируемой позиции', 1);
                        }
                        
                        if (!$this->basket->basketGoods[$this->page->reqUrl[2]]) {
                            throw new Exception('Указанная позиция не найдена в Вашей корзине', 2);
                        }
                        
                        if ($this->basket->basketGoods[$this->page->reqUrl[2]]['tprice'] - $this->basket->basketGoods[$this->page->reqUrl[2]]['user_basket_good_partner_inc'] > $_GET['price']) {
                            throw new Exception('Вы не можете указать цену менее чем ' . ($this->basket->basketGoods[$this->page->reqUrl[2]]['tprice'] - $this->basket->basketGoods[$this->page->reqUrl[2]]['user_basket_good_partner_inc']) . 'р. на эту позицию', 3);
                        }
                        
                        if ($_GET['price'] - $this->basket->basketGoods[$this->page->reqUrl[2]]['tprice'] != $this->basket->basketGoods[$this->page->reqUrl[2]]['user_basket_good_partner_inc']) 
                        {
                            $sth = App::db()->prepare("UPDATE `user_basket_goods` SET `user_basket_good_partner_inc` = :inc WHERE `user_basket_good_id` = :pid AND `user_basket_id` = :bid LIMIT 1");
                            
                            $sth->execute(['inc' => $_GET['price'] - $this->basket->basketGoods[$this->page->reqUrl[2]]['tprice'] + $this->basket->basketGoods[$this->page->reqUrl[2]]['user_basket_good_partner_inc'], 'pid' => $this->page->reqUrl[2], 'bid' => $this->basket->id]);
                        }
                    }
                    catch (Exception $e)
                    {
                        $error = $e->getMessage();
                    }
                    
                    if ($error)
                        exit(json_encode(array('error' => $error)));
                    else
                        exit(json_encode(array('success' => true)));
                    
                    break;
                    
                /**
                 * добавить / удалить коробку
                 */
                case 'box':
                
                    $good_id  = intval($this->page->reqUrl[2]);
                    $stock_id = intval($this->page->reqUrl[3]);
                
                    $this->basket->box($good_id, $stock_id);
                
                    if (!$this->page->isAjax)
                        header('location: ' . $_SERVER['HTTP_REFERER']);
                        
                    exit();
                
                    break;
            
                case 'zoom':
            
                    $good_id  = intval($this->page->reqUrl[2]);
                    $style_id = intval($this->page->reqUrl[3]);
                    $side     = trim($this->page->reqUrl[4]);
            
                    if (!empty($good_id) && !empty($style_id))
                    {
                        $S = new \application\models\style($style_id);
                        $this->view->setVar('img_size', getimagesize(ROOTDIR . $S->{styleCategory::$BASECATS[$S->category]['def_side'] . '_picture'}));
                        $this->view->setVar('good_id', $good_id);
                        $this->view->setVar('style_id', $style_id);
                        $this->view->setVar('side', $side);
                        $this->view->setVar('category', $_GET['category']);
            
                        if ($S->category == 'cup') {
                            
                            $g = new \application\models\good($good_id);
                            
                            $this->view->setVar('update_time', $g->pics[styleCategory::$BASECATS['cup']['src_name']]['update_timestamp']);
                            
                            $this->view->generate('order/basket-zoom.cup.tpl');
                        } else
                            $this->view->generate('order/basket-zoom.tpl');
                        
                        exit();
                    }
                    else
                    {
                        $this->view->setVar('error', array('text' => 'Not enogth data'));
                    }
                    
                    $this->view->generate('index.popup.tpl');
                    exit();
                    
                    break;
            
                /**
                 * ОБРАБОТКА ОШИБОК
                 */
                case 'error':
                    
                    $this->view->setVar('error', intval($this->page->reqUrl[2]));
                
                    break;
            
                /**
                 * ОБРАБОТКА УСПЕШНЫХ СООБЩЕНИЙ
                 */ 
                case 'success':
                    
                    // успешно активирован сертификат
                    if ($this->page->reqUrl[2] == 1)
                    {
                        if ($this->basket->logs['activateCertificate'])
                        {
                            $this->view->setVar('bonusesGiven', $this->basket->logs['activateCertificate'][0]['info']);
                        }
                    }
                    
                    // успешно активирована дисконтная карта
                    if ($this->page->reqUrl[2] == 2)
                    {
                        if ($this->basket->logs['activateDiscontCard'])
                        {
                            $this->view->setVar('discountPercent', $this->basket->logs['activateDiscontCard'][0]['info']);
                        }
                    }
                    
                    $this->view->setVar('success', intval($this->page->reqUrl[2])); 
                    
                break;
            
                /**
                 * ADD GIFT TO BASKET
                 */
                case 'add_gift':
                    
                    if (!empty($this->page->reqUrl[2]))
                    {
                        try
                        {
                            $msg = $this->basket->addGift2basket(intval($this->page->reqUrl[2]), 1, (($_POST['sert_value']) ? $_POST['sert_value'] : ''));
                        }
                        catch (Exception $e)
                        {
                            $msg = array('error' => $e->getMessage());
                        }
                        
                        if (!$msg['error']) {
                            
                            if (!$this->page->isAjax)
                                header('location: ' . $_SERVER['HTTP_REFERER']);
                            
                        } else {
                            
                            if (!$this->page->isAjax)
                                header('location: /' . $this->page->module . '/error/10/');
                        }
            
                        exit(json_encode($r));
                    } 
                    else
                    {
                        if (!$this->page->isAjax) 
                            header('location: /' . $this->page->module . '/error/8/');
                    }
                    
                    exit(json_encode($msg));
                    
                break;
                
                /**
                 * редактировать СТ
                 */
                case 'edit_gift':
                    
                    if (!empty($this->page->reqUrl[2]))
                    {
                        // изменение цены, если это разрешено для данного СТ
                        if ($_GET['price'])
                        {
                            $sth = App::db()->prepare("SELECT `gift_price` FROM `gifts` WHERE `gift_id` = :gift_id LIMIT 1");
                            
                            $sth->execute(array(
                                'gift_id' => $this->page->reqUrl[2],
                            ));
                            
                            $gift = $sth->fetch();
                            
                            if ($gift['gift_price'] == 0)
                            {
                                App::db()->query("UPDATE `user_basket_goods` 
                                                  SET 
                                                    `user_basket_good_price` = '" . abs((int) $_GET['price']) . "',
                                                    `user_basket_good_total_price` = '" . abs((int) $_GET['price']) . "' * `user_basket_good_quantity`
                                                  WHERE 
                                                        `user_basket_id` = '" . $this->basket->id . "'
                                                    AND `gift_id` = '" . $this->page->reqUrl[2] . "'
                                                  LIMIT 1");
                                                  
                            }
                            else
                                $error = 'Этот сопутствующий товар не потдерживает изменение стоимости';
                        }
                    }
                    else
                        $error = 'Не указан сопутствующий товар';
            
                    
                    if ($error)
                        exit(json_encode(array('error' => $error)));
                    else
                        exit(json_encode(array('success' => TRUE)));
            
                    break;
            
                // Удалить СТ из корзины
                case 'delete_gift':
                    
                    $this->basket->removeGift(intval($this->page->reqUrl[2]), 10);
                    header('location: /' . $this->page->module . '/');
                
                    break;
            
                // Удалить товар из корзины
                case 'delete_good':
                
                    $this->basket->removeGood(intval($this->page->reqUrl[2]), intval($this->page->reqUrl[3]), 10000);
                    
                    if (!$_GET['ajax']) {
                        header('location: /' . $this->page->module . '/');
                    }
                    
                    exit(json_encode(array(
                        $this->basket->getBasketSum(), 
                        $this->basket->getBasketSumBonusBack()
                    )));
                
                    break;
                
                // Добавить в коризну сертификат произвольного номинала
                case 'add2basketCert':
                    break;
            
                // АКТИВИРОВАНИЕ ДИСКОНТНОЙ КАРТЫ / СЕРТИФИКАТА
                case 'sercodesubmit':
                    
                    /*
                    if ($_POST['serCode'] == 'Фрисби' || $_POST['serCode'] == 'фрисби') 
                    {
                        if (!$this->basket->logs['promo'])
                        {
                            $this->basket->log('promo', 'Положить фрисби');
                            
                            App::mail()->send(63250, 578, array(
                                'phrase'=> $_POST['serCode'],
                                'User' => $User,
                                'basket' => $basket,
                            ));
                        }
                        
                        header('location: /' . $this->page->module . '/success/3/');
                        exit();
                    }
                    */
                    
                    //printr($_POST, 1);
                    
                    // если сняли галочку "Я хочу оплатить заказ с личного счёта"
                    if (!$_POST['input_basket_sum_without_ls']) {
                        $_SESSION['payed_with_ls'] = 'disabled';
                    } else {
                        $_SESSION['payed_with_ls'] = 'enabled';
                    }
                    
                    if ($_POST['sticerOneList']) {
                        $_SESSION['sticerOneList'] = TRUE;
                    } else {
                        $_SESSION['sticerOneList'] = FALSE;
                    }
                    
                    if (!empty($_POST['serCode']) || !empty($_GET['serCode'])) 
                    {
                        if (!$this->user->session['session_id']) {
                            exit('no access');
                        }
                        
                        if (empty($this->basket->id)) {
                            $this->basket->addBasket();
                        }
                        
                        try
                        {
                            $cert = certificate::find($_POST['serCode'] ? $_POST['serCode'] : $_GET['serCode']);
                            
                            if ($cert->certification_enabled == 0) {
                                throw new Exception('Сертификат отключён', 1);
                                
                            }
                        }
                        catch (Exception $e) 
                        {
                            header('location: /order.v3/error/4/');
                            exit($e->getMessage());
                        }
                        
                        if ($cert->certification_active != 'active')
                        {
                            header('location: /order.v3/error/4/');
                            exit();
                        }
                        elseif ($cert->lifetime != '0000-00-00' && strtotime($cert->lifetime) <= time()) 
                        {
                            header('location: /order.v3/error/5/');
                            exit();
                        }
                        else 
                        {
                            // если пользователь ещё не авторизован, создаём его
                            if (!$this->user->authorized && $cert->certification_type == 'amount') 
                            {
                                if (!empty($_POST['email'])) {
                                    $email = addslashes($_POST['email']);
                                } else {
                                    $error[] = 'Не указан адрес электронной почты';
                                }
                    
                                if (count($error) == 0)
                                {
                                    $this->user->create(array('user_email' => $email));
                                    $this->user->authorize();
                                    
                                    // Отправка уведомления
                                    App::mail()->send($this->user->id, 156, array(
                                        'code' => md5($this->user->id),
                                        'user_id' => $this->user->id,
                                        'user_login' => $this->user->user_login,
                                        'user_password' => $this->user->password
                                    ));
            
                                    /*
                                    if ($prize['section_1'] == 2 || $prize['section_1'] == 1)
                                    {
                                        App::db()->query("UPDATE `roulette` SET `user_id` = '$id' WHERE `certification_id` = '" . $cert['certification_id'] . "' LIMIT 1");
                                    }
                                    */
                                }
                                else
                                {
                                    $this->view->setVar('error', array('text' => implode('<br />', $error)));
                                }
                            }
                            
                            if (count($error) == 0)
                            {
                                $cert->certification_limit--;
                                
                                if ($cert->certification_limit == 0) {
                                    $cert->certification_active = 'none';
                                }
                                
                                $cert->user_id = $this->user->id;
                                $cert->activation_ip = $_SERVER['REMOTE_ADDR'];
                                $cert->user_basket_id = $this->basket->id;
                                $cert->use_date = NOW;
                                
                                // для активации по ссылке запоминаем откуда перешли последний раз
                                if ($_GET['serCode']) {
                                    $cert->referer = $_SERVER['HTTP_REFERER'];
                                }
                                
                                $cert->save();
                                
                                switch ($cert->certification_type)
                                {
                                    // ПОДАРОК С ОСОБЫМИ УСЛОВИЯМИ
                                    case 'gift':
                                        
                                        switch ($cert->certification_password) 
                                        {
                                            case 'gift300':
                                                
                                                $this->user->setMeta('givegifts', 'MJ');
                                                
                                                if (!$this->basket->logs['givegifts_firstorder']) {
                                                    $this->basket->log('givegifts_firstorder', 1);
                                                }
                                                
                                                break;
                                            
                                            default:
                                                break;
                                        }
                                        
                                        break;
                                        
                                    // ДИСКОНТНАЯ КАРТА (+ xx% процентов в корзинную скидку)
                                    case 'percent':
                                        
                                        $this->basket->basketChange(array(
                                            'user_basket_discount' => $cert->certification_value,
                                            'user_basket_discount_description' => 'Скидка по дисконтной карте'));
                
                                        $this->basket->log('activateDiscontCard', $cert->certification_id, $cert->certification_value);
                                        
                                        if ($_POST['serCode'])
                                            header('location: /order.v3/success/2/');
                                        else
                                            header('location: /basket/' . ($cert->id == 5620 ? 'promo10discountSuccess/' : 'success/2/'));
                                        
                                    break;
                
                                    // СЕРТИФИКАТ (+ xx руб. к бонусам пользователя)
                                    case 'amount':
                                        
                                        $this->user->addBonus($cert->certification_value, 'начислено с сертификата №' . $cert->certification_id, $this->basket->id);
                                    
                                        $this->basket->log('activateCertificate', $cert->certification_id, $cert->certification_value);
                                        
                                        header('location: /order.v3/success/1/');
                                        
                                    break;
                                }
                            }
                        }
                    }
                    else 
                    {
                        header('location: /order.v3/');
                    }
                    
                break;
                // END : АКТИВИРОВАНИЕ ДИСКОНТНОЙ КАРТЫ / СЕРТИФИКАТА
            
                        
                // +1 / -1 к количеству товара в корзине
                case 'goodPlus1':
            
                    $gid = intval($_GET['good_id']);
                    $sid = intval($_GET['good_stock_id']);
                    $dir = (!$_GET['dir']) ? $dir = 1 : intval($_GET['dir']);
                    
                    if (!empty($sid))
                    {
                        if ($_GET['quantity']) {
                            $mes = $this->basket->chQuanity($gid, $sid, $_GET['quantity']);
                        } else {
                            if ($dir > 0) {
                                $mes = $this->basket->plusOne($gid, $sid);
                            } else {
                                $mes = $this->basket->removeGood($gid, $sid, 1);
                            }
                        }
                    }
                    else {
                        $mes = 'error:Не достаточно данных';
                    }
                    
                    exit($mes);
                    
                break;
                
                // +1 / -1 к количеству товара в корзине
                case 'giftPlus1':
                    
                    $dir = (!$_GET['dir']) ? $dir = 1 : intval($_GET['dir']);
                    
                    if (!empty($_GET['gift_id']))
                    {
                        if ($dir > 0) {
                            $mes = $this->basket->chGiftQuanity(intval($_GET['gift_id']), $_GET['quantity'] ? $_GET['quantity'] : 1);
                        } else {
                            $mes = $this->basket->removeGift(intval($_GET['gift_id']), 1);
                        }
                    }
                    
                    exit($mes);
                    
                break;
                
            
                default:
                    break;
            }
            
            
            $this->view->setVar('BASKET', TRUE);
            
            $sth = App::db()->query("SELECT `gift_id`, `style_id`, `cat_id`, `good_stock_id` FROM `gifts_styles` WHERE 1");
            
            foreach ($sth->fetchAll() AS $g) {
                if (!empty($g['style_id']))
                    $styles_gifts[$g['style_id']][] = $g['gift_id'];
                if (!empty($g['cat_id']))
                    $cats_gifts[$g['cat_id']][]   = $g['gift_id'];
                if (!empty($g['good_stock_id']))
                    $stock_gifts[$g['good_stock_id']][] = $g['gift_id'];
                
                $gifts_styles[$g['gift_id']][] = $g['style_id'];
                $gifts_cats[$g['gift_id']][] = $g['cat_id'];    
                $gifts_stock[$g['gift_id']][] = $g['good_stock_id'];
            }
            
            // чит, меняем дефолтный носитель для чехлов только для этой страницы, так как чехлы на 4ку кончились
            styleCategory::$BASECATS['cases']['def_style'] = 354;
        
            // вытаскиваем цены и скидки на складе для дополнительных позиций (дизайн в корзине на телефоне и на на тачке)
            $rs = App::db()->query("SELECT `style_id`, `good_stock_id` AS id, `good_stock_price` AS p, `good_stock_discount` AS d 
                    FROM `good_stock` 
                    WHERE 
                            `style_id` IN ('" . styleCategory::$BASECATS['phones']['def_style'] . "', '" . styleCategory::$BASECATS['cases']['def_style'] . "', '" . styleCategory::$BASECATS['laptops']['def_style'] . "', '" . styleCategory::$BASECATS['touchpads']['def_style'] . "', '" . styleCategory::$BASECATS['poster']['def_style'] . "', '" . styleCategory::$BASECATS['cup']['def_style'] . "') 
                        AND `good_stock_visible` = '1' 
                        AND `good_id` = '0'")
                ->fetchAll();
        
            foreach ($rs as $s) 
            {
                foreach (styleCategory::$BASECATS as $cat_name => $cat) {
                    if ($cat['def_style'] && !is_array($cat['def_style']) && $s['style_id'] == $cat['def_style']) {
                        $category = $cat_name;
                        continue;
                    }
                }
                
                if ($category)
                    $stock[$category] = $s;
            }
        
            $sth = App::db()->prepare("SELECT gs.`good_stock_id` AS id, gs.`good_stock_price` AS p, gs.`good_stock_discount` AS d, s.`size_id`, s.`size_meta` 
                FROM `good_stock` gs, `sizes` s 
                WHERE gs.`style_id` = :sid AND gs.`size_id` = :size AND gs.`good_stock_visible` = '1' AND gs.`good_id` = '0' AND gs.`size_id` = s.`size_id` 
                LIMIT 1");
            
            $sth->execute(array(
                'sid' => styleCategory::$BASECATS['auto']['def_style'],
                'size' => min(array_keys($oncar_stiker_sizes)),
            ));
            
            $stock['auto'] = $sth->fetch();
            $stock['auto']['size_meta'] = json_decode($stock['auto']['size_meta'], 1);
            
            $addsth = App::db()->prepare("SELECT gp.`pic_name`, p.`picture_path`
                                FROM `good_pictures` gp, `pictures` p
                                WHERE 
                                        gp.`good_id` = :good_id
                                    AND gp.`pic_name` IN (:pic_name_1, :pic_name_2, :pic_name_3, :pic_name_4, :pic_name_5, 'as_sticker')
                                    AND gp.`pic_id` = p.`picture_id`");
        
            $k                     = 0;
            $totalPrice            = 0;
            $totalPriceWithoutDisc = 0;
            $totalDiscount         = 0;
            
            $stickers = $stickers_sum = 0;
            
            $goods = array();
            $additional = array();
            
            // для проверки размеров исходника самоделок, чтобы вывести предупреждение
            $custSth = App::db()->prepare("SELECT `pic_w`, `pic_h` FROM `good_pictures` WHERE `good_id` = :good_id AND `pic_name` = :pic LIMIT 1");
            
            $Thumb = new S3Thumb(array('ic1.maryjane.ru', 'ic2.maryjane.ru', 'ic3.maryjane.ru', 'ic4.maryjane.ru'), S3AccessKey, S3SecretKey, S3CryptKey);
            
            foreach ($this->basket->basketGoods as $g)
            {
                $k++;
        
                $g['faq_id'] = styleId2styleFaq($g['style_id']);
        
                $totalPriceWithoutDisc += $g['price'] * $g['quantity'];
                $totalPrice += $price = $g['tprice'];
                $totalDiscount         += ($g['price'] * $g['quantity']) - $price;
        
                $g['discount']     = intval($g['discount']);
                $g['number']       = $k;
                $g['deliver_srok'] = $this->basket->getStyleDeliveryDate($g['category'], 'user', false, $g['gsGoodId'] > 0 ? true : false);
                
                if (!isset($avalible[$g['good_stock_id']])) {
                    $avalible[$g['good_stock_id']] = $g['avalible'];
                }
                
                $avalible[$g['good_stock_id']] -= $g['quantity'];   
                
                $g['avalible']     = $avalible[$g['good_stock_id']];
                
                foreach ($styles_gifts[$g['style_id']] as $a) 
                    $additional[$a] = $a;
                
                foreach ($cats_gifts[$g['cat_parent']] as $a) 
                    $additional[$a] = $a;
                
                foreach ($stock_gifts[$g['good_stock_id']] as $a) 
                    $additional[$a] = $a;
        
                if ($g['cat_parent'] > 1) 
                    $gadgets++;
                else
                {
                    $wear++;
                    
                    if ($g['good_status'] != 'customize')
                    {
                        // для тряпок дополнительно вытаскиваем наклейку на айфон и на тачку если есть
                        $addsth->execute(array(
                            'good_id' => $g['good_id'],
                            'pic_name_1' => 'catalog_preview_' . styleCategory::$BASECATS['cases']['def_style'],
                            'pic_name_2' => 'catalog_preview_' . styleCategory::$BASECATS['laptops']['def_style'],
                            'pic_name_3' => 'catalog_preview_' . styleCategory::$BASECATS['touchpads']['def_style'],
                            'pic_name_4' => 'catalog_preview_' . styleCategory::$BASECATS['poster']['def_style'],
                            'pic_name_5' => 'catalog_preview_' . styleCategory::$BASECATS['cup']['def_style'],
                        ));
                        
                        $ppp = $pics = array();
                                        
                        foreach ($addsth->fetchAll() as $add)
                        {
                            foreach (styleCategory::$BASECATS as $cat_name => $cat) {
                                if ($cat['def_style'] && !is_array($cat['def_style']) && strpos($add['pic_name'], (string) $cat['def_style']) !== false) {
                                    $add['pic_name'] = $cat_name;
                                }
                            } 
                            
                            $pics[$add['pic_name']] = $add;
                        }
                        
                        if ($pics['as_sticker']) {
                            $ppp['as_sticker'] = $pics['as_sticker'];
                            unset($pics['as_sticker']);
                        }
                        
                        if ($pics['cases'] || $pics['cup']) {
                            if ($pics['cases'])
                                $ppp['cases'] = $pics['cases'];
                            if ($pics['cup'])
                                $ppp['cup'] = $pics['cup'];
                        } else {
                            shuffle($pics);
                            $ppp[] = array_pop($pics);
                        }
                        
                        foreach ($ppp AS $add)
                        {
                            if (empty($add))
                                continue;
                            
                            switch ($add['pic_name']) 
                            {
                                case 'as_sticker':
                                    
                                    $sth = App::db()->prepare("SELECT p.`picture_path` FROM `good_pictures` gp, `pictures` p WHERE gp.`good_id` = :good_id AND gp.`pic_name` = 'sticker' AND gp.`pic_id` = p.`picture_id` LIMIT 1");
                                    
                                    $sth->execute(array(
                                        'good_id' => $g['good_id'],
                                    ));
                                    
                                    $pic = $sth->fetch();
                                    
                                    $hd_res_info = getimagesize(ROOTDIR . $pic['picture_path']);
                                    
                                    if (empty($hd_res_info)) {
                                        continue 2;
                                    }
                                    
                                    $h = $stock['auto']['size_meta']['h'] * good::$pxPerCm;
                                    $w = round(round(($h / $hd_res_info[1]) * $hd_res_info[0]) / good::$pxPerCm);
                                    $h = round($h / good::$pxPerCm);
                                    
                                    $add['gift_name']     = 'Наклейка на авто ' . $w . ' x ' . $h;
                                    $add['price']         = ceil(($w * $h * cmPriceWithLamination) / 10) * 10 * (($oncar_stiker_sizes[$stock['auto']['size_id']]['koef']) ? $oncar_stiker_sizes[$stock['auto']['size_id']]['koef'] : 1);
                                    $add['good_stock_id'] = $stock['auto']['id'];
                                    $add['comment']       = urlencode($w . ' x ' . $h);
                                    
                                    $add['gift_type'] = 'forauto';
                                    
                                    break;
                                
                                default:
                                    
                                    $add['gift_name']     = ($add['pic_name'] == 'poster' || $add['pic_name'] == 'cases' || $add['pic_name'] == 'cup' ? '' : 'Наклейка на ') . styleId2styleName(styleCategory::$BASECATS[$add['pic_name']]['def_style']);
                                    //$add['price']         = round($stock[$add['pic_name']]['p'] * (1 - max($g['good_discount'], $stock[$add['pic_name']]['d']) / 100)) * 1;
                                    $add['price']         = $stock[$add['pic_name']]['p'];
                                    $add['good_stock_id'] = $stock[$add['pic_name']]['id'];
                                    
                                    $add['gift_type'] = 'for' . $add['pic_name'];
                                    
                                    break;
                                    
                                    break;
                            }
        
                            // размер превьюшки
                            $w = $add['pic_name'] == 'laptops' ? 152 : 85;
            
                            $add['picture_path'] = $Thumb->url($add['picture_path'], $w);
                            
                            $add['good_id']   = $g['good_id'];
                            
                            $good_additional[$g['good_id'] . $add['pic_name']] = $add;
                        }
                    }
                }
            
                // считаем количество наклеек в корзине чтобы предложить расположить их на одном листе
                if ($g['category'] == 'auto' || $g['category'] == 'stickers')
                {
                    $stickers += $g['quantity'];
                    $stickers_sum += $g['tprice'];
                }
        
                if ($g['good_status'] == 'customize')
                {
                    $custSth->execute(array(
                        'good_id' => $g['good_id'],
                        'pic' => styleCategory::$BASECATS[$g['category']]['src_name'], 
                    ));
                    
                    if ($size = $custSth->fetch())
                    {
                        if (good::$srcs[styleCategory::$BASECATS[$g['category']]['src_name']]['min_size']['w'] > $size['pic_w'] || good::$srcs[styleCategory::$BASECATS[$g['category']]['src_name']]['min_size']['h'] > $size['pic_h']) 
                        {
                            $g['tooSmall'] = TRUE;
                        }
                    }
                }
                
                $goods[] = $g;
            }
        
            $additional = array_values($additional);
        
            //printr($add);
            //printr($good_additional);
            //printr($good_additional);
            //printr($reserved);
            //printr($goods);
            //printr("$wear $gadgets");
            
            
            $this->view->setVar('goods', $goods);
        
        
            $giftIds = array();
            
            foreach ($this->basket->basketGifts as $v)
            {
                $k++;
                $v['number'] = $k;
                if ($v['gift_price'] == 0) $v['disabled'] = 'disabled';
                
                $totalPrice += $v['priceTotal'];
                $totalPriceWithoutDisc += $v['priceTotal'];
                $giftIds[] = $v['gift_id'];
                
                $gifts[] = $v;
            }
        
            $this->view->setVar('gifts', $gifts);
        
            $bonusPresent = $this->VARS['bonusPresent'];
            
            $this->view->setVar('totalDiscount', $totalDiscount);
            $this->view->setVar('totalPriceWithoutDisc', $totalPriceWithoutDisc);
            $this->view->setVar('stickers', $stickers);
            $this->view->setVar('sticerOneListDiscount', round($stickers_sum * 0.1));
        
        
            // срок предполагаемой доставки
            if ($gadgets > 0) {
                $this->view->setVar('deliver_srok', $this->basket->getDeliveryTimeGadgets('user'));
            } else {
                $this->view->setVar('deliver_srok', $this->basket->getDeliveryTime('user'));
            }
            
            // ПРЕДУПРЕЖДЕНИЕ О НАЛИЧИИ СЕРТИФИКАТА
            /*
            $sth = App::db()->query("SELECT `certification_value` FROM `certifications` WHERE `certification_type` = 'amount' AND `user_id` = '" . $this->user->id . "' AND `certification_active` = 'none'");
        
            // Если у товарища есть не использваонные сертификаты
            if ($cert = $sth->fetch())
            {
                if ($this->user->user_bonus > 0 && ($totalPrice > ($this->user->user_bonus + $cert['certification_value']))) 
                {
                    $this->view->setVar('CERTIFICAT_NOT_ENOUGH', array('VAL' => $this->user->user_bonus));
                    //$this->view->setVar_var('BUTTONENABLE', 'disabled');
                }
            }
            // END : ПРЕДУПРЕЖДЕНИЕ О НАЛИЧИИ СЕРТИФИКАТА
            
            if ($this->user->user_bonus > 0 && $totalPrice > 0 && $this->user->user_bonus > $totalPrice)
                $this->view->setVar('BONUSES_ENOUGH', array('VAL' => $this->user->user_bonus));
            */
        
            // СОПУТСТВУЮЩИЕ ТОВАРЫ И ПОДАРКИ
            $gifts = array();
            
            if (count($giftIds) > 0) 
                $aq[] = "g.`gift_id` NOT IN (" . implode(',',$giftIds) . ")";
            
            $sth = App::db()->query("SELECT
                            g.`gift_id`,
                            g.`gift_name`,
                            g.`gift_price`,
                            g.`gift_discount`,
                            ROUND(g.`gift_price` * (1 - g.`gift_discount` / 100)) AS price,
                            g.`gift_link`,
                            g.`gift_type`,
                            p.`picture_path`,
                            p2.`picture_path` AS zoom
                        FROM `gifts` AS g
                            LEFT JOIN `pictures` p2 ON p2.`picture_id` = g.`picture_big_id`, `pictures` AS p
                        WHERE
                                g.`picture_id` = p.`picture_id`
                            AND g.`gift_visible` = '1'
                            AND g.`gift_quantity` > '0'
                            " . (($aq) ? ' AND ' . implode(' AND ', $aq) : ''). "
                        ORDER BY g.`gift_order` DESC");
            
            foreach ($sth->fetchAll() as $k => $gift) 
            {   
                if ($gift['gift_type'] == 'packing')
                {
                    $packing[] = $gift;
                    continue;
                }
                
                // адмишнл не привязанный совсем ни к чему
                if (count($gifts_styles[$gift['gift_id']]) == 0 || count($gifts_cats[$gift['gift_id']]) == 0 || count($gifts_stock[$gift['gift_id']]) == 0) {
                    if ($gift['gift_type'] == 'allskins')
                        $gifts_more[] = $gift;
                    else
                        $gifts[] = $gift;
                }
                
                if (in_array($gift['gift_id'], $additional))
                {
                    $gifts[array_search($gift['gift_id'], $additional)] = $gift;
                }
            }
            
            ksort($gifts);
        
            //printr($good_additional);
            //printr($gifts);
            
            $this->view->setVar('GIFTS', array_merge((array) $good_additional, $gifts));
            //$this->view->setVar('GIFTS_more', $gifts_more);
            
            /** 
             * СТИКЕРСЕТЫ
             */
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
                                            
                    $sth->execute();
                    $stickerset = $sth->fetchAll(PDO::FETCH_ASSOC);
                    
                    App::memcache()->set('stickersets', $stickerset, false, 10 * 7200);
                }
                catch(PDOException $e) 
                {  
                    printr($e->getMessage());  
                }
            }
            
            $this->view->setVar('stickerset', $stickersets);
            
            $sth = App::db()->prepare("SELECT `good_stock_price`, `good_stock_discount`, ROUND(`good_stock_price` * (1 - (`good_stock_discount`) / 100)) AS price FROM `good_stock` WHERE (`style_id` = :style_id OR `style_id` = :style_id2) AND `good_id` = '0' ORDER BY price ASC");
            
            $sth->execute(array(
                'style_id' => 537,
                'style_id2' => 584,
            ));
            
            $sstock = $sth->fetchAll(PDO::FETCH_ASSOC);
        
            $this->view->setVar('stickerset_price', $sstock[0]['price']);
            /** 
             * end СТИКЕРСЕТЫ
             */ 
            
            $this->view->setVar('packing', $packing);
            
            // сколько бонусов вернётся за этот заказ
            $bbpercent = $this->user->buyerLevel2discount();
            $this->view->setVar('bbpercent', $bbpercent);
        
            // процент перевода кэша на личном счете юзера в бонусы
            if ($this->user->user_partner_status <= 0) {
               $bppercent = $this->VARS['bonusesPaybackPercent'];
            } else {
               $bppercent = 0;
            }
            
            $this->view->setVar('bppercent', $bppercent);
            
            $this->view->setVar('ls_can_pay', min($this->basket->getBasketSum(), $this->user->balance['total'] + round($this->user->balance['total'] / 100 * $bppercent)));
            
            if ($m = $this->page->getFlashMessage())
            {
                $this->view->setVar('error', array('text' => $m));
            }   
                
            $bdiscount = userId2userBirthdayDiscount($this->user->id);
            if ($bdiscount > $discount)
                $discount = $bdiscount;
            
            if ($this->basket->user_basket_discount > $discount) 
                $discount = $this->basket->user_basket_discount;
            
            $this->view->setVar('discount', $discount);
            
            $this->view->setVar('ref', $_SERVER['HTTP_REFERER']);
            
            /**
             * предупреждение об активированном сертификате
             */
            if ($this->basket->logs['activateDiscontCard']) {
                $cert = new certificate($this->basket->logs['activateDiscontCard'][0]['result']);
                $this->view->setVar('activated_certificate', $cert);
            }
            
            $this->view->generate($this->page->index_tpl);
        }
    }