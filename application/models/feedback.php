<?
    namespace application\models;
    
    use \smashEngine\core\App AS App; 
    use \PDO;
    
    /**
     * 
     */
    class feedback extends \smashEngine\core\Model  
    {   
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
            6 => 'Комментарий',
            7 => 'Жалоба',
            8 => 'Ошибка',
            9 => 'Регистрация',
            12 => 'Опт',
            13 => 'Перезвонить',
            15 => 'Партнёрская программа',
        );
                
        /**
         * Сохранить текущий экземпляр объекта в базу
         */
        public function save()
        {
            parent::save();
            /*    
            App::mail()->send(
                $users,
                2, 
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
            */
        }
    }