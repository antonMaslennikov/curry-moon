<?php
    
    namespace smashEngine\core;

    abstract class Routings {
        
        /**
         * @var базовое пространство имён (чтобы не переписывать каждый раз в правилах)
         */
        var $classesBase = 'application';
        
        /**
         * @var массив с правилами разбора урла
         */
        var $data;
        
        public function __construct($base = null) {
            if ($base) {
                $this->classesBase = $base;
            }
        }
        
        /**
         * получить готовый массив с правилами
         */
        public function get() {
            
            foreach ($this->data AS $k => $row) {
                if ($row['action']) {
                    $this->data[$k]['action'] = $this->classesBase . $row['action'];
                }
            }
            
            return $this->data;
        }
    }