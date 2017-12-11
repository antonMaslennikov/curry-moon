<?php
namespace admin\application\models;

use smashEngine\core\App;
use smashEngine\core\models\FormModel;

/**
 * Class UserFormModel
 * @package admin\application\models
 * 
 */
class UserFormModel extends FormModel {

	public $newRecord = true;

	protected $old_email;

	public $user_password;
	public $user_name;
	public $user_email;
	public $user_phone;
	public $user_birthday; //календарик
	public $user_description; //input

	public $user_status = 'active'; // active , banned,  ///deleted - Удаленный
    public $user_activation = 'waiting'; // done - активация выполнена, waiting - ожидает выполнения активации, failed - активация провалена
    public $user_is_fake = 'false';  // true - Рагистрация через заказ false - Обычная регистрация
	public $user_subscription_status = 'canceled' ;//  'active', 'canceled'

	public $user_address; // input
	public $user_zip; // почтовый индекс
    public $user_country_id; //'=> 'intval', Страны
	public $user_city_id;   //=> 'intval', Городов


	public function setUpdate() {

		$this->newRecord = false;

		$this->old_email = $this->user_email;

		if ($this->user_birthday && ($this->user_birthday != '0000-00-00'))
			$this->user_birthday = \DateTime::createFromFormat('Y-m-d', $this->user_birthday)->format('d.m.Y');
		else
			$this->user_birthday = '';
	}


	public function getData() {

		$attributes = $this->getAttributes();

		return $attributes;
	}


	public function rules() {

		return [
			[['user_name', 'user_email'], 'required' ],

			['user_email', 'email'],
			['user_email', 'uniqueEmail'],

			[['user_phone', 'user_description', 'user_zip', 'user_address'], 'safe'],

			['user_status', 'in', 'range'=>array_keys($this->getStatusList())],

			['user_activation', 'in', 'range'=>array_keys($this->getActivationList())],

			['user_is_fake', 'in', 'range'=>array_keys($this->getIsFakeList()) ],

			['user_subscription_status', 'in', 'range'=>array_keys($this->getSubscriptionStatusList()) ],

			['user_birthday', 'dateFormat'],
		];
	}


	public function attributeLabels() {

		return [
			'user_password'=>'Пароль',
			'user_name'=>'ФИО',
			'user_email'=>'E-mail',
			'user_phone'=>'Телефон',
			'user_birthday'=>'Дата рождения',
		    'user_description'=>'Описание',
			'user_status'=>'Статус',
         	'user_activation'=>'Статус активации',
            'user_is_fake'=>'Тип регистрации',
			'user_subscription_status'=>'Подписка',
            'user_address'=>'Адрес',
			'user_zip'=>'Индекс',
            'user_country_id'=>'Город',
			'user_city_id'=>'Страна',
		];
	}

	protected function getStatusList() {

		return [
			'active'=>'Активный',
			'banned'=>'Заблокированный',
			'deleted'=>'Удаленный',
		];
	}

	protected function getActivationList() {

		return [
			'done' => 'Активация выполнена',
			'waiting' => 'Ожидает выполнения',
			'failed' => 'Активация провалена',
		];
	}

	protected function getIsFakeList() {

		return [
			'false' => 'Регистрация через заказ',
			'true' => 'Обычная регистрация',
		];
	}

	protected function getSubscriptionStatusList() {

		return [
			'canceled' => 'Не оформлена',
			'active' => 'Оформлена',
		];
	}

	protected function uniqueEmail($attribute, $params) {

		if (!$this->newRecord) {

			if ($this->user_email == $this->old_email) return;
		}

		$r = App::db()->prepare("SELECT id FROM `" . user::db() . "` WHERE `user_email` = ? LIMIT 1");

		$r->execute([$this->user_email]);

		if ($r->rowCount() == 1)
		{
			$this->addError('email', sprintf('Пользватель с таким логином уже существует!', $this->getAttributeLabel('slug')));
		}
	}


	public function dateFormat($attribute, $params) {

		if ($this->getErrorSummary()) return;

		if (!$this->user_birthday) return;

		$this->user_birthday = \DateTime::createFromFormat('d.m.Y', $this->user_birthday)->format('Y-m-d');
	}


	public function getDataForTemplate() {

		$data = parent::getDataForTemplate();

		$data['statusList']= $this->getStatusList();

		$data['activationList']= $this->getActivationList();

		$data['isFakeList']= $this->getIsFakeList();

		$data['subscriptionStatusList']= $this->getSubscriptionStatusList();

		return $data;
	}

}