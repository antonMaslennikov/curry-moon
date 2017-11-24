<?
    namespace smashEngine\core;
    
    class View
    {
        public $vars = [];
        public $t;
        
        function __construct()
        {
            $this->t = new \Smarty;
            
            $this->t->template_dir   = 'application/views/';
            $this->t->compile_dir    = 'application/views/templates_c/';
            $this->t->config_dir     = 'application/views/templates_configs/';
            $this->t->cache_dir      = 'application/views/templates_cache/';
            $this->t->debugging      = false;
            $this->t->caching        = false;
            $this->t->cache_lifetime = 120;
        }
        
        /**
         * @param string $content_view шаблон с контентом
         * @param string $template_view шаблон-индекс
         * @param array $data массив с данными
         */
        function generate($template_view)
        {
            // Чит код
            // Если перешли по utm-метке cpc, то игнорируются все тайтлы выставленные странице до этого и подставляются из урла
            if ($_GET['utm_medium'] == 'cpc') {
                $this->vars['PAGE']->noindex = TRUE;
                if ($_GET['utm_term']) {
                    $this->vars['PAGE']->keywords = $this->vars['PAGE']->title = $_GET['utm_term'];
                }
            }
            
            // отправляем все переменные в шаблон
            foreach ($this->vars as $k => $v) {
                $this->t->assign($k, $v);
            }
            
            $this->t->display($template_view);
        }
        
        function setVar($name, $value)
        {
            $this->vars[$name] = $value;
        }
    }
?>