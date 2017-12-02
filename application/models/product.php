<?php

namespace admin\application\models;
use smashEngine\core\App;

/**
 * Class product
 * @package admin\application\models
 *
 * @property int $id ID
 */
class product extends \smashEngine\core\Model {

	/**
	 * @var имя таблицы в БД для хранения экземпляров класса
	 */
	public static $dbtable = 'product';
    
}