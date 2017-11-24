<?php
    namespace application\models;
    
    use \smashEngine\core\App AS App; 
    
    use \PDO;
    use \Imagick;
    use \ImagickPixel;
    use \ImagickDraw;
    use \Exception;
    use \ZipArchive;
    
    use \S3Thumb AS S3Thumb;
    
    
    class good extends \smashEngine\core\Model
    {
        public $id   = 0;
        public $info = array();
        public static $src_resize = 10;
        public static $pxPerCm = 99;

        public $comments = array();
        
        /**
         * Ширина основного превью работы
         */
        public static $mainPreviewWidth = 230;
        
        /**
         * поправка к размеру шрифта в php относительно javascript
         */ 
        public static $fontSizeCorrection = 1.35;
        
        /**
         * @param string имя таблицы в БД для хранения экземпляров класса
         */
        public static $dbtable = 'goods';
        
        public static $metatable = 'good__meta';
        
        public static $pics_table = 'good_pictures';
        
        public static $votetable = 'good__votes';
        
        public static $visitstable = 'good__visits';
        
        public static $good_status = array(
            //'printed'    => 'Победитель "Тираж"',
            'pretendent' => 'Победитель',
            'archived'   => 'Архив',
            'voting'     => 'На голосовании',
            'new'        => 'Новая', 
            //'winners'    => 'Победитель',
            'deny'       => 'Отклонена',
            'customize'  => 'Самоделка',
        );
        
        public static $instockStatus = array(
            'new'       => 'свежак',
            'hit'       => 'хит продаж',
            'reprinted' => 'перевыпуск',
            'sale'      => 'распродажа',
            'ilovemj'   => 'i&#9829;maryjane',
            'few'       => 'очень мало',
            'notees'    => 'c надписями',
            'mjhigh'    => 'mjhigh',
            'thebestof' => 'the best of',
            'valentine' => 'love is',
            'printshop' => 'принтшоп');
        
        public static $colorGroups = array(
            0 => 'разное',
            1 => 'светлые оттенки',
            2 => 'чёрный и тёмные оттенки',
            3 => 'серые',
            4 => 'белый'
        );
        
        /**
         * @param цвета подложки для разных груп цветов
         */
        static $color_bg = array(
            'ps_src_1' => '#C1E8EE',
            'ps_src_2' => '#000',
            'ps_src_3' => '#BCBCBC',
            'ps_src_4' => '#fff',
        );
        
        
        /**
         * Доступные к загрузке исходники работы
         */
        public static $srcs = array(
            'ps_src'     => array('title' => 'Исходник на одежду', 'min_size' => array('w' => 600, 'h' => 200), 'src' => [3600, 5000], 'exts' => 'png'),
            'ps_src_1'   => array('title' => 'Исходник на одежду, светлые оттенки', 'min_size' => array('w' => 600, 'h' => 200), 'exts' => 'png'),
            'ps_src_2'   => array('title' => 'Исходник на одежду, чёрный и тёмные оттенки', 'min_size' => array('w' => 600, 'h' => 200), 'exts' => 'png'),
            'ps_src_3'   => array('title' => 'Исходник на одежду, серые', 'min_size' => array('w' => 600, 'h' => 200), 'exts' => 'png'),
            'ps_src_4'   => array('title' => 'Исходник на одежду, белый', 'min_size' => array('w' => 600, 'h' => 200), 'exts' => 'png'),
            'phones'     => array('title' => 'Исходник для телефонов', 'min_size' => array('w' => 600, 'h' => 800), 'src' => [1280, 2090], 'exts' => 'png,jpeg,jpe,jpg'),
            'laptops'    => array('title' => 'Исходник для ноутбуков', 'min_size' => array('w' => 2150, 'h' => 1500), 'src' => [4600, 3100], 'exts' => 'png,jpeg,jpe,jpg'),
            'touchpads'  => array('title' => 'Исходник для планшетов', 'min_size' => array('w' => 1100, 'h' => 1450), 'src' => [2200, 2900], 'exts' => 'png,jpeg,jpe,jpg'),
            'poster'     => array('title' => 'Исходник для постеров', 'min_size' => array('w' => 2100, 'h' => 3000), 'exts' => 'png,jpeg,jpe,jpg'),
            'auto'       => array('title' => 'Исходник для наклеек на автомобиль', 'exts' => 'ai,eps,rar,zip'),
            'boards'     => array('title' => 'Исходник для наклеек на доски', 'min_size' => array('w' => 5900, 'h' => 6300), 'exts' => 'png,jpeg,jpe,jpg'),
            'sticker'    => array('title' => 'Стикерсет с вылетом и тенью', 'exts' => 'png,jpeg,jpe,jpg'),
            'as_oncar_0' => array('title' => 'Превью на автомобиле #1', 'exts' => 'png,jpeg,jpe,jpg'),
            'as_oncar_1' => array('title' => 'Превью на автомобиле #2', 'exts' => 'png,jpeg,jpe,jpg'),
            'as_oncar_2' => array('title' => 'Превью на автомобиле #3', 'exts' => 'png,jpeg,jpe,jpg'),
            'as_oncar_3' => array('title' => 'Превью на автомобиле #4', 'exts' => 'png,jpeg,jpe,jpg'),
            'as_oncar_4' => array('title' => 'Превью на автомобиле #5', 'exts' => 'png,jpeg,jpe,jpg'),
            'stickerset' => array('title' => 'Исходник для стикерсета', 'exts' => 'png,jpeg,jpe,jpg,ai,zip,rar,eps'),
            'stickerset_preview' => array('title' => 'Превью для стикерсета', 'exts' => 'png,jpeg,jpe,jpg'),
            'patterns'   => array(
                 'title' => 'Исходник для носителей с полной запечаткой', 
                 'min_size' => array('w' => 2700, 'h' => 3800), 
                 'src' => array('w' => 5900, 'h' => 6300),
                 'exts' => 'png,jpeg,jpe,jpg'
            ),
            'patterns_back' => array(
                 'title' => 'Исходник для носителей с полной запечаткой (спинка)', 
                 'min_size' => array('w' => 2700, 'h' => 3800), 
                 'src' => array('w' => 5900, 'h' => 6300),
                 'exts' => 'png,jpeg,jpe,jpg'
            ),
            'patterns_bag'   => array(
                 'title' => 'Исходник для сумок с полной запечаткой', 
                 'min_size' => array('w' => 1600, 'h' => 1800), 
                 'src' => array('w' => 4720, 'h' => 5430),
                 'exts' => 'png,jpeg,jpe,jpg'
            ),
            'cup'        => [
                 'title' => 'Исходник для кружек', 
                 'min_size' => ['w' => 2680, 'h' => 1090],
                 'src' => array('w' => 2680, 'h' => 1090), 
                 'exts' => 'png,jpeg,jpe,jpg',
            ],
            'stickers'   => ['title' => 'Исходник для наклеек', 'min_size' => ['w' => 600, 'h' => 200], 'exts' => 'png,jpeg,jpe,jpg'],
        );
        
        
        public static $denyReasone = array(
            55 => array('title' => 'Повтор работы', 'tpl' => 23),
            15 => array('title' => 'Не соблюдены техтребования при загрузке (читайте внимательнее)', 'tpl' => 23),
            63 => array('title' => 'Не соблюдены общие требования к присылаемым работам', 'tpl' => 23),
            9  => array('title' => 'Диспропорционально растянутая картинка', 'tpl' => 23),
            64 => array('title' => 'Удалите фон на футболке http://www.maryjane.ru/faq/group/12/', 'tpl' => 23),
            61 => array('title' => 'Превью не соответствует изображению', 'tpl' => 23),
            62 => array('title' => 'У работ с одним названием должен быть один макет, если Вы хотите загрузить два разных макета, начните сначала', 'tpl' => 23),
            10 => array('title' => 'Качество изображения слабое (dpi) или артефакты после компрессии. Технические дефекты воспроизведения (возможно: неаккуратная отрисовка, автотрейс, неаккуратно очищенный скан и т. п.)', 'tpl' => 23),
            14 => array('title' => 'Запрещённая законами РФ тематика', 'tpl' => 545),
            65 => array('title' => 'Пожалуйста, прочтите статьи в разделе блога "Обучение" http://www.maryjane.ru/blog/teaching/. Обдумайте и присылайте снова.', 'tpl' => 23),
        );
        
        function __construct($id)
        {
            $this->id = (int) $id;
            
            if (!empty($this->id))
            {
                $r = App::db()->query(sprintf("SELECT g.*, u.`user_login`, u.`user_name`, MAX(gw.`place`) AS place, c.`hex` AS bg, c.`name` AS bg_name
                          FROM 
                            `good_stock_colors` c, 
                            `goods` AS g
                                LEFT JOIN `users` AS u ON g.`user_id` = u.`user_id`
                                LEFT JOIN `good_winners` AS gw ON g.`good_id` = gw.`good_id` 
                          WHERE g.`good_id` = '%d' AND g.`ps_onmain_id` = c.`id`
                          GROUP BY g.`good_id` 
                          LIMIT 1", $this->id));
                
                if ($r->rowCount() == 1) 
                {
                    $this->info = $r->fetch();
                    
                    $this->info['good_name']          = stripslashes($this->info['good_name']);
                    $this->info['good_description']   = stripslashes($this->info['good_description']);
                    $this->info['good_admin_comment'] = stripslashes($this->info['good_admin_comment']);
                    $this->info['user_login']         = str_replace('.livejournal.com', '', stripslashes($this->info['user_login']));
                    
                    return $this->info;
                } 
                else 
                    throw new Exception ('good ' . $this->id . ' not found');
            }
        }
        
        /**
         * Сохранить текущий экземпляр объекта в базу
         */
        public function save()
        {
            foreach ($this->info as $k => $v) {
                $rows[$k] = "`$k` = '" . addslashes($v) . "'";
            }
            
            // вырезаем все поля которых нет в схеме таблицы
            $r = App::db()->query(sprintf("SHOW COLUMNS FROM `%s`", self::$dbtable));
            
            foreach ($r->fetchAll() AS $f) {
                $fields[$f['Field']] = $f['Field'];
            }
            
            $rows = array_intersect_key($rows, $fields);
            // end вырезаем все поля которых нет в схеме таблицы
            
            // редактирование
            if (!empty($this->id))
            {
                $rows['good_modif_date'] = "`good_modif_date` = '" . date("Y-m-d H-i-s") ."'";
                App::db()->query(sprintf("UPDATE `%s` SET %s WHERE `good_id` = '%d' LIMIT 1", self::$dbtable, implode(',', $rows), $this->id));
            }
            // создание
            else
            {
                App::db()->query(sprintf("INSERT INTO `%s` SET %s", self::$dbtable, implode(',', $rows)));
                $this->id = App::db()->lastInsertId();
            }
        }
        
        
        /**
         * проверка авторства работы 
         */
        function valid($uid)
        {
            return $this->info['user_id'] == $uid;
        }
        
        public function getuser()
        {
            if (!empty($this->user_id)) 
            {
                $this->info['user'] = new user($this->user_id);
                return $this->info['user'];
            }
        }
        
        /**
         * синоним функции getPictures
         */
        function getPics($names = null)
        {
            return $this->getPictures($names);
        }
        
        /**
         * Получить список картинок работы
         * @param array $names массив имён картинок
         */
        function getPictures($names = null)
        {
            $r = App::db()->query("SELECT gp.`pic_name`, gp.`pic_id`, gp.`pic_w`, gp.`pic_h`, gp.`update_time`, gp.`upload_time` , p.`picture_path` FROM `good_pictures` gp, `pictures` p WHERE gp.`good_id` = '" . $this->id . "' " . ((count($names) > 0) ? "AND gp.`pic_name` IN ('" . implode("','", $names) . "')" : '') . " AND ABS(gp.`pic_id`) = p.`picture_id`");

            foreach ($r->fetchAll() AS $p) 
            {
                // картинки на тачку
                if (strpos($p['pic_name'], 'as_oncar') !== false) 
                {
                    $k = intval(str_replace('as_oncar_', '', str_replace('_preview', '', $p['pic_name']))); 
        
                    if (in_array($p['pic_name'], array('as_oncar_0', 'as_oncar_1', 'as_oncar_2', 'as_oncar_3', 'as_oncar_4'))) {
                        $this->pics['as_oncar'][$k]['id']     = $p['pic_id'];
                        $this->pics['as_oncar'][$k]['path']   = $p['picture_path'];
                        $this->pics['as_oncar'][$k]['update'] = ($p['update_time'] != '0000-00-00 00:00:00') ? TRUE : FALSE;
                        $this->pics['as_oncar'][$k]['update_timestamp']  = ($p['update_time'] != '0000-00-00 00:00:00') ? strtotime($p['update_time']) : 0;
                        $this->pics['as_oncar'][$k]['file']   = substr(basename($p['picture_path']), 11);
                    }
                    else {
                        if (strpos($p['pic_name'], '_preview') !== false)
                          $this->pics['as_oncar'][$k]['preview'] = $p['picture_path'];
                    }
                    
                }
                /*
                elseif ($p['pic_name'] == 'as_sticker_small')
                {
                    $this->pics['as_oncar'][10]['id']      = $p['pic_id'];
                    $this->pics['as_oncar'][10]['preview'] = $p['picture_path'];
                }
                elseif ($p['pic_name'] == 'sticker')
                {
                    $this->pics[$p['pic_name']]['id']    = $p['pic_id'];
                    $this->pics[$p['pic_name']]['path']  = $p['picture_path'];
                    $this->pics[$p['pic_name']]['file']  = substr(basename($p['picture_path']), 11);
                    $this->pics[$p['pic_name']]['pic_w'] = $p['pic_w'];
                    $this->pics[$p['pic_name']]['pic_h'] = $p['pic_h'];
                    
                    if (!$this->pics['as_oncar'][10])
                        $this->pics['as_oncar'][10] = $this->pics[$p['pic_name']];
                    else
                        $this->pics['as_oncar'][10] = array_merge($this->pics['as_oncar'][10], $this->pics[$p['pic_name']]);
                }
                */
                // октрытка
                elseif (strpos($p['pic_name'], 'postcards') !== false)
                {
                    $k = intval(str_replace('postcards_', '', str_replace('_preview', '', $p['pic_name'])));
                    
                    if (strpos($p['pic_name'], '_preview') !== false) {
                        $this->pics['postcards'][$k]['preview'] = $p['picture_path'];
                    } elseif (strpos($p['pic_name'], '_medium') !== false) {
                        $this->pics['postcards'][$k]['medium'] = $p['picture_path'];
                    } else {
                        $this->pics['postcards'][$k]['id']     = $p['pic_id'];
                        $this->pics['postcards'][$k]['path']   = $p['picture_path'];
                        $this->pics['postcards'][$k]['file']   = substr(basename($p['picture_path']), 11);
                    }
                }
                else 
                {
                     // цветные исходники
                    if (in_array($p['pic_name'], array('ps_src_1', 'ps_src_2', 'ps_src_3', 'ps_src_4')))
                    {
                        $this->pics['ps_src_colors'][$p['pic_name']]['path']   = $p['picture_path'];
                        $this->pics['ps_src_colors'][$p['pic_name']]['id']     = $p['pic_id'];
                        $this->pics['ps_src_colors'][$p['pic_name']]['file']   = substr(basename($p['picture_path']), 11);
                        $this->pics['ps_src_colors'][$p['pic_name']]['update'] = ($p['update_time'] != '0000-00-00 00:00:00') ? TRUE : FALSE;
                        $this->pics['ps_src_colors'][$p['pic_name']]['bg']     = self::$color_bg[$p['pic_name']];
                    }

                    // цветные исходники, превью на футболках
                    if (in_array($p['pic_name'], array('ps_src_1_preview', 'ps_src_2_preview', 'ps_src_3_preview', 'ps_src_4_preview')))
                    {
                        
                        $this->pics['ps_src_colors'][str_replace('_preview', '', $p['pic_name'])]['preview'] = $p['picture_path'];
                    }
                    
                    // цветные исходники, зум
                    if (in_array($p['pic_name'], array('ps_src_1_big', 'ps_src_2_big', 'ps_src_3_big', 'ps_src_4_big')))
                    {
                        $this->pics['ps_src_colors'][str_replace('_big', '', $p['pic_name'])]['big'] = $p['picture_path'];
                    }
                    
                    if (strpos($p['pic_name'], 'catalog_preview') !== false)
                    {
                        if ($p['pic_id'] > 0)
                            $this->pics['catalog_preview'][str_replace('catalog_preview_', '', $p['pic_name'])] = $p['picture_path'];
                    }
                    
                    $this->pics[$p['pic_name']]['id']     = $p['pic_id'];
                    $this->pics[$p['pic_name']]['path']   = $p['picture_path'];
                    $this->pics[$p['pic_name']]['file']   = substr(basename($p['picture_path']), 11);
                    $this->pics[$p['pic_name']]['update'] = ($p['update_time'] != '0000-00-00 00:00:00') ? TRUE : FALSE;
                    $this->pics[$p['pic_name']]['upload_time']  = $p['upload_time'];
                    $this->pics[$p['pic_name']]['update_time']  = $p['update_time'];
                    $this->pics[$p['pic_name']]['update_timestamp']  = ($p['update_time'] != '0000-00-00 00:00:00') ? strtotime($p['update_time']) : 0;
                    $this->pics[$p['pic_name']]['pic_w']  = $p['pic_w'];
                    $this->pics[$p['pic_name']]['pic_h']  = $p['pic_h'];
                    
                    if (strpos($p['pic_name'], '_big') !== FALSE)
                    {
                        $this->pics[str_replace('_big', '', $p['pic_name'])]['big']   = $p['picture_path'];
                    }
                }
            }

            // сортируем картинки на авто
            if ($this->pics['as_oncar'])
            {
                ksort($this->pics['as_oncar']);
            }

            // основной зум
            $this->info['big'] = $this->pics['ps_740x']['path'];
            
            // выключаем основной исходник если все дополнительные выключены
            if ($this->pics['ps_src_colors']['ps_src_1']['pic_id'] < 0 && $this->pics['ps_src_colors']['ps_src_2']['pic_id'] < 0 && $this->pics['ps_src_colors']['ps_src_3']['pic_id'] < 0 && $this->pics['ps_src_colors']['ps_src_4']['pic_id'] < 0)
                $this->pics['ps_src']['id'] = abs($this->pics['ps_src']['id']) * -1; 
            
            return $this->pics;
        }

        public function getTexts()
        {
            $sth = App::db()->prepare("SELECT * FROM `good__texts` WHERE `good_id` = :good");
            
            $sth->execute(array(
                'good' => $this->id,
            ));
            
            $this->texts = array();
            
            foreach ($sth->fetchAll() AS $t) 
            {
                $this->texts[$t['side']][] = $t;
            }
            
            return $this->texts;
        }
        
        /**
         * Получить список картинок работы
         * @param array $names массив имён картинок
         */
        function getPs($names)
        {
            $r = App::db()->query("SELECT gp.`pic_name`, gp.`pic_id`, gp.`pic_w`, gp.`pic_h`, gp.`update_time`, gp.`upload_time` , p.`picture_path` FROM `good_pictures` gp, `pictures` p WHERE gp.`good_id` = '" . $this->id . "' " . ((count($names) > 0) ? "AND gp.`pic_name` IN ('" . implode("','", $names) . "')" : '') . " AND ABS(gp.`pic_id`) = p.`picture_id`");

            foreach ($r->fetchAll() AS $p) 
            {
                // картинки на тачку
                if (strpos($p['pic_name'], 'as_oncar') !== false) 
                {
                    $k = intval(str_replace('as_oncar_', '', str_replace('_preview', '', $p['pic_name']))); 
        
                    if (strpos($p['pic_name'], '_preview') === false) {
                        $this->pics['as_oncar'][$k]['id']     = $p['pic_id'];
                        $this->pics['as_oncar'][$k]['path']   = $p['picture_path'];
                        $this->pics['as_oncar'][$k]['update'] = ($p['update_time'] != '0000-00-00 00:00:00') ? TRUE : FALSE;
                        $this->pics['as_oncar'][$k]['file']   = substr(basename($p['picture_path']), 11);
                    } else {
                        $this->pics['as_oncar'][$k]['preview'] = $p['picture_path'];
                    }
                }
                /*
                elseif ($p['pic_name'] == 'as_sticker_small')
                {
                    $this->pics['as_oncar'][10]['id']      = $p['pic_id'];
                    $this->pics['as_oncar'][10]['preview'] = $p['picture_path'];
                }
                elseif ($p['pic_name'] == 'sticker')
                {
                    $this->pics[$p['pic_name']]['id']    = $p['pic_id'];
                    $this->pics[$p['pic_name']]['path']  = $p['picture_path'];
                    $this->pics[$p['pic_name']]['file']  = substr(basename($p['picture_path']), 11);
                    $this->pics[$p['pic_name']]['pic_w'] = $p['pic_w'];
                    $this->pics[$p['pic_name']]['pic_h'] = $p['pic_h'];
                    
                    if (!$this->pics['as_oncar'][10])
                        $this->pics['as_oncar'][10] = $this->pics[$p['pic_name']];
                    else
                        $this->pics['as_oncar'][10] = array_merge($this->pics['as_oncar'][10], $this->pics[$p['pic_name']]);
                }
                */
                // октрытка
                elseif (strpos($p['pic_name'], 'postcards') !== false)
                {
                    $k = intval(str_replace('postcards_', '', str_replace('_preview', '', $p['pic_name'])));
                    
                    if (strpos($p['pic_name'], '_preview') !== false) {
                        $this->pics['postcards'][$k]['preview'] = $p['picture_path'];
                    } elseif (strpos($p['pic_name'], '_medium') !== false) {
                        $this->pics['postcards'][$k]['medium'] = $p['picture_path'];
                    } else {
                        $this->pics['postcards'][$k]['id']     = $p['pic_id'];
                        $this->pics['postcards'][$k]['path']   = $p['picture_path'];
                        $this->pics['postcards'][$k]['file']   = substr(basename($p['picture_path']), 11);
                    }
                }
                else 
                {
                     // цветные исходники
                    if (in_array($p['pic_name'], array('ps_src_1', 'ps_src_2', 'ps_src_3', 'ps_src_4')))
                    {
                        $this->pics['ps_src_colors'][$p['pic_name']]['path']   = $p['picture_path'];
                        $this->pics['ps_src_colors'][$p['pic_name']]['id']     = $p['pic_id'];
                        $this->pics['ps_src_colors'][$p['pic_name']]['file']   = substr(basename($p['picture_path']), 11);
                        $this->pics['ps_src_colors'][$p['pic_name']]['update'] = ($p['update_time'] != '0000-00-00 00:00:00') ? TRUE : FALSE;
                        $this->pics['ps_src_colors'][$p['pic_name']]['bg']     = self::$color_bg[$p['pic_name']];
                    }

                    // цветные исходники, превью на футболках
                    if (in_array($p['pic_name'], array('ps_src_1_preview', 'ps_src_2_preview', 'ps_src_3_preview', 'ps_src_4_preview')))
                    {
                        
                        $this->pics['ps_src_colors'][str_replace('_preview', '', $p['pic_name'])]['preview'] = $p['picture_path'];
                    }
                    
                    // цветные исходники, зум
                    if (in_array($p['pic_name'], array('ps_src_1_big', 'ps_src_2_big', 'ps_src_3_big', 'ps_src_4_big')))
                    {
                        $this->pics['ps_src_colors'][str_replace('_big', '', $p['pic_name'])]['big'] = $p['picture_path'];
                    }
                    
                    if (strpos($p['pic_name'], 'catalog_preview') !== false)
                    {
                        if ($p['pic_id'] > 0)
                            $this->pics['catalog_preview'][str_replace('catalog_preview_', '', $p['pic_name'])] = $p['picture_path'];
                    }
                    
                    $this->pics[$p['pic_name']]['id']     = $p['pic_id'];
                    $this->pics[$p['pic_name']]['path']   = $p['picture_path'];
                    $this->pics[$p['pic_name']]['file']   = substr(basename($p['picture_path']), 11);
                    $this->pics[$p['pic_name']]['update'] = ($p['update_time'] != '0000-00-00 00:00:00') ? TRUE : FALSE;
                    $this->pics[$p['pic_name']]['upload_time']  = $p['upload_time'];
                    $this->pics[$p['pic_name']]['update_time']  = $p['update_time'];
                    $this->pics[$p['pic_name']]['update_timestamp']  = ($p['update_time'] != '0000-00-00 00:00:00') ? strtotime($p['update_time']) : 0;
                    $this->pics[$p['pic_name']]['pic_w']  = $p['pic_w'];
                    $this->pics[$p['pic_name']]['pic_h']  = $p['pic_h'];
                    
                    if (strpos($p['pic_name'], '_big') !== FALSE)
                    {
                        $this->pics[str_replace('_big', '', $p['pic_name'])]['big']   = $p['picture_path'];
                    }
                }
            }

            // сортируем картинки на авто
            if ($this->pics['as_oncar'])
            {
                ksort($this->pics['as_oncar']);
            }

            // основной зум
            $this->info['big'] = $this->pics['ps_740x']['path'];
            
            // выключаем основной исходник если все дополнительные выключены
            if ($this->pics['ps_src_colors']['ps_src_1']['pic_id'] < 0 && $this->pics['ps_src_colors']['ps_src_2']['pic_id'] < 0 && $this->pics['ps_src_colors']['ps_src_3']['pic_id'] < 0 && $this->pics['ps_src_colors']['ps_src_4']['pic_id'] < 0)
                $this->pics['ps_src']['id'] = abs($this->pics['ps_src']['id']) * -1; 
            
            return $this->pics;
        }
        
        /**
         * Получить список лайков работы
         * @param array массив c номерами лайкнувших юзеров
         */
        function getLikes($nagative = 0)
        {   
            $r = App::db()->query(sprintf("SELECT `user_id` FROM `good_likes` WHERE `good_id` = '%d' AND `negative` = '0'", $this->id));
            
            foreach ($r->fetchAll() AS $l) {
                $this->likes[$l['user_id']]++; 
            }
            
            return $this->likes;
        }
        
        /**
         * Поставить / убрать лайк работе
         * @param int $u - пользователь
         * @param string $pic - картинка которую лайкают у работы
         * @param int $negative - лайк или дизлайк
         */
        function like($u, $pic, $negative = 0)
        {
            if (empty($u) || $u < 0)
                throw new Exception('Вы должны быть авторизованы что бы проголосовать за работу', 1);
            
            if ($u == $this->user_id)
                throw new Exception('Вы не можете добавить свою работу в избранные', 1);
            
            try
            {
                $User = new user($u);
                
                $pic = addslashes(trim($pic));
                $negative = intval($negative);
                
                $r = App::db()->query("SELECT `id`, `negative` FROM `good_likes` WHERE `good_id` = '" . $this->id . "' AND `user_id` = '" . $User->id . "' AND `pic_name` = '" . $pic . "'");
                
                // лайк за эту работу уже ставили
                if ($r->rowCount() > 0)
                {
                    $like = $r->fetch();
                    
                    App::db()->query("DELETE FROM `good_likes` WHERE `good_id` = '" . $this->id . "' AND `user_id` = '" . $User->id . "' AND `pic_name` = '" . $pic . "'");
            
                    if ($like['negative'] != $negative) 
                    {
                        App::db()->query("INSERT INTO `good_likes` 
                                    SET 
                                        `good_id`  = '" . $this->id . "',
                                        `pic_name` = '" . $pic . "',
                                        `negative` = '" . $negative . "',
                                        `user_id`  = '" . $User->id . "', 
                                        `user_ip`  = INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "')");
        
                        if ($User->user_is_fake == 'false')
                        {
                            if (!$negative) {
                                // увеличиваем кол-во лайков работы
                                $this->change(array(
                                    'good_likes' => $this->good_likes + 1
                                ));
                            } else {
                                // уменьшаем кол-во лайков работы
                                $this->change(array(
                                    'good_likes' => $this->good_likes - 1
                                ));
                            }
                        }
                        
                        // если работа на голосовании, то ставим оценку 5
                        if ($this->good_status == 'voting')
                        {
                            App::db()->query("INSERT IGNORE
                                            `" . self::$votetable . "`
                                         SET 
                                            `good_id` = '" . $this->id . "',
                                            `user_id` = '" . $User->id . "',
                                            `vote` = '" . (($negative) ? 0 : 5) . "',
                                            `user_ip` = INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "')
                                         ON DUPLICATE KEY UPDATE 
                                            `vote` = '" . (($negative) ? 0 : 5) . "',
                                            `user_ip` = INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "'),
                                            `time` = NOW()");
                        }
                        
                        $result = 'changed';
                    }
                    else 
                    {
                        if (!$like['negative']) {
                            // уменьшаем кол-во лайков работы
                            if ($User->user_is_fake == 'false') {
                                $this->change(array(
                                    'good_likes' => $this->good_likes - 1
                                )); 
                            }
                        }
                        
                        $result = 'deleted';
                    }
                }
                // "новый" лайк
                else
                {
                    App::db()->query("INSERT INTO `good_likes` 
                                SET 
                                    `good_id`  = '" . $this->id . "',
                                    `pic_name` = '" . $pic . "',
                                    `negative` = '" . $negative . "',
                                    `user_id`  = '" . $User->id . "', 
                                    `user_ip`  = INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "')");
        
                    if (!$negative) 
                    {
                        // увеличиваем кол-во лайков работы (голоса от фейков не засчитываются)
                        if ($User->user_is_fake == 'false') {
                            $this->change(array(
                                'good_likes' => $this->good_likes + 1
                            ));
                        }
                    }
                    
                    // если работа на голосовании, то ставим оценку 5
                    if ($this->good_status == 'voting')
                    {
                        App::db()->query("INSERT IGNORE
                                        `" . self::$votetable . "`
                                     SET 
                                        `good_id` = '" . $this->id . "',
                                        `user_id` = '" . $User->id . "',
                                        `vote` = '" . (($negative) ? 0 : 5) . "',
                                        `user_ip` = INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "')
                                     ON DUPLICATE KEY UPDATE 
                                        `vote` = '" . (($negative) ? 0 : 5) . "',
                                        `user_ip` = INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "'),
                                        `time` = NOW()");
                    }
                    
                    $result = 'added';
                }
            }
            catch (Exception $e)
            {
                throw new Exception($e->getMessage(), 1);
            }
            
            return $result;
        }
        
        /**
         * Поставить / убрать лайк работе
         * @param obj $User - пользователь
         * @param string $pic - картинка которую лайкают у работы
         * @param int $negative - лайк или дизлайк
         */
        function likev2($User, $pic, $negative = 0)
        {
            if (empty($User->id))
                throw new Exception('Вы должны быть авторизованы что бы проголосовать за работу', 1);
            
            if ($User->id == $this->user_id)
                throw new Exception('Вы не можете добавить свою работу в избранные', 1);
            
            try
            {
                $pic = addslashes(trim($pic));
                $negative = intval($negative);
                
                $r = App::db()->query("SELECT `id`, `negative`, `pic_name` FROM `good_likes` WHERE `good_id` = '" . $this->id . "' AND `user_id` = '" . $User->id . "'");
                
                foreach ($r->fetchAll() AS $l)
                {
                    $likes[$l['pic_name']] = $l;
                }
                
                // лайк за эту картинку работы уже ставили
                if ($likes[$pic])
                {
                    App::db()->query("DELETE FROM `good_likes` WHERE `good_id` = '" . $this->id . "' AND `user_id` = '" . $User->id . "' AND `pic_name` = '" . $pic . "'");
            
                    // если меняем лайку на противоположную, нравилось-ненравиться и наоборот
                    if ($likes[$pic]['negative'] != $negative) 
                    {
                        App::db()->query("INSERT INTO `good_likes` 
                                    SET 
                                        `good_id`  = '" . $this->id . "',
                                        `pic_name` = '" . $pic . "',
                                        `negative` = '" . $negative . "',
                                        `user_id`  = '" . $User->id . "', 
                                        `user_ip`  = INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "')");
        
                        // если работа на голосовании, то ставим оценку 5
                        if ($this->good_status == 'voting' && $pic == 'good_preview')
                        {
                            App::db()->query("INSERT IGNORE
                                            `" . self::$votetable . "`
                                         SET 
                                            `good_id` = '" . $this->id . "',
                                            `user_id` = '" . $User->id . "',
                                            `vote` = '" . (($negative) ? 0 : 5) . "',
                                            `user_ip` = INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "')
                                         ON DUPLICATE KEY UPDATE 
                                            `vote` = '" . (($negative) ? 0 : 5) . "',
                                            `user_ip` = INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "'),
                                            `time` = NOW()");
                        }
                        
                        $likes[$pic]['negative'] = $negative;
                        
                        if ($negative == 0)
                            $result = 'changedPlus';
                        else
                            $result = 'changedMinus';
                    }
                    else 
                    {
                        unset($likes[$pic]);
                        
                        $result = 'deleted';
                    }
                }
                // "новый" лайк
                else
                {
                    App::db()->query("INSERT INTO `good_likes` 
                                SET 
                                    `good_id`  = '" . $this->id . "',
                                    `pic_name` = '" . $pic . "',
                                    `negative` = '" . $negative . "',
                                    `user_id`  = '" . $User->id . "', 
                                    `user_ip`  = INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "')");
                    
                    $likes[$pic] = array('pic' => $pic, 'negitive' => $negative);
                    
                    // если работа на голосовании, то ставим оценку 5
                    if ($this->good_status == 'voting' && $pic == 'good_preview')
                    {
                        App::db()->query("INSERT IGNORE
                                        `" . self::$votetable . "`
                                     SET 
                                        `good_id` = '" . $this->id . "',
                                        `user_id` = '" . $User->id . "',
                                        `vote` = '" . (($negative) ? 0 : 5) . "',
                                        `user_ip` = INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "')
                                     ON DUPLICATE KEY UPDATE 
                                        `vote` = '" . (($negative) ? 0 : 5) . "',
                                        `user_ip` = INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "'),
                                        `time` = NOW()");
                    }
                    
                    $result = 'added';
                }
                
                // уменьшаем/увеличиваем итоговое кол-во лайков работы
                if ($User->user_is_fake == 'false')
                {
                    $plus = 0; // кол-во положительных лайков
                    
                    foreach ($likes AS $l) 
                    {
                        if ($l['negative'] == 0)
                            $plus++;
                    }
                    
                    if ($plus == 1 && ($result == 'added' || $result == 'changedPlus')) {
                        // увеличиваем кол-во лайков работы
                        $this->change(array(
                            'good_likes' => $this->good_likes + 1
                        ));
                    } elseif ($plus == 0) {
                        // уменьшаем кол-во лайков работы
                        $this->change(array(
                            'good_likes' => $this->good_likes - 1
                        ));
                    }
                }
            }
            catch (Exception $e)
            {
                throw new Exception($e->getMessage(), 1);
            }
            
            return $result;
        }
        
        /**
         * Удалить лайк
         * @param int $u - пользователь
         * @param string $pic - картинка которую анлайкают у работы
         */
        function unlike($u, $pic)
        {
            $pic = addslashes(trim($pic));
            $u = intval($u);
            
            App::db()->query("DELETE FROM `good_likes` WHERE `good_id` = '" . $this->id . "' AND `user_id` = '" . $u . "' AND `pic_name` = '" . $pic . "'");
        }
    
        /**
         * Получить теги работы
         */
        function gettags()
        {
            $r = App::db()->query(sprintf("SELECT t.* FROM `tags` AS t, `tags_relationships` AS  tr WHERE tr.`object_id` = '%d' AND t.`tag_id` = tr.`tag_id` AND tr.`object_type` = '1' AND t.`tag_id` NOT IN ('13775')", $this->id));
            
            $this->tags = array();
            
            foreach ($r->fetchAll() AS $tag)
            {
                $tag['name'] = stripslashes($tag['name']);
                $this->tags[] = $tag;
            }
            
            return $this->tags;
        }
        
        function addTag($name)
        {
            $sth = App::db()->prepare("SELECT `tag_id` FROM `tags` WHERE `name` = :name LIMIT 1");
            
            $sth->execute(array('name' => $name));
            
            if (!$tag = $sth->fetch())
            {
                $sth = App::db()->prepare("INSERT INTO `tags` SET `name` = :name, `slug` = :slug");
                
                $sth->execute(array(
                    'name' => $name,
                    'slug' => toTranslit($name),
                ));
                
                $tag = array('tag_id' => App::db()->lastInsertId());
            }
            
            if ($this->id > 0)
            {
                $sth = App::db()->prepare("INSERT INTO `tags_relationships` SET `tag_id` = :tag, `object_id` = :good, `object_type` = '1'");
                
                $sth->execute(array(
                    'tag' => $tag['tag_id'],
                    'good' => $this->id,
                ));
            }
            else 
            {
            }
            
            return $tag['tag_id'];
        }
        
        function removeTags($tags)
        {
            App::db()->query("DELETE FROM `tags_relationships` WHERE `object_id` = '" . $this->id . "' AND `object_type` = '1'");
        }

        /**
         * Сбросить кэш группы носителей
         * 
         * @param array $style_id - массив конкретных носителей
         */
        function dropCache($styles)
        {
            foreach ((array) $styles as $k => $style_id) 
            {
                $s = new style($style_id);
                
                $time_mark = $this->pics[styleCategory::$BASECATS[$s->category]['src_name']]['update_timestamp'];
                
                file::deleteFromCloud('cache.maryjane.ru', '/' . $s->category . '/' . $s->id . '/' . $this->id . '.' . $time_mark . '.jpeg');
                file::deleteFromCloud('cache.maryjane.ru', '/' . $s->category . '/' . $s->id . '/' . $this->id . '.model.' . $time_mark . '.jpeg');
            }
        }

        /**
         * Удалить исходник работы
         * 
         * @param string $name название исходника
         */
        function deleteSrc($name)
        {
            if (!$pic = $this->pics[$name])
            {
                if (strpos($name, 'as_oncar_') === 0)
                    if (!$pic = $this->pics['as_oncar'][str_replace('as_oncar_', '', $name)])
                        return;
            }
            
            $arr = array($name); 
            
            array_push($arr, $name . '_preview');
            array_push($arr, $name . '_big');
            array_push($arr, $name . '.work');
            array_push($arr, $name . '_big_preview');           

            // пишем в лог
            if (in_array($name, array_keys(self::$srcs)))
            {
                $this->log('delete_src', $name, $this->pics[$name]['path']);
            }

            $this->delPic($arr);
            
            switch (trim($name))
            {
                case 'ps_src':
                
                    // удаляем превью для голосования
                    $this->delPic('voting_preview');
                    
                    // удаляем цветные исходники, совпадающие с основным
                    App::db()->query("DELETE FROM `good_pictures` WHERE `good_id` = '" . $this->id . "' AND `pic_name` LIKE 'ps_src_%' AND ABS(`pic_id`) = '" . abs($this->pics['ps_src']['id']) . "' LIMIT 4");
                    
                    // удаляем все превью на носителях для каталога на тряпках
                    $r = App::db()->query(sprintf("SELECT `style_id` FROM `good_stock_colors` c, `styles` s, `styles_category` sc WHERE s.`style_color` = c.`id` AND s.`style_category` = sc.`id` AND sc.`cat_parent` = '1'"));
                    
                    foreach ($r->fetchAll() AS $s) {
                        $styles[] = 'catalog_preview_' . $s['style_id'];
                    }
                    
                    $this->delPic($styles);
                        
                break;
                
                case 'ps_src_1':
                case 'ps_src_2':
                case 'ps_src_3':
                case 'ps_src_4':
                    
                    // на место удалённого цветного исходника пишем основной
                    if (!empty($this->pics['ps_src']['id']))
                        $this->addPic($name, $this->pics['ps_src']['id']);
                    
                break;

                case 'ps_180x184':
                case 'good_preview':

                    $this->change(array('good_visible' => 'false'));
                    return TRUE;
                    
                    break;

                    
                case 'phones':
                case 'laptops':
                case 'touchpads':
                case 'poster':
                
                    $r = App::db()->query(sprintf("SELECT s.`style_id`
                            FROM `styles_category` sc, `styles` s
                            WHERE sc.`cat_parent` IN (%s) AND s.`style_category` = sc.`id`", (($name != 'phones') ? styleCategory::$BASECATS[$name]['id'] : implode(',', array(styleCategory::$BASECATS['phones']['id'], styleCategory::$BASECATS['cases']['id'], styleCategory::$BASECATS['ipodmp3']['id'])))));
                            
                    foreach ($r->fetchAll() AS $s) {
                        $styles[] = 'catalog_preview_' . $s['style_id']; 
                    }
                    
                    $this->delPic($styles);

                    $this->dropCache($name);
                    
                break;
                
                case 'patterns_back':
                    
                    $this->delPic('good_preview_back');
                    
                    break;
                    
                case 'sticker':
                    
                    $this->delPic('as_sticker');
                    $this->delPic('as_sticker_small');
                    
                    break;
                
                default:
                break;
            }
            
            if (in_array($name, array_keys(self::$srcs)))
                $this->delMeta($name . '_tiled');
            
            unset($this->pics[$name]);
            
            // проверяем остались ли у работы включённые исходники
            $needed = array_diff(array_keys(self::$srcs), array('as_oncar_0', 'as_oncar_1', 'as_oncar_2', 'as_oncar_3', 'as_oncar_4', 'sticker'));
            
            foreach ($needed as $p) 
            {
                if ($this->pics[$p]['id'] > 0)
                {
                    if ($this->good_status != 'new' && $this->pics['good_preview'] && $this->good_visible == 'false')
                        $this->change(array('good_visible' => 'true'));
                    
                    return TRUE;
                }
            }
            
            $this->change(array('good_visible' => 'false'));
        }
        
        /**
         * Сгенерировать зум работы из исходника 
         * 740px по ширине, с вотермаркой
         * 
         * @param string $src_path путь до исходника
         * @param int $width ширина картинки
         * @return int id сгенерированной картинки
         */
        public static function generateZoom($src_path, $save = 1, $width = 740)
        {
            $i = new Imagick();
            $i->readImage(ROOTDIR . $src_path); 
            $i->setImageFormat('png32');
            $i->scaleImage($width, 0);
            
            $m = new Imagick();
            $m->newImage($i->getImageWidth(), $i->getImageHeight(), new ImagickPixel('transparent'));
            
            $l = new ImagickDraw();
            $l->setStrokeColor(new ImagickPixel( 'white'));
            $l->line(0,$i->getImageHeight(), $i->getImageWidth(), 0);
            $m->drawImage($l);
            
            $l = new ImagickDraw();
            $l->setStrokeColor(new ImagickPixel( 'white'));
            $l->line(0,0, $i->getImageWidth(), $i->getImageHeight());
            $m->drawImage($l);

            $wm = new Imagick(ROOTDIR . '/images/wm.png');
            
            if (!$_GET['center']) {
                $wm->scaleImage($wm->getImageWidth() * (($i->getImageHeight() / 100 * 35) / $wm->getImageHeight()), $i->getImageHeight() / 100 * 35);
                $m->compositeImage($wm, imagick::COMPOSITE_OVER, $i->getImageWidth() - $wm->getImageWidth() - 10, $i->getImageHeight() - $wm->getImageHeight() - 10);
                
                $wm->scaleImage($wm->getImageWidth() * (($i->getImageHeight() / 100 * 35) / $wm->getImageHeight()), $i->getImageHeight() / 100 * 35);
                $m->compositeImage($wm, imagick::COMPOSITE_OVER, 10, 10);
            } else {
                $wm->scaleImage($wm->getImageWidth() * (($i->getImageHeight() / 100 * 100) / $wm->getImageHeight()), $i->getImageHeight() / 100 * 100);
                $m->compositeImage($wm, imagick::COMPOSITE_OVER, $i->getImageWidth() / 2 - $wm->getImageWidth() / 2, $i->getImageHeight() / 2 - $wm->getImageHeight() / 2);
            }
            
            $i->compositeImage($m, imagick::COMPOSITE_OVER, 0, 0);
            
            if ($save)
            {
                createDir(UPLOADTODAY);
                
                $path = UPLOADTODAY . md5(uniqid()) . '.png';
                $f = fopen(ROOTDIR . $path, 'w+');
                fputs($f, $i, strlen($i));
                fclose($f);
            }
            else 
            {
                header('Content-type: image/png');
                exit($i);
            }
            
            return file2db($path);
        }

        /**
         * Сгенерировать пропорциональное превью работы на цветном фоне
         * 
         * @param int $w ширина картинки
         * @param int $src из какого исходника генерить
         * @param boolean $save сохранять на диск или вывести в поток
         * @return array $r массив в id и путём до получившейся картинки
         */
        function generateGoodPreview($w = 230, $src, $save = 'cloud')
        {
            if (!empty($this->id))
            {
                if (!empty($src)) {
                    if (is_numeric($src))
                        $src = pictureId2path($src);
                }
                else 
                {
                    $srcs = array('poster', 'ps_src', 'phones', 'touchpads', 'laptops', 'pattern', 'cup', 'patterns', 'patterns', 'patterns_bag');
                    
                    // выбор исходника 
                    foreach ($srcs as $s) {
                        if ($this->pics[$s] && $this->pics[$s]['id'] > 0) {
                            $src = $this->pics[$s]['path'];
                            break;
                        }
                    }   
                }
            }
            else
            {
                if (is_numeric($src))
                    $src = pictureId2path($src);
            }
                
            if (!$src)
                throw new Exception('src not found', 1); 
            
            try
            {           
                $s = new Imagick(ROOTDIR . $src);
            } 
            catch (Exception $e) 
            {
                throw new Exception('src open error', 2); 
            }

            if (empty($w))
            {
                $h = 600;
                $w = round(($h / $s->getImageHeight()) * $s->getImageWidth());
            }
            else
                $h = round(($w / $s->getImageWidth()) * $s->getImageHeight());
            
            
            try
            {
                $s->cropThumbnailImage($w, $h);
                
                $p = new Imagick();
                $p->newImage($w, $h, new ImagickPixel('#' . (($this->ps_onmain_id == 18 || !$this->ps_onmain_id) ? 'ffffff' : colorId2hex($this->ps_onmain_id))));
                $p->compositeImage($s, imagick::COMPOSITE_OVER, 0, 0);
            }
            catch (Exception $e) 
            {
                throw new Exception('generate error: ' . $e->getMessage(), 3); 
            }
            
            $p->setImageFormat('jpeg');
            
            if ($save)
            {
                $path = UPLOADTODAY . md5(uniqid()) . '.jpeg';
                
                createDir(UPLOADTODAY);
        
                try
                {
                    $p->writeImage(ROOTDIR . $path);
                    
                    if ($save == 'cloud') {
                        $path = file::move2S3($path);
                    }
                }
                catch (Exception $e) 
                {
                    throw new Exception('save on disc "' . $path . '" error', 4); 
                }
                
                return array(
                        'id'   => file2db($path), 
                        'path' => $path,
                        'w'    => $w,
                        'h'    => $h);
            }
            else
            {
                header('Content-type: image/jpeg');
                exit($p);
            }
        }

        function generatePatternPreview($w = 230, $src, $save = 'cloud')
        {
            if (is_numeric($src)) {
                $src = pictureId2path($src);
            }
                
            if (!$src)
                throw new Exception('src not found', 1); 
            
            try
            {           
                $s = new Imagick(ROOTDIR . $src);
            } 
            catch (Exception $e) 
            {
                throw new Exception('src open error', 2); 
            }
            
            $bw = round($w) / 2;
            $bh = round($s->getImageHeight() * ($bw / $s->getImageWidth()));
            
            $h = $bh * 2;
            
            $s->scaleImage($bw, $bh);
            
            $p = new Imagick();
            $p->newImage($w, $h, new ImagickPixel('#fff'));
            $p = $p->textureImage($s);
            
            $p->setImageFormat('jpeg');
            
            if ($save)
            {
                $path = UPLOADTODAY . md5(uniqid()) . '.jpeg';
                
                createDir(UPLOADTODAY);
        
                try
                {
                    $p->writeImage(ROOTDIR . $path);
                    
                    if ($save == 'cloud') {
                        $path = file::move2S3($path);
                    }
                }
                catch (Exception $e) 
                {
                    throw new Exception('save on disc error', 4); 
                }
                
                return array(
                        'id'   => file2db($path), 
                        'path' => $path,
                        'w'    => $w,
                        'h'    => $h);
            }
            else
            {
                header('Content-type: image/jpeg');
                exit($p);
            }
        }
            
        function generatePatternArtPreview($sid, $path)
        {
            if (!$this->pics['patterns']) {
                throw new Exception('Исходник для паттернов отсутствует', 1);
            }
            
            $style = new style($sid);
            
            if (!$style->pics['mock-right']) {
                throw new Exception('Шаблон для создания изображения отсутствует', 2);
            }
            
            $mw = 765;
            $mh = 353;
            
            $ppp = new Imagick(ROOTDIR . $style->pics['mock-right']['path']);
            
            if (!$this->pics['patterns.work']) {
                $this->generateSrcWorkCopy('patterns');
            }
            
            $patternL = new Imagick(ROOTDIR . $this->pics['patterns.work']['path']);        
            $patternR = new Imagick(ROOTDIR . $this->pics['patterns.work']['path']);
                    
            $patternL->cropThumbnailImage(415, $mh);
            
            // накладываем левую часть
            $ppp->compositeImage($patternL, imagick::COMPOSITE_DSTOVER, 0, 0);
        
            // накладываем правую часть
            $patternR->cropThumbnailImage($style->pics['mock-right']['w'], $style->pics['mock-right']['h']);
            $ppp->compositeImage($patternR, imagick::COMPOSITE_DSTOVER, $mw - $ppp->getImageWidth() + $style->pics['mock-right']['x'] - ($patternR->getImageWidth() / 2), $style->pics['mock-right']['y'] - ($patternR->getImageHeight() / 2));
            
            //$bg = new ImagickPixel('white');
            //$ppp->setImageBackgroundColor($bg);
            $bg = new Imagick;
            $bg->newImage($ppp->getImageWidth(), $ppp->getImageHeight(), new ImagickPixel('white'));
            $ppp->compositeImage($bg, imagick::COMPOSITE_DSTOVER, 0, 0);
            
            if ($path)
            {
                $url = parse_url($path);
                
                if ($url['host']) 
                {
                    $name   = basename($url['path']);
                    $folder = dirname($url['path']) . '/';
                    $path = $folder. $name;
                }
                    
                $ppp->writeImage(ROOTDIR . $path);
                
                if ($url['host']) 
                {
                    $path = file::move2S3($path, $path, $url['host']);
                }
                
                return $path;
            }
            else
            {
                $ppp->setImageFormat('jpeg');
                header('Content-type: image/jpeg');
                exit($ppp);
            }
        }
        
        /**
         * @param int $sid номер носителя
         * @param int $width ширина
         * @param int $height высота
         * @param string $side сторона
         * @param string $path куда положить картинку
         * @param bool $db сохранять в базу или нет
         * @param string $bucket имя конкретного бакета в облаке
         */
        function generatePreview($sid, $width, $height, $side = 'front', $path, $db = true, $bucket, $param)
        {
            $result = array();
            
            if ($this->good_status == 'customize')
                $side = str_replace('_model', '', $side);
            
            // Извлекаем шаблон и всю информацию по носителю для наложения
            // на модели
            if (in_array($side, array('front_model', 'back_model', 'observ', 'observ2')))
            {
                $q = sprintf("SELECT p.`picture_path` AS template, s.`style_id`, s.`style_print_block` AS print_block, s.`style_sex` AS sex, sc.`cat_slug` AS cat, c.`id` AS color_id, c.`group` AS color_group, c.`name`, c.`name_en`, sp.`x`, sp.`y`, sp.`w`, sp.`h`
                              FROM `pictures` AS p, `styles` s, `styles_category` sc, `styles_pictures` sp, `good_stock_colors` AS c
                              WHERE s.`style_id` = '%d' AND sp.`style_id` = s.`style_id` AND p.`picture_id` = sp.`pic_id` AND sp.`pic_name` = '%s' AND s.`style_category` = sc.`id` AND c.`id` = s.`style_color`", $sid, str_replace('_model', '', $side));

                $r = App::db()->query($q);
            }

            // если нет фоток моделей или заказано на майке
            if (($r && $r->rowCount() == 0 ) || !in_array($side, array('front_model', 'back_model')))
            {
                $side = str_replace('_model', '', $side);
                
                // Извлекаем шаблон и всю информацию по носителю для наложения
                $q = sprintf("SELECT p.`picture_path` AS template, s.`style_id`, s.`style_print_block` AS print_block, s.`style_sex` AS sex, sc.`cat_slug` AS cat, c.`id` AS color_id, c.`group` AS color_group, c.`name`, c.`name_en`, gs.`good_stock_id`
                              FROM `pictures` AS p, `styles` s, `styles_category` sc, `good_stock_colors` AS c, `good_stock` AS gs
                              WHERE s.`style_id` = '%d' AND s.`style_id` = gs.`style_id` AND p.`picture_id` = s.`" . (($side == 'front') ? 'style_front_picture' : 'style_back_picture') . "` AND s.`style_category` = sc.`id` AND s.`style_print_block` != '' AND c.`id` = s.`style_color` AND gs.`good_id` = '0' 
                              LIMIT 1", $sid);

                $r = App::db()->query($q);
            }
            
            if ($r->rowCount() == 0)
            {
                throw new Exception('Style "' . $sid . '" not founded', 1);
            }
                          
            foreach ($r->fetchAll() AS $s) {
                $style[] = $s;
            }
            
            //array_pop($style);                // выбрасываем последний пустой элемент
            shuffle($style);                // перемешиваем массив
            extract(array_pop($style));     // берём последний элемент и преобразуем его в переменные
            // end Извлекаем шаблон и всю информацию по носителю для наложения
        
        
            // выбираем нужный исходник
            if ($this->id > 0)
            {
                $this->getPictures(array('ps_src', 'ps_src_back', 'ps_src_1', 'ps_src_2', 'ps_src_3', 'ps_src_4'));
    
                if ($path && $db && $this->pics['ps_src_colors']['ps_src_' . $color_group]['id'] < 0) {
                    throw new Exception('Good ' . $this->id . ' is disabled on ' . $sid, 2);
                }
            
                if ($this->pics['ps_src_colors']['ps_src_' . $color_group]) {
                    if (($side == 'front' || $side == 'front_model') && $this->pics['ps_src_' . $color_group]) 
                        $src_name = 'ps_src_' . $color_group;
                    
                    if (($side == 'back' || $side == 'back_model' || $side == 'model_back') && $this->pics['ps_src_' . $color_group . '_back']) {
                        $src_name = $src_name . '_back';
                    }
                }
                else
                {
                    if (($side == 'front' || $side == 'front_model') && $this->pics['ps_src'])
                        $src_name = 'ps_src';
                    
                    if (($side == 'back' || $side == 'back_model' || $side == 'model_back') && $this->pics['ps_src_back']) {
                        $src_name = 'ps_src_back';
                    }
                }
                
                $src = $this->pics[$src_name]['path'];
                
                if (!$src && $this->good_status != 'customize' && $side != 'back' && $side != 'back_model' && $side != 'model_back')
                {
                    throw new Exception("Src \"" . $src_name . "\" not founded", 3);
                }
            }
            // end выбираем нужный исходник

            try
            {
                $templateImg = new Imagick(ROOTDIR . $template);
            }
            catch (Exception $e)
            {
                throw new Exception('Template file can not be readed', 13);
            }
        
            if ($src)
            {
                try
                {
                    $pictureImg = new Imagick(ROOTDIR . $src);
                    
                    if (empty($this->pics[$src_name]['pic_w']) || empty($this->pics[$src_name]['pic_h'])) {
                        $src_sizes = $pictureImg->getImageGeometry();
                    } else {
                        $src_sizes = array(
                            'width' => $this->pics[$src_name]['pic_w'], 
                            'height' => $this->pics[$src_name]['pic_h']);
                    }
                }
                catch (Exception $e)
                {
                    throw new Exception('Src ' . $src_name . ' "' . $src . '" can not be readed', 12);
                }
            }
            
            // Вычисляем будущие размеры и положение накладываемого изображения
            $print_block = unserialize($print_block);
            
            if (!empty($x) && !empty($y) && !empty($w) && !empty($h))
            {
                $print_block[$side] = array('x' => $x, 'y' => $y, 'w' => $w, 'h' => $h);
            }
            else 
            {
                if (($side == 'front_model' || $side == 'back_model') && (empty($print_block[$side]['x']) || empty($print_block[$side]['y']) || empty($print_block[$side]['w']) || empty($print_block[$side]['h'])))
                {
                    $print_block[$side] = $print_block[str_replace('_model', '', $side)];
                }
            }
        
            // получаем координаты расположения рисунка (если они есть)
            if (!empty($src))
            {
                $r = App::db()->prepare("SELECT `x`, `y`, `w`, `h`, `a`, `good_stock_id`
                      FROM `good__positions`
                      WHERE `good_id` = ? AND (`side` = '{$side}' OR `side` = '" . str_replace('_model', '', $side) . "') AND (`good_stock_id` = '0' OR `good_stock_id` = '{$cat}' OR `good_stock_id` = '" . $sid . "' OR `good_stock_id` = '" . $good_stock_id . "')
                      ORDER BY IF(`side` = '{$side}', 1, 0) ASC");
                      
                $r->execute([$this->id]);
            
                foreach ($r->fetchAll() AS $c)
                {
                    $coo[$c['good_stock_id']] = $c;
                }

                if ($coo[$cat . '_' . $sid])
                    $coords = $coo[$cat . "_" . $sid];
                elseif ($coo[$cat])
                    $coords = $coo[$cat];
                elseif ($coo[$sid])
                    $coords = $coo[$sid];
                elseif ($coo[$good_stock_id])
                    $coords = $coo[$good_stock_id];
                else
                    $coords = $coo[0];
            
                // есть координаты указанные автором
                // сумки всегда прибиваем к центру
                if ($coords && $coords['w'] != 0 && $coords['h'] != 0 && $cat != 'sumki')
                {
                    $w = $coords['w'];
                    $h = $coords['h'];
                    $a = $coords['a'];
            
                    if ($coords['good_stock_id'] == '0')
                    {
                        if ($w > $print_block[$side]['w'])
                        {
                            $w = $print_block[$side]['w'];
                            $h = floor($src_sizes['height'] / ($src_sizes['width'] / $w));
            
                            if ($h > $print_block[$side]['h'])
                            {
                                $h = $print_block[$side]['h'];
                                $w = floor($src_sizes['width'] / ($src_sizes['height'] / $h));
                            }
                        }
                        elseif ($h > $print_block[$side]['h'])
                        {
                            $h = $print_block[$side]['h'];
                            $w = floor($src_sizes['width'] / ($src_sizes['height'] / $h));
            
                            if ($w > $print_block[$side]['w'])
                            {
                                $w = $print_block[$side]['w'];
                                $h = floor($src_sizes['height'] / ($src_sizes['width'] / $w));
                            }
                        }
                    }
            
                    switch ($coords['x']) {
                        case 'left':
                            $x = $print_block[$side]['x'] - ($print_block[$side]['w'] / 2);
                            break;
            
                        case 'center':
                            $x = $print_block[$side]['x'] - ($w / 2);
                            break;
            
                        case 'right':
                            $x = $print_block[$side]['x'] + ($print_block[$side]['w'] / 2) - $w;
                            break;
            
                        default:
                            $x = ($print_block[$side]['x'] - round($print_block[$side]['w'] / 2)) + $coords['x'];
                            break;
                    }
            
                    switch ($coords['y']) {
                        case 'top':
                            $y = $print_block[$side]['y'] - ($print_block[$side]['h'] / 2);
                            break;
            
                        case 'center':
                            $y = $print_block[$side]['y'] - ($h / 2);
                            break;
            
                        case 'bottom':
                            $y = $print_block[$side]['y'] + ($print_block[$side]['h'] / 2) - $h;
                            break;
            
                        default:
                            $y = ($print_block[$side]['y'] - round($print_block[$side]['h'] / 2)) + $coords['y'];
                            break;
                    }
                }
                // дефолтные размеры и координаты
                else
                {
                    if (!$print_block[$side])
                    {
                        throw new Exception('Model printing block ' . $side . ' side not setted', 4);
                    }
            
                    if ($src_sizes['width'] > $src_sizes['height'])
                    {
                        $w = $print_block[$side]['w'];
                        $h = floor($src_sizes['height'] / ($src_sizes['width'] / $w));
            
                        if ($h > $print_block[$side]['h'])
                        {
                            $h = $print_block[$side]['h'];
                            $w = floor($src_sizes['width'] / ($src_sizes['height'] / $h));
                        }
                    }
                    else
                    {
                        $h = $print_block[$side]['h'];
                        $w = floor($src_sizes['width'] / ($src_sizes['height'] / $h));
            
                        if ($w > $print_block[$side]['w'])
                        {
                            $w = $print_block[$side]['w'];
                            $h = floor($src_sizes['height'] / ($src_sizes['width'] / $w));
                        }
                    }
                    
                    $x = $print_block[$side]['x'] - round($w / 2);
                    
                    // у сумкок дизайн прибиваем к центру
                    if ($cat == 'sumki') {
                        $y = $print_block[$side]['y'] - round($print_block[$side]['h'] / 2) + round(($print_block[$side]['h'] - $h) / 2);
                    // у маек - к горлу
                    } else {
                        $y = $print_block[$side]['y'] - round($print_block[$side]['h'] / 2);
                    }
                }
            
                try
                {
                    if (!empty($a))
                        $pictureImg->rotateImage('', $a);
                    
                    //$pictureImg->adaptiveResizeImage($w, $h);
                    $pictureImg->resizeImage($w, $h, imagick::FILTER_LANCZOS, 1, TRUE);
                    $templateImg->compositeImage($pictureImg, imagick::COMPOSITE_OVER, $x, $y);
                }
                catch (Exception $e)
                {
                    printr($e);
                }
            }
        
            if ($this->good_status == 'customize')
            {
                $r = App::db()->query(sprintf("SELECT * FROM `good__texts` WHERE `good_id` = '%d' AND `side` = '$side'", $this->id));
            
                if ($r->rowCount() > 0)
                {
                    // накладываем надписи
                    include ROOTDIR . '/vendor/fonts.php';
                    
                    foreach ($r->fetchAll() AS $l)
                    {
                        $angle    = (int) $l['a'] * -1;
                        
                        $fn       = get_font_file($l['font_name'], unserialize($l['font_style']), $fonts_aliases);
                        $fontSize = (int) ($l['font_size'] * self::$fontSizeCorrection);
                        
                        $l['text'] = stripslashes($l['text']);
                        
                        $box = imagettfbbox_fixed($l['font_size'], $l['a'], $fn, $l['text']);
                        
                        $textwidth = abs($box['width']);
                        $textheight = abs($box['height']) - 5;
            
                        $xcord = 0;
                        $ycord = 0;
                        
                        if ($l['a'] == 0) $ycord = $l['font_size'];
                        if ($l['a'] == 90) $xcord = $l['font_size'];
                        if ($l['a'] == -90) {$xcord = $l['font_size'];$ycord = $textheight;}
                        if ($l['a'] == 270) { $xcord -= ($textwidth/2); $ycord = 0; }
                        if ($l['a'] == -270) { $xcord += $l['font_size']; $ycord = 0; }
                        if ($l['a'] == 315)  { $xcord -= $textwidth; $ycord -= ($textwidth/2); }
                        if ($l['a'] == 360)  { $xcord -= $textwidth; $ycord = abs($box[7]); }
                        
                        try
                        {
                            $draw = new ImagickDraw();
                            $draw->setFont($fn);
                            $draw->setFontSize($fontSize);
                            $draw->setFillColor($l['font_color']);
                            
                            $tx = $l['x'] + $xcord + (($print_block[$side]['x'] - $print_block[$side]['w'] / 2));
                            $ty = $l['y'] + $ycord +(($print_block[$side]['y'] - $print_block[$side]['h'] / 2));
                                
                            $templateImg->annotateImage($draw, $tx, $ty, $l['a'], $l['text']);
                        }
                        catch (Exception $e) {
                            throw new Exception('annotate image error: ' . $e->getMessage(), 1);
                        }
                        
                        imagedestroy($st);
                    }
                }
            }
            
            $templateImg->setImageFormat('jpeg');
            $templateImg->setCompression(Imagick::COMPRESSION_JPEG);
            $templateImg->setCompressionQuality((!$param['quality']) ? 100 : $param['quality']); 
            $templateImg->setImageCompression((!$param['quality']) ? 100 : $param['quality']); 
            $templateImg->setImageCompressionQuality((!$param['quality']) ? 100 : $param['quality']);
            $templateImg->stripImage();
            $templateImg->setSamplingFactors(array('2x2', '1x1', '1x1')); 
            
            // если указан путь куда сохранять
            if (!empty($path))
            {
                try
                {
                    $tmpPath = tempnam(sys_get_temp_dir(), 'preview') . '.jpeg';
                    
                    if (!empty($width) && !empty($height) && ($width != $templateImg->getImageWidth() || $height != $templateImg->getImageHeight()))
                    {
                        $templateImg->scaleImage($width, $height);
                    }

                    $templateImg->writeImage($tmpPath);

                    if ($bucket)
                    {
                        $bucket = parse_url($bucket);
                        if ($bucket['path']) {
                            $path = ltrim($bucket['path'], '/');
                        }
                        $bucket = $bucket['host'];
                    }

                    $path = file::move2S3($tmpPath, $path, $bucket);
                        
                    $result['id'] = ($db ? file2db($path) : 0);
                    $result['path'] = $path;
                }
                catch(Exception $e)
                {
                    throw new Exception('Cant save file to ' . $path, 1);
                }
            }
            // вывод на экран
            else
            {
                try
                {
                    if (!empty($width) && !empty($height) && ($width != $templateImg->getImageWidth() || $height != $templateImg->getImageHeight()))
                        $templateImg->scaleImage($width, $height);
                }
                catch(Exception $e)
                {
                    throw new Exception('Cant generate output image', 1);
                }
                
                header('Content-type: image/jpeg');
                echo $templateImg;
            }
            
            $templateImg->clear();

            return $result;
        }

        /**
         * Сгенерить превью на носителе (гаджет)
         *  
         * @param int $sid номер носителя
         * @param int $width ширина картинки на выходе
         * @param int $height высота картинки на выходе 
         * @param string $side сторона
         * @param string $path кастомный путь для сохранения (вместе с именем файла)
         * @param array $param массив дополнительных параметров метода
         * @param string $bucket имя конкретного бакета в облаке
         */
        function generateGadgetPreview($sid, $width, $height, $side, $path, $db = false, $param = array(), $bucket)
        {
            // на всякий случай вырезаем из "стороны" _model
            $side = str_replace('_model', '', $side);
            
            // Извлекаем шаблон и всю информацию по носителю для наложения
            // на модели
            if (in_array($side, array('front_gray', 'back_gray')))
            {
                $q = sprintf("SELECT p.`picture_path` AS " . str_replace('_gray', '', $side) . ", s.`style_id`, s.`style_print_block` AS print_block, s.`style_sex` AS sex, sc.`cat_slug` AS cat, sc.`cat_parent`, gs.`good_stock_id`, c.`id` AS color_id, c.`group` AS color_group, c.`name`, c.`name_en`, sp.`x`, sp.`y`, sp.`w`, sp.`h`
                              FROM `good_stock` gs, `pictures` AS p, `styles` s, `styles_category` sc, `styles_pictures` sp, `good_stock_colors` AS c
                              WHERE s.`style_id` = '%d' AND gs.`style_id` = s.`style_id` AND sp.`style_id` = s.`style_id` AND p.`picture_id` = sp.`pic_id` AND sp.`pic_name` = '%s' AND s.`style_category` = sc.`id` AND c.`id` = s.`style_color`", $sid, $side);

               // print_r("\n" . $q . "\n");

                $r = App::db()->query($q);
            }

            // если нет фоток моделей или заказано на майке
            if (($r && $r->rowCount() == 0) || !in_array($side, array('front_gray', 'back_gray')))
            {
                // Извлекаем шаблон и всю информацию по носителю для наложения
                $r = App::db()->prepare("SELECT p1.`picture_path` as front, p2.`picture_path` as back, s.`style_id`, s.`style_print_block`, sc.`cat_parent`, gs.`good_stock_id`, sc.`cat_slug`
                        FROM `good_stock` gs, `styles_category` AS sc, `styles` AS s
                                LEFT JOIN `pictures` p1 ON s.`style_front_picture` = p1.`picture_id`
                                LEFT JOIN `pictures` p2 ON s.`style_back_picture` = p2.`picture_id`
                        WHERE gs.`style_id` = :sid AND gs.`good_id` = '0' AND gs.`style_id` = s.`style_id` AND s.`style_category` = sc.`id`
                        LIMIT 1");
                
                $r->execute(array('sid' => $sid));
                
                if ($r->rowCount() == 0)
                {
                    throw new Exception('Style ' . $sid . ' not founded', 1);
                }
            }
            
            $side = str_replace('_gray', '', $side);
                    
            $style = $r->fetch();
        
            if (!empty($style['front']) && in_array($side, array('front', 'both')))
                $sides['front']['path'] = $style['front'];
        
            if (!empty($style['back']) && in_array($side, array('back', 'both')))
                $sides['back']['path'] = $style['back'];
        
            $style['style_print_block'] = unserialize($style['style_print_block']);
        
            // боковинки
            if (count($sides) == 2 || (count($sides) == 1 && $sides['back']))
            {
                $r = App::db()->query(sprintf("SELECT sp.`pic_name`, p.`picture_path` AS path, sp.`x`, sp.`y`, sp.`w`, sp.`h` FROM `styles_pictures` sp, `pictures` p WHERE sp.`style_id` = '%d' AND sp.`pic_name` IN ('side', 'lside') AND sp.`pic_id` = p.`picture_id`", $style['style_id']));
                if ($r->rowCount() > 0) {
                    foreach ($r->fetchAll() AS $row) {
                        $sides[$row['pic_name']] =$row;
                    }
                }
            }
            
            // исходники
            if (styleCategory::$BASECATS[$style['cat_slug']])
            {
                $src_name = styleCategory::$BASECATS[$style['cat_slug']]['src_name'];
            }
            else 
            {
                $src_name = styleCategory::$BASECATS[styleCategory::$BASECATSid[$style['cat_parent']]]['src_name'];
            }
            
            $src_path      = $this->pics[$src_name . '_front'] ? $this->pics[$src_name . '_front']['path'] : $this->pics[$src_name]['path'];
            $src_back_path = $this->pics[$src_name . '_back']['path'];
            
            if ($this->pics[$src_name . '.work']['path'] && file_exists(ROOTDIR . $this->pics[$src_name . '.work']['path'])) {
                $src_path = $this->pics[$src_name . '.work']['path'];
            }
            
            if ($this->pics[$src_name . '_front.work']['path'] && file_exists(ROOTDIR . $this->pics[$src_name . '_front.work']['path'])) {
                $src_path = $this->pics[$src_name . '_front.work']['path'];
            }
            
            if ($this->pics[$src_name . '_back.work']['path'] && file_exists(ROOTDIR . $this->pics[$src_name . '_back.work']['path'])) {
                $src_back_path = $this->pics[$src_name . '_back.work']['path'];
            }
            
            if (empty($src_back_path))
                $src_back_path = $src_path;
            
            // ищем индивидуальные координаты накладываемых изображений 
            $r = App::db()->query(sprintf("SELECT `x`, `y`, `w`, `h`, `a`, `side`, `good_stock_id`
                      FROM `good__positions`
                      WHERE `good_id` = '%d' AND (`good_stock_id` = '" . $style['style_id'] . "' OR `good_stock_id` = '" . $style['good_stock_id'] . "')", $this->id));

            if ($r->rowCount() > 0)
            {
                foreach ($r->fetchAll() AS $p)
                {
                    $p['side'] = str_replace('_model', '', $p['side']);
                    
                    //if ($sides[$p['side']])
                        $sides[$p['side']]['coords'] = $p;
                }
            }
            
            // выбираем имеющиеся текстовые надписи
            // только для самоделок
            if ($this->good_status == 'customize')
            {
                $r = App::db()->query(sprintf("SELECT * FROM `good__texts` WHERE `good_id` = '%d' AND `side` IN ('%s')", $this->id, ($side == 'both') ? "front', 'back" : $side));
            
                if ($r->rowCount() > 0)
                {
                    foreach ($r->fetchAll() AS $l)
                    {
                        if ($l['side'] == 'front' && !empty($style[$l['side']]) && $src_path !== false)
                            $sides[$l['side']]['textLayers'][] = $l;
                        if ($l['side'] == 'back' && !empty($style[$l['side']]) && $src_back_path !== false)
                            $sides[$l['side']]['textLayers'][] = $l;
                    }
            
                    include ROOTDIR . '/vendor/fonts.php';
                }
            }
        
            // если это самоделка и указан цвет фона
            if ($this->good_status == 'customize') {
                if ($this->ps_onmain_id && $this->ps_onmain_id != 18)
                    $bg = new ImagickPixel('#' . colorId2hex($this->ps_onmain_id));
            } else {
                if (styleCategory::$BASECATSid[$style['cat_parent']] == 'touchpads' || $style['cat_slug'] == 'patterns' || $style['cat_slug'] == 'patterns-sweatshirts') {
                    $bg = new ImagickPixel('white');
                }
            }
            
            //printr($sides, 1);
                                
            // собираем стороны по отдельности
            foreach ($sides AS $sside => $s)
            {
                if (!$s['path']) {
                    continue;
                }
                
                if (in_array($sside, array('back', 'side', 'lside'))  && !empty($src_back_path)) {
                    $src_path = $src_back_path;
                }
                
                // если не загружен исходник на лицевую - берём его со спины вместе с координатами и размерами
                if ($sside == 'front') {
                    if (empty($src_path))
                        $src_path = $src_back_path;
                    
                    if (!$sides['front']['coords'] && $sides['back']['coords']) {
                        $s['coords'] = $sides['back']['coords'];
                        $s['coords']['side'] = 'front';
                    }
                }
                
                if ($sside == 'back') {
                    if (!$sides['back']['coords'] && $sides['front']['coords']) {
                        $s['coords'] = $sides['front']['coords'];
                        $s['coords']['side'] = 'back';
                    }
                }
        
                $i[$sside] = new Imagick(ROOTDIR . $s['path']);
                
                if ($bg) {
                    $i[$sside]->setImageBackgroundColor($bg);
                }
                
                // накладываем исходник
                if (!empty($src_path))
                {
                    try 
                    {
                        $src = new Imagick(ROOTDIR . $src_path);
                    } 
                    catch (Exception $e) 
                    {
                        throw new Exception('can\'t create src image from path - ' . $src_path, 12);
                    }
                    
                    if (!empty($sides[$sside]['y']) && !empty($sides[$sside]['y']) && !empty($sides[$sside]['y']) && !empty($sides[$sside]['y']))
                    {
                        $style['style_print_block'][$sside] = array(
                            'x' => $style['x'], 
                            'y' => $style['y'], 
                            'w' => $style['w'], 
                            'h' => $style['h']);
                    }
                    else 
                    {
                        if (!$style['style_print_block'][$sside] && (empty($sides[$sside]['x']) || empty($sides[$sside]['y']) || empty($sides[$sside]['w']) || empty($sides[$sside]['h']))) {
                            throw new Exception('not exist printing area for side ' . $sside, 1);
                        }
                    }
                    
                    if (!$style['style_print_block'][$sside]) {
                        throw new Exception('not exist printing area for side ' . $sside, 2);
                    }

                    // боковинка кейса
                    if (in_array($sside, array('side', 'lside')))
                    {
                        // если координаты расположения и размеры исходника заданы заранее
                        if ($sides['back']['coords']['w'] && $sides['back']['coords']['h']) 
                        {
                            $w = $sides['back']['coords']['w']; 
                            $h = $sides['back']['coords']['h'];
                            $x = ($style['style_print_block']['back']['w'] - $sides['back']['coords']['x']) * -1;
                            $y = $sides['back']['coords']['y'];
                        }
                        else 
                        {
                            $h = $style['style_print_block']['back']['h'];
                            $w = $src->getImageWidth() * ($h / $src->getImageHeight());
                            
                            if ($sside == 'lside')
                                $x = 0;
                            else {
                                $x = (($w + $style['style_print_block']['back']['w'] - 3) / 2) * -1;
                            }

                            $y = 0;
                        }
                        
                        if (!empty($sides[$sside]['x']) && !empty($sides[$sside]['y']) && !empty($sides[$sside]['w']) && !empty($sides[$sside]['h'])) {
                            $style['style_print_block'][$sside]['x'] = $sides[$sside]['x'];
                            $style['style_print_block'][$sside]['y'] = $sides[$sside]['y'];
                            $style['style_print_block'][$sside]['w'] = $sides[$sside]['w'];
                            $style['style_print_block'][$sside]['h'] = $sides[$sside]['h'];
                        }
                        
                    }
                    else 
                    { 
                        if ($s['coords'] && ($s['coords']['x'] + $s['coords']['y'] + $s['coords']['w'] + $s['coords']['h']) > 0)
                        {
                            extract($s['coords']);
                        }
                        else
                        {
                            if ($style['style_print_block'][$sside]['w'] >= $style['style_print_block'][$sside]['h'])
                            {
                                $w = $style['style_print_block'][$sside]['w'];
                                $h = floor($src->getImageHeight() / ($src->getImageWidth() / $w));
                                
                                if ($h < $style['style_print_block'][$sside]['h']) {
                                    $h = $style['style_print_block'][$sside]['h'];
                                    $w = floor($src->getImageWidth() / ($src->getImageHeight() / $h));
                                }
                            } 
                            else 
                            {
                                $h = $style['style_print_block'][$sside]['h'];
                                $w = floor($src->getImageWidth() / ($src->getImageHeight() / $h));
            
                                if ($w < $style['style_print_block'][$sside]['w']) {
                                    $w = $style['style_print_block'][$sside]['w'];
                                    $h = floor($src->getImageHeight() / ($src->getImageWidth() / $w));
                                }
                            }
                            
                            $x = ($style['style_print_block'][$sside]['w'] - $w) / 2;
                            $y = ($style['style_print_block'][$sside]['h'] - $h) / 2;
                        }
                    }
                
                    // поворачиваем исходник при необходимости
                    if (!empty($a)) {
                        $src->rotateImage('', $a);
                    }
                    
                    // уменьшаем
                    $src->scaleImage($w, $h);
                
                    $i[$sside]->compositeImage($src, imagick::COMPOSITE_DSTOVER, ($style['style_print_block'][$sside]['x'] - $style['style_print_block'][$sside]['w'] / 2) + $x, ($style['style_print_block'][$sside]['y'] - $style['style_print_block'][$sside]['h'] / 2) + $y);
                }
        
                // накладываем надписи
                foreach($s['textLayers'] as $k => $l)
                {
                    $fn       = get_font_file($l['font_name'], unserialize($l['font_style']), $fonts_aliases);
                    $fontSize = (int) ($l['font_size'] * self::$fontSizeCorrection);
                    
                    $l['text'] = stripslashes($l['text']);
        
                    $tt = new Imagick;

                    $draw = new ImagickDraw();
                    
                    $draw->setFont($fn);
                    $draw->setFontSize($fontSize);
                    $draw->setTextAlignment (Imagick::ALIGN_LEFT);
                    $draw->setFillColor($l['font_color']);
                    $draw->setTextAlignment (Imagick::ALIGN_LEFT);
                    
                    $metrics = $tt->queryFontMetrics($draw, $l['text']);
                    
                    $draw->annotation(0, $metrics['textHeight'] + $metrics['descender'], $l['text']);
                    $tt->newImage($metrics['textWidth'] + 2 * $metrics['boundingBox']['x1'], $metrics['textHeight'] + $metrics['descender'] + 5, new ImagickPixel('transparent'));
                    
                    $tt->drawImage($draw);
                    
                    $tt->rotateImage('', $l['a']);
                    
                    $i[$sside]->compositeImage($tt, imagick::COMPOSITE_OVER, ($style['style_print_block'][$sside]['x'] - $style['style_print_block'][$sside]['w'] / 2) + $l['x'], ($style['style_print_block'][$sside]['y'] - $style['style_print_block'][$sside]['h'] / 2) + $l['y']);
                    
                    $draw->clear();
                    $tt->clear();
                }

                if ($bg) {
                    //$i[$sside] = $i[$sside]->flattenImages();
                    $i[$sside] = $i[$sside]->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
                }
            }

            // собираем стороны в одно изображение
            $prv = new Imagick();
            
            // только 1 сторона
            if ($side == 'front' || $side == 'back' || ($side == 'both' && count($sides) == 1))
            {
                // если указано что нужно генерить на 2 стороны а по факту сторон только две
                // то берём дефолтную сторону
                if ($side == 'both' && count($sides) == 1)
                {
                    $foo = array_keys($i);
                    $side = reset($foo);
                }
                
                // есть боковинки
                if ($sides['side'] || $sides['lside'])
                {
                    $xoffset = 10;
                    $blockw  = 265;
                    
                    // если шаблон носителя шире финального изображения
                    if ($i['back']->getImageWidth() >= ($blockw - $i[($sides['lside']) ? 'lside' : 'side']->getImageWidth() + $xoffset))
                    {
                        $blockw = $i['back']->getImageWidth() + $i[($sides['lside']) ? 'lside' : 'side']->getImageWidth() + (2 * $xoffset);
                    }
                    
                    $prv->newImage($blockw, $i['back']->getImageHeight(), new ImagickPixel("white") );
                    
                    // есть только левая боковинка
                    if ($sides['lside'] && !$sides['side'])
                    {
                        $bx = ($prv->getImageWidth() - ($i['back']->getImageWidth() + $i['lside']->getImageWidth() + $xoffset)) / 2 + $i['lside']->getImageWidth() + $xoffset;
                        $sx = $bx - $i['lside']->getImageWidth() - $xoffset;
                        $prv->compositeImage($i['lside'], imagick::COMPOSITE_OVER, $sx, 0);
                    }
                    // есть только права боковинка
                    elseif ($sides['side'] && !$sides['lside'])
                    {
                        $bx = ($prv->getImageWidth() - ($i['back']->getImageWidth() + $i['side']->getImageWidth() + $xoffset)) / 2;
                        $sx = $bx + $i['back']->getImageWidth() + $xoffset;
                        $prv->compositeImage($i['side'], imagick::COMPOSITE_OVER, $sx, 0);
                    }
                    // есть обе боковинки (накладываем только правую)
                    else
                    {
                        $bx = ($prv->getImageWidth() - ($i['back']->getImageWidth() + $i['side']->getImageWidth() + $xoffset)) / 2;
                        $sx = $bx + $i['back']->getImageWidth() + $xoffset;
                        $prv->compositeImage($i['side'], imagick::COMPOSITE_OVER, $sx, 0);
                    }
                    
                    $prv->compositeImage($i['back'], imagick::COMPOSITE_OVER, $bx, 0);
                        
                    if (!empty($width) && !empty($height))
                    {
                        $i = new Imagick();
                        $i->newImage(500, 512, new ImagickPixel('white') );
                        $i->compositeImage($prv, imagick::COMPOSITE_OVER, round(500 / 2 - $prv->getImageWidth() / 2), round(512 / 2 - $prv->getImageHeight() / 2));
                        $prv = $i;
                    }
                }
                else 
                {
                    // если не указаны размеры выходного изображения - берём размеры шаблона
                    if (empty($width) || empty($height))
                    {
                        $width  = $i[$side]->getImageWidth();
                        $height = $i[$side]->getImageHeight();
                    }
                    
                    $prv->newImage($width, $height, new ImagickPixel("white"));
                    
                    $img = array_pop($i);
                    
                    // размеры выходного изображения известны
                    if (!empty($width) && !empty($height) && ($height < $img->getImageHeight() || $width <= $img->getImageWidth()))
                    {
                        $prvh = $height;
                        $prvw = round($img->getImageWidth() * $prvh / $img->getImageHeight());
                
                        if ($prvw > $width)
                        {
                            $prvw = $height;
                            $prvh = round($img->getImageHeight() * $prvw / $img->getImageWidth());
                        }
                        
                        $img->scaleImage($prvw, $prvh);
                    }
                    
                    
                    if (empty($width) && empty($height))
                    {
                        $width  = $prvw = $i[$side]->getImageWidth();
                        $height = $prvh = $i[$side]->getImageHeight();
                    }
                
                    $prv->compositeImage($img, imagick::COMPOSITE_OVER, round($width / 2 - $img->getImageWidth() / 2), round($height / 2 - $img->getImageHeight() / 2));
                }
            }
            // все доступные стороны
            elseif ($side == 'both')
            {
                // есть боковинки
                if ($sides['side'] || $sides['lside'])
                {
                    $prv->newImage(500, 512, new ImagickPixel('white') );
        
                    $xoffset  = 10;  // смещение передка        
                    $offset   = 10;  // расстояние м/у передком и задком
                    $xoffset2 = 10;  // смещение боковинки
                    
                    $prvw = round((500 - $offset) / 2);
                    $prvh = round($i['back']->getImageHeight() * $prvw / $i['back']->getImageWidth());
                    
                    // планшет
                    if ($sides['lside'] && styleCategory::$BASECATSid[$style['cat_parent']] == 'touchpads')
                    {
                        $xoffset = 40;
                        
                        $i['front']->scaleImage($i['front']->getImageWidth() * 0.9, $i['front']->getImageHeight() * 0.9);
                
                        $fx = (500 - ($i['back']->getImageWidth() + $i['front']->getImageWidth() / 2 + $i[($sides['lside']) ? 'lside' : 'side']->getImageWidth() + $offset)) / 2;
                        
                        $bx = $fx + $i['front']->getImageWidth() / 1.8;
                        $sx = $bx + $i['back']->getImageWidth() + $offset;
                        
                        $prv->compositeImage($i['front'], imagick::COMPOSITE_OVER, $fx, round((512) / 2 - $i['front']->getImageHeight() / 2));
                        $prv->compositeImage($i['back'], imagick::COMPOSITE_OVER, $bx, round((512) / 2 - $i['back']->getImageHeight() / 2));
                        $prv->compositeImage($i[($sides['lside']) ? 'lside' : 'side'], imagick::COMPOSITE_OVER, $sx, round((512) / 2 - $i['back']->getImageHeight() / 2));
                    } 
                    else
                    {
                        $prv->compositeImage($i['front'], imagick::COMPOSITE_OVER, $xoffset, round((512) / 2 - $i['front']->getImageHeight() / 2));
                        $prv->compositeImage($i['back'], imagick::COMPOSITE_OVER, $xoffset + $offset + $i['front']->getImageWidth(), round((512) / 2 - $i['back']->getImageHeight() / 2));
                        $prv->compositeImage($i['side'], imagick::COMPOSITE_OVER, $xoffset + $offset + $xoffset2 + $i['front']->getImageWidth() + $i['back']->getImageWidth(), round((512) / 2 - $i['back']->getImageHeight() / 2));
                    }
                }
                // боковинок нет
                else 
                {
                    $prv->newImage(500, 512, new ImagickPixel("white"));
                
                    // планшет
                    if ($style['cat_parent'] == 60)
                    {
                        try
                        {
                            $i['front']->scaleImage($i['front']->getImageWidth() * 0.8, $i['front']->getImageHeight() * 0.8);
                            $i['back']->chopImage(($i['back']->getImageWidth() - $style['style_print_block']['back']['w']) / 2, 0, 0, 0);
                    
                            if ($style['style_print_block']['orientation'] == 'horizontal')
                            {
                                $prv->compositeImage($i['front'], imagick::COMPOSITE_OVER, round((500) / 2 - $i['front']->getImageWidth() / 2), 20);
                                $prv->compositeImage($i['back'], imagick::COMPOSITE_OVER, round((500 - $i['back']->getImageWidth()) / 2), 512 - 10 - $i['back']->getImageHeight());
                            }
                            else
                            {
                                $prv->compositeImage($i['front'], imagick::COMPOSITE_OVER, 10, round((512) / 2 - $i['front']->getImageHeight() / 2));
                                $prv->compositeImage($i['back'], imagick::COMPOSITE_OVER, 500 - 10 - $i['back']->getImageWidth(), round((512) / 2 - $i['back']->getImageHeight() / 2));
                            }
                        }
                        catch (Exception $e) { printr($e); }
                    }
                    else
                    {
                        $offset = 20;
                        $prvw = round((500 - $offset) / 2);
                        $prvh = round($i['back']->getImageHeight() * $prvw / $i['back']->getImageWidth());
                
                        //$i['front']->scaleImage($prvw, $prvh);
                        //$i['back']->scaleImage($prvw, $prvh);
                    
                        if ($i['front'])
                            $prv->compositeImage($i['front'], imagick::COMPOSITE_OVER, 0, round((512) / 2 - $prvh / 2));
                        
                        if ($i['back'])
                            $prv->compositeImage($i['back'], imagick::COMPOSITE_OVER, 500 - 10 - $i['back']->getImageWidth(), round((512) / 2 - $prvh / 2));
                    }
                }
            }
            
        
            if (($height > 0 && $height != $prv->getImageHeight()) || ($width > 0 && $width != $prv->getImageWidth()))
            {
                $prv->scaleImage($width, $height);
            }
        
            $prv->setCompression(Imagick::COMPRESSION_JPEG);
            $prv->setCompressionQuality((!$param['quality']) ? 95 : $param['quality']); 
            $prv->setImageCompression((!$param['quality']) ? 95 : $param['quality']); 
            $prv->setImageCompressionQuality((!$param['quality']) ? 95 : $param['quality']);
            $prv->setImageFormat('jpeg');
        
            if (!empty($path))
            {
                $finalName = basename($path);
                $path      = dirname($path) . '/';
            
                @unlink(ROOTDIR . $path . $finalName);
            
                createDir($path);   
        
                try
                {
                    $prv->writeImage(ROOTDIR . $path . $finalName);
                    
                    if ($bucket)
                    {
                        $bucket = parse_url($bucket);
                        $newpath = ltrim($bucket['path'], '/');
                        $bucket = $bucket['host'];
                    }

                    $result['path'] = file::move2S3($path . $finalName, $newpath, $bucket);
                    $result['width'] = $width;
                    $result['height'] = $height;
                    
                    if ($db) {
                        $result['id'] = file2db($result['path']);
                    }
                }
                catch (Exception $e) 
                {
                    throw new Exception('Cant save file to ' . $path . '. ' . $e->getMessage(), 1);
                }
            }
            else
            {
                header('Content-type: image/jpeg');
                
                echo $prv;
            }
            
            if ($src)
                $src->clear();
            
            $prv->clear();
        
            return $result;
        }
        
        /**
         * Сгенерировать превью дизайна на кружке
         *
         * @param int $sid номер носителя
         * @param int $width ширина картинки на выходе
         * @param int $height высота картинки на выходе 
         * @param string $side сторона
         * @param string $dst кастомный путь для сохранения (вместе с именем файла)
         * @param boolean $db сохранять в базу или нет
         */
        function generateCupPreview($sid, $width = 500, $height = 512, $side = 'front', $dst = null, $db = false) 
        {
            if (!$this->pics['cup']) {
                throw new Exception('У данной работы нет исходника для кружек', 1);
            }
            
            $S = new style($sid);
            
            // 2680Х970w требуемый размер исходника
            // 2680 * 1090 - новый размер
            
            // -r радиус кружки в пикселях
            // -l высота в пикселях
            // -w процент оборачивания поверхности изображением (справа на лево)
            // -p угол наклона изображения
            // -e коэффициент прогиба изображения (2 даёт нормальный результат при наклоне до 20градусов)
            // -n соотношение между шириной верха и низа (для иммитации перспективы)
            // -a смещение изображения по поверхности цилиндра в градусах (относительно центра) 0 - центр кружки совпадает с центром изображения (по вертикали)
            // -t - убрать фон
            
            define('diam', 22.7);       // длина окружности кружки
            define('height' , 9.5);     // высота кружки
            //define('handle', 1.5);    // ширина ручки
            define('resolution', 300);  // разрещение (dpi)
            
            // Длина окружности кружки в пикселях с вычетом 1 сантиметра на ручку
            $l = round((diam / 2.54) * resolution);
            $l_section = $l / 2 - 250;
            $h_section = height * (resolution / 2.54);
            
            $options = array(
                'front' => array('r' => 149, 'l' => 368-22, 'w' => 50, 'p' => 5, 'e' => 2, 'n' => 100, 'a' => 0, 's' => 100),
                'side'  => array('r' => 149, 'l' => 368-22, 'w' => 50, 'p' => 5, 'e' => 2, 'n' => 100, 'a' => 0, 's' => 100),
                'lside' => array('r' => 149, 'l' => 368-22, 'w' => 50, 'p' => 5, 'e' => 2, 'n' => 100, 'a' => 0, 's' => 100),
            );
            
            // промежуточный подрезанный исходник
            $tmp = tempnam(sys_get_temp_dir(), 'cup_') . '.jpeg';
            // шаблон кружки
            $side = in_array($side, array('front', 'side', 'lside')) ? $side : 'lside';
            
            if ($side == 'front') {
                $tpl = $S->pics['front'][0];
            } else {
                $tpl = $S->pics[$side];
            }
            
            // смещение согнутого изображения на шаблоне кружки 
            $x = round($tpl['x'] - $tpl['w'] / 2);
            $y = round($tpl['y'] - $tpl['h'] / 2);
            
            if (empty($this->pics['cup']['pic_w']) || empty($this->pics['cup']['pic_h'])) {
                $src = new Imagick(ROOTDIR . $this->pics['cup']['path']);
                $src_sizes = $src->getImageGeometry();
            } else {
                $src_sizes = array(
                    'width' => $this->pics['cup']['pic_w'], 
                    'height' => $this->pics['cup']['pic_h']
                );
            }
            
            $src_sizes['width'] = round(($tpl['h'] / $src_sizes['height']) * $src_sizes['width']);
            $src_sizes['height'] = $tpl['h'];
            
            $crop_y = $src_sizes['height'] / 2 - $h_section / 2;
            
            switch ($side) {
                case 'front':
                    $crop_x = $src_sizes['width'] / 2 - ($tpl['w'] + 170) / 2;
                    break;
                
                case 'side':
                    $crop_x = ($src_sizes['width'] / 2) - $tpl['w'] - 170;
                    break;
                    
                case 'lside':
                    $crop_x = $src_sizes['width'] / 2;
                    break;
            }
            
            //printr($src_sizes);
            //printr("$crop_x $crop_y");
            //printr(round($l_section) . "x" . round($h_section));
            //printr($tpl, 1);
            //exit();
            // -v background -b [white|none] цвет фона после сгиба
            
            // убираем прозрачность фона
            $command[] = "convert -background white -flatten " . ROOTDIR . $this->pics['cup']['path'] . ' ' . $tmp;
            
            // уменьшаем и кадрируем исходник
            $command[] = "convert -resize x" . $tpl['h'] . " -crop " . ($tpl['w'] + 170) . "x" . $tpl['h'] . ($crop_x > 0 ? '+' : '') . $crop_x . "+0" . ' ' . $tmp . ' ' . $tmp;
            // сгибаем его
            $command[] = "cylinderize -m vertical -r " . $options[$side]['r'] . " -l " . $options[$side]['l'] . " -w " . $options[$side]['w'] . " -p " . $options[$side]['p'] . " -n " . $options[$side]['n'] . " -e " . $options[$side]['e'] . " -a " . $options[$side]['a'] . " -s " . $options[$side]['s'] . " -v background -b red -f none -t " . $tmp . ' ' . $tmp;
            // накладываем шаблон кружки поверх
            $command[] = "composite -compose DstOver -geometry +{$x}+{$y} " . $tmp . ' ' . ROOTDIR . $tpl['path'] . ' ' . $tmp;
            // склеиваем все слои
            $command[] = "convert -flatten " . $tmp . ' ' . $tmp;
            
            if ($width && $height) 
            {
                $command[] = "convert -resize " . $width . "x" . $height . " " . $tmp . ' ' . $tmp;
            }

            exec(implode('; ', $command), $out);
            
            //printr(implode('; ', $command), 1);
            
            if (strlen($out) > 0 || count($out) > 0) 
            {
                throw new Exception(implode("\n", $out), 1);
            }
            
            if ($dst)
            {
                $path = parse_url($dst);
                    
                $result['path'] = file::move2S3($tmp, ltrim($path['path'], '/'), $path['host']);
                
                if ($db) {
                    $result['id'] = file2db($result['path']);
                }
            } else {
                header('Content-type: image/jpeg');
                imagejpeg(createImageFrom($tmp), null, 98);
                @unlink($tmp);
                exit();
            }
            
            //printr($command);
            //unlink($tmp);
            
            return $result;
        }

        /**
         * Сгенерировать превью дизайна на стикере 
         * @param int $width ширина картинки на выходе
         * @param int $height высота картинки на выходе 
         * @param string $path кастомный путь для сохранения (вместе с именем файла)
         * @param array $param массив дополнительных параметров метода 
         */
        function generateStickerPreview($width = 500, $height = 512, $path, $db = false, $param = array())
        {
            if (!$this->pics[styleCategory::$BASECATS['stickers']['src_name']]) {
                throw new Exception('У данной работы отсутствует исходник для наклеек', 1);
            }
            
            $src = new Imagick(ROOTDIR . $this->pics[styleCategory::$BASECATS['stickers']['src_name']]['path']);
            
            $src->scaleimage(500, 512, TRUE);
            
            $geo = $src->getimagegeometry();

            $prv = new Imagick;
            $prv->newimage($width, $height, new ImagickPixel('white'));

            switch ($param['form']) 
            {
                case 'square':
                    break;
                
                case 'triangle':
                    
                    $mask = new Imagick;
                    $mask->newimage($geo['width'], $geo['height'], new ImagickPixel('blue'));
                    
                    $draw = new ImagickDraw();
                    
                    $draw->setFillColor( new ImagickPixel( "transparent" ) );
                    $draw->circle($geo['width'] / 2, $geo['height'] / 2, $geo['width'], $geo['height'] / 2);
                    $mask->drawImage( $draw );
                    //$draw->composite (imagick::COMPOSITE_XOR, 0, 0, 100, 100, $mask);
                    
                    $src->compositeimage($mask, imagick::COMPOSITE_XOR , 0, 0);
                    
                    $mask->setImageFormat('png');
                    header('Content-type: image/png');
                    exit($mask);
                
                    /*
                    $draw = new ImagickDraw();
                    
                    $draw->setFillColor( new ImagickPixel("blue") );
                    $draw->rectangle(0, 0, $geo['width'], $geo['height']);
                    
                    $src->drawImage( $draw );
                    */
                    
                    break; 
                
                case 'triangle2':
                    
                    $draw = new ImagickDraw();
                    
                    $src->drawImage( $draw );
                    
                    break;
                     
                case 'circle':
                    
                    $draw = new ImagickDraw();
                    
                    $draw->circle( 200, 100, 50, 50);
 
                    $src->drawImage( $draw );
                    
                    break;
                     
                case 'oval':
                    
                    $draw = new ImagickDraw();
                    
                    $draw->ellipse( 200, 100, 50, 50, 0, 360 );
 
                    $src->drawImage( $draw );
                    
                    break; 
                
                default:
                    throw new Exception('Не известная форма наклейки "' . $param['form'] . '"', 2);
                    break;
            }
            
            $prv->compositeimage($src, imagick::COMPOSITE_OVER, $width / 2 - $geo['width'] / 2, $height / 2 - $geo['height'] / 2);
            
            if ($height != $prv->getImageHeight() || $width != $prv->getImageWidth())
            {
                $prv->scaleImage($width, $height);
            }
        
            $prv->setCompression(Imagick::COMPRESSION_JPEG);
            $prv->setCompressionQuality((!$param['quality']) ? 95 : $param['quality']); 
            $prv->setImageCompression((!$param['quality']) ? 95 : $param['quality']); 
            $prv->setImageCompressionQuality((!$param['quality']) ? 95 : $param['quality']);
            $prv->setImageFormat('jpeg');
        
            if (!empty($path))
            {
                $finalName = basename($path);
                $path      = dirname($path) . '/';
            
                @unlink(ROOTDIR . $path . $finalName);
            
                createDir($path);   
        
                try
                {
                    $prv->writeImage(ROOTDIR . $path . $finalName);
                    
                    $result['path'] = file::move2S3($path . $finalName, $newpath);
                    $result['width'] = $width;
                    $result['height'] = $height;
                    
                    if ($db) {
                        $result['id'] = file2db($result['path']);
                    }
                }
                catch (Exception $e) 
                {
                    throw new Exception('Cant save file to ' . $path . '. ' . $e->getMessage(), 1);
                }
            }
            else
            {
                header('Content-type: image/jpeg');
                
                echo $prv;
            }
            
            if ($src)
                $src->clear();
            
            $prv->clear();
        
            return $result;
        }

        /**
         * Сгенерить превью на носителе
         *  
         * @param int $sid номер носителя
         * @param int $width ширина картинки на выходе
         * @param int $height высота картинки на выходе 
         * @param string $side сторона
         * @param string $path кастомный путь для сохранения (вместе с именем файла)
         * @param boolean $db сохранять в базу или нет
         * @param array $param массив дополнительных параметров метода
         * @param string $bucket имя конкретного бакета в облаке
         */
        function preview($sid, $width, $height, $side, $path = null, $db = true, $param = array(), $bucket = null)
        {
            $S = new style($sid);
            
            if (!$side) {
                $side = styleCategory::$BASECATS[$S->category]['def_side'];
                
                if ($S->style_sex == 'male' || $S->style_sex == 'female' || $S->style_sex == 'kids') {
                    $side .= '_model';
                }
            }

            if (!is_null($path) && empty($path)) {
                $path = '/J/catalog/' . date('Y/m/d/') . toTranslit($this->good_name) . '_' . $S->style_slug . '_' . substr(md5(time() . rand()), 0, 4) . '.jpeg';
            }
            
            switch ($S->category)
            {
                case 'phones':
                case 'laptops':
                case 'touchpads':
                case 'ipodmp3':
                case 'cases':
                case 'moto':
                case 'poster':
                case 'posters':
                case 'boards':
                case 'bag':
                case 'textile':
                case 'pillows':
                case 'patterns':
                case 'patterns-sweatshirts':
                case 'patterns-bag':
                case 'patterns-tolstovki':
                case 'bomber':
                case 'body':
                                        
                    $r = $this->generateGadgetPreview($S->id, $width, $height, $side, $path, $db, $param, $bucket);
                    
                    break;
                
                case 'stickers':
                    
                    if ($S->id == 759)
                        $r = $this->generateStickerPreview($width, $height, $path, $db, $param);
                    else
                        $r = $this->generateGadgetPreview($S->id, $width, $height, $side, $path, $db, $param, $bucket);
                    
                    break;
                    
                case 'cup':
                    
                    if (!$side) {
                        $sides = array('side', 'front', 'lside');
                        $side = $sides[rand(0, 2)];
                    }
                    
                    $r = $this->generateCupPreview($S->id, $width, $height, $side, $path, $db);
                    
                    break;
                    
                default:

                    $r = $this->generatePreview($S->id, $width, $height, $side, $path, $db, $bucket, $param);
                    
                    break;
            }
        
            return $r;
        }

        /**
         * Уменьшенное превью исходника на белом фоне
         * доступное публично
         */
        function generateSrcPreview($src, $w, $h, $path)
        {
            if (!self::$srcs[$src]) {
                throw new Exception('Не существует такого исходника ' . $src, 11);
            }
            
            if (!$this->pics[$src]) {
                throw new Exception('Исходник ' . $src . ' у данной работы не загружен', 1);
            }
            
            $prv = new Imagick(ROOTDIR . $this->pics[$src]['path']);
            
            if (!empty($w) && !empty($h)) 
            {
                $result['w'] = $w;
                $result['h'] = $h;
            }
            elseif (empty($w) && !empty($h)) 
            {
                $result['h'] = $h;
                $result['w'] = round($result['h'] / $prv->getImageHeight() * $prv->getImageWidth());
            }
            elseif (!empty($w) && empty($h)) 
            {
                $result['w'] = $w;
                $result['h'] = round($result['w'] / $prv->getImageWidth() * $prv->getImageHeight());
            }
            else 
            {
                $result['w'] = 230; 
                $result['h'] = round($result['w'] / $prv->getImageWidth() * $prv->getImageHeight());
            }
            
            $prv->cropThumbnailImage($result['w'], $result['h']);
            $prv->setImageBackgroundColor('white'); //задаем фон
            //$prv = $prv->flattenImages(); // склеиваем слои
            $prv = $prv->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN); // склеиваем слои
            
            $prv->setImageCompressionQuality(95);

            if (!empty($path))
            {
                $url       = parse_url($path);
                $finalName = basename($path);
                $path      = dirname($url['path']) . '/';
            
                if (!$url['host']) {
                    @unlink(ROOTDIR . $path . $finalName);
                    createDir($path);
                }
        
                $prv->writeImage(ROOTDIR . $path . $finalName);
                
                $result['path'] = $path . $finalName;
                
                if ($url['host']) 
                {
                    $result['path'] = file::move2S3($path . $finalName, $result['path']);
                }
                
                $result['id'] = file2db($result['path']);
                
                return $result;
            }
            else 
            {
                $prv->setImageFormat('jpeg');
                header('Content-type: image/jpeg');
                exit($prv);
            }
        }

        /**
         * Создать "рабочую" копию исходника, из которого будем генерить все превью на носителях 
         */
        public function generateSrcWorkCopy($src)
        {
            if (!self::$srcs[$src]) {
                throw new Exception('Не существует такого исходника ' . $src, 1);
            }
            
            if (!$this->pics[$src]) {
                throw new Exception('Исходник ' . $src . ' у данной работы не загружен', 2);
            }
            
            if ($this->pics[$src . '.work']) {
                $this->delPic($src . '.work');
            }
                                
            $pathinfo = pathinfo($this->pics[$src]['path']);
            
            $work = new Imagick(ROOTDIR . $this->pics[$src]['path']);
            
            $name = explode('.', basename($this->pics[$src]['path']));
            array_pop($name);
            $dst  = $pathinfo['dirname'] . '/' . implode('.', $name) . '.resized.' . $pathinfo['extension'];

            $geo = $work->getImageGeometry();
                
            if ($geo['width'] > $geo['height']) {
                $h = 512;
                $w = floor($geo['width'] / ($geo['height'] / $h));
            } else {
                $w = 500;
                $h = floor($geo['height'] / ($geo['width'] / $w));
            }
            
            // эта строчка здесь на всякий случай если запись из базы была почему-то удалена, а сама картинка осталась 
            unlink(ROOTDIR .  $dst);
            
            $work->scaleImage($w, $h);
            $work->setImageFormat($pathinfo['extension']);
            $work->writeImage(ROOTDIR .  $dst);
            
            $this->addPic($src . '.work', file2db($dst), $w, $h);
        }

        /**
         * Добавить работе новое изображение
         *
         * @param string $name
         * @param int $name - имя картинки
         * @param int $id - id файла
         * @param int $w - ширина
         * @param int $h - высота
         * @param string $date дата загрузки
         * 
         * @return int affected rows count
         */
        function addPic($name, $id, $w = 0, $h = 0, $date = null)
        {
            if (empty($id) || empty($name))
                return false;
            
            // удаляем файл если эта картинка уже была загружена ранее
            // исходники физически не удаляем 
            if (!in_array($name, array_keys(self::$srcs)))
            {
                if (count($this->pics) == 0)
                    $this->getPictures(array($name));
                    
                if ($this->pics[$name]) {
                    deletepicture($this->pics[$name]['id']);
                }
            }   
            
            // вставляем новую запись
            $r = App::db()->query(sprintf("INSERT INTO `good_pictures` 
                SET 
                    `good_id`  = '%d', 
                    `pic_name` = '%s', 
                    `pic_id`   = '%d',
                    `pic_w`    = '%d',
                    `pic_h`    = '%d',
                    `cached_time` = NOW(),
                    `upload_time` = '%s'
                ON DUPLICATE KEY UPDATE 
                    `pic_id`   = '%d',
                    `pic_w`    = '%d',
                    `pic_h`    = '%d',
                    `cached_time` = NOW(),
                    `upload_time` = '%s'", $this->id, addslashes($name), $id, $w, $h, (!$date ? NOW : $date), $id, $w, $h, (!$date ? NOW : $date)));
            
            if (in_array($name, array('ps_src_1', 'ps_src_2', 'ps_src_3', 'ps_src_4')))
                $this->pics['ps_src_colors'][$name] = array(
                    'id'    => $id,
                    'pic_w' => $w,
                    'pic_h' => $h,
                );

            $this->pics[$name] = array(
                'id'    => $id,
                'path'  => pictureId2path($id),
                'pic_w' => $w,
                'pic_h' => $h,
            );
                
            return $r->rowCount();
        }
        
        /**
         * Удалить картинку
         */
        function delPic($names)
        {
            if (!is_array($names))
                $names = (array) $names;
            
            if (count($names) == 0)
                return false;
            
            $this->getPictures($names);
            
            foreach ($names AS $k => $n)
            {
                if (!in_array($n, array('ps_src', 'ps_src_1', 'ps_src_2', 'ps_src_3', 'ps_src_4', 'phones', 'laptops', 'touchpads', 'auto', 'poster')))
                {
                    deletepicture($this->pics[$n]['id']);
                    unset($this->pics[$n]);
                }
                
                $names[$k] = addslashes($n);
            }
            
            App::db()->query(sprintf("DELETE FROM `good_pictures` WHERE `good_id` = '%d' AND `pic_name` IN ('%s')", $this->id, implode("', '", $names)));
        }

        /**
         * Редактировать поля
         * @param array $data - массив с полями
         */
        function change($data)
        {
            if (count($data) > 0)
            {
                $out = array();
                
                foreach ($data as $f => $v) {
                    if (!empty($f)) {
                        $out[] = "`" . addslashes($f) . "` = '" . addslashes($v) . "'";
                        
                        $this->$f = $v;
                    }
                }
    
                App::db()->query("UPDATE `" . self::$dbtable . "` SET " . implode(', ', $out) . " WHERE `good_id` = '" . $this->id . "' LIMIT 1");
            }
        }
        
        /**
         * Включить / выключить исходник работы
         * 
         * @param string $src - имя исходника
         */
        function src_on_of($src)
        {
            $src = trim($src);
            
            //if (!in_array($src, array_keys(self::$srcs))) {
              //  throw new Exception('Исходник "' . $src . '" не существует');
            //}
            
            //if (!isset($this->pics[$src]) && !isset($this->pics['ps_src_colors'][$src]))
                //throw new Exception ('src "' . $src . '" not found'); 
            
            //if (strpos($src, 'ps_src_') !== false) {
            //  $pic_id = $this->pics['ps_src_colors'][$src]['id'] = $this->pics[$src]['id'] = $this->pics['ps_src_colors'][$src]['id'] * -1;
            //} else {
                $pic_id = $this->pics[$src]['id'] = $this->pics[$src]['id'] * -1;
            //}
            
            if (!empty($pic_id)) {
                App::db()->query("UPDATE `good_pictures` SET `pic_id` = '{$pic_id}' WHERE `good_id` = '" . $this->id . "' AND `pic_name` = '{$src}' LIMIT 1");
            }
            
            switch ($src) 
            {
                case 'ps_src':
                case 'patterns_bag':
                      
                    foreach (styleCategory::$BASECATS as $k => $s) {
                        if ($s['src_name'] == $src) {
                            $cats[] = $s['id'];
                        }
                    }
                    
                    // вкл/выкл все превью на носителях для каталога этой цветовой группы
                    $r = App::db()->query("SELECT s.`style_id` FROM `styles` s WHERE s.`style_category` IN ('" . implode("', '", $cats) . "')");
                    
                    foreach ($r->fetchAll() AS $s) {
                        $styles[] = 'catalog_preview_' . $s['style_id'];
                    }
                    
                    if ($this->pics[$src]['id'] > 0)
                        App::db()->query("UPDATE `good_pictures` gp SET gp.`pic_id` = ABS(gp.`pic_id`) WHERE gp.`good_id` = '" . $this->id . "' AND gp.`pic_name` IN ('" . implode("','", $styles) . "')");
                    else
                        App::db()->query("UPDATE `good_pictures` gp SET gp.`pic_id` = ABS(gp.`pic_id`) * -1 WHERE gp.`good_id` = '" . $this->id . "' AND gp.`pic_name` IN ('" . implode("','", $styles) . "')");
                    
                break;
                
                case 'patterns':
                    
                    foreach (styleCategory::$BASECATS as $k => $s) {
                        if ($s['src_name'] == $src) {
                            $cats[] = $s['id'];
                        }
                    }
                    
                    // вкл/выкл все превью на носителях для каталога этой цветовой группы
                    $r = App::db()->query("SELECT s.`style_id` FROM `styles` s WHERE s.`style_category` IN ('" . implode("', '", $cats) . "')");
                    
                    foreach ($r->fetchAll() AS $s) {
                        $styles[] = 'catalog_preview_' . $s['style_id'];
                    }
                    
                    if ($this->pics[$src]['id'] > 0) {
                        App::db()->query("UPDATE `good_pictures` gp SET gp.`pic_id` = ABS(gp.`pic_id`) WHERE gp.`good_id` = '" . $this->id . "' AND gp.`pic_name` IN ('" . implode("','", $styles) . "')");
                        App::db()->query("UPDATE `good_pictures` gp SET gp.`pic_id` = ABS(gp.`pic_id`) WHERE gp.`good_id` = '" . $this->id . "' AND gp.`pic_name` LIKE 'catalog_art_preview_%'");
                    } else {
                        App::db()->query("UPDATE `good_pictures` gp SET gp.`pic_id` = ABS(gp.`pic_id`) * -1 WHERE gp.`good_id` = '" . $this->id . "' AND gp.`pic_name` IN ('" . implode("','", $styles) . "')");
                        App::db()->query("UPDATE `good_pictures` gp SET gp.`pic_id` = ABS(gp.`pic_id`) * -1 WHERE gp.`good_id` = '" . $this->id . "' AND gp.`pic_name` LIKE 'catalog_art_preview_%'");
                    }
                    
                break;
                    
                case 'ps_src_1':
                case 'ps_src_2':
                case 'ps_src_3':
                case 'ps_src_4':
                    
                    // если исходник для этой группы ещё не был загружен
                    if (!$this->pics[$src]['id'])
                    {
                        // копируем в него базовый исходник и выключаем его
                        $this->addPic($src, abs($this->pics['ps_src']['id']) * -1, $this->pics['ps_src']['pic_w'], $this->pics['ps_src']['pic_h']);
                    }
                    else 
                    {
                        App::db()->query("UPDATE `good_pictures` SET `pic_id` = `pic_id` * -1 WHERE `good_id` = '" . $this->id . "' AND `pic_name` = '" . $src . "_preview' LIMIT 1");
                        App::db()->query("UPDATE `good_pictures` SET `pic_id` = `pic_id` * -1 WHERE `good_id` = '" . $this->id . "' AND `pic_name` = '" . $src . "_big' LIMIT 1");
                    }
                    
                    $group = str_replace('ps_src_', '', $src);
                
                    // вкл/выкл все превью на носителях для каталога этой цветовой группы
                    $r = App::db()->query(sprintf("SELECT `style_id` FROM `good_stock_colors` c, `styles` s, `styles_category` sc WHERE c.`group` = '%d' AND s.`style_color` = c.`id` AND s.`style_category` = sc.`id` AND sc.`cat_parent` = '1'", $group));
                    
                    foreach ($r->fetchAll() AS $s) {
                        $styles[] = 'catalog_preview_' . $s['style_id'];
                    }
                    
                    if ($this->pics[$src]['id'] > 0)
                        App::db()->query("UPDATE `good_pictures` gp SET gp.`pic_id` = ABS(gp.`pic_id`) WHERE gp.`good_id` = '" . $this->id . "' AND gp.`pic_name` IN ('" . implode("','", $styles) . "')");
                    else
                        App::db()->query("UPDATE `good_pictures` gp SET gp.`pic_id` = ABS(gp.`pic_id`) * -1 WHERE gp.`good_id` = '" . $this->id . "' AND gp.`pic_name` IN ('" . implode("','", $styles) . "')");
                    
                    // если выключаем цветной исходник
                    if ($this->pics[$src]['id'] < 0 && $this->pics['ps_src']['id'] > 0) {
                        
                        // вкл/выкл основной исходник если выключены все цветные
                        $disabled = 0;
                        
                        foreach (array('ps_src_1','ps_src_2','ps_src_3','ps_src_4') AS $ss) {
                            if ($this->pics[$ss]['id'] < 0) {
                                $disabled++;
                            }
                        }
                        
                        if ($disabled == count(self::$colorGroups) - 1)
                        {
                            $this->addPic('ps_src', $this->pics['ps_src']['id'] * -1, $this->pics['ps_src']['w'], $this->pics['ps_src']['h']);
                        }
                    }

                    // если включаю один из цветных исходников, то автоматически включается основной
                    if ($this->pics[$src]['id'] > 0 && $this->pics['ps_src']['id'] < 0) {
                        $this->addPic('ps_src', abs($this->pics['ps_src']['id']), $this->pics['ps_src']['w'], $this->pics['ps_src']['h']);
                    }
                    
                break;
                
                case 'auto':
                    
                    break;
                    
                // исходник для стикерсета  
                case 'stickerset':
                    
                    if ($this->pics[$src]['id'] > 0)
                        $q = "UPDATE `good_pictures` SET `pic_id` = ABS(`pic_id`) WHERE `good_id` = '" . $this->id . "' AND `pic_name` = 'stickerset_preview'";
                    else
                        $q = "UPDATE `good_pictures` SET `pic_id` = -1 * ABS(`pic_id`) WHERE `good_id` = '" . $this->id . "' AND `pic_name` = 'stickerset_preview'";
                    
                    App::db()->query($q);
                    
                    break;
                    
                // наклейки и всё прочее
                default:
                    
                    foreach (styleCategory::$BASECATS as $k => $v) {
                        if ($v['src_name'] == $src || $k == $src) {
                            $categorys[] = $v['id'];
                        }
                    }
                    
                    $r = App::db()->query(sprintf("SELECT s.`style_id`
                                        FROM `styles_category` sc, `styles` s
                                        WHERE sc.`cat_parent` IN ('%s') AND s.`style_category` = sc.`id`", implode("','", $categorys)));
                                        
                    foreach ($r->fetchAll() AS $s) {
                        $styles[] = 'catalog_preview_' . $s['style_id']; 
                    }
                    
                    if (!empty($this->pics[$src]['id'])) {
                        if ($this->pics[$src]['id'] > 0)
                            $q = "UPDATE `good_pictures` SET `pic_id` = ABS(`pic_id`) WHERE `good_id` = '" . $this->id . "' AND `pic_name` IN ('" . implode("','", $styles) . "')";
                        else
                            $q = "UPDATE `good_pictures` SET `pic_id` = -1 * ABS(`pic_id`) WHERE `good_id` = '" . $this->id . "' AND `pic_name` IN ('" . implode("','", $styles) . "')";
                    } else {
                        $q = "UPDATE `good_pictures` SET `pic_id` = -1 * `pic_id` WHERE `good_id` = '" . $this->id . "' AND `pic_name` IN ('" . implode("','", $styles) . "')";
                    }
                    
                    App::db()->query($q);

                    break;
            }

            
            
            // проверяем остались ли у работы включённые исходники
            foreach (array_diff(array_keys(self::$srcs), ['as_oncar_0','as_oncar_1','as_oncar_2','as_oncar_3','as_oncar_4',]) as $p) 
            {
                if ($this->pics[$p]['id'] > 0)
                {
                    if ($this->good_status != 'new' && $this->good_visible == 'false')
                        $this->change(array('good_visible' => 'true'));
                    
                    return TRUE;
                }
            }
            
            $this->change(array('good_visible' => 'false'));
        }

        /**
         * Продажи за период
         * @var (int) $i - Интервал (в синтаксисе описания интервалов mysql)
         * @return (int) - продано штук
         */
        function getSales($i) 
        {
            $r = App::db()->query(
                    "SELECT 
                        SUM(ubg.`user_basket_good_quantity` - ubg.`user_basket_good_quantity_return`) AS count
                     FROM 
                        `user_baskets` AS ub, `user_basket_goods` AS ubg, `good_stock` AS gs, `styles` s, `styles_category` sc
                     WHERE
                            ubg.`good_id`           = '" . $this->id . "' 
                        AND ub.`user_basket_id`     = ubg.`user_basket_id`
                        AND ub.`user_basket_status` = 'delivered'
                        AND ub.`user_basket_dealer` = '-1'
                        AND ubg.`good_stock_id`     = gs.`good_stock_id`
                        AND gs.`style_id`           = s.`style_id`
                        AND s.`style_category`      = sc.`id`
                        AND ubg.`user_basket_good_payment_id` > '0'
                        " . ((!empty($i)) ? "AND ub.`user_basket_delivered_date` >= NOW() - INTERVAL {$i}" : '') . "
                     GROUP BY ubg.`good_id`");
            
            $foo = $r->fetch();
                         
            return (int) $foo['count'];          
        }
        
        function print_comments($parrent, $level) 
        {
            $sth = App::db()->prepare("SELECT 
                                 c.`comment_id`,
                                 c.`comment_text` AS `text`,
                                 c.`comment_date` AS `date`,
                                 c.`comment_visible`,
                                 c.`user_id`,
                                 c.`comment_parent`, 
                                 u.`user_login`,
                                 u.`user_designer_level`,
                                 u.`user_email`,
                                 u.`user_phone`,
                                 u.`user_name`,
                                 u.`user_show_name`
                              FROM `comments` AS c, `users` AS u
                              WHERE 
                                     c.`object_id`       = :id
                                 AND c.`object_type`     = 'good' 
                                 AND c.`user_id`         = u.`user_id` 
                                 AND c.`comment_visible` = '1'
                                 AND c.`comment_parent`  = :parrent
                                 AND u.`user_status`     = 'active'
                              ORDER BY c.`comment_date` DESC");
            
            $level++;
            
            $sth->execute(array(
                'id' => $this->id, 
                'parrent' => $parrent));
            
            if ($sth->rowCount() > 0)
            {
                foreach ($sth->fetchAll() AS $v)
                {
                    $v['level'] = $level;
                    $v['class'] = 'level-' . $level;
                    
                    $this->comments[] = $v;
                    
                    $this->print_comments($v['comment_id'], $level);
                }
            }

            $level--;
        }
        
        /**
         * Получить комментарии к работе
         * древовидная структура
         */
        public function getComments($page = null, $limit = 1000, $User)
        {
            $this->comments = array();
                
            $sth = App::db()->prepare("SELECT
                         c.`comment_id`,
                         c.`comment_text` AS `text`,
                         c.`comment_date` AS `date`,
                         c.`comment_visible`,
                         c.`user_id`,
                         u.`user_login`,
                         u.`user_designer_level`,
                         u.`user_email`,
                         u.`user_phone`,
                         u.`user_name`,
                         u.`user_show_name`
                      FROM `comments` AS c, `users` AS u
                      WHERE
                             c.`object_id`       = :id
                         AND c.`object_type`     = 'good' 
                         AND c.`user_id`         = u.`user_id` 
                         AND c.`comment_parent`  = '0'
                         AND u.`user_status`     = 'active'
                      ORDER BY c.`comment_date` DESC
                      LIMIT " . ($page * $limit) . ", " . $limit);
            
            $sth->execute(array('id' => $this->id));
            
            $count = $sth->rowCount();
            
            if ($count > 0)
            {
                $level = 0;
            
                foreach ($sth->fetchAll() as $k => $v)
                {
                    $v['level'] = $level;
                    $v['class'] = 'level-' . $level;
                    
                    $this->comments[] = $v;
                    
                    $this->print_comments($v['comment_id'], $level);
                }
                
                $carma = new carma;
                
                foreach($this->comments AS &$v)
                {
                    $v['user_avatar']         = userId2userGoodAvatar($v['user_id'], 50);
                    $v['user_designer_level'] = designerLevelToPicture($v['user_designer_level']);
                    $v['date']                = datefromdb2textdate($v['date'], 1);
            
                    $car = $carma->getCommentCarma('good_comment', $v['comment_id']);
                    
                    if ($v['comment_visible'] == -1) {
                        $v['text'] = '<span class="comment-deleted">Комментарий удалён</span>';
                    } else {
                        if ($car < carma::$carmaHideComment)
                            $v['text'] = "<a href='javascript:void(0);' onclick='showHiddenComment(this);' class='hiddenControl'>Показать</a>&nbsp;&nbsp;<span class='hiddenComment'><br />".stripslashes($v['text']).'</span>';
                        else
                            $v['text'] = stripslashes($v['text']);
                    }
                        
                    $v['carma'] = $carma->generateCarmaBlock('good_comment', $v['comment_id'], $car, $User);
            
                    if ($this->user_id === $v['user_id']) 
                        $v['author_comment'] = 'author';
            
                    if (preg_match("/^user[0-9]*/", $v['user_login']) == 1)
                    {
                        if (!empty($v['user_name']) && $v['user_name'] != 'NULL' && $v['user_show_name'] == 'true') {
                            $v['user_login'] = $v['user_name'];
                        } elseif (!empty($v['user_email'])) {
                            $dog = strpos($v['user_email'], '@');
                            $v['user_login'] = (($dog > 3) ? substr($v['user_email'], 0, $dog / 2) : '') . '****' . substr($v['user_email'], $dog);
                        } else {
                            $v['user_login'] = substr($v['user_phone'], 0, strlen($v['user_phone']) - 5) . '****';
                        }
                    }
                }
            }
            
            return $this->comments;
        }

        /**
         * Получить комментарии к работе
         * "Плоская" структура
         */
        public function getCommentsFlat($page = null, $limit = 1000, $User)
        {
            $this->comments = array();
                
            $sth = App::db()->prepare("SELECT
                         c.`comment_id`,
                         c.`comment_text` AS `text`,
                         c.`comment_date` AS `date`,
                         c.`comment_visible`,
                         c.`comment_parent`,
                         c.`user_id`,
                         u.`user_login`,
                         u.`user_designer_level`,
                         u.`user_email`,
                         u.`user_phone`,
                         u.`user_name`,
                         u.`user_show_name`,
                         SUM(`carma`) AS carma
                      FROM 
                        `comments` AS c
                            LEFT JOIN `carma_comments` ca ON ca.`comment_id` = c.`comment_id`, 
                        `users` AS u
                      WHERE
                             c.`object_id`       = :id
                         AND c.`object_type`     = 'good' 
                         AND c.`user_id`         = u.`user_id` 
                         AND u.`user_status`     = 'active'
                      GROUP BY c.`comment_id`
                      ORDER BY c.`comment_date` ASC
                      LIMIT " . ($page * $limit) . ", " . $limit);
            
            $sth->execute(array('id' => $this->id));
            
            $count = $sth->rowCount();
            
            if ($count > 0)
            {
                foreach ($sth->fetchAll() as $k => $v)
                {
                    if (!empty($v['comment_parent'])) {
                        $this->comments[$v['comment_id']]['citate'] = $this->comments[$v['comment_parent']]['text'];
                    }
                        
                    
                    if (!$this->comments[$v['comment_id']]) {
                        $this->comments[$v['comment_id']] = $v;
                    } else {
                        $this->comments[$v['comment_id']] = array_merge($this->comments[$v['comment_id']], $v);
                    }
                }
                
                $this->comments = array_reverse($this->comments);
                
                $carma = new carma;
                
                foreach($this->comments AS &$v)
                {
                    $v['user_avatar']         = userId2userGoodAvatar($v['user_id'], 50);
                    $v['user_designer_level'] = designerLevelToPicture($v['user_designer_level']);
                    $v['date']                = datefromdb2textdate($v['date'], 1);
                    $v['carma']               = intval($v['carma']);
                    
                    if ($v['comment_visible'] == -1) {
                        $v['text'] = '<span class="comment-deleted">Комментарий удалён</span>';
                    } else {
                        if ($v['carma'] < carma::$carmaHideComment)
                            $v['text'] = "<a href='javascript:void(0);' onclick='showHiddenComment(this);' class='hiddenControl'>Показать</a>&nbsp;&nbsp;<span class='hiddenComment'><br />".stripslashes($v['text']).'</span>';
                        else
                            $v['text'] = stripslashes($v['text']);
                    }
                        
                    $v['carma'] = $carma->generateCarmaBlock('good_comment', $v['comment_id'], $v['carma'], $this->user);
            
                    if ($this->user_id === $v['user_id']) 
                        $v['author_comment'] = 'author';
            
                    if (preg_match("/^user[0-9]*/", $v['user_login']) == 1)
                    {
                        if (!empty($v['user_name']) && $v['user_name'] != 'NULL' && $v['user_show_name'] == 'true') {
                            $v['user_login'] = $v['user_name'];
                        } elseif (!empty($v['user_email'])) {
                            $dog = strpos($v['user_email'], '@');
                            $v['user_login'] = (($dog > 3) ? substr($v['user_email'], 0, $dog / 2) : '') . '****' . substr($v['user_email'], $dog);
                        } else {
                            $v['user_login'] = substr($v['user_phone'], 0, strlen($v['user_phone']) - 5) . '****';
                        }
                    }
                }
            }
            
            return $this->comments;
        }

        public function getCommentsFlatV2($page = null, $limit = 1000, $User)
        {
            $this->comments = array();
                
            $sth = App::db()->prepare("SELECT
                         c.`comment_id`,
                         c.`comment_text` AS `text`,
                         c.`comment_date` AS `date`,
                         c.`comment_visible`,
                         c.`comment_parent`,
                         c.`user_id`,
                         u.`user_login`,
                         u.`user_designer_level`,
                         u.`user_email`,
                         u.`user_phone`,
                         u.`user_name`,
                         u.`user_show_name`,
                         SUM(`carma`) AS carma
                      FROM 
                        `comments` AS c
                            LEFT JOIN `carma_comments` ca ON ca.`comment_id` = c.`comment_id`, 
                        `users` AS u
                      WHERE
                             c.`object_id`       = :id
                         AND c.`object_type`     = 'good' 
                         AND c.`user_id`         = u.`user_id` 
                         AND u.`user_status`     = 'active'
                      GROUP BY c.`comment_id`
                      ORDER BY c.`comment_date` ASC
                      LIMIT " . ($page * $limit) . ", " . $limit);
            
            $sth->execute(array('id' => $this->id));
            
            $count = $sth->rowCount();
            
            if ($count > 0)
            {
                $carma = new carma;
                $parents = [];
                
                foreach ($sth->fetchAll() as $k => $v)
                {
                    $v['user_avatar']         = userId2userGoodAvatar($v['user_id'], 50);
                    $v['user_designer_level'] = designerLevelToPicture($v['user_designer_level']);
                    $v['date']                = datefromdb2textdate($v['date'], 1);
                    $v['carma']               = intval($v['carma']);
                    
                    if ($v['comment_visible'] == -1) {
                        $v['text'] = '<span class="comment-deleted">Комментарий удалён</span>';
                    } else {
                        if ($v['carma'] < carma::$carmaHideComment)
                            $v['text'] = "<a href='javascript:void(0);' onclick='showHiddenComment(this);' class='hiddenControl'>Показать</a>&nbsp;&nbsp;<span class='hiddenComment'><br />".stripslashes($v['text']).'</span>';
                        else
                            $v['text'] = stripslashes($v['text']);
                    }
                        
                    $v['carma'] = $carma->generateCarmaBlock('good_comment', $v['comment_id'], $v['carma'], $User);
            
                    if ($this->user_id === $v['user_id']) 
                        $v['author_comment'] = 'author';
            
                    if (preg_match("/^user[0-9]*/", $v['user_login']) == 1)
                    {
                        if (!empty($v['user_name']) && $v['user_name'] != 'NULL' && $v['user_show_name'] == 'true') {
                            $v['user_login'] = $v['user_name'];
                        } elseif (!empty($v['user_email'])) {
                            $dog = strpos($v['user_email'], '@');
                            $v['user_login'] = (($dog > 3) ? substr($v['user_email'], 0, $dog / 2) : '') . '****' . substr($v['user_email'], $dog);
                        } else {
                            $v['user_login'] = substr($v['user_phone'], 0, strlen($v['user_phone']) - 5) . '****';
                        }
                    }
                    
                    if (!empty($v['comment_parent'])) {
                        if (in_array($v['comment_parent'], array_keys($parents))) {
                            $parents[$v['comment_id']] = $parents[$v['comment_parent']];
                        } else {
                            $parents[$v['comment_id']] = $v['comment_parent'];
                        }
                        
                        $this->comments[$parents[$v['comment_id']]]['childrens'][$v['comment_id']] = $v; 
                    } else {
                        $this->comments[$v['comment_id']] = $v;
                    }
                }
                
                $this->comments = array_reverse($this->comments);
            }
            
            return $this->comments;
        }

        /**
         * принять работу на голосование
         */
        function approve()
        {
            $this->getPictures();

            $carma = new carma;
            $u = new user($this->user_id);

            // 1. APPROVE NEW GOOD
            if ($this->good_status == 'new')
            {
                // если при отправке работы не снимали галку "отправить на голосование"
                if ($this->good_vote_visible > 0)
                {
                    $this->change(array(
                        'good_visible' => 'true', 
                        'good_status' => 'voting', 
                        'good_voting_start' => NOWDATE,
                    ));

                    $sth = App::db()->prepare("SELECT `comment_text` AS text FROM `comments` WHERE  `object_id` = :id AND `object_type` = 'newgood'");
                    $sth->execute(array('id' => $this->id));
                    $hs_comments = $sth->fetchAll();
                    
                    // 2 Оповещаем самого автора о выходе его работы на голосование
                    App::mail()->send($this->user_id, 218, array(
                        'good_id' => $this->id, 
                        'good_name' => $this->good_name,
                        'good' => $this,
                        'hs_comments' => $hs_comments,
                    ));
                    
                    // 4. UPDATE AUTHOR CARMA
                    $carma->updateUserCarma($this->user_id, 'approve_good', $carma->carmaFor['approveMyGoog']);

                    $u->watch($this->id, 'good');

                    $u->setMeta('goodApproved', (int) $u->meta->goodApproved + 1);
                    
                    // 5. проверяем не была ли работа привязана к надписи
                    $sth = App::db()->prepare("UPDATE `notees_goods` SET `approved` = '1' WHERE `good_id` = :gid AND `approved` = '0' LIMIT 1");
                        
                    $sth->execute(array(
                        'gid' => $this->id,
                    ));
                }
                else
                {
                    $this->change(array('good_visible' => 'true', 'good_status' => 'archived'));
                }
            }
            else
            {
                $this->change(array('good_visible' => 'true'));
            }

            // сбрасываем дату загрузки, чтобы кэшилка могла увидеть эту работу 
            // если это не паттерн
            //if (!$this->pics['patterns']) 
            //{
                App::db()->query("UPDATE `good_pictures` SET `upload_time` = NOW() WHERE `good_id` = '" . $this->id . "' AND `pic_name` IN ('" . implode("', '", array_keys(self::$srcs)) . "')");
            //}
            
            return true;
        }

        /**
         * Отклонить новую работу
         * @return bool
         */
        function deny()
        {
            $data = array('good_status' => 'deny');
            
            if ($this->good_visible == 'modify')
                $data += array('good_visible' => 'true');
            
            $this->change($data);

            App::mail()->send(array($this->user_id, 6199), (self::$denyReasone[$this->logs['hudsovet_vote'][0]['info']]['tpl']) ? self::$denyReasone[$this->logs['hudsovet_vote'][0]['info']]['tpl'] : 23, [
                'goodId' => $this->id,
                'goodName' => $this->good_name,
                'goodAutor' => $this->user_id,
                'good' => $this,
            ]);

            return true;
        }

        /**
         * Отправить работу в архив
         */
        function archive()
        {
            $this->change(array(
                'good_visible' => 'true',
                'good_status' => 'archived',
                'good_vote_visible' => 0,
            ));
            
            $sth = App::db()->prepare("SELECT `comment_text` AS text FROM `comments` WHERE  `object_id` = :id AND `object_type` = 'newgood'");
            $sth->execute(array('id' => $this->id));
            $hs_comments = $sth->fetchAll();
            
            App::mail()->send(array($this->user_id), 408, array(
                'good_id' => $this->id, 
                'good_name' => $this->good_name,
                'user_login' => $this->user_login,
                'hs_comments' => $hs_comments,
                'good' => $this,
            ));
            
            App::db()->query("UPDATE `good_pictures` SET `upload_time` = NOW() WHERE `good_id` = '" . $this->id . "' AND `pic_name` IN ('" . implode("', '", array_keys(self::$srcs)) . "')");
        }
        
        /**
         * Проверить получала ли работа медальку
         */
        function isWinner()
        {
            $foo = App::db()->query(sprintf("SELECT (SELECT COUNT(*) FROM `good_winners` WHERE `good_id` = '%d' LIMIT 1) + (SELECT COUNT(*) FROM `competition_winners` WHERE `good_id` = '%d' LIMIT 1) AS s", $this->id, $this->id))->fetch(); 
            return $foo['s'];
        }
        
        /**
         * Выкупить работа у автора
         * @param array $data массив с параметрами
         */
        function buyout($data = null)
        {
            $sth = App::db()->prepare("INSERT IGNORE INTO `good__buyout` 
                                       SET 
                                        `good_id` = :good, 
                                        `approved` = '1'
                                        "
                                        .
                                            ($data['contract_img'] ? ", `contract_img` = '" . intval($data['contract_img']) . "'" : '')
                                        .
                                        " 
                                    ON DUPLICATE KEY UPDATE 
                                        `date` = NOW()");
            $sth->execute(['good' => $this->id]);
            
            $this->good_buyout = 1;
            $this->save();
            
            return true;
        }
        
        /**
         * Проверить выкуплена ли работа у автора
         */
        function isBuyout()
        {
            $foo = App::db()->query(sprintf("SELECT `good_id` FROM `good__buyout` WHERE `good_id` = '%d' AND `approved` = '1' LIMIT 1", $this->id))->fetch();
            return $foo['good_id'];
        }


        /**
         * Сгенерировать акт передачи прав для работы в формате word
         * @return string путь до сгенерированного акта
         * @throws Exception
         */
        function act()
        {
            // путь до шаблона
            $tpl_path    = DS . 'admin/templates' . DS . 'contracts' . DS . (($this->isWinner()) ? 'act' : 'act.pretendent') . '.docx';
            // папка для распаковки шаблона
            $extr_folder = DS . 'application/views/templates_c' . DS . 'act.docx.tmp.' . uniqid() . DS;
            // путь до файла с текстом
            $text_file   = $extr_folder . 'word' . DS . 'document.xml';
            // путь до изображения
            $img_file    = $extr_folder . 'word/media/image1.png';
            // имя выходного файла
            $file_name   = 'act.' . $this->id . '.docx';
            // полный путь до выходного файла
            $out_file    = DS . 'application/views/templates_c' . DS . $file_name;

            $U = new user($this->user_id);
            
            $find    = array('NAME', 'DATE', 'AUTHOR', 'PASSPORT', 'PASS_GIVEN', 'CONTRACT');
            
            $replace = array(
                $this->good_name, 
                datefromdb2textdate($this->good_date, 3), 
                (!empty($this->user_name))? $this->user_name: '______________________________',
                (!empty($U->meta->passport))? $U->meta->passport : '______________',
                (!empty($U->meta->passport_given)) ? $U->meta->passport_when_given . ', ' . $U->meta->passport_given : '___________________________________________',
                (!empty($U->meta->contract) || !empty($U->meta->contract_copyrights)) ? (!empty($U->meta->contract) ? $U->meta->contract : $U->meta->contract_copyrights) : '_______________',
            );

            $word = new ZipArchive;

            if ($word->open(ROOTDIR . $tpl_path) === TRUE)
            {
                createDir($extr_folder);

                if ($word->extractTo(ROOTDIR . $extr_folder))
                {
                    // правим текст
                    $text = file_get_contents(ROOTDIR . $text_file);
                    $text = str_replace($find, $replace, $text);

                    $f = fopen(ROOTDIR . $text_file, 'w+');
                    fwrite($f, $text);
                    fclose($f);

                    // заменяем картинку
                    $p = $this->generateGoodPreview(0, '', 'disk');

                    rename(ROOTDIR . $p['path'], ROOTDIR . $img_file);

                    $w = new ZipArchive;

                    if ($w->open(ROOTDIR . $out_file, ZipArchive::CREATE) === TRUE)
                    {
                        for ($i=0; $i < $word->numFiles; $i++)
                        {
                            $w->addFile(ROOTDIR . $extr_folder . $word->getNameIndex($i), $word->getNameIndex($i));
                        }

                        $w->close();
                        $word->close();

                        deletepicture($p['id']);

                        if (is_dir(ROOTDIR . $extr_folder))
                            rmdirR(ROOTDIR . $extr_folder);

                        return $out_file;
                    }
                    else
                        throw new Exception ('cannot save outputed file');
                }
                else
                    throw new Exception ('word template extracting error');
            }
            else
                throw new Exception ('word template not found');
        }


        /**
         * Логируем в базе действие с работой
         * @var string $a - действие
         * @var string $r - результат
         * @var string $i - дополнительная информация
         */
        function log($a, $r, $i, $u) 
        {
            App::db()->query("INSERT INTO `good__log` SET
                            `good_id` = '" . $this->id . "',
                            `action`    = '" . addslashes($a) . "',
                            `result`    = '" . addslashes($r) . "', 
                            `info`      = '" . addslashes($i) . "',  
                            `user_id`   = '" . $u ."'");
                            
            array_unshift($this->logs[$a], array(
                'result'  => $r,
                'info'    => $i,
                'user_id' => $u,
            ));
        }

        /**
         * Удалить запись из базы
         * @var string $a - действие
         */
        function delLog($a) 
        {
            App::db()->query("DELETE FROM `good__log` WHERE
                            `good_id` = '" . $this->id . "' AND 
                            `action`    = '" . addslashes($a) . "'");
                                        
            unset($this->logs[$a]);
        }
        
        /**
         * геттер логов
         * функция срабатывает при обращении к логам
         */
        function getlogs($actions)
        {
            if (!is_array($actions))
                $actions = (array) $actions; 
            
            foreach ($actions as $k => $a) 
            {
                $actions[$k]['action'] = addslashes($actions[$k]['action']);
            }

            $r = App::db()->query("SELECT * FROM `good__log` WHERE `good_id` = '" . $this->id . "' " . ((count($actions) > 0) ? "AND `action` IN ('" . implode("','", $actions) . "')" : '') . " ORDER BY `time` DESC");
            
            $this->logs = array();
            
            foreach ($r->fetchAll() AS $l) 
            {
                $this->logs[$l['action']][] = $l;
            }
            
            return $this->logs;
        }
        
        /**
         * @param string имя параметра
         * @param string значение параметра
         * 
         * @return int кол-во затронутых строк 
         */
        function setMeta($name, $value)
        {
            $r  = App::db()->query(sprintf("INSERT `" . self::$metatable . "` SET `good_id` = '%d', `meta_name` = '%s', `meta_value` = '%s' 
                                 ON DUPLICATE KEY UPDATE 
                                    `meta_value` = '%s',
                                    `date` = NOW()", $this->id, addslashes($name), addslashes($value), addslashes($value)));
            $this->meta->$name = $value;
            return $r->rowCount();
        }
        
        /**
         * @return array массив meta-параметров
         */
        function getmeta()
        {
            $this->meta = new \stdClass();
            
            $r = App::db()->prepare("SELECT `meta_name`, `meta_value` FROM `" . self::$metatable . "` WHERE `good_id` = :id");
            $r->execute(['id' => $this->id]);
            
            if ($r->rowCount() > 0)
            {
                foreach ($r->fetchAll() AS $m) {
                    $this->info['meta']->$m['meta_name'] = stripslashes($m['meta_value']);
                }
            }
            
            return $this->info['meta'];
        }
        
        /**
         * Удалить значенеи meta-параметра
         * @param string name
         */
        function delMeta($name)
        {
            unset($this->meta->$name);
            return App::db()->query(sprintf("DELETE FROM `" . self::$metatable . "` WHERE `good_id` = '%d' AND `meta_name` = '%s' LIMIT 1", $this->id, addslashes($name)));
        }
        
        /**
         * @return array массив с оценками работы на голосовании
         */
        function getvotes()
        {
            $this->votes = array();
            
            $r = App::db()->query("SELECT v.*, u.`user_login`, u.`user_designer_level`, INET_NTOA(v.`user_ip`) AS user_ip
              FROM `" . self::$votetable . "` v
                 LEFT JOIN `users` u ON v.`user_id` = u.`user_id`
              WHERE v.`good_id` = '" . $this->id . "'");
            
            foreach ($r->fetchAll() AS $v) {
                $v['date'] = datefromdb2textdate($v['time']);
                $v['designer_level'] = user::designerLevel2Picture($v['user_designer_level']);
                $this->votes[] = $v;
            }
            
            return $this->votes;
        }
        
        /**
         * Обработка названия работы перед сохранением
         * - замена первой буквы на заглавную
         */
        public static function prepareName($name)
        {
            $replace = array( 
               "а"=>"А","б"=>"Б","в"=>"В","г"=>"Г","д"=>"Д",
               "е"=>"Е","ё"=>"Ё","ж"=>"Ж",
               "з"=>"З","и"=>"И","й"=>"Й","к"=>"К","л"=>"Л",
               "м"=>"М","н"=>"Н","о"=>"О","п"=>"П","р"=>"Р",
               "с"=>"С","т"=>"Т","у"=>"У","ф"=>"Ф","х"=>"Х",
               "ц"=>"Ц","ч"=>"Ч","ш"=>"Ш","щ"=>"Щ","ъ"=>"Ъ",
               "ы"=>"Ы","ь"=>"Ь","э"=>"Э","ю"=>"Ю","я"=>"Я");
              
            $first = strtoupper(strtr(mb_substr($name, 0, 1), $replace));
            
            return $first . mb_substr($name, 1);
        }
        
        /**
         * Скачать все исходники работы одним архивом
         * @param $src mixed список скачиваемых исходников
         */
        public function exportSrcs($src)
        {
            $z = new ZipArchive;
            
            $zipfile = tempnam(sys_get_temp_dir(), 'zip_') . '.zip';
            
            if ($z->open($zipfile, ZipArchive::CREATE) === TRUE) {}
            else
                throw new Exception ('cannot save outputed file');
            
            $src = (array) $src;
            
            foreach (good::$srcs as $k => $s) 
            {
                if (count($src) > 0 && !in_array($k, $src)) {
                    continue;
                }
                
                if ($this->pics[$k] && file_exists(ROOTDIR . $this->pics[$k]['path'])) 
                {
                    $z->addFile(ROOTDIR . $this->pics[$k]['path'], $k . '.jpg');
                }
            }
            
            $z->close();
                
            file_force_download($zipfile);
            
            @unlink(ROOTDIR . $zipfile);
            
            exit('stop');
        }
        
        /**
         * Выгрузка всех превью работы единым архивом
         */
        public function exportPics($style)
        {
            if (empty($style))
            {
                $z = new ZipArchive;
            
                $zipfile = tempnam(sys_get_temp_dir(), 'zip_') . '.zip';
                
                if ($z->open($zipfile, ZipArchive::CREATE) === TRUE) {}
                else
                    throw new Exception ('cannot save outputed file');
                
                foreach (styleCategory::$BASECATS as $k => $cat) 
                {
                    if ($k == 'cup')
                        continue;
                    
                    if ($k == 'bag')
                        continue;
                    
                    if ($k == 'pillows')
                        continue;
                    
                    if (!$cat['promo'])
                        continue;
                    
                    if (!$this->pics[$cat['src_name']])
                        continue;
                    
                    foreach ((array) $cat['promo'] as $sex => $s) 
                    {
                        $ch = curl_init('http://cache.maryjane.ru/' . $k . '/' . $s . '/' . $this->id . ($cat['sexes'] ? '.model.' : '.') . $this->pics[$cat['src_name']]['update_timestamp'] . '.jpeg');
                      
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)');
                        
                        $content = curl_exec($ch);
                        
                        if (empty($content)) 
                        {
                           $ch = curl_init();
                           
                           curl_setopt_array($ch, array(
                                CURLOPT_URL            => 'http://www.maryjane.ru/s3/cache/' . $k . '/' . $s . '/' . $this->id . ($cat['sexes'] ? '.model.' : '.') . $this->pics[$cat['src_name']]['update_timestamp'] . '.jpeg/',
                                CURLOPT_RETURNTRANSFER => TRUE,
                                CURLOPT_USERAGENT      => 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)',
                            ));
                            
                            $content = curl_exec($ch); 
                           
                            $ch_error = curl_errno($ch);
                            
                            if (!empty($ch_error))  
                            {
                                printr(curl_error($ch));
                            }
                        }
                        
                        if (strlen($content) > 0) {
                            $fileName = tempnam(sys_get_temp_dir(), 'zip_');
                            file_put_contents($fileName, $content);
                            
                            $z->addFile($fileName, $k . '_' . $s . '.jpg');
                        }
                    }
                }

                $z->close();
                
                file_force_download($zipfile);
                
                exit('stop');
            }
            else 
            {
                $S = new style($style);

                $ch = curl_init('http://cache.maryjane.ru/' . $S->category . '/' . $S->id . '/' . $this->id . ($S->cat_parent == 1 ? '.model.' : '.') . $this->pics[styleCategory::$BASECATS[$S->category]['src_name']]['update_timestamp'] . '.jpeg');
                
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)');
                
                $content = curl_exec($ch);

                if (empty($content)) 
                {
                    $ch = curl_init('http://www.maryjane.ru/s3/cache/' . $S->category . '/' . $S->id . '/' . $this->id . ($S->cat_parent == 1 ? '.model.' : '.') . $this->pics[styleCategory::$BASECATS[$S->category]['src_name']]['update_timestamp'] . '/');

                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)');
                    
                    $content = curl_exec($ch);
                }
                
                header('Content-type: image/jpeg');
                exit($content);
            }
        }

        /**
         * Сгенерировать исходник для наклеек и гаджетов
         * @param int $good_stock_id носитель на складе
         * @param string $side сторона
         */
        public function getStickermizeSrc($style_id, $side = 'back')
        {
            $perssizes = array(
                '315' => array('w' => 1070, 'h' => 1570), // 4, 4s
                '599' => array('w' => 1070, 'h' => 1570), // 4, 4s
                '354' => array('w' => 1080, 'h' => 1690), // 5, 5s
                '628' => array('w' => 1080, 'h' => 1690), // 5, 5s
                '666' => array('w' => 1160, 'h' => 1795), // 6
                '643' => array('w' => 1160, 'h' => 1795), // 6
                '667' => array('w' => 1280, 'h' => 2090), // 6+
                '644' => array('w' => 1280, 'h' => 2090), // 6+
            );
            
            // картинки носителей
            $sth = App::db()->query("SELECT s.`style_id`, p.`picture_path`, s.`style_print_block`, sc.`cat_parent` 
                        FROM 
                            `styles_category` AS sc, `styles` AS s
                                " . (($side == 'front') ? "LEFT JOIN `pictures` p ON s.`style_front_picture` = p.`picture_id`" : '') . "
                                " . (($side == 'back')  ? "LEFT JOIN `pictures` p ON s.`style_back_picture` = p.`picture_id`" : '') . "
                        WHERE 
                            s.`style_id` = '{$style_id}' AND sc.`id` = s.`style_category`
                        LIMIT 1");
                        
            $style = $sth->fetch();
        
            $S = new style($style['style_id']);
            
            $category = $S->category;
            
            // для кружек просто выдаём оригинальный исходник
            if ($S->category == 'cup')
            {
                $i = new Imagick(ROOTDIR . $this->pics['cup']['path']);
            }
            else 
            {
                $realwidth = $S->getRealWidth('back');
                
                // ищем индивидуальные координаты накладываемых изображений
                $sth = App::db()->query("SELECT `side`, `x`, `y`, `w`, `h` FROM `good__positions` WHERE `good_id` = '" . $this->id . "' AND `good_stock_id` = '" . $style['style_id'] . "'");
                            
                foreach ($sth->fetchAll() as $pos) {
                    $sides[$pos['side']]['coords'] = $pos;
                }
            
                // выбираем имеющиеся текстовые надписи
                $sth = App::db()->query("SELECT * FROM `good__texts` WHERE `good_id` = '" . $this->id . "' AND `side` = '{$side}'");
                            
                if ($sth->rowCount() > 0)           
                {
                    foreach ($sth->fetchAll() as $l) {
                        $sides[$l['side']]['textLayers'][] = $l;
                    }
                    
                    include ROOTDIR . '/vendor/fonts.php';
                }
                
                if ($this->pics[styleCategory::$BASECATS[$S->category]['src_name']] || $this->pics[styleCategory::$BASECATS[$S->category]['src_name'] . '_back'])
                {
                    if ($side == 'front' && $this->pics[styleCategory::$BASECATS[$S->category]['src_name']]) {
                        $src_path = $this->pics[styleCategory::$BASECATS[$S->category]['src_name']]['path'];
                    } elseif ($side == 'front' && !$this->pics[styleCategory::$BASECATS[$S->category]['src_name']] && $this->pics[styleCategory::$BASECATS[$S->category]['src_name'] . '_back']) {
                        $src_path = $this->pics[styleCategory::$BASECATS[$S->category]['src_name'] . '_back']['path'];
                    } elseif ($side == 'back' && $this->pics[styleCategory::$BASECATS[$S->category]['src_name'] . '_back']) {
                        $src_path = $this->pics[styleCategory::$BASECATS[$S->category]['src_name'] . '_back']['paths'];
                    } elseif ($side == 'back' && !$this->pics[styleCategory::$BASECATS[$S->category]['src_name'] . '_back'] && $this->pics[styleCategory::$BASECATS[$S->category]['src_name']]) {
                        $src_path = $this->pics[styleCategory::$BASECATS[$S->category]['src_name']]['path'];
                    }
            
                    if (!empty($src_path)) {
                        $sides[$side]['src'] = new Imagick(ROOTDIR . $src_path);
                    }
                }
                
                // СОБИРАЕМ ИСХОДНИК
                if ($S->category == 'stickers') {
                    $width  = $sides[$side]['src']->getImageWidth();
                    $height = $sides[$side]['src']->getImageHeight();
                    $resize = 1;
                } else {
                    if ($perssizes[$S->id]) {
                        $width  = $perssizes[$S->id]['w'];
                        $height = $perssizes[$S->id]['h'];
                    } else {
                        $height = styleCategory::$BASECATS[$S->category]['src']['h'];
                        $width  = $height * ($S->print_block[$side]['w'] / $S->print_block[$side]['h']);
                    }
                    
                    if ($S->print_block[$side]['w'] > $S->print_block[$side]['h']) {
                        $resize = $width / $S->print_block[$side]['w'];
                    } else {
                        $resize = $height / $S->print_block[$side]['h'];
                    }
                }
                
                
                
                if ($this->good_status == 'customize' && $this->ps_onmain_id && $this->ps_onmain_id != 18) {
                    $bg = new ImagickPixel('#' . colorId2hex($this->ps_onmain_id));
                } else {
                    $bg = new ImagickPixel('white');
                }
                
                $i = new Imagick;
                $i->newImage($width, $height, $bg);
                   
                // 1. накладываем картинку если есть
                if ($sides[$side]['src'])
                {
                    if ($sides[$side]['coords'] && $S->category != 'stickers') 
                    {
                        if ($sides[($side == 'front') ? 'back' : 'back']['coords'])
                        {
                            extract($sides[($side == 'front') ? 'back' : 'back']['coords']);
                        }
                        else
                        {
                            extract($sides[$side]['coords']);
                        }
                        
                        $sides[$side]['src']->scaleImage($w * $resize, $h * $resize);
                    }

                    // накладываем изображение
                    $i->compositeImage($sides[$side]['src'], imagick::COMPOSITE_OVER, $x * $resize, $y * $resize);
                }
    
                // 2. накладываем надписи
                foreach($sides[$side]['textLayers'] as $k => $l)
                {
                    $fn       = get_font_file($l['font_name'], unserialize($l['font_style']), $fonts_aliases);
                    $fontSize = (int) $l['font_size'] * good::$fontSizeCorrection * $resize;
                    
                    $l['text'] = stripslashes($l['text']);
                    
                    $tt = new Imagick;
            
                    $draw = new ImagickDraw();
                    $draw->setFont($fn);
                    $draw->setFontSize($fontSize);
                    $draw->setFillColor($l['font_color']);
                    $draw->setTextAlignment(Imagick::ALIGN_LEFT);
                    
                    $metrics = $tt->queryFontMetrics($draw, $l['text']);
                    
                    $draw->annotation(0, $metrics['textHeight'] + $metrics['descender'] , $l['text']);
            
                    //$tt->newImage($metrics['textWidth'] + 2 * $metrics['boundingBox']['x1'], $metrics['boundingBox']['y2'] - $metrics['boundingBox']['y1'] + 90, new ImagickPixel('transparent'));
                    $tt->newImage($metrics['textWidth'] + 2 * $metrics['boundingBox']['x1'], $metrics['textHeight'] + $metrics['descender'] + 50, new ImagickPixel('transparent'));
                    $tt->drawImage($draw);
                    
                    $draw->clear ();
                    
                    $tt->rotateImage('', $l['a']);
                    
                    $i->compositeImage($tt, imagick::COMPOSITE_OVER, $l['x'] * $resize, $l['y'] * $resize);
                    
                    $draw->clear();
                    $tt->clear();
                }
            
                // для чехлов подрезаем исходник на 5 мм по краям
                //if ($S->category == 'cases' || strpos($S->style_slug, 'case') !== FALSE)
                //{
                    //$i->cropImage($width - ($pb_multiplier * 10), $height - ($pb_multiplier * 10), $pb_multiplier * 5, $pb_multiplier * 5);
                //}
            }

            $i->setImageFormat("png");
        
            header('Content-type: image/png');  
            exit($i);
        }

        public function getCustomizeSrc($style_id, $side = 'front')
        {
            include ROOTDIR . '/vendor/fonts.php';
        
            if ($side == 'front')
                $col = 'style_front_picture';
            else
                $col = 'style_back_picture';
        
            $sth = App::db()->query("SELECT s.`style_id`, s.`style_color` AS color_id, s.`style_sex`, s.`style_front_picture`, s.`style_back_picture`, s.`style_print_block`, p.`picture_path`, sc.*
                        FROM `styles` s, `pictures` p, `styles_category` sc 
                        WHERE s.`style_id` = '" . $style_id . "' AND p.`picture_id` = s.`$col` AND sc.`id` = s.`style_category`
                        LIMIT 1");
                        
            $style = $sth->fetch();
        
            // координаты, размеры
            $sth = App::db()->query("SELECT pp.`x`, pp.`y`, pp.`w`, pp.`h`, pp.`a` FROM `good__positions` pp WHERE pp.`good_id` = '" . $this->id . "' AND (pp.`good_stock_id` = '" . $style['style_id'] . "') AND pp.`side` = '{$side}' LIMIT 1");
            $sizes = $sth->fetch();
                
            $pb = unserialize($style['style_print_block']);
        
            $pb[$side]['left'] = $pb[$side]['x'] - ($pb[$side]['w'] / 2);
            $pb[$side]['top']  = $pb[$side]['y'] - ($pb[$side]['h'] / 2);
            
            
            // размеры рабочей области
            // + 100 - это костыль для невлезающего текста
            $block_w = max($pb[$side]['w'], $sizes['w']) * src_resize + 100;
            $block_h = max($pb[$side]['h'], $sizes['h']) * src_resize + 100;
        
            // собираем изображение
            $block = new Imagick();
            $block->newImage($block_w, $block_h, new ImagickPixel("transparent"));
            //$block->setImageOpacity(1);
            //$block->setImageOpacity(0);
        
            if ($side == 'front') {
                $src_name = styleCategory::$BASECATS[$style['cat_slug']]['src_name'];
            }
                
            if ($side == 'back') {
                $src_name = styleCategory::$BASECATS[$style['cat_slug']]['src_name'] . '_back'; 
            }
                
            // накладываем картинку
            $src_path = $this->pics[$src_name]['path'];
            
            if (!empty($src_path)) 
            {
                try 
                {
                    $src = new Imagick(ROOTDIR . $src_path);
                    
                    if (!empty($sizes['a']))
                        $src->rotateImage('', $sizes['a']);
                    
                    if (!empty($sizes['w']) && !empty($sizes['h']))
                        $src->scaleImage($sizes['w'] * src_resize, $sizes['h'] * src_resize);
                    else {
                        $src->scaleImage($block_w, ($block_w / $src->getImageWidth()) * $src->getImageHeight());
                    }
                    
                    $block->compositeImage($src, imagick::COMPOSITE_OVER, $sizes['x'] * src_resize, $sizes['y'] * src_resize);
                } 
                catch (Exception $e) 
                {
                    printr($e);
                }
            }
        
            $sth = App::db()->query("SELECT * FROM `good__texts` WHERE `good_id` = '" . $this->id . "' AND `side` = '{$side}' AND `text` != ''");
        
            $textLayers = $sth->fetchAll();
        
            // накладываем надписи
            foreach($textLayers as $k => $l) 
            {
                $l['text'] = stripslashes($l['text']);
                $l['style'] = unserialize($l['font_style']);
                
                $fn       = get_font_file($l['font_name'], unserialize($l['font_style']), $fonts_aliases);
                $fontSize = (int) ($l['font_size'] * good::$fontSizeCorrection);
                
                $tt = new Imagick;
                
                $draw = new ImagickDraw();
                $draw->setFont($fn);
                $draw->setFontSize($fontSize * src_resize);
                $draw->setFillColor($l['font_color']);
                $draw->setTextAlignment(Imagick::ALIGN_LEFT);
                
                $metrics = $tt->queryFontMetrics($draw, $l['text']);
                
                $draw->annotation(5, $metrics['textHeight'] + $metrics['descender'] - 18, $l['text']);
        
                $tt->newImage($metrics['textWidth'] + 2 * $metrics['boundingBox']['x1'] + 100, $metrics['textHeight'] + $metrics['descender'] + 120, new ImagickPixel('transparent'));
                $tt->drawImage($draw);
                
                $tt->rotateImage('', $l['a']);
                
                $block->compositeImage($tt, imagick::COMPOSITE_OVER, $l['x'] * src_resize, $l['y'] * src_resize);
                
                $draw->clear();
                $tt->clear();
            }
            
            $block->setImageFormat('png');
            $block->setImageCompression(\Imagick::COMPRESSION_UNDEFINED);
            $block->setImageCompressionQuality(0);
            $block->stripImage();

            header('Content-type: image/png; charset=utf-8');
            exit($block);
        }

        public static function findAll( array $param = null)
        {
            foreach ($param as $k => $v) {
                if (is_array($v))
                    foreach ($v as $k1 => $v1) {
                        $param[$k][$k1] = addslashes($v1);
                    }
                else
                    $param[$k] = addslashes($v);
            }
            
            $r = App::db()->query("SELECT g.* 
                  FROM 
                    `" . self::$dbtable . "` g
                  WHERE 1
                    " 
                    . ($param['visible'] = 1 ? "AND g.`good_visible` = 'true'" : '')
                    . ($param['user'] ? "AND g.`user_id` = '" . $param['user'] . "'" : '')
                    . ($param['domain'] ? "AND (g.`good_domain` = 'all' OR g.`good_domain` = '" . addslashes($param['domain']) . "')" : '')
                    . ($param['status'] ? "AND g.`good_status` IN ('" . implode("', '", $param['status']) . "')" : '') );
            
            if ($r->rowCount() > 0) 
            {
                $goods = array();
                
                foreach ($r->fetchAll() AS $g)
                {
                    $g['good_name'] = stripslashes($g['good_name']);
                    
                    $goods[$g['good_id']] = $g;
                }
            }
            
            return $goods;
        }
    }

?>