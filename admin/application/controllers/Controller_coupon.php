<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 06.12.2017
 * Time: 22:42
 */

namespace admin\application\controllers;

use \application\models\certificate;
use admin\application\models\couponFormModel;
use smashEngine\core\helpers\Html;

class Controller_coupon extends Controller_ {

	protected $layout = 'index.tpl';

    public function __construct($r)
    {
        parent::__construct($r);
        
        $this->setBreadCrumbs([
			'/admin/coupon/list'=>'<i class="fa fa-fw fa-files-o"></i> Купоны',
		]);
    }
    
	public function action_index() {

		$this->setTemplate('coupon/index.tpl');
		$this->setTitle('<i class="fa fa-fw fa-files-o"></i> Купоны');

		$this->view->setVar('coupons', certificate::findAll());

		$this->render();
	}

	public function action_create() {

		$coupon = new certificate();

		$this->setTemplate('coupon/form.tpl');
		$this->setTitle('Новый купон');

		$this->setBreadCrumbs([
			'/admin/coupon/list'=>'<i class="fa fa-fw fa-files-o"></i> Создать новый',
		]);
        
        $this->page->import(['/public/packages/bootstrap-datepicker/css/bootstrap-datepicker.css',
                             '/public/packages/bootstrap-datepicker/js/bootstrap-datepicker.js',
                             '/public/packages/bootstrap-datepicker/locales/bootstrap-datepicker.ru.min.js']);

		$model = new couponFormModel();
		$postModel = Html::modelName($model);

        $model->certification_limit = 1;
        
		if (isset($_POST[$postModel])) {

			$model->setAttributes($_POST[$postModel]);

			if ($model->validate()) {

				$coupon->setAttributes($model->getData());

				$coupon->save();

				if (isset($_POST['apply'])) {
					$this->page->go('/admin/coupon/update?id='.$coupon->id);
				} else {
					$this->page->go('/admin/coupon/list');
				}
			}
		}

        $this->view->setVar('types', certificate::$types);
		$this->view->setVar('model', $model->getDataForTemplate());
		$this->view->setVar('button', 'Создать');

		$this->render();
	}

	public function action_update() {

		$item = new certificate((int) $_GET['id']);

		$this->setTemplate('coupon/form.tpl');
		$this->setTitle(sprintf('Купон: "%s"', $item->certification_password));

		$this->setBreadCrumbs([
			'/admin/blog/list'=>'<i class="fa fa-fw fa-files-o"></i> Редактировать',
		]);
        
        $this->page->import(['/public/packages/bootstrap-datepicker/css/bootstrap-datepicker.css',
                             '/public/packages/bootstrap-datepicker/js/bootstrap-datepicker.js',
                             '/public/packages/bootstrap-datepicker/locales/bootstrap-datepicker.ru.min.js']);

		$model = new couponFormModel();
		$model->setAttributes($item->info, false);
		$model->setUpdate();

		$postModel = Html::modelName($model);

		if (isset($_POST[$postModel])) {

			$model->setAttributes($_POST[$postModel]);

			if ($model->validate()) {

				$item->setAttributes($model->getData());

				$item->save();

				if (isset($_POST['apply'])) {
					$this->page->go('/admin/coupon/update?id='.$item->id);
				} else {
					$this->page->go('/admin/coupon/list');
				}
			}
		}

        $this->view->setVar('types', certificate::$types);
		$this->view->setVar('model', $model->getDataForTemplate());
		$this->view->setVar('button', 'Изменить');

		$this->render();
	}

	public function action_delete() {

		$coupon = new certificate((int) $_GET['id']);

		if ($coupon->delete()) {
			$this->page->go('/admin/coupon/list');
		} else {
			throw new appException('Неизвестная ошибка');
		}
	}
}