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

			$user->role = self::LoadTeam($user->id);
		}

		return $user;
	}


	public static function findByEmail($search)
	{
		if (!empty($search))
		{
			$search = addslashes($search);

			$r = App::db()->prepare("SELECT `id` FROM `" . self::$dbtable . "` WHERE `user_email` LIKE ? LIMIT 1");

			$r->execute([$search]);

			if ($r->rowCount() == 1)
			{
				$foo = $r->fetch();
				$user = new self($foo['id']);

				$user->info['role'] = self::LoadTeam($user->id);

				$user->role = self::LoadTeam($user->id);

				return $user;
			}
		}
	}


	public static function LoadTeam($user_id) {

		$smtm = App::db()->prepare("SELECT `meta_value` FROM `" . self::$dbtable_meta . "` WHERE `user_id` = :id AND `meta_name`=:param LIMIT 1");

		$smtm->execute([':id'=>$user_id, ':param'=>self::ROLE_PARAM]);

		if ($smtm->rowCount() == 1) {

			$temp = $smtm->fetch();
			$role = array_shift($temp);

			if (isset(self::$allowList[$role])) {

				return $role;
			}
		}
	}
}