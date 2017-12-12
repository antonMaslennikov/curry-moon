<?php
namespace admin\application\models;

use smashEngine\core\models\FormModel;
use smashEngine\core\validators\RangeValidator;

class UserAccessFormModel extends FormModel {

	protected $user_list = null;

	protected $old_id;

	public $newRecord = true;

	public $id;

	public $login = '';

	public $meta_value = 'manager';


	public function setUpdate() {

		$this->newRecord = false;

		$this->old_id = $this->id;

		$user = new user($this->id);

		$this->login = sprintf('%s (%s)', $user->user_name, $user->user_email);
	}


	public function getData() {

		return $this->getAttributes();
	}


	public function rules() {

		return [
			['id', 'checkID'],

			['login', 'unsafe'],

			['meta_value', 'in', 'range'=>array_keys($this->getTeamList())],
		];
	}


	public function attributeLabels() {

		return [
			'id'=>'Пользователь',
			'meta_value'=>'Роль сотрудника',
		];
	}


	public function checkId($attribute, $params) {

		if ($this->newRecord) {

			$list = $this->getUserList();

			if (!isset($list))
		}
	}


	protected function getTeamList() {

		return [
			'fired'=>'Уволенный',
			'manager'=>'Сотрудник',
			'admin'=>'Администратор',
		];
	}

	protected function getUserList() {

		if ($this->newRecord) {

			if ($this->user_list === null) {

				$this->user_list = (new user())->getListEmployees();
			}

			return $this->user_list;
		}

		return [];
	}
}