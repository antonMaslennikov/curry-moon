<?php
    namespace application\models;
    
    use \smashEngine\core\App AS App; 
    use \Exception;

    class gplus extends \smashEngine\core\Model 
    {
        private $client_id;
        private $secret_key;
        private $code;
        public $user;
        public $profile;
        
        public $script_uri = 'http://www.maryjane.ru/login/gplus/';
        public $api_url    = '';
        public $error;
        
        public $accessType = 'offline';
        public $approvalPrompt = 'force';
        
        const OAUTH2_REVOKE_URI = 'https://accounts.google.com/o/oauth2/revoke';
        const OAUTH2_TOKEN_URI = 'https://accounts.google.com/o/oauth2/token';
        const OAUTH2_AUTH_URL = 'https://accounts.google.com/o/oauth2/auth';
        const PROFILE_URI = 'https://www.googleapis.com/plus/v1/people/me';
        
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
            $this->script_uri = $this->script_uri;
        }
        
        function setScriptUri($url)
        {
            $this->script_uri = $url;
        }
        
        function loginDialog($scope = 'profile email https://www.googleapis.com/auth/plus.me')
        {
            $params = array(
                'response_type=code',
                'redirect_uri=' . urlencode($this->script_uri),
                'client_id=' . urlencode($this->client_id),
                'scope=' . urlencode($scope),
                'access_type=' . urlencode($this->accessType),
                'approval_prompt=' . urlencode($this->approvalPrompt),
            );

            if(strpos($scope, 'plus.login') && count($this->requestVisibleActions) > 0) {
                $params[] = 'request_visible_actions=' .
                    urlencode($this->requestVisibleActions);
            }

            if (isset($this->state)) {
              $params[] = 'state=' . urlencode($this->state);
            }
            
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
                  'code' => $this->code,
                  'grant_type' => 'authorization_code',
                  'redirect_uri' => $this->script_uri,
                  'client_id' => $this->client_id,
                  'client_secret' => $this->secret_key
            );
            
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
            
            if ($respHttpCode != 200)
            {
                $this->error->error_description = 'Ошибка получения токена "' . $respHttpCode . '"';
                return false;
            }
            
            if ($respHeaderSize) {
              $responseBody = substr($response, $respHeaderSize);
              $responseHeaders = substr($response, 0, $respHeaderSize);
            } else {
              list($responseHeaders, $responseBody) = explode("\r\n\r\n", $response, 2);
            }
            
            $responseBody = json_decode($responseBody);
            
            $this->token = $responseBody->access_token;
            
            return true; 
        }
        
        public function getProfile()
        {
            $ch = curl_init();
            
            curl_setopt_array($ch, self::$curl_opts);
            
            curl_setopt($ch, CURLOPT_URL, self::PROFILE_URI . '/?access_token=' . $this->token);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Length: ' . strlen($postBody),
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Content-Transfer-Encoding' => 'binary',
                'MIME-Version' => '1.0',
            )); 
            
            $response = curl_exec($ch);
            
            $respHttpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $respHeaderSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            
            if ($respHttpCode != 200)
            {
                $this->error->error_description = 'Ошибка получения данных профиля "' . $respHttpCode . '"';
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
    }
    
?>