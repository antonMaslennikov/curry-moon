<?php
namespace admin\application\controllers;

use \application\models\mailTemplate;
use admin\application\models\mailTemplateFormModel;
use smashEngine\core\helpers\Html;
use \smashEngine\core\exception\appException;

class Controller_templates extends Controller_ {

	protected $layout = 'index.tpl';

    public function __construct($r)
    {
        parent::__construct($r);
        
        $this->setBreadCrumbs([
			'/admin/coupon/list'=>'<i class="fa fa-fw fa-files-o"></i> Шаблоны писем',
		]);
    }
    
	public function action_index() {

		$this->setTemplate('templates/index.tpl');
		$this->setTitle('<i class="fa fa-fw fa-files-o"></i> Шаблоны писем');

        $templates = [];
        
        foreach (mailTemplate::getAll() AS $t) {
            $templates[$t['mail_template_order']]['title'] = $t['category'];
            $templates[$t['mail_template_order']]['tpls'][] = $t;
        }
        
		$this->view->setVar('list', $templates);

		$this->render();
	}

	public function action_create() {

		$template = new mailTemplate();

		$this->setTemplate('templates/form.tpl');
		$this->setTitle('Новый шаблон');

		$this->setBreadCrumbs([
			'/admin/templates'=>'<i class="fa fa-fw fa-files-o"></i> Создать новый',
		]);
        
		$model = new mailTemplateFormModel();
		$postModel = Html::modelName($model);
        
		if (isset($_POST[$postModel])) {

			$model->setAttributes($_POST[$postModel]);

			if ($model->validate()) {

				$template->setAttributes($model->getData());

				$template->save();

				if (isset($_POST['apply'])) {
					$this->page->go('/admin/templates/update?id='.$template->id);
				} else {
					$this->page->go('/admin/templates');
				}
			}
		}

		$this->view->setVar('model', $model->getDataForTemplate());
		$this->view->setVar('button', 'Создать');

		$this->render();
	}

	public function action_update() {

		$item = new mailTemplate((int) $_GET['id']);

		$this->setTemplate('templates/form.tpl');
		$this->setTitle(sprintf('Шаблон: "%s"', $item->id));

		$this->setBreadCrumbs([
			'/admin/blog/list'=>'<i class="fa fa-fw fa-files-o"></i> Редактировать',
		]);
        

		$model = new mailTemplateFormModel();
		$model->setAttributes($item->info, false);
		$model->setUpdate();

		$postModel = Html::modelName($model);

		if (isset($_POST[$postModel])) {

			$model->setAttributes($_POST[$postModel]);

			if ($model->validate()) {

				$item->setAttributes($model->getData());

				$item->save();

				if (isset($_POST['apply'])) {
					$this->page->go('/admin/templates/update?id='.$item->id);
				} else {
					$this->page->go('/admin/templates/list');
				}
			}
		}
        
        $this->view->setVar('tplDir', trim(mailTemplate::$tpl_folder, '..'));
		$this->view->setVar('model', $model->getDataForTemplate());
		$this->view->setVar('button', 'Изменить');

		$this->render();
	}

	public function action_delete() {

		$t = new mailTemplate((int) $_GET['id']);

		if ($t->delete()) {
			$this->page->go('/admin/templates/list');
		} else {
			throw new appException('Неизвестная ошибка');
		}
	}
}