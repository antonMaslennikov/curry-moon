<?php
namespace admin\application\models;

use PDO;
use smashEngine\core\App;
use smashEngine\core\Model;

/**
 * Class post
 * @package admin\application\models
 *
 * @property date publish_date
 * @property string category
 * @property string slug
 * @property string title
 * @property string content
 * @property string status
 * @property string keywords
 * @property string description
 * @property integer image
 * @property string tags
 */
class post extends Model {

	const TYPE_TAGS = 'blog';

	const SPECIAL_BLOG = 0;

	const SPECIAL_STOCK = 1;

	const SPECIAL_LOOKBOOK = 2;

	protected static $dbtable = 'posts';

	protected $modified_data = [

	];

	use TagsTrait;

	/**
	 * Получение списка страниц
	 *
	 * @return array
	 */
	public function getList($params = null) {

        if ($params['category'] && is_numeric($params['category'])) {
            $aq .= "AND `category` = '" . (int) $params['category'] . "'";
        }
        
		$sql = 'SELECT * FROM '.self::db().' WHERE 1 ' . $aq . ' ORDER BY publish_date DESC';

		$stmt = App::db()->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getListCategory() {

		return [
			self::SPECIAL_BLOG => '<span class="label label-default">Блог</span>',
			self::SPECIAL_STOCK => '<span class="label label-primary">Акция</span>',
			self::SPECIAL_LOOKBOOK => '<span class="label label-success">LookBook</span>',
		];
	}

	public function saveContent($id, $content) {

		$sql = 'UPDATE '.self::db().' SET content = :content WHERE id = :id';

		$stmt = App::db()->prepare($sql);
		$stmt->execute([':content'=>$content, ':id'=>(int) $id]);
	}
}