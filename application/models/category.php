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
    }