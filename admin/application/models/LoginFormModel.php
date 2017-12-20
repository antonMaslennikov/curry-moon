<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 13.12.2017
 * Time: 23:31
 */

namespace admin\application\models;

use smashEngine\core\helpers\Password;
use smashEngine\core\models\FormModel;

class LoginFormModel extends FormModel{

	public $email;

	public $password;

	public $rememberMe;

	/**
	 * @var WebUser
	 */
	protected static $user;

	public function rules() {

		return [
			[['email', 'password'], 'required'],

			['email', 'email'],
			['email', 'findEmail'],

			['rememberMe', 'safe'],

			['password', 'checkPassword'],
		];
	}


	public function findEmail($attribute, $params) {

		self::$user = WebUser::findByEmail($this->email);

		if (self::$user === null) {

			$this->addError('email', 'Указан неправильный логин или пароль');
		}

		if (!self::$user->role) {

			$this->addError('email', 'У Вас нет прав для входа в систему!');
		}
	}


	public function checkPassword($attribute, $params) {

		if (!Password::verify($this->password, self::$user->user_password)) {

			$this->addError('email', 'Указан неправильный логин или пароль');
		}
	}


	public function getData() {

		$attributes = $this->getAttributes();

		$attributes['id'] = self::$user->id;

		return $attributes;
	}

}