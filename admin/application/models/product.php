<?php

namespace admin\application\models;
use admin\application\helpers\Pagination;
use PDO;
use smashEngine\core\App;

/**
 * Class product
 * @package admin\application\models
 *
 * @property int $id ID
 */
class product extends \application\models\product {

	protected $modified_data = [
		'product_name'=>false,
	];

	public static $dbtable = 'product';

	protected $query_template = 'SELECT {select} FROM `product` AS pr {at} {where} ORDER BY {order_by}';

	protected $pagination = 0;

	protected $bind_array = null;

	protected $search = null;

    public function updateAllSorting() {

        $stmt = App::db()->prepare('
          SELECT pr.id, pr.category, pr.product_name, pr.status 
            FROM `product` AS pr
        ORDER BY pr.category ASC, pr.status DESC, pr.product_name ASC');

        $stmt->execute();
        $list = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = App::db()->prepare("UPDATE product SET sorting = :sorting WHERE id=:id LIMIT 1");
        $count = 0;
        $category = 0;

        foreach ($list as $v) {

            if ($v['category'] != $category) {

                $category = $v['category'];
                $order = 1;
            }

            $count = $count + $stmt->execute([':sorting'=>($category*1000)+$order, ':id'=>$v['id']]);
            $order++;
        }

        return $count;
    }

	public function getList($filter = null) {

		$sql = $this->createQuery(isset($_GET['search'])?$_GET['search']:null, $filter);

		$smt = App::db()->prepare(str_replace('{select}', 'count(*)', $sql));
		$smt->execute($this->bind_array);

		$total = $smt->fetch();
		$total = array_shift($total);

		$this->pagination = new Pagination($total, isset($_GET['pageSize'])?$_GET['pageSize']:50);

		$sql .= $this->pagination->applyLimit();

		$sql = str_replace('{select}', '*', $sql);

		$stmt = App::db()->prepare($sql);

		$stmt->execute($this->bind_array);
		$temp = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$data = [];
		foreach ($temp as $v) {

			$data[$v['id']] = $v;
		}

		return [
			'page'=>$this->pagination->getTemplate(),
			'search'=>$this->search,
			'data'=>$data
		];
	}


	protected function createQuery($search = null, $filter = null) {

		$query = $this->query_template;
		$where = [];
		$array = [];

		$order_by = 'pr.status DESC, pr.product_name ASC';

		if (is_array($search)) {

			foreach ($search as $param => $value) {

				if (!$value) continue;

				if (isset($this->modified_data[$param])) {

					$where[] = ($this->modified_data[$param]===false)
						?sprintf('pr.`%s` LIKE :%s', $param, $param)
						:sprintf('pr.`%s` = :%s', $param, $param);

					$array[':'.$param] = $this->modified_data[$param]===false
						?'%'.$value.'%'
						:call_user_func($this->modified_data[$param], $value);


					$this->search[$param]=$value;
				}
			}
		}


		if ($filter['categoryfull'])
		{
			$cats = [$filter['categoryfull']];
			foreach ((new category)->getAllChildren($filter['categoryfull']) AS $c) {
				$cats[] = $c['id'];
			}

			$where[] = "pr.`category` IN ('" . implode("', '", $cats) . "')";
            $order_by = 'pr.sorting ASC';
		}

		if ($filter['category'])
		{
			$where[] = "pr.`category` = '" . intval($filter['category']) . "'";
            $order_by = 'pr.sorting ASC';
		}

		if ($filter['status'] == 'active')
		{
			$where[] = "pr.`status` = '1'";
		}

		$at = [];
		if ($filter['picture'])
		{
			$where[] = "p.`picture_id` = pr.`picture_id`";
			$at[] = 'pictures AS p';
		}


		if (count($at)) {

			$query = str_replace('{at}', ', ' . implode(', ', $at), $query);
		} else {

			$query = str_replace('{at}', '', $query);
		}

		if (count($where)) {

			$query = str_replace('{where}', 'WHERE ' . implode(' AND ', $where), $query);
			$this->bind_array = $array;

		} else {

			$query = str_replace('{where}', '', $query);
		}

		$query = str_replace('{order_by}', $order_by, $query);

		return $query;
	}


	public function setNewSorting() {

        $max = $this->getMaxSorting($this->category);

        if (!empty($max)) $sort = (int) $max;
        else $sort = $this->category * 1000;

        return $sort + 1;
    }


    public function fixSorting($category) {

        $stmt = App::db()->prepare('
            SELECT id FROM '.self::$dbtable.' WHERE category = :category ORDER BY sorting ASC;
        ');

        $stmt->execute([':category'=>$category]);
        $temp = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sorting = 1;

        $stmt = App::db()->prepare('
            UPDATE '.self::$dbtable.' SET sorting = :sorting WHERE id = :id LIMIT 1;
        ');

        foreach ($temp as $v) {

            $stmt->execute([':sorting'=>(int) $category*1000 + $sorting, ':id'=>$v['id']]);
            $sorting++;
        }
    }


    protected function getMaxSorting($category) {

        $stmt = App::db()->prepare('
            SELECT MAX(sorting) FROM '.self::$dbtable.' WHERE category = :category;
        ');

        $stmt->execute([':category'=>$this->category]);
        $temp = $stmt->fetch();

        return array_shift($temp);
    }


    protected function findProductSorting($category, $sorting) {

        $stmt = App::db()->prepare('
            SELECT id FROM '.self::$dbtable.' WHERE category = :category AND sorting = :sorting LIMIT 1;
        ');

        $stmt->execute([':category'=>$category, ':sorting' => $sorting]);
        $temp = $stmt->fetch();

        return array_shift($temp);
    }


    protected function updateSorting($id, $sorting) {

        $stmt = App::db()->prepare('
            UPDATE '.self::$dbtable.' SET sorting = :sorting WHERE id = :id LIMIT 1;
        ');

        $stmt->execute([':id'=>(int) $id, ':sorting' => (int) $sorting]);
    }


    public function setDownSorting() {

        //Проверка на превышение max
        if ( ($this->sorting +1) <= $this->getMaxSorting($this->category)) {

            $next_id = $this->findProductSorting($this->category, ($this->sorting +1));
            $this->updateSorting($this->id, ($this->sorting +1));
            $this->updateSorting($next_id, $this->sorting);

        }
    }

    public function setUpSorting() {

        //Проверка на превышение max
        if ( ($this->sorting - 1)%1000 > 0) {

            $next_id = $this->findProductSorting($this->category, ($this->sorting - 1));
            $this->updateSorting($this->id, ($this->sorting - 1));
            $this->updateSorting($next_id, $this->sorting);

        }
    }

    public function setValueSorting($value) {

        $thisValue = $this->sorting%1000;

        $max = $this->getMaxSorting($this->category);

        if ($value <= 0) $value = 1;
        if ($value > $max) $value = $max;

        if ($value == $thisValue) return;

        if ($value > $thisValue) {

            $stmt = App::$db->prepare('
                UPDATE '.self::$dbtable.'
                   SET sorting = sorting-1
                 WHERE sorting <= :value
                   AND sorting > :this_value  
            ');

        } else {

            $stmt = App::$db->prepare('
                UPDATE '.self::$dbtable.'
                   SET sorting = sorting+1
                 WHERE sorting >= :value
                   AND sorting < :this_value  
            ');
        }

        $stmt->execute([
            ':value'=>(int) ($this->category * 1000 + $value),
            ':this_value'=>(int) ($this->category * 1000 + $thisValue)
        ]);

        $this->updateSorting($this->id, ($this->category * 1000 + $value));
    }
}