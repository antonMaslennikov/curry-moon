<?
    namespace smashEngine\core;
    
    /**
     * Управление информацией о странице
     */
     
    class Page
    {
        /**
         * @var string url запроса с образанными GET параметрами 
         */
        public $url;
        
        public $info = array();
        
        /**
         * @var array разобранный по / this->url 
         */
        public $reqUrl;

        /**
         * @var string подключаемый модуль
         */     
        public $module;
        
        /**
         * @var string путь до файла с урлами страниц
         */
        public $pmfile = 'application/views/pagemeta.xml';
        
        /**
         * @var string page title for module (тайтл для всего модуля)
         */
        public $title = '';
        
        /**
         * @var string page title unique for this url (тайтл для разных вариантов модуля)
         */
        public $utitle = '';
        
        public $udescription = '';
        
        public $ogPAGE_TITLE;
        
        /**
         * @var string описание страницы для шаринга в соцсетях
         */
        public $ogPAGE_DESCRIPTION;
        
        /**
         * @var string ссылка на картинку для шаринга страницы в соцсетях
         */
        public $ogImage;
        
        public $ogUrl;
        
        public $seo = '';
        
        /**
         * Не индерксировать страницу
         */
        public $noindex = false;
        
        /**
         * @var string meta keywords
         */
        public $keywords = '';
        
        /**
         * @var string meta description
         */
        public $description = '';
        
        /**
         * @var string хлебная крошка
         */
        public $breadcrump = array();
        
        /**
         * @var string язык страницы
         */
        public $lang = 'ru';
        
        /**
         * @var string путь до папки с файлами переводов
         */
        protected $lang_folder = 'application/views/translation/';
        
        /**
         * @var array массив с перводами
         */
        public $translate = array();
        
        /**
         * @var singleton
         */
        private static $_app;
        
        /**
         * @var объект в который будем сваливать все подключаемые модули
         */
        private static $_imports = array();
        
        /**
         * @var импортируемые на страницу файлы javascript 
         */
        public $js  = array();
        
        /**
         * @var импортируемые на страницу файлы css 
         */
        public $css = array();
        
        /**
         * @var 
         */
        public $isAjax = false;
        
        
        function __construct($url = null)
        {
            // разбираем урл на составляющие
            if (!$url = @parse_url($_SERVER['REQUEST_URI']))
                $url['path'] = $_SERVER['REQUEST_URI'];
        
            $this->REQUEST_URI = $_SERVER['REQUEST_URI'];
            $this->url         = $url['path'];
            $this->url_encoded = toTranslit(urldecode($url['path']));
            $this->reqUrl      = explode('/', $url['path']);
            
            // удаляем пустые элементы
            foreach ($this->reqUrl as $k => $v) 
            {
                if ($v === '') {
                    if ($k == 0 || $k == count($this->reqUrl)) {
                        unset($this->reqUrl[$k]);
                    } else {
                        if (strlen($this->url) > 1) {
                            $this->go('/404/', 301);
                        }
                    }
                }
            }

            if (strpos($_SERVER['HTTP_HOST'], 'dev.') !== false) {
                $this->noindex = true;
            }

            if ($_COOKIE['language'] && in_array($_COOKIE['language'], array('ru', 'en')))
            {
                $this->setLanguage($_COOKIE['language']);
            }

            // если явно в урле указана языковая версия сайта
            if (reset($this->reqUrl) == 'en')
            {
                $this->setLanguage(array_shift($this->reqUrl));
            }
            
            // сбрасывам ключи массива
            $this->reqUrl = array_values($this->reqUrl);
            
            if (!empty($this->reqUrl[0])) {
                $this->module = $this->reqUrl[0];
            }
            
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
                $this->isAjax = true;
            }
            
            if (!$this->isAjax)
            {
                // пробуем найти head-инфу в пейджмете
                if (is_readable($this->pmfile))
                {
                    $xml = simplexml_load_string(file_get_contents($this->pmfile));
                    
                    $m = $this->module;
                    
                    // чит для модулей, начинающихся с цифры
                    if ($xml->{'_' . $this->module}->unique)
                        $m = '_' . $this->module;
                    
                    // вытаскиваем урлы для всего модуля
                    if (!empty($xml->$m->title))         $this->title       = (string) $xml->$m->title;
                    if (!empty($xml->$m->keywords))      $this->keywords    = (string) $xml->$m->keywords;
                    if (!empty($xml->$m->description))   $this->description = (string) $xml->$m->description;
                    if (!empty($xml->$m->seo))           $this->seo         = (string) $xml->$m->seo;
                    if (!empty($xml->$m->canonical))     $this->canonical   = (string) $xml->$m->canonical;
                    if (!empty($xml->$m->noindex))       $this->noindex     = (string) $xml->$m->noindex;
                    
                    // если для модуля заданы заголовки для подстараниц
                    if ($xml->$m->unique) 
                    {
                        foreach ($xml->$m->unique[0] as $k => $p) 
                        {
                            if ($p->attributes()->url == $this->url) 
                            {
                                // тайтл, ключевые слова и описание пишем в свои переменные
                                if (!empty($p->title))        $this->utitle       = (string) $p->title;
                                if (!empty($p->keywords))     $this->ukeywords    = (string) $p->keywords;
                                if (!empty($p->description))  $this->udescription = (string) $p->description;
                                
                                if (!empty($p->seo))          $this->seo         = (string) $p->seo;
                                if (!empty($p->canonical))    $this->canonical   = (string) $p->canonical;
                                if (!empty($p->noindex))      $this->noindex     = (string) $p->noindex;
                            }
                        }
                    }
                }
            }    
         
            $this->translate = $this->getLangVariables();
            
            if ($_SERVER['SERVER_PROTOCOL'] == 'HTTP/1.1') {
                $this->noindex = true;
            }
            
            $this->ogUrl = rtrim($this->url, '/');
        }

        function go($href, $v)
        {
            if ($v == 301)
                header("HTTP/1.1 301 Moved Permanently");
            
            header('location: ' . $href);
            exit();
        }
        
        function refresh()
        {
            header('location: ' . ($_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : '/'));
            exit();
        }
        
        /**
         * установка языка страницы
         */
        function setLanguageFolder($f)
        {
            if (is_dir($f))
                $this->lang_folder = $f; 
        }
        
        /**
         * установка языка страницы
         */
        function setLanguage($l)
        {
            $this->lang = $l;
            
            $pmfile = pathinfo($this->pmfile);
            
            if (is_readable($pmfile['dirname']. DS . $pmfile['filename'] . '.' . $this->lang . '.' . $pmfile['extension']))
            {
                $this->pmfile = $pmfile['dirname']. DS . $pmfile['filename'] . '.' . $this->lang . '.' . $pmfile['extension'];
            }
            
            setcookie('language', $l, time() + 2592000, '/', MJdomain);
        }
        
        /**
         * получить язык страницы
         */
        function getLanguage($l)
        {
            return $this->lang;
        }
        
        /**
         * получить переводы
         */
        function getLangVariables()
        {
            $folder = $this->lang_folder . $this->module . DIRECTORY_SEPARATOR;
            
            $lang = array();
            
            if (file_exists($this->lang_folder . 'header' . DS . $this->lang . '.ini'))
                $lang += (array) parse_ini_file($this->lang_folder . 'header' . DS . $this->lang . '.ini'); 
                
            if (file_exists($this->lang_folder . 'footer' . DS . $this->lang . '.ini'))
                $lang += (array) parse_ini_file($this->lang_folder . 'footer' . DS . $this->lang . '.ini');
            
            if (file_exists($folder . $this->lang . '.ini'))
                $lang += (array) parse_ini_file($folder . $this->lang . '.ini');
            
            return $lang;
        }
        
        /**
         * Добавить языковые переменные на одну страницу  от другой
         * аля импорт переводов с одной страницы на другую
         */
        function addLangPage($page)
        {
            $folder = $this->lang_folder . $page . DIRECTORY_SEPARATOR;
            
            if (file_exists($folder . $this->lang . '.ini')) {
                $this->translate += (array) parse_ini_file($folder . $this->lang . '.ini');
            }
        }
        
        /**
         * подключить на страницу внешний файл (javascript, css)
         */ 
        public function import($paths)
        {
            foreach ((array) $paths AS $path)
            {
                if (!file_exists(ROOTDIR . $path)) {
                }
                
                $file = pathinfo($path);
                
                if (in_array($file['extension'], array('js', 'css')))
                {   
                    if (!in_array($path, $this->$file['extension'])) 
                    {   
                        array_push($this->$file['extension'], $path);
                    }
                }
            }
        }
        
        /**
         * Отслеживаем переходы на нас по отмеенным ссылкам (utm-метки)
         */
        public function track()
        {
            global $User;
            
            if ($_GET['utm_source'] && $_GET['mid'] && $User->authorized)
            {
                $tpl = intval(str_replace('mailsender_', '', $_GET['utm_campaign']));
                
                if (!$_COOKIE['mjtrigger'])
                {
                    $r = App::db()->query("UPDATE `mail_messages` 
                                 SET 
                                    `mail_message_clicked` = NOW(),
                                    `click_endpoint` = '" . $this->module . "'      
                                 WHERE 
                                        `mail_message_id` = '" . intval($_GET['mid']) . "' 
                                    AND `user_id` = '" . intval($User->id) . "'
                                 LIMIT 1");

                    if ($r->rowCount() == 1)
                    {
                        setcookie('mjtrigger', $tpl, time() + 30 * 60, '/');
                    }
                }

                if ($_COOKIE['mjtrigger'])
                {
                    setcookie('mjtrigger', $tpl, time() + 30 * 60, '/');
                }
            }
        }
        
        public function setFlashMessage($m)
        {
            if (!empty($m))
            {
                $_SESSION['mj-flash-message'] = $m;
            }
        }
        
        public function getFlashMessage()
        {
            if (!empty($_SESSION['mj-flash-message']))
            {
                $m = $_SESSION['mj-flash-message'];
                unset($_SESSION['mj-flash-message']);
            }
            
            return $m;
        }
        
        public function page404()
        {
            require_once ROOTDIR . '/404.html';
        }
    }