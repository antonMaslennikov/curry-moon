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

	protected $query_template = 'SELECT {select} FROM `product` {where} ORDER BY status DESC, product_name ASC';

	protected $pagination = 0;

	protected $bind_array = null;

	protected $search = null;

	public function getList() {

		$sql = $this->createQuery(isset($_GET['search'])?$_GET['search']:null);

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


	protected function createQuery($search = null) {

		$query = $this->query_template;
		$where = [];
		$array = [];

		if (is_array($search)) {

			foreach ($search as $param => $value) {

				if (!$value) continue;

				if (isset($this->modified_data[$param])) {

					$where[] = ($this->modified_data[$param]===false)
						?sprintf('`%s` LIKE :%s', $param, $param)
						:sprintf('`%s` = :%s', $param, $param);

					$array[':'.$param] = $this->modified_data[$param]===false
						?'%'.$value.'%'
						:call_user_func($this->modified_data[$param], $value);


					$this->search[$param]=$value;
				}
			}
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