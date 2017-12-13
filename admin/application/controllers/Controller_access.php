<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 13.12.2017
 * Time: 22:55
 */

namespace admin\application\controllers;


class Controller_access extends Controller_ {

	public function action_index()
	{
		$this->page->index_tpl = 'index.tpl';
		$this->page->tpl = 'authorization/index.tpl';

		/*
		$this->page->import(array(
			'/public/js/p/main.js',
			'/public/css/p/main.css',
		));
		*/

		$this->view->generate($this->page->index_tpl);
	}
}