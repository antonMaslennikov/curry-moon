<?php
namespace admin\application\models;

use smashEngine\core\App;
use smashEngine\core\Model;

class userEmployees extends Model{

	const PARAM_NAME = 'team';

	protected static $dbtable = 'users__meta';

	protected $modified_data = [
		'meta_value'=>false,
	];

	function __construct($id = null)
	{
		$this->getDbTableName();

		if ($id) {$this->id = (int) $id;}

		if (!empty($this->id))
		{
			$r = App::db()->prepare("SELECT meta_value FROM `" . self::db() . "` WHERE `user_id` = :user_id AND `meta_name` = :meta_name LIMIT 1");

			$r->execute([':user_id'=>$this->id, ':meta_name'=>self::PARAM_NAME]);

			if ($r->rowCount() == 1)
			{
				$this->info = $r->fetch();
			}
			else
				throw new appException(__CLASS__ . ' ' . $this->id . ' не найден');
		}
	}


	public function update() {

		if (count($this->modified_data)) {

			$update_query = [];
			$update_array =[
				':user_id'=>(int) $this->id,
				':meta_param'=>self::PARAM_NAME,
			];

			foreach ($this->modified_data as $key => $function_cast) {

				if ($this->info[$key] != $this->_initial_data[$key]) {

					$update_query[] = sprintf("`%s` = :%s", $key, $key);
					$update_array[':'.$key] = $function_cast?call_user_func($function_cast, $this->info[$key]):$this->info[$key];
				}
			}

			if (count($update_query)) {

				$sql = 'update ' . self::db() . ' set ' . implode(', ', $update_query) . ' where user_id = :id AND meta_param = :meta_param limit 1;';

				$stmt = App::db()->prepare($sql);

				return $stmt->execute($update_array);
			}

			return true;
		}

		return false;
	}


	public function insert() {

		$sql = 'insert ignore into ' . self::db() . ' (user_id, meta_param, meta_value) VALUES (:user_id, :meta_param, :meta_value) limit 1;';

		$stmt = App::db()->prepare($sql);

		return $stmt->execute([
			':user_id'=>$this->id,
			':meta_param'=>self::PARAM_NAME,
			':meta_value'=>$this->meta_value,
		]);
	}

	public function save() {}
}