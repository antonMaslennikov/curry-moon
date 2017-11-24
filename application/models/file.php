<?
    namespace application\models;
    
    use \smashEngine\core\App AS App; 
    use \PDO;
    use \S3;
    
    class file extends \smashEngine\core\Model
	{
		static $s3;
        
        /**
         * Переместить файл в хранилище s3
         * @param string путь до исходниго файла
         * @param string путь до файла назначения
         * @param string имя бакета
         * @param int удалить после загрузки оригинальный файл
         */
        public static function move2S3($path, $newpath = '', $bucket = '', $unlink = 1)
        {
            if (strpos($path, 'tmp/') === false) {
                $path = ltrim($path, DIRECTORY_SEPARATOR);
            }
            
            if (empty($newpath))
                $newpath = $path;
            else
                $newpath = ltrim($newpath, DIRECTORY_SEPARATOR);
            
            if (empty($bucket))
                $bucket = 'ic' . rand(1, 4) . '.maryjane.ru';
            
            S3::$useExceptions = true;
            
            if (!self::$s3)
                self::$s3 = new S3(S3AccessKey, S3SecretKey);

            self::$s3->putObjectFile(
                $path, 
                $bucket, 
                $newpath, 
                S3::ACL_PUBLIC_READ, 
                array(),
                array(
                    "Cache-Control" => "max-age=315360000",
                    "Expires" => gmdate("D, d M Y H:i:s T", strtotime("+1 years")),
                )
            );
                
            if ($unlink > 0) {
                if (strpos($path, 'tmp/') === false) {
                    @unlink(ROOTDIR . '/' . $path);
                } else {
                    @unlink($path);
                }
            }
            
            return 'http://' . $bucket . '/' . $newpath;
        }
        
        public static function getRandomBucket()
        {
            return 'ic' . rand(1, 4) . '.maryjane.ru';
        }
        
        public static function deleteFromCloud($bucket, $path)
        {
            if (!self::$s3)
                self::$s3 = new S3(S3AccessKey, S3SecretKey);
            
            self::$s3->deleteObject($bucket, $path);
        }
        
        public static function getRemoteFile($src, $dst = null)
        {
            $ch = curl_init($src);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)');
            $content = curl_exec($ch);
    
            if (!$dst) { 
                $dst = tempnam(sys_get_temp_dir(), 'img_');
                file_put_contents($dst, $content);
            } else {
                file_put_contents(ROOTDIR . $dst, $content);
            }
            
            return $dst;
        }
    }