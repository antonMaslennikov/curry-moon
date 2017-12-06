<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 06.12.2017
 * Time: 22:42
 */

namespace admin\application\controllers;

use admin\application\models\blog;
use admin\application\models\post;
use admin\application\models\PostFormModel;
use smashEngine\core\helpers\Html;

class Controller_blog extends Controller_ {

	protected $layout = 'index.tpl';

	public function action_index() {

		$this->setTemplate('blog/index.tpl');
		$this->setTitle('<i class="fa fa-fw fa-files-o"></i> Блог');

		$this->setBreadCrumbs([
			//'<i class="fa fa-fw fa-shopping-bag"></i> Категории товаров',
		]);

		$this->view->setVar('post', (new post())->getList());

		$this->render();
	}


	public function action_create() {

		$post = new post();

		$this->setTemplate('blog/form.tpl');
		$this->setTitle('Новая запись блога');

		$this->setBreadCrumbs([
			'/admin/blog/list'=>'<i class="fa fa-fw fa-files-o"></i> Список страниц',
		]);

		$model = new PostFormModel();
		$postModel = Html::modelName($model);

		if (isset($_POST[$postModel])) {

			$model->setAttributes($_POST[$postModel]);

			if ($model->validate()) {

				$post->setAttributes($model->getData());

				printr($post, 1);
				$post->save();

				if (isset($_POST['apply'])) {

					$this->page->go('/admin/blog/update?id='.$post->id);
				} else {

					$this->page->go('/admin/blog/list');
				}
			}
		}

		$this->view->setVar('model', $model->getDataForTemplate());
		$this->view->setVar('button', 'Создать');

		$this->page->import(['/public/packages/bootstrap-datepicker/css/bootstrap-datepicker.css']);
		$this->page->import(['/public/packages/bootstrap-datepicker/js/bootstrap-datepicker.js']);
		$this->page->import(['/public/packages/bootstrap-datepicker/locales/bootstrap-datepicker.ru.min.js']);
		$this->page->import(['/public/packages/select2/css/select2.min.css']);
		$this->page->import(['/public/packages/select2/js/select2.min.js']);
		$this->page->import(['/public/packages/select2/js/i18n/ru.js']);

		$this->render();
	}

}