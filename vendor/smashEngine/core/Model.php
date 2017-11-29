<?
    namespace smashEngine\core;

    use \Exception;
    use \smashEngine\core\exception\appException;

    class Model
    {
        public $id = 0;
        
        public $info = array();
        
        protected static $dbtable;
        
        
        function __construct($id)
        { 
            $this->getDbTableName();
            
            $this->id = (int) $id;
            
            if (!empty($this->id))
            {
                $r = App::db()->prepare("SELECT * FROM `" . self::$dbtable . "` WHERE `id` = ? LIMIT 1");
                
                $r->execute([$this->id]);
                
                if ($r->rowCount() == 1) 
                {
                    $this->info = $r->fetch();
                    
                    return $this->info;
                } 
                else 
                    throw new appException(__CLASS__ . ' ' . $this->id . ' не найден');
            }
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
            foreach ($attr as $k => $v)
            {
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