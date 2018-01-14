<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 06.12.2017
 * Time: 23:09
 */

namespace admin\application\models;

use smashEngine\core\App;
use smashEngine\core\helpers\File;
use smashEngine\core\helpers\UploadedFile;
use smashEngine\core\models\FormModel;
use smashEngine\core\helpers\Thumbnailer;

/**
 * Class PostFormModel
 * @package admin\application\models
 *
 * @property string publish_date
 * @property string slug
 * @property string title
 * @property string content
 * @property string status
 * @property string keywords
 * @property string description
 * @property integer image
 * @property string tags
 */
class LookbookFormModel extends FormModel {

	protected $all_tags;

	protected $old_slug;

	public $newRecord = true;

	public $publish_date;

	public $slug;

	public $title;

	public $content;

	public $status = 1;

	public $keywords;

	public $description;

	public $image;

	public $image_file;

	public $tags;

	public $category = 2;

	public $lang = 'ru';
    
    public $lb_link;
    public $lb_name;
    public $lb_pictures;
    public $lb_pictures_temp;

	public function rules() {

		return [
			['tags', 'saveNewTags'],

			[['title', 'slug', 'publish_date', 'lb_link', 'lb_name'], 'required',],

			['status', 'in', 'range'=>array_keys($this->getListStatus())],

			['slug', 'safe'],
			['slug', 'uniqueSlug', 'allowEmpty'=>false],
			['slug', 'filter', 'filter'=>'mb_strtolower'],
			['slug', 'filter', 'filter'=>'textToTranslit'],
			['slug', 'length', 'max'=>100],


			[['lang', 'image'], 'unsafe'],

			['image_file', 'file', 'types'=>'jpg, jpeg, gif, png', 'allowEmpty'=>true],
            ['lb_pictures', 'file', 'types'=>'jpg, jpeg, gif, png', 'allowEmpty'=>true, 'maxFiles'=>15],
            
			[['content', 'keywords', 'description'], 'safe'],

			['publish_date', 'dateFormat'],

		];
	}

	public function setUpdate() {

		$this->newRecord = false;

		$this->old_slug = $this->slug;

		$this->publish_date = \DateTime::createFromFormat('Y-m-d', $this->publish_date)->format('d.m.Y');
	}

	protected function getListStatus() {

		return [
			1 => 'Активен',
			0 => 'Не активен',
		];
	}

	public function saveNewTags() {

		$all_tags = $this->getAllTags();

		foreach ($this->tags as $key=> $tag) {

			$tag = trim($tag,  " \t\n\r\0\x0B,");
			if (!isset($all_tags[$tag])) {

				$this->tags[$key] = application\models\product::createTag($tag);
				$this->all_tags[$key] = $tag;
			}
		}
	}


	public function uniqueSlug($attribute, $params) {

		if (!$this->slug) $this->slug = toTranslit($this->title);

		if (!$this->newRecord) {

			if ($this->slug == $this->old_slug)	 return;
		}

		$r = App::db()->prepare("SELECT id FROM `" . post::getDbTableName() . "` WHERE `slug` = ? AND `lang` = ? LIMIT 1");

		$r->execute([$this->slug, $this->lang]);

		if ($r->rowCount() == 1)
		{
			$this->addError('slug', sprintf('Атрибут "%s" уже существует в записях!', $this->getAttributeLabel('slug')));
		}
	}


	protected function getAllTags() {

		if($this->all_tags === null) {

			$this->all_tags = post::getAllTags();

		}

		return $this->all_tags;
	}


	public function dateFormat($attribute, $params) {

		if ($this->getErrorSummary()) return;

		if (!$this->publish_date) return;

		$this->publish_date = \DateTime::createFromFormat('d.m.Y', $this->publish_date)->format('Y-m-d');
	}


	public function attributeLabels() {

		return [
			'publish_date'=>'Дата публикации',
			'slug'=>'URL',
			'title'=>'Заголовок',
			'content'=>'Содержимое',
			'status'=>'Статус',
			'keywords'=>'META ключевые слова',
			'description'=>'META описания',
			'image_file'=>'Изображение',
			'tags'=>'Теги',
            'lb_link' => 'Ссылка на товар',
            'lb_name' => 'Название товара',
            'lb_pictures' => 'Изображения',
		];
	}


	public function getDataForTemplate() {

		$data = parent::getDataForTemplate();

		$data['listStatus'] = $this->getListStatus();

		$data['tags'] = ($this->newRecord)?[]:$data['tags'];

		$data['listAllTags'] = $this->getAllTags();

		return $data;
	}


	public function getData() {

		$attributes = $this->getAttributes();

        $attributes['lb_link'] = str_replace(mainUrl, '', $attributes['lb_link']);
        
        unset($attributes['lb_pictures']);
        
		$pictures = UploadedFile::getInstances($this, 'lb_pictures');

	    if (count($pictures)) {

		    File::checkPath(File::uploadedPath());

		    foreach (UploadedFile::getInstances($this, 'lb_pictures') as $instance) {
                
			    $imgPath = File::uploadedPath() . DS. date('His_') . $instance->name;
			    $instance->saveAs($imgPath);

                // гифы переконвертируем в Jpeg так как на сервере есть проблемы с памятью 
                if ($instance->type == 'image/gif') {
                    $imagine = new \Imagine\Imagick\Imagine();
                    $imagine
                        ->open($imgPath)
                        ->save(str_replace('.gif', '.jpg', $imgPath));
                    
                    unlink($imgPath);
                    
                    $imgPath = str_replace('.gif', '.jpg', $imgPath);
                }
                
			    $attributes['lb_pictures_temp'][] = [
                    'big' => File::getUrlForAbsolutePath($imgPath),
                    'thumb' => (new Thumbnailer())->thumbnail($imgPath, File::uploadedPath(), 214, 279),
                ];
			}
	    }

        // собираем текст поста по шаблону
        $attributes['content']  = '<p style="text-align: left;">';
        $attributes['content'] .= '<a href="' . $attributes['lb_link'] . '">' . $attributes['lb_name'] . '</a>';
        $attributes['content'] .= '</p>';
        
        foreach ($attributes['lb_pictures_temp'] AS $lp) {
            $attributes['content'] .= '<div class="lookbook-block">';
            $attributes['content'] .= '<a href="' . $lp['big'] . '" data-rokbox-album="scarves" data-rokbox="">';
            $attributes['content'] .= '<img src="' . $lp['thumb'] . '" alt="yarkoe-platie" />';
            $attributes['content'] .= '</a>';
            $attributes['content'] .= '</div>';
        }
        
        unset($attributes['lb_pictures_temp']);
        unset($attributes['lb_link']);
        unset($attributes['lb_name']);
        
		return $attributes;
	}
}