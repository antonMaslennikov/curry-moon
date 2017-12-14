<?php

namespace admin\application\models;

use application\models\mailTemplate;
use smashEngine\core\App;
use smashEngine\core\helpers\File;
use smashEngine\core\helpers\UploadedFile;
use smashEngine\core\models\FormModel;

/**
 * Class mailTemplateFormModel
 * @package admin\application\models
 *
 * @property string $slug
 * @property string $title
 * @property UploadedFile $picture_id
 * @property int $status
 */
class mailTemplateFormModel extends FormModel {

	public $newRecord = true;

	public $id;
    
    public $mail_template_name;
    public $mail_template_subject;
    public $mail_template_file;
    public $mail_template_file_old;
    public $mail_template_order;
    public $mail_template_order_old;
	
	
	public function setUpdate() {

		$this->newRecord = false;
        $this->mail_template_order_old = $this->mail_template_order;
        $this->mail_template_file_old = $this->mail_template_file;
	}

    public function getData() {

		$attributes = $this->getAttributes();

        unset($attributes['id']);
        
        if (!$this->newRecord) {
            
            if ($attributes['mail_template_order'] != $attributes['mail_template_order_old']) 
            {
                $filename = basename($attributes['mail_template_file']);
                
                $attributes['mail_template_file'] = $attributes['mail_template_order'] . '/'  . $filename;
                
                if (!is_dir(mailTemplate::$tpl_folder . $attributes['mail_template_order'])) {
                    createDir(mailTemplate::$tpl_folder . $attributes['mail_template_order']);
                }

                rename(mailTemplate::$tpl_folder . $this->mail_template_file_old, mailTemplate::$tpl_folder . $attributes['mail_template_file']);
            }
        }
        
		return $attributes;
	}


	public function setPost($data) {
		$this->setAttributes($data, false);
	}


	public function rules() {

		return [
			
			[['mail_template_subject', 'mail_template_name'], 'required',],
            
            ['mail_template_order', 'in', 'range'=>array_keys(mailTemplate::$cats)],
            
			['newRecord, id', 'unsafe'],
		];
	}


	public function attributeLabels() {

		return [
            'mail_template_name' => 'Название шаблона',
            'mail_template_subject' => 'Заголовок письма',
            'mail_template_file' => 'Файл с содержимым',
            'mail_template_order' => 'Категория',
		];
	}


	protected function getListCategory() {

		if ($this->_listCategory === null) {

			$this->setListCategory();
		}

		return $this->_listCategory;
	}


	public function getDataForTemplate() {

		$data = parent::getDataForTemplate();

        $data['categorys'] = mailTemplate::$cats;
        
		return $data;
	}


	protected function setListCategory() {

		$this->_listCategory =  (new category())->getList();
	}
}