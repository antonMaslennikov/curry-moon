<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 08.12.2017
 * Time: 22:19
 */

namespace admin\application\models;

use admin\application\helpers\Pagination;
use PDO;
use smashEngine\core\App;
use smashEngine\core\Model;

class user extends Model {

	protected static $dbtable = 'users';

	protected $pagination = 0;

	protected $query_template = 'SELECT {select} FROM {table} {where}';

	protected $bind_array = null;

	protected $search = null;

	protected $modified_data = [
		'user_login'=> false,
		'user_password'=> false,
		'user_sex'=> false,
		'user_name'=> false,
		'user_show_name'=> false,
		'user_email'=> false,
		'user_show_email'=> false,
		'user_phone'=> false,
		'user_birthday'=> false,
		'user_register_date'=> false,
		'user_ip'=> 'ip2long',
		'user_url'=> false,
		'user_picture'=> intaval,
		'user_description'=> false,
		'user_status'=> false,
		'user_last_login'=> false,
		'user_activation'=> false,
		'user_is_fake'=> false,
		'user_subscription_status'=> false,
		'user_address'=> false,
		'user_zip'=> false,
		'user_country_id'=> 'intval',
		'user_city_id'=> 'intval',
	];

	public function getList() {

		$sql = $this->createQuery(isset($_GET['search'])?$_GET['search']:null);

		$smt = App::db()->prepare(str_replace('{select}', 'count(*) as c', $sql));
		$smt->execute($this->bind_array);

		$total = $smt->fetch();
		$total = $total['c'];

		$this->pagination = new Pagination($total, isset($_GET['pageSize']));

		$sql .= $this->pagination->applyLimit();

		$sql = str_replace('{select}', '*', $sql);

		$stmt = App::db()->prepare($sql);

		$stmt->execute();
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

		$query = str_replace('{table}', self::db(), $this->query_template);

		if (is_array($search)) {

			$where = [];
			$array = [];
			foreach ($where as $param => $value) {

				if (isset($this->modified_data[$param])) {

					$where[] = $this->modified_data[$param]===false
									?sprintf('`%s` LIKE :%s', $param)
									:sprintf('`%s` = :%s', $param);

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