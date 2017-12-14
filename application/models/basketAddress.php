<?php
    namespace application\models;
    
    use \smashEngine\core\App AS App; 
    
    /**
     * 
     */
    class basketAddress extends \smashEngine\core\Model 
    {   
        /**
         * @var имя таблицы в БД для хранения экземпляров класса
         */ 
        public static $dbtable = 'basket__address';
        
        /**
         * Сохранить экземляр класса в базу
         */
        public function save()
        {
            if (!self::$dbtable) {
                throw new Exception('Не известна таблица для сохранения данных', 1);
            }
            
            foreach ($this->info as $k => $v) {
                $rows[$k] = "`$k` = '" . addslashes($v) . "'";
            }
            
            // вырезаем все поля которых нет в схеме таблицы
            $r = App::db()->query(sprintf("SHOW COLUMNS FROM `%s`", self::db()));
            
            foreach ($r->fetchAll() AS $f) {
                $fields[$f['Field']] = $f['Field'];
            }
            
            $rows = array_intersect_key($rows, $fields);
            // end вырезаем все поля которых нет в схеме таблицы
            
            // редактирование
            if (!empty($this->id))
            {
                App::db()->query(sprintf("UPDATE `%s` SET %s WHERE `id` = '%d' LIMIT 1", self::db(), implode(',', $rows), $this->id));
            }
            // создание
            else
            {
                App::db()->query(sprintf("INSERT IGNORE INTO `%s` SET %s ON DUPLICATE KEY UPDATE `order_date` = NOW()", self::db(), implode(',', $rows)));
                $this->id = App::db()->lastInsertId();
            }
        }
    }