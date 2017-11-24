<?php

    namespace application\models;
    
    use \smashEngine\core\App AS App; 
    use \PDO;
    use \Exception; 
    
    /**
     * Класс-фабрика для работы с различными службами доставки
     */
    
    abstract class delivery_services
    {
        private static $_classes = array(
            'logistic'=>'\application\models\logistic',
            'dpd'=>'\application\models\dpd',
        );

        public static function set($name)
        {
            if (empty(self::$_classes[$name]) || !class_exists(self::$_classes[$name])) throw new Exception ('Not found class "' . $name . '"');

            return new self::$_classes[$name];
        }


        //var $data = array();
        
        // сформировать заявку на доставку
        public function create() {}
        // отправить запрос
        public function place($uri) {}
        public function loadData($data) {}
    }
    

    /**
     * im-logistic.ru
     */
    class logistic extends delivery_services 
    {
        const ACTION_CREATE = 'CREATE';
        const ACTION_STATUS = 'STATUS';

        const DIR_INBOX = '/Inbox/';
        const DIR_OUTBOX = '/Outbox/';

        private $apiUrl = 'http://request.iml.ru/';
        private $apiKey = '04190';
        private $apiPas = 'e44Iq8a4';

        public $action = array(
            self::ACTION_CREATE => true,
            self::ACTION_STATUS => true,
        );


        
        private $orderStatus = array(
            0=>'-',
            1=>'Доставлен',
            2=>'Ошибочная Доставка',
            3=>'Отказ',
            4=>'Перенос',
            5=>'Част.Отказ',
            6=>'Обмен',
        );

        private $deliveryStatus = array(

            0=>'Не принят',
            1=>'На Складе',
            2=>'У Курьера',
            3=>'Доставлен',
            4=>'Подготовлен Возврат',
            5=>'Част. Возврат',
            6=>'Возврат',
            7=>'Перенесено',
            10=>'Самовывоз',
            11=>'Заявка',
            12=>'Коррекция',
            13=>'Завершено',
            999=>'-',
        );


        var $xml = '';
        
        
        /**
         * Загрузить данные о заказе
         */
        public function loadData($data)
        {
            $this->data = $data;
        }
        
        
        /**
         * Выполнить запрос к апи
         */
        public function place($uri)
        {
            $url = $this->apiUrl . ltrim($uri, '/');

            $ch = curl_init();
            
            curl_setopt_array($ch, array(
                CURLOPT_URL            => $url,
                CURLOPT_USERPWD        => $this->apiKey . ':' . $this->apiPas,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_TIMEOUT        => 300
            ));
            
            if (!empty($this->xml))
            {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $this->xml);
            }
            
            $h = curl_exec($ch); 
            
            if (empty($h))
            {
                throw new Exception ('Request answer is empty');
            }
            
            $err = curl_errno($ch);
            
            if (empty($err))
            {
                // получаем код ответа
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                
                if ($http_code == 200) {}
                else
                {
                    switch ($http_code)
                    {
                        case '401': $err = 'Логин и пароль не верны'; break;
                        case '403': $err = 'Доступ запрещен'; break;
                        case '404': $err = 'Файл или папка не найдены'; break;
                        case '409': $err = 'Файл уже существует'; break;
                        case '413': $err = 'Слишком большой запрос'; break;
                        case '422': $err = 'Пустой файл (данные не найдены)'; break;
                        case '423': $err = 'Ошибка создания или удаления файла'; break;
                    }
                }
            }
            
            
            if (!empty($err))
            {
                throw new Exception ($err);
            }
            
            $this->xml = '';
            
            return simplexml_load_string($h);
        }


        public function clearAll() {

            $files = $this->xml2array($this->loadFile());

            foreach ($files['fileName'] as $file) {

                $this->deleteFile(self::DIR_OUTBOX . $file);
            }
        }


        /**
         * @param string $file
         *
         * @throws Exception
         */
        public function loadFile($file='') {

            $this->xml = '';

            return $this->place(self::DIR_OUTBOX . $file);
        }


        public function deleteFile($uri)
        {
            $url = $this->apiUrl . ltrim($uri, '/');

            $ch = curl_init();

            curl_setopt_array($ch, array(
                CURLOPT_URL            => $url,
                CURLOPT_USERPWD        => $this->apiKey . ':' . $this->apiPas,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_CUSTOMREQUEST => "DELETE",
                CURLOPT_TIMEOUT        => 300,
            ));

            $h = curl_exec($ch);

            if (empty($h))
            {
                throw new Exception ('Request answer is empty');
            }

            $err = curl_errno($ch);

            if (empty($err))
            {
                // получаем код ответа
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                if ($http_code == 200) {}
                else
                {
                    switch ($http_code)
                    {
                        case '401': $err = 'Логин и пароль не верны'; break;
                        case '403': $err = 'Доступ запрещен'; break;
                        case '404': $err = 'Файл или папка не найдены'; break;
                        case '409': $err = 'Файл уже существует'; break;
                        case '413': $err = 'Слишком большой запрос'; break;
                        case '422': $err = 'Пустой файл (данные не найдены)'; break;
                        case '423': $err = 'Ошибка создания или удаления файла'; break;
                    }
                }
            }


            if (!empty($err))
            {
                throw new Exception ($err);
            }

            return $http_code;
        }

        /**
         * Чтение из папки (файла)
         */
        public function read($from = self::DIR_INBOX)
        {
            return $this->place($from);
        }

        /**
         * Сформировать заявку на забор товара от нас
         */
        public function zabor($date = '') 
        {
            $this->xml = 
            
                '<?xml version="1.0" encoding="utf-8"?>
                <DeliveryRequest xmlns="http://www.imlogistic.ru/schema/request/v1">
                <Message>
                    <sender>' . $this->apiKey . '</sender>
                    <recipient>001</recipient>
                    <issue>' . date('Y-m-d\Тh:i:s') . '</issue>
                    <reference>' . uniqid() . '</reference>
                    <version>1.0</version>
                    <test>0</test>
                </Message>
                <Order>
                    <number>' . uniqid() . '</number>
                    <action>CREATE</action>
                    <Condition>
                        <service>ЗАБОР</service>
                        <Delivery>
                            <issue>'    . $date . '</issue>
                            <timeFrom>10:00</timeFrom>
                            <timeTo>18:00</timeTo>
                        </Delivery>
                        <comment></comment>
                    </Condition>
                    <Region>
                        <departure>МОСКВА</departure>
                        <destination>МОСКВА</destination>
                    </Region>
                    <Consignee>
                        <Address>
                            <line>ул. Малая Почтовая, д. 12 (серое здание), стр. 3, корп. 20 5-й этаж. компания Мэриджейн</line>
                            <city>Москва</city>
                            <postCode></postCode>
                        </Address>
                        <RepresentativePerson>
                            <name>Марина</name>
                            <Communication>
                                <telephone1>+79031298013</telephone1>
                            </Communication>
                        </RepresentativePerson>
                    </Consignee>
                    <SelfDelivery>
                        <deliveryPoint></deliveryPoint>
                    </SelfDelivery>
                    <GoodsMeasure>
                        <weight></weight>
                        <volume>1</volume>
                        <amount></amount>
                        <statisticalValue></statisticalValue>
                    </GoodsMeasure>
                </Order>
                </DeliveryRequest>';
            
            //header('Content-type: application/xml; charset=utf-8');
            //exit($this->xml);
        
            try
            {
                $answer = $this->place('/Inbox/zabor_' . $date . '.xml');
            }
            catch (Exception $e)
            {
                throw new Exception ('Create new request error: ' . $e->getMessage());
            }
        }


        public function xml2array($xml) {

            if (is_string($xml)) $xml = simplexml_load_string($xml);

            return json_decode(json_encode($xml),TRUE);
        }


        public function sendStatus($orders) {

            $action = self::ACTION_STATUS;
            $unique_id = $action . date('_YmdH');

            if (count($orders) == 0) return false;

            $this->xml =

                '<?xml version="1.0" encoding="utf-8"?>
                <DeliveryRequest xmlns="http://www.imlogistic.ru/schema/request/v1">
                <Message>
                    <sender>' . $this->apiKey . '</sender>
                    <recipient>001</recipient>
                    <issue>' . date('Y-m-d\Тh:i:s') . '</issue>
                    <reference>' .$unique_id . '</reference>
                    <version>1.0</version>
                    <test>1</test>
                </Message>';

            foreach ((array) $orders AS $row)
            {
                $o = new basket($row);

                $o->getAddress();

                $o->address['city'] = cityId2name($o->address['city_id']);
                $o->basket_sum = basketId2sum($o->id) - $o->user_basket_payemnt_partical;

                switch ($o->user_basket_delivery_type)
                {
                    case 'IMlog':
                        if ($o->user_basket_payment_type == 'cashon')
                            $service = '24';
                        else
                            $service = '24КО';
                        break;

                    case 'IMlog_self':
                        if ($o->user_basket_payment_type == 'cashon')
                            $service = 'С24';
                        else
                            $service = 'С24КО';
                        break;
                }

                //
                $this->xml .=
                    '<Order>
                            <number>' . $o->user_basket_id . '</number>
                            <action>'.$action.'</action>
                            <Condition>
                                <service>' . $service . '</service>
                                <Delivery>
                                    <issue>'    .date('Y-m-d', strtotime($o->logs['deliverydate'][0]['result'])) . '</issue>
                                </Delivery>
                            </Condition>
                            <Region>
                                <departure>МОСКВА</departure>
                                <destination>' . strrtoupper($o->address['city']) . '</destination>
                            </Region>
                            <Consignee>
                                <Address>
                                    <line>' . $o->address['address'] . '</line>
                                    <city>' . $o->address['city'] . '</city>
                                    <postCode></postCode>
                                </Address>
                                <RepresentativePerson>
                                    <name>' . $o->address['name'] . '</name>
                                    <Communication>
                                        <telephone1>' . $o->address['phone'] . '</telephone1>
                                    </Communication>
                                </RepresentativePerson>
                            </Consignee>';

                if (!empty($o->address['external_id']))

                    $this->xml .= '<SelfDelivery><deliveryPoint>' . $o->address['external_id'] . '</deliveryPoint></SelfDelivery>';

                $this->xml .= '
                            <GoodsMeasure>
                                <volume>1</volume>
                                <amount>' . ((in_array($service, array('24', 'С24', 'В24'))) ? 0 : $o->basket_sum) . '</amount>
                                <statisticalValue>' . $o->basket_sum . '</statisticalValue>
                            </GoodsMeasure>
                            <GoodsItems>';

                foreach ($o->data['goods'] AS $g)
                {
                    $this->xml .= '<Item>';

                    $this->xml .= '<productNo>'   . $g['user_basket_good_id'] . '</productNo>';

                    $this->xml .= '</Item>';
                }

                $this->xml .= '
                            </GoodsItems>

                        </Order>';


            }

            $this->xml .= '</DeliveryRequest>';

            //header('Content-type: application/xml; charset=utf-8');
            //exit($this->xml);

            try
            {
                $this->place(self::DIR_INBOX . $unique_id . '.xml');
            }
            catch (Exception $e)
            {
                throw new Exception ('Create new request error: ' . $e->getMessage());
            }
        }


        public function checkStatus($file = '') {

            if (!$file) 
                 $file = self::ACTION_STATUS . date('_YmdH', time()-3600).'_Re.xml';
        
            $xml_array = $this->xml2array($this->loadFile($file));

            if (isset($xml_array['Order']) && count($xml_array['Order'])) {
            
                foreach ($xml_array['Order'] as $order) {

                    if ($order['orderStatus']==5 || $order['orderStatus']==3) {

                        $basket = new basket($order['number']);
                        if (!$basket->logs['track']) {
                            $basket->log('track', 'IMlog_deny', null);
                        }
                    }
                }
            }

            $this->deleteFile(self::DIR_OUTBOX . $file);
        }

        /**
         * Получить штрих-код принятых к доставке заказов
         */
        public function getBarcodes() {
                
            $files = array(
                self::ACTION_CREATE . date('_Ymd', time()).'_Re.xml', 
                self::ACTION_CREATE . date('_Ymd', time()).'_1_Re.xml', 
                self::ACTION_CREATE . date('_Ymd', time()).'_2_Re.xml',
                self::ACTION_CREATE . date('_Ymd', time()).'_3_Re.xml',);
        
            foreach ($files AS $file)
            {
                try
                { 
                    $xml_array = $this->xml2array($this->loadFile($file));
        
                    if (isset($xml_array['Order'])) {
                        
                        if ($xml_array['Order']['number']) {
                            $xml_array['Order'] = array(0 => $xml_array['Order']);
                        }
                        
                        foreach ((array) $xml_array['Order'] as $order) {
                            if ($order['BarcodeList']) {
                                $basket = new basket( $order['number']);
                                if (!$basket->logs['barCode']) {
                                    $basket->log('barCode', $order['BarcodeList']['Volume']['barcode'], $order['BarcodeList']['Volume']['encodedBarcode']);
                                    $basket->user_basket_delivered_code = $order['BarcodeList']['Volume']['barcode'];
                                    $basket->save();
                                }
                            }
                        }
                    }
                }
                catch (Exception $e)
                {
                    printr($file . ': ' .  $e->getMessage());
                }
            }
        }
        
        /**
         * Разместить новую заявку на доставку
         * @param string $action - тип заявки
         * @param array  $orders - номера заказов
         * @param string [$date] - дата исполнения заявки
         */
        public function delivery($action = self::ACTION_CREATE, $orders)
        {
            if ($action != self::ACTION_CREATE) 
                $action = self::ACTION_CREATE;

            if (count($orders) == 0)
                return false;

            $unique_id = $action . date('_Ymd');

            $this->xml = 
            
                '<?xml version="1.0" encoding="utf-8"?>
                <DeliveryRequest xmlns="http://www.imlogistic.ru/schema/request/v1">
                <Message>
                    <sender>' . $this->apiKey . '</sender>
                    <recipient>001</recipient>
                    <issue>' . date('Y-m-d\Тh:i:s') . '</issue>
                    <reference>' . $unique_id . '</reference>
                    <version>1.0</version>
                    <test>0</test>
                </Message>';
            
                foreach ((array) $orders AS $row)
                {
                    $o = new basket($row);
                    
                    // Если не указана дата доставки то заявка не отправляется
                    if (!$o->logs['deliverydate']) {
                        continue;
                    }
                    
                    $o->basket_sum = basketId2sum($o->id) - $o->user_basket_payment_partical;
                    
                    switch ($o->user_basket_delivery_type)
                    {
                        case 'IMlog':
                            if ($o->user_basket_payment_type == 'cashon' || $o->user_basket_payment_confirm == 'false')
                                $service = '24КО';
                            else
                                $service = '24';
                            break;
                            
                        case 'IMlog_self':
                            if ($o->user_basket_payment_type == 'cashon' || $o->user_basket_payment_confirm == 'false')
                                $service = 'С24КО';
                            else
                                $service = 'С24';
                            break;
                    }

                    if (!$o->logs['admin_deliverytime'][0]['result']) {
                        if ($o->logs['deliverydate'] && date('H:i', strtotime($o->logs['deliverydate'][0]['result'])) != '00:00') {
                            $o->logs['admin_deliverytime'][0]['result'] = date('H:i', strtotime($o->logs['deliverydate'][0]['result']));
                        } else {
                            $o->logs['admin_deliverytime'][0]['result'] = '10:00';
                        }
                    } else {
                        preg_match("|с (.*) до (.*)|", $o->logs['admin_deliverytime'][0]['result'], $mathches);
                        
                        //$o->logs['admin_deliverytime'][0]['result'] = (intval($mathches[1]) < 10 ? '0' . intval($mathches[1]) : intval($mathches[1])) . ':00';
                        $o->logs['admin_deliverytime'][0]['result'] = (intval($mathches[1]) < 10 ? '10' : intval($mathches[1])) . ':00';
                        $timeTo = (intval($mathches[2]) > 18 ? '18' : intval($mathches[2])) . ':00';
                    }
                    
                    $this->xml .= 
                        '<Order>
                            <number>' . $o->user_basket_id . '</number>
                            <action>'.$action.'</action>
                            <Condition>
                                <service>' . $service . '</service>
                                <Delivery>
                                    <issue>'    . date('Y-m-d', strtotime($o->logs['deliverydate'][0]['result'])) . '</issue>
                                    <timeFrom>' . $o->logs['admin_deliverytime'][0]['result'] . '</timeFrom>
                                    <timeTo>' . ($timeTo ? $timeTo : '18:00') . '</timeTo>
                                </Delivery>
                                <comment>' . stripslashes($o->logs['user_comment'][0]['result']) . '</comment>
                            </Condition>
                            <Region>
                                <departure>МОСКВА</departure>
                                <destination>' . strrtoupper($o->address['city']) . '</destination>
                            </Region>
                            <Consignee>
                                <Address>
                                    <line>' . $o->address['address'] . '</line>
                                    <city>' . $o->address['city'] . '</city>
                                    <postCode></postCode>
                                </Address>
                                <RepresentativePerson>
                                    <name>' . $o->address['name'] . '</name>
                                    <Communication>
                                        <telephone1>' . $o->address['phone'] . '</telephone1>
                                    </Communication>
                                </RepresentativePerson>
                            </Consignee>';

                    if (!empty($o->address['external_id']))

                        $this->xml .= '<SelfDelivery><deliveryPoint>' . $o->address['external_id'] . '</deliveryPoint></SelfDelivery>';

                    $this->xml .= '
                            <GoodsMeasure>
                                <weight>' . $o->getBasketWeight() . '</weight>
                                <volume>1</volume>
                                <amount>' . ((in_array($service, array('24', 'С24', 'В24'))) ? 0 : $o->basket_sum) . '</amount>
                                <statisticalValue>' . ($o->basket_sum + $o->user_basket_payment_partical). '</statisticalValue>
                            </GoodsMeasure>
                            <GoodsItems>';
                            
                            foreach ($o->data['goods'] AS $g)
                            {
                                $this->xml .= '<Item>';
                                
                                $this->xml .= '<productNo>'   . $g['user_basket_good_id'] . '</productNo>';
                                $this->xml .= '<productName>' . $g['style_name'] . ', ' . $g['good_name'] . '</productName>';
                                $this->xml .= '<amountLine>'  . $g['price'] . '</amountLine>';
                                $this->xml .= '<statisticalValueLine>' . $g['price'] . '</statisticalValueLine>';
                                
                                $this->xml .= '</Item>';
                            }
                            
                            $this->xml .= '
                            </GoodsItems>
                            
                        </Order>';
                }
                
            $this->xml .= '</DeliveryRequest>';
            
            //header('Content-type: application/xml; charset=utf-8');
            //exit($this->xml);
        
            try
            {
                file_put_contents(ROOTDIR.'/logs/'. $unique_id . '.xml',$this->xml);
                $answer = $this->place(self::DIR_INBOX . $unique_id . '.xml');
            }
            catch (Exception $e)
            {
                throw new Exception ('Create new request error: ' . $e->getMessage());
            }
        }
        
        /**
         * Сравнить справочник точек самовывоза Iml.ru и того что есть в нашей базе данных
         */
        public function checkDeliveryPoints()
        {
            if (__CLASS__ == 'logistic') 
            {
                //$xml = $this->place('http://api.iml.ru/list/sd');
                
                $url = 'http://api.iml.ru/list/sd';
    
                $ch = curl_init();
                
                curl_setopt_array($ch, array(
                    CURLOPT_URL            => $url,
                    CURLOPT_USERPWD        => $this->apiKey . ':' . $this->apiPas,
                    CURLOPT_RETURNTRANSFER => TRUE,
                    CURLOPT_TIMEOUT        => 300
                ));
                
                $h = curl_exec($ch); 
                
                if (empty($h))
                {
                    throw new Exception ('Request answer is empty');
                }
                
                $err = curl_errno($ch);
                
                if (empty($err))
                {
                    // получаем код ответа
                    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    
                    if ($http_code == 200) {}
                    else
                    {
                        switch ($http_code)
                        {
                            case '401': $err = 'Логин и пароль не верны'; break;
                            case '403': $err = 'Доступ запрещен'; break;
                            case '404': $err = 'Файл или папка не найдены'; break;
                            case '409': $err = 'Файл уже существует'; break;
                            case '413': $err = 'Слишком большой запрос'; break;
                            case '422': $err = 'Пустой файл (данные не найдены)'; break;
                            case '423': $err = 'Ошибка создания или удаления файла'; break;
                        }
                    }
                }
                
                $xml = json_decode($h);
                
                //printr($xml, 1);
                //$p->ClosingDate 
                //$p->OpeningDate
                //exit();
                
                $sth1 = App::db()->prepare("SELECT `id` FROM `city` WHERE `name` = :name LIMIT 1");
                //$sth2 = App::db()->prepare("SELECT `id`, `external_id` FROM `delivery_points` WHERE `city_id` = :city AND `service` = 'IMlog_self' AND `address` LIKE :addr");
                $sth2 = App::db()->prepare("SELECT `id`, `external_id` FROM `delivery_points` WHERE `city_id` = :city AND `service` = 'IMlog_self' AND `external_id` = :n");
                
                $sth3 = App::db()->prepare("INSERT INTO `delivery_points` SET
                                    `service` = :service,
                                    `city_id` = :city_id,
                                    `name` = :name,
                                    `address` = :address,
                                    `schema` = :schema,
                                    `external_id` = :external_id
                                ");
                
                $sth4 = App::db()->prepare("INSERT IGNORE INTO `delivery_services` SET
                                    `service` = :service,
                                    `city_id` = :city_id,
                                    `cost` = :cost,
                                    `time` = :time,
                                    `time1` = :time1,
                                    `time2` = :time2
                                ");
                
                $added_counter = 1;
                    
                foreach ($xml AS $p)
                {
                    if (!(string) $p->ClosingDate || (string) $p->ClosingDate == '29.04.16')
                    {
                        $c = trim((string) $p->RegionCode);
                        $a = trim((string) $p->Address);
                        $n = (int) $p->RequestCode;
    
                        //printr("$c, $a ($n)");
                        
                        $sth1->execute(array('name' => $c));
                        
                        if ($city = $sth1->fetch()) {
                            
                            //$sth2->execute(array('city' => $city['id'], 'addr' => trim('%' . $a . '%')));
                            $sth2->execute(array('city' => $city['id'], 'n' => $n));
                            
                            if ($point = $sth2->fetch()) {
                                //printr($point);
                                if ($point['external_id'] != $n) {
                                    printr('point "' . $c . ' (' . $city['id'] . ')' . ', '. $a . '": Номера не совпадают: db - ' . $point['external_id'] . ', xml - ' . $n);
                                }    
                            }  else {
                                /*
                                printr(array(
                                    'service' => 'IMlog_self',
                                    'city_id' => $city['id'],
                                    'name' => $a,
                                    'address' => $a,
                                    'schema' => $a,
                                    'external_id' => $n,
                                ));
                                */
                                $sth3->execute(array(
                                    'service' => 'IMlog_self',
                                    'city_id' => $city['id'],
                                    'name' => $a,
                                    'address' => $a,
                                    'schema' => $a,
                                    'external_id' => $n,
                                ));
                                
                                $sth4->execute(array(
                                    'service' => 'IMlog_self',
                                    'city_id' => $city['id'],
                                    'cost' => 155,
                                    'time' => 'второй рабочий день',
                                    'time1' => 2,
                                    'time2' => 2,
                                ));
                                
                                $added[] = $added_counter . '. point "' . $c . ' (' . $city['id'] . ')' . ', '. $a . ' (' . $n . ')": not founded (addedd)';
                                
                                $added_counter++;
                            }   
                        } else {
                            printr('city "' . $c . '" not founded');  
                        }
                    } else {
                      // printr('point "' . $p->Address . '" is closed "' . (string) $p->ClosingDate . '"');
                    }
                }
            }

            return $added;
        }
    
        /**
         * Отправить заказчикам смс что их заказ ожидает выдачи в пункте самовывоза
         */
        public function customerNotification()
        {
            foreach ($this->getOrders(['OrderStatus' => 0, 'State' => 10]) as $o) 
            {
                $o['DeliveryDate'] = date("Y-m-d 00:00:00", strtotime($o['DeliveryDate']));
                
                if ($o['DeliveryPoint'] > 0 && getDateDiff($o['DeliveryDate'], NOW, 0, 'd') > 1 && getDateDiff($o['DeliveryDate'], NOW, 0, 'd') < 7 && $o['Phone']) {
                    App::sms()->send($o['Phone'], 'Напоминаем, ваш заказ от maryjane находится на пункте самовывоза IML.');
                }
            }   
        }
    }