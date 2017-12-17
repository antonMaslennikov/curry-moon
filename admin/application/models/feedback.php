<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 15.12.2017
 * Time: 22:33
 */

namespace admin\application\models;


use admin\application\helpers\Pagination;
use smashEngine\core\App;

/**
 * Class feedback
 * @package admin\application\models
 *
 * @property string feedback_status [new, old] + пропустить (кидает в old без ответа)
 * @property string feedback_date дата создания +
 * @property string feedback_reply_date дата ответа
 * @property string feedback_name от кого +
 * @property string feedback_email +

 * @property string feedback_topic тема...
 * @property string feedback_text вопрос + (100 символов ...)
 * @property string feedback_reply ответ
 * @property int feedback_user id авторизованного пользователя
 * @property int feedback_replay_user кто ответил

 * $property string feedback_webclient
 */
class feedback extends \application\models\feedback {

	protected $pagination = 0;

	public function listData($is_old = false) {

		$query = "
			SELECT {select} FROM `".self::$dbtable."`
                WHERE feedback_status = :status
                ORDER BY feedback_date DESC
		";

		$smt = App::db()->prepare(str_replace('{select}', 'count(*)', $query));
		$smt->execute([':status'=>$is_old?self::STATUS_SEND:self::STATUS_NEW]);

		$total = $smt->fetch();

		$this->pagination = new Pagination(array_shift($total), isset($_GET['pageSize'])?$_GET['pageSize']:0);

		$sql = str_replace('{select}', '*', $query);

		$stmt = App::db()->prepare($sql . $this->pagination->applyLimit());

		$stmt->execute([':status'=>$is_old?self::STATUS_SEND:self::STATUS_NEW]);

		return [
			'page'=>$this->pagination->getTemplate(),
			'data'=>$stmt->fetchAll(),
		];
	}

	public function isNew() {

		if (!$this->id) return false;

		return $this->feedback_status === self::STATUS_NEW;
	}

	public function setSpam($user_id) {

		$smt = App::db()->prepare("
			UPDATE `".self::$dbtable."` SET
				feedback_status = :status,
				feedback_reply_date = NOW(),
				feedback_replay_user = :user,
				feedback_error = :error
			WHERE id = :id
			LIMIT 1
		");

		$smt->execute([
			':status'=>self::STATUS_SEND,
			':user'=>(int) $user_id,
			':error'=>'spam',
			':id'=>$this->id,
		]);
	}


	public function send($user_id) {

		$smt = App::db()->prepare("
			UPDATE `".self::$dbtable."` SET
				feedback_status = :status,
				feedback_reply_date = NOW(),
				feedback_replay_user = :user,
				feedback_reply = :text
			WHERE id = :id
			LIMIT 1
		");

		return $smt->execute([
			':status'=>self::STATUS_SEND,
			':user'=>(int) $user_id,
			':text'=>$this->feedback_reply,
			':id'=>$this->id,
		]);
	}
}