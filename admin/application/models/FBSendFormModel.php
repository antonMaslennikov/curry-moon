<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 17.12.2017
 * Time: 0:15
 */

namespace admin\application\models;

use smashEngine\core\models\FormModel;

/**
 * Class FBSendFormModel
 * @package admin\application\models
 * $property type feedback_reply ответ
 */
class FBSendFormModel extends FormModel {

	public $feedback_reply;

	public function rules() {

		return [
			['feedback_reply', 'required'],
		];
	}


	public function attributeLabels() {

		return [
			'feedback_reply' => 'Ответ'
		];
	}
}