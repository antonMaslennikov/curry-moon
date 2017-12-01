<?php

namespace admin\application\models;

/**
 * Class category
 * @package admin\application\models
 *
 * @property int $id ID
 * @property int $parent_id
 * @property int $level
 * @property int $lft
 * @property int $rft
 * @property string $slug
 * @property string $title
 * @property int $picture_id
 * @property int $status
 */
class category extends \smashEngine\core\models\NSModel {

	/**
	 * @var имя таблицы в БД для хранения экземпляров класса
	 */
	public static $dbtable = 'category';
}