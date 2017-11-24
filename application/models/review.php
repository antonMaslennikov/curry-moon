<?
    namespace application\models;
    
    use \smashEngine\core\App AS App; 
    use \PDO;
    use \Exception;
    
    class review extends \smashEngine\core\Model
    {
		public $id   = 0;
		public $info = array();
		
		/**
		 * @param string имя таблицы в БД для хранения экземпляров класса
		 */
		public static $dbtable = 'reviews';
		
		
		function __construct($id)
		{
			$this->id = (int) $id;
			
			if (!empty($this->id))
			{
				$r = App::db()->query(sprintf("SELECT r.*
						  FROM `" . self::$dbtable . "` r
						  WHERE r.`id` = '%d'
						  LIMIT 1", $this->id));
				
				if ($r->rowCount() == 1) 
				{
					$this->info = $r->fetch();
					
					return $this->info;
				} 
				else 
					throw new Exception (__CLASS__ . ' ' . $this->id . ' not found');
			}
		}
		
        public function getuser()
        {
            if (!empty($this->user_id)) 
            {
                $this->info['user'] = new user($this->user_id);
                return $this->info['user'];
            }
        }
        
		/**
		 * Сохранить текущий экземпляр объекта в базу
		 */
		public function save()
		{
			foreach ($this->info as $k => $v) {
			    if ($k == 'text' && !$this->id)
                    $v = nl2br($v);
                
				$rows[$k] = "`$k` = '" . addslashes($v) . "'";
			}
			
			// вырезаем все поля которых нет в схеме таблицы
			$r = App::db()->query(sprintf("SHOW COLUMNS FROM `%s`", self::$dbtable));
			
			foreach ($r->fetchAll() AS $f) {
				$fields[$f['Field']] = $f['Field'];
			}
			
			$rows = array_intersect_key($rows, $fields);
			// end вырезаем все поля которых нет в схеме таблицы
			
			
			// редактирование
			if (!empty($this->id))
			{
				App::db()->query(sprintf("UPDATE `%s` SET %s WHERE `id` = '%d' LIMIT 1", self::$dbtable, implode(',', $rows), $this->id));
			}
			// создание
			else
			{
				App::db()->query(sprintf("INSERT INTO `%s` SET %s", self::$dbtable, implode(',', $rows)));
				$this->id = App::db()->lastInsertId();
                
                // запоминаем дату последнего оставленного отзыва, если пользователь авторизован
                //if ($this->user_id)
                //{
                    //$this->user->setMeta('review_sended', $this->id);
                //}
			}
		}
		
		public static function findAll($params)
		{
			$r = App::db()->query("SELECT t.*, u.`user_activation`
							  FROM `" . self::$dbtable . "` t
							     LEFT JOIN `users` u ON t.`user_id` = u.`user_id`
							  WHERE
							  	1
							  	"
								. 
								($params['approved'] ? "AND t.`approved` = '1'" : '')
                                . 
                                ($params['visible'] ? "AND t.`visible` = '" . addslashes($params['visible']) . "'" : '')
								. 
								(!empty($params['tpl']) ? "AND (t.`tpl` = '" . intval($params['tpl']) . "' || t.`tpl` = '')" : '')
								.
							"ORDER BY `time` DESC "
							. 
								($params['limit'] ? ' LIMIT ' . ($params['offset'] ? $params['offset'] . ',' : '') . $params['limit'] : ''));
                                
			foreach ($r->fetchAll() AS $row) 
			{
				$row['name'] = stripslashes($row['name']);
				$row['text'] = stripslashes($row['text']);
				$row['date'] = datefromdb2textdate($row['time'], 3);
				$row['time_rus'] = datefromdb2textdate($row['time'], 3);
				
				if ($row['user_id']) {
					$row['avatar'] = user::userAvatar($row['user_id']);
				}
				
				$rs[] = $row;
			}
			
			return $rs;
		}
		
        public function setAttributes(array $attr)
        {
            foreach ($attr as $k => $v)
            {
                $this->info[$k] = $v;
            }
        }
	}
