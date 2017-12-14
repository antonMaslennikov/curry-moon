<?php
    namespace application\models;
    
    use \smashEngine\core\App AS App; 
    use \smashEngine\core\exception\appException;
    use \PDO;
    use \Exception;    

    class mailTemplate extends \smashEngine\core\Model
    {
        public $id   = 0;
        public $info = array();
        
        public static $dbtable = 'mail__templates';
        
        public static $tpl_folder = 'application/views/mail/';
        
        /**
         * @var array категории шаблонов
         */
        public static $cats = array(
            'actions'      => 'Акции',
            'basket'       => 'Для заказов',
            'clear'        => 'Другое',
            'misc'         => 'Служебные',
            'news'         => 'Новости',
            'newsCatalog'  => 'Новости в каталоге',
            'report'       => 'Отчёты',
            'registration' => 'Регистрация',
        );
        
        
        function __construct($id)
        {
            $this->id = (int) $id;
            
            if (!empty($this->id))
            {
                $r = App::db()->prepare("SELECT * FROM `" . self::$dbtable . "` WHERE `id` = ? LIMIT 1");
                
                $r->execute([$this->id]);
                
                if ($r->rowCount() == 1) 
                {
                    $this->info = $r->fetch();
                    
                    $this->mail_template_name = stripslashes($this->mail_template_name);
                    $this->mail_template_subject = stripslashes($this->mail_template_subject);
                    $this->mail_template_text = stripslashes($this->mail_template_text);
                    
                    return $this->info;
                } 
                else 
                    throw new appException ('mail template ' . $this->id . ' not found');
            }
        }
        
        /**
         * Сохранить текущий экземпляр объекта в базу
         */
        public function save()
        {
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
                
                $this->mail_template_file = $this->mail_template_order . '/' . $this->id . '.php';
                createDir(self::$tpl_folder . $this->mail_template_order);
                $f = fopen(self::$tpl_folder . $this->mail_template_file, 'w+');
                chmod(self::$tpl_folder . $this->mail_template_file, 0777);
                
                $this->save();
            }
        }
        
        /**
         * Редактировать поля
         * @param array $data - массив с полями
         */
        function change($data)
        {
            if (count($data) > 0)
            {
                $out = array();
                
                foreach ($data as $f => $v) {
                    if (!empty($f)) {
                        $out[] = "`" . addslashes($f) . "` = '" . addslashes($v) . "'";
                        
                        $this->$f = $v;
                    }
                }
    
                App::db()->query("UPDATE `" . self::$dbtable . "` SET " . implode(', ', $out) . " WHERE `id` = '" . $this->id . "' LIMIT 1");
            }
        }
        
        /**
         * Получить код шаблона
         * @param array $args - шаблонные переменные для подстановок 
         * @return string - код шаблона
         */
        public function view($args)
        {
            ob_start();
            
            extract($args);

            require_once (ROOTDIR . '/' . ltrim(self::$tpl_folder, '/') . $this->mail_template_file);
            $text = ob_get_contents();
            
            ob_end_clean();
            
            return $text;
        }
        
        /**
         * Выгрузить шаблон в файл
         * @param string $path путь выгрузки
         */
        public function export($path)
        {
            if (empty($path))
                $path = 'templates/mail_templates/export/' . $this->id . '.html';
                
            $f = fopen($path, 'w+');
            fwrite($f, $this->mail_template_text);
            fclose($f);
        }
        
        /**
         * Импорт шаблон из файла
         * @param string $path путь до файла
         */
        public function import($path)
        {
            if (empty($path))
                $path = 'templates/mail_templates/import/' . $this->id . '.html';
            
            if (file_exists($path)) {
                $f = fopen($path, 'r');
                $text = fread($f, filesize($path));
                
                $this->mail_template_text = $text;
                $this->save();
                
                fclose($f);
            } else {
                throw new Exception('Файл "' . $path . '" не может быть прочитан', 1);
                
            }
        }
        
        /**
         * Сбросить всё статистику по отправкам и просмотрам шаблона
         */
        public function clear()
        {
            $this->mail_template_views = 0;
            $this->mail_template_send = 0;
            $this->droped_date = NOW;
            
            $this->save();
            
            App::db()->query("DELETE FROM `mail__messages` WHERE `mail_message_template_id` = '" . $this->id . "'");
        }
        
        public function getAll()
        {
            $sql = 'SELECT * FROM '.self::$dbtable.' ORDER BY `id` DESC';

            $stmt = App::db()->prepare($sql);
            $stmt->execute();

            $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($rs AS $k =>$r) {
                $rs[$k]['category'] = self::$cats[$r['mail_template_order']];
            }
            
            return $rs;
        }
        
        public function delete() {
            
            $f = $this->mail_template_file;
            
            parent::delete();
            
            unlink(self::$tpl_folder . $f);
            
            return true;
        }
    }
