<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 03.12.2017
 * Time: 23:16
 */

namespace admin\application\controllers;


class Controller_api extends Controller_ {

	protected $layout = null;

	public function action_transliterate()
	{
		if (isset($_GET['data'])) {

			echo toTranslit($_GET['data']);
		}

		die();
	}
}