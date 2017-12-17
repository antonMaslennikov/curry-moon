<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 15.12.2017
 * Time: 22:25
 */

namespace admin\application\controllers;

use admin\application\models\FBSendFormModel;
use admin\application\models\feedback;
use admin\application\models\user;
use smashEngine\core\App;
use smashEngine\core\helpers\Html;


class Controller_feedback extends Controller_ {

	protected $layout = 'index.tpl';

	public function action_list() {

		$this->setTemplate('feedback/list_new.tpl');
		$this->setTitle('<i class="fa fa-envelope-o"></i>Новые сообщения');

		$this->view->setVar('list', (new feedback())->listData() );

		$this->render();
	}


	public function action_list_send() {

		$this->setTemplate('feedback/list_old.tpl');
		$this->setTitle('<i class="fa fa-envelope-o"></i> Отправленные сообщения');

		$this->view->setVar('list', (new feedback())->listData(true) );

		$this->render();
	}


	public function action_cut() {

		$feedback = new feedback((int) $_GET['id']);

		if (!$feedback->isNew()) $this->page->go('/admin/feedback/list');

		$feedback->setSpam($this->user->id);

		$this->page->go('/admin/feedback/list');
	}


	public function action_delete() {

		$feedback = new feedback((int) $_GET['id']);

		if (!$feedback->id) $this->page->go('/admin/feedback/list');

		$feedback->delete();

		if ($feedback->feedback_status === feedback::STATUS_NEW) {

			$this->page->go('/admin/feedback/list');
		} else {

			$this->page->go('/admin/feedback/list_send');
		}
	}


	public function action_view() {

		$feedback = new feedback((int) $_GET['id']);

		if (!$feedback->id) $this->page->go('/admin/feedback/list_send');

		$this->setTemplate('feedback/info.tpl');
		$this->setTitle('<i class="fa fa-envelope-o"></i> Просмотр сообщения "'.crop_str($feedback->feedback_topic, 25).'"');

		$this->setBreadCrumbs([
			'/admin/feedback/list'=>'<i class="fa fa-envelope-o"></i>Отправленные сообщения',
		]);


		$this->view->setVar('fb', $feedback->info);
		$this->view->setVar('user', (new user($feedback->feedback_reply_user))->info);

		$this->render();
	}


	public function action_send() {

		$feedback = new feedback((int) $_GET['id']);

		if (!$feedback->isNew()) $this->page->go('/admin/feedback/list');

		$this->setTemplate('feedback/send.tpl');
		$this->setTitle('<i class="fa fa-envelope-o"></i> Ответ');

		$this->setBreadCrumbs([
			'/admin/feedback/list'=>'<i class="fa fa-envelope-o"></i>Новые сообщения',
		]);

		$model = new FBSendFormModel();

		$postModel = Html::modelName($model);

		if (isset($_POST[$postModel])) {

			$model->setAttributes($_POST[$postModel]);

			if ($model->validate()) {

				$feedback->setAttributes($model->getAttributes());

				if ($feedback->send($this->user->id)) {

					App::mail()->send([$feedback->feedback_email], 18, ['feedback' => $feedback]);
				};

				$this->page->go('/admin/feedback/list');
			}
		}

		$this->view->setVar('fb', $feedback->info);
		$this->view->setVar('model', $model->getDataForTemplate());


		$this->render();
	}
}