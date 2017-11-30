<?php

    namespace application\models;

    use \smashEngine\core\App AS App; 
    use \PDO;

    class mailSubscription extends \smashEngine\core\Model
    {        
        protected static $dbtable = 'mail__subscribers';
        
        protected $user;

        public function __construct(user $user) {
            $this->user = $user;
        }
        
        public function checkUserSubscriptionStatus() {
            
        }
        
        public static function checkEmailSubscriptionStatus($email) {
            $sth = App::db()->prepare("SELECT COUNT(*) AS c FROM `" . self::$dbtable . "` WHERE `email` = ?");
            $sth->execute([$email]);
            $foo = $sth->fetch();
            return $foo['c'];
        }
        
        public static function addEmailSubscription($email) {
            $sth = App::db()->prepare("INSERT INTO `" . self::$dbtable . "` SET `email` = ?, `ip` = ?");
            $sth->execute([$email, ip2long($_SERVER['REMOTE_ADDR'])]);
        }
    }