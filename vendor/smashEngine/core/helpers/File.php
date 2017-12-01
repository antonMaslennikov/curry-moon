<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 02.12.2017
 * Time: 1:23
 */

namespace smashEngine\core\helpers;


class File {

	public static function checkPath($path, $rights = 0777, $recursive = true)
	{
		if(empty($path)) {return false;}

		if (!is_dir($path)) { // проверка на существование директории

			$mask = umask(0);
			$is = mkdir($path, $rights, $recursive); // возвращаем результат создания директории
			umask($mask);

			return $is;

		} else {
			if (!is_writable($path)) { // проверка директории на доступность записи

				return false;
			}
		}

		return true; // папка существует и доступна для записи
	}


	public static function uploadedPath() {

		return implode(DS, [
			$_SERVER['DOCUMENT_ROOT'],
			'public',
			'uploaded',
			date('Y'),
			date('m'),
			date('d'),
		]);
	}
}