<?php
    namespace application\models;
    
    use \smashEngine\core\App AS App; 
    use \smashEngine\core\exception\appException;
    
    use \DateTime;
    use \PDO;
    use \Exception;
    
    use \S3Thumb AS S3Thumb;
    use \sms;
        
    /**
     * 
     */
    class basket extends \smashEngine\core\Model 
    {
        public $id = 0;
        public $info = array(); // Массив, содержащий всю информацию о корзине
        
        /**
         * @var имя таблицы в БД для хранения экземпляров класса
         */ 
        public static $dbtable = 'basket';
        
        /**
         * @var имя таблицы в БД для хранения логов заказа
         */ 
        public static $dbtable_log = 'basket__log';
        
        public $goodsCount = 0;
        public $giftsCount = 0;

        public $MJbasket      = array();
        
        /**
         * @var валюта
         */
        public $currency = 'rub';
        
        /**
         * @var признак уменьшать ли превьюхи позиций при выводе
         */
        public $resizePreview = true;
        
        /**
         * @var Домен на котором работает корзина 
         */ 
        public $basketDomain = '.maryjane.ru';
        
        /**
         * @var диапазоны скидочного количества 
         */ 
        public static $d_ranges = [
            0 => 1,
            1 => 2,
            2 => 6, 
            3 => 11, 
            4 => 21, 
            5 => 51, 
            6 => 101,
        ];
        
        static $discountTypes = [
            0 => 'Cкидка отсутствует',
            1 => 'Скидка на носитель',
            2 => 'Персональная скидка',
            3 => 'Скидка на количество',
            4 => 'Скидка на день рождения',
            5 => 'Скидка на способ оплаты',
            6 => 'Скидка на способ доставки',
            7 => 'Скидка по дисконтной карте',
            8 => 'Скидка на дизайн',
            9 => 'Скидка в рулетку',
            10 => 'Скидка по промо-ссылке',
            11 => 'Скидка по географическому положению',
        ];
            
        /**
         * @var array массив с адресом доставки
         */
        //public $address = array();
        
        /**
         * @var array возможные причины аннулирвоания заказа
         */
        static $cancelReason = array(
            0 => array('domain' => 'MJbasket', 'tpl' => 49,  'caption' => 'Отказ пользователя'),
            2 => array('domain' => 'MJbasket', 'tpl' => 66,  'caption' => 'Нет Оплаты'),
            3 => array('domain' => 'MJbasket', 'tpl' => 67,  'caption' => 'Отсутствует товар на складе'),
            5 => array('domain' => 'MJbasket', 'tpl' => 266, 'caption' => 'Другой заказ'),
            
            // прчины анулировани для скинов
            10 => array('domain' => 'ASbasket', 'tpl' => 49,  'caption' => 'Хочу отредактировать заказ'),
            11 => array('domain' => 'ASbasket', 'tpl' => 49,  'caption' => 'Не устраивают сроки доставки'),
            12 => array('domain' => 'ASbasket', 'tpl' => 49,  'caption' => 'Не устраивает стоимость доставки'),
            13 => array('domain' => 'ASbasket', 'tpl' => 49,  'caption' => 'Не хочу делать предоплату'),
            14 => array('domain' => 'ASbasket', 'tpl' => 49,  'caption' => 'Хочу получить 10% скидку за предоплату'),
            15 => array('domain' => 'ASbasket', 'tpl' => 49,  'caption' => 'Нашел дешевле'),
            16 => array('domain' => 'ASbasket', 'tpl' => 49,  'caption' => 'Все хорошо, просто больше нет потребности в покупке'),
            17 => array('domain' => 'ASbasket', 'tpl' => 49,  'caption' => 'Неверный номер'),
            18 => array('domain' => 'ASbasket', 'tpl' => 49,  'caption' => 'Отсутствует оплата'),
        
            4 => array('domain' => 'all', 'tpl' => 68,  'caption' => 'Недозвон'),   
            6 => array('domain' => 'all', 'tpl' => 98,  'caption' => 'Тестовый заказ'),
            
            20 => array('domain' => 'all', 'caption' => 'Оптовый заказ'),
        );
        
        public static $phoneCallTypes = array(
            0  => 'ещё не звонили',
            1  => '&nbsp;',
            2  => 'недоступен/отключен',
            3  => 'занято/не берет трубку',
            5  => 'перезвонить',
            8  => 'отправлено письмо',
            7  => 'доставить',
        );
        
        /**
         * Причины обмена заказа
         */
        static $exchangeReasons = array(
            'exchange' => array('title' => 'Замена размера'),
            'exchange_malo' => array('title' => 'Мало'),
            'exchange_veliko' => array('title' => 'Велико'),
            'exchange_brak' => array('title' => 'Брак'),
        );
        
        /**
         * @var статусы заказов
         */
        static $orderStatus = array(
            'active'    => 'Не сформирован',
            'ordered'   => 'Заказан',
            'accepted'  => 'Оплачен',
            'delivered' => 'Доставлен',
            'waiting'   => 'Ожидается оплата',
            'prepared'  => 'Подготовлен',
            'canceled'  => 'Отменён',
            'returned'  => 'Возврат',
        );
        
        /**
         * @var способы доставки
         */     
        public static $deliveryTypes = array (
            'user'        => array('title' => 'Самовывоз', 'courier' => 131),
            'deliveryboy' => array('title' => 'Курьер', 'courier' => 131),
            'deliveryboy_vip' => array('title' => 'Курьер день-в-день', 'courier' => 131),
            'metro'       => array('title' => 'В метро', 'courier' => 131),
            'post'        => array('title' => 'Почта России', 'courier' => 131),
            'onStation'   => array('title' => 'На вокзал', 'courier' => 131),
            'ems'         => array('title' => 'EMS Почта России', 'courier' => 131),
            'baltick'     => array('title' => 'Балтийский курьер', 'courier' => 131),
            'dpd'         => array('title' => 'DPD курьер', 'courier' => 131),
            'dpd_self'    => array('title' => 'DPD самовывоз', 'courier' => 131),
            'IMlog'       => array('title' => 'IM-Logistics курьер', 'courier' => 131),
            'IMlog_self'  => array('title' => 'IM-Logistics самовывоз', 'courier' => 131),
            'sdek'        => array('title' => 'СДЕК', 'courier' => 131),
        );

        
        /**
         * @var способы оплаты
         */
        public static $paymentTypes = array(
            'cash'               => array('title' => 'Наличные', 'faq' => 24, 'color' => '#B3E5E5', 'publish' => true),
            'cash2card'          => array('title' => 'Наличные на карту', 'faq' => 24, 'color' => '#3cbd34', 'publish' => true),
            'creditcard'         => array('title' => 'Кредитная карта', 'faq' => 88, 'color' => '#E5E5B3', 'publish' => true, 'tpl' => 'payment_forms/creditcard.quick.tpl'),
            'creditcard_onplace' => array('title' => 'Кредитная карта (на месте доставки)', 'faq' => 172, 'color' => '#848484', 'publish' => true),
            'webmoney'           => array('title' => 'Webmoney', 'faq' => 85, 'color' => '#E5B3E5', 'publish' => true, 'tpl' => 'payment_forms/webmoney.quick.tpl'),
            'yamoney'            => array('title' => 'Yandex деньги', 'faq' => 86, 'color' => '#B3B3E5', 'publish' => true, 'tpl' => 'payment_forms/yamoney.quick.tpl'),
            'sberbank'           => array('title' => 'Банковский перевод', 'faq' => 87, 'color' => '#E5B3B3', 'publish' => true),
            'ls'                 => array('title' => 'Личный счет', 'faq' => 83, 'color' => '#B3E5B3', 'publish' => true),
            'qiwi'               => array('title' => 'QIWI', 'faq' => 84, 'color' => '#FFEB0F', 'publish' => true, 'tpl' => 'payment_forms/qiwi.quick.tpl'),
            'cashon'             => array('title' => 'Наложенный платёж', 'faq' => 139, 'color' => '#3f55c7', 'publish' => true),
            'style'              => array('title' => 'Стиль', 'publish' => false),
        );
        
        /**
         * @var integer сумма заказа, при которой увеличивается стоимость доставки DPD на 200 руб. 
         */
        public static $dpdMarginLimit = 3500;
        
        /**
         * Новыый объект пользовательской корзины
         * @param int $id номер корзины
         * @param user $user текущий пользователь (владелец корзины)
         */
        function __construct($id, user $user = null) 
        {
            if (!empty($id)) {
                $this->id = (int) $id;
                $this->getInfo();
            }
            
            if ($user) {
                $this->user = $user;
            }
        }
        
        
        function setBasket($id) 
        {
            $this->id = (int) $id;
            $this->getInfo();
        }
        
        function setBasketDomain($domain)
        {
            $this->basketDomain = $domain;
            
            if ($domain == '.allskins.ru')
                $this->user_basket_domain = 'ASbasket';
        }
        
        /**
         * Создаём новую корзину
         * @return 
         * @param object $user_id[optional]
         */
        function addBasket($user_id = 0)
        {
            $this->user_id = intval($this->user_id);
            $this->user_basket_date = NOW;
            $this->user_basket_domain = ($this->basketDomain == '.maryjane.ru' ? 'MJbasket' : 'ASbasket');
            
            $this->save();
            
            if ($this->user) {
                $this->user->setSessionValue(['user_basket_id' => $this->id]);
            } 
            
            $this->getInfo();
        }
        
        /**
         * Создаём новую корзину
         * @return 
         * @param array
         */
        public static function create($data)
        {
            $r = App::db()->query("INSERT INTO `user_baskets` 
                              SET 
                                `user_id` = '" . intval($data['user_id']) . "',
                                `user_basket_date` = '" .  NOW . "'"
                                . (!empty($data['user_basket_status']) ?", `user_basket_status` = '" . addslashes($data['user_basket_status']) . "'" : '')
                                . (!empty($data['user_basket_delivery_type']) ?", `user_basket_delivery_type` = '" . addslashes($data['user_basket_delivery_type']) . "'" : ''));
                                
            return new self(App::db()->lastInsertId());
        }
        
        /**
         * Получить всю информацию о корзине из базы
         * @return 
         */
        function getInfo() 
        {
            $r = App::db()->query("SELECT * FROM `user_baskets` WHERE `user_basket_id` = '" . $this->id . "' LIMIT 1");

            if ($this->info = $r->fetch()) 
            {
                $this->user_basket_date_rus = datefromdb2textdate($this->user_basket_date, 3);
                
                if ($this->user_basket_delivery_type)
                    $this->user_basket_delivery_type_rus = self::$deliveryTypes[$this->user_basket_delivery_type]['title'];
                    
                if ($this->user_basket_payment_type)        
                    $this->user_basket_payment_type_rus = self::$paymentTypes[$this->user_basket_payment_type]['title'];
                
                return $this->info;
            } else {
                if (!empty($this->id)) {
                    throw new Exception('Basket ' . $this->id . ' not found', 1);
                }
                
                return false;
            }
        }
        
        /**
         * Изменить поля корзины
         * @return 
         * @param object $data - ассоциативный массив полей и их значений
         */
        function basketChange($data) 
        {
            if (empty($this->id))
                $this->addBasket();
                
            $out = array();
        
            foreach ($data as $f => $v) {
                if (!empty($f)) 
                    $out[] = "`$f` = '$v'";
            }

            // если заказ оформлен и меняется тип доставки или тип оплаты, логируем это
            if ($this->user_basket_status != 'active')
            {
                if ($data['user_basket_delivery_type'] && $this->user_basket_delivery_type != $data['user_basket_delivery_type'])
                    $this->log('user_changes', 'delivery_type', $this->user_basket_delivery_type);
                
                if ($data['user_basket_payment_type'] && $this->user_basket_payment_type != $data['user_basket_payment_type'])
                    $this->log('user_changes', 'payment_type', $this->user_basket_payment_type);
            }
            
            $r = App::db()->query("UPDATE `user_baskets` SET " . implode(', ', $out) . " WHERE `user_basket_id` = '" . $this->id . "' LIMIT 1");
            
            if ($r->rowCount() > 0)
            {
                foreach ($data as $f => $v) {
                    if (!empty($f)) 
                        $this->info[$f] = $v;
                }
            }
            
            return true;
        }
        
        /**
         * Уничтожить заказ полностью
         */
        public function delete()
        {
            App::db()->query("DELETE FROM `user_baskets` WHERE `user_basket_id` = '" . $this->id . "' LIMIT 1");
            App::db()->query("DELETE FROM `user_basket_goods` WHERE `user_basket_id` = '" . $this->id . "'");
            App::db()->query("DELETE FROM `user_basket_log` WHERE `basket_id` = '" . $this->id . "'");
            
            return true;
        }
        
        /**
         * Сохранить текущий экземпляр объекта в базу
         */
        public function save()
        {
            foreach ($this->info as $k => $v) {
                if (!is_array($v) && !is_object($v)) {
                    $rows[$k] = "`$k` = '" . addslashes(trim($v)) . "'";
                }
            }

            // вырезаем все поля которых нет в схеме таблицы
            $r = App::db()->query(sprintf("SHOW COLUMNS FROM `%s`", self::$dbtable));
            
            foreach ($r->fetchAll() AS $f) {
                $fields[$f['Field']] = $f['Field'];
            }
            
            $rows = array_intersect_key($rows, $fields);
            // end вырезаем все поля которых нет в схеме таблицы
            
            // редактирование
            if ($this->id > 0)
            {
                App::db()->query(sprintf("UPDATE `%s` SET %s WHERE `user_basket_id` = '%d' LIMIT 1", self::$dbtable, implode(',', $rows), $this->id));
            }
            // создание
            else
            {
                App::db()->query(sprintf("INSERT INTO `%s` SET %s", self::$dbtable, implode(',', $rows)));
                $this->id = App::db()->lastInsertId();
            }
        }
        
        /**
         * @return string короткий номер заказ, 4 последние цифры телефона заказчика
         */
        function getShortNumber()
        {
            $sn = substr(str_replace(array('-', '(', ')', ' '), '', $this->address['phone']), -4);
            
            if (empty($sn))
                $sn = $this->id;
            
            $this->info['shortNumber'] = $sn;
            
            return $this->info['shortNumber'];
        }
        
        /**
         * Изменение в "куковой" корзине
         */
        function MJbasketChange($data, $type = 'address', $id) {
            
            // сохранение в куки (новый заказ)
            if ($this->user_basket_status == 'active')
            {
                foreach ($data as $f => $v) {
                    $this->MJbasket[$type][$f] = $v;
                }
            }
            // сохранение в базу (редактирование заказа)
            else 
            {
                unset($data['status']);
                unset($data['comment']);
                unset($data['city_name']);
                
                $data['hash'] = substr(md5(implode('', array(
                    $data['region'],
                    $data['name'],
                    $data['postal_code'],
                    $data['country'],
                    $data['kray'],
                    $data['city'],
                    $data['raion'],
                    $data['address'],
                    $data['delivery_point'],
                    $data['metro'],
                    $data['phone'],
                    $data['skype']
                ))), 0, 30);
                
                foreach ($data as $k => &$df) {
                    $df = "uba.`$k` = '$df'";
                }

                App::db()->query(sprintf("UPDATE IGNORE `user_baskets` ub, `user_basket_address` uba SET %s WHERE ub.`user_basket_id` = '{$id}' AND ub.`user_basket_delivery_address` = uba.`id`", implode(', ', $data)));
            }
            
            return;
        }
        
        function setAddress($data)
        {
            unset($data['status']);
            unset($data['comment']);
            unset($data['city_name']);
                            
            // Проверка на уникальность адреса
            // построение хеша адреса
            $fs   = implode("`, `", array_keys($data));
            $vs   = implode("','", $data);

            $hash = substr(md5(implode('', array(
                $this->user_id,
                $data['region'],
                $data['name'],
                $data['postal_code'],
                $data['country'],
                $data['kray'],
                $data['city'],
                $data['raion'],
                $data['address'],
                $data['delivery_point'],
                $data['metro'],
                $data['phone'],
                $data['skype']
            ))), 0, 30);
            
            $r = App::db()->query("INSERT IGNORE INTO `user_basket_address` (`$fs`, `user_id`, `hash`) VALUES ('$vs', '" . $this->user_id . "', '" . $hash . "') ON DUPLICATE KEY UPDATE `order_date` = '" . NOW . "'");
                
            $this->basketChange(array(
                'user_basket_delivery_address' => App::db()->lastInsertId(),
            ));
        }
        
        /**
         * @return string полный адрес доставки одной строкой
         */
        public function getFullAddress()
        {
            $this->info['fullAddress'] = $this->address['name'] . ', ';
        
            if ($this->address['region'] == 'moscow')
            {
                $this->info['fullAddress'] .= (!empty($this->address['metro']) ? 'Метро: ' . $this->address['metro'] . ', ' : '') . $this->address['address'];
            }
            elseif ($this->address['region'] == 'nearmoscow')
            {
                $this->info['fullAddress'] .= regionId2regionName($this->address['region']) . ', ' . (!empty($this->address['city']) ? ', ' . $this->address['city'] : '') . (!empty($this->address['postal_code']) ? ', ' . $this->address['postal_code'] : '') . ', ' . $this->address['address'];
            }
            elseif ($this->address['region'] == 'russia')
            {
                $this->info['fullAddress'] .= $this->address['country'] . (!empty($this->address['city']) ? ', ' . $this->address['city'] : '') . (!empty($this->address['postal_code']) ? ', ' . $this->address['postal_code'] : '') . ', ' . $this->address['address'];
            }
            elseif ($this->address['region'] == 'country')
            {
                $this->info['fullAddress'] .= $this->address['country'] . (!empty($this->address['city']) ? ', ' . $this->address['city'] : '') . (!empty($this->address['postal_code']) ? ', ' . $this->address['postal_code'] : '') . ', ' . $this->address['address'];
            }
            
            return $this->info['fullAddress'];
        }
        
        /**
         * Начисления партнёрам по информерам или автивированным промо-кодам
         * Устаревший метод
         * @return 
         */
        function registerInformersPay() {
        
            if ($this->user_basket_payment_type != 'ls')
            {
                $r = App::db()->query("SELECT ial.`informer_action_id`, i.`user_id`, i.`informer_type`, i.`informer_id`
                                  FROM `informer_action_log` AS ial, `informers` AS i
                                  WHERE ial.`informer_action_type` = 'order' AND ial.`informer_action_target` = '" . $this->id . "' AND ial.`informer_id` = i.`informer_id`");
                
                // если переход по банеру был
                if ($r->rowCount() > 0) 
                {
                    $inf = $r->fetch();
            
                    // если банер не принадлежит хозяину корзины
                    if ($inf['user_id'] != $this->user_id)
                    {
                        $percent    = getVariableValue($this->user_basket_domain == 'MJbasket' ? 'paybackPercent' : 'paybackPercent_as');
                        $basket_sum = $this->getBasketSum();
                        $pay        = round(($basket_sum / 100) * $percent);
                
                        if ($pay > 0)
                        {
                            $pid = pay2User($inf['user_id'], 0, $this->id, "Выплата $percent% автору информера #" . $inf['informer_id'] . " за заказ " . $this->id, $pay, 'promo');
                            
                            $this->basketChange(array('user_basket_informer_pay_id' => $pid));
                            
                            $i = new informer($inf['informer_id']);
                            $i->informer_last_delivered = NOW;
                            $i->save();
                        }
                    }
                }
            }
        }

        /**
         * Начисления партнёрам по информерам или автивированным промо-кодам v2
         * @return 
         */
        function registerPartnersPay() {
        
            if ($this->user_basket_wholesale != -1) {
                return;
            }
        
            if ($this->logs['is_admin'] && !$this->logs['enable_partner_comission']) {
                return;
            }
        
            // смотрим был ли активирован сертификат в заказе
            if ($this->logs['activateDiscontCard']) 
            {
                $cert = new certificate($this->logs['activateDiscontCard'][0]['result']);
            }
   
            $customizePartnerBonus = getVariableValue('customizePartnerBonus');
   
            // проверяем был ли переход по промо-ссылке
            $sth = App::db()->query("SELECT ial.`informer_action_id`, i.`user_id`, i.`informer_type`, i.`informer_id`
                              FROM `informer_action_log` AS ial, `informers` AS i
                              WHERE 
                                    ial.`informer_action_type` = 'order' 
                                AND ial.`informer_action_target` = '" . $this->id . "' 
                                AND ial.`informer_id` = i.`informer_id`
                                AND i.`user_id` != '" . $this->user_id . "'
                              LIMIT 1");
            
            $inf = $sth->fetch();
            
            // определяем номер партнёра
            if ($cert) {
                $partner_id = $cert->certification_author;
            }
            
            if ($inf['user_id']) {
                $partner_id = $inf['user_id'];
            }
            
            // если партнёр сам делает заказ
            if ($this->user->user_partner_status == 1) {
                $partner_id = $this->user_id; 
            }
            
            if ($partner_id > 0) 
            {
                $P = new user($partner_id);
                
                // дефолтный процент отчисления если для выбранной категории он не указан
                $def_percent = getVariableValue($this->user_basket_domain == 'MJbasket' ? 'paybackPercent' : 'paybackPercent_as');
                $percents_base = json_decode(getVariableValue('partnersBaseRate'), 1);
                
                
                if ($personal_base = getVariableValue('partnersBaseRate_' . $partner_id)) {
                    $percents_base = json_decode($personal_base, 1);
                }
                
                $ssth = App::db()->prepare("UPDATE `user_basket_goods` SET `user_basket_good_partner_payment_id` = :payment_id WHERE `user_basket_good_id` = :id LIMIT 1");
        
                $basket_sum = $this->getBasketSum() + $this->user_basket_payment_partical - $this->user_basket_delivery_cost;
        
                foreach ($this->basketGoods as $g) 
                {
                    // если вдруг за эту позицию мы уже платили, то второй раз пропускаем
                    if ($g['user_basket_good_partner_payment_id'] > 0) {
                        continue;
                    }
                    
                    if (!$percent = $percents_base[$g['cat_parent'] == 1 ? $g['style_category'] : $g['cat_parent']]) { 
                        $percent = $def_percent;
                    }
                    
                    // Если был активирован сертификат партнёра, то вычитаем процент скидки по сертификату из отчисления
                    if ($cert->id > 0 && ($cert->user_id == $inf['user_id'] || !$inf)) {
                        $percent -= $cert->certification_value;
                    }
        
                    //if ($this->user_basket_payment_partical > 0) {
                        // Вычитаем из стоимости позиции часть отплаченную бонусами
                        // так как бонусов больше нет типа это отменяем
                        //$g['user_basket_good_total_price'] -= round($this->user_basket_payment_partical / 100 * ($g['user_basket_good_total_price'] * 100 / $basket_sum));
                    //}
                    
                    // если дизайн выкуплен у автора 
                    if ($g['good_buyout'] == 1) {
                        $percent += 10;
                    }
                    
                    $pay = round($g['user_basket_good_total_price'] / 100 * $percent);
                    
                    $percent_title = $percent . '%';
                    
                    // за самоделку партнёр дополнительно получает 100 рублей (при сумме заказа более 1000)
                    if ($g['good_status'] == 'customize' && $basket_sum >= 1000) {
                        $pay += $customizePartnerBonus;
                        $percent_title .= ' + ' . $customizePartnerBonus . 'р.';
                    }
                    
                    //if ($pay > 0)
                    //{
                        if ($cert)
                            $comment = "Выплата {$percent_title} автору купона #" . $cert->id . ' за заказ ' . $this->id;
                        elseif ($inf)
                            $comment = "Выплата {$percent_title} автору информера #" . $inf['informer_id'] . ' за заказ ' . $this->id;
                        else
                            $comment = "Выплата {$percent_title} партнёру за свой заказ " . $this->id;
                        
                        $P->pay($pay, $comment . ' (' . $g['user_basket_good_id'] . ')', 'promo', 0, 1, $this->id);;
                        //$pid = pay2User($partner_id, 0, $this->id, $comment . ' (' . $g['user_basket_good_id'] . ')', $pay, 'promo');
                        
                        $ssth->execute(array(
                            'payment_id' => $pid,
                            'id' => $g['user_basket_good_id'],
                        ));
                    //}
                }
                
                if ($inf['informer_id'] > 0)
                {    
                    $i = new informer($inf['informer_id']);
                    $i->informer_last_delivered = NOW;
                    $i->save();
                }
            }
        }
        
        /**
         * получить время максимального резерва заказа
         * @return int количество дней
         */
        public function maxReservTime()
        {
            if (($this->user_basket_delivery_type == 'user' && $this->user_basket_payment_type == 'cash') || ($this->user_basket_payment_type == 'creditcard' && $this->user_basket_payment_confirm == 'true'))
                $maxreserv = 15;
            else
                if ($this->user_basket_payment_type == 'cash')
                    $maxreserv = 10;
                elseif ($this->user_basket_payment_type == 'sberbank')
                    $maxreserv = 7;
                elseif ($this->user_basket_payment_type == 'cashon')
                    $maxreserv = 15;
                else
                    $maxreserv = 15;
                    //$maxreserv = 3;
        
            return $maxreserv;
        }
        
        /**
         * @return текст смс которое отправляется заказчику в зависимости от времения оформления заказа и типа доставки и оплаты
         */
        function smstext()
        {
            // неоплаченный остаток заказа
            $basketsum = $this->getBasketSum() - $this->alreadyPayed;
            
            // телефон обратной связи
            $phone = str_replace(array(' ','(', ')', '-'), '', ((($this->address['region'] == 'russia' && $this->address['city'] == 1) || $this->address['region'] == 'user' || $this->address['region'] == 'moscow') ? getVariableValue('contactPhone1') : getVariableValue('contactPhoneNonMoscow')));
        
            // сколько времени прошло с момента заказа
            $left = floor((time() - strtotime($this->user_basket_date)) / (24 * 60 * 60));

            $maxreserv = $this->maxReservTime();
            
            // резерв истёк
            if (($left >= $maxreserv || $this->user_basket_status == 'canceled') && $this->user_basket_payment_confirm == 'false')
            {
                $text = 'Резерв истек. Заказ №' . substr($this->address['phone'], -4) . ' отменен.';
            }
            else
            {
                // обмен
                if ($this->logs['set_mark'])
                {
                    switch($this->user_basket_delivery_type)
                    {
                        case 'user':
                            // напечатан
                            if (!$this->isPrinted())
                                $text = 'Ваш заказ на обмен №' . substr($this->address['phone'], -4) . ' подтверждён! Обмен бесплатный. Заказ будет готов к %s ' . $this->logs['admin_deliverytime'][0]['result'] . '. О готовности Вы получите СМС';
                            else
                                $text = 'Заказ ' . substr($this->address['phone'], -4) . ' готов! Адрес http://goo.gl/AtMa4h.' . (($left > 0) ? ' Резерв ' . ($maxreserv - $left) . ' дня(ей).' : '');
                            break;
                        
                        case 'deliveryboy':
                        case 'metro':
                            
                            // брак
                            if ($this->logs['set_mark'][0]['result'] == 'exchange_brak') {
                                $text = 'Ваш заказ на обмен №' . substr($this->address['phone'], -4) . ' подтверждён! Заказ будет исполнен %s ' . $this->logs['admin_deliverytime'][0]['result'] . '. Обмен бесплатный. Необходимо будет вернуть курьеру бракованый заказ';
                            // не брак
                            } else {
                                
                                $esum = 0; // сумма обменных (оригинальных) заказов
                                
                                foreach(json_decode($this->logs['set_mark'][0]['info']) AS $e)
                                {
                                    try
                                    {
                                        $eo = new basket($e);
                                        $esum += $eo->basketSum;
                                    }
                                    catch (Exception $e)
                                    {
                                        printr($e->getMessage());
                                    }
                                }
                                
                                //$text = 'Ваш заказ на обмен №' . substr($this->address['phone'], -4) . ' подтверждён! Заказ будет исполнен %s ' . $this->logs['admin_deliverytime'][0]['result'] . '. Необходимо будет вернуть курьеру заказ на обмен. ' . (($esum > $this->basketSum) ? 'Мы вернём Вам ' . ($esum - $this->basketSum) : 'К оплате ' . $this->user_basket_delivery_cost) . ' рублей';
                                $text = 'Ваш заказ на обмен №' . substr($this->address['phone'], -4) . ' подтверждён! Заказ будет исполнен %s ' . $this->logs['admin_deliverytime'][0]['result'] . '. Необходимо будет вернуть курьеру заказ на обмен. ' . ($esum > $this->basketSum ? '' : 'К оплате ' . $this->user_basket_delivery_cost . ' рублей');
                            }
                            break;
                            
                        case 'dpd':
                        case 'dpd_self':
                            // напечатан
                            if (!$this->isPrinted())
                                $text = 'Ваш заказ на обмен №' . substr($this->address['phone'], -4) . ' подтверждён! Обмен бесплатный. Заказ будет готов к %s ' . $this->logs['admin_deliverytime'][0]['result'] . '. О готовности Вы получите СМС';
                            //else
                                //$text = 'Заказ ' . substr($this->address['phone'], -4) . ' готов! Адрес http://goo.gl/AtMa4h.' . (($left > 0) ? ' Резерв ' . ($maxreserv - $left) . ' дня(ей).' : '');
                            break;
                            
                        case 'IMlog':
                        case 'IMlog_self':
                            // напечатан
                            if (!$this->isPrinted())
                                $text = 'Ваш заказ на обмен №' . substr($this->address['phone'], -4) . ' подтверждён! Обмен бесплатный. Заказ будет готов к %s ' . $this->logs['admin_deliverytime'][0]['result'] . '. О готовности Вы получите СМС';
                            else
                                $text = 'Ваш заказ на обмен №' . substr($this->address['phone'], -4) . ' отправлен! Обмен бесплатный. О готовности Вы получите СМС';
                            
                            break;
                            
                        case 'post':
                            $text = 'Ваш заказ на обмен №' . substr($this->address['phone'], -4) . ' отправлен! Обмен бесплатный. О готовности Вы получите СМС';
                            break;
                    }
                }
                else 
                {
                    switch($this->user_basket_delivery_type)
                    {
                        // самовывоз
                        case 'user':

                            // наличка
                            if ($this->user_basket_payment_type == 'cash' || $this->user_basket_payment_type == 'creditcard_onplace') {
                                // напечатан
                                if (!$this->isPrinted())
                                    $text = 'Заказ ' . substr($this->address['phone'], -4) . ' будет исполнен %s. Вам поступит СМС.';
                                else
                                    $text = 'Заказ ' . substr($this->address['phone'], -4) . ' готов! Адрес http://goo.gl/AtMa4h.' . ($maxreserv - (int) $left > 0 ? ' Резерв ' . ($maxreserv - $left) . ' дня(ей).' : 'Резевр истёк.');
                            // безнал
                            } else {
                                if (!$this->isPrinted())
                                    // не оплачен
                                    if ($this->user_basket_payment_confirm == 'false')
                                        $text = 'Заказ ' . substr($this->address['phone'], -4) . ' подтверждён!' . (($basketsum > 0) ? ' Ожидаем оплату в размере '  . $basketsum . 'р.' : '');
                                    else
                                        $text = 'Заказ ' . substr($this->address['phone'], -4) . ' будет исполнен %s. Вам поступит СМС.';
                                else {
                                    $text = 'Заказ ' . substr($this->address['phone'], -4) . ' готов! Адрес http://goo.gl/AtMa4h.' . ($maxreserv - (int) $left > 0 ? ' Резерв ' . ($maxreserv - $left) . ' дня(ей).' : 'Резевр истёк.');
                                }
                            }   
                                
                            break;
                        
                        // курьер
                        case 'deliveryboy':
                        case 'metro':
                                
                            // наличка
                            if ($this->user_basket_payment_type == 'cash' || $this->user_basket_payment_type == 'creditcard_onplace') {
                                $text = 'Заказ ' . substr($this->address['phone'], -4) . ' будет исполнен %s ' . (($this->logs['admin_deliverytime']) ? $this->logs['admin_deliverytime'][0]['result'] : '') . ' Курьер за час позвонит.';
                            // безнал   
                            } else {
                                // не оплачен
                                if ($this->user_basket_payment_confirm == 'false')
                                    $text = 'Заказ ' . substr($this->address['phone'], -4) . ' принят! Ожидаем оплату в размере ' . $basketsum . ' руб.';
                                else
                                    $text = 'Заказ ' . substr($this->address['phone'], -4) . ' доставим %s ' . (($this->logs['admin_deliverytime']) ? $this->logs['admin_deliverytime'][0]['result'] : '') . '. Курьер за час позвонит.';
                            }
                            
                            break;
                            
                        case 'post':
                            
                            // наличка
                            if ($this->user_basket_payment_type == 'cash') {
                            // безнал   
                            } else {
                                // не оплачен
                                if ($this->user_basket_payment_confirm == 'false')
                                    $text = 'Ожидаем оплату заказа ' . substr($this->address['phone'], -4) . '. Резерв ' . ($maxreserv - $left) . ' дня(ей).';
                                else
                                    if ($this->user_basket_payment_type != 'cashon')
                                        $text = 'Заказ ' . substr($this->address['phone'], -4) . ' оплачен. Будет передан на Почту РФ %s.';
                            }
                            
                            break;
                        
                        case 'IMlog':
                        case 'IMlog_self':
                            
                            // наличка
                            if ($this->user_basket_payment_type == 'cash') {
                            // безнал   
                            } else {
                                // не оплачен
                                if ($this->user_basket_payment_confirm == 'false')
                                    if ($this->user_basket_payment_type == 'cashon')
                                        $text = 'Заказ ' . substr($this->address['phone'], -4) . ' на сумму ' . $basketsum . 'р. принят, будет отправлен в IML %s.';
                                    else
                                        $text = 'Ожидаем оплату заказа ' . substr($this->address['phone'], -4) . '. Резерв ' . ($maxreserv - $left) . ' дня(ей).';
                                else
                                    // наложенный платёж
                                    if ($this->user_basket_payment_type == 'cashon')
                                        $text = 'Заказ ' . substr($this->address['phone'], -4) . ' на сумму ' . $basketsum . 'р. принят, будет отправлен в IML %s.';
                                    else
                                        $text = 'Заказ ' . substr($this->address['phone'], -4) . ' оплачен. Будет передан в службу доставки %s.';
                            }
                            
                            break;
                        
                        case 'dpd':
                        case 'dpd_self':
                            
                            // наличка
                            if ($this->user_basket_payment_type == 'cash') {
                            // безнал   
                            } else {
                                // не оплачен
                                if ($this->user_basket_payment_confirm == 'false')
                                    if ($this->user_basket_payment_type == 'cashon')
                                        $text = 'Заказ ' . substr($this->address['phone'], -4) . ' на сумму ' . $basketsum . 'р. принят, будет отправлен в DPD %s.';
                                    else
                                        $text = 'Ожидаем оплату заказа ' . substr($this->address['phone'], -4) . '. Резерв ' . ($maxreserv - $left) . ' дня(ей).';
                                else
                                    // наложенный платёж
                                    if ($this->user_basket_payment_type == 'cashon')
                                        $text = 'Заказ ' . substr($this->address['phone'], -4) . ' на сумму ' . $basketsum . ' р. принят, будет отправлен в DPD %s.';
                                    else
                                        $text = 'Заказ ' . substr($this->address['phone'], -4) . ' оплачен. Будет передан в службу доставки %s.';
                            }   
                            
                            break;
                    }
                }
            }
            
            return (!empty($text)) ? $text . ' Тел. ' . $phone : '';
        }
        
        /**
         * определяем отпечатан ли заказ полностью
         */
        function isPrinted()
        {
            if (!isset($this->info['isPrinted'])) 
            {
                $sth = App::db()->prepare("SELECT COUNT(pp.`id`) AS printed
                                  FROM `user_basket_goods` ubg, `" . printturn::$dbtable . "` pp, `good_stock` gs
                                  WHERE 
                                        ubg.`user_basket_id` = :id
                                    AND pp.`user_basket_good_id` = ubg.`user_basket_good_id`
                                    AND gs.`good_stock_id` = ubg.`good_stock_id`
                                    AND gs.`good_id` = '0'
                                    AND pp.`status` = 'printed'");
                                    
                $sth->execute(array(
                    'id' => $this->id,
                )); 
                
                $foo = $sth->fetch();
                
                foreach ($this->basketGoods as $g) {
                    if ($g['gsGoodId'] == 0)
                        $c += $g['quantity'];
                }
                
                $this->info['isPrinted'] = $foo['printed'] > 0 && $foo['printed'] >= $c;
            }
            
            return $this->info['isPrinted'];
        }
        
        /**
         * Количество бонусов которые вернуться пользователю за заказ
         */
        function getBasketSumBonusBack() 
        {
            // партнёры не получают бонусы за свои заказы
            if ($this->user->user_partner_status > 0) {
                return 0;
            }             
        
            // процента кэшбэка пользователя
            $percent = buyerLevel2discount($this->user->user_delivered_orders);
            // бонусов на счету пользователя
            $ub = $this->user->user_bonus;

            if ($ub < 0) {
                $ub = 0;
            }
            
            $totalPrice = 0;
            
            if ($this->user_basket_status == 'active') 
            {
                foreach ($this->basketGoods as $row) {
                    if ($row['category'] != 'stickerset') {
                        $totalPrice += $row['tprice'];
                    }
                }
                
                $totalPrice -= min($ub, round($totalPrice / 100 * getVariableValue('maxParticalPayPercent')));
                
                if ($totalPrice < 0) 
                    $totalPrice = 0;
            }
            else 
            {
                foreach ($this->basketGoods as $row) {
                    if ($row['category'] != 'stickerset') {
                        $totalPrice += $row['user_basket_good_total_price'];
                    }
                }

                $totalPrice -= $this->user_basket_payment_partical;
            }
            
            if ($totalPrice < 0)
                $totalPrice = 0;
            
            $bonusback  = ceil($totalPrice / 100 * $percent);

            return intval($bonusback);
        }
        
        /**
         * количеств товаров -> диапазон скидки за количество
         * @return int 
         */
        function countGoods2discountRange()
        {
            $gc = $this->getGoodsCount();
              
            foreach (self::$d_ranges as $krange => $range) {
                if ($gc >= $range) {
                    $drange = $krange;
                }
            }
            
            return $drange;
        }
        
        /**
         * Сумма содержимого корзины
         * @return сумма товаров
         */
        function getBasketSum()
        {
            $this->info['basketSum'] = 0;

            if (count($this->basketGoods) > 0) {
                foreach ($this->basketGoods as $pos) {
                    $this->info['basketSum'] += $pos['tprice'];
                }
            }
            
            if (count($this->basketGifts) > 0) {
                foreach ($this->basketGifts as $pos) {
                    $this->info['basketSum'] += $pos['tprice'];
                }
            }
            
            if ($this->user_basket_status != 'active') {
                $this->info['basketSum'] += $this->user_basket_delivery_cost - $this->user_basket_payment_partical;
            }
            
            return $this->info['basketSum'];
        }
    
        /**
         * Получить количество товаров в корзине
         * @param string $type тип считаемых позиций (все/тряпки/наклейки) 
         * @return int кол-во товаров в корзине
         */
        function getGoodsCount($type = null) {
            
            if ($type == 'wear')
            {
                $sth = App::db()->prepare("SELECT 
                                                SUM(ubg.`user_basket_good_quantity`) AS s
                                             FROM 
                                                `user_basket_goods` ubg,
                                                `good_stock` gs,
                                                `styles` s,
                                                `styles_category` sc
                                             WHERE 
                                                    ubg.`user_basket_id` = :id
                                                AND ubg.`good_stock_id` = gs.`good_stock_id`
                                                AND gs.`style_id` = s.`style_id`
                                                AND s.`style_category` = sc.`id` 
                                                AND sc.`cat_parent` = '1'");
                                        
            } else {
                $sth = App::db()->prepare("SELECT 
                                                SUM(ubg.`user_basket_good_quantity`) AS s
                                             FROM 
                                                `user_basket_goods` ubg,
                                                `good_stock` gs,
                                                `styles` s
                                             WHERE 
                                                    ubg.`user_basket_id` = :id
                                                AND ubg.`good_stock_id` = gs.`good_stock_id`
                                                AND gs.`style_id` = s.`style_id`
                                                AND s.`style_category` NOT IN ('85')");
            }
            
            $sth->execute(array(
                'id' => $this->id,
            ));
                
            $count = $sth->fetch();
            
            return $count['s'];
        }
        
        /**
         * Получить количество СТ в корзине
         * @return 
         */
        function getGiftsCount() {
            $s = 0;
            
            foreach ($this->basketGifts as $pos) {
                $s += $pos['q'];
            }
            
            return $s;
        }
    
        /**
         * @return array товары в корзине
         */
        function getbasketGoods() 
        {
            if (empty($this->id)) {
                return array();
            }
            
            if ($this->currency == 'usd') {
                $usdRate = usdRateDaily();
            }
            
            // для allskins.ru одежда на 10% дороже
            if ($this->basketDomain == '.allskins.ru') {
                $priceIncrease = getVariableValue('allskinsIncrease');
            } else {
                $priceIncrease = 0;
            }
            
            $discounts = array(0);
            
            if ($this->user_basket_status == 'active')
            {
                //$discounts[2] = userId2userPersonalDiscount($this->user_id);
                //$discounts[7] = intval($this->user_basket_discount);
                //$discounts[10] = $_COOKIE['informer'] ? 5 : 0; 
                $discounts[4] = userId2userBirthdayDiscount($this->user_id);
                
                // если в заказе был активирован сертификат
                if ($this->logs['activateDiscontCard']) {
                    $cert = new certificate($this->logs['activateDiscontCard'][0]['result']);
                }
            }
            
            $boxCost = getVariableValue('boxCost');
            
            $drange = $this->countGoods2discountRange();
            
            // массив данным по наклейкам для акции "1+1=3"
            $vinil = [];
            
            $tp = 0;
            
            $Thumb = new S3Thumb(array('ic1.maryjane.ru', 'ic2.maryjane.ru', 'ic3.maryjane.ru', 'ic4.maryjane.ru'), S3AccessKey, S3SecretKey, S3CryptKey);
            
            $rr = App::db()->prepare("SELECT 
                                ubg.*, 
                                ubg.`user_basket_good_price` p, 
                                ubg.`user_basket_good_discount` d, 
                                ubg.`user_basket_good_quantity` quantity, 
                                ubg.`user_basket_good_box` AS box, 
                                ubg.`user_basket_good_total_price` tp, 
                                ubg.`user_basket_good_comment` AS c,
                                ubg.`user_basket_good_partner_inc`,
                                ubg.`user_basket_good_time`,
                                g.`good_name`, 
                                g.`good_discount`, 
                                g.`good_payment_multiplier`, 
                                g.`good_status`, 
                                g.`ps_src`, 
                                g.`ps_src_back`, 
                                g.`good_buyout`,
                                u.`user_id`, 
                                u.`user_login`, 
                                u.`user_designer_level`, 
                                u.`user_city`,
                                c.`name` AS color_name,
                                c.`group` AS color_group,
                                gs.`good_id` AS gsGoodId,
                                gs.`style_id`,
                                gs.`size_id`,
                                gs.`size_rus`,
                                gs.`good_stock_drawing_cost` AS selfprice,
                                gs.`good_stock_price` AS price,
                                gs.`good_stock_discount` AS discount,
                                gs.`good_stock_discount_promo` AS discount_promo,
                                gs.`good_stock_discount_range_1`,
                                gs.`good_stock_discount_range_2`,
                                gs.`good_stock_discount_range_3`,
                                gs.`good_stock_discount_range_4`,
                                gs.`good_stock_discount_range_5`,
                                gs.`good_stock_discount_range_6`,
                                gs.`good_stock_clear_prices`,
                                gs.`good_stock_status`,
                                gs.`good_stock_quantity` +- gs.`good_stock_inprogress_quantity` AS avalible,
                                gs.`good_stock_quantity`,
                                gs.`good_stock_inprogress_quantity`,
                                gs.`good_stock_sold_quantity`,
                                s.`style_name`,
                                s.`style_sex`,
                                s.`style_category`,
                                s.`style_color`,
                                s.`style_viewsize`,
                                s.`style_slug`,
                                s.`style_composition`,
                                s.`style_description`,
                                s.`style_front_picture`,
                                s.`style_back_picture`,
                                s.`style_color` AS color_id,
                                s.`style_print_block`,
                                s.`style_weight`,
                                s.`style_print_cost_front`,
                                s.`style_print_cost_back`,
                                sc.`cat_slug`,
                                sc.`cat_parent`,
                                sz.`size_name`
                              FROM 
                                `user_basket_goods` ubg,
                                `goods` g 
                                    LEFT JOIN `users` u ON g.`user_id` = u.`user_id`,
                                `styles` AS s,
                                `styles_category` AS sc,
                                `sizes` AS sz,
                                `good_stock_colors` AS c,
                                `good_stock` AS gs
                              WHERE 
                                    ubg.`user_basket_id` = :id
                                AND ubg.`gift_id` = '0'
                                AND ubg.`good_id` = g.`good_id`
                                AND ubg.`good_stock_id` = gs.`good_stock_id`
                                AND gs.`style_id`      = s.`style_id`
                                AND gs.`size_id`       = sz.`size_id`
                                AND s.`style_color`    = c.`id`
                                AND sc.`id`            = s.`style_category`
                                AND s.`style_visible` = '1'
                              GROUP BY 
                                ubg.`user_basket_good_id`");
            
            $rr->execute(array(
                'id' => $this->id,
            ));
                                            
            foreach($rr->fetchAll(PDO::FETCH_ASSOC) AS $row)
            {
                $discounts[11] = $discounts[3] = 0;
                
                $row['ubgid']              = $row['user_basket_good_id'];
                $row['good_user']          = $row['user_id'];
                $row['good_name']          = stripslashes($row['good_name']);
                $row['style_name']         = stripslashes($row['style_name']);
                $row['style_composition']  = stripslashes($row['style_composition']);
                $row['size_name']          = stripslashes($row['size_name']);
                $row['color_name']         = stripslashes($row['color_name']);
                $row['style_viewsize']     = $row['style_viewsize'];
                $row['user_avatar']        = userId2userGoodAvatar($row['user_id'], 25, '', 1);
                $row['user_avatar_medium'] = userId2userGoodAvatar($row['user_id'], 50, '', 1);
                $row['designer_level']     = user::designerLevel2Picture($row['user_designer_level']);
                $row['comment']            = stripslashes($row['c']);
                $row['def_side']           = styleCategory::$BASECATS[$row['category']] ? styleCategory::$BASECATS[$row['category']]['def_side'] : 'front';
                
                // если у размера нет русского эквивалента (н-р, у наклейки), но есть комментарий то ставим его вместо размера
                if (empty($row['size_rus']) && !empty($row['c'])) {
                    $row['size_rus'] = $row['c'];
                }

                if (!empty($row['user_city'])) {
                    $row['user_city_name'] = cityId2name($row['user_city']);
                }
                
                // формируем ссылка на эту позицию в каталоге/конструктор
                if ($row['good_status'] == 'customize') {
                    $row['link'] = '/customize/style,' . $row['style_id'] . '/' . $row['good_id'] . '/';
                } else {
                    $row['link'] = '/catalog/' . $row['user_login'] . '/' . $row['good_id'] . '/' . $row['style_slug'] . '/' . ($row['cat_parent'] == 1 ? $row['size_name'] . '/' : '');
                }
                
                // Превьюшки
                // тряпки
                if ($row['cat_parent'] == 1)
                {
                    $row['category'] = $row['cat_slug'];
                    
                    $r = App::db()->query("SELECT p.`picture_path` FROM `pictures` p, `good_pictures` gp WHERE gp.`good_id` = '" . $row['good_id'] . "' AND gp.`pic_name` = '" . (($row['gsGoodId'] == 0) ? 'catalog' : 'sale') . '_preview_' . $row['style_id'] . "' AND gp.`pic_id` = p.`picture_id` LIMIT 1");

                    if ($r->rowCount() == 1) {
                        $foo = $r->fetch();
                        $row['imagePath'] = $foo['picture_path'];
                    }
                    
                    if ($row['good_status'] == 'customize')
                    {
                        $r = App::db()->query("SELECT p.`picture_path` FROM `pictures` p, `good_pictures` gp WHERE gp.`good_id` = '" . $row['good_id'] . "' AND gp.`pic_name` = 'catalog_preview_" . $row['style_id'] . "_back' AND gp.`pic_id` = p.`picture_id` LIMIT 1");
                    
                        if ($r->rowCount() == 1) {
                            $foo = $r->fetch();
                            $row['imageBackPath'] = $foo['picture_path'];
                        } else {
                            //$row['imageBackPath'] = '/ajax/generatePreview/?good_id=' . $row['good_id'] . '&style_id=' . $row['style_id'] . '&side=back';
                        }
                    }
                }
                // наклейки
                else
                {
                    $row['category'] = styleCategory::$BASECATSid[$row['cat_parent']];
                    
                    if ($row['style_id'] == 537)
                        $q = "SELECT p.`picture_path` FROM `pictures` p, `good_pictures` gp WHERE gp.`good_id` = '" . $row['good_id'] . "' AND gp.`pic_name` = 'stickerset_preview' AND gp.`pic_id` = p.`picture_id` LIMIT 1";
                    else
                        switch (styleCategory::$BASECATSid[$row['cat_parent']])
                        {
                            case 'postcards':
                                $q = "SELECT p.`picture_path` FROM `pictures` p, `good_pictures` gp WHERE gp.`good_id` = '" . $row['good_id'] . "' AND gp.`pic_name` IN ('postcards_0_preview', 'postcards_1_preview', 'postcards_2_preview', 'postcards_3_preview', 'postcards_4_preview') AND gp.`pic_id` = p.`picture_id` LIMIT 1";
                                break;
                            
                            case 'auto':
                                $q = "SELECT p.`picture_path` FROM `pictures` p, `good_pictures` gp WHERE gp.`good_id` = '" . $row['good_id'] . "' AND gp.`pic_name` = 'as_sticker' AND gp.`pic_id` = p.`picture_id` LIMIT 1";
                                break;
                                
                            default:
                                $q = "SELECT p.`picture_path` FROM `pictures` p, `good_pictures` gp WHERE gp.`good_id` = '" . $row['good_id'] . "' AND gp.`pic_name` = '" . (($row['gsGoodId'] == 0) ? 'catalog' : 'sale') . "_preview_" . $row['style_id'] . "' AND gp.`pic_id` = p.`picture_id` LIMIT 1";
                                break;
                        }

                    $r = App::db()->query($q);
                    
                    if ($r->rowCount() == 1) {
                        $foo = $r->fetch();
                        $row['imagePath'] = $foo['picture_path'];
                    }
                }

                if ($this->resizePreview) {
                    $w = styleCategory::$BASECATSid[$row['cat_parent']] == 'laptops' ? 152 : 85;
                }

                if (!empty($row['imagePath'])) {
                    if ($this->resizePreview) {
                        $row['imagePath'] = $Thumb->url($row['imagePath'], $w);
                    }
                } else {
                   $row['imagePath'] = 'http://www.maryjane.ru/ajax/generatePreview/?good_id=' . $row['good_id'] . '&style_id=' . $row['style_id'] . '&side=' . ($row['cat_parent'] > 1 ? styleCategory::$BASECATS[styleCategory::$BASECATSid[$row['cat_parent']]]['def_side'] : 'front') . '&width=' . $w;
                }
                
                // расчёт цены
                if ($this->user_basket_status == 'active')  
                {
                    // Скидка за количество
                    if ($row['gsGoodId'] == 0) {
                       $discounts[3] = $row['good_stock_discount_range_' . $drange];
                    }
                    
                    $discounts[1] = $row['discount'];
                    $discounts[8] = ($row['good_discount'] > 0) ? $row['discount_promo'] : 0;
                    
                    // TODO на время акции чёрная пятница отменяем суммроование скидки по купонам (убрать строчку)
                    $discounts[7] = floatval($this->user_basket_discount);
                    
                    if (!in_array(date('Y-m-d'), ['2017-11-23', '2017-11-24', '2017-11-25'])) {
                        if (!in_array($row['style_category'], [71, 118, 24]) && $row['good_id'] > 0 && $this->user->user_partner_status <= 0 && !$this->user->meta->mjteam) {
                            $discounts[11] = $this->user->city != '' && $this->user->city != 'Москва' && $this->user->city_region != 'Московская область' && $this->user->city_region != 'Москва' ? 20 : 0;
                        }
                    }
                  
                    if (($row['good_stock_status'] == 'few' && $row['gsGoodId'] > 0 && !$goodRouletteDiscount) || empty($row['price'])) {
                        $row['discount'] = 0;
                        $row['discount_type'] = 0;
                    } else {
                        arsort($discounts);
                        
                        $row['discount'] = reset($discounts);
                        
                        if ($row['discount'] > 0) {
                           $row['discount_type'] = key($discounts);
                        }
                    }
                    
                    // изделие на плёнке с расчитываемой ценой
                    if (empty($row['price']) && !empty($row['p'])) {
                        $row['price'] = $row['p'];
                    }
                    
                    /* TODO на время акции чёрная пятница отменяем суммроование скидки по купонам (раскоментировать)
                    if ($cert && $cert->certification_style_categorys && count($cert->certification_style_categorys) > 0) {
                        if (in_array($row['cat_parent'] == 1 ? $row['style_category'] : $row['cat_parent'], $cert->certification_style_categorys)) {
                            $row['discount'] += floatval($this->user_basket_discount);
                        }
                    } else {
                        $row['discount'] += floatval($this->user_basket_discount);
                    }
                    */
                    
                    $row['discount_description'] = self::$discountTypes[array_search($row['discount'], $discounts)];
                  
                    // акция "наклейки на один лист"
                    if ($_SESSION['sticerOneList'] && ($row['category'] == 'auto' || $row['category'] == 'stickers')) {
                        $row['discount'] += 10;
                    }
                  
                    // чит для позиции "доработка макета", она всегда идёт без скидки
                    if ($row['good_id'] == 109730) {
                        $row['discount'] = 0;
                        $row['discount_description'] = '';
                    }

                    $row['tprice'] = round(($row['price'] - ($row['price'] / 100 * $row['discount']) + (($row['box'] > 0) ? $boxCost : 0)) * $row['quantity']) ;
                  
                    if ($row['cat_parent'] == 1 && $priceIncrease > 0) {
                        $row['price'] += round($row['price'] / 100 * $priceIncrease);
                        $row['tprice'] += round($row['tprice'] / 100 * $priceIncrease);
                    }
                  
                    // если издели продаётся чистым, используем цены для чистого изделия
                    if (empty($row['good_id']) && empty($row['gift_id'])) {
                        
                        $clear_prices = json_decode($row['good_stock_clear_prices'], 1);
                        
                        if ($clear_prices[$drange]) {
                            $row['price'] = $clear_prices[$drange];
                            $row['tprice'] = $row['price'] * $row['quantity'];
                            $row['discount'] = 0;
                        }
                    }
                    
                    // наценка за самоделку
                    if ($row['good_status'] == 'customize')
                    {
                        $color_pc = $pc = 0;
                        
                        if ($_SESSION['whiteStyleDiscount'] && in_array($row['style_id'], array(407, 429, 390))) {
                            $row['discount'] = $_SESSION['whiteStyleDiscount'];
                            $row['tprice'] = 660 * $row['quantity'];
                        } elseif ($_SESSION['whiteStyleDiscount'] && in_array($row['style_id'], array(408, 436))) {
                            $row['discount'] = $_SESSION['whiteStyleDiscount'];
                            $row['tprice'] = 890 * $row['quantity'];
                        } elseif ($_SESSION['whiteStyleDiscount'] && in_array($row['style_id'], array(510, 510))) {
                            $row['discount'] = $_SESSION['whiteStyleDiscount'];
                            $row['tprice'] = 990 * $row['quantity'];
                        }
                        else 
                        {
                            if ($row['cat_parent'] > 1)
                            {
                                // убираем на самоделку если это простые наклейки или наклейки на доски и если кол-во > 1
                                if (!in_array($row['style_category'], [84, 85]) && $row['quantity'] == 1) {
                                    $pc = getVariableValue('allskinsCustomizePrintCost');
                                }
                            }
                            else
                            {
                                if (!$backSidePrintCost = getVariableValue('backSidePrintCost' . $row['color_id']))
                                    $backSidePrintCost = getVariableValue('backSidePrintCost');
                                
                                // если это не своя майка и не сумка с полной запечаткой
                                if ($row['style_category'] != 71 && $row['style_category'] != 118 && $row['style_category'] != 100) 
                                {
                                    // если кол-во равно 1, то прибавляем наценку за подготовку макета
                                    if ($row['quantity'] == 1)
                                    {
                                        if (!$pc = getVariableValue('frontSidePrintCost' . $row['color_id'])) {
                                            $pc = getVariableValue('frontSidePrintCost');
                                        }
                                    }
                                }
                                
                                if (!empty($row['ps_src']) && !empty($row['ps_src_back'])) {
                                    $row['price']  += $backSidePrintCost;
                                    $row['tprice'] += $backSidePrintCost * $row['quantity'];
                                }
                            }
                            
                            $row['price']  += $pc;
                            $row['tprice'] += $pc * $row['quantity'];
                        }
                    }
                    
                    // + наценка партнёра
                    $row['tprice'] += $row['user_basket_good_partner_inc'];
                } 
                else 
                {
                    // если заказ уже оформлен, то все данные по ценам и скидкам берём из базы
                    $row['price'] = $row['p'];
                    $row['discount'] = $row['d'];
                    $row['tprice'] = $row['tp'];
                }
                
                // сумма начисляемая автору дизайна с продажи данной позиции
                if ($row['good_status'] != 'customize' && $row['user_id'] != $this->user_id) {
                    $row['author_payment'] = ceil((round($row['price'] * (1 - $row['discount'] / 100)) / 100) * ($row['style_id'] == 537 ? 50 : 10)) * $row['quantity'];
                }
                
                $row['tprice_rub'] = $row['tprice'];
                
                // переводим цены в доллары
                if ($this->currency == 'usd')
                {
                    $row['price']  = round($row['price'] / $usdRate, 1);
                    $row['tprice'] = round($row['tprice'] / $usdRate, 1);
                    $row['tp']     = round($row['tp'] / $usdRate, 1);
                }
                
                $this->basketGoods[$row['user_basket_good_id']]  = $row;
                
                $this->goodsCount += $row['q'];
                $tp += $row['tprice'];
                
                // ищем наклейку с наименьшей ценой
                if (in_array($row['category'], array('auto', 'phones', 'laptops', 'touchpads', 'ipodmp3')) && $this->user_basket_status == 'active')
                {
                    for ($i = 0; $i < $row['quantity']; $i++) {
                        $vinil['ids'][]    = $row['user_basket_good_id']; 
                        $vinil['prices'][] = round($row['tprice'] / $row['quantity']);
                    }
                }
            }

            if ($this->user_basket_status == 'active') 
            {
                if ($vinil['prices']) {
                    asort($vinil['prices']);
                    $vinil['prices'] = array_slice($vinil['prices'], 0, floor(count($vinil['prices']) / 3), true);
                }
            }
            
            krsort($this->basketGoods);
            
            // каждая 3я наклейка бесплатно
            if (count($vinil['prices']) > 0) {
                foreach ($vinil['prices'] as $k => $p) {
                    $this->basketGoods[$vinil['ids'][$k]]['discount'] = round(100 / $this->basketGoods[$vinil['ids'][$k]]['quantity'], 1) /*- $this->basketGoods[$vinil['ids'][$k]]['discount'] / 2*/;
                    $this->basketGoods[$vinil['ids'][$k]]['tprice'] -= $p;
                    $this->basketGoods[$vinil['ids'][$k]]['tprice_rub'] -= $p;
                }
            }

            if ($this->user->meta->givegifts && $this->user_basket_status == 'active')
            {
                if ($tp >= 3000) 
                {
                    foreach ($this->basketGoods as $k => $g) 
                    {
                        if ($this->basketGoods[$k]['tprice'] > 0) {
                            $this->basketGoods[$k]['tprice'] -= round(300 / $this->goodsCount);
                            $this->basketGoods[$k]['tprice_rub'] -= round(300 / $this->goodsCount);
                        }
                    }
                }
            }
            
            return $this->basketGoods;
        }
        
        /**
         * Получить вес товаров в корзине
         * @return 
         */
        function getBasketWeight() {
            
            if (empty($this->id)) 
                return false;
            
            $tw = 0;
            
            foreach ($this->basketGoods as $pos) 
            {
                $tw += ($pos['style_weight'] / 1000) * $pos['q'];
            }
            
            return $tw;
        }
        
        function plusOne($gid, $sid, $q = 1)
        {
            $foo = App::db()->query("SELECT (gs.`good_stock_quantity` - gs.`good_stock_inprogress_quantity`) AS q FROM `good_stock` AS gs WHERE gs.`good_stock_id` = '" . intval($sid) . "'")->fetch();
            
            $available = $foo['q'];
            
            foreach ($this->basketGoods as $posk => $pos) {
                if ($pos['good_id'] == $gid && $pos['good_stock_id'] == $sid) {
                    $reservedtome = $pos['quantity'];
                    break;
                }
            }
            
            if ($q > $available - $reservedtome)
            {
                $msg = "error: Этот товар зарезервирован или отсутствует на складе.\nВозможно кто-то откажется от данной модели, попробуйте сделать заказ завтра.";
            } 
            else
            {
                $sth = App::db()->prepare("UPDATE 
                                    `user_basket_goods` 
                                  SET 
                                    `user_basket_good_quantity` = `user_basket_good_quantity` + :q
                                  WHERE 
                                        `user_basket_id` = :bid
                                    AND `good_id` = :gid
                                    AND `good_stock_id` = :sid
                                  LIMIT 1");

                $sth->execute(array(
                    'q' => $q,
                    'bid' => $this->id,
                    'gid' => $gid,
                    'sid' => $sid,
                ));
                                  
                unset($this->basketGoods);
                                  
                $msg = "ok: " . $this->getBasketSum();
            }
            
            return $msg;
        }

        /**
         * Увеличить количество товара в корзине
         */
        function chQuanity($gid, $sid, $q = 1)
        {
            $foo = App::db()->query("SELECT (gs.`good_stock_quantity` - gs.`good_stock_inprogress_quantity`) AS q FROM `good_stock` AS gs WHERE gs.`good_stock_id` = '" . intval($sid) . "'")->fetch();
            
            $available = $foo['q'];
            
            foreach ($this->basketGoods as $posk => $pos) {
                if ($pos['good_id'] == $gid && $pos['good_stock_id'] == $sid) {
                    $reservedtome = $pos['quantity'];
                    break;
                }
            }
            
            if ($q > $available - $reservedtome)
            {
                $msg = "error: Этот товар зарезервирован или отсутствует на складе.\nВозможно кто-то откажется от данной модели, попробуйте сделать заказ завтра.";
            } 
            else
            {
                $sth = App::db()->prepare("UPDATE 
                                    `user_basket_goods` 
                                  SET 
                                    `user_basket_good_quantity` = :q
                                  WHERE 
                                        `user_basket_id` = :bid
                                    AND `good_id` = :gid
                                    AND `good_stock_id` = :sid
                                  LIMIT 1");
                                  
                $sth->execute(array(
                    'q' => $q,
                    'bid' => $this->id,
                    'gid' => $gid,
                    'sid' => $sid,
                ));
                                  
                unset($this->basketGoods);
                                  
                $msg = "ok: " . $this->getBasketSum();
            }
            
            return $msg;
        }
        
        /**
         * Увеличить количество сопуствующего товавра в корзине
         */
        function chGiftQuanity($gid, $q = 1)
        {
            $foo = App::db()->query(sprintf("SELECT g.`gift_quantity` FROM `gifts` AS g WHERE g.`gift_id` = '%d'", $gid))->fetch();
            
            $available = $foo['gift_quantity'];
            
            foreach ($this->basketGifts as $posk => $pos) {
                if ($pos['gift_id'] == $gid) {
                    $reservedtome = $pos['quantity'];
                    break;
                }
            }
            
            if ($q > $available - $reservedtome)
            {
                $msg = "error: Этот товар зарезервирован или отсутствует на складе.";
            } 
            else
            {
                $sth = App::db()->prepare("UPDATE 
                                    `user_basket_goods` 
                                  SET 
                                    `user_basket_good_quantity` = `user_basket_good_quantity` + :q
                                  WHERE 
                                        `user_basket_id` = :bid
                                    AND `gift_id` = :gid
                                  LIMIT 1");
                
                $sth->execute(array(
                    'q' => intval($q),
                    'bid' => $this->id,
                    'gid' => $gid,
                ));
                
                unset($this->basketGifts);
                
                $msg = "ok: " . $this->getBasketSum();
            }
            
            return $msg;
        }
        
        /**
         * Добавить новую позицию в корзину
         * @param basketItem $item добавляемая позиция
         * @return array массив состояния
         */
        public function addToBasket(basketItem $item)
        {
            if (empty($this->id))
                $this->addBasket();

            if (empty($item->good_stock_id)) {
                return array('error' => 'Не указан носитель');
            }
            
            $item->good_id = (int) $item->good_id;
            $item->gift_id = (int) $item->gift_id;
            $item->good_stock_id = (int) $item->good_stock_id;
            
            // максимальное кол-во одной позиции в заказе 
            $maxOrderQuantity = getVariableValue('maxOrderQuantity');
            // максимальное кол-во позиций в заказе 
            $maxOrderPositions = getVariableValue('maxOrderPositions');

            $goodsCount = $reservedtome = 0;

            foreach ($this->basketGoods AS $ubgid => $pos) 
            {
                $goodsCount++;
                
                if ($pos['good_stock_id'] == $item->good_stock_id) {
                    $reservedtome += intval($pos['q']);
                }
                
                // + 1  
                if ($item->good_id == $pos['good_id'] && $pos['good_stock_id'] == $item->good_stock_id) {
                    $plus1 = $ubgid;
                }
            }
            
            // Получаем информацию о носителе на складе
            $sth = App::db()->prepare("SELECT 
                    (gs.`good_stock_quantity` +- gs.`good_stock_inprogress_quantity`) AS avalible, 
                    gs.`good_stock_price` AS price, 
                    gs.`good_stock_discount` AS discount, 
                    gs.`good_stock_discount_promo` AS discount_promo, 
                    gs.`good_id` AS good_stock_good_id, 
                    sc.`cat_parent`, 
                    gs.`style_id` AS sid, 
                    s.`style_color` AS color_id, 
                    gs.`good_stock_status` AS status, 
                    s.`style_category` 
                FROM 
                    `good_stock` AS gs, `styles` s, `styles_category` sc 
                WHERE 
                    gs.`good_stock_id` = ? AND gs.`style_id` = s.`style_id` AND sc.`id` = s.`style_category`");
            
            $sth->execute([$item->good_stock_id]);
            $stock = $sth->fetch();
            
            
            if ($stock['cat_parent'] == 1 && $item->good_id > 0 && ($item->quantity + $goodsCount > $maxOrderPositions))
            {
                return array('error' => "Вы не можете заказать более чем " . $maxOrderPositions . " позиций в одном заказе, пожалуйста обратитесь к администрации магазина");
            } 
            else 
            {
                if ($item->quantity > $stock['avalible'] - $reservedtome)
                {
                    return array('error' => "Этот товар зарезервирован или отсутствует на складе.\nВозможно кто-то откажется от данной модели, попробуйте сделать заказ завтра.");
                } 
                else
                {
                    // для одежды ограничение на кол-во заказываемых позиций
                    if ($stock['cat_parent'] == 1 && $item->good_id > 0 && $item->quantity + intval($this->basketGoods[$plus1]['quantity']) > $maxOrderQuantity)
                    {
                        return array('error' => "Вы не можете заказать более чем " . $maxOrderQuantity . " по одной позиции, обратитесь к администрации магазина");
                    } 
                    else
                    {
                        $sth = App::db()->prepare("SELECT g.`good_status`, g.`ps_src`, g.`ps_src_back`, g.`good_discount` FROM `goods` AS g WHERE g.`good_id` = ?");
                        $sth->execute([$item->good_id]);
                        $good = $sth->fetch();
                        
                        $discounts = [0];
                        
                        // на распрадажу не распространяются никакие скидки
                        if ($stock['status'] == 'few' && $stock['good_stock_good_id'] > 0)
                        {}
                        else
                        {
                            $discounts[4] = userId2userBirthdayDiscount($this->user_id);
                            //$discounts[2] = userId2userPersonalDiscount($this->user_id);
                            //$discounts[7] = $this->user_basket_discount;
                            $discounts[1] = $stock['discount'];
                            $discounts[8] = ($good['good_discount'] > 0) ? $stock['discount_promo'] : 0;
                            
                            if (!in_array(date('Y-m-d'), ['2017-11-23', '2017-11-24', '2017-11-25'])) {
                                if (!in_array($stock['style_category'], [71, 118, 24]) && $item->good_id > 0 && $this->user->user_partner_status <= 0 && !$this->user->meta->mjteam) {
                                    $discounts[11] = $this->user->city != '' && $this->user->city != 'Москва' && $this->user->city_region != 'Московская область' && $this->user->city_region != 'Москва' ? 20 : 0;
                                } 
                            }
                        }
                        
                        $stock['discount'] = max($discounts);
                        $stock['discount'] += intval($this->user_basket_discount);
    
                        arsort($discounts);
                        
                        $discount_type = key($discounts);
    
                        // изделие с расчитываемой в зависимости от размера ценой (наклейка на ноутбук, на автомобиль, ткань)
                        if (empty($stock['price']) && !empty($item->price)) {
                            $stock['price'] = (int) $item->price * ($this->currency == 'usd' ? usdRateDaily() : 1);
                            $stock['discount'] = 0;
                        }
    
                        if ($_SESSION['whiteStyleDiscount']) {
                            if (in_array($stock['sid'], array(407, 429, 390))) {
                                $stock['price'] = 660;
                            } elseif (in_array($stock['sid'], array(408, 436))) {
                                $stock['price'] = 890;
                            } elseif (in_array($stock['sid'], array(510, 510))) {
                                $stock['price'] = 990;
                            }
                            $stock['discount'] = 0;
                        }
    
                        if ($plus1)
                        {
                            App::db()->query("UPDATE `user_basket_goods`
                                             SET
                                                `user_basket_good_quantity` = `user_basket_good_quantity` + '" . $item->quantity . "'
                                             WHERE 
                                                `user_basket_good_id` = '" . $plus1 . "'
                                             LIMIT 1");
                                         
                            $ubgid = $plus1;
                        }
                        else 
                        {
                            App::db()->query("INSERT INTO `user_basket_goods`
                                              SET
                                                `user_basket_id`               = '" . $this->id . "',
                                                `good_id`                      = '" . $item->good_id . "',
                                                `gift_id`                      = '" . $item->gift_id . "',
                                                `good_stock_id`                = '" . $item->good_stock_id . "',
                                                `user_basket_good_price`       = '" . $stock['price'] . "',
                                                `user_basket_good_discount`    = '" . $stock['discount'] . "',
                                                `user_basket_good_total_price` = '" . (round($stock['price'] * (1 - $stock['discount'] / 100)) * $item->quantity) . "', 
                                                `user_basket_good_discount_type` = '" . $discount_type . "',
                                                `user_basket_good_quantity`    = '" . $item->quantity . "',
                                                `user_basket_good_box`         = '-1',
                                                `user_basket_good_comment`     = '" . addslashes(urldecode($item->comment)) . "'");
                                            
                            $ubgid = App::db()->lastInsertId();
                        }
    
                        // если это первый товар в корзине, запоминаем дату добавления
                        if ($this->user_basket_fill_date == '0000-00-00 00:00:00') {
                            $this->basketChange(array('user_basket_fill_date' => NOW));
                        }
                        
                        // чтобы перевытащить позиции из базы и перерасчитать сумму со всеми скидками
                        unset($this->basketGoods);

                        $msg = array(
                            'id' => $ubgid,
                            'sum' => $this->getBasketSum(),
                            'price' => $this->basketGoods[$ubgid]['tprice'],
                        );
                    }
                }
            }
            
            return $msg;
        }
        
        /**
         * Добавить в корзину сразу несколько позиций или СТ
         * @param array $goods массив с товарами
         * @param array $gifts массив с СТ
         */
        public function add2basket($goods, $gifts) 
        {
            foreach($goods AS $good_id => $gs)
            {
                foreach($gs AS $goodStockId => $pos)
                {
                    $basketItem = new basketItem;
                    $basketItem->good_id = $good_id;
                    $basketItem->good_stock_id = $goodStockId;
                    $basketItem->quantity = $pos['q'];
                    $basketItem->price = $pos['p'];
                    $basketItem->comment = $pos['c'];
                    
                    $msg = $this->addToBasket($basketItem);
                }
            }

            foreach ($gifts as $giftId) 
            {
                $msg2 = $this->addGift2basket($giftId, 1);
                
                $msg['sum'] = $msg2['sum'];
                $msg['price'] += $msg2['price'];  
            }
            
            return $msg;
        }
        
        /**
         * Добавить СТ в корзину
         * @return 
         * @param object $giftId - СТ
         * @param object $q - количество
         * @param object $p - цена
         */
        function addGift2basket($giftId, $quant = 1, $p) {
            
            if (empty($this->id)) {
                $this->addBasket();
            }
                
            $giftId = intval($giftId);
            $quant  = intval($quant);
                        
            // Выбираем информацию о подарке
            $r = App::db()->query("SELECT * FROM `gifts` WHERE `gift_id` = '" . $giftId . "' LIMIT 1");

            if ($gift = $r->fetch())
            {
                // Проверяем не зарезервированы ли все эти подарки уже другими пользователями
                $q = "SELECT IFNULL(SUM(ubg.`user_basket_good_quantity`), 0) AS q
                      FROM `user_basket_goods` AS ubg, `user_baskets` AS ub 
                      WHERE 
                            ubg.`gift_id` = '$giftId'
                        AND ubg.`user_basket_id` = ub.`user_basket_id`
                        AND (ub.`user_basket_status` = 'ordered' OR ub.`user_basket_status` = 'accepted' OR ub.`user_basket_status` = 'waiting')
                        AND ub.`user_basket_id` <> '" . $this->id . "'";
                
                $foo = App::db()->query($q)->fetch();
                
                $reserved = $foo['q'];

                if (($gift['gift_quantity'] - $reserved) <= 0) {
                    return array('error' => 'Этот товар уже зарезервирован другими пользователями');
                }
                else
                {
                    // сертификат произвольной стоимости
                    if ($gift['gift_type'] == 'certifikat' && empty($gift['gift_price']))
                        if (!empty($p))
                            $gift['gift_price'] = $p; 
                        else
                            return array('error' => 'Не указан номинал сертификата');
                    
                    foreach ($this->basketGifts AS $id => $pos) 
                    {
                        
                        if ($pos['gift_id'] == $giftId) {
                            $plus1 = $id;
                            break;
                        }
                    }
                    
                    if (!$plus1) {
                        App::db()->query("INSERT INTO `user_basket_goods`
                                     SET
                                        `user_basket_id`               = '" . $this->id . "',
                                        `gift_id`                      = '" . $giftId . "',
                                        `user_basket_good_price`       = '" . $gift['gift_price'] . "',
                                        `user_basket_good_discount`    = '" . $gift['gift_discount'] . "',
                                        `user_basket_good_quantity`    = '" . $quant . "',
                                        `user_basket_good_total_price` = '" . round(($gift['gift_price'] * (1 - $gift['gift_discount'] / 100)) * $quant) . "',
                                        `user_basket_good_box`         = '-1'");
                        
                        $ubgid = App::db()->lastInsertId();
                        
                    } else {
                        App::db()->query("UPDATE `user_basket_goods`
                                     SET
                                        `user_basket_good_quantity` = `user_basket_good_quantity` + '" . $quant . "'
                                     WHERE 
                                            `user_basket_good_id` = '" . $plus1 . "'
                                     LIMIT 1");
                                     
                        $ubgid = $plus1;
                    }
 
                    
                    // Если добавляем в корзину дисконтную карту,
                    // по применяем к корзине скидку по ней
                    if ($gift['gift_type'] == 'diskont_card')
                    {
                        $this->basketChange(array(
                            'user_basket_discount' => $gift['gift_discount'],
                        ));
                    }

                    unset($this->basketGifts);
                    
                    return array(
                        'id' => $ubgid,
                        'sum' => $this->getBasketSum(),
                        'price' => round(($gift['gift_price'] * (1 - $gift['gift_discount'] / 100)) * $quant),
                    );
                }
            } else {
                return array('error' => 'Не известный товар');
            }
            
            return $msg;
        }
        
        /**
         * Удалить товар из корзины
         * @return 
         * @param object $good_id
         * @param object $good_stock_id
         */
        function removeGood($good_id, $good_stock_id, $q = 1) {
            
            foreach ($this->basketGoods as $pos) {
                if ($pos['good_id'] == $good_id && $pos['good_stock_id'] == $good_stock_id) {
                    $reservedtome = $pos['quantity'];
                }
            }
            
            if ($q >= $reservedtome) {
                App::db()->query("DELETE FROM `user_basket_goods` WHERE `user_basket_id` = '" . $this->id . "' AND `good_id` = '" . intval($good_id) . "' AND `good_stock_id` = '" . intval($good_stock_id) . "' AND `gift_id` = '0' LIMIT 1");
            } else {
                App::db()->query("UPDATE `user_basket_goods` SET `user_basket_good_quantity` = `user_basket_good_quantity` - 1 WHERE `user_basket_id` = '" . $this->id . "' AND `good_id` = '" . intval($good_id) . "' AND `good_stock_id` = '" . intval($good_stock_id) . "' LIMIT 1");
            }
        }
        
        /**
         * Удалить СТ из корзины
         * @return 
         * @param object $giftId
         */
        function removeGift($giftId, $q = 1) 
        {
            foreach ($this->basketGoods as $pos) {
                if ($pos['gift_id'] == $giftId) {
                    $reservedtome = $pos['quantity'];
                }
            }
            
            if ($q >= $reservedtome) {
                App::db()->query("DELETE FROM `user_basket_goods` WHERE `user_basket_id` = '" . $this->id . "' AND `gift_id` = '" . intval($giftId) . "' LIMIT 1");
            } else {
                App::db()->query("UPDATE `user_basket_goods` SET `user_basket_good_quantity` = `user_basket_good_quantity` - 1 WHERE `user_basket_id` = '" . $this->id . "' AND `gift_id` = '" . intval($giftId) . "' LIMIT 1");
            }
            
            $r    = App::db()->query("SELECT g.`gift_type` FROM `gifts` AS g WHERE g.`gift_id` = '$giftId'");
            $gift = $r->fetch();
    
            if ($gift['gift_type'] == 'diskont_card')
            {
                $r = App::db()->query("UPDATE `user_baskets` SET `user_basket_discount` = '0' WHERE `user_basket_id` = '" . $this->id . "' LIMIT 1");
            }
        }
        
        /**
         * Переносим корзину из куки в базу (maryjane.ru)
         * Выполняется при подтверждении заказа (последний шаг)
         * @return 
         */
        function saveBasket() 
        {
            $boxCost   = getVariableValue('boxCost');
            $pcPrinted = getVariableValue('printCost0');
            $pcClean   = getVariableValue('printCost10');
            
            $total_q = $bb = $tprice = 0;

            $goods = array();
            $gifts = array();

            $sth1 = App::db()->prepare("UPDATE
                                            `user_basket_goods`
                                       SET
                                            `user_basket_good_price`         = :price, 
                                            `user_basket_good_discount`      = :discount, 
                                            `user_basket_good_discount_type` = :discount_type, 
                                            `user_basket_good_quantity`      = :quantity, 
                                            `user_basket_good_total_price`   = :tprice_rub, 
                                            `user_basket_good_box`           = :box, 
                                            `user_basket_good_print_cost`    = :print_cost
                                      WHERE 
                                            `user_basket_good_id` = :id
                                      LIMIT 1");

            $sth2 = App::db()->prepare("UPDATE `good_stock` SET `good_stock_inprogress_quantity` = `good_stock_inprogress_quantity` + :q WHERE `good_stock_id` = :id LIMIT 1");

            foreach ($this->basketGoods as $row)
            {
                $total_q += $row['quantity'];
                $tprice  += $row['tprice'];
                $bb      += $row['tprice'];
                
                $sth1->execute([
                    'price'         => $row['price'], 
                    'discount'      => $row['discount'], 
                    'discount_type' => $row['discount_type'], 
                    'quantity'      => $row['quantity'], 
                    'tprice_rub'    => $row['tprice_rub'], 
                    'box'           => ($row['box'] == 1 ? 1 : ((($row['cat_parent'] > 1 || $row['style_category'] == 17) ? -1 : ((self::$deliveryTypes[$this->user_basket_delivery_type]['boxed']) ? 1 : -1)))), 
                    'print_cost'    => (($row['gsGoodId'] == 0 ? $pcClean : $pcPrinted) * $row['quantity']),
                    'id'            => intval($row['user_basket_good_id']),
                ]);
                
                // пересчитываем количество зарезервированных товаров
                $sth2->execute([
                    'q' => $row['quantity'],
                    'id' => $row['good_stock_id'],
                ]);
                
                if ($row['box'] > 0)
                {
                    $this->log('box_comment', 'упаковать в коробку позицию #' . $row['user_basket_good_id'], $row['user_basket_good_id']);
                }

                $row['style_print_block'] = unserialize($row['style_print_block']);
                
                if (in_array($row['cat_parent'], array(22, 60, 59)) && $row['style_print_block']['wall']['w'] > 0 && $row['style_print_block']['wall']['h'] > 0)
                {
                    $wallpappers[] = mainUrl . '/ajax/getWallpapper/' . $row['good_id'] . '/' . $row['style_slug'] . '/';
                }
                
                $goods[] = $row;
                
                // футболка самоделка сделанная неавторизванным пользователем
                if ($row['good_status'] == 'customize' && empty($row['good_user'])) 
                {
                    App::db()->query("UPDATE `goods` SET `user_id` = '" . $this->user_id . "' WHERE `good_id` = '" . $row['good_id'] . "' LIMIT 1");
                }
                
                if ($row['good_status'] != 'customize')
                {
                    if (!$author_thanks[$row['good_user']]) {
                        $author_thanks[$row['good_user']] = array(
                            'user_login' => $row['user_login'],
                            'user_city_name' => $row['user_city_name'],
                            'user_avatar' => $row['user_avatar'],
                            'user_avatar_medium' => $row['user_avatar_medium'],
                        );
                    }
                    
                    $author_thanks[$row['good_user']]['author_payment'] += $row['author_payment'];
                }

                //$this->user->addSelected($row['good_user']);
                
                if ($row['style_category'] == 86 || $row['style_category'] == 88)
                {
                    $patterns[] = $row;
                }
            }

            foreach ($this->basketGifts as $pos)
            {
                $row = App::db()->query("SELECT `gift_name`, `gift_price` AS price, `gift_discount` AS discount, `gift_type` FROM `gifts` WHERE `gift_id` = '" . $pos['gift_id'] . "'")->fetch();
                
                if ($row['gift_type'] == 'certifikat' && empty($row['price']))
                    if (!empty($pos['price']))
                        $row['price'] = $pos['price'];
                    else
                        continue;
                else
                    $row['price'] = round($row['price'] * (1 - $row['discount'] / 100));
                    
                $tprice += $row['total'] = $row['price'] * $pos['q'];

                $row['quantity'] = $pos['q'];
                
                App::db()->query("UPDATE 
                                        `user_basket_goods` 
                                    SET
                                        `user_basket_good_price`       = '" . $row['price'] . "',
                                        `user_basket_good_discount`    = '" . $row['discount'] . "',
                                        `user_basket_good_quantity`    = '" . $pos['q'] ."', 
                                        `user_basket_good_total_price` = '" . $row['total'] . "'
                                    WHERE
                                        `user_basket_good_id` = '" . intval($row['user_basket_good_id']) . "'
                                    LIMIT 1");

                $gifts[] = $pos;
            }

            // Регистрируем переход по информеру, если такой был
            if ($this->user_basket_status == 'active' && !empty($_COOKIE['informer']) && !in_array($this->user_id, array(63250)))
            {
                $informercookie = unserialize(stripslashes($_COOKIE['informer']));
                $infocomment = 'Заказ произведен после клика по баннеру #' . $informercookie['informerid'] . ' юзера ' . $informercookie['informeruser'] . "; реферрер: " . $informercookie['referrer'] . "; IP: " . $informercookie['refip'];
                
                if (!empty($informercookie['clickId'])) 
                {
                    try
                    {
                        App::db()->query("INSERT 
                                     INTO `informer_action_log` 
                                     SET 
                                        `informer_action_type` = 'order', 
                                        `informer_id` = '" . $informercookie['informerid'] . "', 
                                        `informer_action_target` = '" . $this->id . "', 
                                        `informer_action_comment` = '" . $infocomment . "',
                                        `informer_click_id` = '" . $informercookie['clickId'] . "'");
                                        
                        $i = new informer($informercookie['informerid']);
                        $i->informer_last_ordered = NOW;
                        $i->informer_orders++;
                        $i->save();
                    }
                    catch (Exception $e)
                    {
                        printr($e, 1);
                    }
                }
            }
            
            
            // Меняем статус корзины
            // Если заказ оплачен полностью бонусами
            if ($this->user_basket_payment_partical >= $tprice + $this->user_basket_delivery_cost)
                $status = 'accepted';
            else
                $status = 'ordered';
            
            $this->basketChange(array(
                'user_basket_status' => $status, 
                'user_basket_date'   => NOW, 
                'user_id'            => $this->user_id,
                'user_basket_domain' => $this->user_basket_domain,
                'user_basket_dealer' => ($this->user->meta->dealer_status == 'active') ? 1 : -1
            ));


            if ($this->user->meta->dealer_transfer)
            {
                $this->log('transfer', $this->user->meta->dealer_transfer);
            }
            
            if ($this->user->meta->givegifts)
            {
                //$this->log('admin_comment', 'Регистрация по акции на главной');
                $this->user->delMeta('givegifts');
            }
            
            if ($this->user->meta->printer_comment)
            {
                $this->log('printer_comment', $this->user->meta->printer_comment);
            }

            /*
             * ОТПРАВКА ПИСЬМА С ДАННЫМИ О ЗАКАЗЕ
             */
            $reparray['deliveryAddress'] = countryId2countryName($this->MJbasket['address']['country']) . ((!empty($this->MJbasket['address']['city'])) ? ', г. ' . cityId2name($this->MJbasket['address']['city']) : '') . ((!empty($this->MJbasket['address']['address'])) ? ', ул. ' . $this->MJbasket['address']['address'] : '');

            if (!empty($this->MJbasket['address']['postal_code'])) $reparray['deliveryAddress'] .= ', (' . $this->MJbasket['address']['postal_code'] . ')';

            if ($this->MJbasket['address']['region'] == 'moscow' || $this->MJbasket['address']['region'] == 'nearmoscow' || $this->MJbasket['address']['region'] == 'metro')
            {
                if (!empty($this->MJbasket['address']['raion'])) $reparray['deliveryAddress'] .= ', р. ' . raionId2raionName($this->MJbasket['address']['raion']);
                if (!empty($this->MJbasket['address']['metro'])) $reparray['deliveryAddress'] .= ', м. ' . metroId2metroName($this->MJbasket['address']['metro']);
            }
            
            if (!empty($this->MJbasket['address']['delivery_point']))
            {
                try
                {
                    $dp = new deliveryPoint($this->MJbasket['address']['delivery_point']);
                }
                catch (Exception $e) {}
            }
            
            $bb -= $this->user_basket_payment_partical;

            $reparray['order']            = $this;
            $reparray['comment']          = (!empty($this->MJbasket['address']['comment'])) ? $this->MJbasket['address']['comment'] : '-';
            $reparray['orderStatus']      = self::$orderStatus[$status];
            $reparray['phone']            = $this->MJbasket['address']['phone'];
            $reparray['orderPhoneNumber'] = substr($this->MJbasket['address']['phone'], -4);
            $reparray['orderNumber']      = $this->id;
            $reparray['deliveryType']     = self::$deliveryTypes[$this->user_basket_delivery_type]['title'] . (($dp) ? ' (' . $dp->address . '. ' . $dp->schema . ')' : '');
            $reparray['deliveryCost']     = $this->user_basket_delivery_cost;
            $reparray['paymentType']      = self::$paymentTypes[$this->user_basket_payment_type]['title'];
            $reparray['particalPay']      = $this->user_basket_payment_partical;
            $reparray['orderSum']         = $tprice;
            $reparray['total']            = $tprice + $this->user_basket_delivery_cost - $this->user_basket_payment_partical;
            
            if ($this->user) {
                $reparray['user']          = $this->user;
                $reparray['buyerDiscount'] = $this->user->buyerLevel2discount();
                $reparray['bonusBack']     = ceil(($bb / 100) * $reparray['buyerDiscount']);
            }
            
            $reparray['goods']            = $goods;
            $reparray['gifts']            = $gifts;
            $reparray['wallpappers']      = $wallpappers;
            $reparray['author_thanks']    = $author_thanks;
            
            App::mail()->send(array($this->user_id), $this->user_basket_domain == 'MJbasket' ? 329 : 330, $reparray, '', '', $this->user_basket_domain == 'MJbasket' ? 'noreplay@maryjane.ru' : 'noreplay@allskins.ru');

            /**
             * отправка смс
             */
            if (is_a($this->user, 'user') && $this->user->smsInfo() && !empty($this->MJbasket['address']['phone']) && !in_array($this->user_id, array(96976, 190169)))
            {
                // время максимального резерва заказа
                if ($this->user_basket_delivery_type == 'user')
                    $maxreserv = '10 дней';
                else
                    if ($this->user_basket_payment_type == 'cash')
                        $maxreserv = '10 дней';
                    elseif ($this->user_basket_payment_type == 'sberbank')
                        $maxreserv = '7 дней';
                    else
                        $maxreserv = '3 дня';
                
                
                // телефон обратной связи
                $phone = str_replace(array(' ','(', ')', '-'), '', ((($this->address['region'] == 'russia' && $this->address['city'] == 1) || $this->address['region'] == 'user' || $this->address['region'] == 'moscow') ? getVariableValue('contactPhone1') : getVariableValue('contactPhoneNonMoscow')));
                
                // если это обмен
                if ($this->logs['set_mark'])
                {
                    switch ($this->user_basket_delivery_type) 
                    {
                        case 'user':
                            
                            $smstext = 'Ваш заказ на обмен #' . substr($this->MJbasket['address']['phone'], -4) . ' принят и ожидает подтверждения менеджера!';
                            
                            break;
                            
                        case 'deliveryboy':
                        case 'metro':
                            
                            $smstext = 'Ваш заказ на обмен #' . substr($this->MJbasket['address']['phone'], -4) . ' принят и ожидает подтверждения менеджера!';
                                
                            // брак
                            if ($this->logs['set_mark'][0]['result'] == 'exchange_brak')
                                $smstext .= ' Обмен бесплатный.';
                            // не брак
                            else
                                $smstext .= ' К оплате ' . $this->user_basket_delivery_cost . ' ' . ($this->currency == 'rub' ? 'р' : '$') . '.';
                            
                            
                            break;
                    }
                }
                else
                {
                    switch ($this->user_basket_delivery_type) 
                    {
                        case 'user':
                        case 'deliveryboy':
                        case 'metro':
                            
                            if ($this->user_basket_payment_type == 'cash' || $this->user_basket_payment_type == 'creditcard_onplace' || $this->user_basket_payment_confirm == 'true')
                                $smstext = 'Заказ ' . substr($this->MJbasket['address']['phone'], -4) . ' на сумму ' . ($tprice + $this->user_basket_delivery_cost - $this->user_basket_payment_partical) . ($this->currency == 'rub' ? 'р' : '$') . '. ожидает проверки менеджера!';
                            
                            if ($this->user_basket_payment_type != 'cash' && $this->user_basket_payment_type != 'cashon' && $this->user_basket_payment_type != 'creditcard_onplace' && $this->user_basket_payment_confirm == 'false')
                                $smstext = 'Заказ ' . substr($this->MJbasket['address']['phone'], -4) . ' принят. Ожидаем оплату в размере ' . ($tprice + $this->user_basket_delivery_cost - $this->user_basket_payment_partical) . ' ' . ($this->currency == 'rub' ? 'р' : '$') . '.';
                            
                            $smstext .= ' ' . $phone;
                            
                            break;
                        
                        case 'post':
                            
                            if ($this->user_basket_payment_type != 'cash' && $this->user_basket_payment_type != 'cashon' && $this->user_basket_payment_confirm == 'false')
                                $smstext = 'Заказ ' . substr($this->MJbasket['address']['phone'], -4) . ' принят! Ожидаем оплату в размере ' . ($tprice + $this->user_basket_delivery_cost - $this->user_basket_payment_partical) . ' ' . ($this->currency == 'rub' ? 'р' : '$') . '. Тел: ' . $phone;
                            
                            break;
                        
                        default:
                            
                            if ($this->user_basket_payment_type != 'cash' && $this->user_basket_payment_confirm == 'false')
                                $smstext = 'Заказ ' . substr($this->MJbasket['address']['phone'], -4) . ' принят! Ожидаем оплату ' . ($tprice + $this->user_basket_delivery_cost - $this->user_basket_payment_partical) . ' ' . ($this->currency == 'rub' ? 'р' : '$') . '. ' . ($this->user_basket_delivery_type != 'deliveryboy' ? ' Резерв ' . $maxreserv : '');
                            
                            if ($this->user_basket_payment_type == 'cashon')
                                $smstext = 'Заказ ' . substr($this->MJbasket['address']['phone'], -4) . ' на сумму ' . ($tprice + $this->user_basket_delivery_cost - $this->user_basket_payment_partical) . ' ' . ($this->currency == 'rub' ? 'р' : '$') . '. ожидает проверки. Тел: ' . $phone;
                            
                            break;
                    }
                }

                if ($smstext)
                {
                    try
                    {
                        $sms = new sms(SMSuser, SMSpassword, SMSsender);
                        
                        if ($this->user_basket_domain == 'ASbasket')
                            $sms->setSender('allskins.ru');
                        
                        if ($this->user_id == 96976)
                            $sms->setSender('Booandstu');
                        
                        $r = $sms->send($this->MJbasket['address']['phone'], $smstext);
                        
                        $this->log('sendsms', $smstext, $r);
                    }
                    catch (Exception $e) 
                    {
                        //printr($e, 1);
                    }
                }
            }

            // Сохраняем адрес в базу
            if ($this->MJbasket['address'])
            {
                unset($this->MJbasket['address']['status']);
                unset($this->MJbasket['address']['comment']);
                unset($this->MJbasket['address']['city_name']);
                //unset($this->MJbasket['address']['skype']);
                                
                // Проверка на уникальность адреса
                // построение хеша адреса
                $fs   = implode("`, `", array_keys($this->MJbasket['address']));
                $vs   = implode("','", $this->MJbasket['address']);

                $hash = substr(md5(implode('', array(
                    $this->user_id,
                    $this->MJbasket['address']['region'],
                    $this->MJbasket['address']['name'],
                    $this->MJbasket['address']['postal_code'],
                    $this->MJbasket['address']['country'],
                    $this->MJbasket['address']['kray'],
                    $this->MJbasket['address']['city'],
                    $this->MJbasket['address']['raion'],
                    $this->MJbasket['address']['address'],
                    $this->MJbasket['address']['delivery_point'],
                    $this->MJbasket['address']['metro'],
                    $this->MJbasket['address']['phone'],
                    $this->MJbasket['address']['skype']
                ))), 0, 30);

                $r = App::db()->query("INSERT IGNORE INTO `user_basket_address` (`$fs`, `user_id`, `hash`) VALUES ('$vs', '" . $this->user_id . "', '" . $hash . "') ON DUPLICATE KEY UPDATE `order_date` = '" . NOW . "'");
                
                $this->basketChange(array(
                    'user_basket_delivery_address' => App::db()->lastInsertId(),
                ));
            }

            
            // наценка на наложенный платёж
            if ($this->user_basket_delivery_type == 'post')
            {
                $pt_margin = getVariableValue($this->user_basket_payment_type . '_margin');
                
                if (!empty($pt_margin)) {
                    $this->user_basket_delivery_cost += $pt_margin;
                    $this->basketChange(array('user_basket_delivery_cost' => $this->user_basket_delivery_cost));
                }
            }

            $this->log('change_status', $status);
            
            // Если источник заказа переход по ссылке из письма, фиксируем это в шаблоне
            if (strpos($this->user_basket_source, 'mailsender_') !== false) {
                $mtpl = (int) substr($this->user_basket_source, strpos($this->user_basket_source, 'mailsender_') + 11);
                if ($mtpl > 0) {
                    $sth = App::db()->prepare("UPDATE `" . mailTemplate::$dbtable . "` SET `mail_template_orders` = `mail_template_orders` + 1, `mail_template_orders_sum` = `mail_template_orders_sum` + :sum  WHERE `mail_template_id` = :id LIMIT 1");
                    $sth->execute(['id' => $mtpl, 'sum' => $tprice + $this->user_basket_delivery_cost]);
                }
            }
            
            if ($this->user_id == 196620 || $this->user_id == 213553 || $this->user_id == 211557 || $this->user->user_email == 'napichimne1@yandex.ru')
            {
                App::mail()->send(array(($this->user_id == 211557 ? 108933 : 6199)), 557, array(
                    'user' => $this->user_id,
                    'basket' => $this->id,
                    'quantity' => $total_q,
                    'sum' => $tprice + $this->user_basket_delivery_cost,
                ));
            }
            
            // если обмен, то уведомление валерии
            /*
            if ($this->logs['set_mark'])
            {
                App::mail()->send(array(63250), 527, array(
                    'basket' => $this->id,
                    'from' => 'с сайта',
                    'reasone' => self::$exchangeReasons[$this->logs['set_mark'][0]['result']]['title'],
                ));
            }
            */
            
            // если в заказе присутствуют паттерны
            if ($patterns)
            {
                $now = new DateTime;
                
                // откладываем автоматическое аннулирование
                $this->log('delaypayment', $now->modify( '1 month' )->format('Y-m-d'));
            }
            
            $this->user->setSessionValue(['user_basket_id' => 0]);
            
            // подчищаем все сессии, которые прописались за этим заказом
            App::db()->query("UPDATE `sessions` SET `user_basket_id` = '0' WHERE `user_basket_id` = '" . $this->id . "'");
            
            return $tprice + $this->user_basket_delivery_cost - $this->user_basket_payment_partical;
        }
        
        /**
         * Получить финальную сумму корзины после сохранение
         * (из БД) 
         */
        function getBasketFinalGoodsSum() 
        {
            $foo = App::db()->query("SELECT SUM(`user_basket_good_total_price`) AS s FROM `user_basket_goods` WHERE `user_basket_id` = '" . $this->id . "'")->fetch();
            return $foo['s'];
        }

        function getuser()
        {
            if (!empty($this->user_id)) 
            {
                $this->info['user'] = new user($this->user_id);
                return $this->info['user'];
            }
        }
    
        /**
         * Получить дату доставки курьером/самовывозом для данной категории носителя
         * 
         * @param string $category категория носителя
         * @param string $dt тип доставки
         * @param string $short укороченная или полная форма записи даты 
         * @param boolean $sale готовая позиция с распродажи или нужно печатать
         * @return string дата доставки
         */
        public function getStyleDeliveryDate($category, $dt = 'deliveryboy', $short = false, $sale = false)
        {
            // НГ праздники
            if (date('z') < 10) {
                return '11 января';
            }
            
            $hour   = (int) date('G');
            $minute = (int) date('i');
            
            $day    = (int) date('w');
            $year   = (int) date('Y');
            $month  = (int) date('m');
            
            $dday   = (int) date('d'); // число

            $now1 = new DateTime;
            $now = new DateTime;
            
            // если позиция с распродажи
            if ($sale) 
            {
                // пн - пт
                if ($day >= 1 && $day <= 4) {
                    // после 17:00
                    if ($hour >= 17)
                        $now->modify('1 day');  // на след день
                } else {
                    if ($day == 5) // пт
                        if ($hour >= 17)
                            $now->modify('3 day'); // пн
                    elseif ($day == 6) // суб
                        $now->modify('2 day'); // пн
                    else // вск
                        $now->modify('1 day'); // пн  
                }
            }
            else
            {
                switch ($category) 
                {
                    // полная запечатка
                    case 'patterns':
                    case 'patterns-sweatshirts':
                    case 'patterns-tolstovki':
                    case 'body':
                    case 'bomber':
                    //case 'patterns-bag':
                    //case 'textile':
                    //case 'pillows':
                        
                        $now->modify('7 day');
                        
                        break;
    
                    // рюкзак
                    case 'bag':
                        
                        $now->modify('10 day');
                        
                        break;
    
                    // винил
                    // доставка только в четверг
                    case 'phones':
                    case 'laptops':
                    case 'touchpads':
                    case 'ipodmp3':
                    case 'auto':
                    case 'moto':
                    case 'boards':
                    case 'stickers':
                    case 'poster':
                    case 'bumper':
                    case 'stickerset':
                       
                        if (in_array(date('m-d', time()), array('04-29', '04-30', '05-01', '05-02', '05-03', '05-04'))) {
                            return '5 мая';
                        }
                       
                        if ($day == 1) // пн
                            $now->modify('3 day'); // чт
                        elseif ($day == 2) // вт
                            $now->modify('2 day'); // чт
                        elseif ($day == 3) // ср 
                            if ((int) $hour <= 15)
                                $now->modify('1 day'); // чт
                            else
                                $now->modify('8 day'); // сл чт
                        elseif ($day == 4) // чт
                            $now->modify('7 day'); // сл чт
                        elseif ($day == 5) // пт
                            $now->modify('6 day'); // чт
                        elseif ($day == 6) // суб
                            $now->modify('5 day'); // чт
                        else // вск
                            $now->modify('4 day'); // чт
                       
                        /*
                        if ($day == 1) // пн
                            if ((int) $hour <= 15)
                                $now->modify('1 day'); // вт
                            else
                                $now->modify('3 day'); // чт
                        elseif ($day == 2) // вт
                            $now->modify('2 day'); // чт
                        elseif ($day == 3) // ср 
                            if ((int) $hour <= 15)
                                $now->modify('1 day'); // чт
                            else
                                $now->modify('6 day'); // вт + 1
                        elseif ($day == 4) // чт
                            $now->modify('5 day'); // вт + 1
                        elseif ($day == 5) // пт
                            $now->modify('4 day'); // вт + 1
                        elseif ($day == 6) // суб
                            $now->modify('3 day'); // вт + 1
                        else // вск
                            $now->modify('2 day'); // вт + 1
                        */    
                        break;
    
                    // кружки | чехлы
                    case 'cup':
                    case 'cases':
                        
                        $workend = 20; // часов
                        $workend_min = '00'; // минут
                        
                        if ($hour >= 0 && ($hour . $minute < $workend . $workend_min))
                        {
                            if ($day >= 1 && $day <= 5)
                                $now->modify('1 day'); // завтра
                            elseif ($day == 6) // суб
                                $now->modify('3 day'); // вт
                            else // вск
                                $now->modify('2 day'); // вт
                        }
                        else 
                        {
                            if ($day >= 1 && $day <= 4)
                                $now->modify('2 day'); // послезавтра
                            elseif ($day == 5) // пят
                                $now->modify('4 day'); // вт
                            elseif ($day == 6) // суб
                                $now->modify('3 day'); // вт 
                            else // вск
                                $now->modify('2 day'); // вт
                        }
                        
                        break;
    
                    // одежда
                    case 'textile':
                    case 'pillows':
                    default:
                        
                        if (in_array(date('m-d', time()), array('04-29', '04-30'))) {
                            return '4 мая';
                        }
                        
                        if (in_array(date('m-d', time()), array('05-06', '05-07', '05-08', '05-09', '05-10'))) {
                            return '11 мая';
                        }
                        
                        $workend = 18; // часов
                        $workend_min = '30'; // минут
                        
                        if ($hour >= 0 && ($hour . $minute < $workend . $workend_min))
                        {
                            if ($dt == 'user') {
                                if ($day >= 1 && $day <= 5) // пн - тп
                                    $now->modify('0 day'); // сегодня
                                elseif ($day == 6) // сб
                                    $now->modify('2 day'); // пн
                                else
                                    $now->modify('1 day'); // пн
                            } else {
                                if ($day >= 1 && $day <= 5) // пн - тп
                                    $now->modify('1 day'); // завтра
                                elseif ($day == 6) // сб
                                    $now->modify('2 day'); // пн
                                else
                                    $now->modify('1 day'); // пн
                            }
                        }
                        else 
                        {
                            if ($dt == 'user') {
                                if ($day >= 1 && $day <= 4)
                                    $now->modify('1 day'); // послезавтра
                                elseif ($day == 5)
                                    $now->modify('3 day'); // пн
                                elseif ($day == 6)
                                    $now->modify('2 day'); // пн
                                else
                                    $now->modify('1 day'); // пн
                            } else {
                                if ($day >= 1 && $day <= 4)
                                    $now->modify('2 day'); // послезавтра
                                elseif ($day == 5)
                                    $now->modify('3 day'); // пн
                                elseif ($day == 6)
                                    $now->modify('2 day'); // пн
                                else
                                    $now->modify('2 day'); // вт
                            }
                        }
                        
                        break;
                }
            }

            // если дата доставки выпала на новогодние каникулы
            if (date('z', strtotime($now->format('Y-m-d'))) < 10) {
                return datefromdb2textdate((date('Y') + 1) . '-01-11');
            }
            
            // если дата доставки выпала на праздники
            if (in_array(date('m-d', strtotime($now->format('Y-m-d'))), array('02-23', '03-08'))) {
                $now->modify('1 day');
            }
            
            if (in_array(date('m-d', strtotime($now->format('Y-m-d'))), array('03-07'))) {
                $now->modify('2 day');
            }

            $date = datefromdb2textdate($now->format('Y-m-d'), 0, 1);
            
            if (!$short)
            {
                if ($now1->diff($now)->format('%a') == 1) {
                    $date .= ', завтра'; 
                }
                
                if ($now1->diff($now)->format('%a') == 2) {
                    $date .= ', послезавтра'; 
                }
            }
            
            return $date;
        }
    
        /**
         * получить
         *  - ожидаемое время доставки заказа клиенту сколько-то (с учётом выходных и времени заказа)
         *  - (для самовывоза) время готовности заказа
         * 
         * @param $dt (string) - тип доставки
         * 
         * @return date - дата ожидаемой доставки
         */
        function deliveryTime($dt = null)
        {
            // час после скольки доставка заказа сдвигается на один день 
            $delim = 2030;
            
            if ($this->user_basket_status == 'active')
            {
                $hour   = (int) date('G');
                $minute = (int) date('i');
                
                $day    = (int) date('w');
                $year   = (int) date('Y');
                $month  = (int) date('m');
                
                $dday   = (int) date('d'); // число
                
            }
            elseif ($this->user_basket_status == 'delivered' && in_array($this->user_basket_delivery_type, array('IMlog_self', 'IMlog', 'dpd', 'dpd_self', 'post', 'baltick'))) 
            {
                $hour   = date('G', strtotime($this->user_basket_delivered_date));
                $day    = date('w', strtotime($this->user_basket_delivered_date));
                $year   = (int) date('Y', strtotime($this->user_basket_delivered_date));
                $month  = (int) date('m', strtotime($this->user_basket_delivered_date));
                $minute = (int) date('i', strtotime($this->user_basket_delivered_date));
                $dday   = (int) date('d', strtotime($this->user_basket_delivered_date)); // число
            }
            else
            {
                $hour   = date('G', strtotime($this->user_basket_date));
                $day    = date('w', strtotime($this->user_basket_date));
                $year   = (int) date('Y', strtotime($this->user_basket_date));
                $month  = (int) date('m', strtotime($this->user_basket_date));
                $minute = (int) date('i', strtotime($this->user_basket_date));
                $dday   = (int) date('d', strtotime($this->user_basket_date)); // число
            }
            
            if (empty($dt))
                $dt = $this->user_basket_delivery_type;

            switch ($dt)
            {
                /**
                 * курьер до квартиры / встреча в метро
                 */
                case 'metro':
                case 'deliveryboy':
                    break;

                    
                // 3ий рабочий день
                case 'IMlog_self':

                    if ($day == 6)                  // суббота
                        return date('Y-m-d', mktime(1, 0, 0, $month, $dday, $year) + 4 * 24 * 3600);
                    if ($day == 0)                  // вскр
                        return date('Y-m-d', mktime(1, 0, 0, $month, $dday, $year) + 3 * 24 * 3600);
                    elseif ($day > 0 && $day < 3)   // пн -вт
                        return date('Y-m-d', mktime($hour, $minute, 0, $month, $dday, $year) + (3 * 24 * 3600));
                    elseif ($day >= 3 && $day <= 5) // вт - пт
                        return date('Y-m-d', mktime($hour, $minute, 0, $month, $dday, $year) + (5 * 24 * 3600));

                    break;

                /**
                 * 
                 */
                case 'IMlog':   
                case 'dpd';

                    if (count($this->address) == 0)
                    {
                        $this->getAddress();
                    }

                    $r = App::db()->query(sprintf("SELECT `cost`, `time1`, `time2`, `cashon` FROM `delivery_services` WHERE `city_id` = '%d' AND `service` = '%s' LIMIT 1", $this->address['city_id'], addslashes($dt)));

                    if ($r->rowCount() == 1)
                    {
                        $info = $r->fetch();
                    }
                    
                    $i = 0;
                    $k = ((intval($hour . $minute) < $delim && $day != 5 && $day != 6) ? 0 : 1) + 1;
                    
                    // отсчитываем нужное кол-во рабочих дней
                    while ($i < $info['time2'])
                    {
                        $next = mktime($hour, $minute, 0, $month, $dday, $year) + ($k * 24 * 3600);
                        
                        if (date('w', $next) != 6 && date('w', $next) != 0)
                            $i++;
                        
                        if ($i == $info['time2'])
                            continue;
                        
                        $k++;
                    }
                
                    return date('Y-m-d', mktime($hour, $minute, 0, $month, $dday, $year) + ($k * 24 * 3600));
                
                    break;
                
                /**
                 * балтийский курьер (доставка в Санкт-Петербург)
                 */
                case 'baltick':
                
                    if ($hour >= 0 && intval($hour . $minute) < $delim)
                    {
                        if ($day >= 1 && $day <= 4)
                            $time = mktime($hour, $minute, 0, $month, $dday, $year) + (1 * 24 * 3600); // пн - чт -> следующий день
                        elseif ($day == 5 || $day == 6)
                            $time = mktime($hour, $minute, 0, $month, $dday, $year) + (3 * 24 * 3600); // пят -> понедельник, сб -> вторник
                        else
                            $time = mktime($hour, $minute, 0, $month, $dday, $year) + (2 * 24 * 3600); // вск  -> вторник
                    }
                    else 
                    {
                        if ($day >= 1 && $day <= 4)
                            $time = mktime($hour, $minute, 0, $month, $dday, $year) + (2 * 24 * 3600); // пн - чт -> послезавтра'
                        elseif ($day == 5 || $day == 6)
                            $time = mktime($hour, $minute, 0, $month, $dday, $year) + (3 * 24 * 3600); // пят -> понедельник, сб -> вторник
                        else
                            $time = mktime($hour, $minute, 0, $month, $dday, $year) + (2 * 24 * 3600); // вск  -> вторник
                    }
                    
                    return date('Y-m-d', $time);
                
                    break;
                
                // 2 недели
                case 'post':
                    
                    return date('Y-m-d', mktime($hour, $minute, 0, $month, $dday, $year) + (14 * 24 * 3600));
                    
                    break;
                    
                default:
                case 'user':
                    break;
            }
        }

        /**
         * ожидаемое время доставки заказа клиенту 
         * (для самовывоза) время готовности заказа
         */
        function getDeliveryTime($dt = null, $full = false, $offset = 0)
        {
            if ($this->user_basket_status == 'active') {
                $hour   = (int) date('G');
                $minute = (int) date('i');
                
                $day    = (int) date('w');
                $year   = (int) date('Y');
                $month  = (int) date('m');
                
                $dday   = (int) date('d'); // число
            } else {
                $date   = $this->user_basket_date;
                $hour   = date('G', strtotime($date));
                $minute = (int) date('i', strtotime($date));
                $day    = date('w', strtotime($date));
                $year   = (int) date('Y', strtotime($this->user_basket_date));
                $month  = (int) date('m', strtotime($this->user_basket_date));
                
                if (!$dt)
                    $dt = $this->user_basket_delivery_type;
            }
            
            $workend = 16; // часов
            $workend_min = '00'; // минут
            
            // ищем в корзине паттерны, если они есть то срок доставки сдвигается на +5 дней
            foreach ($this->basketGoods as $p) 
            {
                if (in_array($p['category'], array('patterns', 'patterns-sweatshirts', 'patterns-tolstovki', 'body', 'bomber'/*, 'patterns-bag'*/, 'textile'))) {
                    $offset = 7;
                }
                
                if ($p['category'] == 'bag') {
                    $offset = 10;
                }
            }
            
            switch ($dt) 
            {
                case 'metro':
                case 'deliveryboy':
                
                    if ($this->user_basket_status != 'active')
                    {
                        $dd = $this->logs['deliverydate'][0];
                        $dt = $this->logs['admin_deliverytime'][0];
                
                        if (!empty($dd['result']))
                            $deliver_srok = datefromdb2textdate($dd['result']) . ' ' . ((!empty($dt['result'])) ? $dt['result'] : '');
                    }
                    
                    if (date('z') >= 363 || date('z') < 11)
                    {
                        $deliver_srok = '11 января*';
                    }
                    
                    
                    if (empty($deliver_srok))
                    {
                        if (!$offset)
                        {
                            if ($hour >= 0 && ($hour . $minute < $workend . $workend_min))
                            {
                                if ($day >= 1 && $day <= 4)
                                    $deliver_srok = datefromdb2textdate(date('Y-m-d', time() + 86400), 0, 1) . ', завтра' . (($full) ? ', ' . weekday2textweekday(date('w', time() + 86400)) : ''); // пн - чт
                                elseif ($day == 5)
                                    $deliver_srok = 'в понедельник'; // пят до 18
                                elseif ($day == 5)
                                    $deliver_srok = 'в понедельник'; // пят до 18
                                else
                                    $deliver_srok = 'во вторник'; // вск до 18
                            }
                            else 
                            {
                                if ($day >= 1 && $day <= 4)
                                    $deliver_srok = datefromdb2textdate(date('Y-m-d', time() + 86400 * 2), 0, 1) . ', послезавтра'; // пн - чт после 18
                                elseif ($day == 5)
                                    $deliver_srok = 'в понедельник'; // пят после 18
                                elseif ($day == 6)
                                    $deliver_srok = 'в понедельник'; // суб после 18
                                else
                                    $deliver_srok = 'во вторник'; // вск после 18
                            }
                        } else {
                            $deliver_srok = datefromdb2textdate(date('Y-m-d', time() + ($offset * 86400)), 0, 1);
                        }
                    }
                    
                    break;
                
                case 'post':
                    
                    $deliver_srok = '2-3 недели';
                    
                    break;
                
                case 'IMlog_self':
                case 'dpd_self':
                    
                    if ($this->user_basket_status != 'active' && $this->logs['deliverydate']) 
                    {
                        $dd = array_shift($this->logs['deliverydate']);
                        $dt = array_shift($this->logs['admin_deliverytime']);
                
                        if (!empty($dd['result'])) {
                            $deliver_srok = datefromdb2textdate($dd['result'], 1) . ' ' . ((!empty($dt['result'])) ? $dt['result'] : '');
                        }
                    }
                    else 
                    {
                        $sth = App::db()->prepare("SELECT ds.`time2`
                            FROM `delivery_services` ds, `delivery_points` dp
                            WHERE 
                                    dp.`id` = :dp
                                AND ds.`city_id` = dp.`city_id`");
                            
                        $sth->execute(array(
                            'dp' => $this->address['delivery_point'],
                        ));
                        
                        if ($ds = $sth->fetch())
                        {
                            $now   = new DateTime;
                            $now->modify($ds['time2'] . ' day');
                            
                            $deliver_srok = datefromdb2textdate($now->format('Y-m-d'), 1);
                        }
                    }
                    
                    break;
                    
                default:
                case 'user':
                
                    // пн - пт
                    /*
                    if ($day >= 1 && $day <= 5)
                    {
                        if ($hour >= 0 && $hour < 18)
                            $deliver_srok = 'сегодня с 18 до 21';
                        else
                            $deliver_srok = datefromdb2textdate(date('Y-m-d', time() + 86400), 0, 1) . ', завтра' . (($full) ? ', ' . weekday2textweekday(date('w', time() + 86400)) : '');
                    }
                    else
                    {
                        if ($hour >= 0 && $hour < 18)
                            $deliver_srok = 'сегодня с 18 до 21';
                        else
                            $deliver_srok = 'завтра с 13 до 21';
                    }
                    */
                    
                    if (date('z') >= 364)
                    {
                        $deliver_srok = 'сегодня до 15';
                    }
                    elseif (date('z') < 12)
                    {
                        $deliver_srok = '11 января';
                    } elseif (date('m-d') == '03-09') {
                        $deliver_srok = 'завтра с 18 до 19';
                    }
                    elseif (in_array(date('m-d'), array('05-01','05-02','05-03','05-04')))
                    {
                        $deliver_srok = '5 мая';
                    }
                    
                    if (empty($deliver_srok))
                    {
                        if ($day == 0 || $day == 6) {
                            $deliver_srok = 'понедельник с 18 до 19';
                        } else {
                            // пн - пт
                            //if ($day >= 1 && $day <= 5)
                            //{
                            if ($hour >= 0 && ($hour . $minute < $workend . $workend_min)) {
                                if (!$offset) {
                                    $deliver_srok = 'сегодня с ' . ((($day >= 1 && $day <= 5)) ? '18' : '11') . ' до 19';
                                } else {
                                    $deliver_srok = datefromdb2textdate(date('Y-m-d', time() + ($offset * 86400)), 0, 1) . (($full) ? ', ' . weekday2textweekday(date('w', time() + ($offset * 86400))) : '');
                                }
                            } else {
                                
                                if (!$offset) {
                                    $deliver_srok = datefromdb2textdate(date('Y-m-d', time() + 86400), 0, 1) . ', завтра' . (($full) ? ', ' . weekday2textweekday(date('w', time() + 86400)) : '');
                                } else {
                                    
                                    $deliver_srok = datefromdb2textdate(date('Y-m-d', time() + (($offset + 1) * 86400)), 0, 1) . (($full) ? ', ' . weekday2textweekday(date('w', time() + (($offset + 1) * 86400))) : '');
                                }
                            }
                        //}
                        //else
                        }
                    }
                    
                    break;
            }
            
            return $deliver_srok;
        }
        
        
        /**
         * получить 
         *  - ожидаемое время доставки заказа клиенту если в корзине есть гаждеты
         *  - (для самовывоза) время готовности заказа
         */
        function getDeliveryTimeGadgets($dt, $full = false)
        {
            if ($this->user_basket_status == 'active') {
                $date   = NOWDATE;
                $hour   = (int) date('G');
                $minute = (int) date('i');
                $day    = (int) date('w');
                $year   = (int) date('Y');
                $month  = (int) date('m');
                $dday   = (int) date('d'); // число
            } else {
                
                if ($this->user_basket_delivery_date != '0000-00-00 00:00:00') {
                    return datefromdb2textdate($this->user_basket_delivery_date, 3);
                }   
                             
                $date   = $this->user_basket_date;
                $hour   = date('G', strtotime($date));
                $minute = (int) date('i', strtotime($date));
                $day    = date('w', strtotime($date));
                $year   = (int) date('Y', strtotime($this->user_basket_date));
                $month  = (int) date('m', strtotime($this->user_basket_date));
            }
            
            $workend = 15; 
            $workend_min = '00';
            
            $vinil = false;
            
            // ищем в корзине паттерны, если они есть то срок доставки сдвигается
            foreach ($this->basketGoods as $p) 
            {
                if (in_array($p['category'], array('patterns', 'patterns-sweatshirts', 'patterns-tolstovki', 'body', 'bomber'/*, 'patterns-bag'*/, 'textile'))) {
                    $offset = 7;
                }
                
                if ($p['category'] == 'bag') {
                    $offset = 10;
                }
                
                if ($p['cat_parent'] > 1 && !in_array($p['category'], array('cases', 'cup'))) {
                    $vinil = true;
                }
            }
            
            if (date('z') >= 363 || date('z') < 10)
            {
                return '11 января';
            }
            

            if ($vinil) 
            {
                if (!$offset)
                {
                    $deliver_srok = $this->getStyleDeliveryDate('phones');
                }
                else 
                {
                    $deliver_srok = datefromdb2textdate(date('Y-m-d', time() + ($offset * 86400)), 0, 1);
                }
            }
            else 
            {
                switch ($dt) 
                {
                    case 'metro':
                    case 'deliveryboy':
    
                        if (empty($deliver_srok))
                        {
                            if (!$offset)
                            {
                                if ($hour >= 0 && ($hour . $minute < 2000))
                                {
                                    if ($day >= 1 && $day <= 4)
                                        $deliver_srok = datefromdb2textdate(date('Y-m-d', time() + 86400), 0, 1) . ', завтра' . (($full) ? ', ' . weekday2textweekday(date('w', time() + 86400)) : ''); // пн - чт
                                    elseif ($day == 5)
                                        $deliver_srok = 'в субботу'; // пят до 18
                                    else
                                        $deliver_srok = 'вторник'; // суб, вск до 18
                                }
                                else 
                                {
                                    if ($day >= 1 && $day <= 4)
                                        $deliver_srok = datefromdb2textdate(date('Y-m-d', time() + 86400 * 2), 0, 1) . ', послезавтра'; // пн - чт после 18
                                    elseif ($day == 5)
                                        $deliver_srok = 'вторник'; // пят после 17
                                    else
                                        $deliver_srok = 'вторник'; // суб, вск после 17
                                }
                            } else {
                                $deliver_srok = datefromdb2textdate(date('Y-m-d', time() + ($offset * 86400)), 0, 1);
                            }
                        }
                        
                        break;
                    
                    default:
                    case 'user':
                    
                        if (date('z') >= 364)
                        {
                            $deliver_srok = 'сегодня до 15';
                        }
                        elseif (date('z') < 8)
                        {
                            $deliver_srok = '8 января';
                        }
                        elseif (date('m-d') == '03-09')
                        {
                            $deliver_srok = 'завтра с 18 до 19';
                        }
                        
                        if (empty($deliver_srok))
                        {
                            if (!$offset)
                            {
                                // пн - чтв
                                if ($day >= 1 && $day < 5)
                                {
                                    if ($hour >= 0 && $hour < 20)
                                        $deliver_srok = 'сегодня с 18 до 19';
                                    else
                                        $deliver_srok = datefromdb2textdate(date('Y-m-d', time() + 86400), 0, 1) . ', завтра' . (($full) ? ', ' . weekday2textweekday(date('w', time() + 86400)) : '');
                                }
                                // пт
                                elseif ($day == 5) 
                                {
                                    if ($hour >= 0 && $hour < 17)
                                        $deliver_srok = 'сегодня с 18 до 19';
                                    else
                                        $deliver_srok = 'поненельник с 18 до 19';
                                }
                                else
                                    $deliver_srok = 'поненельник с 18 до 19';
                                
                            } else {
                                $deliver_srok = datefromdb2textdate(date('Y-m-d', time() + ($offset * 86400)), 0, 1) . (($full) ? ', ' . weekday2textweekday(date('w', time() + ($offset * 86400))) : '');
                            }
                        }
                        
                        break;
                }
            }
            
            return $deliver_srok;
        }
        
        /**
         * Список доступных для доставки заказа дней
         * @param string $dtype тип доставки
         */
        function getPosibleDeliverydates($dtype = null)
        {
            if ($this->user_basket_status == 'active') {
                
                $day = date('w');
                $dayofyear  = date('z');
                $now = time();
                
            } else {

                $date = $this->user_basket_date;
                $h = date('G', strtotime($date));
                $day  = date('w', strtotime($date));
                $dayofyear  = date('z', strtotime($date));
                
                $now = strtotime($date);
                
                if (date('z', strtotime($this->user_basket_date)) != date('z', time())) {
                    if ($this->logs['deliverydate']) {
                        $dates[date('d', strtotime($this->logs['deliverydate'][0]['result']))] = date('j/m/Y', strtotime($this->logs['deliverydate'][0]['result']));
                    }
                    
                    return $dates;
                }
            }
            
            $h = date('G');
            $minute = date('i');
            
            // массив с распределением товаров по категориям
            $this->products = array(); 

            if (count($this->basketGoods) > 0)
            {
                foreach ($this->basketGoods as $p) 
                {
                    // ищем в корзине паттерны, если они есть то срок доставки сдвигается на +X дней
                    if (in_array($p['category'], array('patterns', 'patterns-sweatshirts', 'patterns-tolstovki', 'body', 'bomber' /*, 'patterns-bag'*/, 'textile')) && empty($p['gsGoodId'])) {
                        $offset = 7;
                    }
                    
                    if ($p['cat_slug'] == 'bag') {
                        $offset = 10;
                    }
                    
                    // ищем гаджеты
                    if ($p['cat_parent'] > 1) {
                        if (in_array($p['category'], array('stickerset', 'cases', 'cup'))) {
                            $this->info['products']['forhome']++;
                            if ($p['category'] == 'cases') {
                                $this->info['products']['cases']++;
                            }
                        } else {
                            $this->info['products']['vinil']++;
                        }
                    } else {
                        if (in_array($p['category'], array('patterns', 'patterns-sweatshirts', 'patterns-tolstovki', 'textile', 'body', 'bomber'))) {
                            $this->info['products']['fullprint']++;
                        } else {
                            $this->info['products']['wear']++;
                        }
                    }
                }
            }
            
            // до скольки принимаем заказы на сегодня (для самовывоза), на завтра (для курьера)
            if ($this->products['vinil'] > 0) {
                $workend = 15;
                $workend_min = '00';
            } else {
                $workend = 16;
                $workend_min = '00';  
            }
            
            //printr($offset);
            //printr($this->products);
            //printr($dtype);
            
            foreach (range(0 + (int) $offset, 18) as $d) 
            {
                //printr($d . ': ' . date('m-d', time() + ($d * 3600 * 24)) . ' (' . date('z', time() + ($d * 3600 * 24)) . ') (' . date('w', time() + ($d * 3600 * 24)) . ')');
                
                // закрываем доставкку винила до 4 сентября 2017
                if ($this->products['vinil'] > 0 && date('Y') == 2017 && (int) date('z', time() + ($d * 3600 * 24)) < 246) {
                    // printr('kick 0 (' . (int) date('d', time() + ($d * 3600 * 24)) . ')');
                    continue;
                }
                
                // убираем новогодние праздники
                if (date('z', time() + ($d * 3600 * 24)) < 10) {
                    // printr('kick (' . date('z', time() + ($d * 3600 * 24)) . ')');
                    continue;
                }
                
                // убираем новогодние праздники
                if ($this->products['vinil'] && date('z', time() + ($d * 3600 * 24)) < 13) {
                    // printr('kick 1 (' . date('z', time() + ($d * 3600 * 24)) . ')');
                    continue;
                }
                
                // убираем праздничные дни
                if (in_array(date('m-d', time() + ($d * 3600 * 24)), array('02-23', '02-24', '03-08', '05-01', '05-08', '05-09'))) {
                    // printr('kick 2');
                    continue;
                }
                
                // убираем праздничные дни 2 (только для одежды)
                //if ($this->products['wear'] && in_array(date('m-d', time() + ($d * 3600 * 24)), array('05-06', '05-07', '05-08', '05-09', '05-10'))) {
                    //// printr('kick 2.1');
                    //continue;
                //}
                
                // убираем праздничные дни, в которые не возможна доставка курьером
                if (in_array($dtype, array('deliveryboy', 'metro')) && in_array(date('m-d', time() + ($d * 3600 * 24)), array('12-30', '12-31', '07-29', '08-20'))) {
                    // printr('kick 3');
                    continue;
                }
                
                // убираем праздничные дни, в которые не возможна доставка самовывозом
                if ($dtype == 'user' && in_array(date('m-d', time() + ($d * 3600 * 24)), array('11-04', '03-02', '07-10'))) {
                    // printr('kick 3.1');
                    continue;
                }
                
                // убираем субботу и воскресение
                if (date('w', time() + ($d * 3600 * 24)) == 6 || date('w', time() + ($d * 3600 * 24)) == 0) {
                    // printr('kick 4');
                    continue;
                }
                
                // убираем субботу
                if ($dtype == 'user' && date('w', time() + ($d * 3600 * 24)) == 6 && date('Y-m-d', time() + ($d * 3600 * 24)) != '2016-02-20') {
                    // printr('kick 5');
                    continue;
                }
                
                // винил, доставка только по четвергам
                // Заказ среда до 12:00 доставка четверг, после 12 заказ на след четверг
                if ($this->products['vinil'] && in_array($dtype, array('deliveryboy', 'metro'))) {
                    
                    // если в корзине только винил и сумма заказа более 5к
                    if ($this->basketSum >= 5000 && count($this->products) == 1) {
                    } else {
                        
                        // не четверг
                        if (date('w', time() + ($d * 3600 * 24)) != 4) {
                            // printr('kick 6');
                            continue;
                        }
                        
                        // выкидываем ближайший четверг
                        if ((date('w', time() + ($d * 3600 * 24)) == 4) && $d == 1 && date('H') > 12) {
                            // printr('kick 6.1');
                            continue;
                        }
                    }
                    
                }
                
                if (date('m-d', $now + ($d * 3600 * 24)) == '06-12') {
                    if ($dtype == 'user' && date('H') <= 17) {}
                    else {
                        // printr('kick 7');
                        continue;
                    }
                }
                
                // после закрытия смены доставка сегодня невозможна
                if (/*$dtype != 'user' && */$h . $minute > $workend . $workend_min && $d == 0) {
                    // printr('kick 7.0');
                    continue;
                }
                
                // для винила оставляем доставку только во вторник и четверг
                /*
                if ($this->products['vinil']) 
                {
                     // пн
                    if (date('w', time() + ($d * 3600 * 24)) == 1 && $d < 7)  
                    {
                         // printr('kick 7.1'); 
                         continue; 
                    } // вт  
                    elseif (date('w', time() + $d * 3600 * 24) == 2)
                    {
                        if (date('z', time() + $d * 3600 * 24) - $dayofyear >= 7 - date('N') || ($day == 1 && $h . $minute < $workend . $workend_min)) {} else {
                            // printr('kick 7.2'); 
                            continue; 
                        }
                    } // ср
                    elseif (date('w', time() + ($d * 3600 * 24)) == 3) 
                    {
                        if ($d + $day >= 7 || ($day == 1 && $h . $minute <= $workend . $workend_min)) {} else {
                            // printr('kick 7.3');
                            continue; 
                        }
                    } // чт
                    elseif (date('w', time() + ($d * 3600 * 24)) == 4)   
                    {
                        if ($d + $day >= 7 || $day == 1 || $day == 2 || (($day == 3 && $h . $minute <= $workend . $workend_min))) {} else {
                            // printr('kick 7.4');    
                            continue;
                        }
                    } // пт
                    elseif (date('w', time() + ($d * 3600 * 24)) == 5) 
                    {
                        // printr('kick 7.5');
                        if (date('z', time() + $d * 3600 * 24) - $dayofyear >= 7 - date('N') || ($day == 1 && $h . $minute < $workend . $workend_min) || $day == 2) {} else {
                            continue; 
                        }
                    } // сб, вск
                    elseif (in_array(date('w', time() + ($d * 3600 * 24)), array(6, 0)))
                    {
                        // printr('kick 7.6'); 
                        continue;
                    }
                }
                */
                
                // одежда или товары для дома  
                if (!$this->products['vinil']) 
                {
                    // пн - пт после закрытия смены - через день        
                    if ($day >= 1 && $day <= 5 && in_array($dtype, array('deliveryboy', 'metro')) && ($h . $minute > $workend . $workend_min) && ($now + ($d * 3600 * 24)) <= $now + 3600 * 24)
                    {
                        // printr('kick 8');
                        continue;
                    }
                    
                    // пн - пт до закрытия смены - курьер - через день      
                    if ($day >= 1 && $day <= 5 && in_array($dtype, array('deliveryboy', 'metro')) && ($h . $minute <= $workend . $workend_min) && $d == 0)
                    {
                        // printr('kick 9');
                        continue;
                    }
                }
                
                // пятница
                if ($day == 5)
                {
                    // курьер 
                    if (in_array($dtype, array('deliveryboy', 'metro'))) 
                    {
                        // есть наклейки
                        if ($this->products['vinil'] || $this->products['forhome'])
                        {
                            // после закрытия смены - вторник
                            if ($h . $minute >= $workend . $workend_min) {
                                $skip = 3;
                            // до закрытия смены - понедельник
                            } else {
                                $skip = 2;
                            }
                        // только одежда
                        } else {
                            if ($h . $minute >= $workend . $workend_min) {
                                $skip = 1;
                            } else {
                                $skip = 0;
                            }
                        }   
                    }
                    // самовывоз
                    elseif ($dtype == 'user') 
                    {
                        // гаджеты
                        //if ($this->products['vinil'] || $this->products['forhome']) {
                            // после 18:00 доставка на понедельник
                            if (date('Y-m-d') != '2016-02-19') {
                                if ($h >= 18) {
                                    $skip = 2;
                                }
                            }
                        //}
                    }
                }
                
                if ($skip) {
                    if ($now + ($d * 3600 * 24) <= $now + ($skip * 3600 * 24)) {
                        // printr('kick Skip: ' . $skip);
                        continue;
                    }
                }
                
                // суббота
                if ($day == 6)
                {
                    // тряпки - курьер - вторник
                    if ((!$this->products['vinil'] && !$this->products['forhome']) && in_array($dtype, array('deliveryboy', 'metro')) && ($now + ($d * 3600 * 24) <= $now + 2 * 3600 * 24)) {
                        // printr('kick 10');
                        continue;
                    }
                
                    // гаджеты - самовывоз - понедельник
                    if ($dtype == 'user' && ($now + ($d * 3600 * 24) <= $now + 3600 * 24) && date('Y-m-d') != '2016-02-20') {
                        // printr('kick 11');
                        continue;
                    }
                
                    // гаджеты - курьер 
                    if (($this->products['vinil'] || $this->products['forhome']) && in_array($dtype, array('deliveryboy', 'metro')) && ($now + ($d * 3600 * 24) <= $now + 2 * 3600 * 24)) {
                        // printr('kick 12');
                        continue;
                    }
                }
                
                // воскресение
                if ($day == 0)
                {
                    // тряпки - курьер - до 20.30 - понедельник, после - вторник
                    //if ((!$this->products['vinil'] && !$this->products['forhome']) && in_array($dtype, array('deliveryboy', 'metro')) && ($now + ($d * (($h . $minute <= $workend . $workend_min) ? 1 : 2) * 3600 * 24) <= $now)) {
                    // тряпки - курьер - вторник
                    if (!$this->products['vinil'] && !$this->products['forhome'] && in_array($dtype, array('deliveryboy', 'metro')) && ($now + ($d * 3600 * 24) <= $now + 3600 * 24)) {
                        // printr('kick 13');
                        continue;
                    }
                    
                    // тряпки
                    if ($dtype == 'user' && ($now + ($d * 3600 * 24) <= $now)) {
                        // printr('kick 14');
                        continue;
                    }
                    
                    // гаджеты
                    if (($this->products['vinil'] || $this->products['forhome']) && in_array($dtype, array('deliveryboy', 'metro')) && ($now + ($d * 3600 * 24) <= $now + 3600 * 24)) {
                        // printr('kick 15');
                        continue;
                    }
                }
                
                // в воскресение можно доставить только если самовывоз
                if (date('w', $now + ($d * 3600 * 24)) == 0)
                {
                    if ($dtype == 'user') {}
                    else {
                        // printr('kick 16');
                        continue;
                    }
                }
                
                //printr('yes');
                
                $dates[date('j', $now + ($d * 3600 * 24))] = date('j/m/Y', $now + ($d * 3600 * 24));
            }

            if (count($dates) == 0) {
                $dates[date('j', $now + ($d * 3600 * 24))] = date('j/m/Y', $now + (19 * 3600 * 24));
            }
            
            
            return (array) $dates;
        }
        
        
        /**
         * добавить / удалить коробку
         */
        function box($gid, $gsId) 
        {
            foreach ($this->basketGoods as $ubgid => $pos) {
                
                if ($pos['good_id'] == $gid && $pos['good_stock_id'] == $gsId) {
                    if ($pos['box'] > 0) {
                        $this->basketGoods[$ubgid]['box'] = -1;
                    } else {
                        $this->basketGoods[$ubgid]['box'] = 1;
                    }
                    
                    break;
                }
            }
            
            $sth = App::db()->prepare("UPDATE `user_basket_goods` 
                              SET 
                                `user_basket_good_box` = :box
                              WHERE 
                                    `user_basket_id` = :id
                                AND `good_id` = :gid
                                AND `good_stock_id` = :gsid
                              LIMIT 1");
              
            $sth->execute(array(
                'box'  => $this->basketGoods[$ubgid]['box'],
                'id'   => $this->id,
                'gid'  => intval($gid),
                'gsid' => intval($gsId),
            ));
            
            return 1;
        }
        
        /**
         * Логируем в базе действие с заказом
         * @var string $a - действие
         * @var string $r - результат
         * @var string $i - дополнительная информация
         * @var int $u - id внясящего оплату пользователя
         * @var string $t - время
         */
        function log($a, $r, $i = null, $u = null, $t = null) 
        {
            global $User;
            
            if (empty($this->id))
                $this->addBasket();
                
            App::db()->query("INSERT INTO `user_basket_log` SET
                            `basket_id` = '" . $this->id . "',
                            `action`    = '" . addslashes($a) . "',
                            `result`    = '" . addslashes($r) . "', 
                            `info`      = '" . addslashes($i) . "', 
                            `user_id`   = '" . (empty($u) ? $User->id : $u) ."',
                            `date`      = "  . (empty($t) ? 'NOW()' : "'" . date('Y-m-d H:i:s', strtotime($t)) . "'"));
                            
            $this->logs[$a][] = array(
                'result'  => $r,
                'info'    => $i,
                'user_id' => (empty($u) ? $User->id : $u),
            );
        }
        
        /**
         * удалить действие из лога
         */
        function dellog($a) 
        {
            App::db()->query("DELETE 
                         FROM `user_basket_log`
                         WHERE
                            `basket_id` = '" . $this->id . "' AND
                            `action`    = '" . addslashes($a) . "'");
                    
            unset($this->logs[$action]);
        }
        
        /**
         * получить лог заказа
         * @var string $action - выбрать какое-то конкретное действие
         */
        function getlog($action = '')
        {
            $r = App::db()->query("SELECT * FROM `user_basket_log` WHERE `basket_id` = '" . $this->id . "' " . ((!empty($action)) ? "AND `action` = '" . addslashes($action) . "'" : '') . " ORDER BY `id` DESC");
            
            $this->logs = array();
            
            foreach ($r->fetchAll() AS $l)
            {
                if ($l['action'] == 'printer_comment') 
                {
                    $l['action']  = 'admin_comment';
                    $l['printer'] = TRUE;
                }
                elseif ($l['action'] == 'courier_comment') 
                {
                    $l['action']  = 'admin_comment';
                    $l['courier'] = TRUE;
                }
                else
                {
                    $l['admin'] = TRUE;
                }
                
                $this->logs[$l['action']][] = $l;
            }
            
            return $this->logs;
        }

        function getlogs($action = '')
        {
            return $this->getlog($action);
        }
        
        /**
         * @return array массив с адресом доставки
         */
        function getaddress()
        {
            $this->address = array();
            
            if (!empty($this->user_basket_delivery_address))
            {
                $r = App::db()->query("SELECT uba.*, dp.`external_id`
                                    FROM 
                                        `user_basket_address` uba
                                            LEFT JOIN `delivery_points` dp ON dp.`id` = uba.`delivery_point`
                                    WHERE 
                                        uba.`id` = '" . $this->user_basket_delivery_address . "'
                                    LIMIT 1");
                
                $this->info['address'] = $r->fetch();
                
                $this->info['address']['tel']     = $this->info['address']['phone'];
                $this->info['address']['country_id'] = $this->info['address']['country'];
                $this->info['address']['country'] = countryId2countryName($this->info['address']['country']);
                $this->info['address']['city_id'] = $this->info['address']['city'];
                $this->info['address']['city']    = (!empty($this->info['address']['city'])) ? cityId2name($this->info['address']['city']) : '';
                
                if (!empty($this->info['address']['metro']))
                    $this->info['address']['metro']   = metroId2metroName($this->info['address']['metro']);
            }
            
            $this->address = $this->info['address'];
            
            return $this->info['address'];
        }
        
        public function getAddressHash()
        {
            return substr(md5(implode('', array(
                       $this->user_id,
                       $this->address['region'],
                       $this->address['name'],
                       $this->address['postal_code'],
                       $this->address['country_id'],
                       $this->address['kray'],
                       $this->address['city_id'],
                       $this->address['raion'],
                       $this->address['address'],
                       $this->address['delivery_point'],
                       $this->address['metro'],
                       $this->address['phone'],
                       $this->address['skype']
                    ))), 0, 30);
        }
        
        /**
         * внести оплату по заказу
         * @param int $sum сумма
         * @param string $type способ оплаты
         * @param string $date дата каким числом вносится оплата
         */
        public function pay($sum, $type = null, $date = null)
        {
            if (!$type) { 
                $type = $this->user_basket_payment_type;
            } else {
                // если указан тип оплаты отличный от того, что проставлен в заказе, меняем тип оплаты заказа
                if ($this->user_basket_payment_type != $type && in_array($type, array_keys(self::$paymentTypes))) {
                    $this->user_basket_payment_type = $type;
                    $this->save(); 
                }
            }
            
            if (!self::$paymentTypes[$type]) {
                throw new Exception('Неизвестный тип оплаты', 1);
            }
            
            if ($this->user_basket_payment_confirm == 'true') {
                return true;
            }
            
            if ($sum >= $this->basketSum - $this->alreadyPayed) {
                if (!in_array($this->user_basket_status, array('prepared', 'delivered'))) {         
                    $this->user_basket_status = 'accepted';
                }
                
                $this->user_basket_payment_confirm = 'true';
                $this->user_basket_payment_date = $date ? date('Y-m-d H:i:s', strtotime($date)) : NOW;;
                $this->save();
                
                $this->log('change_status', 'accepted');
            }
            
            if ($sum > 0)
            {
                $this->log('setPayment', (int) $sum, $type, '', $date);
                
                // Если это заказ-предоплата задачи на дизайн
                if ($this->logs['tender_prepayment'])
                {
                    $tender = new tender($this->logs['tender_prepayment'][0]['result']);
                    $tender->approvePrepaymentOrder();
                }
            }
        }
        
        /**
         * аннулирование заказа
         */
        function cancel($params)
        {
            if ($this->user_basket_status != 'canceled')
            {
                $sms   = new sms(SMSuser, SMSpassword, SMSsender);
                $U     = new user($this->user_id);
                
                $foo = App::db()->query("SELECT SUM(`user_basket_good_total_price`) AS s FROM `user_basket_goods` WHERE `user_basket_id` = '" . $this->id . "'")->fetch();
                
                $gs = $foo['s'];

                // Если заказ был частично оплачен бонусами
                if (!empty($this->user_basket_payment_partical))
                {
                    $U->addBonus($this->user_basket_payment_partical, 'Возврат частичной оплаты на лицевой счет в счет отмененного заказа №' . $this->id, $this->id);
                    
                    // если аннулируется не обмен
                    if (!$this->logs['set_mark']) {
                    } else {
                        // убираем все обменные бонусы
                        App::db()->query("DELETE FROM `user_bonuses` WHERE `user_id` = '" . $U->id . "' AND `user_bonus_status` = '-1'");
                    }
                    
                    $this->user_basket_payment_partical = 0;
                }
                
                // Вырубаем корзину
                $this->basketChange(array(
                    'user_basket_status' => 'canceled', 
                    'user_basket_canceled_date' => NOW,
                    'user_basket_payment_partical' => 0,
                ));
                
                $this->log('change_status', 'canceled', $params['reason']);
                
                /**
                 * если это заказ-предоплата, то он аннулируется без уведомлений
                 */
                if ($this->logs['prepayment_for'])
                {
                    $b = new self($this->logs['prepayment_for'][0]['result']);
                    $b->dellog('prepayment');
                    
                    $this->dellog('prepayment_for');
                    
                    return true;
                }
                
                /**
                 * если у заказа есть заказ-предоплата, то он уничтожается
                 */
                if ($this->logs['prepayment'])
                {
                    $b = new self($this->logs['prepayment'][0]['result']);
                    $b->delete();
                    
                    $this->dellog('prepayment');
                }
                
                /**
                 * предоплата задачи
                 */
                if ($this->logs['tender_prepayment'])
                {
                    $tender = new tender($this->logs['tender_prepayment'][0]['result']);
                    $tender->delMeta('prepayment_order');
                }
                    
                
                
                $this->getAddress();
                
                // Отправка почты
                if (!empty($params['reason']) && self::$cancelReason[$params['reason']])
                {
                    App::mail()->send($this->user_id, self::$cancelReason[$params['reason']]['tpl'], array(
                        'orderNumber'      => $this->id,
                        'orderPhoneNumber' => $this->shortNumber,
                        'deliveryCost'     => $this->user_basket_delivery_cost,
                        'orderTotal'       => $gs,
                        'total'            => $gs + $this->user_basket_delivery_cost,
                        'reasone'          => self::$cancelReason[$params['reason']]['caption']
                    ));
                    
                    // уведомление администраторам если заказ аннулирован по причине отсутствия на складе носителя
                    /*
                    if ($params['reason'] == 3)
                    {
                        App::mail()->send(63250, 364, array(
                            'basket_id' => $this->id,
                            'manager'   => $params['manager']
                        ));
                    }
                    */
                    
                    // смс уведомление
                    if ($params['reason'] == 2 && (!$U->meta->order_sms_info || $U->meta->order_sms_info == 1))
                    {
                        try
                        {
                            if ($this->user_id == 96976)
                                $sms->setSender('Booandstu');
                                
                            $sms->send($this->address['phone'], 'Резерв истек. Ваш заказ #' . substr($this->address['phone'], -4) . ' отменен');
                        }
                        catch (Exception $e) { }
                    }
                }

                // если аннулировали оптовый заказ уведомление менеджеру
                if ($this->user_basket_wholesale >= 0)
                {
                    App::mail()->send('marysel@maryjane.ru', 708, array(
                        'order' => $this,
                    ));
                }
                
                // отпечатанные позиции возвращаем на склад
                $r = App::db()->query("SELECT ubg.`user_basket_good_id`, COUNT(pp.`quantity`) AS q
                                  FROM `user_basket_goods` AS ubg, `" . printturn::$dbtable . "` AS pp 
                                  WHERE ubg.`user_basket_id` = '" . $this->id . "' AND ubg.`user_basket_good_id` = pp.`user_basket_good_id` AND pp.`status` = 'printed' 
                                  GROUP BY ubg.`user_basket_good_id`");
                
                if ($r->rowCount() > 0)
                {
                    // ищем в логе "заказ-возврат" для этого заказа
                    $foo = App::db()->query("SELECT `result` FROM `user_basket_log` WHERE `basket_id` = '" . $this->id . "' AND `action` = 'return_basket'")->fetch();
                    
                    $rid = $foo['result'];
            
                    // если возвратного заказа для этого заказа ещё не было, то
                    if (!$rid)
                    {
                        // формируем заказ с возвращёнными позициями и отрицательным количеством
                        App::db()->query("INSERT INTO `user_baskets`
                                  SET 
                                    `user_id`                     = '" . $this->user_id . "',
                                    `user_basket_delivery_type`   = '" . $this->user_basket_delivery_type . "',
                                    `user_basket_delivery_boy`    = '" . $this->user_basket_delivery_boy . "',
                                    `user_basket_delivered_date`  = '" . NOW . "',
                                    `user_basket_status`          = 'returned',
                                    `user_basket_payment_type`    = '" . $this->user_basket_payment_type . "'");
                        
                        $rid = App::db()->lastInsertId(); 
                        
                        $this->log('return_basket', $rid);
                        
                        logBasketChange($rid, 'returned_basket', $this->id);
                    }
                    
                    foreach ($r->fetchAll() AS $pos)
                    {
                        if ($pos['q'] > 0) {
                            returnPositionOnStock($pos['user_basket_good_id'], $pos['q'], array(
                                'to' => 'stock', 
                                'money' => ($this->user_basket_payment_confirm == 'false') ? 'no' : 'ls', 
                                'rid' => $rid, 
                                'reasone' => $params['description']));
                        }
                    }
                }
                // end
                
                // пересчитываем количество зарезервированных товаров
                $r = App::db()->query("SELECT ubg.`user_basket_good_id`, ubg.`good_stock_id`, ubg.`user_basket_good_quantity`
                                  FROM `user_basket_goods` AS ubg
                                  WHERE ubg.`user_basket_id` = '" . $this->id . "'");
                
                foreach ($r->fetchAll() AS $row)
                {
                    updateStockInprogressQuantity($row['good_stock_id']);
                }
            }
            else 
            {
                throw new Exception ('order already canceled');
            }
        }
        
        /**
         * При доставке заказа начисляем авторам купленных работ деньги на личный счёт
         * 54ый размер - это защитные наклейки на экран, за них не выплачиваем
         */
        function pay2authors()
        {
            // за оптовые заказы не начисляем
            //if ($this->logs['wholesale'])
                //return;
            
            $boxCost = getVariableValue('boxCost');
            
            // Начисляем авторам дизайна
            // если дизайны не выкуплены
            $r = App::db()->query("SELECT 
                                g.`user_id` AS u, 
                                g.`good_status`, 
                                g.`good_payment_multiplier` AS pm, 
                                ubg.`user_basket_good_quantity` - ubg.`user_basket_good_quantity_return` AS q, 
                                ubg.`user_basket_good_id` AS id, 
                                ubg.`user_basket_good_total_price` AS tp, 
                                ubg.`user_basket_good_box` AS box,
                                gm.`meta_value` AS customization_of,
                                gs.`style_id`
                              FROM 
                                `user_basket_goods` AS ubg, 
                                `good_stock` AS gs, 
                                `goods` AS g
                                    LEFT OUTER JOIN `good__buyout` AS gb ON g.`good_id` = gb.`good_id` AND gb.`approved` = '1'
                                    LEFT JOIN `good__meta` gm ON gm.`good_id` = g.`good_id` AND gm.`meta_name` = 'customization_of' 
                              WHERE
                                    ubg.`user_basket_id`      = '" . $this->id . "' 
                                AND ubg.`user_basket_good_payment_id` = '0'
                                AND ubg.`good_id`             = g.`good_id`
                                AND ubg.`good_stock_id`       = gs.`good_stock_id`
                                AND gs.`good_id`              = '0'
                                AND gs.`size_id`             != '54'
                                AND gb.`good_id`              IS NULL");
            
            foreach ($r->fetchAll() AS $row)
            {
                // за самоделки начисляем только если это кастомизация чьего-то авторского дизайна
                if ($row['good_status'] == 'customize' && empty($row['customization_of'])) {
                    continue;
                }
                
                if ($row['box'] > 0) {
                    $row['tp'] -= $boxCost * $row['q']; 
                }
                
                if ($row['style_id'] == 537)
                    $percent = 50;
                else 
                    $percent = 10;
                
                $pay = ceil(($row['tp'] / 100) * ($percent * $row['pm']));
                
                if ($row['q'] > 0 && $pay > 0) 
                {
                    $pid = pay2User($row['u'], $row['q'], $this->id, 'Выплата автору дизайна за продажу ' . $row['q'] . ' позиций #' . $row['id'] . ' с заказа ' . $this->id, $pay);
                    
                    App::db()->query("UPDATE `user_basket_goods` 
                                 SET `user_basket_good_payment_id` = '{$pid}' 
                                 WHERE `user_basket_good_id` = '" . $row['id'] . "' AND `user_basket_id` = '" . $this->id . "'");
                                 
                    printr("payment id: {$pid}");
                }
            }
        }
        
        /**
         * Подготовить заказ к отправке
         */
        function setprepared()
        {
            if (!in_array($this->user_basket_status, array('ordered', 'accepted', 'waiting'))) {
                throw new Exception('Заказ в статусе "' . self::$orderStatus[$this->user_basket_status] . '" нельзя подготовить', 1);
            }
            
            // нельзя подготовить заказ, если в нём присутствую неотпечатанные майки
            // только если это не оптовый заказ без печати
            //if ($this->user_basket_wholesale != 1)
            //{
                $sth = App::db()->prepare("SELECT ubg.`user_basket_good_id` AS id, ubg.`user_basket_good_quantity` AS q, COUNT(pp.`id`) AS pq, ubg.`good_id`, ubg.`gift_id`, ubg.`good_stock_id`, gs.`good_id` AS gs_good_id, gs.`size_id`, gs.`good_id` AS good_stock_good_id
                                  FROM 
                                    `user_basket_goods` AS ubg 
                                        LEFT JOIN `" . printturn::$dbtable . "` AS pp ON ubg.`user_basket_good_id` = pp.`user_basket_good_id` AND pp.`status` = 'printed'
                                        LEFT JOIN `good_stock` AS gs ON ubg.`good_stock_id` = gs.`good_stock_id`
                                  WHERE 
                                     ubg.`user_basket_id` = :id
                                  GROUP BY ubg.`user_basket_good_id`");
                
                $sth->execute(['id' => $this->id]);
                                  
                foreach ($sth->fetchAll() AS $tmp)
                {
                    if (($tmp['size_id'] != 54 && $tmp['gift_id'] == 0 && $tmp['good_stock_good_id'] == 0) || in_array($tmp['gift_id'], array(31,35)))
                    {
                        if ($tmp['pq'] < $tmp['q'] && $tmp['good_id'] != 0)
                        {
                            //printr($tmp);
                            throw new Exception('Can\'t set basket as prepared. Not all positions printed. ' . $tmp['id'], 1);
                        }
                        elseif ($tmp['pq'] < $tmp['q'] && $tmp['good_id'] != 0 && $tmp['good_id'] == $tmp['gs_good_id'])
                        {
                            changeStockQuantity($tmp['good_stock_id'], $tmp['q'] - $tmp['pq'], 'dec', 'Заказ #' . $this->id . '. Позиция #' . $tmp['id'] . '. Типа напечатано');
                        }
                        elseif ($tmp['pq'] < $tmp['q'] && $tmp['good_id'] == 0)
                        {
                            changeStockQuantity($tmp['good_stock_id'], $tmp['q'] - $tmp['pq'], 'dec', 'Заказ #' . $this->id . '. Позиция #' . $tmp['id'] . '. Продали чистым');
                        }
                    }
                }
            //}
            
            $sms = new sms(SMSuser, SMSpassword, SMSsender);
            
            $this->getAddress();
            
            // привязка телефона к аккаунту
            $user_phone = str_replace(array(' ', '(', ')', '-', '+'), '', $this->address['phone']);

            if (substr($user_phone, 0, 1) == 8)
                $user_phone = 7 . substr($user_phone, 1);

            if (!empty($user_phone) && !in_array($this->user_id, array(6199, 63250)))
            {
                $foo = App::db()->query("SELECT COUNT(*) AS c FROM `users` WHERE `user_phone` = '" . $user_phone . "' AND `user_id` != '" . $this->user_id . "'")->fetch();
                
                $phone_avalible = $foo['c'];

                if (empty($phone_avalible))
                {
                    App::db()->query("UPDATE `users` SET `user_phone` = '{$user_phone}' WHERE `user_id` = '" . $this->user_id . "' LIMIT 1");
                }
            }

            $change = array(
                'user_basket_status'        => 'prepared', 
                'user_basket_prepared_date' => NOW,
            );
            
            if (empty($this->user_basket_delivery_boy)) {
                $change['user_basket_delivery_boy'] = self::$deliveryTypes[$this->user_basket_delivery_type]['courier'];
            }
            
            $this->basketChange($change);
            
            $this->log('change_status', 'prepared');
            
            if (!empty(self::$deliveryTypes[$this->user_basket_delivery_type]['courier']))
                $this->log('setcourier', self::$deliveryTypes[$this->user_basket_delivery_type]['courier']);
            
            
            // высылаем смс если доставка курьером или в метро
            $basketsum = $this->getBasketSum() - $this->alreadyPayed;
            
            if (!$this->logs['delivery_order'])
            {
                switch ($this->user_basket_delivery_type) 
                {
                    case 'user':
                    
                        // если обмен
                        //
                            //$smstext = 'Ваш заказ на обмен #' . substr($this->address['phone'], -4) . ' подтверждён! Обмен бесплатный. Заказ будет готов к 15 марта ' . (($this->logs['admin_deliverytime']) ? $this->logs['admin_deliverytime'][0]['result'] : '') . '. О готовности Вы получите СМС';
                            
                        break;
                        
                    case 'deliveryboy':
                    case 'metro':
                        
                        // обмен
                        if ($this->logs['set_mark'])
                        {
                            // брак
                            if ($this->logs['set_mark'][0]['result'] == 'exchange_brak') {
                                $smstext = 'Ваш заказ на обмен #' . substr($this->address['phone'], -4) . ' в рюкзаке у курьера! Будет исполнен сегодня ' . $this->logs['admin_deliverytime'][0]['result'] . '. Курьер с вами свяжется, ожидайте. Обмен бесплатный. Не забудьте, пожалуйста, вернуть курьеру бракованый заказ.';
                            // не брак
                            } else {
                                $smstext = 'Ваш заказ на обмен #' . substr($this->address['phone'], -4) . ' у курьера! Будет исполнен сегодня ' . $this->logs['admin_deliverytime'][0]['result'] . '. Курьер за час позвонит. Не забудьте вернуть курьеру товар на обмен. К оплате ' . $this->user_basket_delivery_cost . ' рублей';
                            }
                        }
                        else 
                        {
                            if ($this->user_basket_payment_type == 'cash')
                                $smstext = 'Заказ ' . substr($this->address['phone'], -4) . ' доставим сегодня ' . $this->logs['admin_deliverytime'][0]['result']  . '. ' . (($basketsum > 0) ? 'К оплате ' . $basketsum . ' р. ' : '') . ' Курьер за час позвонит.';
                            
                            if ($this->user_basket_payment_type != 'cash' && $this->user_basket_payment_type != 'cashon' && $this->user_basket_payment_confirm == 'true')
                                $smstext = 'Заказ ' . substr($this->address['phone'], -4) . ' доставим сегодня ' . $this->logs['admin_deliverytime'][0]['result']  . '. Оплачено. Курьер позвонит.';
                        }
                        
                        break;
                    
                    case 'post':
                        
                        if ($this->user_basket_payment_type != 'cash' && $this->user_basket_payment_type != 'cashon' && $this->user_basket_payment_confirm == 'true')
                            $smstext = 'Заказ ' . substr($this->address['phone'], -4) . ' будет передан на Почту РФ сегодня.';
                        
                        break;
                }
            }

            if (userId2smsInfo($this->user_id) && $smstext)
            {   
                if ($this->user_basket_domain == 'ASbasket')
                    $sms->setSender('allskins.ru');
                
                if ($this->user_id == 96976)
                    $sms->setSender('Booandstu');
                
                $smstext .= ' Спасибо за заказ! 84952293073';
                
                try
                {
                    $r = $sms->send($this->address['phone'], $smstext);
                    $this->log('sendsms', $smstext, $r);
                }
                catch (Exception $e)
                {
                }
            }
                
        
            // пересчитываем количество зарезервированных товаров
            $r = App::db()->query("SELECT ubg.`good_stock_id` FROM `user_basket_goods` ubg, `good_stock` gs WHERE ubg.`user_basket_id` = '" . $this->id . "' AND ubg.`good_stock_id` = gs.`good_stock_id` AND gs.`good_id` = '0'");
            
            foreach ($r->fetchAll() AS $rowbg) {
                updateStockInprogressQuantity($rowbg['good_stock_id']);
            }
        }
        
        /**
         * Доставить заказ до получателя или курьера
         */
        function setdelivered($args)
        {
            foreach ($args AS &$arg) {
                $arg = trim($arg);
            }
            
            if ($this->user_basket_status == 'delivered') {
                return array('status' => 'error', 'message' => 'Can\'t set basket as delivered. Order already is delivered.');
            }
            
            if (in_array($this->user_basket_delivery_type, array('post', 'dpd', 'baltick')) && empty($args['code'])) {
                throw new Exception('Не указан код отслеживания', 1);
            }
            
            $this->user_tel            = $this->address['phone'];
            $this->country             = $this->address['country'];
            $this->user_kray           = $this->address['kray'];
            $this->user_postal_address = $this->address['address'];
            $this->user_city           = !empty($this->address['city']) ? 'г.' . $this->address['city'] : '';
            $this->user_metro          = $this->address['metro'];
            $this->user_postal_code    = $this->address['postal_code'];
            $this->user_name           = $this->address['name'];
            
            // нельзя доставить заказ, если в нём присутствую неотпечатанные майки
            $r = App::db()->query("SELECT ubg.`user_basket_good_id` AS id, ubg.`user_basket_good_quantity` AS q, COUNT(pp.`id`) AS pq, ubg.`good_id`, ubg.`gift_id`, ubg.`good_stock_id`, gs.`size_id`, gs.`good_id` AS good_stock_good_id
                              FROM 
                                `user_basket_goods` AS ubg 
                                    LEFT JOIN `" . printturn::$dbtable . "` AS pp ON ubg.`user_basket_good_id` = pp.`user_basket_good_id` AND pp.`status` = 'printed'
                                    LEFT JOIN `good_stock` AS gs ON ubg.`good_stock_id` = gs.`good_stock_id`
                              WHERE ubg.`user_basket_id` = '" . $this->id . "'
                              GROUP BY ubg.`user_basket_good_id`");
                              
            foreach ($r->fetchAll() AS $tmp)
            {
                if (($tmp['size_id'] != 54 && $tmp['gift_id'] == 0 && $tmp['good_stock_good_id'] == 0) || in_array($tmp['gift_id'], array(31,35)))
                {
                    if ($tmp['pq'] < $tmp['q'] && $tmp['good_id'] != 1 && $tmp['good_id'] != 0)
                    {
                        throw new Exception('Can\'t set basket as delivered. Not all positions printed. (' . $tmp['id'] . ')');
                    }
                }
            }
            
            // Смена статуса корзины, начисление средств, подтверждение о оплате
            $this->user_basket_status            = 'delivered';
            $this->user_basket_delivered_date    = NOW;
            $this->user_basket_delivery_boy      = self::$deliveryTypes[$this->user_basket_delivery_type]['courier'];
            $this->user_basket_delivery_boy_cost = intval($args['delivery_boy_cost']);
            
            $this->log('change_status', 'delivered', $args['code']);
            
            // фиксируем оплату, если (он не оплачен или оплачен но бонусами) и это не сбербанк и не наложенный платёж
            if ($this->user_basket_wholesale == -1 && !in_array($this->user_basket_payment_type, array('cashon', 'sberbank')) && ($this->user_basket_payment_confirm == 'false' || $this->user_basket_payment_type == 'ls'))
            {
                $this->user_basket_payment_confirm = 'true'; 
                
                $rest = basketId2sum($this->id) - $this->alreadyPayed - $this->user_basket_payment_partical;
                
                if ($rest > 0) {
                    $this->log('setPayment', $rest, $this->user_basket_payment_type);
                    $this->user_basket_payment_date = NOW; 
                }
            }
            
            $this->save();
            
            /**
             * если это заказ-предоплата, то оригинальный заказ оплачивается на сумму предоплаты
             */
            if ($this->logs['prepayment_for'])
            {
                $b = new self($this->logs['prepayment_for'][0]['result']);
                $b->dellog('prepayment');
                $b->log('setPayment', basketId2sum($this->id), $this->user_basket_payment_type);
                
                $this->dellog('setPayment');
                
                return true;
            }
        
            $tq = 0; // общее кол-во товаров в корзине
            $tp = 0; // общая сумма товаров в корзине
            $bp = 0; // количество бонусов, возвращаемых покупателю, учитывается только скидка на товар
            $cp = 0; // отчисления контрагентам для оптовых заказов
            
            $wallpappers = $wp = array();
            
            $ssth1 = App::db()->prepare("UPDATE `good_stock` SET `good_stock_sold_quantity` = :q WHERE `good_stock_id` = :id LIMIT 1");
            
            /**
             * Списание товаров со склада, если это не принтшоп
             * принтшоп списывается в момент печати
             */
            foreach ($this->basketGoods as $k => $row) 
            {
                $q = $row['user_basket_good_quantity'] - $row['user_basket_good_quantity_return'];
                
                // sale и чистое списываем со склада во время доставки
                if (!empty($row['gsGoodId']) || (empty($row['good_id']) && empty($row['gift_id'])))
                {
                    changeStockQuantity($row['good_stock_id'], $q, '', 'Заказ # ' . $this->id  . '. Доставлено');
                    App::db()->query("UPDATE `good_stock` AS gs SET `good_stock_inprogress_quantity` = `good_stock_inprogress_quantity` - $q WHERE gs.`good_stock_id` = '" . $row['good_stock_id'] . "' LIMIT 1");
                }
                
                if (!empty($q)) {
                    $ssth1->execute(['id' => $row['good_stock_id'], 'q' => $row['good_stock_sold_quantity'] + $q]);
                }
                
                $tq += $q;
                $tp += $row['user_basket_good_total_price'];
                
                if ($row['category'] != 'stickerset') {
                    $bp += $row['user_basket_good_total_price'];
                }
                
                $cp += $row['user_basket_good_print_cost'];
                
                $pb = unserialize($row['style_print_block']);
                
                if (in_array($row['category'], array('phones', 'touchpads', 'ipodmp3')) && $pb['wall']['w'] > 0 && $pb['wall']['h'] > 0)
                {
                    $link = 'http://www.maryjane.ru/ajax/getWallpapper/' . $row['good_id'] . '/' . $row['style_slug'] . '/';
                    $wp[] = $link;
                    $wallpappers[] = '<div style="margin:24px 0 0px 5px;padding: 5px 0 5px 5px;width: 366px;border:2px solid #F3F3F3;"><div style="float:left;margin: 10px 10px 0 6px;"><a href="' . $link . '" style="border:0px;margin:0px;padding:0px;" target="_blank"><img src="http://www.maryjane.ru/images/order/mj/400/strel.gif" width="50" height="47" alt="" title="" style="border:0px;margin:0px;padding:0px;" /></a></div><div style="float:left;margin: 5px 0px 5px 9px;"><span style="padding: 3px 0;background-color:#abaaab;color:#ffffff;font-size: 14px;line-height: 19px;">&nbsp;Скачать обои на рабочий стол</span></div><div style="clear:left;"></div></div>';
                }
                
                // Если это не оптовый заказ и не заказ созданный админом и позиция не самоделка
                // то ставим лайк каждой работе в заказе от имени юзера
                if ($this->user_basket_wholesale < 0 && !$this->logs['is_admin'])
                {
                    App::db()->query("INSERT IGNORE INTO `good_likes` 
                                 SET 
                                    `good_id`  = '" . $row['good_id'] . "',
                                    `pic_name` = 'good_preview',
                                    `negative` = '0',
                                    `user_id`  = '" . $this->user->id . "'");
                                    
                    App::db()->query("UPDATE `goods` SET `good_likes` = `good_likes` + 1 WHERE `good_id`  = '" . $row['good_id'] . "' LIMIT 1");
                }
                
                // если была наценка партнёра
                if (!empty($row['user_basket_good_partner_inc'])) {
                    $this->user->pay($row['user_basket_good_partner_inc'], 'Комиссия партнёра за проданную позицию #' . $row['user_basket_good_id'], 'order', 0, 1, $this->id);
                }
            } 
             
            // СПИСАНИЕ СТ
            foreach ($this->basketGifts as $k => $rowbg) 
            {
                changeGiftQuantity($rowbg['gift_id'], $rowbg['user_basket_good_quantity'], 'dec');
                $bp += $rowbg['user_basket_good_total_price'];
           
                if ($rowbg['gift_type'] == 'certifikat') 
                {
                    for ($i = 1; $i <= $rowbg['quantity']; $i++)
                    {
                        $a    = uniqid("", true); 
                        $b    = strpos($a, "."); 
                        $pass = substr($a, $b + 1);
                        
                        $r1 = App::db()->query("INSERT INTO `certifications` 
                                           SET
                                            `certification_password` = '" . $pass . "', 
                                            `certification_value`    = '" . (!empty($rowbg['gift_price']) ? $rowbg['gift_price'] : $rowbg['user_basket_good_price']) . "', 
                                            `certification_type`     = 'amount', 
                                            `certification_active`   = 'active', 
                                            `certification_author`   = '". $this->user_id ."',
                                            `certification_author_basket` = '" . $this->id . "'");
                        
                        App::mail()->send($this->user_id, 137, array(
                            'sertCode'  => $pass, 
                            'sertValue' => !empty($rowbg['gift_price']) ? $rowbg['gift_price'] : $rowbg['user_basket_good_price'],
                        ));
                    }
                }
            }
            
            $this->registerPartnersPay(); // Начисляем деньги партнёрам за заказ
            
            $this->pay2authors();         // Начисление средств авторам, если в заказе присутствуют их дизайны
            
            
            // Списание сертификата если оплата прошла по нему
            // Смотрим не активировал ли он сертификат
            $r = App::db()->query("SELECT `certification_id` FROM `certifications` WHERE `certification_type` = 'amount' AND `user_id` = '" . $this->user_id . "' AND `certification_active` = 'none'");
            
            if ($r->rowCount() > 0)
            {
                $cert = $r->fetch();
                
                $r = App::db()->query("UPDATE `certifications` SET `certification_active` = 'used' WHERE `certification_id` = '".$cert[0]."' LIMIT 1");
            }
            
            $bp -= $this->user_basket_payment_partical;
            
            
            // начисляем бонусы за заказ
            // если это не оптовый заказ и у пользователя не отключено получение бонусов за заказ
            // за заказы сделанные на allskins.ru бонусы не начисляем
            // отключено 20-06-2017
            /*
            if ($this->user_basket_domain == 'MJbasket' && $this->user_basket_wholesale < 0 && $bp > 0 && !$this->user->meta->bonuses_disabled && !$this->user->meta->dealer_status && $this->user->user_partner_status <= 0)
            {
                $bonusBack = ($bp / 100) * $this->user->buyerLevel2discount();
                
                if ($bonusBack > 0) {
                    $this->basketChange(array(
                        'user_basket_bonusback_id' => $this->user->addBonus($bonusBack, 'Бонусы за заказ', $this->id, 0, $this->user->id)));
                }   
            }
            */
            
            /**
             * Отправка почты
             */ 
            switch ($this->user_basket_delivery_type) 
            {
                case 'deliveryboy':
                case 'user':
                case 'metro':
                    
                    if (($this->user_basket_payment_type == 'cash' && $this->user_basket_payment_confirm == 'true') || $this->user_basket_delivery_type == 'user') {
                        $smstext = 'Заказ ' . substr($this->address['phone'], -4) . ' доставлен! Обмен/возврат 365 дней.';
                    }
            
                    /**
                     * ищем самую догорую позицию в заказе
                     */
                    $position = array();
    
                    foreach ($this->basketGoods as $g) 
                    {
                        if ($g['price'] > $position['price']) 
                        {
                            $position = $g;
                        }
                    }
                    
                    if ($this->user_basket_wholesale < 0) 
                    {               
                        App::mail()->send(array($this->user_id), ($this->logs['delivery_order'] ? 686 : (strpos($this->user->user_email, 'yandex') !== false ? 869 : 867)), array(
                            'basket'           => $this->id,
                            'order'            => $this,
                            'orderPhoneNumber' => $this->shortNumber,
                            'phone'            => $this->address['phone'],
                            'email'            => $this->user->user_email,
                            'wallpappers'      => $wp,
                            'bonusBack'        => $bonusBack,
                            'buyerDiscount'    => $this->user->buyerLevel2discount(),
                            'style_name'       => styleCategory::$BASECATS[$position['category']]['title_genitive'],
                            'good_name'        => $position['good_name'],
                            'position'         => $position,
                        ), 
                        '', '', 'mj@maryjane.ru');
                    }
                    
                break;
                        
                case 'post':
                
                    App::mail()->send($this->user_id, 133, array(
                        'order'            => $this,
                        'userLogin'        => $this->user->user_login, 
                        'code'             => trim($args['code']),
                        'bonusBack'        => $bonusBack,
                        'buyerDiscount'    => $this->user->buyerLevel2discount(),
                    ));
                        
                    $this->basketChange(
                        array('user_basket_delivered_code' => $args['code']));
                    
                    //if ($this->user_basket_payment_type != 'cash' && $this->user_basket_payment_type != 'cashon' && $this->user_basket_payment_confirm == 'true')
                    $smstext = 'Заказ ' . substr($this->address['phone'], -4) . ' на Почте РФ http://goo.gl/4UqZre номер ' . trim($args['code']) . '.';
                    
                break;
                
                case 'dpd':
                case 'dpd_self':
                
                    if (!empty($args['code'])) 
                    {
                        $code = addslashes(trim($args['code']));
                        $mcode = "Если в течении двух недель вам не пришло почтовое извещение, то свяжитесь с вашим почтовым отделением, указав следующий код: <strong>" . $code . '</strong>';
                    }
                
                    App::mail()->send($this->user_id, 287, array(
                        'order'            => $this->id,
                        'code'             => $code,
                        'mcode'            => $mcode,
                        'orderPhoneNumber' => $this->shortNumber,
                        'bonusBack'        => $bonusBack,
                    ));
                    
                    $this->basketChange(array(
                        'user_basket_delivered_code' => $code));
                    
                    if ($this->user_basket_delivery_type == 'dpd')
                    {
                        if ($this->user_basket_payment_type != 'cash' && $this->user_basket_payment_confirm == 'true') {
                            $smstext = 'Заказ ' . substr($this->address['phone'], -4) . ' отправлен DPD. Обмен/возврат 365 дней.';
                            //$smstext = 'Zakaz №' . substr($this->address['phone'], -4) . ' (' . $code . ') Dostavit DPD ' . datefromdb2textdate($this->logs['deliverydate'][0]['result'], 3) . ' Otsledit zakaz, izmenit datu/adres dostavki http://dpd.ru/p.do2?rGsSgPlOkNimi tel.88002504434';
                            //$smstext = 'Zakaz №' . substr($this->address['phone'], -4) . ' Dostavlen v DPD, prisvoen №' . $code . '. Otsledit zakaz, izmenit datu/adres dostavki http://dpd.ru/p.do2?rGsSgPlOkNimi tel.88002504434';
                        }
                        
                        if ($this->user_basket_payment_type == 'cashon') {
                            $smstext = 'Заказ ' . substr($this->address['phone'], -4) . ' отправлен DPD. Обмен/возврат 365 дней.';
                            //$smstext = 'Zakaz №' . substr($this->address['phone'], -4) . ' (' . $code . ') Dostavit DPD ' . datefromdb2textdate($this->logs['deliverydate'][0]['result'], 3) . ' Otsledit zakaz, izmenit datu/adres dostavki http://dpd.ru/p.do2?rGsSgPlOkNimi tel.88002504434';
                            //$smstext = 'Zakaz №' . substr($this->address['phone'], -4) . ' Dostavlen v DPD, prisvoen №' . $code . '. Otsledit zakaz, izmenit datu/adres dostavki http://dpd.ru/p.do2?rGsSgPlOkNimi tel.88002504434';
                        }
                    }
                    else
                    {
                        if ($this->user_basket_payment_type != 'cash' && $this->user_basket_payment_confirm == 'true')
                            $smstext = 'Заказ ' . substr($this->address['phone'], -4) . ' в DPD. Вы получите СМС. Обмен/возврат 365 дней.';
                        
                        if ($this->user_basket_payment_type == 'cashon')
                            $smstext = 'Заказ ' . substr($this->address['phone'], -4) . ' отправлен DPD. Вы получите СМС. Обмен/возврат 365 дней.';
                    }
                    
                break;
            
                case 'IMlog':
                case 'IMlog_self':
                
                    if (!empty($args['code'])) 
                    {
                        $code = addslashes(trim($args['code']));
    
                        $this->basketChange(array(
                            'user_basket_delivered_code' => $code));
                    }
                    
                    if ($this->user_basket_delivery_type == 'IMlog')
                    {
                        if ($this->user_basket_payment_type != 'cash' && $this->user_basket_payment_confirm == 'true')
                            $smstext = 'Заказ ' . substr($this->address['phone'], -4) . ' отправлен IML. Обмен/возврат 365 дней.';
                        
                        if ($this->user_basket_payment_type == 'cashon')
                            $smstext = 'Заказ ' . substr($this->address['phone'], -4) . ' отправлен IML. Обмен/возврат 365 дней.';
                    }
                    else
                    {
                        if ($this->user_basket_payment_type != 'cash' && $this->user_basket_payment_confirm == 'true')
                            $smstext = 'Zakaz ' . substr($this->address['phone'], -4) . ' v IML' . ($args['code'] ? ' (kod otslejivania ' . $args['code'] . ')' : '') . '. Vi polychite SMS. Obmen/vozvrat 365 dnei.';
                        
                        if ($this->user_basket_payment_type == 'cashon')
                            $smstext = 'Zakaz ' . substr($this->address['phone'], -4) . ' otpravlen IML. Vi polychite SMS. Obmen/vozvrat 365 dnei.';
                    }
                
                    // если выбран самовывоз из точки выдачи
                    if (!empty($this->address['delivery_point'])) {
                        // информация о пункте выдачи самовывоза
                        $delivery_point = App::db()->query(sprintf("SELECT `name`, `address` FROM `delivery_points` WHERE `id` = '%d' LIMIT 1", $this->address['delivery_point']))->fetch();
                        // приблизительное время прибытия заказа
                        $foo = App::db()->query("SELECT `time` FROM `delivery_services` WHERE `service` = '" . $this->user_basket_delivery_type . "' AND `city_id` = '" . intval($this->address['city_id']) . "' LIMIT 1")->fetch();
                        $delivery_time = $foo['time'];
                    } else {
                        $delivery_time = datefromdb2textdate($this->logs['deliverydate'][0]['result'], 3);
                    }
                    
                    if ($this->user_basket_delivery_type == 'IMlog')
                        App::mail()->send($this->user_id, 398, array(
                            'order'            => $this->id,
                            'code'             => $code,
                            'orderPhoneNumber' => $this->shortNumber,
                            'bonusBack'        => $bonusBack,
                            'delivery_point'   => stripslashes($delivery_point['name']) . ' (' . stripslashes($delivery_point['address']) . ')',
                            'delivery_time'    => stripslashes($delivery_time),
                            'maplink'          => urlencode($this->address['city'] . ' ' . $delivery_point['address']),
                        ));
                    else
                        App::mail()->send($this->user_id, 413, array(
                            'order'            => $this,
                            'code'             => $code,
                            'bonusBack'        => $bonusBack,
                            'buyerDiscount'    => $this->user->buyerLevel2discount(),
                            'delivery_point'   => stripslashes($delivery_point['name']) . ' (' . stripslashes($delivery_point['address']) . ')',
                            'delivery_time'    => stripslashes($delivery_time),
                            'maplink'          => urlencode($this->address['city'] . ' ' . $delivery_point['address']),
                        ));
                
                break;
            }

            /**
             * Отправка смс
             */
            if ($smstext && $this->user->smsInfo() && !$this->logs['delivery_order'] && $this->user_basket_wholesale == -1)
            {
                try
                {
                    if ($this->user_basket_domain == 'ASbasket')
                        App::sms()->setSender('allskins.ru');
                    
                    if ($this->user_id == 96976)
                        App::sms()->setSender('Booandstu');
                    
                    $r = App::sms()->send($this->address['phone'], $smstext);
                    
                    $this->log('sendsms', $smstext, $r);
                }
                catch (Exception $e) { }
            }
            
            $carma = new carma;
            $carma->updateUserCarma($this->user_id, 'forOrder', $carma->carmaFor['forOrder']);
            
            // Увеличиваем количество доставленных заказов пользователя
            // срабатывает 1 раз в сутки
            if (!in_array($this->user_id, array(35021)))
            {
                $r = App::db()->query("SELECT `user_basket_id` FROM `user_baskets` WHERE `user_id` = '" . $this->user_id . "' AND `user_basket_status` = 'delivered' AND `user_basket_id` != '" . $this->id . "' AND `user_basket_delivered_date` >= '" . NOWDATE . "'");
                
                if ($r->rowCount() == 0) {
                    $this->user->change(['user_delivered_orders' => $this->user->user_delivered_orders + 1]);
                }
            }
            
            if ($this->user->user_partner_status <= 0 && $this->basketSum >= 20000) {
                $this->user->change(['user_partner_status' => 1]);
            }
            
            // если заказ помечен как оптовый,
            if ($this->user_basket_wholesale >= 0)
            {
                App::mail()->send(108933, 732, array('order' => $this));
            } 
            
            return true;
        }
        
        /**
         * восстановление заказа
         */
        function restore()
        {
            if ($this->user_basket_status == 'canceled')
            {
                // 1. создаём новый заказ
                $q = "INSERT INTO `user_baskets`
                      SET 
                        `user_id`                      = '" . $this->user_id . "',
                        `user_basket_delivery_type`    = '" . $this->user_basket_delivery_type . "',
                        `user_basket_delivery_boy`     = '" . $this->user_basket_delivery_boy . "',
                        `user_basket_delivery_cost`    = '" . $this->user_basket_delivery_cost . "',
                        `user_basket_delivery_address` = '" . $this->user_basket_delivery_address . "',
                        `user_basket_delivered_date`   = '" . NOW . "',
                        `user_basket_status`           = 'ordered',
                        `user_basket_payment_type`     = '" . $this->user_basket_payment_type . "',
                        `user_basket_payment_confirm`  = 'false'";
                $r = App::db()->query($q);
                
                $new = App::db()->lastInsertId();
                
                
                // 2. пытаемся восстановить товары
                $r = App::db()->query("SELECT ubg.* FROM `user_basket_goods` ubg, `good_stock` gs WHERE ubg.`user_basket_id` = '" . $this->id . "' AND ubg.`good_stock_id` = gs.`good_stock_id`");
                
                foreach ($r->fetchAll() AS $row) 
                {
                    // 2.1. пытаемся найти позицию  распродаже
                    $rr = App::db()->query("SELECT `good_stock_id` FROM `good_stock` WHERE `good_id` = '" . $row['good_id'] . "' AND `style_id` = '" . $row['style_id'] . "' AND `size_id` = '" . $row['size_id'] . "' AND `good_stock_visible` = '1' AND `good_stock_quantity` > '0' LIMIT 1");
                    
                    if ($rr->rowCount() == 1)
                    {
                        $foo = $rr->fetch();
                        $row['good_stock_id'] = $foo['good_stock_id'];
                    }

                    App::db()->query("INSERT INTO `user_basket_goods`
                                 SET
                                    `user_basket_id`               = '" . $new . "',
                                    `good_id`                      = '" . $row['good_id'] . "',
                                    `good_stock_id`                = '" . $row['good_stock_id'] . "',
                                    `user_basket_good_price`       = '" . $row['user_basket_good_price'] . "',
                                    `user_basket_good_discount`    = '" . $row['user_basket_good_discount'] . "',
                                    `user_basket_good_quantity`    = '" . ($row['user_basket_good_quantity'] - $row['user_basket_good_quantity_return']) . "',
                                    `user_basket_good_total_price` = '" . $row['user_basket_good_total_price'] . "',
                                    `user_basket_good_box`         = '" . $row['user_basket_good_box'] . "'");
                }

                $old = $this->id;

                $this->log('restoredto', $new);
                
                $this->setBasket($new);
                
                $this->log('restoredfrom', $old);
                
                return $new;
            }
        }
        
        /**
         * @return int уже оплаченная часть суммы заказа
         */
        public function getalreadyPayed()
        {
            if ($this->logs['setPayment']) {
                foreach ($this->logs['setPayment'] AS $p)
                {
                    if ($p['info'] != 'ls')
                        $payed += $p['result'];
                }
            }
            
            return $this->alreadyPayed = $payed;
        }
        
        /**
         * Установить текущую валюту корзины
         */
        public function setCurrency($c) {
            if (!in_array($c, ['rub', 'usd'])) {
                throw new Exception('Установлена некорректная валюта корзины "' . $c . '"', 12);
            }
            
            $this->currency = $c;
        }
        
        function __destruct() {
        }

        /**
         * Выгрузка  по e-mail  печатнику по стороне
         * @param array $position определенные позиции
         * @param int $id номер профиля получателя выгрузки
         * @param string $comment комментарий отправляемый подрядчику
         */
        function sendOnSide($position = array(), $id, $comment = null) 
        {
            $header = array(
                array('Дата', date('d.m.Y')),
                array('Заказчик','maryjane'),
                array('Дата готовности',),
                array('Комментарий',''),
                array('Дизайн', 'Продукция своя/макспринт', 'файл', 'Размер принта, см', 'Расположение принта, по умолчанию грудь', 'Вид продукции', 'пол', 'размер', '', 'цвет продукции', 'кол-во', 'Цена за шт.', 'дата выполнения', 'примечание для Макспринт', 'МЖ заказ №'),
            );

            $footer = array(array('AAAAA'));

            $data = array();

            $gender = array(
                'male'=>'муж',
                'female'=>'жен',
                'kids'=>'дет',
                'unisex'=>'',
            );

            if (count($position) > 0) {
                $intersect = array_intersect_key($this->basketGoods, array_flip((array) $position));
            } else {
                $intersect = $this->basketGoods;
            }
            
            /*
            if ($id == 210074) 
            {
                $filePath = ROOTDIR . '/J/export/';
                $file = $filePath.$this->id;
    
                if (count($position))  $file.='_'.md5(implode('', $position));
    
                @unlink($file.'.xlsx');
                @unlink($file.'.zip');
    
                $zip = new ZipArchive();
                $zip->open($file.'.zip', ZipArchive::CREATE);
            }
            */
            
            $sth1 = App::db()->prepare("SELECT COUNT(*) AS c FROM `" . printturn::$dbtable . "` WHERE `user_basket_good_id` = :ubgid");

            $sth2 = App::db()->prepare("INSERT 
                            INTO `" . printturn::$dbtable . "` 
                            SET 
                                `user_basket_good_id` = :ubgid, 
                                `status`              = 'accepted', 
                                `quantity`            = '1', 
                                `user_id`             = :user,
                                `contractor_id`       = :contractor,
                                `accepted_date`       = NOW()");
                                
            $sth3 = App::db()->prepare("UPDATE `" . printturn::$dbtable . "` SET `contractor_id` = :contractor_id WHERE `user_basket_good_id` = :ubgid AND `status` != 'printed'");

            foreach ($intersect as $p) 
            {
                /*
                $good = new good($p['good_id']);

                if ($good->pics['patterns']) {
                    $patterns++;
                }
                */
                
                //if ($id == 210074) 
                //{
                    /*
                    $pic_name = styleCategory::$BASECATS[$p['category']]['src_name'];
                    
                    if ($pic_name == 'ps_src') 
                    {
                        if ($good->pics['ps_src_' . $p['color_group']]) {
                            $pic_name = 'ps_src_' . $p['color_group'];
                        }
                    }
                    
                    $zip->addFile(ROOTDIR . $good->pics[$pic_name]['path'], basename($good->pics[$pic_name]['path']));
    
                    // если есть исходник на спику
                    if ($good->pics[styleCategory::$BASECATS[$p['category']]['src_name'] . '_back']) {
                        $zip->addFile(ROOTDIR . $good->pics[styleCategory::$BASECATS[$p['category']]['src_name'] . '_back']['path'], basename($good->pics[styleCategory::$BASECATS[$p['category']]['src_name'] . '_back']['path']));
                    }
                    
                    // добавляем в архив превьюшки
                    if ($p['imagePath']) {
                        $zip->addFile(file::getRemoteFile($p['imagePath']), $p['user_basket_good_id'] . '.jpeg');
                    }
                    
                    // превью спинки
                    if ($p['imageBackPath']) {
                        $zip->addFile(file::getRemoteFile($p['imageBackPath']), $p['user_basket_good_id'] . '.back.jpeg');
                    }
    
                    $data[] = array(
    
                        0=>implode('_', array('mj', basename($good->pics[styleCategory::$BASECATS[$p['category']]['src_name']]['path']), $p['good_name'] )),//Дизайн
                        'своя',//Продукция своя/макспринт
                        mainUrl.'/J/pictures_src/EXPORT/'.$this->id.'.zip',//файл
                        '',//Размер принта, см
                        '',//Расположение принта, по умолчанию грудь
                        $p['style_name'],//Вид продукции
                        $gender[$p['style_sex']],//пол
                        $p['size_name'],//размер,
                        '',
                        $p['color_name'],//цвет продукции
                        $p['quantity'],//кол-во
                        '1',//Цена за шт.
                        '',//дата выполнения
                        '',//примечание для Макспринт
                        $this->id,//МЖ заказ №
                    );
                    */
                    
                    $sended = false;
                //}

                // если поизция уже отправлена на стророну то пропускаем следующие шаги
                foreach ($this->logs[$id == 222554 ? 'sendOnsidePointP' : 'sendOnside'] as $l) 
                {
                    if ($l['result'] == $p['user_basket_good_id'])
                    {
                        $sended = true;
                    }
                }
                
                if (!$sended)
                {
                    // выгрузка в поинтпринт
                    if ($id == 222554) {
                        $this->log('sendOnsidePointP', $p['user_basket_good_id'], $id);
                    } else {
                        $this->log('sendOnside', $p['user_basket_good_id'], $id);
                    }
                    
                    // переводим все позиции которые уже в очереди и не отпечатаны на сторону
                    $sth3->execute(array(
                        'contractor_id' => $id,
                        'ubgid' => $p['user_basket_good_id'],
                    ));
                    
                    // получаем кол-во таких позиций уже в очереди
                    $sth1->execute(array(
                        'ubgid' => $p['user_basket_good_id'],
                    ));
                    
                    $pos = $sth1->fetch();
                    
                    // ставим позицию в очередь печати
                    for ($i = 0; $i < $p['user_basket_good_quantity'] - (int) $pos['c']; $i++) 
                    {
                        $sth2->execute(array(
                            'ubgid' => $p['user_basket_good_id'],
                            'user' => $this->user->id,
                            'contractor' => (int) $id,
                        ));
                    }
                }
            }

            /*
            if ($id == 210074) 
            {
                $zip->close();
                
                $writer = new XLSXWriter();
    
                $writer->writeSheet(array_merge($header, $data, $footer),'Sheet1');
                $writer->writeToFile($file.'.xlsx');
                
                App::mail()->send( array(63250, 6199, $id), 652, array(
                    'idBasket'=>$this->id,
                    'urlExcel'=>mainUrl.'/J/export/'.basename($file).'.xlsx',
                    'urlZip'=>mainUrl.'/J/export/'.basename($file).'.zip',
                    'comment' => $comment,
                ));
            }
            */
            
            if ($patterns > 0)
            {
                App::mail()->send(array(81706), 628, array(
                    'basket' => $this,
                ));
            }
        }

        /**
         * Изменить тип доставки заказа
         * @param string $dt тип доставки
         */
        public function changeDeliveryType($dt)
        {
            if (!in_array($dt, array_keys(self::$deliveryTypes))) {
                throw new Exception('Не известный тип доставки', 1);
            }
            
            $was = $this->user_basket_delivery_type;
            
            switch ($dt)
            {
                case 'user':    
                    $this->user_basket_delivery_cost = 0;
                    $this->user_basket_delivery_boy = 0;
                break;
                
                case 'metro':   
                    $this->user_basket_delivery_cost = getVariableValue('delivery_metro_cost');
                    break;
                    
                case 'deliveryboy': 
                    if ($this->address['region'] == 'nearmoscow')
                        $this->user_basket_delivery_cost = getVariableValue('delivery_deliveryboy_cost_nearmoscow');
                    elseif ($this->address['region'] == 'moscow')
                        $this->user_basket_delivery_cost = getVariableValue('delivery_deliveryboy_cost');
                    else
                        $this->user_basket_delivery_cost = getVariableValue('delivery_deliveryboy_cost');
                    break;
                    
                case 'post':
                    $this->user_basket_delivery_cost = in_array($this->address['country_id'], (array) 838 /*+ $SNGcountry*/) ? getVariableValue('deliverycost_post_sng') : getVariableValue('deliverycost_post_world');
                    break;  
                    
                case 'dpd': 
                    $r = cityId2dpdCost($this->address['city_id']);
                    $this->user_basket_delivery_cost = (int) $r['cost'];
                    break;
                    
                case 'baltick':
                case 'IMlog':
                case 'IMlog_self':
                    
                    $r = cityId2deliveryCost($address['city'], $dt);
                    $this->user_basket_delivery_cost = (int) $r['cost'];
                    
                    break;
                    
                default:
                    $this->user_basket_delivery_cost = $this->user_basket_delivery_cost;
                    break;
            }
            
            $this->user_basket_delivery_type = $dt;
            $this->save();
            
            // Если цена доставки уменьшилась, а заказ был оплачен бонусами на больше чем получившаяся сумма
            // возвращаем часть бонусов на лс
            $sth = App::db()->prepare("SELECT SUM(`user_basket_good_total_price`) AS s FROM `user_basket_goods` WHERE `user_basket_id` = ?");
            $sth->execute([$this->id]);
            $foo = $sth->fetch();
            $pp = $foo['s'] + $this->user_basket_delivery_cost;
            
            if ($this->user_basket_payment_partical > $pp)
            {
                $this->user_basket_payment_partical = $pp;
                $this->save();
                
                addBonusJournalRecord($this->user_id, $this->user_basket_payment_partical - $pp, 'Возврат в связи с изменением стоимости доставки', 'inc', $this->id);
            }
            
            $this->log('change_delivery', $dt, $was);
        }

        /**
         * Изменить тип оплаты заказа
         * @param string $pt тип оплаты
         */
        public function changePaymentType($pt)
        {
            if (!in_array($pt, array_keys(self::$paymentTypes))) {
                throw new Exception('Не известный тип доставки', 1);
            }
            
            $was = $this->user_basket_payment_type;
            
            /*
            if ($pt == 'creditcard')
            {
                $ccmax = getVariableValue('creditcard_max');
            
                if (basketId2sum($id) - $basket->user_basket_payment_partical - (int) $basket->alreadyPayed >= $ccmax)
                    die('Max order sum for credicard - ' . $ccmax . ' rub');
            }
            */
            
            // 1. меняем тип оплаты
            $this->user_basket_payment_type = $pt;
            $this->save();
            
            // 2. Высылаем инструкцию по оплате заказчику на почту
            // 2.1 получаем текст инструкции из faq
            $r = App::db()->query("SELECT `text` FROM `faq` WHERE `id` = '" . self::$paymentTypes[$pt]['faq'] . "'");
            $faq = $r->fetch();
            
            // 2.2 шаблон
            $spec['cash']['sberbank'] = 407;
            
            $spec['creditcard']['cashon'] = 510;
            $spec['cash']['cashon'] = 510;
            $spec['webmoney']['cashon'] = 510;
            $spec['yamoney']['cashon'] = 510;
            $spec['sberbank']['cashon'] = 510;
            $spec['qiwi']['cashon'] = 510;
            $spec['ls']['cashon'] = 510;
                
            // 2.3 отправляем письмо
            App::mail()->send($this->user_id, ($spec[$was][$pt] ? $spec[$was][$pt] : 234), 
                array(
                    'id'   => $this->id,
                    'order'   => $this,
                    'orderPhoneNumber' => substr($this->address['phone'], -4),
                    'to'   => self::$paymentTypes[$pt]['title'],
                    'from' => self::$paymentTypes[$was]['title'],
                    'text' => $faq['text'],
                    'info' => $info,
                ));
            
            $this->log('change_payment', $pt, $was);
        }
    }
    /** end class **/
    
?>