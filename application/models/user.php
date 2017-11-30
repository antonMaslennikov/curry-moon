<?
    namespace application\models;
    
    use \smashEngine\core\App AS App;
    use \PDO;
    use \stdClass;
    use \Exception;
    use \ZipArchive;
    use \shortUrl;
    use \S3Thumb;
    use Geo;
    
    /**
     * Класс работы с пользователямими
     */
    
    class user extends \smashEngine\core\Model
    {
        public $id;
        public $authorized = FALSE;
        public $session;
        
        /**
         * @var имя таблицы в БД для хранения экземпляров класса
         */ 
        public static $dbtable = 'users';
        
        public static $dbtable_meta = 'users__meta';
        
        /**
         * @var скидочные диапазоны, по ним расчитывается возврат бонусов в зависимости от кол-ва доставленных заказов
         */
        public static $blranges = array(
            array('min' => 1,  'max' => 1,      'color' => '9966CC'),
            array('min' => 2,  'max' => 2,      'color' => '3366FF'),
            array('min' => 3,  'max' => 3,      'color' => '009966'),
            array('min' => 4,  'max' => 9,      'color' => 'FF6600'),
            array('min' => 10, 'max' => 44,     'color' => 'FF0000'),
            array('min' => 45, 'max' => 100000, 'color' => '000')
        );
        
        /**
         * @param int $id номер пользователя
         */
        function __construct($id, $import = null)
        {
            $this->id = (int) $id;
            $this->info = [];
            
            if ($this->id > 0)
            {
                $this->getInfo();
            }
        }
        
         
        public function getschema()
        {
            $r = App::db()->query("SHOW COLUMNS FROM `" . self::$dbtable . "`");
            
            foreach ($r->fetchAll() AS $f) {
                $this->info['schema'][$f['Field']] = $f;
            }
            
            return $this->info['schema'];
        }
        
        public function getInfo()
        {
            $r = App::db()->prepare("SELECT * FROM `" . self::$dbtable . "` WHERE `user_id` = :id LIMIT 1");
                
            $r->execute(['id' => $this->id]);
            
            if ($r->rowCount() == 1)
            {
                $this->info = $r->fetch();
            
                // если логин пользователя зашифрован через ****
                preg_match("/\*\*\*\*/", $this->user_login, $matches);
                
                if (count($matches) > 0) {
                    if ($matches[0] && !empty($this->user_phone)) {
                        $this->user_login_decoded = $this->user_phone;
                    } elseif ($matches[0] && !empty($this->user_email)) {
                        $this->user_login_decoded = $this->user_email;
                    }
                }

                $this->user_phone_truncated = substr($this->user_phone, -4);
                    
                // ожидающие выплаты бонусы
                $this->info['user_bonuses_wait'] = $this->getBonuses(0);
            
                if (!empty($this->user_url)) {
                    $this->user_url = trim(str_replace('http://', '', stripslashes($this->user_url)), '/');
                }
                
                if (!empty($this->user_ip)) {
                    $this->user_ip = long2ip($this->user_ip);
                }
            }
            else 
            {
                throw new Exception("user " . $this->id . " not found", 1);
            }
        }
        
        /**
         * Получить данные по сессии активного посетителя
         * и создать экземпляр класса из этих данных
         */
        public static function load()
        {
            // кука уже установлена на клиенте
            if (isset($_COOKIE['session']) && strlen($_COOKIE['session']) < 40) 
            {
                $sth = App::db()->prepare("SELECT * FROM `sessions` WHERE `session_id` = :sid LIMIT 1");
                
                $sth->execute(['sid' => $_COOKIE['session']]);
                
                /**
                 * Такая сессия в БД не обнаружена (истекла по времени хранения):
                 *  - пользователь не авторизован
                 *  - заводим новую сессию в базе
                 */
                if (!$session = $sth->fetch())
                {
                    if (!$bot = SpiderDetect($_SERVER['HTTP_USER_AGENT']))
                    {
                        $session = array('session_id' => md5(time() . mt_rand()), 'session_time' => time() + 2592000);
                        
                        $sth = App::db()->prepare("INSERT INTO `sessions` (`session_id`, `user_id`, `session_time`, `session_logged`) VALUES (:session_id, '', :session_time, '0')");
               
                        $sth->execute($session);
                        
                        // Обновляем куку
                        setcookie('session', $session['session_id'], time() + 2592000, '/', AppDomain);
                    } 
                }
                else
                {
                    /**
                     * Кука есть на клиенте и есть на сервере и время сессии истекло
                     */
                    if ($session['session_time'] <= time())
                    {
                        // Продлеваем сессию в базе
                        $sth = App::db()->prepare("UPDATE LOW_PRIORITY `sessions` SET `session_time` = :time WHERE `session_id` = :sid LIMIT 1");
                        
                        $sth->execute(array(
                            'sid' => $session['session_id'],
                            'time' => time() + 2592000,
                        ));
                        
                        // Продлеваем куку
                        setcookie('session', $_COOKIE['session'], time() + 2592000, '/', AppDomain);
                    }
                }
            } 
            // куки нет на клиенте
            else 
            {
                // если спамер потделал куку
                if (strlen($_COOKIE['session']) >= 40) {
                    throw new Exception('Некорректная запись сессии', 2);
                }
                
                if (!$bot = SpiderDetect($_SERVER['HTTP_USER_AGENT']))
                {
                    $session = array('session_id' => md5(time() . mt_rand()), 'session_time' => time() + 2592000);
                    
                    setcookie('session', $session['session_id'], $session['session_time'], '/', AppDomain);
                    
                    $sth = App::db()->prepare("INSERT INTO `sessions` (`session_id`, `user_id`, `session_time`, `session_logged`) VALUES (:session_id, '', :session_time, '0')");
                    
                    $sth->execute($session);
                }
            }
            
            try
            {
                $U = new self($session['user_id']);
                
                $U->session = $session;
                
                if (intval($session['user_id']) > 0 && $session['session_logged'] == 1) {
                    $U->authorized = true;
                } else {
                    $U->authorized = false;
                }
            }
            catch (Exception $e)
            {
                // пользователь не найден в базе данных (возможно удалён по какой-то причине)
                if ($e->getCode() == 1) 
                {
                    $U = new self;
                    $U->authorized = false;
                    
                    // заводим ему новую сессию
                    $U->session = array('session_id' => md5(time() . mt_rand()), 'session_time' => time() + 2592000);
                    
                    setcookie('session', $U->session['session_id'], $U->session['session_time'], '/', AppDomain);
                    
                    $sth = App::db()->prepare("INSERT INTO `sessions` (`session_id`, `user_id`, `session_time`, `session_logged`) VALUES (:session_id, '', :session_time, '0')");
                    
                    $sth->execute($U->session);
                }
            }
            
            $U->geoLocation();

            if ($U->authorized) {
                
                $upd = [];
                
                if (empty($U->user_city)) {
                    $upd['user_city'] = cityName2id($U->city);
                }
                
                if ($U->user_ip != $_SERVER['REMOTE_ADDR']) {
                    $upd['user_ip'] = ip2long($_SERVER['REMOTE_ADDR']);
                }
                
                if (count($upd) > 0) {
                    $U->change($upd);
                }
            }
            
            return $U;
        }

        /**
         * Изменить сессионный параметр
         * @param array $data массив с полями и значениями
         */
        public function setSessionValue($data)
        {
            foreach ($data as $k => $v) {
                if (!in_array($k, ['user_id', 'session_time', 'session_logged', 'session_short', 'user_basket_id'])) {
                    throw new Exception("Недопустимое поле \"{$k}\"");
                }
                
                $updates[] = "`$k` = '" . addslashes($v) . "'";
                
                $this->session[$k] = $v;
            }
            
            if (count($updates) > 0) {
                App::db()->query("UPDATE `sessions` SET " . implode(', ', $updates) . " WHERE `session_id` = '" . $this->session['session_id'] . "' LIMIT 1");
            }
        }
        
        /**
         * Сохранить текущий экземпляр объекта в базу
         */
        public function save()
        {
            foreach ($this->info as $k => $v) {
                if (!is_array($v) && !is_object($v))
                    $rows[$k] = "`$k` = '" . addslashes(trim($v)) . "'";
            }

            // вырезаем все поля которых нет в схеме таблицы
            $rows = array_intersect_key($rows, $this->schema);
            
            // редактирование
            if ($this->id > 0)
            {
                App::db()->query(sprintf("UPDATE `%s` SET %s WHERE `user_id` = '%d' LIMIT 1", self::$dbtable, implode(',', $rows), $this->id));
            }
            // создание
            else
            {
                if (count($rows) == 0)
                {
                    $rows['user_register_date'] = "`user_register_date` = NOW()";
                }
                
                App::db()->query(sprintf("INSERT INTO `%s` SET %s", self::$dbtable, implode(',', $rows)));
                $this->id = App::db()->lastInsertId();
            }
        }
        
        /**
         * создать нового пользователя
         */
        function create($data)
        {
            if (!$data['user_login'])
            {
                $maxuid = App::db()->query("SELECT (MAX(`user_id`) + 1) AS n FROM `users` LIMIT 1")->fetch();
                $data['user_login'] = 'user' . $maxuid['n'];
                $data['user_is_fake'] = 'true';
            }
            
            if (!$data['user_password'])
            {
                $data['user_password'] = md5(SALT . $this->getPassword());
            }
            
            $data['user_status'] = 'active';
            
            foreach ($data as $f => $v) {
                if (!empty($f)) {
                    $out[] = "`" . addslashes($f) . "` = '" . addslashes($v) . "'";
                    $this->info[$f] = $v;
                }
            }
            
            App::db()->query("INSERT INTO `users` SET " . implode(',' , $out) . ", `user_last_login` = NOW(), `user_ip` = INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "')");
            
            $this->id = App::db()->lastInsertId();
            
            return $this;
        }
        
        public static function createNew($data)
        {
            $U = new self;
            
            foreach ($data as $f => $v) {
                if (!empty($f)) {
                    $U->{$f} = $v;
                }
            }
            
            if (!$data['user_login'])
            {
                $maxuid = App::db()->query("SELECT (MAX(`user_id`) + 1) AS n FROM `users` LIMIT 1")->fetch();
                $U->user_login = 'user' . $maxuid['n'];
                $U->user_is_fake = 'true';
            }
            
            if (!$data['user_password'])
            {
                $U->password = substr(md5(time()), 0, 10);
                $U->user_password = md5(SALT . $U->password);
            }
            
            $U->user_status = 'active';
            $U->user_ip = $_SERVER['REMOTE_ADDR'];
            
            $U->save();
            
            return $U;
        }
        
        /**
         * Авторизовать пользователя
         */
        function authorize()
        {
            if (!empty($this->id))
            {
                if ($this->user_status != 'deleted' && $this->user_status != 'banned')
                {
                    $this->setSessionValue(['user_id' => $this->id, 'session_logged' => 1]);
                    
                    $this->getInfo();
                    
                    $this->authorized = true;

                    $this->change(array('user_last_login' => date("Y-m-d H:i")));

                    // если ещё не начали оформлять новый заказ
                    // ищем последний недоделанный заказ в предыдущей сессии и перекидываем его в текущую сессию
                    if ($this->session['session_id'] && empty($this->session['user_basket_id']))
                    {
                        $sth = App::db()->prepare("SELECT ub.`user_basket_id` FROM `user_baskets` ub, `user_basket_goods` ubg WHERE ub.`user_id` = :user AND ub.`user_basket_status` = 'active' AND ubg.`user_basket_id` = ub.`user_basket_id` ORDER BY ub.`user_basket_id` DESC LIMIT 1");
                        
                        $sth->execute(array('user' => $this->id));
                        
                        if ($last = $sth->fetch()) {
                            $this->setSessionValue(['user_basket_id' => $last['user_basket_id']]);
                        }
                    }
                }
                else
                {
                    if ($this->user_status != '')
                        throw new Exception('Ваш аккаунт заблокирован', 2);
                }
            } 
        }
        
        /**
         * Сгенерировать секретный код для всеких там засекреченных штук :) 
         */
        public static function getSecretCode($n = 25, $numeric = false)
        {
            $code = md5(SALT . uniqid());
            
            // оставляем только цифры
            if ($numeric)
            {
                $code = str_split($code);
                
                foreach ($code AS $k => $l)
                {
                    if (!is_numeric($l))
                        unset($code[$k]);
                }
                
                $code = implode('', $code);
            }
            
            return substr($code, 0, $n);
        }
        
        
        
        /**
         * Сгенерировать новый пароль
         */
        function getPassword()
        {
            $this->info['password'] = $this->password = substr(md5(time()), 0, 10);
            
            return $this->info['password'];
        }
        
        /**
         * Меняет пароль пользователя
         */
        function setPassword($pass)
        {
            try
            {
                $this->change(array('user_password' => md5(SALT . trim($pass))));
            }
            catch (exception $e) {}
        }
        
        /**
         * Активация аккаунта
         */
        function activate()
        {
            $firstTime = false;
            
            if ($this->user_status != 'active') {
                $firstTime = true;
            }
            
            $this->user_activation = 'done';
            $this->user_status = 'active'; 
            $this->user_is_fake = 'false';
            $this->user_last_login = NOW;
            
            $this->save();
            
            if ($firstTime) {
                // "активируем" все лайки юзера
                $r = App::db()->query(sprintf("SELECT `good_id` FROM `good_likes` WHERE `user_id` = '%d' AND `negative` = '0'", $this->id));
                
                foreach ($r->fetchAll() AS $g) 
                {
                    App::db()->query(sprintf("UPDATE `goods` SET `good_likes` = `good_likes` + 1 WHERE `good_id` = '%d' LIMIT 1", $g['good_id']));
                }
            }
        }
        
        /**
         * @param string имя параметра
         * @param string значение параметра
         * 
         * @return int кол-во затронутых строк 
         */
        function setMeta($name, $value)
        {
            $r = App::db()->prepare("INSERT INTO `" . self::$dbtable_meta . "` SET `user_id` = :user, `meta_name` = :name, `meta_value` = :value ON DUPLICATE KEY UPDATE `meta_value` = :value, `date` = NOW()");
                                    
            $r->execute(['user' => $this->id, 'name' => $name, 'value' => $value ? $value : '']);
                                    
            $this->meta->$name = $value;
            
            return $r->rowCount();
        }
        
        /**
         * @return array массив meta-параметров
         */
        function getmeta()
        {
            $r = App::db()->query(sprintf("SELECT `meta_name`, `meta_value` FROM `" . self::$dbtable_meta . "` WHERE `user_id` = '%d'", $this->id));
            
            $this->meta = new \stdClass();
            
            if ($r->rowCount() > 0)
            {
                foreach ($r->fetchAll() AS $m) {
                    $this->info['meta']->$m['meta_name'] = stripslashes($m['meta_value']);
                }
            }
            
            return $this->info['meta'];
        }
        
        /**
         * Удалить значенеи meta-параметра
         * @param string name
         */
        function delMeta($name)
        {
            unset($this->meta->$name);
            return App::db()->query(sprintf("DELETE FROM `" . self::$dbtable_meta . "` WHERE `user_id` = '%d' AND `meta_name` = '%s' LIMIT 1", $this->id, addslashes($name)));
        }
        
        /**
         * Редактировать поля
         */
        function change($data)
        {
            if (count($data) > 0)
            {
                $keys = $values = [];
                
                foreach ($data as $f => $v) {
                    if (!empty($f) && in_array($f, array_keys($this->schema))) {
                        $keys[] = "`" . $f . "` = ?";
                        $values[] = $v;
                        
                        if ($f == 'user_ip') {
                            $v = long2ip($v);
                        }
                        
                        $this->info[$f] = $v;
                    }
                }
                
                if (count($keys) > 0) {
                    $sth = App::db()->prepare("UPDATE `users` SET " . implode(', ', $keys) . " WHERE `user_id` = '" . $this->id . "' LIMIT 1");
                    $sth->execute($values);
                }
            }
        }
        
        /**
         * начислить / списать бонусы
         */
        function addBonus($count, $comment, $info, $status = 1, $who)
        {
            if (empty($count))
                return false;
            
            // если пользователь использовал "обещанный платёж",
            // то при первом приходе на его счёт бонусов списываем с него нужную сумму
            if ($this->user_bonus < 0 && $count > 0 && $status == 1) 
            {
                $can_donate = $count;
                
                $r = App::db()->query("SELECT `user_bonus_id`, `user_bonus_count`, `user_bonus_info` FROM `user_bonuses` WHERE `user_id` = '" . $this->id . "' AND `user_bonus_info` LIKE 'awaiting_donate%' AND `user_bonus_count` > '0' ORDER BY `user_bonus_time` ASC");
                
                foreach ($r->fetchAll() AS $d)
                {
                    $recive = min($can_donate, $d['user_bonus_count']);
                    
                    $dst = new user(str_replace('awaiting_donate', '', $d['user_bonus_info']));
                    
                    $dst->addBonus($recive, 'Получены от пользователя ' . $this->user_login, 'donateMe', 1, $this->id);
                    
                    $can_donate -= $recive;
                    
                    App::db()->query("UPDATE `user_bonuses` SET `user_bonus_count` = `user_bonus_count` - {$recive} WHERE `user_bonus_id` = '" . $d['user_bonus_id'] . "' LIMIT 1");
                    
                    if ($can_donate <= 0)
                        break;
                }
            }
        
            App::db()->query(
                     "INSERT 
                      INTO `user_bonuses` 
                      SET
                        `user_id`            = '" . $this->id . "',
                        `user_bonus_count`   = '" . intval(abs($count)) . "',
                        `user_bonus_comment` = '" . addslashes($comment) . "',
                        `user_bonus_time`    = NOW(),
                        `user_bonus_inc`     = '" . (($count > 0) ? 'inc' : 'dec') . "', 
                        `user_bonus_info`    = '" . addslashes($info) . "', 
                        `user_bonus_status`  = '" . intval($status) ."',
                        `user_bonus_author`  = '" . $who . "'");
            
            $id = App::db()->lastInsertId();
            
            if ($status == 1)
            {
                $this->change(array('user_bonus' => $this->user_bonus + $count));
            }

            /**
             * отслеживание состояния счёта конкретных пользователей
             */
            if (in_array($this->id, array(104874, 215017, 213056, 104874, 215237)) && $count < 0 && ($this->user_bonus + $count) < 1000) 
            {
                App::mail()->send(array('marysel@maryjane.ru', $this->id), 621, array(
                    'user_login' => $this->user_login,
                    'user' => $this,
                ));
            }
            
            return $id;
        }
        
        /**
         * Получить сумму бонусов
         * @param int $status - выплаченные / ожидающие / обменные
         */
        function getBonuses($status = 1)
        {
            if ($status == 1)
                return (int) $this->info['user_bonus'];
            
            $foo = App::db()->query(sprintf("SELECT SUM(IF(`user_bonus_inc` = 'inc', `user_bonus_count`, -1 * `user_bonus_count`)) AS s FROM `user_bonuses` WHERE `user_id` = '%d' AND `user_bonus_status` = '%d' ", $this->id, $status))->fetch();
            
            return (int) $foo['s'];
        }
        
        
        /**
         *  Начислить / выплату кэш пользователю
         * @param int $paymentValue сумма 
         * @param string $description описание
         * @param string $type тип операции
         * @param int $direction вывод или начисление
         * @param int $status подтверждена ли операция 
         * @param int $basketId номер корзины
         * @param int $pdata реквизиты операции
         * @param user $user кто совершил операцию
         * 
         * @return int last inserted id
         */
        function pay($paymentValue, $description, $type = 'printshop', $direction = 0, $status = 1, $basketId = null, $pdata = 0, user $user = null)
        {
            if ($paymentValue > 0 && $this->meta->pay_disabled)
                return;
            
            $r = App::db()->prepare("INSERT INTO `printshop_payments` 
                                   SET
                                    `user_id`         = :user,
                                    `user_basket_id`  = :basketId, 
                                    `price`           = :paymentValue,
                                    `description`     = :description,
                                    `direction`       = :direction, 
                                    `status`          = :status,
                                    `add_date`        = NOW(),
                                    `type`            = :type,
                                    `payment_data_id` = :pdata,
                                    `author`          = :author");
                         
            $r->execute([
                'user' => $this->id,
                'basketId' => $basketId ? $basketId : '', 
                'paymentValue' => $paymentValue,
                'description' => $description ? $description : '',
                'direction' => $direction,
                'status' => $status,
                'pdata' => $pdata ? $pdata : 0,
                'type' => $type ? $type : 'printshop',
                'author' => $user ? $user->id : '',
            ]);
                                    
            $pid = App::db()->lastInsertId();
            
            return $pid;
        }

        /**
         * получить состояние рублёвог счёта пользователя
         */
        function balance()
        {
            $foo = App::db()->query("SELECT SUM(`price`) AS s FROM `printshop_payments` WHERE `user_id` = '" . $this->id . "' AND `status` = '1'")->fetch();
            return (int) $foo['s'];
        }
        
        
        /**
         * определение города пользователя
         */
        function geoLocation()
        {
            $geo = new geo(array('charset' => 'utf-8'));
            $r = $geo->get_value();

            $this->city        = $r['city'];
            $this->country     = $r['country'];
            $this->city_region = $r['region'];
            
            return $r;
        }

        
        /**
         * Расчёт процента отчислений бонусов за позцию в зависимости от байер-левела пользователя
         */
        function buyerLevel2discount()
        {
            if (isset($this->info['buyer_discount']))
                return $this->info['buyer_discount'];
                
            $bl = intval($this->user_delivered_orders);
        
            if ($bl == 1 || $bl == 0)
                $this->buyer_discount = 5;
            elseif ($bl == 2)
                $this->buyer_discount = 7;
            elseif ($bl == 3)
                $this->buyer_discount = 9;
            elseif ($bl >= 4 && $bl <= 9)
                $this->buyer_discount = 10;
            elseif ($bl >= 10 && $bl <= 44)
                $this->buyer_discount = 15;
            elseif ($bl > 44 && $bl < 100000)
                $this->buyer_discount = 20;
            elseif ($bl >= 100000 && $bl < 500000)
                $this->buyer_discount = 30;
            elseif ($bl >= 500000)
                $this->buyer_discount = 35;
        
            return $this->buyer_discount;
        }
        
        /**
         * Преобразование числового значения дизайн-уровня пользователя в картинку NEW
         *
         * @param int $dl
         * @return string
         */
        public static function designerLevel2Picture($dl = 0)
        {
            if ($dl < 0)
                $dlp = 'mjteam';
            elseif($dl == 0)
                $dlp = 0;
            elseif ($dl >= 1 && $dl <= 2)
                $dlp = 1;
            elseif ($dl >= 3 && $dl <= 6)
                $dlp = 2;
            elseif ($dl >= 7 && $dl <= 15)
                $dlp = 3;
            elseif ($dl >= 16 && $dl <= 30)
                $dlp = 4;
            elseif ($dl >= 31 && $dl < 40)
                $dlp = 5;
            elseif ($dl >= 40)
                $dlp = 'kazan';

            return("<img src='http://www.maryjane.ru/images/dlp/designer_level-$dlp.gif' border='0' title='$dl' alt='$dl' class='dl' />");
        }
        
        /**
         * Получить промо-код для указанного пользователя
         * @return string
         */
        function getPromoCode() 
        {
            $r = App::db()->query(sprintf("SELECT `informer_key` FROM `informers` WHERE `user_id` = '%d' AND `informer_type` = 'personal' LIMIT 1", $this->id));
            
            if (!$code = $r->fetch())
            {
                $r = App::db()->query(sprintf("INSERT INTO `informers` (`user_id`, `informer_type`, `informer_href`) VALUES ('%d', 'personal', '')", $this->id));
                $r = App::db()->query("UPDATE `informers` SET `informer_key` = MD5(LAST_INSERT_ID()) WHERE `informer_id` = LAST_INSERT_ID()");
                $r = App::db()->query("SELECT `informer_key` FROM `informers` WHERE `informer_id` = LAST_INSERT_ID()");
                $code = $r->fetch();
            }
            
            return $code['informer_key'];
        }
        
        /**
         * получить информацию о платформе пользователя
         * - гаджет или комп
         * - браузер
         */
        function getClient()
        {
            $this->info['client'] = get_browser(null);
            
            return $this->info['client'];
        }
        
        /**
         * Подписать на уведомления
         * 
         * @param int id объекта
         * @param string типа объекта
         * 
         * @return int affected rows
         */
        function watch($id, $type)
        {
            $types = array('blog' => 0, 'good' => 1, 'newgood' => 1, 'gallery' => 2);
            
            if (isset($types[$type]))
            {
                $r = App::db()->query(sprintf("INSERT IGNORE INTO `user_watches`
                     SET 
                        `user_id`     = '%d',
                        `object_id`   = '%d',
                        `object_type` = '%d'
                     ON DUPLICATE KEY UPDATE 
                        `user_notified` = 'false'", 
                     $this->id, $id, $types[$type]));
                     
                return $r->rowCount();
            }
            else
                throw new Exception ('unknown  subscription ' . $type);
        }
        
        /**
         * Отписать пользователя от уведомлений о коментариях к объекту или от всех уведомлений
         * 
         * @param int id объекта
         * @param string типа объекта
         * 
         * @return int affected rows
         */
        function unwatch($id, $type)
        {
            $types = array('blog' => 0, 'good' => 1, 'newgood' => 1, 'gallery' => 2);
            $r = App::db()->query(sprintf("DELETE FROM `user_watches` WHERE `user_id` = '%d' " . ((!empty($id)) ? " AND `object_id` = '%d' AND `object_type` = '%d'" : ''), $this->id, $id, $types[$type]));
            return $r->rowCount();
        }
        
        /**
         * проверить следит ли пользователем за каментами к объекту
         * @param int id объекта
         * @param string типа объекта
         * 
         * @return int num rows
         */
        function isWatching($id, $type)
        {
            $types = array('blog' => 0, 'good' => 1, 'newgood' => 1, 'gallery' => 2);
            if (isset($types[$type]))
            {
                $r = App::db()->query(sprintf("SELECT `id` FROM `user_watches` WHERE `user_id` = '%d' AND `object_id` = '%d' AND `object_type` = '%d' LIMIT 1", $this->id, $id, $types[$type]));
            }
            return $r->rowCount();
        }
        
        /**
         * Определить подписан ли пользователь
         * @param int $id номер подписки
         */
        function isSubscribed($id)
        {
            $foo = App::db()->query(sprintf("SELECT `mail_list_id` FROM `mail_list_subscribers` WHERE `user_id` = '" . intval($this->id) . "' AND ABS(`mail_list_id`) = '%d' LIMIT 1", $id))->fetch();
            return (int) $foo['mail_list_id'];
        }
        
        /**
         * Подписать пользователя на рассылку
         * @param int $id номер подписки
         */
        function subscribe($id)
        {
            App::db()->query(sprintf("INSERT INTO `mail_list_subscribers` SET `user_id` = '%d', `user_ip` = INET_ATON('%s'), `mail_list_id` = '%d'", $this->id, $_SERVER['REMOTE_ADDR'], $id));
        }
        
        /**
         * Отписать пользователя
         * @param int $id номер подписки
         */
        function unsubscribe($id)
        {
            $s = $this->isSubscribed($id);
            
            if ($s >= 0) {
                if ($s == 0)
                    $this->subscribe(-1 * $id);
                else
                    App::db()->query(sprintf("UPDATE `mail_list_subscribers` SET `mail_list_id` = -1 * `mail_list_id` WHERE `user_id` = '%d' AND `mail_list_id` = '%d' LIMIT 1", $this->id, $id));
            }
            
            return true;
        }
        
        /**
         * Получить список подписок пользователя
         */
        function getsubscriptions()
        {
            $r = App::db()->query("SELECT `mail_list_id` FROM `mail_list_subscribers` WHERE `user_id` = '" . $this->id . "'");
            
            $this->info['subscriptions'] = array();
            
            foreach($r->fetchAll() AS $row) {
                $this->info['subscriptions'][abs($row['mail_list_id'])] = $row['mail_list_id'];
            }
            
            return $this->info['subscriptions'];
        }
        
        /**
         * список лайков пользователя
         * @return array
         */
        function getLikes($name = 'good_preview', $negative = 0)
        {
            if (!$this->authorized)
                return array();

            $this->info['likes'] = array();

            $r = App::db()->prepare("SELECT `good_id` FROM `good_likes` WHERE `user_id` = :uid AND `pic_name` = :name AND `good_id` > '0' AND `negative` = :neg");
            
            $r->execute(['uid' => $this->id, 'name' => $name, 'neg' => $negative]);
            
            foreach ($r->fetchAll() AS $l) {
                $this->info['likes'][$l['good_id']] = 1;
            }

            return $this->info['likes'];
        }
        
        
        /**
         * лог по отчисления с продаж за период
         * @param date $start начало периода
         * @param date $end окончание периода
         * 
         * @return array массив с работами и их проданное количество + суммарное отчисление по ней
         */
        function getAssignments($start, $end)
        {
            $Thumb = new S3Thumb(array('ic1.maryjane.ru', 'ic2.maryjane.ru', 'ic3.maryjane.ru', 'ic4.maryjane.ru'), S3AccessKey, S3SecretKey, S3CryptKey);
            
            $q = "SELECT 
                    g.`good_id`, 
                    g.`good_name`, 
                    s.`style_name`,
                    s.`style_color`,
                    s.`style_id`,
                    c.`name` AS color_name,
                    ub.`user_basket_delivered_date`,
                    SUM(ubg.`user_basket_good_quantity`) AS q,
                    SUM(pp.`price`) AS p,
                    SUM(pp.`price`) AS total_price,
                    SUM(IF(sc.`cat_parent` = 1, ubg.`user_basket_good_quantity`, 0)) AS quantityP,
                    SUM(IF(sc.`cat_parent` > 1, ubg.`user_basket_good_quantity`, 0)) AS quantityA,
                    CONCAT(g.`good_id`,  s.`style_id`) AS gs,
                    (SELECT p.`picture_path` FROM `good_pictures` gp, `pictures` p WHERE gp.`good_id` = ubg.`good_id` AND gp.`pic_name` = CONCAT('catalog_preview_', gs.`style_id`) AND gp.`pic_id` = p.`picture_id` LIMIT 1) AS picture_path
                  FROM 
                    `goods` AS g, 
                    `user_basket_goods` AS ubg, 
                    `user_baskets` AS ub, 
                    `printshop_payments` pp, 
                    `good_stock` gs, 
                    `good_stock_colors` c, 
                    `styles` s, 
                    `styles_category` sc
                  WHERE
                        g.`user_id`                        = '" . $this->id . "'
                    AND g.`good_id`                        = ubg.`good_id`
                    AND ub.`user_basket_id`                = ubg.`user_basket_id`
                    AND ub.`user_basket_status`            = 'delivered'
                    AND ubg.`user_basket_good_payment_id`  = pp.`id`
                    AND pp.`direction`                     = '0'
                    AND pp.`status`                        = '1'
                    AND pp.`type`                          = 'printshop'
                    AND ubg.`good_stock_id`                = gs.`good_stock_id`
                    AND gs.`style_id`                      = s.`style_id`
                    AND s.`style_category`                 = sc.`id` 
                    AND s.`style_color` = c.`id`
                    " . (($start) ? "AND pp.`add_date` >= '{$start}'" : '') . "
                    " . (($end)   ? "AND pp.`add_date` <= '{$end}'" : '') . "
                  GROUP BY gs
                  ORDER BY ub.`user_basket_date` DESC";
            
            $r = App::db()->query($q);
        
            if ($r->rowCount() > 0) 
            {
                foreach ($r->fetchAll() AS $row)
                {
                    $row['good_name'] = stripslashes($row['good_name']);
                    
                    if ($row['picture_path'])
                        $row['preview'] = $Thumb->url($row['picture_path'], styleCategory::$BASECATSid[$row['cat_parent']] == 'laptops' ? 152 : 85);
                    else
                        $row['preview'] = '/ajax/generatePreview/?good_id=' . $row['good_id'] . '&style_id=' . $row['style_id'] . '&width=' . (styleCategory::$BASECATSid[$row['cat_parent']] == 'laptops' ? 152 : 85) . '&side=' . styleCategory::$BASECATS[styleCategory::$BASECATSid[$row['cat_parent']]]['def_side'];
                    
                    $row['date'] = date('d.m.y H:i', strtotime($row['user_basket_delivered_date']));
                    
                    $sold[] = $row;
                }
                
                return $sold;
            }
            
            return false;
        }
        
        
        /**
         * Получить количество работ пользователя определённого статуса
         * @param string/array - статусы работ
         */
        function getUserGoodsCount($statuses)
        {
            if (!is_array($statuses))
                $statuses = (array) $statuses;
                
            $r = App::db()->query(sprintf(
                    "SELECT COUNT(*) AS c
                     FROM `goods` g
                     WHERE 
                            g.`user_id` = '%d'
                        " . ((count($statuses) > 0) ? "AND g.`good_status` IN ('" . implode("', '", $statuses) . "')" : '') . "
                        AND g.`good_visible` = 'true'", $this->id));
            
            $foo = $r->fetch();
            
            return (int) $foo['c'];
        }

        
        /**
         * получить заполненный контракт с пользователем
         * @param (string) имя контракта
         * @return (docx) документ-word
         */
        function contract($name)
        {
            // путь до шаблона
            $tpl_path    = 'application/views/contract/tpl/' . (($name == 'license') ? 'contract.license' : 'contract') . '.docx';
            // папка для распаковки шаблона
            $extr_folder = 'application/views/templates_c/act.docx.tmp.' . uniqid() . DS;
            // путь до файла с текстом
            $text_file   = $extr_folder . 'word' . DS . 'document.xml';
            // пути до изображений
            $img_file1    = $extr_folder . 'word' .  DS. 'media' .  DS. 'image1.png';
            $img_file2    = $extr_folder . 'word' .  DS. 'media' .  DS. 'image2.png';
            $img_file3    = $extr_folder . 'word' .  DS. 'media' .  DS. 'image3.png';
            // имя выходного файла
            $file_name   = 'contract.' . $this->id . (($name == 'license') ? '.license' : '') . '.docx';
            // полный путь до выходного файла
            $out_file    = 'application/views/templates_c/' . $file_name;
            
            $find    = array('CONTRACTNO', 'NOWDATE', 'USERNAME', 'BIRTHDAY', 'ADDRESS', 'PASSPORT', 'PASSGIVEN', 'PASSWHENGIVEN', 'PASSSCAN1', 'PASSSCAN2', 'PFRSCAN');
            if ($this->authorized)
                $replace = array(($this->user_login . '_' . date('dm') . substr(date('Y'), 2)), datefromdb2textdate(NOWDATE), $this->meta->contract_fio, $this->meta->birthday, stripslashes($this->meta->address_propiska), $this->meta->passport, $this->meta->passport_given, $this->meta->passport_when_given, pictureId2path($this->meta->passport_scan1), pictureId2path($this->meta->passport_scan2), pictureId2path($this->meta->pfr_scan));
            else
                $replace = 'XXX';

            
            $word = new ZipArchive;

            if ($word->open($tpl_path) === TRUE)
            {
                createDir($extr_folder);

                if ($word->extractTo($extr_folder))
                {
                    // правим текст
                    $text = file_get_contents($text_file);
                    $text = str_replace($find, $replace, $text);

                    // заменяем картинки
                    copy(ROOTDIR . (($this->meta->passport_scan1) ? pictureId2path($this->meta->passport_scan1) : '/images/0.gif'), $img_file1);
                    copy(ROOTDIR . (($this->meta->passport_scan2) ? pictureId2path($this->meta->passport_scan2) : '/images/0.gif'), $img_file2);
                    copy(ROOTDIR . (($this->meta->pfr_scan)       ? pictureId2path($this->meta->pfr_scan)       : '/images/0.gif'), $img_file3);
                    
                    $f = fopen($text_file, 'w+');
                    fwrite($f, $text);
                    fclose($f);

                    $w = new ZipArchive;

                    if ($w->open($out_file, ZipArchive::CREATE) === TRUE)
                    {
                        for ($i=0; $i < $word->numFiles; $i++)
                        {
                            $w->addFile($extr_folder . $word->getNameIndex($i), $word->getNameIndex($i));
                        }

                        $w->close();
                        $word->close();

                        if (is_dir($extr_folder))
                            rmdirR($extr_folder);

                        return $out_file;
                    }
                    else
                        throw new Exception ('cannot save outputed file');
                }
                else
                    throw new Exception ('word template extracting error');
            }
            else
                throw new Exception ('word template not found');
        }
        
        
        /**
         * @return bool включено ли для пользователя смс-информирование
         */ 
        function smsInfo($id)
        {
            return ($this->meta->order_sms_info === '0') ? FALSE : TRUE;
        }
    
    
        function getavatar()
        {
            return self::userAvatar($this->id, 25);
        }
        
        function getavatar_medium()
        {
            return self::userAvatar($this->id, 50);
        }
        
        function getavatar_medium_path()
        {
            return self::userAvatar($this->id, 50, '', true);
        }

        /**
         * получить аватарку
         * @param int $userId - id-пользваотеля
         * @param int $size - ширина картинки
         */
        static function userAvatar($userId, $size='50', $no_avatar_name = null, $noimage = 0, $nocache = false)
        {
            if (is_file(ROOTDIR . IMAGEUPLOAD . '/avatars/avatar_' . substr(md5($userId),0,6) . '.gif'))
            {
                if ($size != 100) {
                    $avatar_path = IMAGEUPLOAD . '/avatars/avatar_' . substr(md5($userId),0,6) . '_tbn_' . $size . '.gif';
                } else {
                    $avatar_path = IMAGEUPLOAD . '/avatars/avatar_' . substr(md5($userId),0,6) . '.gif';
                }
            }
            else
            {
                if (!empty($no_avatar_name))
                    $avatar_path = $no_avatar_name;
                else {
                    if ($size == 100)
                        $avatar_path = '/images/designers/nophoto100.gif';
                    else
                        $avatar_path = '/images/designers/nophoto50.gif';
                }
            }
        
            $avatar_link = "<img src='" . $avatar_path . (($nocache) ? '?='.rand(1,99999) : '') . "' width='$size' height='$size' alt='' class='avatar' />";
        
            if ($noimage == 0)
                return $avatar_link;
            else {
                return $avatar_path;
            }
        }
        
        /**
         * получить логин
         * @param int $userId - id-пользваотеля
         * @param int $size - ширина картинки
         */
        static function userLogin($userId)
        {
            if (!empty($userId))
                $foo = App::db()->query("SELECT `user_login` FROM `users` WHERE `user_id` = '" . addslashes($userId) . "' LIMIT 1")->fetch();
            
            return $foo['user_login'];
        }
        
        
        public static function find($search)
        {
            if (!empty($search))
            {
                $search = addslashes($search);
                
                $r = App::db()->query("SELECT `user_id`
                          FROM `" . self::$dbtable . "`
                          WHERE 
                            `user_id` = '" . $search . "' OR `user_login` LIKE '" . $search . "' OR `user_email` LIKE '" . $search . "' OR `user_phone` LIKE '" . user::noralizePhone($search) . "'
                          LIMIT 1");
                          
                if ($r->rowCount() == 1) 
                {
                    $foo = $r->fetch();
                    return new self($foo['user_id']);
                } 
            }
        }
        
        public static function findAll($search)
        {
            $r = App::db()->query(sprintf("SHOW COLUMNS FROM `%s`", self::$dbtable));
            
            foreach ($r->fetchAll() AS $f) {
                $fields[$f['Field']] = $f['Field'];
            }
            
            $rows = array_intersect_key($search, $fields);
            
            foreach ($rows AS $k => $v) {
                $aq[] = "`" . $k . "` = '" . addslashes($v) . "'";
            }
            
            $r = App::db()->query("SELECT `user_id`
                      FROM `" . self::$dbtable . "`
                      WHERE 
                        1
                        " 
                        . (count($aq) ? ' AND ' . implode(' AND ', $aq) : '')
                );
                      
            if ($r->rowCount() > 0) 
            {
                foreach ($rows AS $k => $u) {
                    $result[] = $u;
                }
                
                return $result;
            } else {
                return false;
            }
        }
        
        /**
         * Получить промо-ссылку на выбранную страницу сайта
         * @param string $url - адрес страницы
         */
        public function getPromoUrl($url)
        {
            if (!$this->authorized) {
                throw new Exception('Вы должны быть авторизованы, чтобы получить код', 1);
                return;
            }
            
            if (empty($url)) {
                $url = '/catalog/' . $this->user_login . '/';
            } else {
                $url = str_replace(mainUrl, '', $url);
            }
            
            $sth = App::db()->prepare("SELECT `informer_id`, `informer_key`, `informer_comment`, `informer_short_code` FROM `informers` WHERE `user_id` = :user AND `informer_type` = 'personal' AND `informer_href` = :url LIMIT 1");
    
            $sth->execute(array(
                'user' => $this->id,
                'url' => $url,
            ));
    
            if ($sth->rowCount() == 0)
            {
                App::db()->query("INSERT INTO `informers` (`user_id`, `informer_type`, `informer_href`) VALUES ('" . $this->id . "', 'personal', '$url')");
                
                $inf = array('informer_id' => App::db()->lastInsertId());
                
                $inf['informer_key'] = md5($inf['informer_id']);
                $inf['informer_short_code'] = shortUrl::convertInt2Code($inf['informer_id']);
                
                App::db()->query("UPDATE `informers` SET 
                                    `informer_key` = '" . $inf['informer_key'] . "',
                                    `informer_short_code` = '" . $inf['informer_short_code']  . "' 
                                  WHERE 
                                    `informer_id` = '" . $inf['informer_id'] . "' 
                                  LIMIT 1");
                                  
              
            }
            else
            {
                $inf = $sth->fetch();
            }
            
            $uri = parse_url($url);
            $url = ($uri['scheme'] ? $uri['scheme']: 'http') . '://' . ($uri['host'] ? $uri['host'] : 'www.maryjane.ru') . '/informers/' . $inf['informer_key'] . '/';
            
            if ($inf['informer_short_code']) {
                return array(
                    'link' => '/promo/' . $inf['informer_short_code'] . '/',
                    'id' => $inf['informer_id'],
                    'comment' => $inf['informer_comment'],
                );
            }
        }
        
        public function addSelected($uid)
        {
            if ($uid == $this->id)
                return false;
            
            if (in_array($uid, array_keys($this->selected)))
                return false;
            
            $r = App::db()->query("INSERT INTO `selected` (`user_id`, `selected_id`, `add_date`) VALUES ('" . $this->id . "', '" . intval($uid) . "', '" . NOW . "')");
            
            if ($r->rowCount() == 1)
                $this->selected[$uid] = $uid;
            
            return true;
        }
        
        public function removeSelected($uid)
        {
            
        }
        
        public function getSelected()
        {
            $this->selected = array();
            
            $r = App::db()->query("SELECT * FROM `selected` WHERE `user_id` = '" . $this->id . "'");
            
            foreach ($r->fetchAll() AS $u) {
                $this->selected[$u['selected_id']] = $u;
            }
            
            return $this->selected;
        }
        
        public function profileQuickInfo()
        {
            // сколько "моих" работ добавлено в избранное (исключая работ на голосовании)
            $sth = App::db()->prepare("SELECT COUNT(*) FROM `good_likes` gl, `goods` g WHERE g.`good_id` = gl.`good_id` AND g.`user_id` = :user AND gl.`negative` = '0' AND g.`good_status` != 'voting'");
        
            $sth->execute(array('user' => $this->id));
            $foo = $sth->fetch();
            
            $meSelectedCount = $foo['c'];
        
            // Работ прислано и прошло в каталог
            $sth = App::db()->prepare("SELECT COUNT(DISTINCT(g.`good_id`)) AS c
                  FROM `goods` AS g
                  WHERE g.`user_id` = :user AND g.`good_visible` = 'true' AND g.`good_status` NOT IN ('new', 'deny', 'customize')");
                  
            $sth->execute(array('user' => $this->id));
            $foo = $sth->fetch();
            
            $goodsCount = $foo['c'];
            
            // Фотографий прислано
            $sth = App::db()->prepare("SELECT COUNT(*) AS c
                  FROM `gallery` AS ga, `goods` AS g, `pictures` AS p
                  WHERE ga.`gallery_picture_visible` = 'true' AND ga.`good_id` = g.`good_id` AND ga.`gallery_picture_author` = :user  AND p.`picture_id` = ga.`gallery_small_picture`");
            
            $sth->execute(array('user' => $this->id));    
            $foo = $sth->fetch();
            
            $pictCount = $foo['c'];
            
            // Постов в блог написано
            $sth = App::db()->prepare("SELECT COUNT(*) AS c
                  FROM `user_posts` AS up
                  WHERE up.`post_author` = :user AND up.`post_status` = 'publish'");
                  
            $sth->execute(array('user' => $this->id));    
            $foo = $sth->fetch();
                  
            $postCount = $foo['c'];
            
            // сколько работ добавил в избранное
            $sth = App::db()->prepare("SELECT COUNT(DISTINCT(g.`good_id`)) AS c
                  FROM `good_likes` gl, `goods` g
                  WHERE gl.`user_id` = :user AND gl.`good_id` = g.`good_id` AND g.`good_visible` = 'true' AND gl.`negative` = '0'");
                    
            $sth->execute(array('user' => $this->id));        
            $foo = $sth->fetch();
                    
            $selectedCount = $foo['c'];
            
            return array(
                'user_avatar'           => user::userAvatar($this->id, 100),
                'user_id'               => $this->id,
                'user_login'            => str_replace('.livejournal.com', '', $this->user_login),
                'user_designer_level'   => designerLevelToPicture($this->user_designer_level),
                'my_selected_works'     => (int) $selectedCount,
                'me_liked'              => (int) $meSelectedCount,
                'goodsCount'    => $goodsCount,
                'pictCount'     => $pictCount,
                'postCount'     => $postCount,
                'selectedCount' => $selectedCount,
            );
        }
    
        /**
         * получить стандартный вид телефона
         */
        public static function noralizePhone($phone)
        {
            $phone = str_replace(array(' ', '(', ')', '-', '+'), '', trim(strtolower($phone)));
                                    
            if (strpos($phone, '8') === 0) {
                $phone = '7' . substr($phone, 1);
            }
            
            return $phone;
        }
    
        /**
         * оповещение на мыло о недавно просмотренных работах
         * авторизованным
         */
        public static function notify1()
        {
            $mtpl = 866;
            
            $sth = App::db()->query("SELECT gv.*, u1.`user_login` AS author_login, u2.`user_login`, u2.`user_email`, g.`good_name`
                                     FROM 
                                        `good__visits` gv, 
                                        `" . self::$dbtable . "` u1, 
                                        `" . self::$dbtable . "` u2
                                            LEFT OUTER JOIN `mail_messages` mm ON u2.`user_id` = mm.`user_id` AND mm.`mail_message_template_id` = '{$mtpl}', 
                                        `" . good::$dbtable . "` g
                                     WHERE
                                            gv.`visitor_id` = u2.`user_id`
                                        AND gv.`user_id` = u1.`user_id`
                                        AND gv.`good_id` = g.`good_id`
                                        AND gv.`date` > NOW() - INTERVAL 2 HOUR
                                        AND gv.`good_id` > '0'
                                        AND gv.`page` = 'catalog'
                                        AND mm.`mail_message_id` IS NULL
                                     ORDER BY gv.`date` DESC");
            
            $users = [];
                                     
            foreach ($sth->fetchAll() AS $row) {
                if (!$users[$row['user_id']]) {
                    $users[$row['user_id']] = ['id' => $row['visitor_id'], 'login' => $row['user_login'], 'email' => $row['user_email']];
                }
                
                $users[$row['user_id']]['goods'][$row['good_id']] = $row;
            }
            
            //printr($users);
            
            foreach ($users as $u) {
                        
                if ($u['id'] != 27278) {
                    continue;
                }
                
                if (count($u['goods']) >= 3 || $u['id'] == 27278) {
                    App::mail()->send($u['id'], $mtpl, ['data' => $u]);
                }
            }
        }
    }

