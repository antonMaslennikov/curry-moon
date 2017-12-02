<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 02.12.2017
 * Time: 1:23
 */

namespace smashEngine\core\helpers;

use smashEngine\core\App;

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


	public static function getUrlForAbsolutePath($path) {

		$path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $path);
		$path = str_replace(DS, '/', $path);

		return $path;
	}


	public static function getAbsolutePathForUrl($url) {

		$url = str_replace('/', DS, $url);

		$url  = $_SERVER['DOCUMENT_ROOT'].$url;

		return $url;
	}


	public static function deletePicture($id)
	{
		if (!empty($id))
		{
			$r = App::db()->query("SELECT `picture_path` FROM `pictures` WHERE `picture_id` = '" . abs(intval($id)) . "' LIMIT 1");

			$row = $r->fetch();

			$path = self::getAbsolutePathForUrl($row['picture_path']);

			if (file_exists($path)) {
				@unlink($path);
			}

			App::db()->query("DELETE FROM pictures WHERE `picture_id` = '" . abs(intval($id)) ."' LIMIT 1");

			return true;
		}
		else
			return false;
	}


	public static function uploadedPath($basePath = null) {

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