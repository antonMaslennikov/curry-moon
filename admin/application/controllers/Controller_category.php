<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 01.12.2017
 * Time: 19:31
 */

namespace admin\application\controllers;


use admin\application\models\category;

class Controller_category {

	public function action_index()
	{
		$this->page->index_tpl = 'index.tpl';
		$this->page->tpl = 'category/index.tpl';

		$this->page->title = "Пример";

		$model = new category();
		printr($model, 1);

		/*
		$this->page->import(array(
			'/public/js/p/main.js',
			'/public/css/p/main.css',
		));
		*/

		$this->view->generate($this->page->index_tpl);
	}
}