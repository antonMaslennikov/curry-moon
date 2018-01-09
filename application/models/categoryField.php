<?php

    namespace application\models;

    use \smashEngine\core\App AS App;

    class categoryField extends \smashEngine\core\Model {

        /**
         * @var имя таблицы в БД для хранения экземпляров класса
         */
        protected static $dbtable = 'categorys__options';
        
        public function __construct($id = 0)
        {
            parent::__construct($id);
            
            $this->value = json_decode($this->value, 1);
        }
        
        public function save() {
            
            $this->slug = $this->slug ? $this->slug : toTranslit($this->name);
            
            foreach ($this->value AS $v) {
                if (!empty($v['slug']) && !empty($v['value'])) {
                    $values[] = $v;
                }
            }
            
            $this->value = json_encode_cyr($values);
            
            parent::save();
        }
    }