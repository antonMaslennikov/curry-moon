<?
    namespace application\models;
    
    use \smashEngine\core\App AS App; 
    use \PDO;
    use \S3Thumb AS S3Thumb;
    
    /**
     * 
     */
    class feedback extends \smashEngine\core\Model  {
        
        var $id;
        var $info = array();
        
        /**
         * @param string имя таблицы в БД для хранения экземпляров класса
         */
        public static $dbtable = 'feedback';
        
        public static $subjects = array(
            0 => 'без темы',
            1 => 'Статус заказа',
            2 => 'Обмен заказа',
            3 => 'Вопрос',
            4 => 'Идея',
            5 => 'Отправка работы',
            6 => 'Комментарий',
            7 => 'Жалоба',
            8 => 'Ошибка',
            9 => 'Регистрация',
            10 => 'Гаджет-запрос',
            12 => 'Опт',
            13 => 'Перезвонить',
            14 => 'Моя работа',
            15 => 'Партнёрская программа',
            16 => 'Почему отклонили мою работу?',
        );
        
        function __construct() {
            
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
            $sth = App::db()->query("SHOW COLUMNS FROM `" . self::$dbtable . "`");

            foreach ($sth->fetchAll() AS $f) {
                $fields[$f['Field']] = $f['Field'];
            }
            
            $rows = array_intersect_key($rows, $fields);
            // end вырезаем все поля которых нет в схеме таблицы
            
            // редактирование
            if (!empty($this->id))
            {
                App::db()->query(sprintf("UPDATE `%s` SET %s WHERE `good_id` = '%d' LIMIT 1", self::$dbtable, implode(',', $rows), $this->id));
            }
            // создание
            else
            {
                App::db()->query(sprintf("INSERT INTO `%s` SET %s", self::$dbtable, implode(',', $rows)));
                $this->id = App::db()->lastInsertId();
                
                App::db()->query("INSERT INTO `messages`
                            SET
                                `text`       = '" . $this->feedback_text . "',
                                `attachment` = '" . intval($this->feedback_pict) . "',
                                `referer`    = '" . $_SERVER['HTTP_REFERER'] . "',
                                `webclient`  = '" . addslashes($_SERVER['HTTP_USER_AGENT']) . "'");
    
                $mid = App::db()->lastInsertId();
    
                App::db()->query("INSERT INTO `messages_adressats`
                             SET
                                `mess_id`         = '" . $mid . "',
                                `user_from_id`    = '" . $this->feedback_user . "',
                                `user_from_email` = '" . $this->feedback_email . "',
                                `user_to_id`      = '" . 11 . "'");
                
                $users = array(27278, 6199);
                
                if ($this->feedback_topic == 12) {
                    array_push($users, 108933);
                }
                
                /*
                if ($this->feedback_topic != 14) {
                    array_push($users, 63250);
                }
                */
                if ($this->feedback_user > 0) {
                    $row = App::db()->query("SELECT `user_basket_id` FROM `user_baskets` WHERE `user_id` = '" . $this->feedback_user . "' AND `user_basket_status` IN ('ordered', 'accepted', 'prepared') ORDER BY `user_basket_id` DESC LIMIT 1")->fetch();
                    $last_order = $row['user_basket_id'];
                }
                
                App::mail()->send(
                    $users,
                    220, 
                    array(
                        'fid'     => $this->id,
                        'mid'     => $mid,
                        'feedback'=> '&feedback=1',
                        'subject' => self::$subjects[$this->feedback_topic], 
                        'date'    => datefromdb2textdate(NOW),
                        'referer' => $_SERVER['HTTP_REFERER'],
                        'user'    => (!empty($this->userId)) ? userId2userLogin($this->userId, 1 , 0, 1) : $this->feedback_email, 
                        'text'    => stripslashes($this->feedback_text),
                        'order'   => ($last_order) ? $last_order : '#',
                        'attach'  => (!empty($this->feedback_pict) ? 'http://www.maryjane.ru' . pictureId2path($this->feedback_pict) : '')));
            }
        }
    }