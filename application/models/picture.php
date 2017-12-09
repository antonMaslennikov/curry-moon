<?php
    
    namespace application\models;
    
    use \smashEngine\core\App AS App; 
    use \PDO;
    use \Exception; 

class picture { 

	public $id;					// id картинки
	public $path;				// относительный путь до картинки
	public $errors = array();	// массив ошибок при обработке изображения
	
    public static $dbtable = 'pictures';
    
	function __construct($id = 0)
	{
		if (!empty($id))
			$this->id = $id;
	}
	
	/**
	 * Получить путь до картинки
	 * @return путь
	 */
	function picId2path()
	{
	    $p = App::db()->query("SELECT `picture_path` FROM `pictures` WHERE `picture_id` = '" . $this->id . "'")->fetch();
		
		if (!empty($p['picture_path'])) {
			$this->path = $p['picture_path'];
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Получить путь до указанной картинки
	 * @return путь
	 * @param object $id - id картинки
	 */
	static function pictureId2path($id) {
		$p = App::db()->query("SELECT `picture_path` FROM `pictures` WHERE `picture_id` = '" . $id . "'")->fetch();
        
        if (!empty($p['picture_path'])) {
			return $p['picture_path'];
		} else {
			return false;
		}
    } 

	/**
	 * Поимайть файл из $_FILES и положить в базу
	 * 
	 * @return array(status, message)
	 * @param object $file
	 * @param object $folder - папка назначения
	 * @param object $file_name[optional] - конечное имя файла (без расширения)
	 * @param object $ext[optional] - допустимые расширения файлов
	 * @param object $minx - минимально допустимая ширина (0 - не ограничена)
	 * @param object $miny - минимально допустимая высота (0 - не ограничена)
	 */
	function catchFile ($file, $folder, $file_name = '', $ext = "gif,png,jpeg,jpg", $minx = 0, $miny = 0)
	{
		$result = array();
	
		if (!empty($_FILES[$file]['tmp_name']) && $_FILES[$file]['tmp_name'] != 'none')
		{
			if (!empty ($_FILES[$file]['error']))
		    {
		    	switch ($_FILES[$file]['error'])
		        {
					case '1'   : $error = 'Превышен допустимый размер файла';	break;
					case '2'   : $error = 'Превышен допустимый размер файла';	break;
		            case '3'   : $error = 'Файл загружен лишь частично'; break;
		            case '4'   : $error = 'Не выбран файл для загрузки'; break;
		            case '6'   : $error = 'Отсутствует временная папка на сервере'; break;
		            case '7'   : $error = 'Ошибка записи на диск'; break;
		            case '8'   : $error = 'File upload stopped by extension'; break;
		            case '999' : default : $error = 'Неизвестная ошибка'; break;
		        }
		    }
			
			if (empty ($error))
			{
				// Проверка на расширение файла
				$allowed_ext = explode(',', $ext);
				$extension   = strtolower(end(explode('.', $_FILES[$file]['name'])));
				
				if (in_array($extension, $allowed_ext))
				{
					// Если не указано конкретное имя, генерируем из исходного
					if (empty($file_name))
						$file_name = time() . '_' .toTranslit($_FILES[$file]['name']);
					else
						$file_name .= '.' . $extension;
						
					$uploadFullPath = $folder . $file_name;
	
					if (@move_uploaded_file ($_FILES[$file]['tmp_name'], ROOTDIR . $uploadFullPath)) 
					{
						chmod(ROOTDIR . $uploadFullPath, 0777);
						
						if (!empty($minx) || !empty($miny))
						{
							$file_size = getimagesize(ROOTDIR . $uploadFullPath);
							
							// не совпадают оба размера
							if (!empty($minx) && !empty($miny) && $file_size[0] < $minx && $file_size[1] < $miny)
							{
								$result['status']  = 'error';
								$result['message'] = "Минимальная ширина картинки $minx px, а минимальная высота - $miny px";
								unlink(ROOTDIR . $uploadFullPath);
							} 
							else
							{
								if (!empty($minx) && $file_size[0] < $minx)
								{
									$result['status']  = 'error';
									$result['message'] = "Минимальная ширина картинки $minx px";
									unlink(ROOTDIR . $uploadFullPath);
								}
								
								if (!empty($miny) && $file_size[1] < $miny)
								{
									$result['status']  = 'error';
									$result['message'] = "Минимальная высота картинки $miny px";
									unlink(ROOTDIR . $uploadFullPath);
								}
							}
						}
						
						if ($result['status'] != 'error')
						{
							$r = App::db()->query("INSERT INTO `pictures` (`picture_path`) VALUES ('" . $uploadFullPath . "')");
							$this->path = $uploadFullPath;
							$this->id   = App::db()->lastInsertId();
								
							$result['status']  = 'ok';
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
					$result['message'] = 'Недопустимый формат файла. Допустимые: ' . $ext;
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
	
	static function createThumbNew($loadPath, $setPath, $thmb_x='100', $thmb_y=0, $quality='100', $prop=1)
	{
		$filename = basename($loadPath);
		$ext = strtolower(end(explode('.', $filename)));
	
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
	
		imagecopyresampled($thmb_image,	$image,	0, 0, 0, 0, $thmb_x, $thmb_y, $imgData[0], $imgData[1]);
	
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
	
		chmod($setPath . $thmb_name , 0777);
	
		imagedestroy($image);
		imagedestroy($thmb_image);
	
		$r = App::db()->query("INSERT INTO `pictures` (`picture_path`) VALUES ('" . str_replace(ROOTDIR, '', $setPath.$thmb_name) . "')");
		
		return App::db()->lastInsertId();
	}
	
	function delete()
	{
		
	}
}