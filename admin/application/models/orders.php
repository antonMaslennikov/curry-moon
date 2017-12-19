<?php

namespace admin\application\models;

use smashEngine\core\App;
use application\models\basketItem;
use application\models\user;
use admin\application\helpers\Pagination;

/**
 * Class product
 * @package admin\application\models
 *
 * @property int $id ID
 */
class orders extends \application\models\basket {
    
    protected $pagination = 0;
    
    public function getAll($filters)
    {
        if ($filters['user']) {
            $aq[] = "b.`user_id` = '" . intval($filters['user']) . "'";
        }

        if ($filters['status']) {
            $fs = [];
            foreach ((array) $filters['status'] AS $s) {
                if (self::$orderStatus[$s]) {
                    $fs[] = $s;
                }
            }
            $aq[] = "b.`user_basket_status` IN ('" . implode("', '", $fs) . "')";
        }

        if ($filters['statusNot'] && self::$orderStatus[$filters['statusNot']]) {
            $aq[] = "b.`user_basket_status` != '" . $filters['statusNot'] . "'";
        }
        
        if ($filters['payment']) {
            $fs = [];
            foreach ((array) $filters['payment'] AS $s) {
                if (self::$paymentTypes[$s]) {
                    $fs[] = $s;
                }
            }
            $aq[] = "b.`user_basket_payment_type` IN ('" . implode("', '", $fs) . "')";
        }
        
        if ($filters['delivery']) {
            $fs = [];
            foreach ((array) $filters['delivery'] AS $s) {
                if (self::$deliveryTypes[$s]) {
                    $fs[] = $s;
                }
            }
            $aq[] = "b.`user_basket_delivery_type` IN ('" . implode("', '", $fs) . "')";
        }
        
        if ($filters['date']['start']) {
            $filters['date']['start'] = date('Y-m-d 00:00:00', strtotime($filters['date']['start']));
            $aq[] = "b.`user_basket_date` >= '" . $filters['date']['start'] . "'";
        }
        
        if ($filters['date']['end']) {
            $filters['date']['end'] = date('Y-m-d 23:59:59', strtotime($filters['date']['end']));
            $aq[] = "b.`user_basket_date` <= '" . $filters['date']['end'] . "'";
        }
        
        
        
        $pagination_query = $q = "SELECT {select}
            FROM 
                `" . self::$dbtable . "` b 
                    {ljt}
                " . ($at ? ', ' . implode(', ', $at) : '') . "
            WHERE 1 " 
                . 
                ($aq ? ' AND ' . implode(' AND ', $aq) : '');
        
        
        $pagination_query = str_replace('{ljt}', '', $pagination_query);
        $pagination_query = str_replace('{select}', 'count(*)', $pagination_query);
        
        //printr($pagination_query);
        
        $smt = App::db()->prepare($pagination_query);
		$smt->execute();
		$total = $smt->fetch();
        
		$this->pagination = new Pagination(array_shift($total), isset($_GET['pageSize'])?$_GET['pageSize']:0);
        
        
        $ljt[] = "LEFT JOIN `" . basketItem::$dbtable . "` bi ON b.`id` = bi.`user_basket_id`";
        $ljt[] = "LEFT JOIN `" . user::$dbtable . "` u ON b.`user_id` = u.`id`";
            
        $q = str_replace('{ljt}', implode(' ', $ljt), $q);
        $q = str_replace('{select}', 'b.*, SUM(bi.`user_basket_good_total_price`) AS sum, SUM(bi.`user_basket_good_quantity`) AS countGoods, u.`user_email`', $q);
        
        $q .= "GROUP BY b.`id`";
        
        if ($filters['orderBy']) {
            // ёбаный стыд))) 
            $q .= " ORDER BY " . addslashes($filters['orderBy']) . ' ' . (in_array($filters['orderDir'], ['ASC', 'DESC']) ? $filters['orderDir'] : 'DESC');
        }

        $sth = App::db()->prepare($q . $this->pagination->applyLimit());
        $sth->execute();
        
        $rows = $sth->fetchAll();

        foreach ($rows AS $k => $p) {
            $rows[$k]['status'] = self::$orderStatus[$p['user_basket_status']];
            $rows[$k]['user_basket_delivery_type_rus'] = self::$deliveryTypes[$p['user_basket_delivery_type']]['title'];
            $rows[$k]['user_basket_payment_type_rus'] = self::$paymentTypes[$p['user_basket_payment_type']]['title'];
        }

        return [
			'page'=>$this->pagination->getTemplate(),
			'data'=>$rows,
		];
    }
    
    function getflatlog()
    {
        $r = App::db()->query("SELECT * FROM `" . self::$dbtable_log . "` WHERE `basket_id` = '" . $this->id . "' " . ((!empty($action)) ? "AND `action` = '" . addslashes($action) . "'" : '') . " ORDER BY `id` DESC");

        $this->flatlog = array();

        foreach ($r->fetchAll() AS $l)
        {

            $this->flatlog[] = $l;
        }

        return $this->flatlog;
    }
}