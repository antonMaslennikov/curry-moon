<?php

    namespace application\models;
    
    use \smashEngine\core\App AS App; 
    use \PDO;
    use \Exception; 

	class shortUrl extends \smashEngine\core\Model 
	{
	    protected static $chars = "123456789bcdfghjkmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ";
	    protected static $table = "short_urls";
	    protected static $checkUrlExists = true;
	
	    protected $timestamp;
	
	    public function __construct() {
	        $this->timestamp = $_SERVER["REQUEST_TIME"];
	    }
	
	    public function urlToShortCode($url) {
	        if (empty($url)) {
	            throw new Exception("Не получен адрес URL.");
	        }
	
	        if ($this->validateUrlFormat($url) == false) {
	            throw new Exception(
	                "Адрес URL имеет неправильный формат.");
	        }
	
			/*
	        if (self::$checkUrlExists) {
	            if (!$this->verifyUrlExists($url)) {
	                throw new Exception(
	                    "Адрес URL не существует.");
	            }
	        }
			 */
	
	        $shortCode = $this->urlExistsInDb($url);
			
	        if ($shortCode == false) {
	            $shortCode = $this->createShortCode($url);
	        }
	
	        return $shortCode;
	    }
	
	    protected function validateUrlFormat($url) {
	        return filter_var($url, FILTER_VALIDATE_URL,
	            FILTER_FLAG_HOST_REQUIRED);
	    }
	
	    protected function verifyUrlExists($url) {
	        $ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL, $url);
	        curl_setopt($ch, CURLOPT_NOBODY, true);
	        curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true);
	        curl_exec($ch);
	        $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	        curl_close($ch);
	
	        return (!empty($response) && $response != 404);
	    }
	
	    protected function urlExistsInDb($url) {
	        $query = "SELECT short_code FROM " . self::$table . " WHERE long_url = :long_url LIMIT 1";
	        $stmt = App::db()->prepare($query);
	        $params = array(
	            "long_url" => $url
	        );
	        $stmt->execute($params);
	
	        $result = $stmt->fetch();
	        return (empty($result)) ? false : $result["short_code"];
	    }
	
	    protected function createShortCode($url) {
	        $id = $this->insertUrlInDb($url);
	        $shortCode = $this->convertIntToShortCode($id);
	        $this->insertShortCodeInDb($id, $shortCode);
	        return $shortCode;
	    }
	
	    protected function insertUrlInDb($url) {
	        $query = "INSERT INTO " . self::$table .
	            " (long_url, date_created) " .
	            " VALUES (:long_url, :timestamp)";
	        $stmnt = App::db()->prepare($query);
	        $params = array(
	            "long_url" => $url,
	            "timestamp" => $this->timestamp
	        );
			
	        $stmnt->execute($params);
	
	        return App::db()->lastInsertId();
	    }
	
	    protected function convertIntToShortCode($id) {
	        $id = intval($id);
	        if ($id < 1) {
	            throw new Exception(
	                "ID не является некорректным целым числом.");
	        }
	
	        $length = strlen(self::$chars);
	        // Проверяем, что длина строки
	        // больше минимума - она должна быть
	        // больше 10 символов
	        if ($length < 10) {
	            throw new Exception("Длина строки мала");
	        }
	
	        $code = "";
	        while ($id > $length - 1) {
	            // Определяем значение следующего символа
	            // в коде и подготавливаем его
	            $code = self::$chars[fmod($id, $length)] . $code;
	            // Сбрасываем $id до оставшегося значения для конвертации
	            $id = floor($id / $length);
	        }
	
	        // Оставшееся значение $id меньше, чем
	        // длина self::$chars
	        $code = substr(md5(uniqid()), 0, 3) . self::$chars[$id] . $code;
	
	        return $code;
	    }
        
        public static function convertInt2Code($id) {
            $id = intval($id);
            if ($id < 1) {
                throw new Exception(
                    "ID не является некорректным целым числом.");
            }
    
            $length = strlen(self::$chars);
            // Проверяем, что длина строки
            // больше минимума - она должна быть
            // больше 10 символов
            if ($length < 10) {
                throw new Exception("Длина строки мала");
            }
    
            $code = "";
            while ($id > $length - 1) {
                // Определяем значение следующего символа
                // в коде и подготавливаем его
                $code = self::$chars[fmod($id, $length)] . $code;
                // Сбрасываем $id до оставшегося значения для конвертации
                $id = floor($id / $length);
            }
    
            // Оставшееся значение $id меньше, чем
            // длина self::$chars
            $code = substr(md5(uniqid()), 0, 3) . self::$chars[$id] . $code;
    
            return $code;
        }
	
	    protected function insertShortCodeInDb($id, $code) {
	        if ($id == null || $code == null) {
	            throw new Exception("Параметры ввода неправильные.");
	        }
	        $query = "UPDATE " . self::$table .
	            " SET short_code = :short_code WHERE id = :id";
	        $stmnt = App::db()->prepare($query);
	        $params = array(
	            "short_code" => $code,
	            "id" => $id
	        );
	        $stmnt->execute($params);
	
	        if ($stmnt->rowCount() < 1) {
	            throw new Exception(
	                "Строка не обновляется коротким кодом.");
	        }
	
	        return true;
	    }
	
	    public function shortCodeToUrl($code, $increment = true) {
	        if (empty($code)) {
	            throw new Exception("Не задан короткий код.");
	        }
	
	        if ($this->validateShortCode($code) == false) {
	            throw new Exception(
	                "Короткий код имеет неправильный формат.");
	        }
	
	        $urlRow = $this->getUrlFromDb($code);
	        if (empty($urlRow)) {
	            throw new Exception(
	                "Короткий код не содержится в базе.");
	        }
	
	        if ($increment == true) {
	            $this->incrementCounter($urlRow["id"]);
	        }
	
	        return $urlRow["long_url"];
	    }
	
	    protected function validateShortCode($code) {
	        return preg_match("|[" . self::$chars . "]+|", $code);
	    }
	
	    protected function getUrlFromDb($code) {
	        $query = "SELECT id, long_url FROM " . self::$table .
	            " WHERE short_code = :short_code LIMIT 1";
	        $stmt = App::db()->prepare($query);
	        $params=array(
	            "short_code" => $code
	        );
	        $stmt->execute($params);
	
	        $result = $stmt->fetch();
	        return (empty($result)) ? false : $result;
	    }
	
	    protected function incrementCounter($id) {
	        $query = "UPDATE " . self::$table .
	            " SET counter = counter + 1 WHERE id = :id";
	        $stmt = App::db()->prepare($query);
	        $params = array(
	            "id" => $id
	        );
	        $stmt->execute($params);
	    }
	}
    
?>