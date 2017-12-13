<?php
    namespace application\models;
    
    use \smashEngine\core\App; 
    use \PDO;
    use \Exception;
    use \smashEngine\core\exception\appException;
    
    class tag extends \smashEngine\core\Model
    {
        protected static $dbtable = 'tags';
        
        public function __construct($id) 
        {
            parent::__construct($id);
        }


        public function findBySlug($slug) 
        {
            $sth = App::db()->prepare("SELECT * FROM `" . self::$dbtable . "` WHERE `slug` = ? LIMIT 1");
            
            $sth->execute([urldecode($slug)]);
            
            if (!$tag = $sth->fetch()) {
                throw new appException('Данный тег не найден', 1);
            }
            
            return new self($tag['id']);
        }
    }