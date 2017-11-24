<?php
    namespace application\models;
    
    use \smashEngine\core\App AS App; 
    use \Exception;

    class fb extends \smashEngine\core\Model 
    {
        private $client_id;
        private $secret_key;
        private $code;
        public  $token;
        public $user;
        public $profile;
        
        public $script_uri = 'http://www.maryjane.ru/login/fb/';
        public $api_url    = '';
        public $error;
        
        static $curl_opts = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => 0,
            CURLOPT_FAILONERROR => false,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HEADER => true,
            CURLOPT_VERBOSE => false,
        );
        
        const OAUTH2_TOKEN_URI = 'https://graph.facebook.com/oauth/access_token';
        const USER_API_URI = 'https://graph.facebook.com/me';
        const MEDIA_API_URI = 'https://graph.facebook.com/me/albums';
        
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
        
        function loginDialog()
        {
            $_SESSION['state'] = md5(uniqid(rand(), TRUE));
            $dialog_url = 'https://www.facebook.com/dialog/oauth?client_id=' . $this->client_id . '&redirect_uri=' . $this->script_uri . '&state=' . $_SESSION['state'] . '&display=popup&scope=email,user_photos';
            return "<script> top.location.href='" . $dialog_url . "'</script>";
        }
        
        
        function setCode($code)
        {
            $this->code = $code;
        }
        
        public function getToken()
        {
            $response = json_decode(file_get_contents(self::OAUTH2_TOKEN_URI . '?client_id=' . $this->client_id . "&redirect_uri=" . $this->script_uri . "&client_secret=" . $this->secret_key . "&code=" . $this->code));
            
            $this->token = $response->access_token;
            
            if (!$this->token) {
                return false;
            } else {
                return true;
            }
        }
        
        public function setToken($token)
        {
            $this->token = $token;
        }
        
        public function getProfile()
        {
            $fields = array('id', 'name', 'gender', 'birthday', 'email');
            $this->profile = json_decode(file_get_contents(self::USER_API_URI . "?access_token=" . $this->token . "&fields=" . implode(',', $fields)));
        }
        
        
        /**
         * Получить медиа-файлы с профиля
         * @return json
         */
        public function getMedia($from = 'self')
        {
            $ch = curl_init();
            
            curl_setopt_array($ch, self::$curl_opts);
            
            switch ($from) {
                case 'value':
                break;
                
                default:
                case 'self':
                    $api_url = '';
                    break;
            }
                
            curl_setopt($ch, CURLOPT_URL, self::MEDIA_API_URI . $api_url . '?access_token=' . $this->token);
            
            $response = curl_exec($ch);
            
            $respHttpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $respHeaderSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            
            if ($respHttpCode != 200)
            {
                $this->error->error_description = 'Ошибка получения данных "' . $respHttpCode . '"';
                return false;
            }
            
            if ($respHeaderSize) {
              $responseBody = substr($response, $respHeaderSize);
              $responseHeaders = substr($response, 0, $respHeaderSize);
            } else {
              list($responseHeaders, $responseBody) = explode("\r\n\r\n", $response, 2);
            }
            
            $albums = json_decode($responseBody);
            
            foreach ($albums->data as $a) 
            {
                if ($a->count > 0)
                {
                    $this->media['albums'][$a->id] = array(
                        'id' => $a->id,
                        'name' => $a->name,
                        'count' => $a->count,
                    );
                    
                    $fields = array('id','picture','source','width','height','images');
                    
                    $images = json_decode(file_get_contents("https://graph.facebook.com/" .  $a->id . "/photos?access_token=" . $this->token . '&fields=' . implode(',', $fields)));
                    
                    foreach($images->data AS $i)
                    {
                        $img = new stdClass();
                        
                        $img->id = $i->id;
                        $img->album = $a->id;
                        
                        $img->low_resolution->url = $i->images[3]->source;
                        $img->low_resolution->width = $i->images[3]->width;
                        $img->low_resolution->height = $i->images[3]->height;
                        
                        $img->thumbnail->url = $i->images[5]->source;
                        $img->thumbnail->width = $i->images[5]->width;
                        $img->thumbnail->height = $i->images[5]->height;

                        $img->standard_resolution->url = $i->images[0]->source;
                        $img->standard_resolution->width = $i->images[0]->width;
                        $img->standard_resolution->height = $i->images[0]->height;
        
                        $this->media['images'][] = $img;
                    }
                }
            }
            
            return $this->media;
        }
    }
    
?>