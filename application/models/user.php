<?
    namespace application\models;
    
    use \smashEngine\core\App AS App;
    use \smashEngine\core\helpers\Password;

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
         * @param int $id номер пользователя
         */
        function __construct($id=0, $import = null)
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
            $r = App::db()->prepare("SELECT * FROM `" . self::$dbtable . "` WHERE `id` = :id LIMIT 1");
                
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
                App::db()->query(sprintf("UPDATE `%s` SET %s WHERE `id` = '%d' LIMIT 1", self::$dbtable, implode(',', $rows), $this->id));
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
                $maxuid = App::db()->query("SELECT (MAX(`id`) + 1) AS n FROM `users` LIMIT 1")->fetch();
                $data['user_login'] = 'user' . $maxuid['n'];
                $data['user_is_fake'] = 'true';
            }
            
            if (!$data['user_password'])
            {
                $data['user_password'] = Password::hash($this->getPassword());
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

            $this->activateCode = md5(SALT .  $this->id);
            $this->activateLink = mainUrl . "/ru/users/activate/?userid=" . $this->id . "&key=" . $this->activateCode;

            App::mail()->send($this->id, 1, ['user' => $this]);
            
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
                $maxuid = App::db()->query("SELECT (MAX(`id`) + 1) AS n FROM `users` LIMIT 1")->fetch();
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
                        $sth = App::db()->prepare("SELECT ub.`id` FROM `" . basket::$dbtable . "` ub, `" . basketItem::$dbtable . "` ubg WHERE ub.`id` = :user AND ub.`user_basket_status` = 'active' AND ubg.`user_basket_id` = ub.`id` ORDER BY ub.`id` DESC LIMIT 1");
                        
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
                $r = App::db()->query(sprintf("SELECT `good_id` FROM `good_likes` WHERE `id` = '%d' AND `negative` = '0'", $this->id));
                
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
            $r = App::db()->prepare("INSERT INTO `" . self::$dbtable_meta . "` SET `id` = :user, `meta_name` = :name, `meta_value` = :value ON DUPLICATE KEY UPDATE `meta_value` = :value, `date` = NOW()");
                                    
            $r->execute(['user' => $this->id, 'name' => $name, 'value' => $value ? $value : '']);
                                    
            $this->meta->$name = $value;
            
            return $r->rowCount();
        }
        
        /**
         * @return array массив meta-параметров
         */
        function getmeta()
        {
            $r = App::db()->query(sprintf("SELECT `meta_name`, `meta_value` FROM `" . self::$dbtable_meta . "` WHERE `id` = '%d'", $this->id));
            
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
            return App::db()->query(sprintf("DELETE FROM `" . self::$dbtable_meta . "` WHERE `id` = '%d' AND `meta_name` = '%s' LIMIT 1", $this->id, addslashes($name)));
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
                    $sth = App::db()->prepare("UPDATE `users` SET " . implode(', ', $keys) . " WHERE `id` = '" . $this->id . "' LIMIT 1");
                    $sth->execute($values);
                }
            }
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
         * Определить подписан ли пользователь
         * @param int $id номер подписки
         */
        function isSubscribed($id)
        {
            $foo = App::db()->query(sprintf("SELECT `mail_list_id` FROM `mail_list_subscribers` WHERE `id` = '" . intval($this->id) . "' AND ABS(`mail_list_id`) = '%d' LIMIT 1", $id))->fetch();
            return (int) $foo['mail_list_id'];
        }
        
        /**
         * Подписать пользователя на рассылку
         * @param int $id номер подписки
         */
        function subscribe($id)
        {
            App::db()->query(sprintf("INSERT INTO `mail_list_subscribers` SET `id` = '%d', `user_ip` = INET_ATON('%s'), `mail_list_id` = '%d'", $this->id, $_SERVER['REMOTE_ADDR'], $id));
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
                    App::db()->query(sprintf("UPDATE `mail_list_subscribers` SET `mail_list_id` = -1 * `mail_list_id` WHERE `id` = '%d' AND `mail_list_id` = '%d' LIMIT 1", $this->id, $id));
            }
            
            return true;
        }
        
        /**
         * Получить список подписок пользователя
         */
        function getsubscriptions()
        {
            $r = App::db()->query("SELECT `mail_list_id` FROM `mail_list_subscribers` WHERE `id` = '" . $this->id . "'");
            
            $this->info['subscriptions'] = array();
            
            foreach($r->fetchAll() AS $row) {
                $this->info['subscriptions'][abs($row['mail_list_id'])] = $row['mail_list_id'];
            }
            
            return $this->info['subscriptions'];
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
                $foo = App::db()->query("SELECT `user_login` FROM `users` WHERE `id` = '" . addslashes($userId) . "' LIMIT 1")->fetch();
            
            return $foo['user_login'];
        }
        
        
        public static function find($search)
        {
            if (!empty($search))
            {
                $search = addslashes($search);
                
                $r = App::db()->query("SELECT `id`
                          FROM `" . self::$dbtable . "`
                          WHERE 
                            `id` = '" . $search . "' OR `user_login` LIKE '" . $search . "' OR `user_email` LIKE '" . $search . "' OR `user_phone` LIKE '" . user::noralizePhone($search) . "'
                          LIMIT 1");
                          
                if ($r->rowCount() == 1) 
                {
                    $foo = $r->fetch();
                    return new self($foo['id']);
                } 
            }
        }
        
        public static function findByEmail($search)
        {
            if (!empty($search))
            {
                $search = addslashes($search);
                
                $r = App::db()->prepare("SELECT `id` FROM `" . self::$dbtable . "` WHERE `user_email` LIKE ? LIMIT 1");
                
                $r->execute([$search]);
                
                if ($r->rowCount() == 1) 
                {
                    $foo = $r->fetch();
                    return new self($foo['id']);
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
            
            $r = App::db()->query("SELECT `id`
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
        
        protected function getRecoverCode() {
            return substr(md5(SALT . $this->user_login . $this->user_email . date('Y-m-d')), 2, 8);
        }
        
        public function sendRecoverEmail() {
            $code = $this->getRecoverCode();
            App::mail()->send($this->id, 2, ['uid' => $this->id, 'code' => $code, 'token' => md5($code)]);
        }
        
        public function compareRecoverCode($str) {
            return $str == $this->getRecoverCode();
        }
        
        public function compareRecoverToken($str) {
            return $str == md5($this->getRecoverCode());
        }
        
        public function resetPassword()
        {
            $this->password = substr(md5(time()), 0, 10);
            $this->user_password = md5(SALT . $this->password);
            $this->save();
            
            App::mail()->send($this->id, 3, ['user' => $this]);
        }
    }

