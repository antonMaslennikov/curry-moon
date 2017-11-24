<?php
    namespace application\models;
    
    use \smashEngine\core\App AS App; 
    use \PDO;
    use \Exception;

    class deliveryPoint extends \smashEngine\core\Model 
    {
        public $id   = 0;
        public $info = array();
        
        public static $dbtable = 'delivery_points';
        
        function __construct($id)
        {
            $this->id = (int) $id;
            
            if (!empty($this->id))
            {
                $r = App::db()->query(sprintf("SELECT *
                          FROM `" . self::$dbtable . "`
                          WHERE `id` = '%d'
                          LIMIT 1", $this->id));
                
                if ($r->rowCount() == 1) 
                {
                    $this->info = $r->fetch();
                    
                    $this->name = stripslashes($this->name);
                    $this->address = stripslashes($this->address);
                    $this->schema = nl2br(stripslashes($this->schema));
                    
                    return $this->info;
                } 
                else 
                    throw new Exception ('delivery point' . $this->id . ' not found');
            }
        }
              
        /**
         * Сохранить текущий экземпляр объекта в базу
         */
        public function save()
        {
            foreach ($this->info as $k => $v) {
                $rows[$k] = "`$k` = '" . mysql_real_escape_string($v) . "'";
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
        
        public static function dropCache() {
            App::memcache()->delete('order.v3_delivery_points');
            App::memcache()->delete('order.v3_delivery_services');
            App::memcache()->delete('order.v3_citys');
        }
        
        public static function findAll($params = null)
        {
            $sth = App::db()->prepare("SELECT dp.*
                                      FROM 
                                        `" . self::$dbtable . "` dp
                                      WHERE 
                                        1
                                        " 
                                        . ($params['city'] ? "AND dp.`city_id` = '" . intval($params['city']) . "'" : '') .
                                      "ORDER BY dp.`service`, dp.`active` DESC, dp.`time` DESC, dp.`name`");
                                        
            $sth->execute();
            
            foreach ($sth->fetchAll() as $p) 
            {
                $p['name'] = stripslashes($p['name']);
                $p['address'] = stripslashes($p['address']);
                $p['schema'] = stripslashes($p['schema']);
                $p['service_title'] = basket::$deliveryTypes[$p['service']]['title'];
                
                $points[] = $p;
            }
            
            return $points;
        }
    }