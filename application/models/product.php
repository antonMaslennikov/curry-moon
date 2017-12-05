<?php

namespace application\models;
use smashEngine\core\App;
use smashEngine\core\helpers\File;
use smashEngine\core\helpers\Thumbnailer;

/**
 * Class product
 * @package application\models
 *
 * @property int $id ID
 */
class product extends \smashEngine\core\Model {

	/**
	 * @var имя таблицы в БД для хранения экземпляров класса
	 */
	public static $dbtable = 'product';
    
    public static $dbtable_pictures = 'product__pictures';

    public static $manufacturers = [
        0 => ['name' => 'Мы'],
        1 => ['name' => 'ZARA', 'email' => 'instyle@gavick.com'],
        2 => ['name' => 'inStyle', 'email' => ''],
    ];
    
    /**
     * @param int $pic_id добавить изображение для товара
     */
    public function addPicture($pic_id) {
        
        if (empty($pic_id)) {return;}

	    $thumb_id = $this->createThumb($pic_id);
        
        $sth = App::db()->prepare("INSERT INTO `" . self::$dbtable_pictures . "` SET 
                `product_id` = ?,
                `orig_id` = ?,
                `thumb_id` = ?
            ");
        
        $sth->execute([$this->id, $pic_id, $thumb_id]);
    }
    
    public function getPictures()
    {
        $sth = App::db()->prepare("SELECT p.*, p1.`picture_path` AS orig_path, p1.`picture_path` AS thumb_path 
                FROM 
                    `" . self::$dbtable_pictures . "` p, 
                    `" . Picture::$dbtable . "` p1, 
                    `" . Picture::$dbtable . "` p2 
                WHERE
                    p.`product_id` = ? 
                    AND p.`orig_id` = p1.`picture_id`
                    AND p.`thumb_id` = p2.`picture_id`
            ");
        
        $sth->execute([$this->id]);
        $pics = $sth->fetchAll();
        
        $this->pictures = $pics;
        
        return $this->info['pictures'];
    }


	public function createThumb($pic_id) {

		$orig_url = File::getAbsolutePathForUrl(pictureId2path($pic_id));

		$thumb_url = (new Thumbnailer())->thumbnail($orig_url, File::uploadedPath(), 250, 325);

		return file2db($thumb_url);
	}
    
    public function getAll() {
        
        $sth = App::db()->prepare("SELECT * FROM `" . self::$dbtable . "` WHERE 1");
        
        $sth->execute();
        $rows = $sth->fetchAll();
        
        return $rows;
    }
    
    
}