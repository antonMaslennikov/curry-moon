<?php

    namespace application\models;
    
    use \smashEngine\core\App AS App; 
    use \PDO;
    use \Exception; 

    class basketItem extends \smashEngine\core\Model
    {
        public $id   = 0;
        public $info = array();
        
        public static $dbtable = 'basket__items';
        
        function __construct($id = null)
        {
            $this->id = (int) $id;
            
            if (!empty($this->id))
            {
                $r = App::db()->prepare("SELECT * FROM `" . self::$dbtable . "` WHERE `user_basket_good_id` = ? LIMIT 1");
                $r->execute([$this->id]);
                
                if ($r->rowCount() == 1) 
                {
                    $this->info = $r->fetch();
                    
                    return $this->info;
                } 
                else 
                    throw new Exception (__CLASS__ . ' ' . $this->id . ' not found');
            }
        }
    }