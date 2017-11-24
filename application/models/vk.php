<?php
    namespace application\models;
    
    use \smashEngine\core\App AS App; 
    use \Exception;

    class vk extends \smashEngine\core\Model 
    {
        private $client_id;
        private $secret_key;
        
        public $token;
        public $code;
        public $user;
        public $profile;
        
        public $script_uri = 'http://www.maryjane.ru/login/vk/';
        public $api_url    = 'http://api.vk.com/api.php';
        public $error;
        
        protected $access_secret;
        
        static $curl_opts = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => 0,
            CURLOPT_FAILONERROR => false,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HEADER => true,
            CURLOPT_VERBOSE => false,
        );
        
        
        function __construct($id, $code)
        {
            $this->client_id = $id;
            $this->secret_key = $code;
            $this->script_uri = urlencode($this->script_uri);
        }
        
        function setScriptUri($url)
        {
            $this->script_uri = $url;
        }
        
        function shareDialog()
        {
            $dialog_url = 'http://api.vk.com/oauth/authorize?client_id=' . $this->client_id . '&scope=offline,wall,photos&redirect_uri=' . $this->script_uri . '&response_type=code';
            return "<script> top.location.href='" . $dialog_url . "'</script>";
        }
        
        public function loginDialog()
        {
            $dialog_url = 'http://api.vk.com/oauth/authorize?client_id=' . $this->client_id . '&scope=offline,wall,photos&redirect_uri=' . $this->script_uri . '&response_type=code';
            return "<script> top.location.href='" . $dialog_url . "'</script>";
        }
        
        function setCode($code)
        {
            $this->code = trim($code);
        }
        
        public function setToken($token)
        {
            $this->token = $token;
        }
        
        public function getToken()
        {
            $r = json_decode(file_get_contents('https://api.vk.com/oauth/access_token?client_id=' . $this->client_id . "&client_secret=" . $this->secret_key . "&code=" . $this->code . "&redirect_uri=" . $this->script_uri));

            if (!empty($r->error)) 
            {
                $this->error = $r;
                return false;
            }
            else 
            {
                $this->token         = $r->access_token;
                $this->user          = $r->user_id;
                $this->access_secret = $r->secret;
                
                return true;  
            }
        }
        
        public function getProfile()
        {
            $fields = array('uid', 'first_name', 'last_name', 'sex', 'bdate', 'city', 'country', 'photo_100', 'contacts');
            
            $this->profile = json_decode(@file_get_contents("https://api.vk.com/method/getProfiles?uid=" . $this->user . "&access_token=" . $this->token . "&fields=" . implode(',', $fields)))->response;
            
            $r = json_decode(@file_get_contents("https://api.vk.com/method/getCities?uid=" . $this->user . "&access_token=" . $this->token . "&cids=" . $this->profile[0]->city))->response;
            $this->profile[0]->city_name = $r[0]->name;

            $r = json_decode(@file_get_contents("https://api.vk.com/method/getCountries?uid=" . $this->user . "&access_token=" . $this->token . "&cids=" . $this->profile[0]->country))->response;
            $this->profile[0]->country_name = $r[0]->name;
        }
        
        
        /**
         * Получить медиа-файлы с профиля
         * @return json
         */
        public function getMedia($from = 'self')
        {
            if (!$this->profile[0]->uid)
            {
                throw new Exception("Не указан номер пользователя в сети Вконтакте", 1);
                return false;
            }
            
            /**
             * получаем список альбомов
             */
            $r = json_decode(@file_get_contents("https://api.vk.com/method/photos.getAlbums?owner_id=" . $this->profile[0]->uid));
            
            if ($r->error)
            {
                throw new Exception("Ошибка получения списка альбомов. (" . $r->error->error_msg . ")", 1);
                return false;
            }
            
            foreach ($r->response as $a) 
            {
                if ($a->size > 0)
                {
                    $this->media['albums'][$a->aid] = array(
                        'id' => $a->aid,
                        'name' => $a->title,
                        'count' => $a->size,
                    );
                }
            }
            /**
             * end получаем список альбомов
             */
            
            
            /**
             * получаем список фотографий
             */
            $r = json_decode(@file_get_contents("https://api.vk.com/method/photos.getAll?owner_id=" . $this->profile[0]->uid . "&access_token=" . $this->token . '&photo_sizes=1'));
            
            if ($r->error)
            {
                throw new Exception("Ошибка получения списка фотографий. (" . $r->error->error_msg . ")", 1);
                return false;
            }
            
            foreach ($r->response as $k => $i) 
            {
                if ($k == 0)
                    continue;
                
                $img = new stdClass();
            
                $img->id = $i->pid;
                $img->album = $a->aid;
                
                $img->low_resolution->url = $i->sizes[8]->src;
                $img->low_resolution->width = $i->sizes[8]->width;
                $img->low_resolution->height = $i->sizes[8]->height;
                
                $img->thumbnail->url = $i->sizes[6]->src;
                $img->thumbnail->width = $i->sizes[6]->width;
                $img->thumbnail->height = $i->sizes[6]->height;
    
                $img->standard_resolution->src = $i->sizes[4]->src;
                $img->standard_resolution->width = $i->sizes[4]->width;
                $img->standard_resolution->height = $i->sizes[4]->height;
    
                $this->media['images'][] = $img;
            }
            /**
             * end получаем список фотографий
             */
            
            return $this->media;
        }

        
        /**
         * Вызов метода api
         *  
         * @param string $method
         * @param mixed $parameters
         * @return mixed
         */
        public function callMethod($method, $parameters)
        {
            if (!$this->token) return false;
            
            if (is_array($parameters)) $parameters = http_build_query($parameters);
            
            $queryString = "/method/$method?$parameters&access_token={$this->token}";
            $querySig    = md5($queryString . $this->access_secret);
            
            return json_decode(file_get_contents(
                "https://api.vk.com{$queryString}&sig=$querySig"
            ));
        }
     
        /**
         * Пост на стене юзера
         *  
         * @param string $message
         * @param bool $fromGroup
         * @param bool $signed
         * @return mixed
         */
        public function wallPostMsg($message, $fromGroup = true, $signed = false)
        {
            return $this->callMethod('wall.post', array(
                'owner_id'   => '184277163',
                'message'    => $message,
                'from_group' => $fromGroup ? 1 : 0,
                'signed'     => $signed ? 1 : 0,
            ));
        } 
    }

?>