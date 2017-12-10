<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 06.12.2017
 * Time: 22:42
 */

namespace admin\application\controllers;

use admin\application\models\settings;
use admin\application\models\settingsFormModel;
use smashEngine\core\helpers\Html;

class Controller_settings extends Controller_ {

	protected $layout = 'index.tpl';

    public function __construct($r)
    {
        parent::__construct($r);
        
        $this->setBreadCrumbs([
			'/admin/settings/list'=>'<i class="fa fa-fw fa-files-o"></i> Настройки',
		]);
    }
    
	public function action_index() {

		$this->setTemplate('settings/index.tpl');
		$this->setTitle('<i class="fa fa-fw fa-files-o"></i> Настройки');

		$this->view->setVar('settings', settings::getList());

        if ($_POST)
        {
            settings::saveListValues($_POST['values']);
            
            $this->page->refresh();
        }
        
		$this->render();
	}


	public function action_create() {

		$settings = new settings();

		$this->setTemplate('settings/form.tpl');
		$this->setTitle('Новая опция');

		$this->setBreadCrumbs([
			'/admin/settings/list'=>'<i class="fa fa-fw fa-files-o"></i> Настройки',
		]);

		$model = new settingsFormModel();
		$postModel = Html::modelName($model);

		if (isset($_POST[$postModel])) {

			$model->setAttributes($_POST[$postModel]);

			if ($model->validate()) {

				$settings->setAttributes($model->getData());

				$settings->save();

				if (isset($_POST['apply'])) {
					$this->page->go('/admin/settings/update?id='.$post->id);
				} else {
					$this->page->go('/admin/settings/list');
				}
			}
		}

		$this->view->setVar('model', $model->getDataForTemplate());
		$this->view->setVar('button', 'Создать');

		$this->render();
	}


	public function action_delete() {

		$settings = new settings((int) $_GET['id']);

		if ($settings->delete()) {
			$this->page->go('/admin/settings/list');
		} else {
			throw new Exception('Неизвестная ошибка');
		}
	}


	public function action_update() {

		$post = new settings((int) $_GET['id']);

		$this->setTemplate('settings/form.tpl');
		$this->setTitle(sprintf('Запись: "%s"', $post->title));

		$this->setBreadCrumbs([
			'/admin/blog/list'=>'<i class="fa fa-fw fa-files-o"></i> Настройки',
		]);

		$model = new settingsFormModel();
		$model->setAttributes($post->info, false);
		$model->setUpdate();

		$postModel = Html::modelName($model);

		if (isset($_POST[$postModel])) {

			$model->setAttributes($_POST[$postModel]);

			if ($model->validate()) {

				$post->setAttributes($model->getData());

				$post->save();

				if (isset($_POST['apply'])) {
					$this->page->go('/admin/settings/update?id='.$post->id);
				} else {
					$this->page->go('/admin/settings/list');
				}
			}
		}

		$this->view->setVar('model', $model->getDataForTemplate());
		$this->view->setVar('button', 'Изменить');

		$this->render();
	}

}