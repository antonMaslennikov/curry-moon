<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 29.11.2017
 * Time: 21:27
 */

namespace admin\application\controllers;


class Controller_example extends Controller_{

	public function action_index()
	{
		$this->page->index_tpl = 'adminlte/index.tpl';
		$this->page->tpl = 'adminlte/page/index.tpl';

		/*
		$this->page->import(array(
			'/public/js/p/main.js',
			'/public/css/p/main.css',
		));
		*/

		$this->view->generate($this->page->index_tpl);
	}
}