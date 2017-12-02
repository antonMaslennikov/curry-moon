<?php

    use \smashEngine\core\App AS App;
    use \application\models\user AS user;
    use \application\models\good AS good;
    use \application\models\basket AS basket;
    use \application\models\styleCategory AS styleCategory;
    use \application\models\mail AS mail;
    
    /**
     * функция автозагрузки классов
     */
    function loadPackages($className)
    {
        $pices = explode('\\', $className);
     
        if ($pices[0] == 'application')
            $classPath = implode(DIRECTORY_SEPARATOR, $pices) . '.php';
        else {
            array_unshift($pices, 'vendor');
            $classPath = implode(DIRECTORY_SEPARATOR, $pices) . '.php';
        }
        
        if (is_readable($classPath)) {
            require_once $classPath;
        }
    }
        
    function getmicrotime() 
    { 
        list($usec, $sec) = explode(" ", microtime()); 
        return ((float)$usec + (float)$sec); 
    } 
    
    function HexToRGB($hex) {
        $hex = str_replace("#", "", $hex);
        $color = array();
    
        if(strlen($hex) == 3) {
            $color['r'] = hexdec(substr($hex, 0, 1) . $r);
            $color['g'] = hexdec(substr($hex, 1, 1) . $g);
            $color['b'] = hexdec(substr($hex, 2, 1) . $b);
        }
        else if(strlen($hex) == 6) {
            $color['r'] = hexdec(substr($hex, 0, 2));
            $color['g'] = hexdec(substr($hex, 2, 2));
            $color['b'] = hexdec(substr($hex, 4, 2));
        }
    
        return $color;
    }
    
    /**
     * @var (string) строка
     * @var (bollean) выход
     */
    function printr($str, $exit = false)
    {
        if ($_SERVER['REMOTE_ADDR'] == '77.40.3.41' || appMode == 'dev') {
            
            echo '<pre>'.print_r($str, 1).'</pre>';
            
            if ($exit)
                exit();
        }
    }
    
    function leadingzero ($input)
    {
        if ($input < 10 && $input >-1)
        {
            return("0" . intval($input));
        } else {
            return($input);
        }
    }
        
    function month2textmonth ($input)
    {
        if ($input==1)
        {
            return("января");
        }
        if ($input==2)
        {
            return("февраля");
        }
        if ($input==3)
        {
            return("марта");
        }
        if ($input==4)
        {
            return("апреля");
        }
        if ($input==5)
        {
            return("мая");
        }
        if ($input==6)
        {
            return("июня");
        }
        if ($input==7)
        {
            return("июля");
        }
        if ($input==8)
        {
            return("августа");
        }
        if ($input==9)
        {
            return("сентября");
        }
        if ($input==10)
        {
            return("октября");
        }
        if ($input==11)
        {
            return("ноября");
        }
        if ($input==12)
        {
            return("декабря");
        }
    }
    
    function single_month2textmonth ($input)
    {
        if ($input==1)
        {
            return("Январь");
        }
        if ($input==2)
        {
            return("Февраль");
        }
        if ($input==3)
        {
            return("Март");
        }
        if ($input==4)
        {
            return("Апрель");
        }
        if ($input==5)
        {
            return("Май");
        }
        if ($input==6)
        {
            return("Июнь");
        }
        if ($input==7)
        {
            return("Июль");
        }
        if ($input==8)
        {
            return("Август");
        }
        if ($input==9)
        {
            return("Сентябрь");
        }
        if ($input==10)
        {
            return("Октябрь");
        }
        if ($input==11)
        {
            return("Ноябрь");
        }
        if ($input==12)
        {
            return("Декабрь");
        }
    }
    
    function datefromdb2textdate ($input, $cut = null, $cutD = null)
    {
        list ($ymd, $time)  = explode(" ", $input);
        list ($y, $m, $d) = explode("-",$ymd);
        $month = month2textmonth($m);
        
        if (!empty($cut))
        {
            $etime = array_slice(explode(":", $time), 0, 3 - intval($cut));
            $time = implode(':', $etime);
        }
        
        $date = array(intval($d), $month, $y);
        
        if (!empty($cutD))
        {
            $date = array_slice($date, 0, 3 - intval($cutD));
        }
        
        $buffer =  implode(' ', $date);
        
        if ($time != "00:00:00" && $time != "00:00" && $time != "00")
            $buffer .= ' ' . $time;
    
        return trim($buffer);
    }
    
    function weekday2textweekday ($input)
    {
        if ($input==1)
        {
            return("понедельник");
        }
        if ($input==2)
        {
            return("вторник");
        }
        if ($input==3)
        {
            return("среда");
        }
        if ($input==4)
        {
            return("четверг");
        }
        if ($input==5)
        {
            return("пятница");
        }
        if ($input==6)
        {
            return("суббота");
        }
        if ($input==7 || $input==0)
        {
            return("воскресенье");
        }
    }
    
    function toLowerCase ($input)
    {
        $input = strtolower($input);
        $input = str_replace("Й", "й", $input);
        $input = str_replace("Ц", "ц", $input);
        $input = str_replace("У", "у", $input);
        $input = str_replace("К", "к", $input);
        $input = str_replace("Е", "е", $input);
        $input = str_replace("Н", "н", $input);
        $input = str_replace("Г", "г", $input);
        $input = str_replace("Ш", "ш", $input);
        $input = str_replace("Щ", "щ", $input);
        $input = str_replace("З", "з", $input);
        $input = str_replace("Х", "х", $input);
        $input = str_replace("Ъ", "ъ", $input);
        $input = str_replace("Ф", "ф", $input);
        $input = str_replace("Ы", "ы", $input);
        $input = str_replace("В", "в", $input);
        $input = str_replace("А", "а", $input);
        $input = str_replace("П", "п", $input);
        $input = str_replace("Р", "р", $input);
        $input = str_replace("О", "о", $input);
        $input = str_replace("Л", "л", $input);
        $input = str_replace("Д", "д", $input);
        $input = str_replace("Ж", "ж", $input);
        $input = str_replace("Э", "э", $input);
        $input = str_replace("Я", "я", $input);
        $input = str_replace("Ч", "ч", $input);
        $input = str_replace("С", "с", $input);
        $input = str_replace("М", "м", $input);
        $input = str_replace("И", "и", $input);
        $input = str_replace("Т", "т", $input);
        $input = str_replace("Ь", "ь", $input);
        $input = str_replace("Б", "б", $input);
        $input = str_replace("Ю", "ю", $input);
        $input = str_replace("Ё", "е", $input);
        return($input);
    }
    
    function toTranslit($input)
    {
        $input = mb_strtolower($input, 'UTF-8');
        
        $input = str_replace(array("й","ц","у","к","е","н","г","ш","щ","з","х","ъ","ф","ы","в","а","п","р","о","л","д","ж","э","я","ч","с","м","и","т","ь","б","ю","ё"), array("y","c","u","k","e","n","g","sh","sh","z","x","_","f","u","v","a","p","r","o","l","d","g","e","ya","ch","s","m","i","t","_","b","yu","e"), $input);
        $input = str_replace(array("Й","Ц","У","К","Е","Н","Г","Ш","Ц","З","Х","Ъ","Ф","Ы","В","А","П","Р","О","Л","Д","Ж","Э","Я","Ч","С","М","И","Т","Ь","Б","Ю","Ё"), array("y","c","u","k","e","n","g","sh","sh","z","x","_","f","u","v","a","p","r","o","l","d","g","e","ya","ch","s","m","i","t","_","b","yu","e"), $input);
        $input = str_replace(array("'",'"',"\\",",","%","&",':',';','*','@','«','»', '=', '’'), '', $input);
        $input = str_replace(array(" ","/","!","?","#","№",'<','>','(',')', '♥'), "-", $input);
        
        return $input;
    }
    
    function textToTranslit($input)
    {
    
        $input = str_replace("й", "y", $input);
        $input = str_replace("ц", "c", $input);
        $input = str_replace("у", "u", $input);
        $input = str_replace("к", "k", $input);
        $input = str_replace("е", "e", $input);
        $input = str_replace("н", "n", $input);
        $input = str_replace("г", "g", $input);
        $input = str_replace("ш", "sh", $input);
        $input = str_replace("щ", "sh", $input);
        $input = str_replace("з", "z", $input);
        $input = str_replace("х", "x", $input);
        $input = str_replace("ъ", "_", $input);
        $input = str_replace("ф", "f", $input);
        $input = str_replace("ы", "u", $input);
        $input = str_replace("в", "v", $input);
        $input = str_replace("а", "a", $input);
        $input = str_replace("п", "p", $input);
        $input = str_replace("р", "r", $input);
        $input = str_replace("о", "o", $input);
        $input = str_replace("л", "l", $input);
        $input = str_replace("д", "d", $input);
        $input = str_replace("ж", "g", $input);
        $input = str_replace("э", "e", $input);
        $input = str_replace("я", "ya", $input);
        $input = str_replace("ч", "ch", $input);
        $input = str_replace("с", "s", $input);
        $input = str_replace("м", "m", $input);
        $input = str_replace("и", "i", $input);
        $input = str_replace("т", "t", $input);
        $input = str_replace("ь", "", $input);
        $input = str_replace("б", "b", $input);
        $input = str_replace("ю", "yu", $input);
        $input = str_replace("ё", "e", $input);
    
        $input = str_replace("Й", "Y", $input);
        $input = str_replace("Ц", "C", $input);
        $input = str_replace("У", "U", $input);
        $input = str_replace("К", "K", $input);
        $input = str_replace("Е", "E", $input);
        $input = str_replace("Н", "N", $input);
        $input = str_replace("Г", "G", $input);
        $input = str_replace("Ш", "SH", $input);
        $input = str_replace("Щ", "Щ", $input);
        $input = str_replace("З", "Z", $input);
        $input = str_replace("Х", "X", $input);
        $input = str_replace("Ъ", "", $input);
        $input = str_replace("Ф", "F", $input);
        $input = str_replace("Ы", "U", $input);
        $input = str_replace("В", "V", $input);
        $input = str_replace("А", "A", $input);
        $input = str_replace("П", "P", $input);
        $input = str_replace("Р", "R", $input);
        $input = str_replace("О", "O", $input);
        $input = str_replace("Л", "L", $input);
        $input = str_replace("Д", "D", $input);
        $input = str_replace("Ж", "J", $input);
        $input = str_replace("Э", "E", $input);
        $input = str_replace("Я", "YA", $input);
        $input = str_replace("Ч", "CH", $input);
        $input = str_replace("С", "S", $input);
        $input = str_replace("М", "M", $input);
        $input = str_replace("И", "I", $input);
        $input = str_replace("Т", "T", $input);
        $input = str_replace("Ь", "", $input);
        $input = str_replace("Б", "B", $input);
        $input = str_replace("Ю", "YU", $input);
        $input = str_replace("Ё", "E", $input);
    
    
        return($input);
    }
    
    function readfilecontent($path)
    {
        $handle = fopen($path , "r");
        $file_content = fread($handle, filesize($path));
        fclose($handle);
        return($file_content);
    }
        
    /**
     * Поимайть файл из $_FILES и положить в базу
     *
     * @return array(status, message)
     * @param object $file - имя файлового-поля
     * @param object $folder - папка назначения (путь без ROOTDIR со слешем на конце)
     * @param object $file_name[optional] - конечное имя файла (без расширения)
     * @param string $ext[optional] - допустимые расширения файлов
     * @param int $minx - минимально допустимая ширина (0 - не ограничена)
     * @param int $miny - минимально допустимая высота (0 - не ограничена)
     * @param int $maxx - максимально допустимая ширина (0 - не ограничена)
     * @param int $maxy - максимально допустимая высота (0 - не ограничена)
     */
    function catchFile($file, $folder, $file_name = '', $ext = "gif,png,jpeg,jpg", $minx = 0, $miny = 0, $maxx = 0, $maxy = 0, $storage = 0)
    {
        $result = array();
    
        //printr($_FILES[$file]);
        
        if (!empty($_FILES[$file]['tmp_name']) && $_FILES[$file]['tmp_name'] != 'none')
        {
            if (!empty ($_FILES[$file]['error']))
            {
                switch ($_FILES[$file]['error'])
                {
                    case '1'   : $error = 'Превышен допустимый размер файла';   break;
                    case '2'   : $error = 'Превышен допустимый размер файла';   break;
                    case '3'   : $error = 'Файл загружен лишь частично'; break;
                    case '4'   : $error = 'Не выбран файл для загрузки'; break;
                    case '6'   : $error = 'Отсутствует временная папка на сервере'; break;
                    case '7'   : $error = 'Ошибка записи на диск'; break;
                    case '8'   : $error = 'File upload stopped by extension'; break;
                    case '999' : default : $error = 'Неизвестная ошибка'; break;
                }
            }
    
            if (empty($error))
            {
                // Проверка на расширение файла
                $allowed_ext = explode(',', $ext);
                $foo = explode('.', $_FILES[$file]['name']);
                $extension   = strtolower(end($foo));
    
                if (in_array($extension, $allowed_ext))
                {
                    // Если не указано конкретное имя, генерируем из исходного
                    if (empty($file_name))
                        $file_name = uniqid() . rand(0, 999) . '_' . toTranslit($_FILES[$file]['name']);
                    else
                        $file_name .= '.' . $extension;
    
                    $uploadFullPath = $folder . $file_name;
    
                    // Распарсиваем путь загрузки и создаём соответствующие папки если их нет
                    if (!is_dir(ROOTDIR . $folder))
                    {
                        createDir($folder);
                    }
    
                    if (move_uploaded_file($_FILES[$file]['tmp_name'], ROOTDIR . $uploadFullPath))
                    {
                        chmod(ROOTDIR . $uploadFullPath, 0777);
                        
                        $file_size = getimagesize(ROOTDIR . $uploadFullPath);
                        
                        // Проверка на совпадение с ограничениями по размерам
                        // если эти ограничения есть
                        if (!empty($minx) || !empty($miny) || !empty($maxx) || !empty($maxy))
                        {
                            // не совпадают оба размера (ограничения снизу)
                            if (!empty($minx) && !empty($miny) && $file_size[0] < $minx && $file_size[1] < $miny)
                            {
                                $result['status']  = 'error';
                                $result['message'] = "Мин. размер $minx x $miny px";
                                unlink(ROOTDIR . $uploadFullPath);
                            }
                            else
                            {
                                if (!empty($minx) && $file_size[0] < $minx)
                                {
                                    $result['status']  = 'error';
                                    $result['message'] = "Мин. ширина картинки $minx px";
                                    unlink(ROOTDIR . $uploadFullPath);
                                }
    
                                if (!empty($miny) && $file_size[1] < $miny)
                                {
                                    $result['status']  = 'error';
                                    $result['message'] = "Мин. высота картинки $miny px";
                                    unlink(ROOTDIR . $uploadFullPath);
                                }
                            }
    
                            // не совпадают оба размера (ограничения сверху)
                            if (!empty($maxx) && !empty($maxy) && $file_size[0] > $maxx && $file_size[1] > $maxy)
                            {
                                $result['status']  = 'error';
                                $result['message'] = "Макс. размер картинки $maxx x $maxy px";
                                unlink(ROOTDIR . $uploadFullPath);
                            }
                            else
                            {
                                if (!empty($maxx) && $file_size[0] > $maxx)
                                {
                                    $result['status']  = 'error';
                                    $result['message'] = "Макс. ширина картинки $maxx px";
                                    unlink(ROOTDIR . $uploadFullPath);
                                }
    
                                if (!empty($maxy) && $file_size[1] > $maxy)
                                {
                                    $result['status']  = 'error';
                                    $result['message'] = "Макс. высота картинки $maxy px";
                                    unlink(ROOTDIR . $uploadFullPath);
                                }
                            }
    
                            // не совпадают оба размера (строгие ограничения по обеим размерам)
                            if (!empty($minx) && !empty($miny) && !empty($maxx) && !empty($maxy) && $minx == $maxx && $miny == $maxy && $file_size[0] != $minx && $file_size[1] != $miny)
                            {
                                $result['status']  = 'error';
                                $result['message'] = "Размер строго $minx x $miny px";
                                unlink(ROOTDIR . $uploadFullPath);
                            }
                        }
    
                        if ($result['status'] != 'error')
                        {
                            // если указано перенести файл в облачное хранилище
                            if ($storage == 1)
                            {
                                
                                $uploadFullPath = file::move2S3($uploadFullPath);
                            }
                            
                            $result['status'] = 'ok';
                            $result['id']     = file2db($uploadFullPath);
                            $result['path']   = $uploadFullPath;
                            $result['file']   = basename($uploadFullPath);
                            $result['sizes']  = $file_size;
                            $result['extension'] = $extension;
                            $result['size']   =  $_FILES[$file]['size'];
                        }
                    }
                    else
                    {
                        $result['status']  = 'error';
                        $result['message'] = 'При перемещении файла произошла ошибка';
                    }
                }
                else
                {
                    $result['status']  = 'error';
                    $result['message'] = 'Недопустимый формат файла - ' . $extension . '. Допустимые: ' . $ext;
                }
            }
            else
            {
                $result['status']  = 'error';
                $result['message'] = $error;
            }
        }
        else
        {
            $result['status']  = 'error';
            $result['message'] = 'Файл не выбран';
        }
    
        return $result;
    }
    
    
    function catchFileWithResize($file, $src=false, $sizeW = "0", $sizeH = "0")
    {
        if ($src == true) $imgupload = "/pictures_src";
        else $imgupload = IMAGEUPLOAD;
    
        $source_size = @getimagesize($_FILES[$file]['tmp_name']);
    
        // -- GET FILE EXTENTION --
        $tmp = explode(".", $_FILES[$file]['name']);
        $ext = strtolower($tmp[count($tmp)-1]);
    
        switch($ext) {
    
            case 'gif':
    
                $uploadTempfile = ROOTDIR . "/images/upload_temp/" . time() . ".gif";
                move_uploaded_file($_FILES[$file]['tmp_name'], $uploadTempfile);
                chmod($uploadTempfile, 0777);
    
                if ($sizeW != 0 && $sizeH != 0) {
    
                    $source = imagecreatefromgif($uploadTempfile);
                    $resampled = imagecreatetruecolor($sizeW, $sizeH);
    
                    $query = "INSERT INTO `pictures` (picture_path) VALUES ('temp')";
                    $result = App::db()->query($query);
                    $id = App::db()->lastInsertId();
    
                    $uploadfile = ROOTDIR . $imgupload . "/" . $id . "_" . toTranslit($_FILES[$file]['name']);
                    $query = "UPDATE pictures SET picture_path='" . $imgupload . "/" . $id . "_" . toTranslit($_FILES[$file]['name']) . "' WHERE picture_id='$id'";
                    $result = App::db()->query($query);
    
                    imagecopyresampled($resampled, $source, 0, 0, 0, 0, $sizeW, $sizeH, $source_size[0], $source_size[1]);
                    if (!imagegif($resampled, $uploadfile, 100))
                    {
                        $query = "DELETE FROM pictures WHERE picture_id='$id'";
                        $result = App::db()->query($query);
                        return false;
                    }
    
                }
                @unlink($uploadTempfile);
                break;
    
            default:
                break;
        }
    }
    
    function pictureId2path($id)
    {
        try
        {
            $result = App::db()->query("SELECT * FROM `pictures` WHERE `picture_id` = '{$id}' LIMIT 1");
            
            if ($row = $result->fetch()) {
                return $row['picture_path'];
            }
        }
        catch (Exception $e)
        {
        }
    }
    
    function fileId2path ($id)
    {
        return pictureId2path($id);
    }
    
    function rmdirR ($dir) {
        $d = dir($dir);
        while($entry = $d->read()) {
            if ($entry != '.' && $entry != '..') {
                if (is_dir($dir. DS .$entry)) {
                    rmdirR($dir. DS .$entry);
                }
                else {
                    unlink ($dir. DS .$entry);
                }
            }
        }
        $d->close();
        rmdir ($dir);
    }
    
    function deletepicture($id)
    {
        if (!empty($id))
        {
            $r = App::db()->query("SELECT `picture_path` FROM `pictures` WHERE `picture_id` = '" . abs(intval($id)) . "' LIMIT 1");
            
            $row = $r->fetch();
            
            if (file_exists(ROOTDIR . $row['picture_path'])) {
                @unlink(ROOTDIR . $row['picture_path']);
            }
    
            App::db()->query("DELETE FROM pictures WHERE `picture_id` = '" . abs(intval($id)) ."' LIMIT 1");
            
            return true;
        }
        else
            return false;
    }
    
    function userId2userName($userId)
    {
        $row = App::db()->query("SELECT `user_name` FROM users WHERE user_id='" . intval($userId) . "' LIMIT 1")->fetch();
        
        return stripslashes($row['user_name']);
    }
    
    function userId2userEmail($userId)
    {
        $row = App::db()->query("SELECT `user_email` FROM users WHERE user_id='" . intval($userId) . "'")->fetch();
        
        return $row['user_email'];
    }
    
    /**
     * Получить телефон пользователя
     * @param object $userId - пользователь
     * @param object $explode [optional] - получить телефон строкой или разбитый в массиве
     * @return
     */
    function userId2userTel ($userId, $explode = false)
    {
        $r = App::db()->query(sprintf("SELECT `user_phone` FROM `users` WHERE `user_id` = '%d' LIMIT 1", $userId));
        $row = $r->fetch();
    
        $row['user_phone'] = str_replace('+', '', $row['user_phone']);
    
        if (!$explode)
            return $row['user_phone'];
        else
        {
            $tel = array();
    
            if (strlen($row['user_phone']) == 11)
            {
                $tel['user_tel_0'] = substr($row['user_phone'], 0, 1);
                $tel['user_tel_1'] = substr($row['user_phone'], 1,3);
                $tel['user_tel_2'] = substr($row['user_phone'], 4);
            }
            else
            {
                $tel['user_tel_0'] = substr($row['user_phone'], 0, 2);
                $tel['user_tel_1'] = substr($row['user_phone'], 2,3);
                $tel['user_tel_2'] = substr($row['user_phone'], 5);
            }
    
            return $tel;
        }
    }
    
    /**
     * Преобразование id юзера в логин с добавлением информации
     * @return
     * @param object $userId - id юзера
     * @param object $link - указывать ли ссылку на профиль
     * @param object $designerLevel - указывать ли квадрат (дизайн - уровень)
     * @param object $admin - ссылка в админку или пользовательскую часть
     * @param object $chase - показывать ли погон
     */
    function userId2userLogin ($userId, $link = null, $designerLevel = null, $admin = null, $chase = null)
    {
        $result = App::db()->query("SELECT `user_id`, `user_login`, `user_designer_level` FROM `users` WHERE `user_id` = '" . $userId . "'");
        $row = $result->fetch();
        $output = $row['user_login'];
    
        if (!empty($link))
        {
            if (!empty($admin))
            {
                if ($output == "ljuser" . $userId)
                {
                    $output = "<img src='/images/ljuser.gif' border='0'>" . $output;
                }
                $output = "<a href='http://www.maryjane.ru/index_admin.php?module=users&amp;task=view&amp;id=$userId'>$output</a>";
    
            } else {
                $output = "<a href='http://www.maryjane.ru/profile/$userId/'>$output</a>";
            }
        }
        if (!empty($designerLevel))
        {
            $output .= "&nbsp;" . designerLevel2Picture($row['user_designer_level']);
        }
    
        $output = str_replace('.livejournal.com', '', $output);
    
        return($output);
    }
    
    function countDays2text ($count)
    {
        if ($count == 1) { return("1 день"); }
        if ($count > 1 && $count<5) { return("$count дня"); }
        if ($count > 4 && $count<21) { return("$count дней"); }
        //if ($count>20 && $count<100)
        if ($count>20)
        {
            if ($count%10 == 1) { return("$count день"); }
            if ($count%10 > 1 && $count%10 < 5) { return("$count дня"); }
            if (($count%10 > 4 && $count%10 < 10) || $count%10 == 0) { return("$count дней"); }
        }
    }
    
    function getVariableValue($name)
    {
        $r = App::db()->prepare("SELECT `variable_value` FROM variables WHERE variable_name = :name LIMIT 1");
        
        $r->execute(['name' => strtolower($name)]);
        
        if ($var = $r->fetch()) {
            $v = $var['variable_value'];
        }
    
        return $v;
    }
    
    function getVariableDescription($name)
    {
        $r = App::db()->query("SELECT `variable_description` FROM variables WHERE variable_name = '$name'");
        
        if ($var = $r->fetch()) {
            return $var['variable_description'];
        }
    }
    
    function setVariableValue ($name, $value)
    {
        global $memcache;
        
        $sth = App::db()->prepare("INSERT INTO `variables` SET 
                    `variable_name` = :name, 
                    `variable_value` = :value 
                  ON DUPLICATE KEY UPDATE 
                    `variable_value` = :value");
                    
        $sth->execute(array(
            'name' => $name,
            'value' => $value,
        ));  
        
        // сбрасываем кэш 
        $memcache->delete('VARS');
    }
    
    function bool2text($input)
    {
        return $input == "true" ? "Да" : "Нет";
    }
    
    function pagination($cp, $page, $link, $max, $next = false, $params = null)
    {
        $pages = array();
    
        if ($cp <= $max)
        {
            if ($page != 1)
                $pages[] = "<a rel='nofollow' href=" . str_replace('/page', '', $link) . "/>1</a>";
            else
                $pages[] = "<span>1</span>";
    
            for ($i = 2; $i <= $cp; $i++)
            {
                if ($page == $i)
                    $pages[] = "<span>$i</span>";
                else
                    $pages[] = "<a rel='nofollow' href='$link/$i/" . (count($params) > 0 ? '?' . http_build_query($params) : '') . "'>$i</a>";
            }
        }
        else
        {
            // В середине списка
            if ($page > ceil($max / 2) && $page < ($cp-ceil($max / 2)))
            {
                $pages[] = "<a rel='nofollow' href=" . $link . "/" . ($page-1)  . "/>&lt;</a>";
                $pages[] = "<a rel='nofollow' href=" . str_replace('/page', '', $link) . "/>1</a>";
                $pages[] = '<div>...</div>';
    
                for ($i = $page - floor($max / 2); $i <= $page + floor($max / 2); $i++)
                {
                    if ($page == $i)
                        $pages[] = "<span>$i</span>";
                    else
                        $pages[] = "<a rel='nofollow' href='" . $link . "/" . ($i == 1 ? '' : $i . '/') . (count($params) > 0 ? '?' . http_build_query($params) : '') . "'>$i</a>";
                }
    
                $pages[] = '<div>...</div>';
                $pages[] = "<a rel='nofollow' href='" . $link . "/$cp/" . (count($params) > 0 ? '?' . http_build_query($params) : '') . "'>$cp</a>";
            }
    
            // в меньшей половине
            if ($page <= ceil($max / 2))
            {
                if ($page != 1)
                    $pages[] = "<a rel='nofollow' href='" . str_replace('/page', '', $link) . "/" . (count($params) > 0 ? '?' . http_build_query($params) : '') . "'>1</a>";
                else
                    $pages[] = "<span>1</span>";
    
                for ($i = 2; $i <= $max; $i++)
                {
                    if ($page == $i)
                        $pages[] = "<span>$i</span>";
                    else
                        $pages[] = "<a rel='nofollow' href='" . $link . "/" . ($i == 1 ? '' : $i . '/') . (count($params) > 0 ? '?' . http_build_query($params) : '') . "'>$i</a>";
                }
    
                $pages[] = '<div>...</div>';
                $pages[] = "<a rel='nofollow' href='" . $link . "/$cp/" . (count($params) > 0 ? '?' . http_build_query($params) : '') . "'>$cp</a>";
            }
    
            // в большей половине
            elseif ($page >= ($cp - ceil($max / 2)))
            {
                $pages[] = "<a rel='nofollow' href='" . $link . "/" . ($page - 1)  . "/" . (count($params) > 0 ? '?' . http_build_query($params) : '') . "'>&lt;</a>";
                $pages[] = "<a rel='nofollow' href='" . str_replace('/page', '', $link) . "/" . (count($params) > 0 ? '?' . http_build_query($params) : '') . "'>1</a>";
                $pages[] = '<div>...</div>';
    
                for ($i = $cp - $max + 1; $i<=$cp; $i++)
                {
                    if ($page == $i)
                        $pages[] = "<span>$i</span>";
                    else
                        $pages[] = "<a rel='nofollow' href='" . $link . "/" . ($i == 1 ? '' : $i . '/') . (count($params) > 0 ? '?' . http_build_query($params) : '') . "'>$i</a>";
                }
            }
        }
    
        // "следующая страница"
        if ($next)
        {
            if ($page < $cp)
                $pages[] = "<a href='" . $link . "/" . ($page + 1) . "/" . (count($params) > 0 ? '?' . http_build_query($params) : '') . "'>></a>";
            else {
                $pages[] = "<a href='" . $link . "/1/" . (count($params) > 0 ? '?' . http_build_query($params) : '') . "'><<</a>";
            }
        }
    
        //$pages[0] = "<a href=" . str_replace('/page', '', $link) . "/>1</a>";
    
        return implode(' ', $pages);
    }
    
    
    function userId2userAvatar ($userId, $size='25', $no_avatar_name='nophoto.gif')
    {
        $query  =  "SELECT p.`picture_path`
                FROM `users` u, `pictures` p
                WHERE u.`user_id` = '".intval($userId)."' AND p.`picture_id` = u.`user_picture`";
        $result = App::db()->query($query);
    
        if ($result->rowCount() > 0) {
            $row = $result->fetch();
            $avatar_link = "<img src='". $row['picture_path'] . "' alt='avatar' width='".$size."' height='".$size."'  style='vertical-align:middle; border:none;' />";
        } else {
            $avatar_link = "<img src='images/designers/". $no_avatar_name ."' width='".$size."' alt='no avatar' style='vertical-align:middle; border:none;' />";
        }
    
        return $avatar_link;
    }
    
    function userId2userGoodAvatar($userId, $size='50', $no_avatar_name = null, $noimage = 0, $nocache = false)
    {
        if (is_file(ROOTDIR . IMAGEUPLOAD . '/avatars/avatar_' . substr(md5($userId),0,6) . '.gif'))
        {
            if ($size != 100) {
                $avatar_path = IMAGEUPLOAD . '/avatars/avatar_' . substr(md5($userId),0,6) . '_tbn_' . $size . '.gif';
            } else {
                $avatar_path = IMAGEUPLOAD . '/avatars/avatar_' . substr(md5($userId),0,6) . '.gif';
            }
        }
        else
        {
            if (!empty($no_avatar_name))
                $avatar_path = $no_avatar_name;
            else {
                if ($size == 100)
                    $avatar_path = '/images/designers/nophoto100.gif';
                else
                    $avatar_path = '/images/designers/nophoto50.gif';
            }
        }
    
        $avatar_link = "<img src='" . $avatar_path . (($nocache) ? '?='.rand(1,99999) : '') . "' width='$size' height='$size' alt='' class='avatar' />";
    
        if ($noimage == 0)
            return $avatar_link;
        else {
            return $avatar_path;
        }
    }
    
    
    /**
     * Получить разницу двух дат в часах
     *
     * @param начальная дата $dateS
     * @param конечная дата $dateE (0 - сечас)
     * @return разница
     */
    function getDateDiff($dateS, $dateE = 0, $round = true, $format = 'h') {
    
        $tmp  = explode(" ", $dateS);
        $date = explode("-", $tmp[0]);
        $time = explode(":", $tmp[1]);
        $dateS = mktime($time[0], $time[1], $time[2], $date[1], $date[2], $date[0]);
        
        if (empty($dateE))
            $dateE = time();
        else
        {
            $tmp   = explode(" ", $dateE);
            $date  = explode("-", $tmp[0]);
            $time  = explode(":", $tmp[1]);
            $dateE = mktime($time[0], $time[1], $time[2], $date[1], $date[2], $date[0]);
        }
    
        if ($format == 'h')
            $del = 3600;
        elseif ($format == 'd')
            $del = 86400;
        elseif ($format == 'm')
            $del = 60;
    
        if ($round)
            $diff =  floor(($dateE - $dateS) / $del);
        else
            $diff =  round(($dateE - $dateS) / $del, 1);
    
        return $diff;
    }
   
    /**
     * Функция создания уменьшенной копии изображения. Обновлённая
     * Правильно уменьшает прозрачные PNG с сохранением прозрачности
     *
     * @param путь загрузки $loadPath - абсолютный путь
     * @param путь создания картинки $setPath - абсолютный путь до папки назначения
     * @param Размер тумбы $thmb_x
     * @param Качество картинки $quality
     * @return true
     */
    function createThumbNew($loadPath, $setPath, $thmb_x='100', $thmb_y=0, $quality='100', $prop=1, $savedb = 0)
    {
        $filename = basename($loadPath);
        $dirname  = dirname($loadPath);
        $foo      = explode('.', $filename);
        $ext      = strtolower(end($foo));
    
        $setPathFilename = basename($setPath);
        $setPathDirname  = dirname($setPath);
    
        // Если не указывать путь назначения, тумба заменяет оригинал под тем же именем
        if (empty($setPath)) {
            $setPath  = $loadPath;
            //}
            // Если он указан вместе с именем файла
            //elseif (!empty($setPathFilename)) {
            //  exit($setPathDirname);
        } else {
            $thmb_name  = str_replace('.' . $ext, '', $filename) . '_tbn_' . $thmb_x . '.' . $ext;
            $setPath   .= $thmb_name;
        }
    
        //exit('loadpath: ' . $loadPath . '<br />setpath: ' . $setPath . '<br />');
    
        $imgData = getimagesize($loadPath);
    
        if ($thmb_x == 0) $thmb_x = $imgData[0];
        if ($thmb_y == 0) $thmb_y = $imgData[1];
    
        $image = createImageFrom($loadPath);
    
        if ($prop == 1)
        {
            if ($imgData[0] < $thmb_x) {
                $thmb_x = $imgData[0];
                $thmb_y = $imgData[1];
            } else {
                $koef_prop = $imgData[0] / $thmb_x;
                $thmb_y = floor($imgData[1] / $koef_prop);
            }
        }
    
        $thmb_image = imagecreatetruecolor($thmb_x, $thmb_y);
        imagesavealpha($thmb_image, true);
        $transPng=imagecolorallocatealpha($thmb_image,0,0,0,127);
        imagefill($thmb_image, 0, 0, $transPng);
    
        imagecopyresampled($thmb_image, $image, 0, 0, 0, 0, $thmb_x, $thmb_y, $imgData[0], $imgData[1]);
    
        switch ($imgData[2]) {
            case 1:
                if (imagegif($thmb_image, $setPath, $quality) == false) return false;
                break;
            case 2:
                if (imagejpeg($thmb_image, $setPath, $quality) == false) return false;
                break;
            case 3:
                if (imagepng($thmb_image, $setPath) == false) return false;
                break;
        }
    
        chmod($setPath , 0777);
    
        imagedestroy($image);
        imagedestroy($thmb_image);
        
        if ($savedb == 0)
        {
            return $thmb_name;
        }
        else
        {
            $r = App::db()->query("INSERT INTO `pictures` (`picture_path`) VALUES ('" . str_replace(ROOTDIR, '', $setPath) . "')");
            return App::db()->lastInsertId();
        }
    }
    
    function thumb($loadPath, $setPath, $thmb_x='100', $thmb_y=0, $quality = '100', $prop = 1, $savedb = 1)
    {
        $filename = basename($loadPath);
        $dirname  = dirname($loadPath);
        $foo      = explode('.', $filename);
        $ext      = strtolower(end($foo));
    
        $setPathFilename = basename($setPath);
        $setPathDirname  = dirname($setPath);
    
        // Если не указывать путь назначения, тумба заменяет оригинал под тем же именем
        if (empty($setPath)) {
            $setPath  = $loadPath;
        }
    
        //exit('loadpath: ' . $loadPath . '<br />setpath: ' . $setPath . '<br />');
    
        $imgData = getimagesize(ROOTDIR . $loadPath);
    
        if ($thmb_x == 0) $thmb_x = $imgData[0];
        if ($thmb_y == 0) $thmb_y = $imgData[1];
    
        $image = createImageFrom(ROOTDIR . $loadPath);
    
        if ($prop == 1)
        {
            if ($imgData[0] < $thmb_x) {
                $thmb_x = $imgData[0];
                $thmb_y = $imgData[1];
            } else {
                $koef_prop = $imgData[0] / $thmb_x;
                $thmb_y = floor($imgData[1] / $koef_prop);
            }
        }
    
        $thmb_image = imagecreatetruecolor($thmb_x, $thmb_y);
        imagesavealpha($thmb_image, true);
        $transPng=imagecolorallocatealpha($thmb_image,0,0,0,127);
        imagefill($thmb_image, 0, 0, $transPng);
    
        imagecopyresampled($thmb_image, $image, 0, 0, 0, 0, $thmb_x, $thmb_y, $imgData[0], $imgData[1]);
    
        switch ($imgData[2]) {
            case 1:
                if (imagegif($thmb_image, ROOTDIR.$setPath, $quality) == false) return false;
                break;
            case 2:
                if (imagejpeg($thmb_image, ROOTDIR.$setPath, $quality) == false) return false;
                break;
            case 3:
                if (imagepng($thmb_image, ROOTDIR.$setPath) == false) return false;
                break;
        }
    
        chmod(ROOTDIR . $setPath , 0777);
    
        imagedestroy($image);
        imagedestroy($thmb_image);
    
        if ($savedb == 0)
        {
            return $setPath;
        }
        else
        {
            $result['id'] = file2db($setPath);
            $result['path'] = $setPath;
            
            return $result;
        }
    }
        
    /**
     * Функция создания уменьшенной копии изображения
     *
     * @param путь загрузки $loadPath
     * @param путь создания картинки $setPath
     * @param Размер тумбы $thmb_x
     * @param Качество картинки $quality
     * @return true
     */
    function createThumb($loadPath, $setPath, $thmb_x='100', $thmb_y=0, $quality='100', $prop=1)
    {
        $filename = basename($loadPath);
        $foo      = explode('.', $filename);
        $ext      = strtolower(end($foo));
    
        // Если не указывать путь назначения, 
        // тумба заменяет оригинал под тем же именем
        if (empty($setPath)) {
            $setPath = $loadPath;
            $thmb_name = '';
        } else {
            $thmb_name = str_replace('.' . $ext, '', $filename) . '_tbn_' . $thmb_x . '.' . $ext;
        }
    
        $imgData = getimagesize($loadPath);
    
        if ($thmb_x == 0) $thmb_x = $imgData[0];
        if ($thmb_y == 0) $thmb_y = $imgData[1];
    
        switch ($imgData[2]) {
            case 1: // GIF
                $image = imagecreatefromgif($loadPath);
                break;
            case 2: // JPG
                $image = imagecreatefromjpeg($loadPath);
                break;
            case 3: // PNG
                $image = @imagecreatefrompng($loadPath);
                break;
            default:
                return false;
                break;
        }
    
        if ($prop == 1)
        {
            if ($imgData[0] < $thmb_x) {
                $thmb_x = $imgData[0];
                $thmb_y = $imgData[1];
            } else {
                $koef_prop = $imgData[0] / $thmb_x;
                $thmb_y = floor($imgData[1] / $koef_prop);
            }
        }
    
        $thmb_image = imagecreatetruecolor($thmb_x, $thmb_y);
        $color_a = HexToRGB ( 'ffffff' );
        //printr($color_a);
        $color = imagecolorallocate ($thmb_image, $color_a ['r'], $color_a ['g'], $color_a ['b'] );
        imagefill ( $thmb_image, 0, 0, $color );
    
    
        imagecopyresampled($thmb_image, $image, 0, 0, 0, 0, $thmb_x, $thmb_y, $imgData[0], $imgData[1]);
    
        switch ($imgData[2]) {
            case 1:
                if (imagegif($thmb_image, $setPath.$thmb_name, $quality) == false) return false;
                break;
            case 2:
                if (imagejpeg($thmb_image, $setPath.$thmb_name, $quality) == false) return false;
                break;
            case 3:
                if (imagepng($thmb_image, $setPath.$thmb_name) == false) return false;
                break;
        }
    
        chmod($setPath.$thmb_name , 0777);
    
        imagedestroy($image);
        imagedestroy($thmb_image);
    
        return $thmb_name;
    }
    
    /**
     * Создать объект изображение по указанному пути
     */
    function createImageFrom($loadPath)
    {
        $imgData = getimagesize($loadPath);
    
        switch ($imgData[2]) {
            case 1: // GIF
                $image = @imagecreatefromgif($loadPath);
                break;
            case 2: // JPG
                $image = @imagecreatefromjpeg($loadPath);
                break;
            case 3: // PNG
                $image = @imagecreatefrompng($loadPath);
                break;
            default:
                return false;
                break;
        }
        return $image;
    }
    
    
    /**
     * Функция ресайза картинок
     *
     * @param путь до исходной картинки $loadPath
     * @param путь до папки с обрезаной картинкой $setPath
     * @param координата - x на исходной картинке $src_x
     * @param координата - y на исходной картинке $src_y
     * @param ширина обрезаемой области $width
     * @param высота обрезаемой области $height
     */
    function createCropedImg ($loadPath, $setPath, $src_x = 0, $src_y = 0, $width = 100, $height = 100)
    {
        $imgData = getimagesize($loadPath);
    
        switch ($imgData[2]) {
            case 1: // GIF
                $src_img = imagecreatefromgif($loadPath);
                break;
            case 2: // JPG
                $src_img = imagecreatefromjpeg($loadPath);
                break;
            case 3: // PNG
                $src_img = @imagecreatefrompng($loadPath);
                break;
            default:
                break;
        }
    
        $src_size = getimagesize($loadPath);
    
        if ($width == 0)  $width  = $src_size[0];
        if ($height == 0) $height = $src_size[1];
    
        $dst_img = imagecreatetruecolor($width, $height);
    
        if (!imagecopy($dst_img, $src_img, 0, 0, $src_x, $src_y, $width, $height)) {
            ImageDestroy($src_img);
            ImageDestroy($dst_img);
            return false;
        }
    
        $mas = array();
        $mas = explode('/', $loadPath);
        $n = count($mas);
    
        $mas2 = array();
        $mas2 = explode('.', $mas[$n-1]);
        $n2 = count($mas2);
    
        // Формируем имя вида ORIGINAL_NAME_resized_WIDTHxHEIGHT.ORIGINAL_EXTENTION
        $croped_name = $mas2[$n2-2].'_resized_' . $width. 'x' . $height . '.' . $mas2[$n2-1];
    
        switch ($imgData[2]) {
            case 1: // GIF
                if (imagegif($dst_img, $setPath . $croped_name, 100) == false) return false;
                break;
            case 2: // JPG
                if (imagejpeg($dst_img, $setPath . $croped_name, 100) == false) return false;
                break;
            case 3: // PNG
                if (imagepng($dst_img, $setPath . $croped_name) == false) return false;
                break;
        }
    
        ImageDestroy($src_img);
        ImageDestroy($dst_img);
    
        return ($croped_name);
    }
    
    /**
     * Валидация логина
     *
     * @param string $str
     * @return boolean
     */
    function validateLogin($str) {
    
        $allowed_sym = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789_';
    
        for ($i=0; $i<=(strlen($str)-1); $i++) {
            if (!strstr($allowed_sym, $str{$i})) {
                return false;
            }
        }
        
        if (strlen($str) > 15)
            return false;
            
        return true;
    }
    
    function validateEmail($str) {
        if (!preg_match("/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-z]{2,6})$/", $str))
            return false;
        else
            return true;
    }
    
    function strip_JS($str)
    {
        //$str = str_replace('"', "'", $str);
        //$search = array ("'on(.*?)=\'(.*?)\''i", "'javascript:'i");
        $search = array ("'onclick=\'(.*?)\''i", "'onkeyup=\'(.*?)\''i", "'onmouseover=\'(.*?)\''i", "'javascript:'i");
        $str =  preg_replace($search, '', $str);
        return $str;
    }
    
    function get_city_by_ip($ip = '', $reset_cache = FALSE)
    {
        if(!$_COOKIE["location"] or $reset_cache) {
    
            if (!$ip) {
                //$ip = ВАША_ФУНКЦИЯ_ОПРЕДЕЛЕНИЯ_IP();
                return false;
            }
    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://ipgeobase.ru:7020/geo?ip=' . $ip);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
            curl_setopt($ch, CURLOPT_USERAGENT, 'PHP Bot');
    
            $data = curl_exec($ch);
            $city = ( !curl_errno($ch) && $xml = simplexml_load_string($data) ) ? $xml->ip->city : false;
            curl_close($ch);
    
            if ($city) {
                $city_enc = base64_encode($city);
                setcookie('location', $city_enc, time()+3600*24*7, '/'); //set cookie for 1 week
            }
        } else {
            $city = base64_decode($_COOKIE["location"]);
        }
    
        return $city;
    }
    
    
    function ip2city($ip = '', $reset_cache = FALSE)
    {
        if (!$_COOKIE['geo'] or $reset_cache) {
    
            if (!$ip) {
                //$ip = ВАША_ФУНКЦИЯ_ОПРЕДЕЛЕНИЯ_IP();
                return false;
            }
    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://ipgeobase.ru:7020/geo?ip=' . $ip);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
            curl_setopt($ch, CURLOPT_USERAGENT, 'PHP Bot');
    
            $data = curl_exec($ch);
            
            if (!curl_errno($ch))
            {
                $xml = simplexml_load_string($data);
                $r['city'] = (string) $xml->ip->city[0];
                $r['country'] = (string) $xml->ip->country[0];
            }
            
            curl_close($ch);
    
            if ($r) {
                $city_enc = json_encode($r);
                setcookie('geo', $city_enc, time()+3600*24*7, '/'); //set cookie for 1 week
            }
        } else {
            $r = json_decode(stripslashes($_COOKIE['geo']), 1);
        }
    
        return $r;
    }

    function cityName2id($city, $country = null, $add = 0, $strict = 0) {
    
        $sth = App::db()->prepare("SELECT `id` FROM `city` WHERE `name` = :name " . (($strict) ? " AND `status` = '1'" : '') . " LIMIT 1");
        $sth->execute(['name' => trim($city)]);
        $city = $sth->fetch();
        
        if (empty($city['id']) && $add)
        {
            $sth = App::db()->prepare("insert into `city` set `name` = :name, `country` = '" . intval(trim($country)) . "'");
            $sth->execute(['name' => trim($city)]);
            
            $city['id'] = App::db()->lastInsertId();
        } 
        
        return $city['id'];
    }
    
    
    function SpiderDetect($USER_AGENT){
        
        $engines = array(
            array('Aport', 'Aport robot'),
            array('Google', 'Google'),
            array('Googlebot', 'Googlebot'),
            array('msnbot', 'MSN'),
            array('Rambler', 'Rambler'),
            array('Yahoo', 'Yahoo'),
            array('AbachoBOT', 'AbachoBOT'),
            array('accoona', 'Accoona'),
            array('AcoiRobot', 'AcoiRobot'),
            array('ASPSeek', 'ASPSeek'),
            array('CrocCrawler', 'CrocCrawler'),
            array('Dumbot', 'Dumbot'),
            array('FAST-WebCrawler', 'FAST-WebCrawler'),
            array('GeonaBot', 'GeonaBot'),
            array('Gigabot', 'Gigabot'),
            array('Lycos', 'Lycos spider'),
            array('MSRBOT', 'MSRBOT'),
            array('Scooter', 'Altavista robot'),
            array('AltaVista', 'Altavista robot'),
            array('WebAlta', 'WebAlta'),
            array('IDBot', 'ID-Search Bot'),
            array('eStyle', 'eStyle Bot'),
            array('Mail.Ru', 'Mail.Ru Bot'),
            array('Scrubby', 'Scrubby robot'),
            array('Yandex', 'Yandex'),
            array('YaDirectBot', 'Yandex Direct'),
            array('bingbot', 'bingbot'),
            array('AhrefsBot', 'AhrefsBot'),
            array('dotbot', 'dotbot'),
            array('Wget', 'Wget'),
            array('Rootlebot', 'Rootlebot'),
            array('MJ12bot', 'MJ12bot'),
            array('Twitterbot', 'Twitterbot'),
            array('YandexBot', 'YandexBot'),
            array('Go-http-client', 'Go-http-client'),
            array('SMTBot', 'SMTBot'),
            array('SputnikBot', 'SputnikBot'),
        );
    
        foreach ($engines as $engine) {
            if (strstr($USER_AGENT, $engine[0])){
                return($engine[1]);
            }
        }
        
        if (strpos($USER_AGENT, 'robot') !== false || strpos($USER_AGENT, 'bot.html') !== false)
            return 'another';
            
        return false;
    }
    
    function objectToArray( $object )
    {
        if( !is_object( $object ) && !is_array( $object ) )
        {
            return $object;
        }
        if( is_object( $object ) )
        {
            $object = get_object_vars( $object );
        }
        return array_map( 'objectToArray', $object );
    }
    
    /**
     * создать папки из пути
     */
    function createDir($path)
    {
        if (!is_dir(ROOTDIR . $path))
        {
            $path = explode('/', $path);
    
            umask(0002);
            $ppath = ROOTDIR;
    
            foreach($path as $f)
            {
                if (!empty($f))
                {
                    $ppath .= '/' . $f;
                    if (!is_dir($ppath)) mkdir($ppath, 0775);
                }
            }
        }
    }
    
    function file2db($path)
    {
        if (!empty($path))
        {
        //if (file_exists(ROOTDIR . $path)) {
            $sth = App::db()->prepare("INSERT INTO `pictures` SET `picture_path` = :path");
            $sth->execute(['path' => trim($path)]);
            return App::db()->lastInsertId();
        //}
        }
        
        return true;
    }
    
    function crop_str($string, $limit)
    {
        $substring_limited = iconv_substr($string,0, $limit, 'utf-8');        //режем строку от 0 до limit
        
        if (iconv_strlen($substring_limited, 'utf-8') < $limit)
            return $substring_limited;
        else
            return iconv_substr($substring_limited, 0, iconv_strrpos($substring_limited, ' ', 'utf-8'), 'utf-8');    //берем часть обрезанной строки от 0 до последнего пробела
    }
    
    /**
     * просклонять слово "дни"
     */
    function declineDay($d)
    {
        if ($d == 1)
            return 'день';
        elseif ($d > 1 && $d < 5)
            return 'дня';
        elseif ($d >= 5 && $d <= 20)
            return 'дней';
        elseif ($d == 21)
            return 'день';
        elseif ($d > 21 && $d < 5)
            return 'дня';
    }
    
    function json_encode_cyr($str) {
        $arr_replace_utf = array('\u0410', '\u0430','\u0411','\u0431','\u0412','\u0432',
        '\u0413','\u0433','\u0414','\u0434','\u0415','\u0435','\u0401','\u0451','\u0416',
        '\u0436','\u0417','\u0437','\u0418','\u0438','\u0419','\u0439','\u041a','\u043a',
        '\u041b','\u043b','\u041c','\u043c','\u041d','\u043d','\u041e','\u043e','\u041f',
        '\u043f','\u0420','\u0440','\u0421','\u0441','\u0422','\u0442','\u0423','\u0443',
        '\u0424','\u0444','\u0425','\u0445','\u0426','\u0446','\u0427','\u0447','\u0428',
        '\u0448','\u0429','\u0449','\u042a','\u044a','\u042b','\u044b','\u042c','\u044c',
        '\u042d','\u044d','\u042e','\u044e','\u042f','\u044f','\u00ab','\u00bb', '\u2116', '\u2013');
        $arr_replace_cyr = array('А', 'а', 'Б', 'б', 'В', 'в', 'Г', 'г', 'Д', 'д', 'Е', 'е',
        'Ё', 'ё', 'Ж','ж','З','з','И','и','Й','й','К','к','Л','л','М','м','Н','н','О','о',
        'П','п','Р','р','С','с','Т','т','У','у','Ф','ф','Х','х','Ц','ц','Ч','ч','Ш','ш',
        'Щ','щ','Ъ','ъ','Ы','ы','Ь','ь','Э','э','Ю','ю','Я','я', "«", "»", '№', '-');
        $str1 = json_encode($str);
        $str2 = str_replace($arr_replace_utf,$arr_replace_cyr,$str1);
        return $str2;
    }
    
    function strrtoupper($str) { 
        $trans=array( 
         "а" => "А",  "б" => "Б",  "в" => "В",  "г" => "Г",  "д" => "Д",  "е" => "Е",  
         "ё" => "Ё",  "ж" => "Ж",  "з" => "З",  "и" => "И",  "й" => "Й",  "к" => "К",  
         "л" => "Л",  "м" => "М",  "н" => "Н",  "о" => "О",  "п" => "П",  "р" => "Р",  
         "с" => "С",  "т" => "Т",  "у" => "У",  "ф" => "Ф",  "х" => "Х",  "ц" => "Ц",  
         "ч" => "Ч",  "ш" => "Ш",  "щ" => "Щ",  "ь" => "Ь",  "ы" => "Ы",  "ъ" => "Ъ",  
         "э" => "Э",  "ю" => "Ю",  "я" => "Я",  
        );  
        $str=strtr($str,  $trans);  
        return($str);  
    }
    
    /**
     * Прообразование числа в текстовый вид
     */
    function num_propis($num){ // $num - цело число
    
        # Все варианты написания чисел прописью от 0 до 999 скомпануем в один небольшой массив
         $m=array(
          array('ноль'),
          array('-','один','два','три','четыре','пять','шесть','семь','восемь','девять'),
          array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать','пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать'),
          array('-','-','двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят','восемьдесят','девяносто'),
          array('-','сто','двести','триста','четыреста','пятьсот','шестьсот','семьсот','восемьсот','девятьсот'),
          array('-','одна','две')
         );
        
        # Все варианты написания разрядов прописью скомпануем в один небольшой массив
         $r=array(
          array('...ллион','','а','ов'), // используется для всех неизвестно больших разрядов 
          array('тысяч','а','и',''),
          array('миллион','','а','ов'),
          array('миллиард','','а','ов'),
          array('триллион','','а','ов'),
          array('квадриллион','','а','ов'),
          array('квинтиллион','','а','ов')
          // ,array(... список можно продолжить
         );
        
         if($num==0)return$m[0][0]; # Если число ноль, сразу сообщить об этом и выйти
         $o=array(); # Сюда записываем все получаемые результаты преобразования
        
        # Разложим исходное число на несколько трехзначных чисел и каждое полученное такое число обработаем отдельно
         foreach(array_reverse(str_split(str_pad($num,ceil(strlen($num)/3)*3,'0',STR_PAD_LEFT),3))as$k=>$p){
          $o[$k]=array();
        
        # Алгоритм, преобразующий трехзначное число в строку прописью
          foreach($n=str_split($p)as$kk=>$pp)
          if(!$pp)continue;else
           switch($kk){
            case 0:$o[$k][]=$m[4][$pp];break;
            case 1:if($pp==1){$o[$k][]=$m[2][$n[2]];break 2;}else$o[$k][]=$m[3][$pp];break;
            case 2:if(($k==1)&&($pp<=2))$o[$k][]=$m[5][$pp];else$o[$k][]=$m[1][$pp];break;
           }$p*=1;if(!$r[$k])$r[$k]=reset($r);
        
        # Алгоритм, добавляющий разряд, учитывающий окончание руского языка
          if($p&&$k)switch(true){
           case preg_match("/^[1]$|^\\d*[0,2-9][1]$/",$p):$o[$k][]=$r[$k][0].$r[$k][1];break;
           case preg_match("/^[2-4]$|\\d*[0,2-9][2-4]$/",$p):$o[$k][]=$r[$k][0].$r[$k][2];break;
           default:$o[$k][]=$r[$k][0].$r[$k][3];break;
          }$o[$k]=implode(' ',$o[$k]);
         }
         
         return implode(' ',array_reverse($o));
    }
    
    /**
     * отправить файл в поток
     * для прямого скачивания браузером
     */
    function file_force_download($file) 
    {
      if (file_exists($file)) {
        // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
        // если этого не сделать файл будет читаться в память полностью!
        if (ob_get_level()) {
          ob_end_clean();
        }
        
        // заставляем браузер показать окно сохранения файла
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        
        header('Content-Disposition: attachment; filename=' . basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        
        // читаем файл и отправляем его пользователю
        readfile($file);
        return true;
      }
    }
    
    function usdRateDaily() 
    {
        if (!$dollar = App::memcache()->get('dollarDaily' . NOWDATE)) 
        {
            $xml = simplexml_load_file('http://www.cbr.ru/scripts/XML_daily.asp');
        
            if ($xml) 
            {
                foreach($xml->Valute AS $v)
                {
                    if ((int) $v->NumCode == 840) {
                        $dollar = (string) $v->Value;
                    }
                }
            }
            
            if (!empty($dollar))
                App::memcache()->set('dollarDaily' . NOWDATE, $dollar, false, 3600 * 25);
            else {
                $dollar = App::memcache()->get('dollarDaily' . date('Y-m-d', time() - 3600 * 24));
            }
        }
        
        return $dollar;
    }
    
    
    /**
     * Создать укороченную ссылку с помощью гуглового укорачивателя
     * @param string $url - адрес для укорачивания
     */
    function createShortUrl($url)
    {
        define("AUTH_KEY","AIzaSyCriORz0xqwb7T9eVFxuwBlTbmz5gb2Uh4");
        define("API_URL","https://www.googleapis.com/urlshortener/v1/url");
        
        $ku = curl_init();
        
        curl_setopt($ku,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ku,CURLOPT_SSL_VERIFYHOST,FALSE);
        
        curl_setopt($ku,CURLOPT_RETURNTRANSFER,TRUE);
        curl_setopt($ku,CURLOPT_POST,TRUE);
        
        curl_setopt($ku,CURLOPT_POSTFIELDS,json_encode(array("longUrl"=>$url)));
        
        curl_setopt($ku,CURLOPT_HTTPHEADER,array("Content-Type:application/json"));
        
        curl_setopt($ku,CURLOPT_URL,API_URL."?key=".AUTH_KEY);
        
        $result = json_decode(curl_exec($ku));
        
        curl_close($ku);
    
        return $result->id;
    }
    
    /**
     * Перобразовать запись телефона к стандартной для записи в базу данных
     * @param string $phone телефон
     * @return string стандартизованная запись телефона
     */
    function normalizePhone($phone) {
        $phone = str_replace(array(' ', '(', ')', '-', '+'), '', $phone);
    
        if (strpos($phone, '8') === 0)
            $phone = '7' . substr($phone, 1);
        
        return $phone;
    }
    
    // Session management functions
    function set_session_value($sid, $tbl_row, $value)
    {
        $result = App::db()->query("UPDATE `sessions` SET `{$tbl_row}` = '" . addslashes($value) . "' WHERE `session_id` = '" . $sid . "' LIMIT 1");
    }
    
    function get_session_value($SESSIONID, $tbl_row)
    {
        $r = App::db()->prepare("SELECT `{$tbl_row}` FROM `sessions` WHERE `session_id` = :sid")->fetch();
        $r->execute(['sid' => trim($SESSIONID)]);
        $foo = $r->fetch();
        return $foo[$tbl_row];
    }
    
    /**
     * Функция разбора логов апача
     */
    function log_apache_parser($file, $time=3, $max=1000, $ip=false, $bot=false, $admin=false, $error=false, $search = null)
    {
        if (is_file($file))
        {
            if($file=@file_get_contents($file))
            {
                $admin_IP=$_SERVER['REMOTE_ADDR'];//IP админа
                $time=$time*3600;//разница часов во времени с сервером (UTC)
                $result='';
                $file=preg_replace( "#\r\n|\r|\n#",PHP_EOL,$file);//унификация делителя для разных ОС
                $file=explode(PHP_EOL,$file);
                $file=array_reverse($file);
    
                $max++;
                
                foreach($file as $i=>$val)
                {
                    if($i==$max)
                        break;
    
                    if($val!=='')
                    {
                        preg_match_all('~"(.*?)(?:"|$)|([^"]+)~',$val,$m,PREG_SET_ORDER);
                        $temp=[];
                        $break=false;//не было отмены парса строки
                        
                        foreach($m as $ii=>$val2)
                        {
                            $val2[0]=trim($val2[0]);//echo $ii.'<br>';
                            if($val2[0]=='')continue;
                            
                            if($ii==0)//IP и дата
                            {
                                $temp2=explode(' - - ',$val2[0]);
                                $temp2[0]=trim($temp2[0]);
                                
                                if(($ip) && $ip!==$temp2[0])
                                {
                                    $max++;
                                    $break=true;
                                    break;
                                }
                                
                                $temp['ip']= $temp2[0];
                                $DATE=str_replace(['[',']'],'',$temp2[1]);
                                $DATE=explode(':',$DATE);
                                $temp['time']=date('Y-m-d H:i:s',strtotime(str_replace('/',' ',$DATE[0]).' '.$DATE[1].':'.$DATE[2].':'.$DATE[3])+$time);//дата+ time часов
                            }
                            else
                            {
                                if($ii==1)//Запрос
                                {
                                    if(!$admin && strpos($val2[0], 'index_admin.php')) {
                                        $max++;
                                        $break=true;
                                        break;
                                    }
                                    
                                    if( strstr($val2[0],'%'))
                                        $val2[0]=urldecode($val2[0]);
                                    
                                    $temp['request']=trim($val2[0],'"');
    
                                    if ($search && !preg_match($search, $val2[0])) {
                                        $max++;
                                        $break=true;
                                        break;
                                    }
                                }
                                else
                                {
                                    if ($ii==2)//Код ответа
                                    {
                                        $temp['code'] = (int) $val2[0];
                                        
                                        if($temp['code'] < 300)
                                        {
                                            if($error)//исключаем показы 2-XX
                                            {
                                                $max++;
                                                $break=true;
                                                break;
                                            }
                                        }
                                        else
                                        {
                                            if($temp['code'] < 400)
                                            {
                                                if($error)//исключаем показы 3-XX
                                                {
                                                    $max++;
                                                    $break=true;
                                                    break;
                                                }
                                                
                                            }
                                        }
                                    }
                                    else
                                    {
                                        if($ii==3)//откуда
                                        {
                                            if(strstr($val2[0],'%'))$val2[0]=urldecode($val2[0]);//перекодируем кириллицу если есть
                                            $temp['referer']=trim($val2[0],'"');
                                        }
                                        else
                                        {
                                            if($ii==5)//браузер
                                            {
                                                $val2[0]=trim($val2[0],'"');
                                                
                                                if(SpiderDetect($val2[0]))
                                                {
                                                    if(!$bot)//исключаем показы ботов
                                                    {
                                                        $max++;
                                                        $break=true;
                                                        break;
                                                    }
                                                    $temp['bot']=true;
                                                }
                                                $temp['browser']=$val2[0];
                                            }
                                            else
                                            {
                                                $temp['browser']=trim($val2[0],'"');
                                            }
                                        }
                                    }
                                }
                            }
                        }
    
                        if(!$break) {
                            $result[] = $temp;
                        }
                    }
                    else 
                        $max++;
                }
            }
            else 
                throw new Exception('Файл не читается', 1);
        }
        else 
            throw new Exception('Не найден файл логов', 2);
        
        return $result;
    }
    
    function stopAndRedirect($url)
    {
        header('Location: ' . $url);
    
        $content = sprintf(
            '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta http-equiv="refresh" content="1;url=%1$s" /><title>Redirecting to %1$s</title></head><body>Redirecting to <a href="%1$s">%1$s</a>.</body></html>',
            htmlspecialchars($url, ENT_QUOTES, 'UTF-8')
        );
    
        echo $content;
    
        exit;
    }
    
    
    function _GET($key)
    {
        return isset($_GET[$key]) ? $_GET[$key] : null;
    }
    
    function _POST($key)
    {
        return isset($_POST[$key]) ? $_POST[$key] : null;
    }
    
    function IS_POST()
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }
    
    function GET_METHOD()
    {
        $method =  $_SERVER['REQUEST_METHOD'];
    
        if(IS_POST()){
            if(isset($_SERVER['X-HTTP-METHOD-OVERRIDE'])){
                $method = strtoupper($_SERVER['X-HTTP-METHOD-OVERRIDE']);
            }
        }
    
        return $method;
    }
    
    function _e($str)
    {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }
    
    function _d($str, $default)
    {
        return $str ? _e($str) : _e($default);
    }
    
    function dd($value)
    {
        var_dump($value);
        die();
    }
    
    function IS_HTTPS()
    {
        return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off';
    }
    
    function GET_HTTP_HOST()
    {
        $host = IS_HTTPS() ? 'https://' : 'http://';
        $host .= GET_HOST();
        return $host;
    }
    
    function GET_HOST()
    {
        $host = $_SERVER['HTTP_HOST'];
    
        $host = strtolower(preg_replace('/:\d+$/', '', trim($host)));
    
        if ($host && !preg_match('/^\[?(?:[a-zA-Z0-9-:\]_]+\.?)+$/', $host)) {
            throw new \UnexpectedValueException(sprintf('Invalid Host "%s"', $host));
        }
    
        return $host;
    }
    
    function GET_PATH_INFO($baseUrl = null)
    {
        static $pathInfo;
    
        if (!$pathInfo) {
            $pathInfo = $_SERVER['REQUEST_URI'];
    
            if (!$pathInfo) {
                $pathInfo = '/';
            }
    
            $schemeAndHttpHost = IS_HTTPS() ? 'https://' : 'http://';
            $schemeAndHttpHost .= $_SERVER['HTTP_HOST'];
    
            if (strpos($pathInfo, $schemeAndHttpHost) === 0) {
                $pathInfo = substr($pathInfo, strlen($schemeAndHttpHost));
            }
    
            if ($pos = strpos($pathInfo, '?')) {
                $pathInfo = substr($pathInfo, 0, $pos);
            }
    
            if (null != $baseUrl) {
                $pathInfo = substr($pathInfo, strlen($pathInfo));
            }
    
            if (!$pathInfo) {
                $pathInfo = '/';
            }
        }
    
        return $pathInfo;
    }