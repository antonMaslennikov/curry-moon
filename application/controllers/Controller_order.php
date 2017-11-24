<?php

    namespace application\controllers;
    
    use \smashEngine\core\App AS App;

    use \application\models\basket AS basket;
    use \application\models\basketAddress AS basketAddress;
    use \application\models\user AS user;
    use \application\models\style AS style;
    use \application\models\styleCategory AS styleCategory;
    use \application\models\certificate AS certificate;
    use \application\models\deliveryPoint AS deliveryPoint;
    
    use \smashEngine\core\exception\appException AS appException; 
    
    use \Exception;
    use \PDOException;
    use \DateTime;
          
    class Controller_order extends \smashEngine\core\Controller
    {
        public static $SNGcountry = array(675,685,693,734,759,807,865,876,879,880);
        
        public function action_index()
        {
            if (!$this->page->reqUrl[1])
                $step = 1;
            elseif (!empty($this->page->reqUrl[1]) && $this->page->reqUrl[1] != 'step')
                $step = trim($this->page->reqUrl[1]);
            else
                $step = trim($this->page->reqUrl[2]);
        
        
            $this->page->tpl = 'order/v3/order.tpl';
            $this->page->footer_tpl = 'order/footer.tpl';
            $this->page->noindex = TRUE;
            
            $this->view->setVar('step', $step);
            $this->view->setVar('basket_sum', $this->basket->basketSum);
            
            // если покупатель из москвы и он не регистрировался по акции
            if ($this->user->city == 'Москва' && $this->user->user_partner_status <= 0 && !$this->basket->logs['givegifts_firstorder'])
            {
                $this->view->setVar('freedelivery_rest', $this->VARS['deliveryfreemin_deliveryboy'] - $this->basket->basketSum);
            }
            
            //printr($this->basket->id, 1);
            //printr($this->basket->basketSum);
            
            switch ($step) 
            {
                case 'checkstatus':
                    
                    $this->page->tpl = 'order/checkstatus.tpl';
                    
                    $this->page->import(array('/js/p/checkstatus.js'));
                    //printr($_POST);
                    
                    if ($_POST['search'])
                    {
                        $sth = App::db()->prepare("SELECT `user_basket_id` 
                                    FROM `user_baskets` ub, `user_basket_address` uba
                                    WHERE 
                                            ub.`user_basket_delivery_address` = uba.`id`
                                        AND (uba.`phone` LIKE :phone OR ub.`user_basket_id` = :id)
                                    ORDER BY IF(ub.`user_basket_status` = 'canceled', 0, 1) DESC, ub.`user_basket_id` DESC
                                    LIMIT 1");
                                    
                        $sth->execute(array(
                            'phone' => '%' . substr($_POST['search'], -6),
                            'id' => $_POST['search'],
                        ));
                     
                        if ($o = $sth->fetch())
                        {
                            $order = new basket($o['user_basket_id']);
                            
                            $order->status = basket::$orderStatus[$order->user_basket_status];
                           
                            if ($order->user_basket_status == 'canceled' ||
                                ($order->user_basket_status == 'delivered' && $order->user_basket_payment_type != 'cashon') || 
                                ($order->user_basket_status == 'delivered' && $order->user_basket_payment_type == 'cashon' && $order->user_basket_payment_confirm == 'true'))
                            {}
                            else 
                            {
                                if ($order->logs['deliverydate'])
                                {
                                    $order->deliverydate = $order->logs['deliverydate'][0]['result'];
                                    
                                    if ($order->user_basket_delivery_type != 'user' && $order->user_basket_delivery_type != 'deliveryboy')
                                    {
                                        $sth = App::db()->prepare("SELECT `time1`, `time2`, `time` FROM `delivery_services` WHERE `city_id` = :city AND `service` = :service AND `time2` != '' LIMIT 1");
                                        
                                        $sth->execute(['city' => $order->address['city_id'], 'service' => $order->user_basket_delivery_type]);
                                        
                                        if ($ds = $sth->fetch()) {
                                            $this->view->setVar('delivery_service_time', $ds);
                                            //$dd = new DateTime($order->deliverydate);
                                            //$dd->modify('+' . intval($ds['time2']) . ' day');
                                            //$order->deliverydate = $dd->format('Y-m-d');
                                        }
                                    }
                                    
                                    $order->deliverydate = datefromdb2textdate($order->deliverydate);
                                }
                            }
                            
                            $this->view->setVar('order', $order);
                            // подписка на смс-информирование
                            $this->view->setVar('sms_info_checked', ($this->user->meta->order_sms_info === '0') ? FALSE : TRUE);
                        }
                    }
                    
                    break;
                    
                /**
                 * отчёт на почту о состоянии клиента
                 */
                case 'troubleshooting':
                
                    foreach ($_COOKIE as $key => $value) {
                        $cookie .= "$key => $value\n<br />";
                    }
                    
                    App::mail()->send(array(27278), 399, array(
                        'cookie' => $cookie,
                        'ip' => $_SERVER['REMOTE_ADDR'],
                    ));
                        
                    header('location: /');
                    exit('Thank you very much for your helping!');
                    
                    break;
                    
                /**
                 * Сгенерировать карту с точками самовывоза
                 */
                case 'selfDeliveryMap':
                    
                    if (!empty($this->page->reqUrl[2]) && !empty($this->page->reqUrl[3]))
                    {
                        $city    = (int) $this->page->reqUrl[2];
                        $service = addslashes(trim($this->page->reqUrl[3]));
                        $point   = (int) $this->page->reqUrl[4];
                        
                        if ($city_name = cityId2name($city))
                        {
                            $sth = App::db()->prepare("SELECT `id`, `name`, `address`, `schema` FROM `delivery_points` WHERE `city_id` = :city AND `service` = :service " . (!empty($point) ? " AND `id` = '{$point}'": ''));
        
                            $sth->execute(array(
                                'city' => $city,
                                'service' => $service,
                            ));
        
                            $points = $sth->fetchAll();
                            
                            foreach ($points as &$p) {
                                $p['name'] = htmlspecialchars(stripslashes($p['name']));
                                $p['address'] = htmlspecialchars(stripslashes($p['address']));
                                $p['schema'] = nl2br(stripslashes($p['schema']));
                            }
                            
                            if (!empty($point))
                            {
                                $this->view->setVar('point', $points[0]);
                            }
                            
                            $this->view->setVar('city_name', $city_name);
                            $this->view->setVar('service', $service);
                            $this->view->setVar('points', $points);
                            
                            $this->page->tpl = 'order/v3/selfdeliveryMap.tpl';
                            $this->page->index_tpl = 'index.popup.tpl';
                        }
                    }
                    
                    break;
                    
        
                case 'save_address':
                    
                    if ($_GET['field'] == 'fio')
                        $this->basket->MJbasketChange(array('name' => $_GET['value']));
                    
                    if ($_GET['field'] == 'skype')
                        $this->basket->MJbasketChange(array('skype' => $_GET['value']));
                    
                    if ($_GET['field'] == 'phone')
                        $this->basket->MJbasketChange(array('phone' => $_GET['value']));
                    
                    if ($_GET['field'] == 'country')
                        $this->basket->MJbasketChange(array('country' => $_GET['value']));
                    
                    if ($_GET['field'] == 'address_comment')
                        $this->basket->MJbasketChange(array('address' => $_GET['value']));
                    
                    if ($_GET['field'] == 'city')
                        $this->basket->MJbasketChange(array('city' => $_GET['value']));
                        
                    if ($_GET['field'] == 'city_name')
                        $this->basket->MJbasketChange(array('city_name' => $_GET['value']));
                        
                    if ($_GET['field'] == 'postal_code')
                        $this->basket->MJbasketChange(array('postal_code' => $_GET['value']));
                    
                    exit();
                    
                break;
                
                case 'get_address':
                    
                    try
                    {
                        if (empty($this->page->reqUrl[2])) {
                            throw new appException("Не указан номер адреса доставки", 1);
                        }
                        
                        $a = new basketAddress($this->page->reqUrl[2]);
                        
                        if (!$a) {
                            throw new appException("Указанный адрес доставки не обнаружен", 2);
                        }
                        
                        if ($this->user->id != $a->user_id) {
                            throw new appException("Указанный адрес доставки вам не принадлежит", 2);
                        }
                        
                        $a->city_name    = cityId2name($a->city);
                        $a->dpd          = cityId2dpdCost($a->city);
                        $a->post = [
                            'cost' => in_array($a->country, array(838) + self::$SNGcountry) ? 200 : 1150,
                            'time' => '2 - 4 недели'];
                        
                        exit(json_encode($a->info));
                    }
                    catch (appException $e)
                    {
                        exit('error');
                    }
                    
                    exit();
                    
                break;
                
                /**
                 * УДАЛЕНИЕ СТАРОГО АДРЕСА ДОСТАВКИ
                 */
                case 'delete_address':
                    if (!empty($this->page->reqUrl[2])) { 
                        $sth = App::db()->query("UPDATE `user_basket_address` SET `status` = '0' WHERE `id` = '" . intval($this->page->reqUrl[2]) . "' AND `user_id` = '". $this->user->id . "' LIMIT 1");
                    }
                    
                    header("location: /" . $this->page->module . '/');
                    exit();
                break;
                
                /**
                 * адрес доставки
                 */
                default:
                    
                    $this->page->import(array(
                        '/js/calendar.js',
                        '/js/p/order.v4.1.js',
                        '/css/order.styles_2013.css',
                        '/css/glissegallery.css',
                        '/js/glissegallery.js',
                    ));
                    
                    // редактирование заказа
                    if (!empty($this->page->reqUrl[1]) && is_numeric($this->page->reqUrl[1]))
                    {
                        $this->basket->id = $id = intval($this->page->reqUrl[1]);
                        
                        try
                        {
                            $this->basket = new basket($this->page->reqUrl[1]);
                            
                            if ($this->basket->user_id != $this->user->id) {
                                header('location: /');
                                exit('on access');
                            }
                        }
                        catch (Exception $e)
                        {
                            header('location: /orderhistory/');
                            exit('basket not found');
                        }
                        
                        $this->basket->getBasketSum();
                        
                        $this->basket->MJbasket['address'] = $this->basket->getAddress();
                        
                        $pt_default = $this->basket->user_basket_payment_type;
                        $dt_default = $this->basket->user_basket_delivery_type;
                        
                        $this->view->setVar('id', $this->basket->id);
                        $this->view->setVar('dt_default', $dt_default);
                        $this->view->setVar('pt_default', $pt_default);
                        
                        // камент
                        if (!empty($this->basket->user_basket_massage_id))
                        {
                            $c = App::db()->query("SELECT `text` FROM `messages` WHERE `id` = '" . $this->basket->user_basket_massage_id . "' LIMIT 1")->fetch();
                            $this->view->setVar('delivery_comment', $c['text']);
                        }
                
                        // удобное время доставки, если доставка курьером
                        if ($this->basket->user_basket_delivery_type == 'deliveryboy' || $this->basket->user_basket_delivery_type == 'deliveryboy_vip')
                        {
                            $dtime = $this->basket->logs['admin_deliverytime'][0]['result'];
                            
                            if (!empty($dtime))
                            {
                                if (strpos($dtime, 'c') !== false)
                                    $deliverytime['s'] = trim(substr($dtime, 2, 2));
                                
                                if (strpos($dtime, 'до') !== false)
                                    $deliverytime['e'] = trim(substr($dtime, (strpos($dtime, 'до') + 4)));
                                
                                $this->view->setVar('deliverytime', $deliverytime);
                            }
                        }
                        
                        if (!empty($this->basket->MJbasket['address']['metro'])) {
                            $this->view->setVar('order_metro', $this->basket->MJbasket['address']['metro']);
                        }
                        
                        // если доставка в метро, то вытаскиваем станцию и время встречи
                        if ($this->basket->user_basket_delivery_type == 'metro')
                        {
                            $mt = $this->basket->logs['admin_deliverytime'][0]['result'];
                            
                            if (!empty($mt))
                            {
                                $this->view->setVar('metrotime', stripslashes($mt));
                            }
                        }
                        
                        $city_id    = ($this->basket->MJbasket['address']['city_id']) ? $this->basket->MJbasket['address']['city_id'] : 1;
                        $country_id = $this->basket->MJbasket['address']['country_id'];
                        $city_name  = $this->basket->MJbasket['address']['city'];
                        
                        $this->basket->user_basket_delivery_date_formated = date('d/m/Y', strtotime($this->basket->user_basket_delivery_date)); 
                        
                        $this->view->setVar('order', $this->basket);
                    }
        
                    $stickers = $stickers_sum = 0;
                    
                    foreach ($this->basket->basketGoods as $g) 
                    {
                        // ПРОВЕРКА НА ЗАРЕЗЕРВИРОВАННОСТЬ
                        if ($this->basket->user_basket_status == 'active')
                        {
                            if (!isset($avalible[$g['good_stock_id']])) {
                                $foo = App::db()->query("SELECT `good_stock_quantity` +- `good_stock_inprogress_quantity` AS q FROM `good_stock` WHERE `good_stock_id` = '" . $g['good_stock_id'] . "'")->fetch();
                                $avalible[$g['good_stock_id']] = $foo['q'];
                            }
        
                            $avalible[$g['good_stock_id']] -= $g['q'];  
        
                            if ($avalible[$g['good_stock_id']]  < 0)    
                            {
                                // оформляем заявку о предзаказе, если её не оставляли в ближайшие 5 минут 
                                if (!$foo = \application\models\stockPreorder::findAll(['user_ip' => $_SERVER['REMOTE_ADDR'], 'style_id' => $g['style_id'], 'start' => date('Y-m-d H:i:s', time() - 5 * 60)])) 
                                {
                                    $preorder = new \application\models\stockPreorder;
                                    $preorder->user_ip = ip2long($_SERVER['REMOTE_ADDR']);
                                    $preorder->user_id = $this->user->id;
                                    $preorder->user_email = $this->user->user_email;
                                    $preorder->style_id = $g['style_id'];
                                    $preorder->good_id = $g['good_id'];
                                    $preorder->good_stock_id = $g['good_stock_id'];
                                    $preorder->save();
                                }
                                
                                $this->page->go('/basket/');
                            }
                        }
                        
                        $totalPrice += $g['tprice'];
                        $goods[] = $g;
                        $sc++;
                        
                        // считаем общее кол-во тряпок и наклеек
                        if ($g['cat_parent'] > 1) {
                            $gadgets++;
                        } else {
                            $wear++;
                        }
                        
                        // сичтаем общее количество "мелких" наклеек
                        if (in_array($g['category'], array('phones', 'laptops', 'touchpads', 'auto', 'stickers', 'ipodmp3'))) 
                        {
                            if (in_array($g['category'], array('auto', 'stickers'))) {
                                preg_match_all("|([0-9]{1,3}) x ([0-9]{1,3}) см|", $g['comment'], $matches);
                                
                                if ($matches[1][0] > 0 && $matches[1][0] <= 22 && $matches[2][0] > 0 && $matches[2][0] <= 32) {
                                    $stickers++;
                                    $stickers_sum += $g['tprice'];    
                                }
                            } else {
                                $stickers++;
                                $stickers_sum += $g['tprice'];
                            }
                        }
                    }
        
                    foreach ($this->basket->basketGifts as $g)
                    {
                        $totalPrice += $g['tprice'];
                        $gifts[] = $g;
                        $gsc++;
                    }
                
                    $this->view->setVar('goods', $goods);
                    $this->view->setVar('gifts', $gifts);
                    
                    if ($sc + $gsc == 0)
                    {
                        header('location: /basket/');
                        exit('basket is empty');
                    }
                    
                    // если редактируем заказ высчитываем количество бонусов за заказ
                    if ($this->basket->user_basket_status != 'active')
                    {
                        $bonusBack  = ceil($totalPrice / 100 * $this->user->buyerLevel2discount());
                        $this->view->setVar('bonusBack', $bonusBack);
                        
                        
                    }
                    
                    $bbpercent = $this->user->buyerLevel2discount();
                    $this->view->setVar('bbpercent', $bbpercent);
                    
                    // процент перевода кэша на личном счете юзера в бонусы
                    if ($this->user->user_partner_status <= 0) {
                       $bppercent = $this->VARS['bonusesPaybackPercent'];
                    } else {
                       $bppercent = 0;
                    }
                    
                    $this->view->setVar('bppercent', $bppercent);
                    
                    // сколько покупашка может оплатить с личного счёта без учёта бонусов
                    $this->view->setVar('ls_total_pay', min($this->basket->basketSum, $this->user->balance['total'] + round($this->user->balance['total'] / 100 * $bppercent)));
                    // сколько покупашка может оплатить с личного счёта с учётом бонусов
                    $this->view->setVar('ls_can_pay', min($this->basket->basketSum - $partical_payment, $this->user->balance['total'] + round($this->user->balance['total'] / 100 * $bppercent)));
                    
                    if ($_SESSION['payed_with_ls'] == 'disabled') {
                        $this->view->setVar('checkbox_payed_without_ls', TRUE);
                    }
                    
                    $product_preiod = 0;
                    
                    // ищем в корзине паттерны, если они есть то срок доставки сдвигается на время производства
                    foreach ($this->basket->basketGoods as $p) 
                    {
                        if (in_array($p['category'], array('patterns', 'patterns-sweatshirts', 'patterns-bag', 'textile'))) {
                            $product_preiod = 7;
                        }
                        
                        if ($p['category'] == 'bag') {
                            $product_preiod = 10;
                        }
                    }
                    
                    // ------------------------------------------------------------------------------------------------------------------------------
                    // ГОРОД ОПРЕДЕЛЁННЫЙ ГЕОЛОКАЦИЕЙ проверяем его наличие в нашей базе
                    // ------------------------------------------------------------------------------------------------------------------------------
                    if (empty($id))
                    {
                        if ($this->user->country == 'UA')
                            $cntry = 880;
                        elseif ($this->user->country == 'BY')
                            $cntry = 693;
                        elseif ($this->user->country == 'KZ')
                            $cntry = 759;
                        elseif ($this->user->country == 'RU')
                            $cntry = 838;
                        else
                            $cntry = 0;
                        
                        if (empty($this->user->city))
                            $this->user->city = 'Москва';
                            
                        $city_name  = $this->user->city;
                        $city_id    = cityName2id($this->user->city, $cntry, true);
                        $country_id = cityId2country($city_id);
                    }
                    // ------------------------------------------------------------------------------------------------------------------------------
                    // end ГОРОД ОПРЕДЕЛЁННЫЙ ГЕОЛОКАЦИЕЙ проверяем его наличие в нашей базе
                    // ------------------------------------------------------------------------------------------------------------------------------
                    
                    // ------------------------------------------------------------------------------------------------------------------------------
                    // СПИСОК ПРЕДЫДУЩИХ АДРЕСОВ ДОСТАВКИ
                    // ------------------------------------------------------------------------------------------------------------------------------
                    if ($this->user->authorized && empty($id))
                    {
                        $sth = App::db()->query("SELECT uba.*, ub.`user_basket_delivery_type`, ub.`user_basket_payment_type`, c.`name` AS city_name, cc.`country_id`, cc.`country_name`
                                    FROM `user_basket_address` AS uba
                                        LEFT JOIN `user_baskets` AS ub ON ub.`user_basket_delivery_address` = uba.`id` 
                                        LEFT JOIN `city` AS c ON uba.`city` = c.`id`
                                        LEFT JOIN `countries` AS cc ON uba.`country` = cc.`country_id`
                                    WHERE uba.`user_id` = '" . $this->user->id . "' AND uba.`status` = '1' AND uba.`region` != ''  
                                    GROUP BY uba.`id` 
                                    ORDER BY uba.`order_date` DESC 
                                    LIMIT 10");
            
                        if ($sth->rowCount() > 0)
                        {
                            $address = $sth->fetchAll();
        
                            //$address = array_reverse($address);
            
                            foreach ($address as $k => &$aa)
                            {
                                $aa['full_address'] = $aa['country_name'] . ((!empty($aa['city'])) ? ', г. ' . $aa['city_name'] : '') . ((!empty($aa['address'])) ? ', ул. ' . $aa['address'] : '') . ', ' . $aa['name'];
            
                                if (!empty($aa['postal_code'])) $aa['full_address'] .= ', (' . $aa['postal_code'] . ')';
                                
                                if ($aa['region'] == 'moscow' || $aa['region'] == 'nearmoscow' || $aa['region'] == 'metro')
                                {
                                    if (!empty($aa['raion'])) $aa['full_address'] .= ', р. ' . raionId2raionName($aa['raion']);
                                    if (!empty($aa['metro'])) $aa['full_address'] .= ', м. ' . metroId2metroName($aa['metro']);
                                }
                                
                                $aa['full_address'] .= ', ' . $aa['phone'];
                                $aa['order_date']   = datefromdb2textdate($aa['order_date'], 1);
                                
                                //if ($k == count($address) - 1)
                                if ($k == 0)
                                {
                                    $aa['checked'] = 'checked="checked"';
                                    
                                    if ($aa['user_basket_delivery_type'] == 'user') {
                                        $city_id    = 1;
                                        $city_name  = 'Москва';
                                        $country_id = 838;
                                    }
                                    else {
                                        if (!empty($aa['city']))
                                        {
                                            $city_id    = $aa['city'];
                                            $city_name  = $aa['city_name'];
                                            $country_id = $aa['country_id'];
                                        }
                                    }
                                    
                                }
                            }
        
                            $this->view->setVar('address', $address);
                            $this->view->setVar('new_address_showed', 'none');
                        }
                    }
                    // ------------------------------------------------------------------------------------------------------------------------------
                    // end СПИСОК ПРЕДЫДУЩИХ АДРЕСОВ ДОСТАВКИ
                    // ------------------------------------------------------------------------------------------------------------------------------
        
                    // ------------------------------------------------------------------------------------------------------------------------------
                    // ИНИЦИАЛИЗАЦИЯ АДРЕСА ДОСТАВКИ
                    // ------------------------------------------------------------------------------------------------------------------------------
                    if (!empty($this->basket->MJbasket['address']['name'])) 
                        $this->view->setVar('user_name', $this->basket->MJbasket['address']['name']);
                    else
                        if ($address[0])
                            $this->view->setVar('user_name',  $address[0]['name']);
                        else
                            if ($this->user->authorized)
                                $this->view->setVar('user_name',  $this->user->user_name);
                            else
                                $this->view->setVar('user_name', '');    
                    
        
                    if (!empty($this->basket->MJbasket['address']['phone']))
                        $this->view->setVar('user_phone', $this->basket->MJbasket['address']['phone']);
                    else
                        if ($address[0])
                            $this->view->setVar('user_phone',  $address[0]['phone']);
                        else
                            if ($this->user->authorized && !empty($this->user->user_phone))
                                $this->view->setVar('user_phone', $this->user->user_phone);
                            else
                                $this->view->setVar('user_phone', '');   
                    
                    
                    if (!empty($this->basket->MJbasket['address']['address']))
                        $this->view->setVar('default_address', $this->basket->MJbasket['address']['address']);
                    else 
                        if ($address[0])
                            $this->view->setVar('default_address',  $address[0]['address']);
                        else
                            $this->view->setVar('default_address', '');
                    
                    
                    if (!empty($this->basket->MJbasket['address']['postal_code']))
                        $this->view->setVar('default_postal_code', $this->basket->MJbasket['address']['postal_code']);
                    else
                        if ($address[0])
                            $this->view->setVar('default_postal_code',  $address[0]['postal_code']);
                        else
                            $this->view->setVar('default_postal_code', '');  
                        
                    
                    $this->view->setVar('user_email', ($this->user->authorized) ? $this->user->user_email : '');
                    // ------------------------------------------------------------------------------------------------------------------------------
                    // end ИНИЦИАЛИЗАЦИЯ АДРЕСА ДОСТАВКИ
                    // ------------------------------------------------------------------------------------------------------------------------------
                    
                    
                    // ------------------------------------------------------------------------------------------------------------------------------
                    // Список стран и городов
                    // ------------------------------------------------------------------------------------------------------------------------------
                    if (!$countrys = App::memcache()->get($this->page->module . '_countrys')) 
                    {
                        $sth = App::db()->query("SELECT `country_id`, `country_name` FROM `countries` WHERE `country_id` NOT IN ('838','880','693','759') ORDER BY `raiting`, `country_name`");
                        
                        foreach ($sth->fetchAll() as $c) {
                            $countrys[$c['country_id']] = $c;
                        }
        
                        App::memcache()->set($this->page->module . '_countrys', $countrys, false, 7 * 24 * 3600);
                    }
                        
                    $this->view->setVar('country', $countrys);
        
                    // Вытаскиваем список пунктов самовывоза по городам
                    if (!$dp = App::memcache()->get($this->page->module . '_delivery_points')) 
                    {
                        $sth = App::db()->query("SELECT `id`, `city_id`, `service`, `name`, `address`, `schema` FROM `delivery_points` WHERE `active` > '0'");
                        
                        foreach ($sth->fetchAll() as $r) {
                            $r['address'] = htmlspecialchars(stripslashes($r['address']));
                            $r['schema'] = stripslashes($r['schema']);
                            $dp[$r['city_id']][$r['service']][] = $r;
                        }
                        
                        App::memcache()->set($this->page->module . '_delivery_points', $dp, false, 7 * 24 * 3600);
                    }
        
                    // сервисы доставки (по городам)
                    if (!$ds = App::memcache()->get($this->page->module . '_delivery_services'))
                    {
                        $sth = App::db()->query("SELECT `service`, `city_id`, `time`, `time1`, `time2`, `cost`, `cost2`, `cashon` FROM `delivery_services` WHERE 1");
                        
                        foreach ($sth->fetchAll() as $r) {
                                
                            if (($r['service'] == 'IMlog' || $r['service'] == 'IMlog_self' || $r['service'] == 'deliveryboy') && ((date('m-d') == '08-18' && date('H' >= 21)) || date('m-d') == '08-19' || date('m-d') == '08-20')) {
                                 $r['cost'] = $r['cost2'] = 0;
                            }
                                 
                                  
                            if ($dp[$r['city_id']][$r['service']]) {
                                $r['self-delivery-points'] = json_encode($dp[$r['city_id']][$r['service']]);
                            }
                            
                            $ds[$r['city_id']][$r['service']] = $r;
                        }
                        
                        App::memcache()->set($this->page->module . '_delivery_services', $ds, false, 7 * 24 * 3600);
                    }
        
        
                    // сервисы доставки (по странам)
                    if (!$dsc = App::memcache()->get($this->page->module . '_delivery_services_country')) 
                    {
                        try
                        {
                            $sth = App::db()->query("SELECT `service`, `country_id`, `time`, `time1`, `time2`, `cost` FROM `delivery_services_dpd_country` WHERE 1");
                            
                            foreach ($sth->fetchAll() as $r) {
                                $dsc[$r['country_id']][$r['service']] = $r;
                            }
                            
                            App::memcache()->set($this->page->module . '_delivery_services_country', $dsc, false, 7 * 24 * 3600);
                        }
                        catch (Exception $e)
                        {
                            printr($e);
                        }
                    }
                    
                    // Список городов с группировкой по первой букве
                    if (!$rs = App::memcache()->get($this->page->module . '_citys')) 
                    {
                        $sth = App::db()->query("SELECT c.`id`, c.`name`, SUBSTR(`name`, 1, 1) AS letter, c.`country`, c.`gmt`
                                    FROM `city` c
                                    WHERE c.`status` = '1' 
                                    GROUP BY c.`id`
                                    ORDER BY letter, c.`rating`");
                                    
                        $rs = $sth->fetchAll();
                        
                        App::memcache()->set($this->page->module . '_citys', $rs, false, 7 * 24 * 3600);
                    }
                    
                    $cc = 1;
                    
                    // цена доставки почтой россии по россии и зарубеж
                    $deliverycost_post_russia    = $this->VARS['deliverycost_post'];
                    $deliverycost_post_world     = $this->VARS['deliverycost_post_world'];
                    
                    // если заказ оформляется по акции или это партнёр, то бесплатная доставка на него не распространяется
                    $deliveryfreemin_imlog_self  = !$this->basket->logs['givegifts_firstorder'] && $this->user->user_partner_status <= 0 ? $this->VARS['deliveryfreemin_imlog_self'] : 100000;
                    $deliveryfreemin_post_mj     = !$this->basket->logs['givegifts_firstorder'] && $this->user->user_partner_status <= 0 ? $this->VARS['deliveryfreemin_post_mj'] : 100000;
                    $deliveryfreemin_deliveryboy = !$this->basket->logs['givegifts_firstorder'] && $this->user->user_partner_status <= 0 ? $this->VARS['deliveryfreemin_deliveryboy'] : 100000;
                    
                    if (empty($id)) {
                        $hour = date('G');
                        $day  = date('w');
                    } else {
                        $hour = date('G', strtotime($order->user_basket_date));
                        $day  = date('w', strtotime($order->user_basket_date));
                    }
                    
                    foreach ($rs as $ck => $c) 
                    {
                        if ($this->basket->basketSum >= $deliveryfreemin_post_mj && $c['country'] == 838)
                            $c['postcost'] = 0;
                        else
                            $c['postcost'] = round((($c['country'] == 838 || in_array($c['country'], self::$SNGcountry)) ? $deliverycost_post_russia : $deliverycost_post_world) / ($this->page->lang == 'en' ? $this->VARS['usdRate'] : 1), 1);
                        
                        // если в корзине только наклейки суммой более 500 рублей, доставка почтой россии заказным письмом бесплатно
                        if ($sc == $stickers && $stickers_sum >= 500 && $c['country'] == 838) {
                            $c['postcost'] = 0;
                            $c['post_zakaznoe'] = TRUE;
                        }
                        
                        // dpd, если возможна доставка до конечного города
                        if ($ds[$c['id']]['dpd'])
                        {
                            if ($this->basket->basketSum >= $deliveryfreemin_post_mj && $c['country'] == 838)
                                $c['dpd_cost'] = 0;
                            else 
                                $c['dpd_cost'] = ($this->basket->basketSum >= basket::$dpdMarginLimit && $ds[$c['id']]['dpd']['cost2']) ? $ds[$c['id']]['dpd']['cost2'] : $ds[$c['id']]['dpd']['cost'];
                            
                            $c['time1']    = $ds[$c['id']]['dpd']['time1'];
                            $c['time2']    = $ds[$c['id']]['dpd']['time2'];
                            
                            if ($this->page->lang == 'en') 
                                $c['dpd_cost'] = round($c['dpd_cost'] / $this->VARS['usdRate'], 1);
                        }
                        // цены расчитываются из учёта доставки до страны
                        elseif (!$ds[$c['id']]['dpd'] && $dsc[$c['country']]['dpd']) 
                        {
                            if ($this->basket->basketSum >= $deliveryfreemin_post_mj)
                                $c['dpd_cost'] = 0;
                            else
                                $c['dpd_cost'] = ($this->basket->basketSum >= basket::$dpdMarginLimit) ? $dsc[$c['country']]['dpd']['cost2'] : $dsc[$c['country']]['dpd']['cost'];
                            
                            $c['time1']    = $dsc[$c['country']]['dpd']['time1'];
                            $c['time2']    = $dsc[$c['country']]['dpd']['time2'];
                            
                            if ($this->page->lang == 'en') 
                                $c['dpd_cost'] = round($c['dpd_cost'] / $this->VARS['usdRate'], 1);
                        }
                        
                        if ($ds[$c['id']]['IMlog'] && $product_preiod > 0)
                        {
                            $ds[$c['id']]['IMlog']['time'] = ($product_preiod + $ds[$c['id']]['IMlog']['time1']) . ' рабочих дней';
                        }
                        
                        if ($ds[$c['id']]['IMlog_self'])
                        {
                            $ds[$c['id']]['IMlog_self']['time'] = ($product_preiod + $ds[$c['id']]['IMlog_self']['time1']) . ' рабочих дней';
                        }
                        
                        $c['IMlog_cost'] = $ds[$c['id']]['IMlog']['cost'];
                        $c['IMtime']     = $ds[$c['id']]['IMlog']['time'];
                        
                        if ($this->page->lang == 'en') 
                            $c['IMlog_cost'] = round($c['IMlog_cost'] / $this->VARS['usdRate'], 1);
                        
                        if ($ds[$c['id']]['IMlog_self'])
                        {
                            $c['IMlog_self_cost']      = $this->basket->basketSum >= $deliveryfreemin_imlog_self ? 0 : $ds[$c['id']]['IMlog_self']['cost'];
                            $c['IMself_time']          = $ds[$c['id']]['IMlog_self']['time'];
                            $c['IMselfDeliveryPoints'] = $ds[$c['id']]['IMlog_self']['self-delivery-points'];
                            
                            if ($this->page->lang == 'en') 
                                $c['IMlog_self_cost'] = round($c['IMlog_self_cost'] / $this->VARS['usdRate'], 1);
                        }
                        
                        if ($ds[$c['id']]['dpd_self'])
                        {
                            $c['dpd_self_cost']         = $ds[$c['id']]['dpd_self']['cost'];
                            $c['dpdself_time']          = $ds[$c['id']]['dpd_self']['time'] . (($ds[$c['id']]['dpd_self']['time1'] == 1) ? ' день' : ' дня');
                            $c['dpdselfDeliveryPoints'] = $ds[$c['id']]['dpd_self']['self-delivery-points'];
                            $c['dpd_self_cashon']       = $ds[$c['id']]['dpd_self']['cashon'];
                            
                            if ($this->page->lang == 'en') 
                                $c['dpd_self_cost'] = round($c['dpd_self_cost'] / $this->VARS['usdRate'], 1);
                        }
                        
                        //$c['baltick_cost'] = $ds[$c['id']]['baltick']['cost'];
                        //$c['baltickTime']  = $ds[$c['id']]['baltick']['time'];
                                        
                        // время доставки dpd
                        $dpdtime = array_diff(array($c['time1'], $c['time2']), array('0', ''));
                        foreach ($dpdtime as $k => $v) 
                            $dpdtime[$k] += (($hour < 17) ? 1 : 2);
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
                    // ------------------------------------------------------------------------------------------------------------------------------
                    // end Список стран и городов
                    // ------------------------------------------------------------------------------------------------------------------------------
                    
                    
                    // ------------------------------------------------------------------------------------------------------------------------------
                    // станции метро
                    // ------------------------------------------------------------------------------------------------------------------------------
                    if (!$metro_stations = App::memcache()->get('metro_stations')) 
                    {
                        $r = App::db()->query("SELECT * FROM `metro` ORDER BY `metro_name`");
                        $metro_stations = $r->fetchAll();
                        App::memcache()->set('metro_stations', $metro_stations, false, 30 * 24 * 3600);
                    }
                    
                    $this->view->setVar('metro_stations', $metro_stations);
                    // ------------------------------------------------------------------------------------------------------------------------------
                    // end станции метро
                    // ------------------------------------------------------------------------------------------------------------------------------
                    
                    $dt['post'] = '';
                    $dt['dpd']  = '';
                    $dt['user'] = 0;
                    
                    // вычисляем сумарный вес товаров в корзине
                    $tweight = $this->basket->getBasketWeight();
                    
                    if ($dpd = cityId2dpdCost($city_id, $tweight)) 
                    {
                        if ($this->basket->basketSum >= $deliveryfreemin_post_mj)
                            $dt['dpd'] = 0;
                        else 
                            $dt['dpd'] = $dpd['cost'];
                        
                        if ($this->page->lang == 'en')
                        {
                            $dpd['cost'] = round($dpd['cost'] / $this->VARS['usdRate'], 1);
                        }
                        
                        $this->view->setVar('dpd_cashon_possible', $dpd['cashon']);
                        $this->view->setVar('dpd_delivery_time', $dpd['time']);
                    }
                    
                    if ($im = cityId2imCost($city_id))
                    {
                        if ($this->page->lang == 'en')
                        {
                            $im['cost'] = round($im['cost'] / $this->VARS['usdRate'], 1);
                        }
                    }
                    
                    $this->view->setVar('default_city_id', $city_id);
                    $this->view->setVar('default_city_name', $city_name);
                    $this->view->setVar('default_city_dpd', $dpd);
                    $this->view->setVar('default_city_im', $im);
                    $this->view->setVar('order_country', $country_id);
        
                    // ------------------------------------------------------------------------------------------------------------------------------
                    // Сроки доставки по москве разными способами дотавки
                    // ------------------------------------------------------------------------------------------------------------------------------
                    if ($gadgets > 0)
                    {
                        $this->view->setVar('deliveryboy_deliver_srok', $this->basket->getDeliveryTimeGadgets('deliveryboy'));
                        $this->view->setVar('deliveryboy_deliver_srok_full', $this->basket->getDeliveryTimeGadgets('deliveryboy', true));
                        
                        $this->view->setVar('user_deliver_srok', $this->basket->getDeliveryTimeGadgets('user'));
                        $this->view->setVar('user_deliver_srok_full', $this->basket->getDeliveryTimeGadgets('user', true));    
                    }
                    else 
                    {
                        $this->view->setVar('deliveryboy_deliver_srok', $this->basket->getDeliveryTime('deliveryboy'));
                        $this->view->setVar('deliveryboy_deliver_srok_full', $this->basket->getDeliveryTime('deliveryboy', true));
                        
                        $this->view->setVar('user_deliver_srok', $this->basket->getDeliveryTime('user'));
                        $this->view->setVar('user_deliver_srok_full', $this->basket->getDeliveryTime('user', true));
                    }
                    
        
                    // опдеределяем список дат самовывоза доступных для выбора (для календарика)
                    $this->view->setVar('user_deliver_posible', json_encode(array_values($this->basket->getPosibleDeliverydates('user'))));
                    
                    // опдеределяем список дат доставки курьером доступных для выбора (для календарика)
                    $this->view->setVar('deliveryboy_deliver_posible', json_encode(array_values($this->basket->getPosibleDeliverydates('deliveryboy'))));
                    
                    // временные интервалы доставки для самовывоза 
                    $delivery_time_intervals = array(
                        'user' => array(
                            //array('min' => 10, 'max' => 14, 'caption' => 'с 10 до 14'),
                            array('min' => 14, 'max' => 17, 'caption' => 'с 14 до 17'),
                            array('min' => 17, 'max' => 19, 'caption' => 'с 17 до 19'),
                        ),
                    );
                    
                    if ($this->basket->user_basket_status == 'active') {
                        foreach ($delivery_time_intervals['user'] as $k => $v) 
                        {
                            //if ($v['min'] < 18 && date('m-d') == '12-30') {
                            //    unset($delivery_time_intervals['user'][$k]);
                            //}
                            
                            if (date('H') > $v['max'] - 1) {
                                $delivery_time_intervals['user'][$k]['disabled'] = TRUE;
                                //unset($delivery_time_intervals['user'][$k]);
                            }
                        }
                    }
                    
                    $this->view->setVar('delivery_time_intervals', $delivery_time_intervals);
                    
                    // ------------------------------------------------------------------------------------------------------------------------------
                    // end Сроки доставки по москве разными способами дотавки
                    // ------------------------------------------------------------------------------------------------------------------------------
                
                    $pt['CREDITCARD_onplace']['discount'] = $pt['CREDITCARD']['discount'] = intval($this->VARS["creditcardDiscount"]);
                    $pt['YAMONEY']['discount']    = intval($this->VARS["yamoneyDiscount"]);
                    $pt['WEBMONEY']['discount']   = intval($this->VARS["webmoneyDiscount"]);
                    
                    // если суммарная цена заказа больше определённого минимума или это режим обмена по браку, то доставка бесплатная
                    if ($totalPrice >= $deliveryfreemin_deliveryboy || $this->basket->logs['set_mark'][0]['result'] == 'exchange_brak') {
                        $dt['deliveryboy'] = $dt['metro'] = 0;
                        $dt['deliveryboy_mkad'] = 250;
                    }
                    else
                    {
                        $dt['deliveryboy']      = $this->VARS['delivery_deliveryboy_cost'];
                        $dt['deliveryboy_vip']  = $this->VARS['delivery_deliveryboy_vip_cost'];
                        $dt['metro']            = $this->VARS['delivery_metro_cost'];
                        $dt['deliveryboy_mkad'] = $this->VARS['deliverycost_mkad'];
                    }
                    
                    $pt['CASH']['discount']     = intval($this->VARS["cashDiscount"]);
                    $pt['QIWI']['discount']     = intval($this->VARS["qiwiDiscount"]);
                    $pt['SBERBANK']['discount'] = intval($this->VARS["sberbankDiscount"]);
                    
                    if ($this->basket->user_basket_payment_partical == 0)
                        $partical_payment = min($this->user->user_bonus, round($totalPrice / 100 * $this->VARS['maxParticalPayPercent']));
                    else
                        $partical_payment = $this->basket->user_basket_payment_partical;
                    
                    $this->view->setVar('maxParticalPayPercent', $this->VARS['maxParticalPayPercent']);
                    $this->view->setVar('bonuses', $partical_payment);
                    $this->view->setVar('rest_bonuses', max($this->user->user_bonus - $partical_payment, 0));
                    
                    $partical_payment -= $exchengedBonuses;
                    $this->user->user_bonus -= $exchengedBonuses;
                    
                    //printr($dt);
                    
                    unset($pt['QIWI']);
                    
                    $dTypes = array();
                    $pTypes = array();
        
                    foreach($dt AS $dk => $dv) 
                    {
                        if ($dv !== '')
                        {
                            if ($deliveryDiscount[$dk] > $this->basket->user_basket_discount)
                                $this->basket->user_basket_discount = intval($deliveryDiscount[$dk]);
                            
                            // если в корзине только наклейки суммой более 500 рублей, доставка почтой россии заказным письмом бесплатно
                            if ($sc == $stickers && $stickers_sum >= 500 && $dk == 'post') {
                                $dv = $deliveryTypeDiscount = 0;
                            }
                            
                            $dTypes[$dk] = array(
                                'cost'       => $dv - ($dv / 100 * $deliveryTypeDiscount), 
                                'cost_usd'   => round(($dv - ($dv / 100 * $deliveryTypeDiscount)) / ($this->page->lang == 'en' ? $this->VARS['usdRate'] : 1), 1), 
                                'totalPrice' => ($totalPrice + $dv) - $ddd,
                                'particalPayment' => min($this->user->user_bonus, round((($totalPrice + $dv) - $ddd) / 100 * $this->VARS['maxParticalPayPercent'])),
                                'discount'   => intval($deliveryDiscount[$dk]), 
                                'selected'   => (($dt_default == $dk) ? 'checked="checked"' : ''));
                            
                            if ($sc == $stickers && $stickers_sum >= 500) {    
                               $dTypes['post']['post_zakaznoe'] = TRUE;
                            }
                            
                        } else {
                            $dTypes[$dk] = array();
                        }
                    }
                    
                    $this->view->setVar('time', range(10, 18));
        
        
                    $basket_discount = $this->basket->user_basket_discount;
        
                    foreach($pt AS $ptk => $ptv)
                    {
                        // за наличный расчёт скидки нет
                        if ($ptv['discount'] > $this->basket->user_basket_discount) {
                            $this->basket->user_basket_discount = $ptv['discount'];
                        }
        
                        $ptv['total'] = $this->basket->getBasketSum() + $this->VARS[$ptk . '_margin'] - $this->basket->user_basket_delivery_cost + ($this->basket->user_basket_status != 'active' ? $this->basket->user_basket_payment_partical : 0);
        
                        $pTypes[$ptk] = array(
                            'discount'              => $ptv['discount'], 
                            'total'                 => $ptv['total'], 
                            'total_without_bonuses' => ((($ptv['total'] - $this->user->user_bonus) < 0) ? 0 : $ptv['total'] - $this->user->user_bonus),
                            'checked'               => (($pt_default == strtolower($ptk)) ? 'checked="checked"' : ''));
                            
                        $this->basket->user_basket_discount = $basket_discount;
                    }
                    
                    // Вырубаем тип доставки "курьер день-в-день", если он сегодня уже не возможен
                    if ($this->basket->products['wear'] && count($this->basket->products) == 1) {
                        if (date('H') >= 17)
                            $dTypes['deliveryboy_vip']['next_day'] = datefromdb2textdate(date('Y-m-d', time() + 24 * 3600));
                    } else {
                        unset($dTypes['deliveryboy_vip']);
                    }
                    
                    //printr($pt_default);
                    //printr($dTypes);
                    //printr($pTypes);
                    
                    $this->view->setVar('deliveryTypes', $dTypes);
                    $this->view->setVar('paymentTypes', $pTypes);        
                    
                    
                    $a = $this->basket->MJbasket['address'];
                    
                    if ($a['region'] != 'user' && $a['region'] != 'metro')
                    {
                        $a['full_address'] = countryId2countryName($a['country']) . ((!empty($a['city'])) ? ', г. ' . cityId2name($a['city']) : '') . ((!empty($a['address'])) ? ', ул. ' . $a['address'] : '') . ', ' . $a['name'];
            
                        if (!empty($a['postal_code'])) $a['full_address'] .= ', (' . $a['postal_code'] . ')';
                        
                        if ($a['region'] == 'moscow' || $a['region'] == 'nearmoscow' || $a['region'] == 'metro')
                        {
                            if (!empty($a['raion'])) $a['full_address'] .= ', р. ' . raionId2raionName($a['raion']);
                            if (!empty($a['metro'])) $a['full_address'] .= ', м. ' . metroId2metroName($a['metro']);
                        }
                        
                        $a['full_address'] .= ', +' . $a['phone'];
                        
                        $this->view->setVar('deliveryAddress', $a['full_address']);
                    }
                    
                    
                    
        
        
                    /**
                     * СОХРАНЕНИЕ ДАННЫХ
                     */
                    if ($_POST['save'])
                    {
                        //printr($partical_payment);
                        //printr($_POST, 1);
                        //printr($basket); 
                        //exit();
                        
                        if (!$_POST['ID'] && $this->basket->user_basket_status != 'active' && $this->user->meta->mjteam != 'super-admin' && $this->user->meta->mjteam != 'grand_manager' && $this->user->meta->mjteam != 'developer')
                        {
                            //$error[] = 'Данная корзина не может быть сохранена. Обратитесь к администрации сайта';
                        }
                        
                        if (!empty($_POST['fio'])) {
                            $name = addslashes($_POST['fio']);
                        } else {
                            $error[] = 'Не указано имя';
                        }
                        
                        if (!empty($_POST['phone'])) {
                            $phone = addslashes($_POST['phone']);
                        } else {
                            $error[] = 'Не указан телефон';
                        }
                        
                        if (!$this->user->authorized && !empty($_POST['email'])) {
                            $email = addslashes($_POST['email']);
                        } elseif ($this->user->authorized) {
                            $email = $this->user->user_email;
                        } else {
                            $error[] = 'Не указан адрес электронной почты';
                        }
                        
                        if (!empty($_POST['data']['delivery']) && in_array($_POST['data']['delivery'], array_keys(basket::$deliveryTypes))) 
                            $deliveryType = addslashes(trim($_POST['data']['delivery']));
                        else
                            $error[] = 'Не выбран способ доставки';
                            
                        if (!empty($_POST['data']['payment_method']))   
                            $paymentType  = addslashes(trim($_POST['data']['payment_method']));
                        else
                            $error[] = 'Не выбран способ оплаты';
                        
                        /**
                         * Если прислали код сертификата
                         */
                        if (!empty($_POST['serCode']))
                        {
                            try
                            {
                                $cert = certificate::find($_POST['serCode']);
                                
                                if ($cert->certification_active != 'active' || ($cert->lifetime != '0000-00-00' && strtotime($cert->lifetime) <= time()))
                                {
                                    $error[] = $this->page->translate['ORDERV3_certificate_already_activated'];
                                    $this->view->setVar('certification_error', 1);
                                }
                            }
                            catch (Exception $e) 
                            {
                                $error[] = $e->getMessage();
                                $this->view->setVar('certification_error', $e->getMessage());
                            }
                        }
                        
                        // если все поля заполнены верно
                        if (count($error) == 0)
                        {
                            if ($_POST['ID'])
                                $this->basket->id = intval($_POST['ID']);
                            
                            // Регаем неавторизованного пользователя, если мыло есть
                            if (!$this->user->authorized) 
                            {
                                $this->user->create(array('user_email' => $email, 'user_name' => $name));
                                $this->user->authorize();
                                
                                App::mail()->send($this->user->id, 156, array(
                                    'code'          => md5($this->user->id),
                                    'user_id'       => $this->user->id,
                                    'user_login'    => $this->user->user_login,
                                    'user_password' => $this->user->password,
                                ));
                                
                                $this->basket->basketChange(array('user_id' => $this->user->id));
                                
                                
                                $phone = str_replace(array(' ', '(', ')', '-', '+'), '', $phone);
        
                                if (substr($phone, 0, 1) == 8)
                                    $phone = 7 . substr($phone, 1);
                                
                                // проверяем, есть ли в нашей базе уже пользователь с таким мылом или телефоном
                                try
                                {
                                    $sth = App::db()->prepare("SELECT 
                                                            SUM(IF(`user_email` = :email, 1, 0 )) AS emails, 
                                                            SUM(IF(`user_phone` = :phone, 1, 0 )) AS phones
                                                          FROM 
                                                            `users`
                                                          WHERE 
                                                                `user_id` != :user_id
                                                            AND (`user_email` = :email OR `user_phone` = :phone)
                                                            AND `user_status` = 'active'");
                                                            
                                    $sth->execute(array(
                                        'user_id' => $this->user->id,
                                        'email' => $email,
                                        'phone' => $phone,
                                    ));
                                    
                                    if ($duplicates = $sth->fetch())
                                    {
                                        if ($duplicates['emails'] > 0)
                                            $dm = 'email';
                                        //if ($duplicates['phones'] > 0)
                                            //$m = 'phone';
                                        if ($duplicates['emails'] > 0 && $duplicates['phones'] > 0)
                                            $dm = 'both';
                                        
                                        if (!empty($dm))
                                            $this->user->setMeta('user_duplicate', $dm);
                                    }
                                }
                                catch (Exception $e)
                                {
                                    printr($e);
                                }
                            }
                            
                            // вычисляем регион доставки
                            if ($_POST['data']['delivery'] == 'user')
                                $region = 'user';
                            elseif ($_POST['city'] == 1 && $_POST['city_name'] == 'Москва' && $_POST['country'] == 838)
                                $region = 'moscow';
                            else 
                            {
                                if (intval($_POST['country']) == 838) {
                                    $region = 'russia';
                                } else {
                                    $region = 'country';    
                                }
        
                                // город в базе не обнаружен
                                if (empty($_POST['city']) && strtolower($_POST['city_name']) != 'москва')
                                {
                                    $sth = App::db()->query("SELECT `id` FROM `city` WHERE `name` = '" . addslashes($_POST['city_name']) . "' LIMIT 1");
                                    
                                    if ($sth->rowCount() == 0) 
                                    {
                                        if ($_POST['country'] == 838) {
                                            $_POST['country'] = 0;  
                                        }
                                        
                                        $sth2 = App::db()->query("INSERT INTO `city` SET 
                                            `name` = '" . addslashes($_POST['city_name']) . "', 
                                            `country` = '" . intval($_POST['country']) . "'");
                                            
                                        $_POST['city'] = App::db()->lastInsertId();
                                        
                                    } else {
                                        $city = $sth->fetch();
                                        $_POST['city'] = $city['id'];
                                    }
                                }
                            }
                            
                            // сохраняем данные о доставке
                            $this->basket->MJbasketChange(array(
                                        'region'      => $region,
                                        'name'        => addslashes($_POST['fio']),
                                        'skype'       => addslashes($_POST['skype']),
                                        'phone'       => addslashes(trim($_POST['phone'])),
                                        'country'     => intval($_POST['country']),
                                        'address'     => ($region != 'user') ? addslashes($_POST['address_comment']) : '',
                                        'city'        => intval($_POST['city']),
                                        'city_name'   => cityId2name($_POST['city']),
                                        'postal_code' => ($region != 'user') ? addslashes($_POST['postal_code']) : '',
                                        'metro'       => addslashes(trim($_POST['data']['select_metro_' . $deliveryType])),
                                        'comment'     => addslashes(trim($_POST['data']['comment_text']))
                                    ), 
                                    'address', intval($_POST['ID']));
                        
                        
                            
                            if ($deliveryType == 'deliveryboy_mkad')
                                $this->basket->log('admin_comment', 'Курьер за МКАД');
                            
                            if ($_POST['ID'] && $_POST['data']['delivery'] != $this->basket->user_basket_delivery_type) {
                                $this->basket->log('change_delivery', $_POST['data']['delivery'], $this->basket->user_basket_delivery_type);
                            }
                            
                            // выбрано время встречи
                            if (!empty($_POST['data'][$deliveryType . '_time'])) {
                                if ($_POST['data'][$deliveryType . '_time'] != $this->basket->logs['admin_deliverytime'][0]['result']) {
                                    $this->basket->log('admin_deliverytime', $_POST['data'][$deliveryType . '_time'], $this->user->id);
                                    $this->basket->user_basket_delivery_time = $_POST['data'][$deliveryType . '_time'];
                                }
                            }
                            
                            // выбрана дата встречи
                            if (!empty($_POST['data'][$deliveryType . '_date'])) {
                                $dd = explode('/', $_POST['data'][$deliveryType . '_date']);
                                $ddate = date('Y-m-d', mktime(0,0,0, $dd[1], $dd[0], $dd[2]));
                                
                                if ($ddate != $this->basket->logs['deliverydate'][0]['result']) {
                                    $this->basket->log('deliverydate', $ddate, $this->user->id);
                                    $this->basket->user_basket_delivery_date = $ddate;
                                }
                            }
                            
                            // вычисляем цену доставки
                            switch ($deliveryType) {
                                case 'deliveryboy':
                                case 'deliveryboy_mkad':
                                case 'deliveryboy_vip':
                                case 'metro':
                                    $deliveryCost = ($this->basket->logs['set_mark'][0]['result'] == 'exchange_brak') ? 0 : $dTypes[$deliveryType]['cost'];
                                    break;
                                case 'post':
                                    $deliveryCost = in_array($_POST['country'], array(838) + self::$SNGcountry) ? $this->VARS['deliverycost_post'] : $this->VARS['deliverycost_post_world'];
                                    break;
                                case 'user':
                                case 'samovivoz':
                                    $deliveryCost = 0;
                                    break;
                                default:
                                    $deliveryCost = $jcitys[$_POST['country']][$_POST['city']][$deliveryType . '_cost'];
        
                                    // если известен и выбран пункт самовывоза в этом городе
                                    if ($_POST[$deliveryType . '_point'] && $this->basket->address['delivery_point'] != $_POST[$deliveryType . '_point'])
                                    {
                                        $sth = App::db()->query(sprintf("SELECT `id`, `name`, `address` FROM `delivery_points` WHERE `id` = '%d' LIMIT 1", $_POST[$deliveryType . '_point']));
                                        
                                        if ($sth->rowCount() == 1) 
                                        {
                                            $point = $sth->fetch();
                                            //$this->basket->log('user_comment', addslashes('Самовывоз из "' . $point['name'] . '" (' . $point['address'] . ')'), $_POST[$deliveryType . '_point']);
                                            
                                            $this->basket->MJbasketChange(array('delivery_point' => $point['id']));
                                        }
                                    }
                                    
                                    // если выбрана оплата наличными, переделываем её в наложенный платёж
                                    if ($paymentType == 'cash')
                                        $paymentType = 'cashon';
                                    
                                    break;
                            }
        
                            $deliveryType = str_replace('_mkad', '', $deliveryType);
                            
                            $this->basket->user_basket_delivery_type = $deliveryType;
                            $this->basket->user_basket_delivery_cost = $deliveryCost - ($deliveryCost / 100) * intval($deliveryTypeDiscount);
                            $this->basket->user_basket_payment_type  = $paymentType;
                            
                            if ($_POST['source'] && $_POST['source'] != '(direct)') {
                                $this->basket->user_basket_source = $_POST['source'];
                            }
                            
                            $this->basket->save();
                            
                            /**
                             * ЧАСТИНАЯ ОПЛАТА ЗАКАЗА БОНУСАМИ
                             */
                            if (!$_POST['data']['my_bonuses']) {
                                $partical_payment = 0;
                            }
                            
                            // если заказ уже оплачивался бонусами
                            if ($this->basket->user_basket_payment_partical > 0 && $this->basket->user_basket_payment_partical != $partical_payment)
                            {
                                // возвращаем их
                                $this->user->addBonus($this->basket->user_basket_payment_partical, 'Возврат частичной оплаты при редактировании заказа #' . $this->basket->id);
                                // обнуляем частичную оплату
                                $this->basket->basketChange(array('user_basket_payment_partical' => 0));
                            }
        
                            // при оформлении указан код активного сертификата
                            if ($cert)
                            {
                                try
                                {
                                    $cert->certification_limit--;
                            
                                    if ($cert->certification_limit == 0) {
                                        $cert->certification_active = 'none';
                                    }
                                    
                                    $cert->user_id = $this->user->id;
                                    $cert->activation_ip = $_SERVER['REMOTE_ADDR'];
                                    $cert->user_basket_id = $this->basket->id;
                                    $cert->use_date = NOW;
                                    
                                    $cert->save();
                                    
                                    switch ($cert->certification_type)
                                    {
                                        // ДИСКОНТНАЯ КАРТА (+ xx% процентов в корзинную скидку)
                                        case 'percent':
                                            
                                            $this->basket->user_basket_discount = $cert->certification_value;
                                            $this->basket->user_basket_discount_description = 'Скидка по дисконтной карте';
                                            $this->basket->save();
                                            
                                            unset($this->basket->basketGoods);
                                            
                                            //foreach ($this->basket->basketGoods as &$bg) 
                                            //{
                                            //  $bg['discount'] += $this->basket->user_basket_discount;
                                            //}
                    
                                            $this->basket->log('activateDiscontCard', $cert->certification_id, $cert->certification_value);
                                            
                                        break;
                    
                                        // СЕРТИФИКАТ (+ xx руб. к бонусам пользователя)
                                        case 'amount':
                                            
                                            $this->user->addBonus($cert->certification_value, 'начислено с сертификата №' . $cert->certification_id, $this->basket->id);
                                        
                                            $this->basket->log('activateCertificate', $cert->certification_id, $cert->certification_value);
                                            
                                            // если на счету не было бонусов до этого и галка "использовать бонусы" не стояла
                                            // ставим её принудительно
                                            if (!$_POST['data']['my_bonuses'])
                                                $partical_payment = min($cert->certification_value, $this->basket->basketSum + $deliveryCost);
                                            else
                                                $partical_payment = min($this->user->user_bonus, $this->basket->basketSum + $deliveryCost);
                                            
                                            $_POST['data']['my_bonuses'] = 1;
                                            
                                        break;
                                    }
                                }
                                catch (Exception $e)
                                {
                                    printr($e->getMessage());
                                }
                            }
                            
                            // возможна оплата бонусами
                            if ($_POST['data']['my_bonuses'])
                            {
                                $partical_payment = min(round($this->basket->basketSum / 100 * $this->VARS['maxParticalPayPercent']) + $this->basket->user_basket_delivery_cost, $this->user->user_bonus);
                                
                                if ($partical_payment > 0)
                                {
                                    if ($this->page->lang == 'en') {
                                        $this->user->user_bonus = $this->user->user_bonus_rub;
                                    }
                                    
                                    if ($this->basket->user_basket_payment_partical != $partical_payment)
                                    {
                                        $this->user->addBonus(-1 * $partical_payment * ($this->page->lang == 'en' ? $this->VARS['usdRate'] : 1), 'Списание бонусов при частичной оплате заказ #' . $this->basket->id, $this->basket->id);
                                        
                                        $this->basket->basketSum += $this->basket->user_basket_payment_partical;
                                        $this->basket->user_basket_payment_partical = 0;
                                        
                                        // бонусов достаточно для полной оплаты
                                        if ($partical_payment >= ($this->basket->basketSum + $deliveryCost))
                                        {
                                            $this->basket->basketChange(array(
                                                'user_basket_payment_type'     => 'ls', 
                                                'user_basket_payment_partical' => ($this->basket->basketSum + $deliveryCost) * ($this->page->lang == 'en' ? $this->VARS['usdRate'] : 1)));
                                                
                                            // если это не обмен помечаем заказ как оплаченный
                                            if (empty($exchengedBonuses))
                                                $this->basket->basketChange(array('user_basket_payment_confirm' => 'true'));
                                        }
                                        else 
                                        {
                                            $this->basket->basketChange(array(
                                                'user_basket_payment_partical' => $partical_payment * ($this->page->lang == 'en' ? $this->VARS['usdRate'] : 1),
                                                'user_basket_payment_confirm'  => 'false'));
                                        }
                                    }
                                }
                            }
                            /**
                             * end ЧАСТИНАЯ ОПЛАТА ЗАКАЗА БОНУСАМИ
                             */
                             
                            if ($_POST['data']['lichnyy_schet'])
                            {
                                $ls_payment = min($this->basket->basketSum - $partical_payment + $this->basket->user_basket_delivery_cost, $this->user->balance['total']);
                                
                                try
                                {
                                    $sth = App::db()->prepare("INSERT IGNORE INTO `printshop_payments_data` 
                                                            (`user_id`, `type`) 
                                                          VALUES 
                                                            (:user, :type) 
                                                          ON DUPLICATE KEY 
                                                            UPDATE `use_date` = NOW(), id = LAST_INSERT_ID(id)");
                                    
                                    $sth->execute(array('user' => $this->user->id, 'type' => 'bonus'));
                                
                                    $id = App::db()->lastInsertId();
                                
                                    // 1. списываем деньги со счёта
                                    $pid = $this->user->pay(-1 * $ls_payment, 'Оплата заказа с личного счёта', 'order', 1, 1, $this->basket->id, $id);
                                    
                                    $this->basket->log('lichnyy_schet', $pid);
                                    
                                    // +% за вывод в бонусы (всем кроме партнёров)
                                    $ls_payment += round($ls_payment / 100 * $bppercent);
                                    
                                    // 2. выводим деньги в бонусы
                                    $this->basket->user->addBonus($ls_payment, 'Вывод средств с личного счёта', $this->basket->id, 1, $this->user->id);
                                    
                                    // 3. Засчитываем их в заказе
                                    $ls_payment = min($this->basket->basketSum + $this->basket->user_basket_delivery_cost, $ls_payment);
                                    
                                    $this->basket->user_basket_payment_partical += $ls_payment;
                                    
                                    $this->basket->user->addBonus(-1 * $ls_payment, 'Частичная оплата бонусами заказа #' . $this->basket->id, $this->basket->id);
                                    
                                    if ($ls_payment > 0 && $ls_payment >= $this->basket->basketSum + $this->basket->user_basket_delivery_cost)
                                    {
                                        $this->basket->user_basket_payment_type = 'ls';
                                        $this->basket->user_basket_payment_confirm = 'true';
                                        
                                        if ($this->basket->user_basket_status == 'ordered') {
                                            $this->basket->user_basket_status = 'accepted';
                                        }
                                        
                                        $this->basket->user_basket_payment_date = NOW;
                                    }
                                    
                                    $this->basket->save();
                                    
                                   
                                }
                                catch (PDOException $e) { printr($e->getMessage(), 1); }
                            }
                            
                            /**
                             * Если пользователь оформляет обмен
                             */
                            if ($this->basket->logs['set_mark'])
                            {
                                $this->user->addBonus(-1 * min($exchengedBonuses, $this->basket->basketSum + $deliveryCost), 'Оплата заказа обменными бонусами', $this->basket->id, -1);
                            }
        
                            
                            if (!$_POST['ID']) 
                            {
                                $this->basket->saveBasket();
                                /*
                                if (!empty($roulette_id))
                                {
                                    App::db()->query("UPDATE `roulette` SET `basket_id` = '" . $this->basket->id . "' WHERE `id` = '{$roulette_id}' LIMIT 1");
                                    
                                    $comment = 'Выигрыш в рулетку';
                                    
                                    if ($_COOKIE['roulette_prize'] == 0) 
                                        $comment .= '. Бесплатная футболка из SALE';
                                    elseif ($_COOKIE['roulette_prize'] == 1) 
                                        $comment .= '. Бонусы 250 р.';
                                    elseif ($_COOKIE['roulette_prize'] == 2) 
                                        $comment .= '. Бонусы 123 р.';
                                    
                                    $this->basket->log('user_comment', $comment, $this->basket->user_id);
                                    
                                    setcookie('roulette_prize', '', (time() - 2592000), "/", AppDomain);
                                }
                                */
                                
                                if ($_SESSION['sticerOneList']) {
                                    //$this->basket->log('printer_comment', $_SESSION['sticerOneList'] ? 'Покупатель заказал резку' : 'Не резать!');
                                    $this->basket->log('printer_comment', 'Не резать!');
                                    unset($_SESSION['sticerOneList']);
                                }
                                
                                if (isset($_COOKIE['mopromo_click_id'])) 
                                {
                                    $r = file_get_contents('http://tds.mopromo.ru/tds_notify.php?status=0&click_id=' . $_COOKIE['mopromo_click_id'] . '&summ=' . $this->basket->basketSum);
                                    
                                    $this->basket->log('partner', 'mopromo', $r);
                                }
                            }
                            
                            // сохраняем комментарий к заказу
                            if (!empty($_POST['data']['comment_text'])) 
                            {
                                // начинаем новый трэд обсуждения
                                $sth = App::db()->query("INSERT INTO `messages` SET `text` = '" . addslashes(trim($_POST['data']['comment_text'])) . "'");
                
                                $mid = App::db()->lastInsertId();
                
                                $sth = App::db()->query("INSERT INTO `messages_adressats`
                                             SET
                                                `mess_id`      = '" . $mid . "',
                                                `user_from_id` = '" . $this->user->id . "',
                                                `user_to_id`   = '" . 10 . "'");
                
                                // начинаем трэд обсуждения
                                $this->basket->basketChange(array('user_basket_massage_id' => $mid));
                                
                                // чит для скайзелика, чтобы его каментарий шёл сразу печатнику
                                if ($this->user->id == 190169) {
                                    $this->basket->log('printer_comment', $_POST['data']['comment_text'], $this->user->id);
                                }
                            }
        
                            if (!$_POST['ID']) {
                                foreach ($this->basket->basketGoods as $g) {
                                    if ($g['good_status'] == 'customize') {
                                        header('location: /' . $this->page->module . '/confirm-customize/' . $this->basket->id . '/');
                                        exit();
                                    }
                                }
                                
                                header('location: /' . $this->page->module . '/confirm/' . $this->basket->id . '/');
                            } else
                                header('location: /orderhistory/' . $this->basket->id . '/');
                                
                            exit();
                        }
                        else 
                        {
                            foreach ($_POST as $k => $v) {
                                $this->view->setVar($k, $v);
                            }
                            
                            $this->view->setVar('ERROR', array('text' => implode('<br />', $error)));    
                        }
                    }
        
                    
                break;
                
                /**
                 * подтверждение заказа
                 */
                case 'confirm':
                case 'confirm-customize':
                    
                    $this->page->tpl = 'order/v3/confirm.tpl';
                    
                    try
                    {
                        $this->page->import(array(
                            '/js/calendar.js',
                            '/js/p/order.v3.js',
                            '/css/order.styles_2013.css'
                        ));
                    }
                    catch (Exception $e)
                    {
                        printr($e->getMessage());
                    }
                    
        
                    if (!empty($this->page->reqUrl[2]))
                    {
                        $id = intval($this->page->reqUrl[2]);
                        
                        try
                        {
                            $B = new basket($this->page->reqUrl[2]);
                            
                            if ($B->user_id == $this->user->id || $this->user->meta->mjteam)
                            {
                                $B->getaddress();
                                
                                $B->phone_2 = substr($B->address['phone'], -4);
                                $B->phone_1 = substr($B->address['phone'], 0, strlen($B->address['phone']) - 4);
                                
                                if (!empty($B->address['delivery_point']))
                                {
                                    try
                                    {
                                        $dp = new deliveryPoint($B->address['delivery_point']);
                                        $B->delivery_point_address = $dp->address;
                                        $B->delivery_point_schema = $dp->schema;
                                    }
                                    catch (Exception $e) {}
                                }
                                
                                if (($B->user_basket_status == 'ordered' || $B->user_basket_status == 'accepted'))
                                {
                                    foreach ($B->basketGoods as $g)
                                    {
                                        $g['style_name'] = addslashes($g['style_name']);
                                        
                                        $totalPrice += $g['tprice'];
                                        $goods[] = $g;
                                        $sc++;
                                        
                                        if ($g['good_status'] != 'customize' && $g['good_user'] != $B->user_id)
                                        {
                                            if (!$author_thanks[$g['good_user']]) {
                                                $author_thanks[$g['good_user']] = array(
                                                    'user_login' => $g['user_login'],
                                                    'user_city_name' => $g['user_city_name'],
                                                    'user_avatar' => $g['user_avatar'],
                                                    'user_avatar_medium' => $g['user_avatar_medium'],
                                                    'good_id' => $g['good_id'],
                                                );
                                            }
                                            
                                            $author_thanks[$g['good_user']]['author_payment'] += $g['author_payment'];
                                        }
                                    }
                                    
                                    foreach ($B->basketGifts as $g)
                                    {
                                        $totalPrice += $g['tprice'];
                                        $gifts[] = $g;
                                        $gsc++;
                                    }
                                    
                                    if ($this->page->lang == 'en')
                                    {
                                        $B->user_basket_payment_partical = round($B->user_basket_payment_partical / $this->VARS['usdRate'], 1);
                                        $B->user_basket_delivery_cost = round($B->user_basket_delivery_cost / $this->VARS['usdRate'], 1);
                                    }
                                    
                                    $B->PAYMENT_FORM = 'payment_forms/' . $B->user_basket_payment_type . '.quick.tpl';
                                    
                                    $this->view->setVar('order', $B->info);
                                    $this->view->setVar('goods', $goods);
                                    $this->view->setVar('gifts', $gifts);
                                    
                                    // для гугл-аналитикса выдаём список покупок один раз
                                    if (!$_COOKIE[$this->basket->basketName]) {
                                        $this->view->setVar('GA_T_I', $goods);
                                    }
                                    
                                    // форма оплаты
                                    if ($B->user_basket_status != 'canceled' && !empty($B->user_basket_payment_type)) 
                                    {
                                        if ($B->user_basket_payment_type != 'ls' && $B->user_basket_payment_type != 'cash')
                                            $this->view->setVar('PAYMENT_FORM', 'payment_forms/' . $B->user_basket_payment_type . '.tpl');
                                        
                                        $totalPrice += $B->user_basket_delivery_cost + intval($this->VARS[$B->user_basket_payment_type . '_margin']) - $B->user_basket_payment_partical;
                
                                        $this->view->setVar('basketNum',      $B->id);
                                        $this->view->setVar('basketDelivery', basket::$deliveryTypes[$B->user_basket_delivery_type]['title']);
                                        $this->view->setVar('basketPayment',  basket::$paymentTypes[$B->user_basket_payment_type]['title']);
                                        $this->view->setVar('basketCity',     $B->address['city']);
                                        $this->view->setVar('basketKray',     cityId2kray($B->address['city']));
                                        $this->view->setVar('basketCountry',  $B->address['country']);
                                        $this->view->setVar('custFIO',        $B->address['name']);
                                        $this->view->setVar('custTEL',        substr($B->address['phone'], 1));
                                        $this->view->setVar('author_thanks',  $author_thanks);
                                        
                                        $this->view->setVar('basketDate',     NOW);
                                        $this->view->setVar('deliveryCost',   $B->user_basket_delivery_cost);
                                        $this->view->setVar('totalPrice',     $totalPrice); // todo
                                        $this->view->setVar('basket_sum',     $totalPrice); // todo
                                        
                                        $percent    = $this->user->buyerLevel2discount();
                                        $bonusback  = ceil(($totalPrice - $B->user_basket_delivery_cost + $B->user_basket_payment_partical) / 100 * $percent);
                                        
                                        $this->view->setVar('bonusBack',     $bonusback); // todo
                                    }
                
                                    // подписка на смс-информирование
                                    $this->view->setVar('sms_info_checked', ($this->user->meta->order_sms_info === '0') ? FALSE : TRUE);
                                    
                                    // переписка с администратором
                                    $sth = App::db()->query("SELECT m.*, ma.`user_from_id`, ma.`user_to_id`, ma.`visible_from`, ma.`visible_to`
                                                FROM `messages` AS m, `messages_adressats` AS ma
                                                WHERE 
                                                         m.`id` = ma.`mess_id`
                                                    AND ((ma.`user_from_id` = '" . $this->user->id . "' AND ma.`user_to_id` = '10') OR (ma.`user_to_id` = '" . $this->user->id . "' AND ma.`user_from_id` = '10'))
                                                ORDER BY m.`send_date` DESC");
                                                
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
                                    
                                    $paymentTypes = basket::$paymentTypes;
                                    
                                    unset($paymentTypes['ls']);
                                    unset($paymentTypes['cashon']);
                                    
                                    if (in_array($B->user_basket_delivery_type, array('post', 'dpd')))
                                        unset($paymentTypes['cash']);
                                    
                                    $this->view->setVar('paymentTypes', $paymentTypes);
                                }
                                else
                                {
                                    header('location:/orderhistory/' . $B->id . '/');
                                    exit();
                                }
                            }
                            else
                                throw new Exception('no access for this order', 1);
                        }
                        catch (Exception $e)
                        {
                            printr($e->getMessage());
                        }
                    }
                    else 
                    {
                        header('location: /orderhistory/');
                        exit();
                    }
                break;  
                
                
                /**
                 * РАСПЕЧАТКА ЗАКАЗА
                 */
                case 'print':
        
                    try
                    {
                        $this->page->import(array(
                            '/js/calendar.js',
                            '/js/p/order.v3.js',
                            '/css/order.styles_2013.css'
                        ));
                    }
                    catch (Exception $e)
                    {
                        printr($e->getMessage());
                    }
                    
                    $this->page->tpl = 'about/about.print.tpl';
                    $this->page->index_tpl = 'index.print.tpl';
                
                    if (isset($this->page->reqUrl[2]) && !empty($this->page->reqUrl[2]))
                    {
                        $id = intval($this->page->reqUrl[2]);
                        
                        $q = "SELECT
                                ub.`user_basket_id`,
                                ub.`user_basket_status`,
                                ub.`user_basket_date`,
                                ub.`user_basket_payment_type`,
                                ub.`user_basket_delivery_type`,
                                ub.`user_basket_delivery_cost`,
                                ub.`user_basket_payment_partical`,
                                u.`user_name`,
                                u.`user_email`,
                                u.`user_metro`,
                                u.`user_postal_code`,
                                u.`user_postal_address`,
                                u.`user_city`,
                                ub.`user_basket_date`,
                                ubl.`result` AS self_delivery_point,
                                uba.`city`
                            FROM 
                                `users` AS u, 
                                `user_baskets` AS ub
                                    LEFT JOIN `user_basket_address` uba ON uba.`id` = ub.`user_basket_delivery_address`
                                    LEFT JOIN `user_basket_log` ubl ON ubl.`basket_id` = ub.`user_basket_id` AND ubl.`result` LIKE 'Самовывоз из%'
                            WHERE 
                                    ub.`user_basket_id` = '" . $id . "'
                                AND ub.`user_id` = '" . $this->user->id . "'
                                AND u.`user_id`  = ub.`user_id`
                            GROUP BY ub.`user_basket_id`
                            LIMIT 1";
                            
                        $r = App::db()->query($q);
                        
                        if ($b = $r->fetch())
                        {
                            $basket = new basket($id);
                            
                            $totalPrice             = 0;
                            $totalPriceWithoutDisc  = 0;
                            $totalDiscount          = 0;
                            
                            foreach ($this->basket->basketGoods as $g)
                            {
                                $g['styleName'] = $g['style_name'];
                                
                                // превью для гаджетов
                                if (styleCategory::$BASECATSid[$g['cat_parent']]) {
                                    $g['category'] = styleCategory::$BASECATSid[$g['cat_parent']];
                                }
                                
                                // превью для семейных наклеек
                                if ($g['style_id'] == 331) {
                                    $foo = App::db()->query("SELECT p.`picture_path` FROM `good_pictures` gp, `pictures` p WHERE gp.`good_id` = '" . $g['good_id'] . "' AND gp.`pic_name` = 'preview' AND gp.`pic_id` = p.`picture_id` LIMIT 1")->fetch();
                                    $g['imagePath'] = $foo['picture_path'];
                                }
                                
                                $totalPriceWithoutDisc += $g['price'] * $g['quantity'];
                                $totalPrice += $g['price'] = $g['priceTotal'] = round($g['price'] * (1 - $g['discount'] / 100)) * $g['quantity'];
                                $totalDiscount         += round($g['price'] * $g['quantity']) - $g['price'];
                                $g['discount'] = round($g['discount']);
                                
                                $b['goods'][] = $g;
                            }
                            
                            foreach ($this->basket->basketGifts as $g)
                            {
                                $totalPrice += $g['priceTotal'];
                                $b['gifts'][] = $g;
                            }
                            
                            $b['user_city'] = cityId2name($b['user_city']);
                            
                            $b['total']['deliveryPrice'] = intval($b['user_basket_delivery_cost']);
                            $b['total']['totalPrice']    = ($totalPrice + $b['user_basket_delivery_cost']);
                            //$b['total']['totalPriceWithoutDisc'] = $totalPriceWithoutDisc;
                            $b['total']['totalDiscount'] = $totalDiscount;
                            $b['total']['particalPay']   = $b['user_basket_payment_partical'];
                            
                            if (!empty($b['self_delivery_point']))
                            {
                                $st = strpos($b['self_delivery_point'], '(') + 1;
                                $en = strpos($b['self_delivery_point'], ')');
                                
                                $b['self_delivery_point'] = substr($b['self_delivery_point'], $st, $en - $st);
                                $b['city_name'] = cityId2name($b['city']);
                            }
                            
                            $this->view->setVar('BASKETS', array(0 => $b));
        
                            $this->view->setVar('contactPhone1', $this->VARS['contactPhone1']);
                            $this->view->setVar('TITLE', 'Распечатать заказ');
        
                        } else {
                            header("location: /basket/");
                            exit();
                        }
                    } else {
                        header("location: /basket/");
                        exit();
                    }
            
                break;
            }

            $this->view->generate($this->page->index_tpl ? $this->page->index_tpl : 'index.tpl');
        }
    }