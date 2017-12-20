<?php
namespace admin\application\models;

use PDO;
use smashEngine\core\App;
use smashEngine\core\helpers\Password;
use smashEngine\core\models\FormModel;

/**
 * Class UserFormModel
 * @package admin\application\models
 * 
 */
class UserFormModel extends FormModel {

	private $_countryList = null;

	public $newRecord = true;

	protected $old_email;

	public $user_password = '';
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

		$this->user_password = '';

		if ($this->user_birthday && ($this->user_birthday != '0000-00-00'))
			$this->user_birthday = \DateTime::createFromFormat('Y-m-d', $this->user_birthday)->format('d.m.Y');
		else
			$this->user_birthday = '';
	}


	public function getData() {

		$attributes = $this->getAttributes();

		if ($attributes['user_password']) $attributes['user_password'] = Password::hash($attributes['user_password']);

		if (is_string($attributes['user_city_id']) && $attributes['user_city_id']!=intval($attributes['user_city_id']))
			$attributes['user_city_id'] = cityName2id($attributes['user_city_id'], $attributes['user_country_id'], 1);

		return $attributes;
	}


	public function rules() {

		return [
			[['user_name', 'user_email'], 'required' ],

			['user_email', 'email'],
			['user_email', 'uniqueEmail'],

			[['user_phone', 'user_description', 'user_zip', 'user_address', 'user_password'], 'safe'],

			['user_status', 'in', 'range'=>array_keys($this->getStatusList())],

			['user_activation', 'in', 'range'=>array_keys($this->getActivationList())],

			['user_is_fake', 'in', 'range'=>array_keys($this->getIsFakeList()) ],

			['user_subscription_status', 'in', 'range'=>array_keys($this->getSubscriptionStatusList()) ],

			['user_country_id', 'in', 'range'=>array_keys($this->getCountryList())],

			['user_city_id', 'checkUserCityID'],

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
            'user_country_id'=>'Страна',
			'user_city_id'=>'Город',
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

	protected function getCountryList() {

		if ($this->_countryList === null) {

			$this->_countryList = $this->setCountryList();
		}

		return $this->_countryList;
	}

	protected function getCityList() {

		$list = $this->user_country_id?(new user())->cityList($this->user_country_id):[];

		return $list;
	}


	protected function setCountryList() {

		$r = App::db()->prepare("SELECT country_id, country_name  FROM `countries` ORDER BY country_id");

		$r->execute();

		$list = [];
		foreach ($r->fetchAll(PDO::FETCH_ASSOC) as $v) {

			$list[$v['country_id']] = $v['country_name'];
		}

		return $list;
	}


	public function uniqueEmail($attribute, $params) {

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


	public function checkUserCityID() {

		if ($this->user_city_id && !$this->user_country_id) {

			$this->addError('user_country_id', 'Перед указанием города нужно ввести страну');
		}
	}


	public function getDataForTemplate() {

		$data = parent::getDataForTemplate();

		$data['statusList']= $this->getStatusList();

		$data['activationList']= $this->getActivationList();

		$data['isFakeList']= $this->getIsFakeList();

		$data['subscriptionStatusList']= $this->getSubscriptionStatusList();

		$data['countryList']= $this->getCountryList();

		$data['cityList']= $this->getCityList();

		return $data;
	}

}