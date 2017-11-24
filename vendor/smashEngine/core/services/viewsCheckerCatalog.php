<?php

    namespace smashEngine\core\services;

    use \smashEngine\core\exception\serviceScriptException;
    
    class viewsCheckerCatalog extends viewsChecker 
    {
        function __construct($file) {
                
            parent::__construct($file);
            
            $this->pattern = '\/catalog\/(?!.*styles).+\/[0-9]{4,6}\/(.*)';
        }
        
        public function save()
        {
            if (count($this->goods) == 0) {
                return;
            }
            
            $sth1 = \application\core\App::db()->prepare("INSERT INTO `" . \application\models\good::$visitstable . "`
                                       SET
                                            `ip`      = :ip,
                                            `good_id` = :gid,
                                            `style_id`= :sid,
                                            `page`    = :page,
                                            `date`    = :date");
            
            $sth2 = \application\core\App::db()->prepare("UPDATE `" . \application\models\good::$dbtable . "` 
                                        SET 
                                            `visits_catalog` = `visits_catalog` + :v, 
                                            `visits_2month` = `visits_2month` + :v
                                        WHERE 
                                            `good_id` = :gid
                                        LIMIT 1");
            
            foreach ($this->goods as $gId => $ips) 
            {
                foreach ($ips as $ip => $pages) 
                {
                    foreach ($pages as $page => $time) 
                    {
                        $foo = explode('/', trim($page, '/'));
                        
                        $sid = 0;
                        
                        if ($foo[3]) {
                            if (!$sid = self::$styles[$foo[3]]) {
                                self::$styles[$foo[3]] = $sid = styleSlug2styleId($foo[3]);
                            }
                        }
                       
                        $sth1->execute([
                            'ip' => ip2long($ip),
                            'gid' => $gId,
                            'sid' => (int) $sid,
                            'page' => $foo[0],
                            'date' => $time,
                        ]);
                    }
                    
                    $sth2->execute([
                        'gid' => $gId,
                        'v' => count($pages),
                    ]); 
                }
            }
        }
    }