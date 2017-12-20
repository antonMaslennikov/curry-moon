<?php
namespace admin\application\controllers;

use smashEngine\core\App;
use \application\models\product;

class Controller_db_migrate extends Controller_ {

	public function action_index() {
        
        exit('atop');
        
        //$sth = App::db()->query("select * from `product__pictures` WHERE 1 and `thumb_id` = 0 limit 100");
        
        //$sthD1 = App::db()->prepare("delete from `product__pictures` WHERE `id` = ? LIMIT 1");
        //$sthD2 = App::db()->prepare("DELETE FROM pictures WHERE `picture_id` = ? LIMIT 1");
        
        //$sthU1 = App::db()->prepare("UPDATE `product__pictures` SET `thumb_id` = ? WHERE `id` = ? LIMIT 1");
        $sthU2 = App::db()->prepare("UPDATE `product` SET `picture_id` = ? WHERE `id` = ? LIMIT 1");
        
        $sth = App::db()->query("SELECT p.* FROM  `product` p, `product__pictures` pp 
                                 WHERE p.`picture_id` = '0'  and p.`id` = pp.`product_id`
                                 group by p.`id`");
        
        foreach ($sth->fetchAll() AS $row)
        {
            printr($row);
            
            /*
            if ($row['thumb_id'] > 0) {
                $thumb_path = pictureId2path($row['thumb_id']);
                if (file_exists('../' . $thumb_path)) {
                    printr('skip');
                    continue;
                }
            }
            */
            
            $p = new product($row['id']);

            foreach ($p->pictures AS $pic)
            {
                if (file_exists('../' . $pic['orig_path'])) {
                    
                    $tid = $p->createThumb($pic['orig_id']);
                    $sthU2->execute([$tid, $row['id']]);
                    
                    break;
                }
            }

            /*
            $tid = $p->createThumb($row['orig_id']);
            
            $sthU1->execute([$tid, $row['id']]);
            
            if (empty($p->picture_id))
                $sthU2->execute([$tid, $row['product_id']]);
            
            $sthD2->execute([$row['thumb_id']]);
            */
            /*
            if (!file_exists('../' . $orig_path)) {
                $sthD1->execute([$row['id']]);
                $sthD2->execute([$row['orig_id']]);
                $sthD2->execute([$row['thumb_id']]);
                printr('orig not exist');
                continue;
            }
            */
        }
        
        /*
        require '../product_medias.php';
        
        printr($product_medias);
        
        $sth0 = App::db()->prepare("select `id` from `product` WHERE `id` = ? LIMIT 1");
        $sth = App::db()->prepare("INSERT INTO `product__pictures` SET `id` = ?, `product_id` = ?, `orig_id` = ?, `thumb_id` = ?");
        
        foreach ($product_medias AS $pm) 
        {
            $sth0->execute([$pm['product_id']]);
            
            if (!$sth0->fetch()) {
                continue;
            }
            
            if (!empty($pm['orig_id'])) {
                
                $oid = file2db($pm['orig_id']);
                
                if (!empty($pm['thumb_id']))
                    $tid = file2db($pm['thumb_id']);
                else
                    $tid = 0;
                
                $sth->execute([$pm['id'], $pm['product_id'], $oid, $tid]);
                
            }
        }
        */
        exit('hello');
    }
}