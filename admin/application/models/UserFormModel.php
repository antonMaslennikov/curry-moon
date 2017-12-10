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

	protected $old_login;

	public $user_login;
	public $user_password;
	//public $user_sex;
	public $user_name;
	public $user_show_name = 'true';
	public $user_email;
	public $user_show_email = 'false';
	public $user_phone;
	//public $user_birthday;
	//public $user_register_date;
	public $user_ip; // ip2long,
	//public $user_url;
	//public $user_picture; //'=> 'intval',
    public $user_description;
	public $user_status;
    public $user_last_login;
	public $user_activation;
    public $user_is_fake;
	public $user_subscription_status;
    public $user_address;
	public $user_zip;
    public $user_country_id; //'=> 'intval',
	public $user_city_id;   //=> 'intval',


	public function setUpdate() {

		$this->newRecord = false;

		$this->old_login = $this->user_login;
	}


	public function getData() {

		$attributes = $this->getAttributes();

		return $attributes;
	}


	public function rules() {

		return [
			[['user_login', 'user_name', 'user_email'], 'required' ],

			['user_login', 'uniqueLogin'],

			['user_email', 'email'],

			[['user_show_name', 'user_show_email'], 'in', 'range'=>array_keys($this->getShowNameList())],

			[['user_phone'], 'safe'],
		];
	}


	public function attributeLabels() {

		return [
			'user_login'=>'Логин',
			'user_password'=>'',
		//	'user_sex'=>'',
			'user_name'=>'ФИО',
			'user_show_name'=>'Отображать ФИО',
			'user_email'=>'E-mail',
			'user_show_email'=>'Отображать E-mail',
			'user_phone'=>'Телефон',
		//	'user_birthday'=>'',
		//	'user_register_date'=>'',
			'user_ip'=>'',
		//	'user_url'=>'',
		//	'user_picture'=>'',
            'user_description'=>'',
			'user_status'=>'',
            'user_last_login'=>'',
			'user_activation'=>'',
            'user_is_fake'=>'',
			'user_subscription_status'=>'',
            'user_address'=>'',
			'user_zip'=>'',
            'user_country_id'=>'',
			'user_city_id'=>'',
		];
	}

	protected function uniqueLogin($attribute, $params) {

		if (!$this->newRecord) {

			if ($this->slug == $this->old_slug)	 return;
		}

		$r = App::db()->prepare("SELECT id FROM `" . user::db() . "` WHERE `user_login` = ? LIMIT 1");

		$r->execute([$this->slug]);

		if ($r->rowCount() == 1)
		{
			$this->addError('slug', sprintf('Пользватель с таким логином уже существует!', $this->getAttributeLabel('slug')));
		}
	}

	protected function getShowNameList() {

		return [
			'true' => 'Отображать',
			'false' => 'Не отображать',
		];
	}

	public function getDataForTemplate() {

		$data = parent::getDataForTemplate();

		$data['show_name_list'] = $this->getShowNameList();

		return $data;
	}

}