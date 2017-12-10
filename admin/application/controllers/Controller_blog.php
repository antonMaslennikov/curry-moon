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
			'/admin/blog/list'=>'<i class="fa fa-fw fa-files-o"></i> Блог',
		]);

		$model = new PostFormModel();
		$postModel = Html::modelName($model);

		if (isset($_POST[$postModel])) {

			$model->setAttributes($_POST[$postModel]);

			if ($model->validate()) {

				$post->setAttributes($model->getData());

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

		$this->page->import([
			'/public/packages/bootstrap-datepicker/css/bootstrap-datepicker.css',
			'/public/packages/bootstrap-datepicker/js/bootstrap-datepicker.js',
			'/public/packages/bootstrap-datepicker/locales/bootstrap-datepicker.ru.min.js',
			'/public/packages/select2/css/select2.min.css',
			'/public/packages/select2/js/select2.min.js',
			'/public/packages/select2/js/i18n/ru.js'
		]);

		$this->render();
	}


	public function action_delete() {

		$post = new post((int) $_GET['id']);

		if ($post->delete()) {

			$this->page->go('/admin/blog/list');
		} else {

			throw new Exception('Неизвестная ошибка');
		}
	}


	public function action_update() {

		$post = new post((int) $_GET['id']);

		$this->setTemplate('blog/form.tpl');
		$this->setTitle(sprintf('Запись: "%s"', $post->title));

		$this->setBreadCrumbs([
			'/admin/blog/list'=>'<i class="fa fa-fw fa-files-o"></i> Блог',
		]);

		$model = new PostFormModel();
		$model->setAttributes($post->info, false);
		$model->setUpdate();

		$postModel = Html::modelName($model);

		if (isset($_POST[$postModel])) {

			$model->setAttributes($_POST[$postModel]);

			if ($model->validate()) {

				$post->setAttributes($model->getData());

				$post->save();

				if (isset($_POST['apply'])) {

					$this->page->go('/admin/blog/update?id='.$post->id);
				} else {

					$this->page->go('/admin/blog/list');
				}
			}
		}

		$this->view->setVar('model', $model->getDataForTemplate());
		$this->view->setVar('button', 'Изменить');

		$this->page->import([
			'/public/packages/bootstrap-datepicker/css/bootstrap-datepicker.css',
			'/public/packages/bootstrap-datepicker/js/bootstrap-datepicker.js',
			'/public/packages/bootstrap-datepicker/locales/bootstrap-datepicker.ru.min.js',
			'/public/packages/select2/css/select2.min.css',
			'/public/packages/select2/js/select2.min.js',
			'/public/packages/select2/js/i18n/ru.js'
		]);

		$this->render();
	}

}