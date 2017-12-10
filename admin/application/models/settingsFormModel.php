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

/**
 * Class PageFormModel
 * @package admin\application\models
 *
 * @property string slug
 * @property string h1_ru
 * @property string h1_en
 * @property string text_ru
 * @property string text_en
 * @property string meta_keywords
 * @property string meta_description
 */
class settingsFormModel extends FormModel{

	protected $old_variable_name;

	public $id;

	public $newRecord = true;

	public $variable_name;
	public $variable_value;
    public $variable_description;


	public function setUpdate() {

		$this->newRecord = false;

		$this->old_variable_name = $this->variable_name;
	}


	public function getOldSlug() {

		return $this->old_variable_name;
	}


	public function getData() {

		return $this->getAttributes();
	}

	public function rules() {

		return [
			['variable_name', 'required',],
            ['variable_name', 'lat',],
			['variable_name', 'uniqueSlug', 'allowEmpty'=>false],
			
			[['variable_name'], 'length', 'max'=>100],

			['newRecord', 'unsafe'],

			[['variable_name', 'variable_value', 'variable_description'], 'safe'],
		];
	}


	public function uniqueSlug($attribute, $params) {

		if (!$this->newRecord) {

			if ($this->variable_name == $this->old_variable_name)	 return;
		}

		$r = App::db()->prepare("SELECT id FROM `" . settings::getDbTableName() . "` WHERE `variable_name` = ? LIMIT 1");

		$r->execute([$this->variable_name]);

		if ($r->rowCount() == 1)
		{
			$this->addError('variable_name', sprintf('Настройка "%s" уже существует!', $this->getAttributeLabel('slug')));
		}
	}


	public function attributeLabels() {

		return [
			'variable_name'=>'Название',
			'variable_value'=>'Значение',
            'variable_description' => 'Описание',
		];
	}

	public function getDataForTemplate() {

		$data = parent::getDataForTemplate();

		return $data;
	}
}