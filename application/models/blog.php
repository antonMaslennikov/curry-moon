<?php

    namespace application\models;
    
    use \smashEngine\core\App AS App; 
    use \PDO;
    use \Exception;

	class blog extends \smashEngine\core\Model 
	{
		/**
		 * @var имя таблицы в БД для хранения экземпляров класса
		 */ 
		public static $dbtable = 'user_posts';
		
		/**
		 * @var имя таблицы в БД для хранения логов изменения экземпляров класса
		 */ 
		public static $dbtable_logs = 'user_posts__log';
		
		/**
		 * @var array тематика поста
		 */
		public static $themes = array(
			1 => array(
					'name' => 'Дизайн', 
					'slug' => 'dizayn'),
			2 => array(
					'name' => 'Отклоненные работы', 
					'slug' => 'otklonennuerabotu'),
			3 => array(
					'name' => 'Реклама', 
					'slug' => 'reklama'),
			4 => array(
					'name' => 'Пожелания и предложения', 
					'slug' => 'pogelaniyaipredlogeniya'),
			5 => array(
					'name' => 'Новости', 
					'slug' => 'novosti'),
			6 => array(
					'name' => 'Изготовление футболок', 
					'slug' => 'izuotovlenie-futbolok'),
			7 => array(
					'name' => 'Победители недели', 
					'slug' => 'winners'),
			8 => array(
					'name' => 'Конкурсы', 
					'slug' => 'competitions'),
			9 => array(
					'name' => 'Интервью', 
					'slug' => 'interview'),
			10 => array(
					'name' => 'Портфолио', 
					'slug' => 'portfolio'),
			11 => array(
					'name' => 'Рассылки', 
					'slug' => 'mail'),
			12 => array(
					'name' => 'Обучение', 
					'slug' => 'teaching'),	
			13 => array(
					'name' => 'Саморазвитие: креативность', 
					'slug' => 'samorazvitie'),		
		);
		
		function __construct($id = null)
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

		/**
		 * транслитерация заголовка поста в 
		 * iso
		 */
		public static function rus2lat($str)
		{
			 
			$replace = array( 
			   "Є"=>"YE","І"=>"I","Ѓ"=>"G","і"=>"i","№"=>"#","є"=>"ye","ѓ"=>"g",
			   "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D",
			   "Е"=>"E","Ё"=>"YO","Ж"=>"ZH",
			   "З"=>"Z","И"=>"I","Й"=>"J","К"=>"K","Л"=>"L",
			   "М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R",
			   "С"=>"S","Т"=>"T","У"=>"U","Ф"=>"F","Х"=>"X",
			   "Ц"=>"C","Ч"=>"CH","Ш"=>"SH","Щ"=>"SHH","Ъ"=>"'",
			   "Ы"=>"Y","Ь"=>"","Э"=>"E","Ю"=>"YU","Я"=>"YA",
			   "а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d",
			   "е"=>"e","ё"=>"yo","ж"=>"zh",
			   "з"=>"z","и"=>"i","й"=>"j","к"=>"k","л"=>"l",
			   "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
			   "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"x",
			   "ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"shh","ъ"=>"",
			   "ы"=>"y","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",' - '=>'-', ' — '=>'-',' '=>'-');
		   
   			return strtolower(trim(preg_replace('/[^A-Za-z0-9_\-]/', '', strtr($str, $replace)), '-'));
		}
		
		/**
		 * Поиск id темы по её slug
		 */
		public static function slug2themeId($slug) {
			foreach (self::$themes as $id => $t) {
				if ($slug == $t['slug']) {
					return $id;
				}
			}
			return false;
		}
		
		/**
		 * Логируем в базе действие с заказом
		 * @var string $a - действие
		 * @var string $r - результат
		 * @var string $i - дополнительная информация
         * @param user $User пользователь
		 */
		function log($a, $r, $i, $User) 
		{
        	App::db()->query("INSERT INTO `" . self::$dbtable_logs . "` SET
        					`post_id` = '" . $this->id . "',
        					`action`    = '" . addslashes($a) . "',
        					`result`    = '" . addslashes($r) . "', 
        					`info`      = '" . addslashes($i) . "', 
        					`user_id`   = '" . $User->id ."'");
							
			$this->logs[$action][] = array(
				'result'  => $r,
				'info'    => $i,
				'user_id' => $User->id
			);
		}
	
		function getlogs($action = '')
		{
			$r = App::db()->query("SELECT * FROM `" . self::$dbtable_logs . "` WHERE `post_id` = '" . $this->id . "' " . ((!empty($action)) ? "AND `action` = '" . addslashes($action) . "'" : '') . " ORDER BY `id` DESC");
			
			$this->logs = array();
			
			foreach ($r->fetchAll() AS $l) 
			{
				$this->logs[$l['action']][] = $l;
			}
			
			return $this->logs;
		}
	}

?>