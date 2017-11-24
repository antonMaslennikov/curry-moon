<?php
    namespace application\models;
    
    use \smashEngine\core\App AS App; 
    use \Exception;

    class instagram extends \smashEngine\core\Model 
    {
        private $client_id;
        private $secret_key;
        private $code;
        public $user;
        public $profile;
        
        public $script_uri = 'http://www.maryjane.ru/login/instagram/';
        public $error;
        
        public $accessType = 'offline';
        public $approvalPrompt = 'force';
        
        public $media = array();
        
        /**
         * Format for endpoint URL requests
         * @var string
         */
        protected $_endpointUrls = array(
            'authorize' => 'https://api.instagram.com/oauth/authorize/?client_id=%s&redirect_uri=%s&response_type=%s',
            'access_token' => 'https://api.instagram.com/oauth/access_token',
            'user' => 'https://api.instagram.com/v1/users/%d/?access_token=%s',
            'user_feed' => 'https://api.instagram.com/v1/users/self/feed?access_token=%s&max_id=%d&min_id=%d',
            'user_recent' => 'https://api.instagram.com/v1/users/%d/media/recent/?access_token=%s&max_id=%d&min_id=%d&max_timestamp=%d&min_timestamp=%d',
            'user_search' => 'https://api.instagram.com/v1/users/search?q=%s&access_token=%s',
            'user_follows' => 'https://api.instagram.com/v1/users/%d/follows?access_token=%s',
            'user_followed_by' => 'https://api.instagram.com/v1/users/%d/followed-by?access_token=%s',
            'user_requested_by' => 'https://api.instagram.com/v1/users/self/requested-by?access_token=%s',
            'user_relationship' => 'https://api.instagram.com/v1/users/%d/relationship?access_token=%s',
            'modify_user_relationship' => 'https://api.instagram.com/v1/users/%d/relationship?action=%s&access_token=%s',
            'media' => 'https://api.instagram.com/v1/media/%d?access_token=%s',
            'media_search' => 'https://api.instagram.com/v1/media/search?lat=%s&lng=%s&max_timestamp=%d&min_timestamp=%d&distance=%d&access_token=%s',
            'media_popular' => 'https://api.instagram.com/v1/media/popular?access_token=%s',
            'media_comments' => 'https://api.instagram.com/v1/media/%d/comments?access_token=%s',
            'post_media_comment' => 'https://api.instagram.com/v1/media/%d/comments?access_token=%s',
            'delete_media_comment' => 'https://api.instagram.com/v1/media/%d/comments?comment_id=%d&access_token=%s',
            'likes' => 'https://api.instagram.com/v1/media/%d/likes?access_token=%s',
            'post_like' => 'https://api.instagram.com/v1/media/%d/likes',
            'remove_like' => 'https://api.instagram.com/v1/media/%d/likes?access_token=%s',
            'tags' => 'https://api.instagram.com/v1/tags/%s?access_token=%s',
            'tags_recent' => 'https://api.instagram.com/v1/tags/%s/media/recent?max_id=%d&min_id=%d&access_token=%s',
            'tags_search' => 'https://api.instagram.com/v1/tags/search?q=%s&access_token=%s',
            'locations' => 'https://api.instagram.com/v1/locations/%d?access_token=%s',
            'locations_recent' => 'https://api.instagram.com/v1/locations/%d/media/recent/?max_id=%d&min_id=%d&max_timestamp=%d&min_timestamp=%d&access_token=%s',
            'locations_search' => 'https://api.instagram.com/v1/locations/search?lat=%s&lng=%s&foursquare_id=%d&distance=%d&access_token=%s',
        );
        
        const OAUTH2_AUTH_URL = 'https://api.instagram.com/oauth/authorize/';
        const OAUTH2_TOKEN_URI = 'https://api.instagram.com/oauth/access_token';
        const USER_API_URI = 'https://api.instagram.com/v1/';
        // https://api.instagram.com/v1/users/1574083/?access_token=464770123.f59def8.2cc4d3e349e148a4b9352f98783dfb6a
        const MEDIA_API_URI = 'https://api.instagram.com/v1/media';
        // https://api.instagram.com/v1/media/search?lat=48.858844&lng=2.294351&access_token=464770123.f59def8.2cc4d3e349e148a4b9352f98783dfb6a
        
        static public $curl_opts = array(
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
            $this->script_uri = $this->script_uri;
        }
        
        function setScriptUri($url)
        {
            $this->script_uri = $url;
        }
        
        function loginDialog($scope = 'basic')
        {
            $params = array(
                'client_id=' . urlencode($this->client_id),
                'redirect_uri=' . urlencode($this->script_uri),
                'response_type=code',
                'scope=' . urlencode($scope),
            );
            
            $params = implode('&', $params);
            
            return "<script> top.location.href='" . self::OAUTH2_AUTH_URL . "?$params" . "'</script>";
        }
        
        
        function setCode($code)
        {
            $this->code = $code;
        }
        
        public function getToken()
        {
            $params = array(
                'client_id' => $this->client_id,
                'client_secret' => $this->secret_key,
                'code' => $this->code,
                'grant_type' => 'authorization_code',
                'redirect_uri' => $this->script_uri,
            );
            
            //$params = implode('&', $params);
            
            $postBody = http_build_query($params, '', '&');
            
            $ch = curl_init();
            
            curl_setopt_array($ch, self::$curl_opts);
            
            curl_setopt($ch, CURLOPT_URL, self::OAUTH2_TOKEN_URI);
            
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Length: ' . strlen($postBody),
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Content-Transfer-Encoding' => 'binary',
                'MIME-Version' => '1.0',
            )); 
            
            
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postBody);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            
            $response = curl_exec($ch);
            
            $respHttpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $respHeaderSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            
            if ($respHeaderSize) {
              $responseBody = substr($response, $respHeaderSize);
              $responseHeaders = substr($response, 0, $respHeaderSize);
            } else {
              list($responseHeaders, $responseBody) = explode("\r\n\r\n", $response, 2);
            }
            
            $responseBody = json_decode($responseBody);
            
            if ($respHttpCode != 200)
            {
                $this->error->error_description = 'Ошибка получения токена "' . $responseBody->error_message . ' (' . $respHttpCode . ')' . '"';
                throw new Exception($this->error->error_description, 1);
                return false;
            }
            
            $this->token   = $responseBody->access_token;
            $this->profile_id = $responseBody->user->id;
            
            return $this->token; 
        }
        
        public function setToken($token)
        {
            $this->token = $token;
        }
        
        /**
         * Search for a user by name.
         * @param string $name. A query string
         */
        public function searchUser($name) 
        {
            $ch = curl_init();    
            
            $endpointUrl = sprintf($this->_endpointUrls['user_search'], urlencode($name), $this->token);
            
            curl_setopt_array($ch, self::$curl_opts);
            curl_setopt($ch, CURLOPT_URL, $endpointUrl);
            
            $response = curl_exec($ch);
            
            $respHttpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $respHeaderSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            
            if ($respHttpCode != 200)
            {
                $this->error->error_description = 'Ошибка получения данных профиля "' . $respHttpCode . '"';
                throw new Exception($this->error->error_description, 2);
                return false;
            }
            
            if ($respHeaderSize) {
              $responseBody = substr($response, $respHeaderSize);
              $responseHeaders = substr($response, 0, $respHeaderSize);
            } else {
              list($responseHeaders, $responseBody) = explode("\r\n\r\n", $response, 2);
            }
            
            $users = json_decode($responseBody);
            
            foreach ($users->data AS $u) {
                
                //printr($u);
                
                if ($u->username == $name) {
                    $id =$u->id;
                }
            }
            
            return $id;
        }        
        
        public function getProfile()
        {
            $ch = curl_init();
            
            curl_setopt_array($ch, self::$curl_opts);
            
            curl_setopt($ch, CURLOPT_URL, self::USER_API_URI . 'users/' . $this->profile_id . '/?access_token=' . $this->token);
            
            $response = curl_exec($ch);
            
            $respHttpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $respHeaderSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            
            if ($respHttpCode != 200)
            {
                $this->error->error_description = 'Ошибка получения данных профиля "' . $respHttpCode . '"';
                throw new Exception($this->error->error_description, 2);
                return false;
            }
            
            if ($respHeaderSize) {
              $responseBody = substr($response, $respHeaderSize);
              $responseHeaders = substr($response, 0, $respHeaderSize);
            } else {
              list($responseHeaders, $responseBody) = explode("\r\n\r\n", $response, 2);
            }
            
            $this->profile = json_decode($responseBody);
        }
        
        /**
         * Получить медиа-файлы с профиля
         * @return json
         */
        public function getMedia($from = 'self')
        {
            $ch = curl_init();
            
            curl_setopt_array($ch, self::$curl_opts);
            
            $api_url = sprintf('users/%s/media/recent/', $from);
            
            curl_setopt($ch, CURLOPT_URL, self::USER_API_URI . $api_url . '?access_token=' . $this->token);
            
            $response = curl_exec($ch);
            
            $respHttpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $respHeaderSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            
            if ($respHttpCode != 200)
            {
                $this->error->error_description = 'Ошибка получения данных "' . $respHttpCode . '"';
                throw new Exception($this->error->error_description, 3);
                return false;
            }
            
            if ($respHeaderSize) {
              $responseBody = substr($response, $respHeaderSize);
              $responseHeaders = substr($response, 0, $respHeaderSize);
            } else {
              list($responseHeaders, $responseBody) = explode("\r\n\r\n", $response, 2);
            }
            
            $media = json_decode($responseBody);
            
            foreach ($media->data as $i) 
            {
                $this->media['images'][] = $i->images;
            }
            
            return $this->media;
        }
    }
    
?>