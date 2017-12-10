<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 04.12.2017
 * Time: 0:52
 */

namespace admin\application\models;

use smashEngine\core\App;
use smashEngine\core\models\FormModel;
use \application\models\certificate;

/**
 * Class couponFormModel
 * @package admin\application\models
 */
class couponFormModel extends FormModel{

    protected $old_password;
    
	public $id;

	public $newRecord = true;

	public $certification_password;
	public $certification_value;
    public $certification_type;
    public $certification_active;
    public $certification_comment;
    public $certification_limit;
    public $lifestart;
    public $lifetime;
    public $certification_enabled;

    protected function getListStatus() {

		return [
			1 => 'Активен',
			0 => 'Не активен',
		];
	}

	public function setUpdate() {
		$this->newRecord = false;
        $this->old_password = $this->certification_password;

		$this->lifestart = \DateTime::createFromFormat('Y-m-d', $this->lifestart)->format('d.m.Y');
        $this->lifetime = \DateTime::createFromFormat('Y-m-d', $this->lifetime)->format('d.m.Y');
	}

	public function getData() {

		return $this->getAttributes();
	}

	public function rules() {

		return [
			[['certification_password', 'certification_value'], 'required',],
			[['certification_password'], 'length', 'max'=>30],
            ['certification_password', 'uniquePassword', 'allowEmpty'=>false],
            
			['newRecord', 'unsafe'],

			[['certification_password', 'certification_value', 'certification_type', 'certification_active', 'certification_comment', 'certification_limit', 'lifestart', 'lifetime' ,'certification_enabled'], 'safe'],
            
            ['lifestart', 'dateFormat1'],
            ['lifetime', 'dateFormat2'],
		];
	}
    
    public function uniquePassword($attribute, $params) {

		if (!$this->newRecord) {
			if ($this->certification_password == $this->old_password)	 return;
		}

		$r = App::db()->prepare("SELECT id FROM `" . certificate::getDbTableName() . "` WHERE `certification_password` = ? LIMIT 1");

		$r->execute([$this->certification_password]);

		if ($r->rowCount() == 1)
		{
			$this->addError('certification_password', sprintf('Купон "%s" уже существует в записях!', $this->certification_password));
		}
	}

    public function dateFormat1($attribute, $params) {

		if ($this->getErrorSummary()) return;

		if (!$this->lifestart) return;

		$this->lifestart = \DateTime::createFromFormat('d.m.Y', $this->lifestart)->format('Y-m-d');
	}
    
    public function dateFormat2($attribute, $params) {

		if ($this->getErrorSummary()) return;

		if (!$this->lifetime) return;

		$this->lifetime = \DateTime::createFromFormat('d.m.Y', $this->lifetime)->format('Y-m-d');
	}
    
	public function attributeLabels() {

		return [
			'certification_password' => 'Код',
            'certification_value' => 'Значение',
            'certification_type' => 'Тип купона',
            'certification_active' => 'Статус купона',
            'certification_comment' => 'Кооментарий',
            'certification_limit' => 'Лимит на количество использований',
            'lifestart' => 'Дата начала действия купона',
            'lifetime' => 'Срок годности купона',
            'certification_enabled' => 'Вкл/выкл',
		];
	}

	public function getDataForTemplate() {

		$data = parent::getDataForTemplate();

        $data['listStatus'] = $this->getListStatus();
        
		return $data;
	}
}