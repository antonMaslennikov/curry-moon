<?
    namespace application\models;
    
    use \smashEngine\core\App AS App; 
    use \PDO;
    use \S3Thumb AS S3Thumb;
    
    /**
     * класс работы с носителями
     */
    class style extends \smashEngine\core\Model
    {
        public $id   = 0;
        public $info = array();
        
        /**
         * @var имя таблицы в БД для хранения экземпляров класса
         */ 
        public static $dbtable = 'styles';
        
        /**
         * @var имя таблицы в БД для хранения изображений экземпляров класса
         */ 
        public static $dbtable_pictures = 'styles_pictures';
        
    
        function __construct($id)
        {
            $this->id = (int) $id;
            
            if (!empty($this->id))
            {
                $r = App::db()->prepare(
                         "SELECT
                            s.*, c.`hex` AS color_hex, c.`name` AS color_name, c.`group` AS color_group, sc.`name` AS cat_name, sc.`cat_slug`, sc.`cat_parent`, p1.`picture_path` AS front_picture, p2.`picture_path` AS back_picture
                          FROM 
                            `good_stock_colors` c, 
                            `styles_category` sc, 
                            `styles` AS s
                                LEFT JOIN `pictures` AS p1 ON p1.`picture_id` = s.`style_front_picture`
                                LEFT JOIN `pictures` AS p2 ON p2.`picture_id` = s.`style_back_picture`
                          WHERE 
                            s.`style_id` = :id AND s.`style_color` = c.`id` AND s.`style_category` = sc.`id`
                          LIMIT 1");
                
                $r->execute(['id' => $this->id]);
                
                if ($r->rowCount() == 1) 
                {
                    $this->info = $r->fetch();

                    $this->info['style_name']        = htmlspecialchars(stripslashes($this->info['style_name']));
                    $this->info['style_description'] = (stripslashes($this->info['style_description'])); // убрал преобразование спецсимволов иначе во внутряке каталога теги не отображаются
                    $this->info['style_composition'] = (stripslashes($this->info['style_composition']));
                    
                    if (!empty($this->style_print_block))
                    {
                        $this->print_block = unserialize($this->style_print_block);
                    }
                    
                    if ($this->info['cat_parent'] > 1) {
                        $this->info['category'] = styleCategory::$BASECATSid[$this->info['cat_parent']];
                    } else {
                        $this->info['category'] = $this->cat_slug;
                    }

                    return $this->info;
                } 
                else 
                    throw new Exception ('style ' . $this->id . ' not found');
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
                App::db()->query(sprintf("UPDATE `%s` SET %s WHERE `style_id` = '%d' LIMIT 1", self::$dbtable, implode(',', $rows), $this->id));
            }
            // создание
            else
            {
                App::db()->query(sprintf("INSERT INTO `%s` SET %s", self::$dbtable, implode(',', $rows)));
                $this->id = App::db()->lastInsertId();
            }
        }
        
        /**
         * Добавить работе новое изображение
         *
         * @param string $name
         * @param int $name - имя картинки
         * @param int $id - id файла
         * @param int $w - ширина
         * @param int $h - высота
         * 
         * @return int affected rows count
         */
        function addPic($name, $id)
        {
            if (empty($id) || empty($name)) {
                throw new Exception('Error adding picture', 2);
            }
            
            // удаляем файл если эта картинка уже была загружена ранее
            if (!in_array($name, array('front', 'back', 'front_model', 'back_model')) && $this->pics[$name]) {
                $this->delPic($name);
            }
            
            // вставляем новую запись
            $sth = App::db()->prepare("INSERT INTO `" . self::$dbtable_pictures . "` 
                SET 
                    `style_id`  = :sid, 
                    `pic_name` = :pic_name, 
                    `pic_id`   = :pic_id
                ON DUPLICATE KEY UPDATE 
                    `pic_id`   = :pic_id");
            
            $sth->execute(array(
                'sid' => $this->id,
                'pic_name' => $name,
                'pic_id' => $id,
            ));

            $this->pics[$name] = array(
                'id'    => $id,
                'path'  => pictureId2path($id),
            );
                
            return $sth->rowCount();
        }
        
        function delPic($names)
        {
            if (!is_array($names))
                $names = (array) $names;
            
            if (count($names) == 0)
                return false;
            
            foreach ($names AS $k => $n)
            {
                    deletepicture($this->pics[$n]['id']);
                    unset($this->pics[$n]);
                
                $names[$k] = addslashes($n);
            }
            
            App::db()->query(sprintf("DELETE FROM `" . self::$dbtable_pictures . "` WHERE `style_id` = '%d' AND `pic_name` IN ('%s')", $this->id, implode("', '", $names)));
        }
        
        public function getPictures($names)
        {
            return $this->getPics();
        }
        
        /**
         * Получить список картинок носителя
         */
        public function getPics($names = null)
        {
            if (!is_array($names))
                $names = (array) $names;
                
            $r = App::db()->query("SELECT sp.`pic_name`, sp.`pic_id` AS id, sp.`x`, sp.`y`, sp.`w`, sp.`h`, p.`picture_path` AS path FROM `styles_pictures` sp, `pictures` p WHERE sp.`style_id` = '" . $this->id . "' " . ((count($names) > 0) ? "AND sp.`pic_name` IN ('" . implode("','", $names) . "')" : '') . " AND ABS(sp.`pic_id`) = p.`picture_id`");

            foreach ($r->fetchAll() AS $p) 
            {
                if (strpos($p['pic_name'], '_preview') !== FALSE)
                {
                    $this->pics[str_replace('_preview', '', $p['pic_name'])]['preview']   = $p['path'];
                }
                elseif ($p['pic_name'] == 'front')
                {
                    $this->pics['front'][] = $p;
                }
                else
                    $this->pics[$p['pic_name']] = $p;
            }
            
            return $this->pics; 
        }

        public static function find($search)
        {
            if (!empty($search))
            {
                $r = App::db()->query("SELECT `style_id`
                          FROM `styles`
                          WHERE `style_id` = '" . addslashes(trim($search)) . "' OR `style_slug` = '" . addslashes(trim($search)) . "' OR `style_name` = '" . addslashes(trim($search)) . "'
                          LIMIT 1");
                
                if ($r->rowCount() == 1) 
                {
                    $foo = $r->fetch();
                    
                    return new self($foo['style_id']);
                } 
                else
                    return false; 
                    //throw new Exception ('Носитель "' . $search . '" не найден');
            }
        }

        public static function findAll($param)
        {
            foreach ($param as $k => $v) {
                if ($k == 'orderby') {
                    continue;
                }
                if (is_array($v))
                    foreach ($v as $k1 => $v1) {
                        $param[$k][$k1] = addslashes($v1);
                    }
                else
                    $param[$k] = addslashes($v);
            }
            
            if ($param['cat'])
            {
                if ($param['cat'] == 'gadgets')
                    $aq[] = "sc.`cat_parent` > 1";
                else  
                    $aq[] = "sc.`cat_parent` = 1";
            }
                
            if ($param['excludecat'])
            {
                $aq[] = "sc.`id` NOT IN ('" . implode("', '", $param['excludecat']) . "')";
            }
            
            if ($param['exclude_style'])
            {
                $aq[] = "s.`style_id` NOT IN ('" . implode("', '", $param['exclude_style']) . "')";
            }
            
            if ($param['onstock'] || $param['onstock2'] || $param['onstock3'])
            {
                $af[] = "(gs.`good_stock_quantity` +- gs.`good_stock_inprogress_quantity`)  AS quantity";
                $af[] = "gs.`good_stock_price` AS price";
                $af[] = "gs.`good_stock_discount` AS discount";
                $af[] = 'sz.`size_id`';
                $af[] = 'sz.`size_name`';
                $af[] = 'sz.`size_meta`';
                $aq[] = "gs.`size_id` = sz.`size_id`";
                $aq[] = "gs.`good_stock_visible` > '0'";
                $at[] = '`sizes` sz';
            }
            
            if ($param['onstock'])
            {
                $aq[] = "gs.`good_stock_quantity` +- gs.`good_stock_inprogress_quantity` > '0'";
            }
            
            if ($param['onstock2'])
            {
            }
            
            if ($param['onstock3'])
            {
                $aq[] = "(gs.`good_stock_quantity` +- gs.`good_stock_inprogress_quantity` >= '50' || ((s.`style_category` = '70' || s.`style_category` = '17') && s.`style_color` = '21'))";
            }
            
            if ($param['have_preview'])
            {
                $aq[] = "(s.`style_front_picture` > '0' OR s.`style_back_picture` > '0')";
            }
            
            if ($param['front_picture'])
            {
                $at[] = 'pictures p';
                $aq[] = "s.`style_front_picture` = p.`picture_id`";
                $af[] = 'p.`picture_path`';
            }
            
            if ($param['model_front_picture'])
            {
                $at[] = 'pictures p';
                $at[] = 'styles_pictures sp';
                $aq[] = "sp.`pic_id` = p.`picture_id`";
                $aq[] = "sp.`style_id` = s.`style_id`";
                $aq[] = "sp.`pic_name` = 'front'";
                $af[] = 'p.`picture_path`';
            }
            
            if ($param['back_picture'])
            {
                $at[] = 'pictures pb';
                $aq[] = "s.`style_back_picture` = pb.`picture_id`";
                $af[] = 'pb.`picture_path` AS back';
            }
            
            if ($param['category'])
            {
                foreach (styleCategory::$BASECATS as $k => $c) {
                    if ($c['id'] == $param['category'] || $param['category'] == $k) 
                    {
                        // тряпка
                        if ($c['sexes'])
                            if ($c['id'] == $param['category'])
                                $aq[] = "sc.`id` = '" . intval($param['category']) . "'";
                            else
                                $aq[] = "sc.`cat_slug` = '" . $param['category'] . "'";
                        else
                            if ($c['id'] == $param['category'])
                                $aq[] = "sc.`cat_parent` = '" . intval($param['category']) . "'";
                            else 
                                $aq[] = "sc.`cat_parent` = '" . intval(styleCategory::$BASECATS[$param['category']]['id']) . "'";
                            
                        break;
                    }
                }
            }
            
            if ($param['cat_id'])
            {
                if (is_array($param['cat_id'])) 
                    $aq[] = "sc.`id` IN ('" . implode("','", $param['cat_id']) . "')";
                else
                    $aq[] = "sc.`id` = '" . intval($param['cat_id']) . "'";
            }
            
            if ($param['categorys'])
            {
                if ($param['cat'] == 'gadgets')
                    $aq[] = "sc.`cat_parent` IN ('" . implode("','", (array) $param['categorys']) . "')";
                else
                    $aq[] = "sc.`id` IN ('" . implode("','", (array) $param['categorys']) . "')";
            }
            
            if ($param['sex'] && in_array($param['sex'], array('male', 'female', 'kids', 'unisex')))
            {
                $aq[] = "s.`style_sex` = '" . $param['sex'] . "'";
            }
            
            if ($param['color'])
            {
                $aq[] = "s.`style_color` = '" . intval($param['color']) . "'";
            }
            
            $q = "SELECT 
                    s.`style_id`, s.`style_name`, s.`style_slug`, s.`style_description`, s.`style_category`, s.`style_sex`, s.`style_color`, s.`style_order`, s.`style_viewsize` AS faq_id, sc.`id` AS category_id, sc.`name` AS category_name, sc.`cat_slug`, sc.`cat_parent`, c.`id` AS color_id, c.`hex` AS color_hex, c.`name` AS color_name, c.`name_en`, c.`group` AS color_group, gs.`good_stock_id`, gs.`good_stock_status` AS stock_status, gs.`size_rus`
                    "
                    .
                    (($af) ? ', ' . implode(', ', $af)  : '')   
                    .   
                    "
                  FROM 
                    `styles` s, 
                    `styles_category` sc, 
                    `good_stock_colors` c, 
                    `good_stock` gs 
                    "
                    .
                    (($at) ? ', ' . implode(', ', $at)  : '')   
                    .   
                    "
                  WHERE
                        s.`style_category` = sc.`id`
                    AND s.`style_color`    = c.`id`
                    "
                    .
                    (($aq) ? ' AND ' . implode(' AND ', $aq)  : '') 
                    .   
                    "
                    AND s.`style_visible`  = '1'
                    AND s.`style_id` NOT IN ('291')
                    AND s.`style_category` NOT IN ('71')
                    AND s.`style_id` = gs.`style_id`
                    AND gs.`good_stock_visible` = '1'
                    AND gs.`good_id` = '0'
                  ORDER BY 
                    " . ($param['orderby'] ? $param['orderby'] : "sc.`id`, s.`style_order`, s.`style_id` DESC");

            //printr($q, 1);      
                  
            $r = App::db()->query($q);

            $Thumb = new S3Thumb(array('ic1.maryjane.ru', 'ic2.maryjane.ru', 'ic3.maryjane.ru', 'ic4.maryjane.ru'), S3AccessKey, S3SecretKey, S3CryptKey);
                              
            foreach ($r->fetchAll() AS $s)
            {
                if ($s['picture_path'])
                    $s['thmb245'] = $Thumb->url($s['picture_path'], 245);
                
                if ($s['cat_parent'] > 1) {
                    $s['category'] = styleCategory::$BASECATSid[$s['cat_parent']];
                } else {
                    $s['category'] = $s['cat_slug'];
                }
                
                if ($s['size_meta'])
                    $s['size_meta'] = json_decode($s['size_meta'], 1);
                
                if ($param['onstock'] || $param['onstock2'] || $param['onstock3']) {
                    $s['tprice'] = $s['price'] - round($s['price'] / 100 * $s['discount']);
                }
                
                if (!$param['notgrouped'])
                    $styles[$s['style_id']] = $s;
                else
                    $styles[] = $s;
            }

            return $styles;
        }
        
        /**
         * @return float коэфф-т пропорциональности между реальным размером области запечатки и на картинке
         */
        public function getRealWidth($side)
        {
            if (!empty($this->print_block[$side]['real_w'])) {
                $real_w = $this->print_block[$side]['real_w'];
            }
            
            if (empty($real_w))
                $real_w = styleCategory::$BASECATS[$this->category]['def_real_w'][$side]; 
                
            return $real_w;
            //return $this->print_block[$side]['w'] / ($real_w / 10);
        }
    }