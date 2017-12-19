<?php
namespace application\models;

use PDO;
use smashEngine\core\App;
use smashEngine\core\Model;

/**
 * Class post
 * @package admin\application\models
 *
 * @property date publish_date
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

	protected static $dbtable = 'posts';

	protected $modified_data = [

	];

	use \admin\application\models\TagsTrait;

	/**
	 * Получение списка страниц
	 *
	 * @return array
	 */
	public function getList($params, $trans_id = null) {

        $data = [];
        
        if ($params['withtag']) {
            $at[] = '`tags_relation` tr';
            $aq[] = 'tr.`tag_id` = ?';
            $aq[] = 't.`id` = tr.`post_id`';
            
            array_push($data, $params['withtag']);
        }
        
		$sql = 'SELECT SQL_CALC_FOUND_ROWS t.*, p.`picture_path` 
                FROM 
                    '.self::$dbtable.' t, 
                    `' . picture::$dbtable . '` p
                    ' . (count($at) > 0 ? ', ' . implode(', ', $at) : '') . '
                WHERE p.`picture_id` = t.`image`
                    '
                    .
                    (in_array($params['lang'], ['ru', 'en']) ? " AND `lang` = '" . $params['lang'] . "'" : '')
                    .
                    (isset($params['status']) ? " AND `status` = '" . (int) $params['status'] . "'" : '')
                    .
                    (isset($params['category']) ? " AND `category` = '" . (int) $params['category'] . "'" : '')
                    .
                    ($params['datestart'] ? " AND `publish_date` >= '" . $params['datestart'] . "'" : '')
                    .
                    ($params['dateend'] ? " AND `publish_date` <= '" . $params['dateend'] . "'" : '')
                    .
                    (count($aq) > 0 ? ' AND ' . implode(' AND ', $aq) : '') 
                    .
                    '
                ORDER BY ' . ($params['orderby'] ? $params['orderby'] : 'publish_date');

        if ($params['limit']) {
            $sql .= " LIMIT " . ($params['offset'] ? intval($params['offset']) : 0) . ", " . intval($params['limit']);
        }
        
		$stmt = App::db()->prepare($sql);
		$stmt->execute($data);

        $foo = App::db()->query("SELECT FOUND_ROWS() AS s")->fetch();
        $_SESSION['pages_total_' . $trans_id] = $foo['s'];
        
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

    public function findBySlug($slug)
    {
        $stmt = App::db()->prepare('SELECT t.`id` FROM '.self::$dbtable.' t WHERE t.`slug` = ? LIMIT 1');
		$stmt->execute([urldecode($slug)]);

        if ($post = $stmt->fetch()) {
		  return new self($post['id']);
        }
    }
}