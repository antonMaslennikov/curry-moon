<?php

namespace application\models;
use admin\application\models\TagsTrait;
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

	const TYPE_TAGS = 'product';

	/**
	 * @var имя таблицы в БД для хранения экземпляров класса
	 */
	public static $dbtable = 'product';
    
    public static $dbtable_pictures = 'product__pictures';

    public static $dbtable_related = 'product__related';

	use TagsTrait;

    public static $manufacturers = [
        0 => ['name' => 'Мы'],
        1 => ['name' => 'inStyle', 'email' => ''],
        2 => ['name' => 'ZARA', 'email' => 'instyle@gavick.com'],
    ];


	public function addPictures($pictures) {

		foreach ($pictures as $pic_id) {

			$this->addPicture($pic_id);
		}
	}
    
    public function __construct($id = 0)
    {
        parent::__construct($id);
        
        $this->total_price = round($this->product_price - $this->product_price / 100 * $this->product_discount);
    }
    
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
        $sth = App::db()->prepare("SELECT p.*, p1.`picture_path` AS orig_path, p2.`picture_path` AS thumb_path 
                FROM 
                    `" . self::$dbtable_pictures . "` p, 
                    `" . picture::$dbtable . "` p1, 
                    `" . picture::$dbtable . "` p2 
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


	public function getRelated() {

		$sth = App::db()->prepare("SELECT related_id as id
                FROM
                    `" . self::$dbtable_related . "`
                WHERE
                    `product_id` = ?
            ");

		$sth->execute([$this->id]);
		$temp = $sth->fetchAll();
		$products = [];

		foreach ($temp as $v) {$products[] = $v['id'];}

		$this->related = $products;

		return $this->info['related'];
	}


	public function related_search() {

		if (isset($_GET['search']) && $_GET['search']) {

			$bind = [
				':id'=>$this->id,
				':p_name'=>'%'.trim($_GET['search']).'%'
			];

			$where = "AND t.product_name LIKE :p_name";
		} else {

			$bind = [
				':id'=>$this->id,
			];

			$where = "";
		}

		$sql = "SELECT t.id, t.product_name, t.quantity, t.product_price, t.product_discount, p.picture_path as src, r.related_id as related
                FROM `" . self::$dbtable . "` AS t
                LEFT JOIN `" . self::$dbtable_related ."` AS r ON (t.id = r.related_id AND r.product_id = :id)
                LEFT JOIN `pictures` AS p ON (t.picture_id = p.picture_id)
                WHERE t.`id` != :id ".$where." AND r.related_id IS NULL
                ORDER BY t.product_name ASC
                LIMIT 100
        ";//OR r.related_id IS NOT NULL

		$sth = App::db()->prepare($sql);

		$sth->execute($bind);

		return $sth->fetchAll();
	}


	public function setRelated($related_id) {

		$sth = App::db()->prepare("INSERT IGNORE INTO `".self::$dbtable_related."` (product_id, related_id) VALUES (:product, :related)");

		$sth->execute([':product'=>(int) $this->id, ':related'=>(int) $related_id]);
	}


	public function removeRelated($related_id) {

		$sth = App::db()->prepare("DELETE FROM `".self::$dbtable_related."` WHERE product_id = :product AND related_id = :related LIMIT 1");

		$sth->execute([':product'=>(int) $this->id, ':related'=>(int) $related_id]);
	}


	public function listProductRelated($catalog = false) {

		$sth = App::db()->prepare("SELECT t.id, t.product_name, t.quantity, t.product_price, t.product_discount, t.`status`, p.picture_path as src, r.related_id as related
                FROM `" . self::$dbtable . "` AS t
                INNER JOIN  `" . self::$dbtable_related ."` AS r ON (t.id = r.related_id AND r.product_id = :id )
                LEFT JOIN `pictures` AS p ON (t.picture_id = p.picture_id)
                WHERE t.`id` != :id " . ($catalog ? "AND t.`status` = '1' AND t.`quantity` > '0'" : '') . "
                ORDER BY t.product_name ASC
            ");

		$sth->execute([':id'=> $this->id]);

		return $sth->fetchAll();
	}


	public function delete() {

		$this->deletePictures();

		parent::delete();

		self::deleteTags($this->id);

		return true;
	}


	public function mainPicture($thumb_id) {

		$stmt = App::db()->prepare("UPDATE `" . self::$dbtable . "` SET
            `picture_id` = :picture WHERE id = :id LIMIT 1");

		return $stmt->execute([':picture'=>(int) $thumb_id, ':id'=> (int) $this->id]);
	}


	public function deletePictures() {

		$stmt = App::db()->prepare("SELECT thumb_id FROM `" . self::$dbtable_pictures . "` WHERE product_id = :id");

		$stmt->execute([':id'=>(int) $this->id]);
		$pictures = $stmt->fetchAll();

		foreach ($pictures as $pict) {

			$this->deletePicture($pict['thumb_id']);
		}
	}


	public function deletePicture($thumb_id) {

		$stmt = App::db()->prepare("SELECT orig_id FROM `" . self::$dbtable_pictures . "` WHERE product_id = :id AND thumb_id = :img LIMIT 1");

		$stmt->execute([':id'=>(int) $this->id, ':img'=> (int) $thumb_id]);
		$original = $stmt->fetch();

		$stmt = App::db()->prepare("DELETE FROM `" . self::$dbtable_pictures . "` WHERE product_id = :id AND thumb_id = :img LIMIT 1");

		$stmt->execute([':id'=>(int) $this->id, ':img'=> (int) $thumb_id]);

		deletepicture($original['orig_id']);

		deletepicture($thumb_id);

		if ($this->picture_id == $thumb_id) {

			$stmt = App::db()->prepare("UPDATE `" . self::$dbtable . "` SET picture_id = 0  WHERE id = :id LIMIT 1");

			$stmt->execute([':id'=>(int) $this->id]);
		}

		return true;
	}


	public function createThumb($pic_id) {

		$orig_url = File::getAbsolutePathForUrl(pictureId2path($pic_id));

		$thumb_url = (new Thumbnailer())->thumbnail($orig_url, File::uploadedPath(), 250, 325);

		return file2db($thumb_url);
	}
    
    /**
     * Получить список товаров
     * @param  mixed $filters массив с параметрами поиска
     * @param  string $trans_id номер транзакции, чтобы запросы разных пользвателей не пересекались                                            
     * @return array массив с товарами
     */
    public function getAll($filters = null, $trans_id = null) {
        
        if ($filters['categoryfull'])
        {
            $cats = [$filters['categoryfull']];
            foreach ((new category)->getAllChildren($filters['categoryfull']) AS $c) {
                $cats[] = $c['id'];
            }
            
            $aq[] = "pr.`category` IN ('" . implode("', '", $cats) . "')";
        }
        
        if ($filters['avalible'])
        {
            $aq[] = "pr.`quantity` +- pr.`quantity_reserved` > 0";
        }
        
        if ($filters['category'])
        {
            $aq[] = "pr.`category` = '" . intval($filters['category']) . "'";
        }
        
        if ($filters['status'] == 'active')
        {
            $aq[] = "pr.`status` = '1'";
        }

	    $at = [];
        if ($filters['picture'])
        {
            $aq[] = "p.`picture_id` = pr.`picture_id`";
            $at[] = 'pictures AS p';
        }
        
        $q = "SELECT SQL_CALC_FOUND_ROWS * 
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
        
        $foo = App::db()->query("SELECT FOUND_ROWS() AS s")->fetch();
        $_SESSION['pages_total_' . $trans_id] = $foo['s'];
        
        $rows = $sth->fetchAll();
        
        foreach ($rows AS $k => $p) {
            $rows[$k]['isNew'] = $p['added_date'] != '0000-00-00 00:00:00' && getDateDiff($p['added_date']) < 14 * 24 * 3600 ? true : false;
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
    
    public function getCategorysChain()
    {
        $cat = new \admin\application\models\category;
        
        $chain = $cat->getChain($cat->getNode($this->category));
        array_shift($chain);
        return $chain;
    }
}