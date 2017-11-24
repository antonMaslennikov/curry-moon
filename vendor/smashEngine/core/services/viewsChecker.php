<?php

    namespace smashEngine\core\services;

    use \smashEngine\core\exception\serviceScriptException;

    /**
     * Анализатор логов с фиксацией просмотров объектов
     */
    abstract class viewsChecker {
        
        /**
         * @var string путь до файла с логами посещений
         */
        protected $logfile = '';
        
        /**
         * @var string регулярное выражение для поиска страниц
         */
        protected $pattern = '';
        
        /**
         * @var array массив данными по посещениями
         */
        protected $data = [];
        
        /**
         * @var array буфер с данными по носителям
         */
        protected static $styles = [];
        
        
        protected function __construct($file) {
            if (!is_file($file)) {
                throw new serviceScriptException('Файл с логами не обнаружен', 1);
            }
            
            $this->logfile = $file;
        }
        
        /**
         * Проанализировать файл логов 
         * и сформировать универсальный массив для сохранения в базу данных
         */
        public function parse()
        {
            if (!$this->pattern) {
                throw new serviceScriptException('Регулярное выражение для поиска не задано', 1);
            }
            
            $now = time();
            
            $visits = log_apache_parser($this->logfile, 0, 10000, '', '', '', '', '/GET ' . $this->pattern . '/i');
            
            //printr($visits);
            
            foreach ($visits AS $v)
            {
                $v['url'] = parse_url(trim(str_replace(['GET', 'HTTP/1.0', 'HTTP/1.1'], '', $v['request'])));

                if (strtotime($v['time']) >= $now - 3600) 
                {
                    $v['url']['parts'] = explode('/', trim($v['url']['path'], '/'));
                    
                    if (!$goods[$v['url']['parts'][2]][$v['ip']][$v['url']['path']]) {
                        // [good_id][user_ip][visited_path] = time
                        $this->goods[$v['url']['parts'][2]][$v['ip']][$v['url']['path']] = $v['time'];
                    }
                }
            }
            
            return $this;
        }
        
        public abstract function save();
    }
    