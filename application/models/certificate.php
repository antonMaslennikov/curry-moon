<?php

    namespace application\models;
    
    use \smashEngine\core\App AS App; 
    use \smashEngine\core\exception\appException;

    use \PDO;
    use \Exception;

    class certificate extends \smashEngine\core\Model
    {
        public $id   = 0;
        public $info = array();
        
        public static $types = array(
            'percent' => array('title' => 'Процент'),
            'amount'  => array('title' => 'Сумма'),
        );
        
        /**
         * @param string тип сертификата: на скидку или на бонусы
         */
        public $type;
        
        /**
         * @param string имя таблицы в БД для хранения экземпляров класса
         */
        protected static $dbtable = 'certifications';
        
        /**
         * @param string на сколько использований по умолчанию создаётся мельти-дисконтная карта
         */
        public static $multiDefault = 10000;
        
        
        function __construct($id = null)
        {
            $this->id = (int) $id;
            
            if (!empty($this->id))
            {
                $r = App::db()->query(sprintf("SELECT c.*
                          FROM `" . self::$dbtable . "` c
                          WHERE c.`id` = '%d'
                          LIMIT 1", $this->id));
                
                if ($r->rowCount() == 1) 
                {
                    $this->info = $r->fetch();
                    
                    if ($this->certification_style_categorys) {
                        $this->certification_style_categorys = json_decode($this->certification_style_categorys, 1);
                    }
                    
                    return $this->info;
                } 
                else 
                    throw new Exception ('certificate ' . $this->id . ' not found');
            }
        }
              
        /**
         * @param $code string - код сертификата
         * @return instance of class certificate - объект класс "сертификат"
         */
        public static function find($code)
        {
            if (!empty($code))
            {
                $r = App::db()->prepare("SELECT c.`id`
                          FROM `" . self::$dbtable . "` c
                          WHERE c.`certification_password` = ?
                          LIMIT 1");
                
                $r->execute([trim($code)]);
                
                if ($r->rowCount() == 1) 
                {
                    $row = $r->fetch();
                    return new self($row['id']);
                } 
                else 
                    throw new appException ('Сертификат с кодом "' . $code . '" не найден');
            }
        }
        
        /**
         * Найти список сертификатов
         * @param array $params список фильтров
         */
        public static function findAll($params)
        {
            $sth = App::db()->query("SELECT * 
                              FROM  `" . self::$dbtable . "` c
                              WHERE 
                                1
                                "
                                .
                                ($params['user'] ? " AND c.`certification_author` = '" . intval($params['user']) . "'" : '')
                                .
                                ($params['coupon'] ? " AND c.`coupon` = '1'" : '')
                                .
                                ($params['enabled'] ? " AND c.`certification_enabled` = '1'" : '')
                                .
                                " 
                              ORDER BY c.`add_date` DESC ");
             
             foreach ($sth->fetchAll() as $row) {
                 
                 if ($row['lifetime'] != '0000-00-00') {
                    $row['lifetime'] = datefromdb2textdate($row['lifetime']);
                 }
                 
                 $r[] = $row;
             }
                              
             return $r;
        }
        
        /**
         * Удалить сертификат 
         */
        public function delete()
        {
            App::db()->query("DELETE FROM  `" . self::$dbtable . "` WHERE `id` = '" . $this->id . "' LIMIT 1");
            return true;
        }
        
        /**
         * Сохранить текущий экземпляр объекта в базу
         */
        public function save()
        {
            foreach ($this->info as $k => $v) {
                $rows[$k] = "`$k` = '" . addslashes($v) . "'";
            }
            
            // вырезаем все поля которых нет в схеме таблицы
            $r = App::db()->query(sprintf("SHOW COLUMNS FROM `%s`", self::$dbtable));
            
            foreach ($r->fetchAll() AS $f) {
                $fields[$f['Field']] = $f['Field'];
            }
            
            $rows = array_intersect_key($rows, $fields);
            // end вырезаем все поля которых нет в схеме таблицы
            
            
            // редактирование
            if (!empty($this->id))
            {
                App::db()->query(sprintf("UPDATE `%s` SET %s WHERE `id` = '%d' LIMIT 1", self::$dbtable, implode(',', $rows), $this->id));
            }
            // создание
            else
            {
                App::db()->query(sprintf("INSERT INTO `%s` SET %s", self::$dbtable, implode(',', $rows)));
                $this->id = App::db()->lastInsertId();
            }
        }
    
        /**
         * активировать сертификат
         * @param user $user текущий пользователь
         * @param basket $basket текущая корзина
         */
        public function activate(user $user, basket $basket)
        {
            // защита от повторного активирования
            if ($_SESSION['certificate_activated_' . $this->id]) {
                throw new appException('Вы уже активировали данный купон', 1);
            }
            
            // проверки на валидность сертификата
            if ($this->certification_enabled <= 0) {
                throw new appException('Данный купон выключен', 2);
            }
            
            if (strtotime($this->lifestart) > time()) {
                throw new appException('Данный купон ещё рано использовать', 3);
            }
            
            if ($this->lifetime != '0000-00-00' && time() > strtotime($this->lifetime)) {
                throw new appException('Данный купон уже поздно использовать', 4);
            }
            
            if ($this->certification_multi == 0 && $this->certification_limit < 1) {
                throw new appException('Данный купон уже использован', 5);
            }
            
            switch ($this->certification_type)
            {
                // ПОДАРОК С ОСОБЫМИ УСЛОВИЯМИ
                case 'gift':
                    
                    switch ($this->certification_password) 
                    {
                        case 'gift300':
                            
                            $user->setMeta('givegifts', 'MJ');
                            
                            if (!$basket->logs['givegifts_firstorder']) {
                                $basket->log('givegifts_firstorder', 1);
                            }
                            
                            break;
                        
                        default:
                            break;
                    }
                    
                    break;
                    
                // ДИСКОНТНАЯ КАРТА (+ xx% процентов в корзинную скидку)
                case 'percent':
                    
                    $basket->basketChange(array(
                        'user_basket_discount' => $this->certification_value,
                        'user_basket_discount_description' => 'Скидка по дисконтной карте'));

                    $basket->log('activateDiscontCard', $this->id, $this->certification_value);
                    
                break;

                // СЕРТИФИКАТ (+ xx руб. к бонусам пользователя)
                case 'amount':
                    
                    $basket->log('activateCertificate', $this->id, $this->certification_value);
                    
                    $basket->basketChange(['user_basket_payment_partical' => $this->certification_value]);
                    
                break;
            }

            $_SESSION['certificate_activated_' . $this->id] = true;
        }
    }