<?php

namespace application\models;
use smashEngine\core\App;

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
    public function appPicture($pic_id) {
        
        if (empty($pic_id)) {
            return;
        }
        
        $sth = App::db()->prepare("INSERT INTO `" . self::$dbtable_pictures . "` SET 
                `product_id` = ?,
                `big_id` = ?
            ");
        
        $sth->execute([$this->id, $pic_id]);
    }
    
    public function getAll() {
        
        $sth = App::db()->prepare("SELECT * FROM `" . self::$dbtable . "` WHERE 1");
        
        $sth->execute();
        $rows = $sth->fetchAll();
        
        return $rows;
    }
    
    
}