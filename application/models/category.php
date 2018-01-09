<?php

    namespace application\models;

    use \smashEngine\core\App AS App;

    class category extends \smashEngine\core\models\NSModel {

        /**
         * @var имя таблицы в БД для хранения экземпляров класса
         */
        protected static $dbtable = 'categorys';
        
        /**
         * Поиск узла по slug
         * @param string $slug
         */
        public static function findNodeBySlug($slug) 
        {
            $sth = App::db()->prepare("SELECT `id` FROM `" . self::$dbtable . "` WHERE `slug` = ? LIMIT 1");
            $sth->execute([$slug]);
            $foo = $sth->fetch();
            
            return $foo['id'];
        }
        
        public function getAdditionFields($imploded = true) {
            $sth = App::db()->prepare("SELECT * FROM `" . categoryField::db() . "` WHERE `category_id` = ?");
            $sth->execute([$this->id]);
            foreach ($sth->fetchAll() AS $f) {
                if ($imploded) {
                    $values = [];
                    foreach (json_decode($f['value'], 1) AS $v) {
                        $values[] = $v['value'];
                    }
                    $f['value'] = implode(', ', $values);
                } else {
                    $f['value'] = json_decode($f['value'], 1);
                }
                $rows[] = $f;
            }
            return $rows;
        }
    }