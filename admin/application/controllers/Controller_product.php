<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 01.12.2017
 * Time: 19:31
 */

namespace admin\application\controllers;


use application\models\product;
use admin\application\models\ProductFormModel;
use smashEngine\core\helpers\Html;

class Controller_product extends Controller_ 
{
    protected $layout = 'index.tpl';

	public function action_index()
	{
		$this->setTemplate('product/index.tpl');
		$this->setTitle("Товары");

		

		$this->render();
	}
    
    public function action_create()
	{
		$this->setTemplate('product/create.tpl');
		$this->setTitle("Добавить товар");

		

		$this->render();
	}
}