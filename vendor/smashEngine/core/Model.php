<?
    namespace smashEngine\core;

    use \Exception;
    use \smashEngine\core\exception\appException;

    class Model
    {
        public $id = 0;
        
        public $info = array();
        
        protected static $dbtable;

	    protected $_initial_data = [];

	    protected $modified_data = [];


        function __construct($id = null)
        { 
            $this->getDbTableName();
            
            if ($id) {
                $this->id = (int) $id;
            }
            
            if (!empty($this->id))
            {
                $r = App::db()->prepare("SELECT * FROM `" . self::$dbtable . "` WHERE `id` = ? LIMIT 1");
                
                $r->execute([$this->id]);
                
                if ($r->rowCount() == 1) 
                {
                    $this->info = $r->fetch();

	                $this->_initial_data = $this->info;

                    return $this->info;
                } 
                else 
                    throw new appException(__CLASS__ . ' ' . $this->id . ' не найден');
            }
        }


	    public function update() {

		    if (count($this->modified_data)) {

			    $update_query = [];
			    $update_array = [':id'=>(int) $this->id];

			    foreach ($this->modified_data as $key => $function_cast) {

				    if ($this->info[$key] != $this->_initial_data[$key]) {

					    $update_query[] = sprintf("%s = :%s", $key, $key);
					    $update_array[':'.$key] = $function_cast?call_user_func($function_cast, $this->info[$key]):$this->info[$key];
				    }
				}

			    if (count($update_query)) {

				    $sql = 'update ' . self::$dbtable . ' set ' . implode(', ', $update_query) . ' where id = :id limit 1;';

				    // printr($sql);
				    // printr($update_array, 1);

				    $stmt = App::db()->prepare($sql);

				    return $stmt->execute($update_array);
			    }

			    return true;
		    }

		    return false;
	    }


	    public function delete() {

		    $sql = 'delete from '.self::$dbtable.' where id = :id limit 1;';

		    $stmt = App::db()->prepare($sql);

		    return $stmt->execute([':id'=>(int) $this->id]);
	    }

        
        /**
         * ищем у пронаследовавшей модели свойство dbtable, содержащее имя таблицы
         */
        static function getDbTableName() {
            foreach (get_class_vars(get_called_class()) AS $k => $v) {
                if ($k == 'dbtable') {
                    self::$dbtable = $v;
                }
            }
            
            return self::$dbtable;
        }
        
        public function __set($name, $value) 
        {
            $this->info[$name] = $value;
        }
    
        public function __get($name) 
        {
            if (array_key_exists($name, $this->info)) {
                return $this->info[$name];
            }
            
            $getter = 'get' . $name;
            
            if (!method_exists($this, $getter))
            {
            }
            else {
                return $this->$getter();
            }
        }
        
        public function __isset($name)
        {
            return isset($this->info[$name]);
        }


        public function setAttributes(array $attr)
        {
	        $reflection = new \ReflectionClass(get_called_class());

	        foreach ($attr as $k => $v)
            {
	            if ($reflection->hasProperty($k) && $k != 'info') {

		            $this->$k = $v;
	            }
                
                $this->info[$k] = $v;
			}
        }
    
        /**
         * Сохранить экземляр класса в базу
         */
        public function save()
        {
            if (!self::$dbtable) {
                throw new Exception('Не известна таблица для сохранения данных', 1);
            }
            
            foreach ($this->info as $k => $v) {
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
            }
        }
    }
?>