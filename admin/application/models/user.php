<?php
namespace admin\application\models;

use admin\application\helpers\Pagination;
use PDO;
use smashEngine\core\App;
use smashEngine\core\Model;

/**
 * Class user
 * @package admin\application\models
 *
 * @property string $user_password
 * @property string $user_name
 * @property string $user_email
 * @property string $user_phone
 * @property string $user_birthday
 * @property string $user_description
 * @property string $user_status
 * @property string $user_activation
 * @property string $user_is_fake
 * @property string $user_subscription_status
 * @property string $user_address
 * @property string $user_zip
 * @property int $user_country_id
 * @property int $user_city_id
 */
class user extends Model {

	protected static $dbtable = 'users';

	protected $pagination = 0;

	protected $query_template = 'SELECT {select} FROM {table} {where} ORDER BY user_name ASC';

	protected $bind_array = null;

	protected $search = null;

	protected $modified_data = [
		'user_password'=> false,
		'user_name'=> false,
		'user_email'=> false,
		'user_phone'=> false,
		'user_birthday'=> false,
		'user_description'=> false,
		'user_status'=> false,
		'user_activation'=> false,
		'user_is_fake'=> false,
		'user_subscription_status'=> false,
		'user_address'=> false,
		'user_zip'=> false,
		'user_country_id'=> 'intval',
		'user_city_id'=> 'intval',
	];

	public function getEmployees() {

		$stmt = App::db()->prepare('
			SELECT t.*, m.meta_value FROM `'.self::db().'` AS t, `users__meta` AS m WHERE t.id = m.user_id AND m.meta_name = :meta ORDER BY t.id
		');

		$stmt->execute([':meta'=>'team']);
		$temp = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$data = [];
		foreach ($temp as $v) {

			$data[$v['id']] = $v;
		}

		return $data;
	}

	public function getInfo() {

		$data = [
			'info'=>$this->info,
		];

		$r = App::db()->prepare("SELECT country_name FROM `countries` WHERE country_id = :country LIMIT 1");

		$r->execute([':country'=>$this->user_country_id]);

		$temp = $r->fetch();

		$data['country'] = array_shift($temp);

		$r = App::db()->prepare("SELECT name FROM `city` WHERE id = :id LIMIT 1");

		$r->execute([':id'=>$this->user_city_id]);

		$temp = $r->fetch();

		$data['city'] = array_shift($temp);

		return $data;
	}

	public function getList() {

		$sql = $this->createQuery(isset($_GET['search'])?$_GET['search']:null);

		$smt = App::db()->prepare(str_replace('{select}', 'count(*) as c', $sql));
		$smt->execute($this->bind_array);

		$total = $smt->fetch();
		$total = $total['c'];

		$this->pagination = new Pagination($total, isset($_GET['pageSize'])?$_GET['pageSize']:0);

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


	public function getListEmployees() {

		$stmt = App::db()->prepare('
			SELECT t.id, t.user_name, t.user_email, m.meta_value
			FROM `'.self::db().'` AS t
			LEFT JOIN `users__meta` AS m ON (t.id = m.user_id AND m.meta_name = :meta) ORDER BY t.id
		');

		$stmt->execute([':meta'=>'team']);
		$temp = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$data = [];
		foreach ($temp as $v) {

			$data[$v['id']] = $v;
		}

		return $data;
	}


	protected function createQuery($search = null) {

		$query = str_replace('{table}', self::db(), $this->query_template);
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

	public function cityList($country, $is_json = false) {

		$r = App::db()->prepare("SELECT id, `name`  FROM `city` WHERE country = :country ORDER BY id");

		$r->execute([':country'=>$country]);

		$list = [];
		foreach ($r->fetchAll(PDO::FETCH_ASSOC) as $v) {

			if ($is_json) {

				$list[]=[
					'id'=>$v['id'],
					'text'=>$v['name']
				];

			} else {
				$list[$v['id']] = $v['name'];
			}
		}

		return $list;
	}

}