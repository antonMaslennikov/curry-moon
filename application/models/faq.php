<?php
    
    namespace application\models;
    
    use \smashEngine\core\App AS App; 
    use \PDO;
    use \Exception;

    class faq extends \smashEngine\core\Model 
    {
        public $id   = 0;
        public $info = array();
    
        public static $dbtable = 'faq';
    
        static $audience = array(
            'all'       => array('title' => 'Все'),
            'buyers'    => array('title' => 'Покупатели'),
            'designers' => array('title' => 'Дизайнеры'),
            'partners'  => array('title' => 'Партнёры'),
        );  
        
        /**
         * Получить данные по группе faq
         */
        public static function getGroup($groupId)
        {
            $sth = App::db()->prepare("SELECT `title`, `text`, `group` FROM `" . self::$dbtable . "` WHERE `group` = ? AND `visible` = '1' ORDER BY `order` DESC");
            $sth->execute(array($groupId));
            $out = [];
            foreach ($sth->fetchAll() AS $foo) {
                $foo['text'] = stripslashes($foo['text']);
                $out[] = $foo;
            }
            return $out;
        }
    }