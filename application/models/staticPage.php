<?php
    namespace application\models;
    
    use \smashEngine\core\App; 
    use \PDO;
    use \Exception;
    use \smashEngine\core\exception\appException;
    
    class staticPage extends \smashEngine\core\Model
    {
        protected static $dbtable = 'static_pages';
        
        public function __construct($id) 
        {
            parent::__construct($id);
            
            // ДОДЕЛАТЬ !!!
            $this->h1   = $this->h1_ru;
            $this->text = $this->text_ru;
        }


        public function findBySlug($slug) 
        {
            $sth = App::db()->prepare("SELECT * FROM `" . self::$dbtable . "` WHERE `slug` = ? AND status = 1 LIMIT 1");
            
            $sth->execute([urldecode($slug)]);
            
            if (!$page = $sth->fetch()) {
                throw new appException('Данная статичная страница не обнаружена', 1);
            }
            
            return new self($page['id']);
        }
    }