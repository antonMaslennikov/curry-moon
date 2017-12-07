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
    
    /**
     * Получить список товаров
     * @param  mixed $filters массив с параметрами поиска
     * @return array массив с товарами
     */
    public function getAll($filters = null) {
        
        if ($filters['categoryfull'])
        {
            $cats = [$filters['categoryfull']];
            foreach ((new category)->getAllChildren($filters['categoryfull']) AS $c) {
                $cats[] = $c['id'];
            }
            
            $aq[] = "pr.`category` IN ('" . implode("', '", $cats) . "')";
        }
        
        if ($filters['category'])
        {
            $aq[] = "pr.`category` = '" . intval($filters['category']) . "'";
        }
        
        if ($filters['status'] == 'active')
        {
            $aq[] = "pr.`status` = '1'";
        }
        
        if ($filters['picture'])
        {
            $aq[] = "p.`picture_id` = pr.`picture_id`";
            $at[] = 'pictures AS p';
        }
        
        $q = "SELECT * 
            FROM `" . self::$dbtable . "` pr" . ($at ? ', ' . implode(', ', $at) : '') . "
            WHERE 1 " . ($aq ? ' AND ' . implode(' AND ', $aq) : '');
        
        if ($filters['orderBy']) {
            // ёбаный стыд))) 
            $q .= " ORDER BY " . addslashes($filters['orderBy']) . ' ' . (in_array($filters['orderDir'], ['ASC', 'DESC']) ? $filters['orderDir'] : 'DESC');
        }
        
        if ($filters['limit']) {
            $q .= " LIMIT " . ($filters['offset'] ? intval($filters['offset']) : 0) . ", " . intval($filters['limit']);
        }
          
        //printr($q, 1);
        
        $sth = App::db()->prepare($q);
        
        $sth->execute();
        $rows = $sth->fetchAll();
        
        foreach ($rows AS $k => $p) {
            $rows[$k]['total_price'] = round($p['product_price'] - $p['product_price'] / 100 * $p['product_discount']);
        }
        
        return $rows;
    }
    
    public function getBySlugPlus($slug)
    {
        $sth = App::db()->prepare("SELECT `id` FROM `" . self::$dbtable . "` WHERE CONCAT_WS('-', `slug`, `product_sku`) = ? LIMIT 1");
        $sth->execute([$slug]);
        $foo = $sth->fetch();
        return $foo['id'];
    }
}