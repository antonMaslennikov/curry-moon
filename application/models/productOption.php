<?php

    namespace application\models;

    use \smashEngine\core\App AS App;

    class productOption extends \smashEngine\core\Model {

        /**
         * @var имя таблицы в БД для хранения экземпляров класса
         */
        protected static $dbtable = 'product__options';
        
        public static function getAllProducts($filters) {
            
            foreach ($filters AS $o => $v) {
                $sth = App::db()->query("SELECT `product_id` FROM `" . self::db() . "` WHERE `option` = '{$o}' AND `value` IN ('" . implode("','", (array) $v) . "')");
                foreach ($sth->fetchAll() AS $p) {
                    $products[$o][] = $p['product_id'];
                }
            }
            
            $grouped_products = [];
            
            foreach ($products AS $k => $p) {
                if ($k == 0) {
                    $grouped_products = $p;
                } else {
                    $grouped_products = array_intersect($grouped_products, $p);
                }
            }
            
            return $grouped_products;
        }
        
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
                App::db()->query(sprintf("INSERT IGNORE INTO `%s` SET %s ON DUPLICATE KEY UPDATE `value` = '%s'", self::db(), implode(',', $rows), $this->value));
                $this->id = App::db()->lastInsertId();
            }
        }
    }