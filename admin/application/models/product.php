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

	protected $query_template = 'SELECT {select} FROM `product` AS pr {at} {where} ORDER BY pr.status DESC, pr.product_name ASC';

	protected $pagination = 0;

	protected $bind_array = null;

	protected $search = null;

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
		}

		if ($filter['category'])
		{
			$where[] = "pr.`category` = '" . intval($filters['category']) . "'";
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

		return $query;
	}
}