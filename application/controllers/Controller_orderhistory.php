<?php

    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
    use \application\models\basket AS basket;
    use \application\models\review AS review;
    use \application\models\styleCategory AS styleCategory;
    use \application\models\style AS style;
    use \application\models\payment AS payment;
    
    use \Exception;
    use \DateTime;
    use \PDO;
    use \S3Thumb;
    
    class Controller_orderhistory extends \smashEngine\core\Controller
    {
        public function action_index()
        {
            if (!$this->user->authorized) {
                 header('location: /login/'); 
                 exit(); 
            }
            
            $this->page->tpl = 'order/history/content.tpl';
            $this->page->footer_tpl = 'order/footer.tpl';
            $this->page->noindex = TRUE;
            $this->page->utitle = $this->user->user_login . ' - История заказов';
            
            $userId = $headerUserId = $this->user->id;
            
            
            $this->view->setVar('userId', $userId);
            
        
            // подписка на смс-информирование
            $this->view->setVar('sms_info_checked', ($this->user->meta->order_sms_info === '0') ? FALSE : TRUE);
        
            /**
             * Смена типа оплаты
             */
            if ($_POST['changept'] && !empty($_POST['paymentype']) && !empty($_POST['id']))
            {
                $id = intval($_POST['id']);
                $pt = addslashes($_POST['paymentype']); 
                
                try
                {
                    $b = new basket($id);
                }
                catch (Exception $e)
                {
                    exit($e->getMessage());
                }
                
                if ($b->user_id != $this->user->id)
                {
                    exit('Вы не можете изменить тип оплаты этого заказа');
                }
                
                if ($pt == 'creditcard')
                {
                    $ccmax = getVariableValue('creditcard_max');
                
                    if ($this->page->lang == 'en') {
                        $ccmax = round($ccmax / $this->VARS['usdRate'], 1); 
                    }
                
                    if ($b->getBasketSum() >= $ccmax)
                        die('Максимальная сумма заказа для оплаты кредитной картой ' . $ccmax . ($this->page->lang == 'en' ? ' руб.' : '$'));
                }
                
                // если доставка в пункт самовывоза и выбрана оплата наличными меняем её на наложенный платёж
                if ($_POST['paymentype'] == 'cash' && (in_array($b->user_basket_delivery_type, array('IMlog_self', 'dpd_self')) || (in_array($b->user_basket_delivery_type, array('IMlog', 'dpd')) && $b->address['city_id'] > 1))) {
                    $pt = 'cashon';
                }
                
                $b->basketChange(array('user_basket_payment_type' => $pt));
                
                // стоимость коробок
                $boxCost = getVariableValue('boxCost');
                // скидка на этот способ оплаты
                $d = getVariableValue($pt . 'Discount');
                
                $sth = App::db()->query("SELECT `user_basket_good_id`, `user_basket_good_price`, `user_basket_good_discount`, `user_basket_good_discount_type`, `user_basket_good_quantity`, `user_basket_good_box` 
                            FROM `user_basket_goods` 
                            WHERE `user_basket_id` = '{$id}'");
                
                foreach ($sth->fetchAll() AS $row)
                {
                    $tp = (round($row['user_basket_good_price'] * (1 - $d / 100)) * $row['user_basket_good_quantity']) + (($row['user_basket_good_box'] > 0) ? $boxCost * $row['user_basket_good_quantity'] : 0);
                    
                    // сикдка на способ оплаты больше чем та что была на эту позицию
                    if ($d > $row['user_basket_good_discount']) 
                    {
                        $q = "UPDATE `user_basket_goods` 
                              SET 
                                `user_basket_good_discount`      = '{$d}', 
                                `user_basket_good_discount_type` = '5',
                                `user_basket_good_total_price`   = '{$tp}'
                              WHERE 
                                `user_basket_good_id` = '" . $row['user_basket_good_id'] . "' 
                              LIMIT 1";
                    }
                    // если она меньше чем та что была
                    else
                    {
                        // то уменьшаем её только в случае если скидка была за способ оплаты
                        if ($row['user_basket_good_discount_type'] == 5) 
                        {
                            $q = "UPDATE `user_basket_goods` 
                                  SET 
                                    `user_basket_good_discount`      = '{$d}', 
                                    `user_basket_good_discount_type` = '" . (($d == 0) ? 0 : 5) . "',
                                    `user_basket_good_total_price`   = '{$tp}'
                                  WHERE 
                                    `user_basket_good_id` = '" . $row['user_basket_good_id'] . "' 
                                  LIMIT 1";
                        }
                    }
                    
                    if ($q) {
                        App::db()->query($q);
                    }
                }
                
                if ($_POST['next'])
                    header("location: " . $_POST['next']);
                else
                    header("location: /" . $this->page->module . "/$id/#o$id");
                exit();
            }
            
            /**
             * добавить телефон для связи
             */
            if ($_POST['addphone'])
            {
                if (!empty($_POST['basket_id'])) {
                    $sth = App::db()->query("SELECT MAX(ub.`user_basket_id`) AS m FROM `user_baskets` ub WHERE ub.`user_basket_id` = '" . $_POST['basket_id'] . "' AND ub.`user_id` IN ('" . $this->user->id . "', '27278', '6199')");
                } else {
                    $sth = App::db()->query("SELECT MAX(ub.`user_basket_id`) AS m  FROM `user_baskets` ub WHERE ub.`user_id` = '" . $this->user->id . "' AND ub.`user_basket_domain` = 'MJbasket' AND ub.`user_basket_status` <> 'delivered' AND ub.`user_basket_status` <> 'active' AND ub.`user_basket_status` <> 'returned'");
                }
                
                $foo = $sth->fetch();
                
                $id = (int) $foo['m'];
                
                if (!empty($id))
                {
                    $sth = App::db()->query("SELECT `id` FROM `user_basket_log` WHERE `basket_id` = '{$id}' AND `action` = 'user_comment' AND `date` >= NOW() - INTERVAL 1 MINUTE");
                    
                    if ($sth->rowCount() == 0) {
                        logBasketChange($id, 'user_comment', 'Дополнительный телефон для связи - ' . addslashes(trim($_POST['addphone'])));
                        exit('ok');
                    } else {
                        exit('Не чаще 1 телефона в минуту');
                    }
                }
            }
        
            switch ($this->page->reqUrl[1]) 
            {
                case 'sendreview':
                    
                    if ($_POST['user_basket_id'])
                    {
                        $b = new basket($_POST['user_basket_id']);
                        
                        $r = new review;
                        
                        $r->text  = 'Качество продукции: ' . $_POST['a'] . "\n";
                        $r->text .= 'Качество печати: ' . $_POST['b'] . "\n";
                        $r->text .= 'Оцените доставку: ' . $_POST['c'] . "\n";
                        $r->text .= 'Размер: ' . $_POST['d'] . "\n";
                        $r->text .= 'Стоит покупать: ' . $_POST['e'] . "\n\n";
                        $r->text .= $_POST['text'];
                        
                        $r->user_basket_id = $b->id;
                        
                        $r->email = $b->user->user_email;
                        $r->name = $b->user->user_name ? $b->user->user_name : $b->address['name'];
                        $r->phone = $b->address['phone'];
                        $r->city = $this->user->city;
                        $r->user_id = $this->user->id;
                        
                        $i = 1;
                        
                        foreach ($b->basketGoods as $g) 
                        {
                            $r->{'pic' . $i} = $g['imagePath'];
                            
                            if ($i >= 3)
                                break;
                            
                            $i++;
                        }
                        
                        $r->save();
                        
                        header('location:' . str_replace('sendreview_success/', '', $_SERVER['HTTP_REFERER']) . 'sendreview_success/');
                        exit();
                    }
                    else
                    {
                        exit('Не указан номер заказа');
                    }
                    
                    break;
                    
                /**
                 * Смена даты и времени доставки активного заказа
                 */
                case 'saveDeliveryTime':
                
                    $sth = App::db()->query(sprintf("SELECT `user_basket_id` FROM `user_baskets` WHERE `user_id` = '%d' AND `user_basket_status` IN ('ordered', 'waiting', 'accepted', 'prepared') ORDER BY `user_basket_date` DESC LIMIT 1", $this->user->id));
                    
                    $foo = $sth->fetch();
                    
                    $bid = $foo['user_basket_id']; 
                    
                    if (!empty($bid))
                    {
                        try
                        {
                            $b = new basket($bid);
                        
                            // изменено время встречи с курьером
                            if (!empty($_POST['data']['kurer_time'])) {
                                
                                $last = $b->logs['admin_deliverytime'][0];
                                
                                // если последнее время встречи устанавливал админ и пользователь его изменяет
                                if ($last && $last['user_id'] != $this->user->id && $_POST['data']['kurer_time'] != $last['result'])
                                {
                                    $b->log('user_changes', 'admin_deliverytime', $_POST['data']['kurer_time']);
                                }
                                
                                if ($_POST['data']['kurer_time'] != $last['result'])
                                    $b->log('admin_deliverytime', $_POST['data']['kurer_time'], $this->user->id);
                            }
                            
                            // изменена дата доставки
                            if (!empty($_POST['data']['kurer_date']) || !empty($_POST['data']['deliveryboy_date'])) {
                                        
                                if (isset($_POST['data']['deliveryboy_date']))
                                    $_POST['data']['kurer_date'] = $_POST['data']['deliveryboy_date'];
                                        
                                $last = $b->logs['deliverydate'][0];
                                
                                // если последнее время встречи устанавливал админ и пользователь его изменяет
                                if ($last && $last['user_id'] != $this->user->id && $_POST['data']['kurer_date'] != $last['result'])
                                {
                                    $b->log('user_changes', 'deliverydate', $_POST['data']['kurer_date']);
                                }
                                
                                $dd = explode('/', $_POST['data']['kurer_date']);
                                $date = date('Y-m-d', mktime(0, 0, 0, $dd[1], $dd[0], $dd[2]));
                
                                if ($date != $last['result'])
                                    $b->log('deliverydate', $date, $this->user->id);
                            }
                        }
                        catch (Exception $e) 
                        {
                            exit($e->getMessage());
                        }
                    }
                    
                    exit('ok');
                
                break;
            
                /**
                 * ВОЗВРАТ
                 */
                case 'return':
                
                    // отменяем возврат
                    if ($this->page->reqUrl[2] == 'cancel')
                    {
                        // отменяем все возвратные бонусы
                        $sth = App::db()->query("SELECT `user_bonus_id` AS id FROM `user_bonuses` WHERE `user_id` = '" . $this->user->id . "' AND `user_bonus_status` = '-1'");
                        
                        foreach ($sth->fetchAll() as $p) {
                            $delete[] = $p['id'];
                        }
                        
                        // отменяем все возвраты в кэш
                        $sth = App::db()->query("SELECT `id` FROM `" . payment::$dbtable . "` WHERE `user_id` = '" . $this->user->id . "' AND `status` = '0' AND `type` = 'exchange'");
                        
                        foreach ($sth->fetchAll() as $p) {
                            $delete[] = $p['id'];
                        }

                        App::db()->query("DELETE FROM `user_bonuses` WHERE `user_id` = '" . $this->user->id . "' AND `user_bonus_status` = '-1' LIMIT 100");                        
                        App::db()->query("DELETE FROM `" . payment::$dbtable . "` WHERE `user_id` = '" . $this->user->id . "' AND `status` = '0' AND `direction` = '0' AND `type` = 'exchange' LIMIT 100");
                                                
                        App::db()->query("UPDATE `user_basket_goods` SET `user_basket_good_exchange_payment` = '0' WHERE `user_basket_good_exchange_payment` IN ('" . implode("', '", $delete) . "')");
                        
                        $this->basket->dellog('set_mark');
                        
                        $this->page->refresh();
                    }
                    
                    // подтвердить возврат
                    if ($_POST['submit'])
                    {
                        if (count($_POST['pos']) > 0)
                        {
                            $back = 0;
                            
                            $sth1 = App::db()->prepare("UPDATE `user_basket_goods` SET `user_basket_good_exchange_payment` = :p WHERE `user_basket_good_id` = :id LIMIT 1");
                            
                            $sth = App::db()->prepare("SELECT ubg.`user_basket_good_id`, ubg.`user_basket_id`, ubg.`user_basket_good_price` AS price, ubg.`user_basket_good_discount` AS discount, (ubg.`user_basket_good_quantity` - ubg.`user_basket_good_quantity_return`) AS quantity
                                        FROM `user_basket_goods` ubg, `user_baskets` ub
                                        WHERE 
                                                ub.`user_id` = '" . $this->user->id . "'
                                            AND ub.`user_basket_status` = 'delivered'
                                            AND ub.`user_basket_delivered_date` >= NOW() - INTERVAL 12 MONTH
                                            AND ub.`user_basket_id` = ubg.`user_basket_id`
                                            AND ubg.`user_basket_good_quantity` - ubg.`user_basket_good_quantity_return` > 0
                                            AND ubg.`user_basket_good_id` IN (" . implode(',', array_fill (0, count($_POST['pos']), '?')) . ")");
                            
                            $sth->execute(array_keys($_POST['pos']));
                            
                            foreach($sth->fetchAll() AS $p)
                            {
                                $back += $bback = round($p['price'] * (1 - $p['discount'] / 100)) * $p['quantity'];
                                
                                if ($_POST['cashback']) {
                                    $pay = $this->user->pay($bback, 'Обмен-возврат позиции ' . $p['user_basket_good_id'], 'exchange', 0, 1, '', '', $this->user);
                                } else {
                                    $pay = $this->user->addBonus($bback, 'Обменные бонусы', $p['user_basket_good_id'], -1);
                                }
                                
                                // запоминаем id выплаты по конкретной позиции
                                $sth1->execute(['p' => $pay, 'id' => $p['user_basket_good_id']]);
                                
                                $exchorders[] = $p['user_basket_id'];
                            }
                            
                            
                            if (empty($this->basket->id)) {
                                $this->basket->addBasket();
                            }
                
                            // помечаем заказ как обменный
                            switch ($_POST['cause']) {
                                // замена размера
                                case 1:
                                    $this->basket->log('set_mark', 'exchange', json_encode(array_unique($exchorders)));
                                    break;
                                // другая причина
                                case 3:
                                    $this->basket->log('set_mark', 'exchange', json_encode(array_unique($exchorders)));
                                    break;
                                // замена по браку
                                case 2:
                                    $this->basket->log('set_mark', 'exchange_brak', json_encode(array_unique($exchorders)));
                                    break;
                                case 4:
                                    $this->basket->log('set_mark', 'exchange_malo', json_encode(array_unique($exchorders)));
                                    break;
                                case 5:
                                    $this->basket->log('set_mark', 'exchange_veliko', json_encode(array_unique($exchorders)));
                                    break;
                            }
                            
                            if ($_POST['exchange_reasone']) {
                                $this->basket->log('exchange_reasone', $_POST['exchange_reasone'] . ($_POST['cashback'] ? '. Хочу вернуть деньги' : ''));
                            }

                            if (!$this->page->isAjax) {
                                header('location: ' . (($_POST['next']) ? str_replace('http://' . $_SERVER['HTTP_HOST'], '', urldecode($_POST['next'])) : '/'));
                            }
        
                            exit(json_encode(['status' => 'ok', 'sum' => $back]));
                            
                        } else {
                            exit(json_encode(['status' => 'error', 'message' => 'Не указаны позиции для возврата']));
                        }
                    }
                    else 
                    {
                        if ($this->user->authorized && $this->page->isAjax)
                        {
                            $this->user->user_bonus += $this->user->exchenged_bonuses = $exchengedBonuses = $this->user->getBonuses(-1);
                            $this->view->setVar('exchengedBonuses', $exchengedBonuses);
                        }
                        
                        // пользователь уже в режиме оформления обмена
                        if ($exchengedBonuses > 0)
                        {
                            $this->view->generate('order/history/return.inprogress.tpl');
                        }
                        else
                        {
                            $Thumb = new S3Thumb(array('ic1.maryjane.ru', 'ic2.maryjane.ru', 'ic3.maryjane.ru', 'ic4.maryjane.ru'), S3AccessKey, S3SecretKey, S3CryptKey);
                            
                            $sth = App::db()->prepare("SELECT ubg.`user_basket_good_id`, ubg.`user_basket_id`, g.`good_id`, g.`good_name`, s.`style_name`, ubg.`user_basket_good_price` AS price, ubg.`user_basket_good_discount` AS discount, (ubg.`user_basket_good_quantity` - ubg.`user_basket_good_quantity_return`) AS quantity, u.`user_login`, s.`style_id`, gs.`size_rus`,sc.`cat_parent`
                                    FROM 
                                        `user_basket_goods` ubg, 
                                        `user_baskets` ub, 
                                        `goods` g, 
                                        `good_stock` gs, 
                                        `styles` s, 
                                        `styles_category` AS sc,
                                        `users` u
                                    WHERE 
                                            ub.`user_id` = :uid
                                        AND ub.`user_basket_status` = 'delivered'
                                        AND ub.`user_basket_delivered_date` >= NOW() - INTERVAL 12 MONTH
                                        AND ub.`user_basket_id` = ubg.`user_basket_id`
                                        AND ubg.`user_basket_good_exchange_payment` = '0'
                                        AND g.`good_id` > 0
                                        AND ubg.`good_id` = g.`good_id`
                                        AND ubg.`good_stock_id` = gs.`good_stock_id`
                                        AND gs.`style_id` = s.`style_id`
                                        AND ubg.`user_basket_good_quantity` - ubg.`user_basket_good_quantity_return` > 0
                                        AND ubg.`user_basket_good_total_price` > '0'
                                        AND g.`user_id` = u.`user_id`
                                        AND sc.`id` = s.`style_category`"
                                        . ($this->page->reqUrl[2] ? " AND ub.`user_basket_id` = '" . intval($this->page->reqUrl[2]) . "'" : '')
                                        );
                            
                            $sth->execute(array(
                                'uid' => $this->user->id,
                            ));
                            
                            if ($rs = $sth->fetchAll())
                            {
                                $ssth = App::db()->prepare("SELECT p.`picture_path` FROM `good_pictures` gp, `pictures` p WHERE gp.`good_id` = :id AND gp.`pic_name` = :name AND gp.`pic_id` = p.`picture_id` LIMIT 1");
                                
                                foreach($rs AS $k => &$p)
                                {
                                    if (in_array($p['style_id'], ['407','429','390']) && $p['user_basket_good_total_price'] == '660') {
                                        unset($rs[$k]);
                                        continue;
                                    }
                                    
                                    $p['good_name'] = stripslashes($p['good_name']);
                                    
                                    if ($p['style_id'] == 288)
                                        $ssth->execute(['id' => $p['good_id'], 'name' => 'as_sticker']);
                                    elseif ($p['style_id'] == 537)
                                        $ssth->execute(['id' => $p['good_id'], 'name' => 'stickerset_preview']);
                                    else
                                        $ssth->execute(['id' => $p['good_id'], 'name' => 'catalog_preview_' . $p['style_id']]);
                
                                    if ($foo = $ssth->fetch()) {
                                        $p['pic'] = $Thumb->url($foo['picture_path'], styleCategory::$BASECATSid[$p['cat_parent']] == 'laptops' ? 152 : 85);
                                    }
                                    
                                    $p['tprice']    = round($p['price'] * (1 - $p['discount'] / 100)) * $p['quantity'];
                                }
                                
                                $this->view->setVar('positions', $rs);
                            }
                                    
                            $this->view->generate('order/history/return.v2.tpl');  
                        }
                        
                        exit();
                    }
                break;
            
                /**
                 * Анулирование заказа
                 */
                case 'cancel':
                    
                        if ($_POST['submit'])
                        {
                            $r = App::db()->query("SELECT `user_basket_id`, `user_basket_payment_partical`, `user_basket_payment_confirm` 
                                              FROM `user_baskets` WHERE `user_id` = '" . $this->user->id . "' AND `user_basket_status` IN ('ordered','waiting','accepted') 
                                              LIMIT 1");
                            
                            if ($row = $r->fetch())
                            {
                                if (!empty($_POST['reason']))
                                {
                                    if ($_POST['reason'] == 'my')
                                        $reason = addslashes($_POST['my_reason']);
                                    else
                                        $reason = addslashes($_POST['reason']);
                                }
                
                                $B = new basket($row['user_basket_id']);                
                                
                                try
                                {
                                    $B->cancel(array('reason' => $reason));
                                    
                                    if ($B->user_basket_payment_confirm == 'true')
                                    {
                                        $B->log('payed_canceled', 1);
                                    }
                                }
                                catch (Exception $e) { printr($e->getMessage()); }
                            } 
                            else 
                                exit('error');
                                
                            exit();
                        }
                        else 
                        {
                            foreach (basket::$cancelReason AS $k => $r)
                                if ($r['domain'] != 'ASbasket' && $r['domain'] != 'all')
                                    unset($cancelReason[$k]);
                                 
                            $this->view->setVar('cancelReason', $cancelReason);
                            
                            $this->page->tpl = 'order/history/cancel.tpl';
                            $this->page->index_tpl = 'index.popup.tpl';
                        }
                    break;
                    
                default:
                    
                    if (!empty($this->page->reqUrl[1]) && $this->page->reqUrl[1] != 'user')
                    {
                        $order = intval($this->page->reqUrl[1]);
                    } 
                    elseif ($this->page->reqUrl[1] == 'user' && in_array($this->user->meta->mjteam, array('super-admin', 'grand_manager', 'manager')))
                    {
                        $this->user->id = intval($this->page->reqUrl[2]);
                    }
                    
                    $onpage = 10;
                    $p      = ($this->page->reqUrl[1] == 'page' && !empty($this->page->reqUrl[2]) && !$order) ? intval($this->page->reqUrl[2]) : 1;
                    
                    /**
                     * Вытаскиваем сколько у пользователя заказов по статусам
                     */
                    $sth = App::db()->prepare("SELECT ub.`user_basket_status`, COUNT(ub.`user_basket_id`) AS c
                        FROM 
                            `user_baskets` ub
                        WHERE 
                                ub.`user_id` = :user
                            AND ub.`user_basket_status` NOT IN ('active', 'returned')
                        GROUP BY ub.`user_basket_status`");
                    
                    $sth->execute(array('user' => $this->user->id));
                    
                    $counts = $sth->fetchAll(PDO::FETCH_KEY_PAIR);
                    
                    // если у пользователя нет ни одно заказа кроме аннлированных, то перебрасываем его в таб с аннулированными
                    if ($counts['canceled'] > 0 && array_sum(array_diff_key($counts, array('canceled' => ''))) == 0 && $this->page->reqUrl[1] != 'canceled')
                    {
                        header('location: /' . $this->page->module . '/canceled/');
                        exit();
                    }
                    
                    /**
                     * Получаем список заказов
                     */
                    $sth = App::db()->query("SELECT 
                                SQL_CALC_FOUND_ROWS
                                    ub.*, uba.*, u.`user_email`
                                FROM `users` AS u, `user_baskets` ub
                                    LEFT JOIN `user_basket_address` uba ON uba.`id` = ub.`user_basket_delivery_address` 
                                WHERE
                                        ub.`user_id` = '" . $this->user->id . "'
                                    " . (($order) ? "AND ub.`user_basket_id` = '{$order}'" : '') . "
                                    AND ub.`user_basket_status` <> 'active' 
                                    AND ub.`user_basket_status` <> 'returned' 
                                    AND u.`user_id` = ub.`user_id` 
                                    "
                                    .
                                    (!$order ? ($this->page->reqUrl[1] == 'canceled' ? "AND ub.`user_basket_status` = 'canceled'" : "AND ub.`user_basket_status` != 'canceled'") : '')
                                    .
                                    "
                                ORDER BY ub.`user_basket_date` DESC
                                LIMIT " . (($p - 1) * $onpage) . ", $onpage");
                    
                    if ($sth->rowCount() > 0)
                    {
                        $recordset = $sth->fetchAll();
                        
                        $sth = App::db()->query("SELECT FOUND_ROWS() AS t");
                        $foo = $sth->fetch();
                        
                        $total     = $foo['t']; 
                        $orders    = array();
                        
                        if ($total > $onpage)
                        {
                            $this->view->setVar('PAGES', pagination(ceil($total / $onpage), $p, '/' . $this->page->module . '/page', 10));
                        }
                        
                        foreach ($recordset as $k => $o) 
                        {
                            $o['phone'] = str_replace(array('-', '(', ')', ' '), '', $o['phone']);
                            
                            $orders[$o['user_basket_id']]['ID']         = $o['user_basket_id']; 
                            $orders[$o['user_basket_id']]['user_basket_status'] = $o['user_basket_status'];
                            $orders[$o['user_basket_id']]['STATUS']     = basket::$orderStatus[$o['user_basket_status']]; 
                            $orders[$o['user_basket_id']]['STATUSCODE'] = $o['user_basket_status'];
                            $orders[$o['user_basket_id']]['phone'][1]   = substr($o['phone'], 0, strlen($o['phone']) - 4);
                            $orders[$o['user_basket_id']]['phone'][2]   = substr($o['phone'], -4);
                            
                            $orders[$o['user_basket_id']]['date']       = explode(' ', $o['user_basket_date']);
                            $orders[$o['user_basket_id']]['date'][0]    = datefromdb2textdate($orders[$o['user_basket_id']]['date'][0]);
                            $orders[$o['user_basket_id']]['date'][1]    = explode(':', $orders[$o['user_basket_id']]['date'][1]);
                            
                            if (!$order && $o['user_basket_status'] != 'canceled')
                            {
                                $order = $o['user_basket_id'];
                            }
                            
                            if ($order && $order == $o['user_basket_id'])
                            {
                                $totalPrice             = 0;
                                $totalPriceWithoutDisc  = 0;
                                $totalDiscount          = 0;
                    
                                try
                                {
                                    $b = new basket($o['user_basket_id']);
                                }
                                catch (Exception $e) 
                                {
                                    printr($e->getMessage());
                                }
                                
                                if ($this->page->lang == 'en')
                                {
                                    $o['user_basket_delivery_cost'] = round($o['user_basket_delivery_cost'] / $this->VARS['usdRate'], 1);
                                    $o['user_basket_payment_partical'] = round($o['user_basket_payment_partical'] / $this->VARS['usdRate'], 1);
                                    $b->alreadyPayed = round($b->alreadyPayed / $this->VARS['usdRate'], 1);
                                }
                                
                                $this->view->setVar('O', $b);
                                $this->view->setVar('order', $o);
                                $this->view->setVar('basketNum', $order);
                                
                                /**
                                 * смотрим не начислялиСь ли обменные-бонусы за этот закза
                                 */
                                $sth = App::db()->prepare("SELECT b.`user_bonus_info` 
                                                      FROM `user_bonuses` b, `user_basket_goods` ubg
                                                      WHERE 
                                                            b.`user_id` = :user 
                                                        AND b.`user_bonus_info` = ubg.`user_basket_good_id`
                                                        AND ubg.`user_basket_id` = :bid");
                                                
                                $sth->execute(array(
                                    'user' => $b->user_id,
                                    'bid' => $b->id,
                                ));
                                
                                foreach ($sth->fetchAll() as $p) {
                                    $exchanged[$p['user_bonus_info']] = $p['user_bonus_info'];
                                }
                                
                                foreach ($b->basketGoods as $ubgid => $g)
                                {
                                    // превью для гаджетов
                                    if ($g['cat_parent'] > 1)
                                    {
                                        $g['category'] = styleCategory::$BASECATSid[$g['cat_parent']];
                                    }
                                    
                                    if ($exchanged[$ubgid])
                                        $g['exchanged'] = true;
                                    
                                    if (!empty($g['user_basket_good_partner_payment_id'])) {
                                        $partner_payments[] = $g['user_basket_good_partner_payment_id'];
                                    }
                                    
                                    // для ноутбуков возможно поменять размер
                                    if (!empty($g['size_rus']))
                                    {
                                        $sth = App::db()->query("SELECT gs.`good_stock_id`, s.`size_id`, s.`size_name` FROM `good_stock` gs, `sizes` s WHERE gs.`style_id` = '" . $g['style_id'] . "' AND gs.`good_id` = '0' AND gs.`good_stock_quantity` +- gs.`good_stock_inprogress_quantity` > '0' AND gs.`good_stock_visible` > '0' AND gs.`size_id` = s.`size_id`");
                                        $g['avalible_sizes'] = $sth->fetchAll();
                                        foreach ($g['avalible_sizes'] as $ask => $asv) {
                                            $g['avalible_sizes'][$ask]['size_name'] = stripslashes($asv['size_name']);
                                        }
                                    }
                                    
                                    $g['style_print_block'] = unserialize($g['style_print_block']);
                
                                    $totalPrice += $g['price'] = $g['tprice'];
                                    
                                    $goods[] = $g;
                                    $sc++;
                                }
                                
                                foreach ($b->basketGifts as $g)
                                {
                                    $totalPrice += $g['tprice'];
                                    $gifts[] = $g;
                                    $gsc++;
                                }
                                
                                $this->view->setVar('goods', $goods);
                                $this->view->setVar('gifts', $gifts);
                                
                        
                                $this->view->setVar('totalDiscount', $totalDiscount);
                                $this->view->setVar('totalPrice', $totalPrice + $o['user_basket_delivery_cost'] - $o['user_basket_payment_partical'] - $b->alreadyPayed);
                                $this->view->setVar('ttotalPrice', $totalPrice - (($b->alreadyPayed != $b->basketSum) ? $b->alreadyPayed : 0));
                                $this->view->setVar('totalPriceWithoutDisc', $totalPriceWithoutDisc);
                                $this->view->setVar('basket_sum',  $totalPrice + ($o['user_basket_delivery_cost'] - $o['user_basket_payment_partical'] - $b->alreadyPayed));
                                
                                if ($o['user_basket_status'] == 'delivered') {
                                    $sth = App::db()->query("SELECT `user_bonus_count` FROM `user_bonuses` WHERE `user_bonus_id` = '" . $o['user_basket_bonusback_id'] . "' LIMIT 1");
                                    $foo = $sth->fetch();
                                    $this->view->setVar('basketBack',  (int) $foo['user_bonus_count']);
                                } elseif ($o['user_basket_status'] == 'canceled') {
                                } else {
                                    $this->view->setVar('basketBack',  $b->getBasketSumBonusBack());
                                }
                                
                                $hour = date('G', strtotime($o['user_basket_date']));
                                $day  = date('w', strtotime($o['user_basket_date']));
                                
                                if (!$b->logs['deliverydate']) {
                                    $this->view->setVar('deliver_srok', $b->getDeliveryTime());
                                } else {
                                    $this->view->setVar('deliver_srok', datefromdb2textdate($b->logs['deliverydate'][0]['result'], 3) . ($b->logs['admin_deliverytime'] ? ', ' . $b->logs['admin_deliverytime'][0]['result'] : ''));
                                    
                                    if ($b->user_basket_delivery_type != 'user' && $b->user_basket_delivery_type != 'deliveryboy')
                                    {
                                        $sth = App::db()->prepare("SELECT `time1`, `time2`, `time` FROM `delivery_services` WHERE `city_id` = :city AND `service` = :service AND `time2` != '' LIMIT 1");
                                        $sth->execute(['city' => $b->address['city_id'], 'service' => $b->user_basket_delivery_type]);
                                        
                                        if ($ds = $sth->fetch()) {
                                            $this->view->setVar('delivery_service_time', $ds);
                                        }
                                    }
                                }
                                
                                $data = array();
                        
                                $data['DATE']          = datefromdb2textdate($o['user_basket_date'], 1);
                                $data['BUYER_EMAIL']   = $o['user_email'];
                                $data['PAYMENT']       = basket::$paymentTypes[$b->user_basket_payment_type]['title'];
                                $data['DELIVERY']      = $b->user_basket_delivery_type;
                                $data['PAYMENT_TYPE']  = $b->user_basket_payment_type;
                                $data['DELIVERY_TYPE'] = basket::$deliveryTypes[$b->user_basket_delivery_type]['title'];
                                $data['DELIVERY_COST'] = $b->user_basket_delivery_cost;
                        
                                // адрес доставки
                                if ($b->user_basket_delivery_type != 'user')
                                {
                                    $data['BUYER_ADDRESS'] = $b->fullAddress;
                        
                                }
                                // end адрес доставки
                                
                                foreach($b->logs AS $l)
                                {
                                    if ($l['action'] == 'user_comment')
                                        $data['COMMENT'] = $l['result']; 
                                    
                                    if ($l['action'] == 'phoneCall')
                                    {
                                        $l['result'] = basket::$phoneCallTypes[$l['result']];
                                        $l['date']   = datefromdb2textdate($l['date'], 1);
                                        if ($l['info'] != '0000-00-00 00:00:00')
                                        $l['info']   = datefromdb2textdate($l['info'], 1);
                                        $data['phoneCalls'][] = $l;
                                    }
                                    
                                    if ($l['action'] == 'change_status')
                                    {
                                        $l['date']   = datefromdb2textdate($l['date'], 1);
                                        $l['status'] = basket::$orderStatus[$l['result']];
                                        $data['hrono'][$l['result']] = $l;
                                    }
                                }
                                
                                $this->view->setVar('DETAILS', $data);
                                $this->view->setVar('LOGS', $b->logs);
                                $this->view->setVar('DETAILS', $data);
                                
                                // ВЫВОДИМ ДАННЫЕ О ЗАКАЗЕ ДЛЯ ОТПЛАТЫ АССИСТОМ
                                if (
                                    $o['user_basket_payment_type'] == 'creditcard' || 
                                    $o['user_basket_payment_type'] == 'webmoney' || 
                                    $o['user_basket_payment_type'] == 'yamoney' ||
                                    $o['user_basket_payment_type'] == 'qiwi'
                                    )
                                {
                                    $this->view->setVar('name', $this->user->user_name);
                                    $this->view->setVar('email', $this->user->user_email);
                                    
                                    $this->view->setVar("basketDate", $o['user_basket_date']);
                                    
                                    $this->view->setVar('okLink', mainUrl . "/payments/assist/?orderId=" . $row['user_basket_id'] . "&amp;show=accepted");
                                    $this->view->setVar('noLink', mainUrl . "/payments/assist/?orderId=" . $row['user_basket_id'] . "&amp;show=failed");
                                }
                        
                                if (!empty($o['user_basket_payment_type']) && $o['user_basket_payment_confirm'] == 'false' && $o['user_basket_status'] != 'canceled' && $o['user_basket_status'] != 'delivered')
                                {
                                    $this->view->setVar('PAYMENT_FORM', 'payment_forms/' . $o['user_basket_payment_type'] . '.tpl');
                                }
                                // END :: ВЫВОДИМ ДАННЫЕ О ЗАКАЗЕ ДЛЯ ОТПЛАТЫ АССИСТОМ
                        
                                $this->view->setVar('basketNum',      $b->id);
                                $this->view->setVar('basketDelivery', basket::$deliveryTypes[$b->user_basket_delivery_type]['title']);
                                $this->view->setVar('basketPayment',  basket::$paymentTypes[$b->user_basket_payment_type]['title']);
                                $this->view->setVar('custFIO', $o['name']);
                                $this->view->setVar('custPHONE', $o['phone']);
                    
                                if (array_search('sendreview_success', $this->page->reqUrl)) {
                                    $this->view->setVar('sendreview', 'sucess');
                                } else {
                                    $this->view->setVar('sendreview', 'form');
                                }
                                
                                // если за заказ были партнёрские отчисления считаем их сумму
                                if (count($partner_payments) > 0)
                                {
                                    $sth = App::db()->prepare("SELECT ABS(SUM(`price`)) AS s FROM `printshop_payments` WHERE `id` IN (" . implode(',', array_fill(0, count($partner_payments), '?')) . ")");
                                    
                                    $sth->execute($partner_payments);
                                    
                                    $foo = $sth->fetch();
                                    
                                    $this->view->setVar('partners_comission', $foo['s']);
                                }
                    }
                
                    if ($o['user_basket_status'] != 'delivered' && $o['user_basket_status'] != 'canceled')
                    {
                        $active_orders++;
                    }
                    elseif ($o['user_basket_status'] == 'delivered')
                    {
                        $delivered_orders++;
                    }
                }
            
                $this->view->setVar('ORDERS', $orders);
                
                $this->view->setVar('active_orders', $active_orders);
                $this->view->setVar('delivered_orders', $delivered_orders);
            }
        
            // переписка с администратором
            $sth = App::db()->query("SELECT m.*, ma.`user_from_id`, ma.`user_to_id`, ma.`visible_from`, ma.`visible_to`
                        FROM `messages` AS m, `messages_adressats` AS ma
                        WHERE 
                                 m.`id` = ma.`mess_id`
                            AND ((ma.`user_from_id` = '" . $this->user->id . "' AND ma.`user_to_id` = '10') OR (ma.`user_to_id` = '" . $this->user->id . "' AND ma.`user_from_id` = '10'))
                        ORDER BY m.`send_date` ASC");
                        
            $mess = $sth->fetchAll();
            
            foreach($mess AS &$m) {
                $m['send_date'] = datefromdb2textdate($m['send_date'], 1);
                if ($m['user_from_id'] == 10)
                    $m['user_login'] = 'Менеджер';
                else 
                    $m['user_login'] = $this->user->user_login;
                $m['text'] = stripslashes($m['text']);
            }
            
            $this->view->setVar('messages', $mess);
            
            unset(basket::$paymentTypes['ls']);
            unset(basket::$paymentTypes['cashon']);
            
            if (in_array($o['user_basket_delivery_type'], array('post', 'dpd')))
                unset(basket::$paymentTypes['cash']);
                
            if (basketId2sum($order) >= getVariableValue('creditcard_max'))
                unset(basket::$paymentTypes['creditcard']);
            
            $this->view->setVar('paymentTypes', basket::$paymentTypes);
        
            $this->view->setVar('deliveryboy_deliver_posible', json_encode(array_values($this->basket->getPosibleDeliverydates())));
            
            break;
            }

            $this->view->generate($this->page->index_tpl ? $this->page->index_tpl : 'index.tpl');
        }
    }