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
            'prepared'  => 'Подготовлен',
            'delivered' => 'Доставлен',
            'canceled'  => 'Отменён',
        );
        
        /**
         * @var способы доставки
         */     
        public static $deliveryTypes = array (
            'user'  => array('title' => 'Самовывоз'),
            'post'  => array('title' => 'Почта России'),
            'major' => ['title' => 'MAJOR EXPRESS'],
        );

        
        /**
         * @var способы оплаты
         */
        public static $paymentTypes = array(
            'cash'              => array('title' => 'Наличные', 'ico' => '/public/images/payment/cash_rub.png'),
            'yamoney'           => array('title' => 'Яндекс.Деньги', 'ico' => '/public/images/payment/visa-mc-yandex.png'),
            'sberbank'          => array('title' => 'Карта Сбербанка', 'ico' => '/public/images/payment/sberbank.png'),
            'alfa'              => array('title' => 'Карта Альфа-Банка', 'ico' => '/public/images/payment/alfabank-white.png'),
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
            parent::__construct();
            
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
        
        /**
         * Создаём новую корзину
         * @return 
         * @param object $user_id[optional]
         */
        function addBasket($user_id = 0)
        {
            $this->user_id = intval($this->user_id);
            $this->user_basket_date = NOW;;
            
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
            $r = App::db()->query("INSERT INTO `" . basket::$dbtable . "` 
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
            $r = App::db()->query("SELECT * FROM `" . basket::$dbtable . "` WHERE `id` = '" . $this->id . "' LIMIT 1");

            if ($this->info = $r->fetch()) 
            {
                $this->user_basket_date_rus = datefromdb2textdate($this->user_basket_date, 3);
                
                if ($this->user_basket_delivery_type)
                    $this->user_basket_delivery_type_rus = self::$deliveryTypes[$this->user_basket_delivery_type]['title'];
                    
                if ($this->user_basket_payment_type) {     
                    $this->user_basket_payment_type_rus = self::$paymentTypes[$this->user_basket_payment_type]['title'];
                    $this->user_basket_payment_type_ico = self::$paymentTypes[$this->user_basket_payment_type]['ico'];
                }
                
                $this->status_rus = self::$orderStatus[$this->user_basket_status];
                
                return $this->info;
            } else {
                if (!empty($this->id)) {
                    throw new appException('Basket ' . $this->id . ' not found', 1);
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
            
            $r = App::db()->query("UPDATE `" . basket::$dbtable . "` SET " . implode(', ', $out) . " WHERE `id` = '" . $this->id . "' LIMIT 1");
            
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
            App::db()->query("DELETE FROM `" . basket::$dbtable . "` WHERE `id` = '" . $this->id . "' LIMIT 1");
            
            return true;
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
        
        function setAddress($data)
        {
            unset($data['status']);
            unset($data['comment']);
            unset($data['city_name']);
                            
            $fs   = implode("`, `", array_keys($data));
            $vs   = implode("','", $data);

            $r = App::db()->query("INSERT IGNORE INTO `" . basketAddress::$dbtable . "` 
                                  (`$fs`, `user_id`) VALUES ('$vs', '" . $this->user_id . "') 
                                  ON DUPLICATE KEY UPDATE `order_date` = '" . NOW . "'");
                
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
            
            //if ($this->user_basket_status = 'active') {
                $this->info['basketSum'] += $this->user_basket_delivery_cost - $this->user_basket_payment_partical;
            //}
            
            return $this->info['basketSum'];
        }
    
        /**
         * Получить количество товаров в корзине
         * @param string $type тип считаемых позиций (все/тряпки/наклейки) 
         * @return int кол-во товаров в корзине
         */
        function getGoodsCount($type = null) {
            
            $sth = App::db()->prepare("SELECT SUM(ubg.`user_basket_good_quantity`) AS s FROM `" . basketItem::$dbtable . "` ubg WHERE ubg.`user_basket_id` = ?");
            
            $sth->execute([$this->id]);
                
            $count = $sth->fetch();
            
            return $count['s'];
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
            
            $discounts = [];
            
            if ($this->user_basket_status == 'active')
            {   
                // если в заказе был активирован сертификат
                if ($this->logs['activateDiscontCard']) {
                    $cert = new certificate($this->logs['activateDiscontCard'][0]['result']);
                }
            }
            
            $tp = 0;
            
            $rr = App::db()->prepare("SELECT 
                                ubg.*, 
                                ubg.`user_basket_good_price` p, 
                                ubg.`user_basket_good_discount` d, 
                                ubg.`user_basket_good_quantity` quantity, 
                                ubg.`user_basket_good_total_price` tp, 
                                ubg.`user_basket_good_comment` AS c,
                                ubg.`user_basket_good_time`,
                                g.`id` AS product_id,
                                g.`product_name`,
                                g.`product_sku`,
                                p.`picture_path`
                              FROM 
                                `" . basketItem::$dbtable . "` ubg,
                                `" . product::$dbtable . "` g ,
                                `" . picture::$dbtable . "` p
                              WHERE 
                                    ubg.`user_basket_id` = :id
                                AND ubg.`good_id` = g.`id`
                                AND g.`picture_id` = p.`picture_id`
                              GROUP BY 
                                ubg.`id`");
            
            $rr->execute(array(
                'id' => $this->id,
            ));
                                            
            foreach($rr->fetchAll(PDO::FETCH_ASSOC) AS $row)
            {
                $row['ubgid']              = $row['id'];
                $row['product_name']       = stripslashes($row['product_name']);
                $row['comment']            = stripslashes($row['c']);
                
                $row['price'] = $row['p'];
                $row['discount'] = $row['d'] + $this->user_basket_discount;
                
                if ($this->user_basket_status == 'active') {
                    $row['tprice'] = round($row['price'] - $row['price'] / 100 * $row['discount']) * $row['quantity'];
                } else {
                    $row['tprice'] = $row['tp'];
                }
                
                $row['discount_sum'] = round($row['tprice'] / 100 * $row['discount']);

                $row['tprice_rub'] = $row['tprice'];
                
                // переводим цены в доллары
                if ($this->currency == 'usd')
                {
                    $row['price']  = round($row['price'] / $usdRate, 1);
                    $row['tprice'] = round($row['tprice'] / $usdRate, 1);
                    $row['tp']     = round($row['tp'] / $usdRate, 1);
                }
                
                $this->basketGoods[$row['id']]  = $row;
                
                $this->goodsCount += $row['q'];
                $tp += $row['tprice'];
            }

            krsort($this->basketGoods);
            
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
        
        function plusOne($gid, $q = 1)
        {
            $available = App::db()->query("SELECT `quantity` AS q FROM `" . product::$dbtable . "` WHERE `id` = '" . intval($gid) . "' LIMIT 1")->fetch();
            
            foreach ($this->basketGoods as $posk => $pos) {
                if ($pos['good_id'] == $gid) {
                    $reservedtome = $pos['quantity'];
                    break;
                }
            }
            
            if ($q > $available['q'] - $reservedtome)
            {
                throw new appException("Этот товар зарезервирован или отсутствует на складе.\nВозможно кто-то откажется от данной модели, попробуйте сделать заказ завтра.");
            } 
            else
            {
                $sth = App::db()->prepare("UPDATE 
                                    `" . basketItem::$dbtable . "` 
                                  SET 
                                    `user_basket_good_quantity` = `user_basket_good_quantity` + :q
                                  WHERE 
                                        `user_basket_id` = :bid
                                    AND `good_id` = :gid
                                  LIMIT 1");

                $sth->execute(array(
                    'q' => $q,
                    'bid' => $this->id,
                    'gid' => $gid
                ));
                                  
                unset($this->basketGoods);
            }
        }

        /**
         * Увеличить количество товара в корзине
         */
        function chQuanity($gid, $q = 1)
        {
            if (!is_numeric($q) || $q < 0) {
                throw new appException('Указано некорректное количество');
            }
            
            foreach ($this->basketGoods as $posk => $pos) 
            {
                if ($pos['good_id'] == $gid) 
                {
                    $reservedtome = $pos['quantity'];
                    
                    $available = App::db()->query("SELECT `quantity` AS q FROM `" . product::$dbtable . "` WHERE `id` = '" . intval($gid) . "' LIMIT 1")->fetch();
                    
                    if ($q > $available['q'] - $reservedtome)
                    {
                        throw new appException("Этот товар зарезервирован или отсутствует на складе.\nВозможно кто-то откажется от данной модели, попробуйте сделать заказ завтра.");
                    } 
                    else
                    {
                        $sth = App::db()->prepare("UPDATE 
                                            `" . basketItem::$dbtable . "` 
                                          SET 
                                            `user_basket_good_quantity` = :q,
                                            `user_basket_good_total_price` = :tp
                                          WHERE 
                                                `user_basket_id` = :bid
                                            AND `good_id` = :gid
                                          LIMIT 1");

                        $sth->execute(array(
                            'q' => $q,
                            'bid' => $this->id,
                            'gid' => $gid,
                            'tp' => $pos['price'] * (1 - $pos['discount'] / 100) * $q,
                        ));

                        unset($this->basketGoods);
                    }
                    
                    break;
                }
            }
            
            
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
            
            $item->good_id = (int) $item->good_id;
            
            // максимальное кол-во одной позиции в заказе 
            $maxOrderQuantity = getVariableValue('maxOrderQuantity');
            // максимальное кол-во позиций в заказе 
            $maxOrderPositions = getVariableValue('maxOrderPositions');

            $goodsCount = $reservedtome = 0;

            foreach ($this->basketGoods AS $ubgid => $pos) 
            {
                $goodsCount++;
                
                if ($pos['good_id'] == $item->good_id) {
                    $reservedtome += intval($pos['quantity']);
                }
                
                // + 1  
                if ($item->good_id == $pos['good_id']) {
                    $plus1 = $ubgid;
                }
            }

            // Получаем информацию о носителе на складе
            $product = new product($item->good_id);
            
            
            if ($item->good_id > 0 && ($item->quantity + $goodsCount > $maxOrderPositions))
            {
                throw new appException("Вы не можете заказать более чем " . $maxOrderPositions . " позиций в одном заказе, пожалуйста обратитесь к администрации магазина");
            } 
            else 
            {
                if ($item->quantity > $product->quantity - $reservedtome)
                {
                    throw new appException("Этот товар зарезервирован или отсутствует на складе.");
                } 
                else
                {
                    if ($plus1)
                    {
                        $sth = App::db()->prepare("UPDATE `" . basketItem::$dbtable . "`
                                         SET
                                            `user_basket_good_quantity` = `user_basket_good_quantity` + :q,
                                            `user_basket_good_total_price` = :tp
                                         WHERE 
                                            `id` = :id
                                         LIMIT 1");

                        $sth->execute([
                            'q' => $item->quantity, 
                            'id' => $plus1,
                            'tp' => round($item->price * (1 - $item->discount / 100)) * ($item->quantity + $reservedtome),
                        ]);
                        
                        $ubgid = $plus1;
                    }
                    else 
                    {
                        $sth = App::db()->prepare("INSERT INTO `" . basketItem::$dbtable . "`
                                          SET
                                            `user_basket_id`               = :b,
                                            `good_id`                      = :pid,
                                            `user_basket_good_price`       = :p,
                                            `user_basket_good_discount`    = :d,
                                            `user_basket_good_total_price` = :tp, 
                                            `user_basket_good_quantity`    = :q,
                                            `user_basket_good_comment`     = :c");
                        
                        $sth->execute([
                            'b' => $this->id,
                            'pid' => $item->good_id,
                            'p' => $item->price,
                            'd' => $item->discount,
                            'tp' => round($item->price * (1 - $item->discount / 100)) * $item->quantity,
                            'q' => $item->quantity,
                            'c' => urldecode($item->comment),
                        ]);

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
            
            return $msg;
        }
        
        /**
         * Добавить в корзину сразу несколько позиций или СТ
         * @param array $goods массив с товарами
         */
        public function add2basket($goods) 
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
            
            return $msg;
        }
        
        /**
         * Удалить товар из корзины
         * @return 
         * @param object $good_id
         * @param object $good_stock_id
         */
        function removeGood($good_id) {
            
            foreach ($this->basketGoods as $pos) {
                if ($pos['good_id'] == $good_id) {
                    App::db()->query("DELETE FROM `" . basketItem::$dbtable . "` WHERE `user_basket_id` = '" . $this->id . "' AND `good_id` = '" . intval($good_id) . "' LIMIT 1");
                }
            }
        }
        
        /**
         * Переносим корзину из куки в базу (maryjane.ru)
         * Выполняется при подтверждении заказа (последний шаг)
         * @return 
         */
        function saveBasket() 
        {   
            $total_q = $bb = $tprice = 0;

            $goods = array();
            $gifts = array();

            $sth1 = App::db()->prepare("UPDATE
                                            `" . basketItem::$dbtable . "`
                                       SET
                                            `user_basket_good_price`         = :price, 
                                            `user_basket_good_discount`      = :discount, 
                                            `user_basket_good_quantity`      = :quantity, 
                                            `user_basket_good_total_price`   = :tprice_rub
                                       WHERE 
                                            `id` = :id
                                       LIMIT 1");

            foreach ($this->basketGoods as $row)
            {
                $total_q += $row['quantity'];
                $tprice  += $row['tprice'];
                
                $sth1->execute([
                    'price'         => $row['price'], 
                    'discount'      => $row['discount'], 
                    'quantity'      => $row['quantity'], 
                    'tprice_rub'    => $row['tprice_rub'], 
                    'id'            => intval($row['id']),
                ]);
                
                $goods[] = $row;
            }

            // Меняем статус корзины
            // Если заказ оплачен полностью бонусами
            if ($this->user_basket_payment_partical >= $tprice + $this->user_basket_delivery_cost)
                $status = 'accepted';
            else
                $status = 'ordered';
            
            $this->user_basket_status = $status;
            $this->user_basket_date   = NOW;
            $this->user_id            = $this->user->id;

            $this->save();

            /*
             * ОТПРАВКА ПИСЬМА С ДАННЫМИ О ЗАКАЗЕ
             */
            $reparray['deliveryAddress'] = $this->address['country'] . (!empty($this->address['city']) ? ', г. ' . $this->address['city'] : '') . ((!empty($this->address['address'])) ? ', ул. ' . $this->address['address'] : '');

            if (!empty($this->address['postal_code'])) $reparray['deliveryAddress'] .= ', (' . $this->address['postal_code'] . ')';
            if (!empty($this->address['metro'])) $reparray['deliveryAddress'] .= ', м. ' . metroId2metroName($this->address['metro']);
            
            $reparray['order']            = $this;
            $reparray['orderStatus']      = self::$orderStatus[$status];
            $reparray['phone']            = $this->address['phone'];
            $reparray['orderPhoneNumber'] = substr($this->address['phone'], -4);
            $reparray['deliveryType']     = self::$deliveryTypes[$this->user_basket_delivery_type]['title'] . (($dp) ? ' (' . $dp->address . '. ' . $dp->schema . ')' : '');
            $reparray['paymentType']      = self::$paymentTypes[$this->user_basket_payment_type]['title'];
            $reparray['orderSum']         = $tprice;
            $reparray['total']            = $tprice + $this->user_basket_delivery_cost - $this->user_basket_payment_partical;
            $reparray['user']             = $this->user;
            
            App::mail()->send(array($this->user_id), 4, $reparray);

            $this->log('change_status', $status);
            
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
            $foo = App::db()->query("SELECT SUM(`user_basket_good_total_price`) AS s FROM `" . basketItem::$dbtable . "` WHERE `user_basket_id` = '" . $this->id . "'")->fetch();
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
         * Логируем в базе действие с заказом
         * @var string $a - действие
         * @var string $r - результат
         * @var string $i - дополнительная информация
         * @var int $u - id внясящего оплату пользователя
         * @var string $t - время
         */
        function log($a, $r, $i = null, $u = null, $t = null) 
        {
            if (empty($this->id))
                $this->addBasket();
                
            App::db()->query("INSERT INTO `" . self::$dbtable_log . "` SET
                            `basket_id` = '" . $this->id . "',
                            `action`    = '" . addslashes($a) . "',
                            `result`    = '" . addslashes($r) . "', 
                            `info`      = '" . addslashes($i) . "', 
                            `user_id`   = '" . (empty($u) ? $User->id : $u) ."',
                            `date`      = "  . (empty($t) ? 'NOW()' : "'" . date('Y-m-d H:i:s', strtotime($t)) . "'"));
                            
            $this->logs[$a][] = array(
                'result'  => $r,
                'info'    => $i,
                'user_id' => $u,
            );
            
            $this->user_basket_last_change_date = NOW;
            $this->save();
        }
        
        /**
         * удалить действие из лога
         */
        function dellog($a) 
        {
            App::db()->query("DELETE 
                         FROM `" . self::$dbtable_log . "`
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
            $r = App::db()->query("SELECT * FROM `" . self::$dbtable_log . "` WHERE `basket_id` = '" . $this->id . "' " . ((!empty($action)) ? "AND `action` = '" . addslashes($action) . "'" : '') . " ORDER BY `id` DESC");
            
            $this->logs = array();
            
            foreach ($r->fetchAll() AS $l)
            {   
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
                $r = App::db()->query("SELECT uba.*
                                    FROM 
                                        `" . basketAddress::$dbtable . "` uba
                                    WHERE 
                                        uba.`id` = '" . $this->user_basket_delivery_address . "'
                                    LIMIT 1");
                
                $this->info['address'] = $r->fetch();
                
                $this->info['address']['tel']     = $this->info['address']['phone'];
                $this->info['address']['country_id'] = $this->info['address']['country'];
                $this->info['address']['country'] = countryId2name($this->info['address']['country']);
                $this->info['address']['city_id'] = $this->info['address']['city'];
                $this->info['address']['city']    = (!empty($this->info['address']['city'])) ? cityId2name($this->info['address']['city']) : '';
                
                if (!empty($this->info['address']['metro']))
                    $this->info['address']['metro']   = metroId2metroName($this->info['address']['metro']);
            }
            
            $this->address = $this->info['address'];
            
            return $this->info['address'];
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
                // Вырубаем корзину
                $this->basketChange(array(
                    'user_basket_status' => 'canceled', 
                    'user_basket_canceled_date' => NOW,
                ));
                
                $this->log('change_status', 'canceled', $params['reason']);
                
                // Отправка почты
                App::mail()->send($this->user_id, 20, array(
                    'order'   => $this,
                    'reasone' => $params['reason'],
                ));
                
                // снимаем резер с товаров
            }
            else 
            {
                throw new appException('order already canceled');
            }
        }
        
        
        /**
         * Подготовить заказ к отправке
         */
        function setprepared()
        {
            if (!in_array($this->user_basket_status, array('ordered', 'accepted', 'waiting'))) {
                throw new appException('Заказ в статусе "' . self::$orderStatus[$this->user_basket_status] . '" нельзя подготовить', 1);
            }
            
            $change = array(
                'user_basket_status'        => 'prepared', 
                'user_basket_prepared_date' => NOW,
            );
            
            $this->basketChange($change);
            
            $this->log('change_status', 'prepared');
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
            
            // Смена статуса корзины, начисление средств, подтверждение о оплате
            $this->user_basket_status            = 'delivered';
            $this->user_basket_delivered_date    = NOW;
            $this->save();
            
            $this->log('change_status', 'delivered', $args['code']);
            
            $this->pay($this->basketSum - $this->alreadyPayed);
            
            // Отправка почты
            App::mail()->send($this->user_id, ($this->user_basket_delivery_type == 'user' ? 22 : 21), array(
                'order'   => $this,
            ));
            
            // списываем товары со склада
            
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
                $q = "INSERT INTO `" . basket::$dbtable . "`
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
                $r = App::db()->query("SELECT ubg.* FROM `" . basketItem::$dbtable . "` ubg, `good_stock` gs WHERE ubg.`user_basket_id` = '" . $this->id . "' AND ubg.`good_stock_id` = gs.`good_stock_id`");
                
                foreach ($r->fetchAll() AS $row) 
                {
                    // 2.1. пытаемся найти позицию  распродаже
                    $rr = App::db()->query("SELECT `good_stock_id` FROM `good_stock` WHERE `good_id` = '" . $row['good_id'] . "' AND `style_id` = '" . $row['style_id'] . "' AND `size_id` = '" . $row['size_id'] . "' AND `good_stock_visible` = '1' AND `good_stock_quantity` > '0' LIMIT 1");
                    
                    if ($rr->rowCount() == 1)
                    {
                        $foo = $rr->fetch();
                        $row['good_stock_id'] = $foo['good_stock_id'];
                    }

                    App::db()->query("INSERT INTO `" . basketItem::$dbtable . "`
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
            
            $sth1 = App::db()->prepare("SELECT COUNT(*) AS c FROM `" . printturn::$dbtable . "` WHERE `id` = :ubgid");

            $sth2 = App::db()->prepare("INSERT 
                            INTO `" . printturn::$dbtable . "` 
                            SET 
                                `id` = :ubgid, 
                                `status`              = 'accepted', 
                                `quantity`            = '1', 
                                `user_id`             = :user,
                                `contractor_id`       = :contractor,
                                `accepted_date`       = NOW()");
                                
            $sth3 = App::db()->prepare("UPDATE `" . printturn::$dbtable . "` SET `contractor_id` = :contractor_id WHERE `id` = :ubgid AND `status` != 'printed'");

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
            $sth = App::db()->prepare("SELECT SUM(`user_basket_good_total_price`) AS s FROM `" . basketItem::$dbtable . "` WHERE `user_basket_id` = ?");
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
        
        public function getAll($filters)
        {
            if ($filters['user'])
            {
                $aq[] = "b.`user_id` = '" . intval($filters['user']) . "'";
            }

            if ($filters['status'])
            {
                $fs = [];
                foreach ((array) $filters['status'] AS $s) {
                    if (self::$orderStatus[$s]) {
                        $fs[] = $s;
                    }
                }
                $aq[] = "b.`user_basket_status` IN ('" . implode("', '", $fs) . "'";
            }
            
            if ($filters['statusNot'] && self::$orderStatus[$filters['statusNot']])
            {
                $aq[] = "b.`user_basket_status` != '" . $filters['statusNot'] . "'";
            }

            $q = "SELECT SQL_CALC_FOUND_ROWS b.*, SUM(bi.`user_basket_good_total_price`) AS sum, SUM(bi.`user_basket_good_quantity`) AS countGoods, u.`user_email`
                FROM 
                    `" . self::$dbtable . "` b 
                        LEFT JOIN `" . basketItem::$dbtable . "` bi ON b.`id` = bi.`user_basket_id`
                        LEFT JOIN `" . user::$dbtable . "` u ON b.`user_id` = u.`id`
                    " . ($at ? ', ' . implode(', ', $at) : '') . "
                WHERE 1 " . ($aq ? ' AND ' . implode(' AND ', $aq) : '') 
                .
                "GROUP BY b.`id`";

            if ($filters['orderBy']) {
                // ёбаный стыд))) 
                $q .= " ORDER BY " . addslashes($filters['orderBy']) . ' ' . (in_array($filters['orderDir'], ['ASC', 'DESC']) ? $filters['orderDir'] : 'DESC');
            }

            if ($filters['limit']) {
                $q .= " LIMIT " . ($filters['offset'] ? intval($filters['offset']) : 0) . ", " . intval($filters['limit']);
            }

            //printr($q, 1);

            $sth = App::db()->prepare($q);

            $sth->execute();

            $foo = App::db()->query("SELECT FOUND_ROWS() AS s")->fetch();
            $_SESSION['pages_total_' . $trans_id] = $foo['s'];

            $rows = $sth->fetchAll();

            foreach ($rows AS $k => $p) {
                $rows[$k]['status'] = self::$orderStatus[$p['user_basket_status']];
                $rows[$k]['user_basket_delivery_type_rus'] = self::$deliveryTypes[$p['user_basket_delivery_type']]['title'];
                $rows[$k]['user_basket_payment_type_rus'] = self::$paymentTypes[$p['user_basket_payment_type']]['title'];
            }

            return $rows;
        }
    }
    /** end class **/
    
?>