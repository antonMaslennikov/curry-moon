<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 13.12.2017
 * Time: 21:33
 */

namespace admin\application\models;


use smashEngine\core\App;

class WebUser extends \application\models\user {

	const ROLE_PARAM = 'team';

	protected static $allowList = [
		'manager'=>true,
		'admin'=>true,
	];

	public static function load()
	{
		$user = parent::load();

		if ($user->id) {

			$smtm = App::db()->prepare("SELECT `param_value` FROM `" . self::$dbtable_meta . "` WHERE `user_id` = :id AND `param_name`=:param LIMIT 1");

			$smtm->execute([':id'=>$user->id, ':param'=>self::ROLE_PARAM]);

			if ($smtm->rowCount() == 1) {

				$temp = $smtm->fetch();

				if (isset(self::$allowList[$temp['param_value']])) {

					$user->info['role'] = $temp['param_value'];
				}
			}
		}

		return $user;
	}

}