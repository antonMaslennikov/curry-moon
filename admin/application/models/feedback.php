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
 * $property type feedback_status [new, old] + пропустить (кидает в old без ответа)
 * $property type feedback_date дата создания +
 * $property type feedback_reply_date дата ответа
 * $property type feedback_name от кого +
 * $property type feedback_email +

 * $property type feedback_topic тема...
 * $property type feedback_text вопрос + (100 символов ...)
 * $property type feedback_reply ответ
 * $property type feedback_user id авторизованного пользователя
 * $property type feedback_replay_user кто ответил

 * $property type feedback_webclient
 *
 * пропустить ответить ---удалить---
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
}