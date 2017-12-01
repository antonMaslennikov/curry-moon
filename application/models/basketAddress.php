<?php
    namespace application\models;
    
    use \smashEngine\core\App AS App; 
    use \DateTime;
    use \PDO;
    use \Exception;
    
    /**
     * 
     */
    class basketAddress extends \smashEngine\core\Model 
    {
        public $id = 0;
        
        public $info = array();
        
        /**
         * @var имя таблицы в БД для хранения экземпляров класса
         */ 
        public static $dbtable = 'basket__address';
        
        public function __construct($id = null)
        {
            $this->id = (int) $id;
            
            if ($this->id > 0)
            {
                $r = App::db()->query(sprintf("SELECT * FROM `" . self::$dbtable . "` WHERE `id` = '%d' LIMIT 1", $this->id));
                
                if ($r->rowCount() == 1)
                {
                    $this->info = $r->fetch();
                }
                else 
                {
                    throw new Exception(__CLASS__ . ' ' . $this->id . ' not found', 1);
                }
            }
        }
    }