<?php

    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
    use \application\models\styleCategory AS styleCategory;
    use \application\models\style AS style;
    use \application\models\good AS good;
    use \application\models\user AS user;
    use \application\models\stock AS stock;
    use \application\models\carma AS carma;
    use \application\models\generateTurn AS generateTurn;
    
    use \DateTime;    
    use \PDO;
    use \Exception;
    
    use Geo;
    use S3Thumb;
     
    class Controller_catalog extends \smashEngine\core\Controller
    {
        // зарезервированные ключевые слова для урлов каталога
        protected static $keywords = [
            'tag', 'nottag', 'page', 'winners', 'preview', 'selected', 'sitemap', 'zoom', 'styles', 'ajax',
            'male', 'female', 'kids', // полы
            'new', 'popular', 'grade', 'artdir', 'visits', 'popular_by_category', 'likes', 'avg_grade', 'avg_grade_designers', 'rating', 'recently_buy', 'recently_view', 'hot', 'actions', // сортировки
            'top2013', 'top2014', 'top2015', 'top2016', // сортировки
        ];
        
        public function __construct(\Routing\Router $router)
        {
            parent::__construct($router);
        }
        
        /**
         * Смена адреса посетителя 
         * на случай если геолокация ошиблась и неверно определила город  
         */
        public function action_set_location()
        {
            $g = new geo();
        
            if (!$this->page->reqUrl[1]) {
                $this->page->reqUrl[1] = 1;
            }
            
            if ($this->page->reqUrl[1] == 1) 
            {
                $geo['city'] = 'Москва';
                $geo['region'] = 'Москва';
                $geo['district'] = 'Центральный федеральный округ';
                $geo['country'] = 'RU';
            }
            else 
            {
                $geo['city'] = 'Санкт-Петербург';
                $geo['region'] = 'Санкт-Петербург';
                $geo['district'] = 'Северо-Западный федеральный округ';
                $geo['country'] = 'RU';
            }
            
            setcookie($g->cookiename, json_encode_cyr($geo), time()+3600*24*7, '/');
        
            $this->page->refresh();
        }
        
        public function action_search_autocomplite()
        {
            $out = array();
        
            $sth = App::db()->prepare("SELECT `name` FROM `tags` WHERE `name` LIKE :search AND `synonym_id` = '0' AND `tag_ps_goods` > '1' AND `name` NOT LIKE '% %' ORDER BY `tag_ps_goods` DESC LIMIT 10");
            
            $sth->execute(array('search' => trim($_GET['q']) . '%'));
            
            foreach ($sth->fetchAll() AS $c)    {
                $out[] = mb_strtolower(stripslashes($c['name']));
            }
            
            exit(json_encode(array_slice(array_unique($out), 0, 10)));
        }
        
        public function action_index()
        {
            $this->page->index_tpl = 'index.tpl';
                   
            if ($this->page->lang != 'en') {
                $this->VARS['usdRate'] = 1;
            }
            
            if ($this->page->title)
                $this->page->title = (array) $this->page->title;
            else
                $this->page->title = array();
            
            $this->page->import(array(
                '/css/catalog/styles.css',
            ));
            
            $task = 'start';
            
            $this->view->setVar('backLink', $_SERVER['HTTP_REFERER']);
            
            
            // =======================================================================================
            // получаем список всех доступных размеров
            // =======================================================================================
            if (!$CATALOG_SIZES = App::memcache()->get('CATALOG_SIZES'))
            {
                $sth = App::db()->query("SELECT `size_id`, `size_name` FROM `sizes` ORDER BY `order` DESC");
                
                foreach ($sth->fetchAll() as $s) {
                    $CATALOG_SIZES[$s['size_id']] = strtolower(stripslashes($s['size_name']));
                }
                
                App::memcache()->set('CATALOG_SIZES', $CATALOG_SIZES, false, 10 * 86400);
            } 
            
            // =======================================================================================
            // получаем список всех доступных цветов
            // =======================================================================================
            if (!$CATALOG_COLORS = App::memcache()->get('CATALOG_COLORS'))
            {
                $sth = App::db()->query("SELECT `id`, `slug`, `name` FROM `good_stock_colors`");
                
                foreach ($sth->fetchAll() as $s) {
                    $CATALOG_COLORS['slug'][$s['id']] = stripslashes($s['slug']);
                    $CATALOG_COLORS['name'][$s['id']] = stripslashes($s['name']);
                }
                
                App::memcache()->set('CATALOG_COLORS', $CATALOG_COLORS, false, 10 * 86400);
            } 
            
            /**
             * Доступные цвета подложки для монохромных наклеек
             */
            $autocolors = array(
                84 => array(
                    'color_name' => 'серый',
                    'color_hex' => 'ababab',
                ),
                37 => array(
                    'color_name' => 'белый',
                    'color_hex' => 'fff',
                ),
                1 => array(
                    'color_name' => 'чёрный',
                    'color_hex' => '000',
                ),
            );
            
            $rreqUrl = $filters = array();
        
            foreach ($this->page->reqUrl as $k => $v)
            {
                $f = explode(';', $v);
        
                if (count($f) > 0)
                {
                    foreach ($f as $kk => $fv)
                    {
                        $ff = explode(',', $fv);
        
                        foreach ($ff as $fvv) {
                            $rreqUrl[] = $fvv;
                        }
                    }
                }
            }
            
            $reqUrl = $rreqUrl;
            
            // =======================================================================================
            // разбираем урл и ищем в нём фильтры
            // =======================================================================================
            foreach ($reqUrl as $k => $v) 
            {
                if (in_array($v, array_keys(styleCategory::$BASECATS)) && $v != 'stickers')
                {
                    $filters['category'] = $v;
                    $this->view->setVar('razdel', styleCategory::$BASECATS[$v]['title_2']);
                }
                elseif (in_array($v, array('male', 'female', 'kids')))  
                {       
                    $filters['sex'] = $v;
                    $sex_filtered = TRUE;
                }
                elseif (array_search($v, $CATALOG_COLORS['slug']))  
                {
                    $filters['color'] = array_search($v, $CATALOG_COLORS['slug']);
                    $filters['color_name'] = $CATALOG_COLORS['name'][$filters['color']];
                    $color_filtered = TRUE;
                }
                elseif (array_search(strtolower($v), $CATALOG_SIZES))   
                {
                    $filters['size'] = array_search(strtolower($v), $CATALOG_SIZES);
                    $filters['size_name'] = $CATALOG_SIZES[$filters['size']];        
                    $size_filtered = TRUE;
                }
                // устаревший вид урла
                elseif ($v == 'category') 
                {
                    if (!empty($reqUrl[$k + 1])) {
                        $filters['category'] = $reqUrl[$k + 1];
                        $this->view->setVar('razdel', styleCategory::$BASECATS[$reqUrl[$k + 1]]['title_2']);
                    } else {
                        $this->page404();
                    }
                }
                // устаревший вид урла
                elseif ($v == 'color')
                {
                    $filters['color'] = $reqUrl[$k + 1];
                    $filters['color_name'] = $CATALOG_COLORS['name'][$reqUrl[$k + 1]];
                    $color_filtered = TRUE;
                }
                // устаревший вид урла
                elseif ($v == 'size') 
                {       
                    $filters['size'] = $reqUrl[$k + 1];
                    $filters['size_name'] = $CATALOG_SIZES[$reqUrl[$k + 1]];
                    $size_filtered = TRUE;
                }
                elseif (in_array($v, array('enduro', 'atv', 'jetski', 'snowmobile', 'helmet', 'helm', 'zakaz-nakleek')))
                {
                    //$filters['category'] = $v;
                    $this->view->setVar('razdel', styleCategory::$BASECATS[$v]['title_2']);
                }
            }
            // =======================================================================================
            // END разбираем урл и ищем в нём фильтры
            // =======================================================================================
            
            if ($size_filtered)
                $this->view->setVar('size_filtered', $size_filtered);
            
            //printr($filters, 1);
        
            /**
             * Распарсиваем фильтры страницы из урла
             */
            foreach ($reqUrl as $k => $v)
            {
                if ($continue) {
                    unset($continue);
                    continue;
                }
                
                switch ($v)
                {
                    case 'catalog':
                        break;
                        
                    /**
                     * Страница партнёра
                     */
                    case 'selected':
                        
                        if ($this->user->authorized && empty($this->page->reqUrl[$k + 1])) {
                            array_push($this->page->reqUrl, $this->user->user_login);
                            $this->page->go('/' . implode('/', $this->page->reqUrl) . '/', 301);
                        }
                        
                        $this->page->noindex = TRUE;
                        $this->view->setVar('partner', TRUE);
        
                        break;
                        
                    /**
                     * Установить дефолтный носитель работы для отображения на витрине
                     */
                    case 'setdefaultstyle':
                    
                        $task = $v;
                        break 2;
                        
                        break;
                        
                    case 'offerAccepted':
                        
                        $this->view->setVar('offerAccepted', TRUE);
                        
                        break;
                    
                    case 'home':
                        
                        $task = 'start';
                        
                        break;
                        
                    case 'disabled':
                        
                        $task = $v;
                        
                        break;
                        
                    case 'archived':
                        
                        $filters['good_status'] = 'archived';
                        
                        break;
                        
                    case '1color':
                        
                        $task = $v;
                        
                        break;
                        
                    case 'collection':
                        break;
                        
                    case 'ready':
                        break;
                        
                    case 'notees':
                        
                        $task = 'main';
                        $continue = $notees = true;
                        
                        $at[] = '`notees_goods` AS ng';
                        $aq[] = "ng.`good_id` = g.`good_id`";
                        $aq[] = "ng.`note_id` = '" . intval($this->page->reqUrl[1]) . "'";
                        
                        break;
                        
                    case 'enduro':
                    case 'jetski':
                    case 'atv':
                    case 'snowmobile':
                    case 'helmet':
                    case 'helm':
                    case 'zakaz-nakleek':
            
                        if (!$search) {
                            $task = $v;
                        }
                        
                        $continue = true;
                        
                        break;
                        
                    // режим просмотра
                    case 'preview':
                    case 'models':
                        
                        $mode = $v;
                        //$continue = true;
                        
                        $this->view->setVar('mode', $mode);
                        
                        break;
                        
                    case 'relatedTags':
                    case 'disabled':
                    case 'tiptop':
        
                        $task = 'main';
        
                        break;
                        
                    case 'frame':
                    case 'paper':
                    case 'canvas':
                    case 'plastic':
                    
                        $poster_type = $breaked = $v;
                    
                        $this->page->noindex = true;
                        
                        break;
                        
                    case 'horizontal':
                    case 'vertical':
                    case 'square':
                    
                        $poster_orientation = $breaked = $v;
                    
                        break;
                    
                    case 'top':
                        
                        $top = TRUE;
                        
                        $goods = array(10562,15506,16071,17767,15618,14258,16423,16107,19891,12871,16940,16042,18034,19615,21456,19798,31357,18971,18425,15606,17442,15933,15972,15584,15597,32127,16118,13086,16970,14318);
                        
                        if ($filters['category'] == 'poster')
                            $goods = array_merge($goods, array(32426,39070,37330,53921,51277,49522,49253,45785,45757,42260,38309,38501,31703,31830));
                        
                        $aq[] = "g.`good_id` IN ('" . implode("', '", $goods) . "')";
                        
                        if ($filters['category'] == 'sumki' && $filters['sex'] == 'female') {}
                        elseif ($filters['category'] == 'tolstovki' && !$filters['sex']) {}
                        else
                            if (count($filters) > 0) {
                                $Pag->noindex = TRUE;
                            }
                        
                        $this->view->setVar('top', TRUE);
                        
                        break;
                    
                    case 'sitemap':
                        
                        $task = $breaked = $v;
                        
                        break;
                        
                    case 'search':
                    
                        if (isset($_GET['q']))
                        {
                            $_GET['q'] = trim($_GET['q']);
                            
                            if (empty($_GET['q'])) {
                                $this->page->tpl = 'message.tpl';
                                $this->page->title = 'Поиск - ничего не найдено';
                                $this->page->noindex = true;
                                $this->view->setVar('MESSAGE', ['text' => 'Вы не указали текст поискового запроса']);
                                $this->view->setVar('PAGE', $this->page);
                                $this->view->generate('index.tpl');
                            }
                            
                            $phrase_text = urldecode(str_replace(array('футболка', 'футболки', 'майки', 'майка', 'толстовка', 'толстовки', 'сумка', 'сумки'), '', $_GET['q']));
        
                            /**
                             * сначала пробуем искать в названиях носителей
                             * (если ищут конкретную модель гаджета)
                             */
                            if ($S = style::find($phrase_text))
                            {
                                $this->page->go('/catalog/' . $S->category . '/' . $S->style_slug . '/new/');
                            } 
                            
                            /**
                             * убираем из поискового запроса определённые слова
                             */
                            $stopwords = array(
                                0 => array('word' => 'чехол', 'replace' => 'cases'),
                                1 => array('word' => 'чехлы', 'replace' => 'cases'),
                                2 => array('word' => 'чехлов', 'replace' => 'cases'),
                                3 => array('word' => 'чехлами', 'replace' => 'cases'),
                                4 => array('word' => 'case', 'replace' => 'cases'),
                                5 => array('word' => 'cases', 'replace' => 'cases'),
                                
                                6  => array('word' => 'наклейка', 'replace' => 'auto'),
                                7  => array('word' => 'наклейки', 'replace' => 'auto'),
                                8  => array('word' => 'наклейку', 'replace' => 'auto'),
                                9  => array('word' => 'стикер', 'replace' => 'auto'),
                                10 => array('word' => 'стикеры', 'replace' => 'auto'),
                                11 => array('word' => 'sticker', 'replace' => 'auto'),
                                12 => array('word' => 'stickers', 'replace' => 'auto'),
                                12 => array('word' => 'stick', 'replace' => 'auto'),
                            ); 
                        
                            foreach ($stopwords as $word) 
                            {
                                if (strpos($phrase_text, $word['word'] . ' ') !== false)
                                {
                                    if (!array_search($word['replace'], $this->page->reqUrl)) {
                                        $this->page->reqUrl[] = $word['replace'];
                                    }
                                    
                                    $phrase_text = str_replace(array($word['word'], 'с'), '', $phrase_text);    
                                }
                            }
                        } else {
                            $phrase_text = urldecode(trim($this->page->reqUrl[$k + 1]));
                        }
                    
                        $task = 'main';
                    
                        $this->page->title[0] = (($this->page->lang == 'en') ? 'Search results for: ' : 'Результаты поиска по запросу: ') . urldecode($phrase_text);
                    
                    
                        $sth = App::db()->prepare("SELECT `id`, `phrase`, `synonym`, `r301` FROM `search` WHERE `phrase` = :phrase LIMIT 1");
                    
                        $sth->execute(array(
                            'phrase' => $phrase_text,
                        ));
                        
                        if ($phrase = $sth->fetch())
                        {
                            if (!empty($phrase['r301']))
                            {
                                $this->page->go($phrase['r301'], 301);
                            }
                        }
                        else
                        {
                            $phrase['phrase'] = trim($this->page->reqUrl[2]);
                            
                            $sth = App::db()->prepare("INSERT INTO `search` SET `phrase` = :phrase, `hits` = '0'");
                            
                            $sth->execute(array(
                                'phrase' => $phrase['phrase'],
                            ));
                            
                            $phrase['id'] = App::db()->lastInsertId();
                        }
                    
                        
                        if (empty($this->page->reqUrl[3]))
                        {
                            try
                            {
                                // считаем этот "поиск"
                                // кол-во использований обновляется триггером 
                                $sth = App::db()->prepare("SELECT `id` FROM `search_hits` WHERE `phrase_id` = :phrase_id AND `ip` = INET_ATON(:ip) AND `time` >= NOW() - INTERVAL 5 MINUTE");
                                    
                                $sth->execute(array(
                                    'phrase_id' => $phrase['id'], 
                                    'ip' => $_SERVER['REMOTE_ADDR'],
                                )); 
                                        
                                if ($sth->rowCount() == 0)
                                {
                                    $sth = App::db()->prepare("INSERT INTO `search_hits` SET `phrase_id` = :phrase_id, `user_id` = :user_id, `ip` = INET_ATON(:ip)");
                                    
                                    $sth->execute(array(
                                        'phrase_id' => $phrase['id'], 
                                        'user_id' => $this->user->id, 
                                        'ip' => $_SERVER['REMOTE_ADDR'],
                                    ));
                                }
                            }
                            catch (PDOException $e) 
                            {
                                 printr($e->getMessage()); 
                            }
                        }
                        
                        // собираем все синонимы в один поисковый запрос
                        if ($phrase['synonym']) {
                            // если фраза входит в какую-то коллекцию
                            $sth = App::db()->prepare("SELECT `id`, `phrase`, `r301` FROM `search` WHERE `id` != 'synonym1' AND (`synonym` = :synonym1 OR `synonym` = :synonym2 OR `id` = :synonym2)");
                            $sth->execute(array('synonym1' => $phrase['id'], 'synonym2' => $phrase['synonym']));
                        } else {
                            $sth = App::db()->prepare("SELECT `id`, `phrase`, `r301` FROM `search` WHERE `synonym` = :synonym1");
                            $sth->execute(array('synonym1' => $phrase['id']));
                        }
        
                        foreach ($sth->fetchAll() as $p) 
                        {
                            // если у собирающей фразы стоит редирект 
                            if ($p['id'] == $phrase['synonym'] && !empty($p['r301']))
                            {
                                $this->page->go($p['r301'], 301);
                            }
                            
                            $synonyms[] = addslashes(stripslashes($p['phrase']));
                        }
        
                        if (count($filters) == 0)
                        {
                            $sth = App::db()->prepare("SELECT t1.`slug`, t1.`pic_id`, t2.`name` FROM `tags` t1 LEFT JOIN `tags` AS t2 ON t1.`synonym_id` = t2.`tag_id` WHERE t1.`name` = :tag LIMIT 1");
                            
                            $sth->execute(array(
                                'tag' => $phrase['phrase'],
                            ));
                            
                            if ($sth->rowCount() == 1)
                            {
                                $tag = $sth->fetch();
                                
                                if ($tag['pic_id'] > 0) {
                                    $this->page->go('/tag/' . $tag['slug'] . '/', 301);
                                } else {
                                    if (!empty($tag['name']))
                                        $synonyms[] = $tag['name'];
                                }
                            }
                        }
        
                        try
                        {
                            $dbs = new PDO("mysql:host=127.0.0.1;port=9306;dbname=" . DBNAME, DBUSER, DBPASS, array(
                                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", 
                                PDO::ATTR_PERSISTENT => true,
                                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
                            
                            $ss = preg_replace("/[^a-zA-ZА-Яа-я0-9_-|()\s]/u", '', $phrase['phrase']);
                            $ss = str_replace(array('||'), '|', $ss);
                            
                            $sth = $dbs->query("SELECT * FROM goods WHERE MATCH('" . $ss . "') LIMIT 0, 10000");
                               
                            foreach ($sth->fetchAll() AS $r) {
                                $result['matches'][$r['id']] = $r['id'];
                                $search_main_phrase_goods[] = $r['id'];
                            }
                            
                            // если к поисковой фразе есть синонимы
                            if ($synonyms)
                            {
                                $ss = '(' . implode(')|(', array_diff($synonyms, array('', ' '))) . ')';
                                $ss = preg_replace("/[^a-zA-ZА-Яа-я0-9_-|()\s]/u", '', $ss);
                                $ss = str_replace(array('||'), '|', $ss);
                            
                                $sth = $dbs->query("SELECT * FROM goods WHERE MATCH('" . $ss . "') LIMIT 0, 10000");
                                
                                foreach ($sth->fetchAll() AS $r) {
                                    $result['matches'][$r['id']] = $r['id'];
                                }
                            }
                        }
                        catch (Exception $e)
                        {
                            printr($e);
                        }
                        
                        $aq[] = "g.`good_id` IN ('" . implode("', '", array_keys($result["matches"])) . "')";
                        
                        if (!$this->user->meta->mjteam || $this->user->meta->mjteam == 'fired')
                            $aq[] = "(g.`good_sold_year` > '0' OR g.`user_id` = '" . $this->user->id . "')";
                        
                        $search = $breaked = TRUE;
                        
                        $this->view->setVar('SEARCH', urldecode($phrase_text));
                        $this->page->noindex = true;
                    
                        break;
                        
                    case 'zoom':
                        
                        $zoom = TRUE; 
                        $task = 'good';
                        
                        break;
                        
                    case 'styles':
                        
                        $breaked = $task = 'styles';
                        
                        break 2;
        
                        break;
                    
                    case 'futbolki':
                    case 'longsleeve_tshirt':           
                    case 'tolstovki':
                    case 'sweatshirts':
                    case 'mayki':
                    case 'mayki-alkogolichki':
                    case 'platya':
                    case 'sumki':
                    case 'patterns':
                    case 'patterns-sweatshirts':
                    case 'patterns-tolstovki':
                    case 'patterns-bag':
                    case 'phones':
                    case 'laptops':
                    case 'touchpads':
                    case 'cases':
                    case 'bumper':
                    case 'ipodmp3':
                    case 'moto':
                    case 'boards':
                    case 'auto':
                    case 'auto-simple':
                    case 'cup':
                    case 'bag':
                    case 'textile':
                    case 'pillows':
                    case 'bomber':
                    case 'body':
                    case 'poster':
                            
                        if (!$good_id && styleCategory::$BASECATS[$v])
                        {
                            $this->view->setVar('category', $filters['category']);
                            $this->view->setVar('category_sexes', count(styleCategory::$BASECATS[$filters['category']]['sexes']));
                            
                            // носитель явно указан в урле
                            if (!empty($reqUrl[$k + 1]) && !in_array($reqUrl[$k + 1], self::$keywords))
                            {
                                if ($Style = style::find($reqUrl[$k + 1]))
                                {
                                    // если это носитель то следующую итерацию пропускаем
                                    $continue = true;
                                }
                                
                                // открыт дефолтный гаджет
                                if ($Style->category != 'cases' && $Style->id == styleCategory::$BASECATS[$v]['def_style'] && !$reqUrl[$k + 2])
                                {
                                    unset($reqUrl[$k + 1]);
                                    
                                    $this->page->go('/' . implode('/', $reqUrl) . '/', 301);
                                }
                                
                                if ($v == 'auto' || $v == 'auto-simple') {
                                    $task = $v;
                                } else {
                                    $task = 'main';
                                }
                            }
                            // носитель явно не указан
                            else 
                            {
                                if (!$tag && !$search && in_array($v, ['futbolki', 'cases', 'tolstovki', 'sweatshirts'])) {
                                    // стартовый лендинг для категории носителей
                                    $task = 'start';
                                } elseif ($v == 'patterns' || $v == 'patterns-sweatshirts' || $v == 'patterns-tolstovki' || $v == 'bomber' || $v == 'body') {
                                    // стартовый лендинг для паттернов
                                    $task = 'start-patterns'; 
                                } elseif ($v == 'auto' || $v == 'auto-simple') {
                                    $task = $v;
                                    $style_id = styleCategory::$BASECATS[$v]['def_style'];
                                    $Style = new \application\models\style($style_id);
                                } elseif ($v == 'poster') {
                                    $task = 'main';
                                } else {
                                    $task = 'main';
                                    
                                    // каталог с дефолтным носителем
                                    if (!is_array(styleCategory::$BASECATS[$v]['def_style']))
                                        $style_id = styleCategory::$BASECATS[$v]['def_style'];
                                    else {
                                        $style_id = $filters['sex'] ? styleCategory::$BASECATS[$v]['def_style'][$filters['sex']] : array_shift(styleCategory::$BASECATS[$v]['def_style']); 
                                    }
                                    
                                    $Style = new \application\models\style($style_id);
                                }
                            }
                            
                            if ($Style)
                            {
                                $filters['sex'] = $Style->style_sex;
                                $filters['category'] = $Style->category;
                                $filters['color'] = $Style->style_color;
                                
                                $style_id = $sstyle_id = $Style->id;
                                $style_name = $Style->style_name;
                                $style_slug = $Style->style_slug;
                                
                                if (empty($Style->style_front_picture) && empty($Style->style_back_picture) && !empty($Style->pics['rez']) && !empty($Style->pics['observ']) && !$search) {
                                    $Style->model_preview = $Style->pics['observ']['path'];
                                } 
                                
                                $this->view->setVar('Style', $Style);
                                $this->view->setVar('category_style', $Style->id);
                                $this->view->setVar('style_slug', $Style->style_slug);
                                $this->view->setVar('category_def_style', styleCategory::$BASECATS[$v]['def_style']);
                                
                                if ($Style->category == 'cases')
                                    $this->page->breadcrump[] = array(
                                        'link'    => '#',
                                        'caption' => 'Чехлы');
                                elseif ($Style->category == 'bumper')
                                    $this->page->breadcrump[] = array(
                                        'link'    => '#',
                                        'caption' => 'Бампер');
                                elseif ($Style->category == 'phones')
                                    $this->page->breadcrump[] = array(
                                        'link'    => '/catalog/phones/new/',
                                        'caption' => 'Наклейки на телефон');
                                elseif ($Style->category == 'laptops')
                                    $this->page->breadcrump[] = array(
                                        'link'    => '/catalog/laptops/new/',
                                        'caption' => 'Наклейки на ноутбук');
                                elseif ($Style->category == 'touchpads')
                                    $this->page->breadcrump[] = array(
                                        'link'    => '/catalog/touchpads/new/',
                                        'caption' => 'Наклейки на планшет');
                                elseif ($Style->category == 'ipodmp3')
                                    $this->page->breadcrump[] = array(
                                        'link'    => '/catalog/ipodmp3/new/',
                                        'caption' => 'Наклейки на плеер');
                                elseif ($Style->category == 'cup')
                                    $this->page->breadcrump[] = array(
                                        'link'    => '#',
                                        'caption' => 'Для дома');
                                elseif ($Style->category == 'poster')
                                    $this->page->breadcrump[] = array(
                                        'link'    => "/catalog/{$v}/",
                                        'caption' => 'Постеры');
                            }
        
                            switch ($filters['category']) {
                                case 'phones':
                                    switch ($style_slug) {
                                        case 'iphone-4-bumper':
                                        case 'iphone-5-bumper':
                                            $this->page->title[] = $Style->style_name;
                                            break;
                                        default:
                                            $this->page->title[] = 'Наклейки на ' . $Style->cat_name . ' ' . $Style->style_name;
                                            /*
                                            $this->page->breadcrump[] = array(
                                                'link'    => '#',
                                                'caption' => $Style->cat_name);
                                            */
                                            break;
                                    }
                                    break;
                                case 'touchpads':
                                    $this->page->title[] = 'Наклейки на ' . $Style->style_name;
                                    break;
                                 case 'ipodmp3':
                                    $this->page->title[] = 'Виниловые наклейки на ' . $Style->style_name;
                                    break;
                                case 'laptops':
                                    $this->page->title[] = 'Наклейки на ' . $Style->style_name;
                                    break;
                                case 'cases':
                                    $this->page->title[] = $Style->style_name . ' купить';
                                    break;
                                 case 'boards':
                                    $this->page->title[] = 'Наклейки на доски';
                                    break;
                                case 'bumper':
                                    $this->page->title[] = 'Силиконовый бампер для ' . $Style->style_name;
                                    break;
                                case 'poster':
                                    array_unshift($this->page->title, 'Постеры');
                                    break;
                            }
        
                            if ($Style && $v != 'patterns' && $v != 'patterns-sweatshirts' && $v != 'patterns-tolstovki' && $v != 'patterns-bag') {
                                $this->page->breadcrump[] = array(
                                    'link'    => '/' . $this->page->module . "/$v/",
                                    'caption' => ($v != 'cup' ? $Style->cat_name : '') . ' ' . $Style->style_name);
                            }
                        }
        
                        break;
                        
                    case 'disk':
                        
                        $task = 'moto_disk';
                        
                        break;  
                        
                    case 'stickers':
                        
                        if (!$this->page->reqUrl[2])
                        {
                            if (!$breaked)
                                $task = 'start';
                            
                            $this->page->breadcrump[] = array(
                                'link'    => '/' . $this->page->module . "/$v/",
                                'caption' => 'Стикеры');
                        } else {
                            
                            if (!$breaked)
                                $task = 'auto';
                            
                            $Style = new style(288);
                        }
                        
                        break;
        
                    /**
                     * новинки
                     */
                    case 'hot':
                    case 'actions':
                        
                        $task = 'main';
                        $orderBy = 'new';
                        $onPage  = 500;
                        $at[] = '`good__buyout` gb'; 
                        $aq[] = "gb.`good_id` = g.`good_id`";
                        $aq[] = "gb.`approved` = '1'";
                        $hot  = true;
                        $this->view->setVar('HOT', true);
                        $this->view->setVar('ACTIONS', true);
        
                        $this->page->breadcrump[] = array(
                            'link' => '/' . $this->page->module . "/$v/",
                            'caption' => 'Победители недели со скидкой'
                        );
                        
                        $this->page->title[] = 'Победители недели со скидкой';
        
                        break;
        
                    // сохранение персональных данных автора (тайтл, дескрипшн, шапка)
                    case 'savePersonalData':
        
                        if ($this->user->id = $U->id)
                        {
                            if (!empty($_POST['personal_title']))
                                $this->user->setMeta('personal_title', addslashes(substr(strip_tags($_POST['personal_title']), 0, 255)));
                            else
                                $this->user->delMeta('personal_title');
                            
                            if (!empty($_POST['meta_description'])) {
                                
                                if ($this->user->id != 6199) {
                                    $_POST['meta_description'] = strip_tags($_POST['meta_description']); 
                                }
                                
                                $this->user->setMeta('meta_description', addslashes(substr($_POST['meta_description'], 0, 10000)));
                            } else
                                $this->user->delMeta('meta_description');
        
                            
                            // Если указан файл для шапки
                            if (!empty($_FILES['personal_header']['tmp_name']))
                            {
                                $result = catchFileNew('personal_header', IMAGEUPLOAD . '/printshop/personal_headers/', '', $ext = 'png,jpg,jpeg,gif', 980, 100, 980, 100);
        
                                if ($result['status'] == 'ok')
                                    $this->user->setMeta('personal_header', (int) $result['id']);
                                else
                                    $error = $result['message'];
                            }
                            
                            if ($this->page->reqUrl[3] == 'delete_header')
                            {
                                $this->user->delMeta('personal_header');
                            }
                        }
        
                        if (!$error) {
                            header('location: ' . $_SERVER['HTTP_REFERER']);
                            exit();
                        } else {
                            $this->view->setVar('ERROR', array('text' => $error));
                            $this->page->noindex = true;
                            $breaked = TRUE;
                            $task = 'savePersonalData';
                        }
        
                        break;
        
                    // редирект на самую новую работу в каталоге
                    case 'last':
        
                        $sth = App::db()->query("SELECT
                                        g.`good_id`,
                                        u.`user_login`
                                    FROM
                                        `users` AS u,
                                        `goods` AS g
                                    WHERE 
                                            g.`ps_src`         > '0'
                                        AND g.`good_visible`   = 'true'
                                        AND g.`user_id`        = u.`user_id`
                                        AND (g.`good_status` = 'printed' OR g.`good_status` = 'pretendent')
                                    GROUP BY g.`good_id`
                                    ORDER BY  g.`good_voting_end` DESC 
                                    LIMIT 1");
        
                        $last = $sth->fetch();
        
                        // собираем фильтры
                        if (!empty($reqUrl[4]))
                            $reqUrl[3] = implode(';', array($reqUrl[3], $reqUrl[4]));
        
        
                        header("HTTP/1.1 301 Moved Permanently");
                        header('location: /' . $this->page->module . '/' . $last['user_login'] . '/' . $last['good_id'] . '/' . (!empty($reqUrl[2]) ? addslashes($reqUrl[2]) . '/' : '') . (!empty($reqUrl[3]) ? addslashes($reqUrl[3]) . '/' : ''));
                        exit();
        
                        break;
        
                    // специальный признак в урле, что работы нужно отдавать аджаксом
                    case 'ajax':
                        
                        $ajax = TRUE;
                        
                        if ($task == 'start')
                            $task = 'main';
                        
                        header("Status: 404 Not Found");
                        
                        break;
        
                    case 'style':
                        break;
        
                    case 'size':
                        $continue = true;
                        break;
        
                    case 'color':
        
                        $filters[$v] = ($v2 == 'white') ? 37 : $reqUrl[$k + 1];
                        $continue = $breaked = true;
        
                        break;
        
                    case 'authors':
        
                        $task    = 'authors';
                        $breaked = true;
        
                        break;
        
                    case 'all':
        
                        $onPage = 3000;
                        $this->page->noindex = true;
        
                        break;
        
                    case 'sex':
        
                        $filters[$v] = $reqUrl[$k + 1];
        
                        if (!$good_id && !$filters['category'])
                        {
                            if ($filters['sex'] == 'male')
                            {
                                $tag1 = 'alkogol_';
                            }
                            elseif ($filters['sex'] == 'female')
                            {
                                $tag1 = '8marta';
                            }
                            else
                            {
                                $this->page->go('/catalog/', 301);
                            }
        
                            //$baseOnPage  = $onPage  = 45;
                            $orderBy = 'popular';
                            //$breaked = TRUE;
        
                            if ($tag = getTagInfoBySlug(urldecode(addslashes($tag1))))
                            {
                                $at['tag_table'] = '`tags` AS t';
                                $at['tag_r_table'] = '`tags_relationships` AS gtr';
                                
                                $aq[] = "(t.`tag_id` = '" . $tag['tag_id'] . "' OR t.`synonym_id` = '" . $tag['tag_id'] . "')";
                                $aq[] = "gtr.`tag_id` = t.`tag_id`"; 
                                $aq[] = "gtr.`object_id` = g.`good_id`"; 
                                $aq[] = "gtr.`object_type` = '1'";
        
                                if (empty($tag['title'])) {
                                    $this->page->title[0] = $tag['title'];
                                } else {
                                    $this->page->title[0] = 'Футболки "' . $tag['name'] . '"';
                                }
        
                                $this->view->setVar('TAG', $tag);
        
        
                                $sth = App::db()->query("SELECT t.`tag_id`, t.`name`, t.`alt`, t.`slug`, t.`alt` AS title, t.`tag_ps_goods` as `count`
                                            FROM `tags` AS t
                                            WHERE t.`tag_ps_goods` > '2'
                                            ORDER BY t.`name` ASC");
        
                                $rs = $sth->fetchAll();
        
                                foreach ($rs as $k => $v)
                                {
                                    if ($v['tag_id'] == $tag['tag_id'])
                                    {
                                        if ($rs[$k - 1])
                                            $prev_tag = $rs[$k - 1];
                                        else
                                            $prev_tag = $rs[count($rs) - 1];
        
                                        if ($rs[$k + 1])
                                            $next_tag = $rs[$k + 1];
                                        else
                                            $next_tag = $rs[0];
                                    }
                                }
        
                                // след/пред тег
                                $this->view->setVar('next_tag', $next_tag);
                                $this->view->setVar('prev_tag', $prev_tag);
                            }
                            else
                            {
                                $this->page->go('/catalog/', 301);
                            }
                        }
        
                        break;
        
                    case 'category':
        
                        $filters[$v] = $reqUrl[$k + 1];
        
                        break;
        
                    case 'sale':
        
                        if ($reqUrl[1] == 'view' && !empty($reqUrl[2]))
                        {
                            $task     = 'good';
                            $good_id  = intval($reqUrl[2]);
                        }
                        else
                        {
                            $task = 'sale';
                        }
        
                        $sale = $breaked = true;
        
                        $this->view->setVar('sale', $sale);
                        
                        if (in_array($reqUrl[2], array(17318, 28766, 57591, 19891, 25144)))
                            $this->page->breadcrump[] = array(
                                'link'    => '/odnocvet/',
                                'caption' => 'mjforall');
                        else
                            $this->page->breadcrump[] = array(
                                'link'    => (($this->page->module == 'sale') ? '/' . $this->page->module . '/' : '/' . $this->page->module . "/$v/"),
                                'caption' => "Распродажа");
        
                        break;
        
        
                    case 'catalog':
                    case 'catalog.mobile':
                        
                        //$task = 'main';
                        
                        break;
        
                    case 'winners':
                        
                        $winners = $orderBy = 'win_date';
                        
                        if ($filters['category'])
                            $this->page->title[0] = 'Купить ' . styleCategory::$BASECATS[$filters['category']]['title_lower'] . ' победителей';
                        else
                            $this->page->title[0] = 'Купить футболки победителей';
        
                        if (!$filters['category'])
                            $this->page->breadcrump[] = array(
                                'link'    => '/' . $this->page->module . '/winners/',
                                'caption' => 'Победители');
        
                        if ($task == 'start') {
                            $task = 'main';
                        }
        
                        break;
        
                    case 'male':
                    case 'female':
                    case 'kids':
                        
                        $filters['sex'] = $v;
                        
                        break;
        
        
                    case 'tags':
                        $task = 'tags';
                        $breaked     = true;
                        break;
        
                    case 'nottag':
        
                        $breaked = TRUE;
        
                        if ($tag = getTagInfoBySlug(urldecode(addslashes($reqUrl[$k + 1]))))
                        {
                            $sth = App::db()->query("SELECT `object_id` FROM `tags_relationships` WHERE `tag_id` = '" . $tag['tag_id'] . "' AND `object_type` = '1'");
                            
                            foreach ($sth->fetchAll() as $g) {
                                $exclude[] = $g['object_id'];
                            }
                            
                            if (count($exclude) > 0) {
                                $aq[] = "g.`good_id` NOT IN ('" . implode("','", $exclude) . "')";
                            }
                        }
        
                        break;
        
                    // Выборка по тегу
                    case 'tag':
                    case 'tagall':
                    case '8march':
                    case '9may':
                    case '14february':
                    case '23february':
                    case 'kasperskiy':
                    case 'prikol':
                    case 'amnesty':
        
                        if (!$task || $task == 'start' || in_array($task, array('8march', '9may', '14february', '23february'))) {
                            $task = 'main';
                        }
        
                        switch ($v) {
                            case 'tag':
                                $continue = TRUE;
                                $tag = getTagInfoBySlug(urldecode(addslashes($reqUrl[$k + 1])));
                                break;
                            
                            case 'kasperskiy':
                                $tag = getTagInfoBySlug($v);
                                break;
                            
                            case 'prikol':
                                
                                /**
                                 * извращение, ручные редиректы для тега прикол
                                 */
                                if ($this->url != '/prikol/' && $this->url != '/prikol/new/' && $this->url != '/prikol/popular/' && $this->url != '/prikol/mayki-alkogolichki/' && $this->url != '/prikol/tolstovki/' && $this->url != '/prikol/tolstovki/female/') {
                                    if ($this->page->reqUrl[0] == 'prikol') {
                                        $this->page->go('/tag' . $this->page->url, 301);
                                    }
                                } 
                                 
                                $tag = getTagInfoBySlug($v);
                                break;
                                
                            case 'amnesty':
                                $tag = getTagInfoBySlug($v);
                                $specific_content_tpl = 'catalog/tags/amnesty.tpl';
                                break;
                            
                            case '8march':
                                $tag = getTagInfoBySlug('8march');
                                $collection_id = $collection = 17218;
                                $this->view->setVar('collection', '8march');
                                break;
                                
                            case '9may':
                                $tag = getTagInfoBySlug('9may');
                                $collection_id = $collection = 17540;
                                $this->view->setVar('collection', '9may');
                                break;
                                
                            case '14february':
                                $tag = getTagInfoBySlug('14february');
                                $collection_id = $collection = 17124;
                                $this->view->setVar('collection', '14february');
                                break;
                                
                            case '23february':
                                $tag = getTagInfoBySlug('voennue');
                                $collection_id = $collection = 17150;
                                $this->view->setVar('collection', '23february');
                                break;
                            
                        }
                        
                        if (!$tag) {
                            $this->page404();
                        }

                        if (!empty($tag['synonym_id']) && !$collection_id)
                        {
                            $sth = App::db()->query("SELECT `slug` FROM `tags` WHERE `tag_id` = '" . $tag['synonym_id'] . "' LIMIT 1");
                            $slug = $sth->fetch();
                            
                            if (!empty($slug['slug'])) {
                                $this->page->go(str_replace($tag, $slug['slug'], $this->page->url), 301);
                            }
                        }
        
                        if (empty($tag['synonym_id']) && $tag['tag_ps_goods'] == 0) {
                            $this->page404();
                        }
        
                        if ($tag['slug'] == 'parnie_futbolki')
                        {
                            $task = 'parnie_futbolki';
                        }
                        
                        if ($tag['slug'] == 'nadpis_')
                        {
                            // убираем работы с тегов "english"
                            $sth = App::db()->query("SELECT `object_id` FROM `tags_relationships` WHERE `tag_id` = '20101' AND `object_type` = '1'");
                            
                            foreach ($sth->fetchAll() as $g) {
                                $excluded_goods[] = $g['object_id'];
                            }
                            
                            $aq[] = "g.`good_id` NOT IN ('" . implode("', '", $excluded_goods) . "')";
                        }
                            
                        if (($tag['slug'] == 'english' || $tag['slug'] == 'nadpis_') && !$this->page->isAjax) 
                        {
                            $this->page->import(array(
                                '/css/notees.css',
                                '/js/p/notees.js',
                            ));
                            
                            if (!$filters['category']) {
                                //$specific_content_tpl = 'catalog/list.notees.tpl';
                                $list_tpl = 'list.goods.tpl';
                            }
                            
                            $this->view->setVar('notees', TRUE);
                            
                            try
                            {
                                /**
                                 * список победителей 
                                 */
                                $sth = App::db()->query("SELECT n.*, 
                                                    (n.`grades_plus_count` + n.`grades_minus_count`) AS grades_all_count, 
                                                    (n.`grades_plus_count` / n.`grades_minus_count`) AS avg, 
                                                    u.`user_id`, 
                                                    u.`user_login`, 
                                                    COUNT(ng.`good_id`) AS goods
                                                  FROM 
                                                    `users` AS u,
                                                    `notees` AS n
                                                        LEFT JOIN `notees_goods` AS ng ON ng.`note_id` = n.`id` AND ng.`approved` = '1'
                                                  WHERE 
                                                        n.`user_id` = u.`user_id`  
                                                    AND n.`place` != '0'
                                                  GROUP BY n.`id`
                                                  ORDER BY n.`id` DESC
                                                  LIMIT 30");
                                
                                $notees_winners = $sth->fetchAll();
                                
                                foreach ($notees_winners as &$nw) {
                                    $nw['text'] = stripslashes($nw['text']);
                                }
                                
                                $this->view->setVar('notees_winners', $notees_winners);
                                
                                /**
                                 * топ теги
                                 */
                                $sth = App::db()->query("SELECT `tag_id`, `slug`, `name`, IF (`tag_ps_goods` = 0, 1, `tag_ps_goods`) AS count FROM `tags` 
                                    WHERE `tag_id` IN ('129','574','18753','571','435','11563','6905','16','14366','4855','3084','16980','677','4973','2510','163','4208','939','4515','2651','2331','1824','504','620','4705','204','14279','441','3453','12832','2967','11463','11937','2344','5093','3596','14351','83','19701','6931','16204','410','3699','903','3537','16348') 
                                    ORDER BY `name`");
                                
                                $notees_top_tags = $sth->fetchAll();
                                
                                $this->view->setVar('notees_top_tags', $notees_top_tags);
                            }
                            catch (Exception $e)
                            {
                                printr($e);
                            }
                        }
    
                        // детские фотки
                        if ($tag['slug'] == 'detskie')
                        {
                            if (!$filters['category']) {
                                $list_tpl = 'tags/list.detskie.tpl';
                            }

                            $sth = App::db()->query("SELECT ga.`gallery_picture_id`, ga.`good_id`, ga.`comments`, p.`picture_path`, u.`user_login`
                                        FROM `gallery` AS ga, `pictures` AS p, `goods` g, `users` u
                                        WHERE ga.`gallery_picture_visible` = 'true' AND p.`picture_id` = ga.`gallery_small_picture` AND ga.`sex` = '-2' AND ga.`good_id` = g.`good_id` AND g.`user_id` = u.`user_id` 
                                        GROUP BY ga.`gallery_picture_id` 
                                        ORDER BY ga.`gallery_picture_date` DESC 
                                        LIMIT 16");
                                        
                            $rs = $sth->fetchAll();
                
                            foreach ($rs as $k => $p) {
                                if ($k < 8)
                                    $photos['top'][] = $p;
                                else
                                    $photos['bottom'][] = $p;
                            }
                            
                            $this->view->setVar('photos', $photos);
                        }
    
                        if (empty($this->page->canonical)) {
                            $this->page->canonical = '/tag/' . $tag['slug'] . '/';
                        }
    
                        /**
                         * Если смотрим корневой тег коллекции тегов или тег принадлежит какой-то коллекции
                         * вытягиваем всю коллекцию для "цветного облака тегов"
                         */
                        if ($page <= 1 && $tag['slug'] != 'nadpis_')
                        {
                            $sth = App::db()->prepare("SELECT tc.`collection_id`, tc.`tag_id` FROM `tags__collections` tc WHERE tc.`tag_id` = :tag OR tc.`collection_id` = :tag");
                                                  
                            $sth->execute(array(
                                'tag' => $tag['tag_id'],
                            ));
                            
                            foreach ($sth->fetchAll() as $r) 
                            {
                                $tmp[] = $r['collection_id'];
                                
                                if ($tag['tag_id'] == $r['collection_id'])
                                {
                                    $collection_id = $r['collection_id'];
                                    $this->view->setVar('collection', $r['slug']);
                                } else {
                                    
                                }
                            }

                            if (count($tmp) > 0)
                            {
                                $sth = App::db()->query("SELECT t.`tag_id`, t.`name` AS title, t.`slug` 
                                                    FROM `tags__collections` tc, `tags` t 
                                                    WHERE ((tc.`collection_id` IN ('" . implode("', '", $tmp) . "') AND tc.`tag_id` = t.`tag_id`) || t.`tag_id` IN ('" . implode("', '", $tmp) . "')) AND t.`tag_id` != '" . $tag['tag_id'] . "' AND t.`tag_ps_goods` > '0'
                                                    GROUP BY t.`tag_id`");
                                $sth->execute();
                            
                                $turl = $this->page->reqUrl;
                            
                                foreach ($sth->fetchAll() as $r) 
                                {
                                    $turl[1] = $r['slug'];
                                    $r['link'] = '/' . implode('/', $turl) . '/';
                                    
                                    $tags_collection[$r['tag_id']] = $r;
                                }
                            }

                            $this->view->setVar('collection_id', $r['collection_id']);
                            $this->view->setVar('tags_collection', $tags_collection);
                        }
                        
                        $at['tag_table'] = '`tags` AS t';
                        $at['tag_r_table'] = '`tags_relationships` AS gtr';
                        
                        // если просматривают коллекцию тегов то выводим все теги из этой коллекции
                        if ($tag['slug'] == 'parnie_futbolki' || in_array($filters['category'], array('boards')))
                            $aq[] = " t.`tag_id` = '" . $tag['tag_id'] . "'";
                        else
                            $aq[] = " (t.`tag_id` = '" . $tag['tag_id'] . "' " . ($tag['slug'] != 'nadpis_' ? "OR t.`synonym_id` = '" . $tag['tag_id'] . "'" : '') . (count($tags_collection) > 0 ? " OR t.`tag_id` IN ('" . implode("', '", array_keys($tags_collection)) . "')" : '') . ")";
                        
                        $aq[] = " gtr.`tag_id` = t.`tag_id` AND gtr.`object_id` = g.`good_id` AND gtr.`object_type` = '1'";
    
                        // убираем работы с конкурса одноцветных наклеек
                        $aq[] = "g.`competition_id` != '2475'";
    
                        if (!$filters['category'])
                        {
                            $this->page->breadcrump[0] = array(
                                'link'    => '/tag/',
                                'caption' => ($this->page->utitle) ? $this->page->utitle : $this->page->title[count($this->page->title) - 1]);
                            
                            if (!$this->page->utitle) {
                                $this->page->breadcrump[] = array(
                                    'link'    => '/tag/' . $tag['slug'] . '/',
                                    'caption' => $tag['name']);
                            }
                            
                            if (!$collection) {
                                $this->page->title[0] = $tag['name'] . ' - ' .  ($this->page->lang == 'en' ? 'Collections' : 'Коллекции');
                                $PD[0] = $tag['name'] . ' - ' . ($this->page->lang == 'en' ? 'Collections' : 'Коллекции');
                            } else {
                                if ($tag['alt'])
                                    $this->page->title[0] = $PD[0] = $tag['alt'];
                            }
                        }
                        else
                        {
                            if (!$collection) {
                                $this->page->title[0] = $tag['name'];
                            } else {
                                if ($tag['alt'])
                                    $this->page->title[0] = $tag['alt'];
                            }
                            
                             $this->page->breadcrump[] = array(
                                'link'    => '/tag/' . $tag['slug'] . '/',
                                'caption' => $tag['name']);
                             
                             if ($tag['name_syn1'] && count(array_intersect($this->page->reqUrl, ['new', 'grade', 'popular'])) == 0 && in_array($filters['category'], ['futbolki', 'sweatshirts', 'tolstovki', 'mayki-alkogolichki', 'patterns', 'patterns-sweatshirts', 'patterns-tolstovki']) && !$this->page->utitle)
                             {
                                 $PT = ['Купить'];
                                 $PT[] = $filters['sex'] == 'female' ? 'женские' : $filters['sex'] == 'kids' ? 'детские' : 'мужские';
                                 $PT[] = styleCategory::$BASECATS[$filters['category']]['title_lower'];
                                 $PT[] = $tag['name_syn1'];
                                 $PT[] = 'в Москве, цена';
                                 $PT[] = $filters['sex'] == 'female' ? 'женских' : $filters['sex'] == 'kids' ? 'детских' : 'мужских';
                                 $PT[] = styleCategory::$BASECATS[$filters['category']]['title_rp'];
                                 $PT[] = $tag['name_syn2'];
                                 $PT[] = 'с прикольными принтами - магазин дизайнерской одежды MaryJane';
                                 
                                 $PD = ['Магазин дизайнерской одежды MaryJane предлагает купить'];
                                 $PD[] = $filters['sex'] == 'female' ? 'женские' : $filters['sex'] == 'kids' ? 'детские' : 'мужские';
                                 $PD[] = styleCategory::$BASECATS[$filters['category']]['title_lower'];
                                 $PD[] = $tag['name_syn1'];
                                 $PD[] = 'в Москве с прикольными принтами по привлекательной цене.';
                                 
                                 $H1[] = $filters['sex'] == 'female' ? 'Женские' : $filters['sex'] == 'kids' ? 'Детские' : 'Мужские';
                                 $H1[] = styleCategory::$BASECATS[$filters['category']]['title_lower'];
                                 $H1[] = $tag['name_syn1'];
                                 
                                 $this->view->setVar('H1', implode(' ', $H1));
                                    
                                 $this->page->utitle = implode(' ', $PT);
                             }
                        }
    
                        $this->view->setVar('TAG', $tag);
                
                        if ($page == 1 || empty($page))
                        {
                            if (!$rs = App::memcache()->get('CATALOG_TAGS')) {
                                $sth = App::db()->query("SELECT t.`tag_id`, t.`name`, t.`alt`, t.`slug`, t.`tag_ps_goods` as `count`
                                    FROM 
                                        `tags_relationships` tr 
                                            inner join `tags` t on tr.`tag_id` = t.`tag_id`
                                            inner join `goods` g on tr.`object_id` = g.`good_id` AND tr.`object_type` =  '1'
                                    WHERE 
                                            t.`tag_ps_goods` >  '2'
                                        AND g.`good_status` IN ('printed',  'pretendent')
                                        AND g.`good_visible` =  'true'
                                    GROUP BY tr.`tag_id`
                                    ORDER BY t.`name` ASC");
            
                                $rs = $sth->fetchAll();
                                
                                App::memcache()->set('CATALOG_TAGS', $rs, false, 3600 * 48);
                            }
        
                            foreach ($rs as $k => $v)
                            {
                                if ($v['tag_id'] == $tag['tag_id'])
                                {
                                    if ($rs[$k - 1])
                                        $prev_tag = $rs[$k - 1];
                                    else
                                        $prev_tag = $rs[count($rs) - 1];
        
                                    if ($rs[$k + 1])
                                        $next_tag = $rs[$k + 1];
                                    else
                                        $next_tag = $rs[0];
                                }
                            }
        
                            if ($prev_tag && $next_tag)
                            {
                                $next_tag['title'] = (!empty($next_tag['alt']) && !in_array($filters['category'], array('phones', 'touchpads', 'laptops', 'ipodmp3', 'cases'))) ? $next_tag['alt'] : ((styleCategory::$BASECATS[$filters['category']]) ? styleCategory::$BASECATS[$filters['category']]['title'] : 'Футболки')  . ' "' . $next_tag['name'] . '"';
                                $prev_tag['title'] = (!empty($prev_tag['alt']) && !in_array($filters['category'], array('phones', 'touchpads', 'laptops', 'ipodmp3', 'cases'))) ? $prev_tag['alt'] : ((styleCategory::$BASECATS[$filters['category']]) ? styleCategory::$BASECATS[$filters['category']]['title'] : 'Футболки') . ' "' . $prev_tag['name'] . '"';
            
                                // след/пред тег
                                $this->view->setVar('next_tag', $next_tag);
                                $this->view->setVar('prev_tag', $prev_tag);
                            }
                            
                            if ($tag['slug'] == 'nadpis_' && !$filters['category']) 
                            {
                                if (!$gallery = App::memcache()->get('GALLERY_TAG_NADPIS_'))
                                {
                                    $sth = App::db()->prepare("SELECT g.`good_id`, g.`good_name`, u.`user_login`, p.`picture_path`
                                          FROM
                                            `goods` g,
                                            `tags_relationships` tr,
                                            `tags` t,
                                            `gallery` ga,
                                            `users` u,
                                            `pictures` p
                                          WHERE
                                                t.`slug` = :slug
                                            AND t.`tag_ps_goods` > '0'
                                            AND tr.`tag_id` = t.`tag_id`
                                            AND tr.`object_id` = g.`good_id`
                                            AND tr.`object_type` = '1'
                                            AND g.`good_status` IN ('printed', 'pretendent')
                                            AND g.`good_visible` = 'true'
                                            AND g.`good_id` = ga.`good_id`
                                            AND g.`user_id` = u.`user_id`
                                            AND ga.`gallery_picture_visible` = 'true'
                                            AND p.`picture_id` = ga.`gallery_small_picture`
                                          GROUP BY g.`good_id`
                                          ORDER BY g.`good_voting_end` DESC
                                          LIMIT 10");
                                          
                                    $sth->execute(array(
                                        'slug' => $tag['slug'],
                                    )); 
                                        
                                    $gallery = $sth->fetchAll(PDO::FETCH_ASSOC);
                                    
                                    App::memcache()->set('GALLERY_TAG_NADPIS_', $gallery, false, 2 * 86400);
                                }
                                
                                foreach ($gallery as $k => $g) {
                                    $gallery[$k]['good_name'] = stripslashes($g['good_name']);
                                }
                                
                                $this->view->setVar('gallery', $gallery);
                            }
                        }
    
                        if ($tag['slug'] == '14february' || $tag['slug'] == '23february') {
                            $orderBy = 'grade';
                        }
                        
                        /*
                        if ($tag['slug'] == 'novuygod') {
                            $orderBy = 'popular';
                        } else {
                            $orderBy = 'grade';
                        }
                        */
                        
                        break;
        
                    // все возможные варианты сортировок 
                    case 'new':
                    case 'popular':
                    case 'visits':
                    case 'popular_by_category':
                    case 'likes':
                    case 'avg_grade':
                    case 'avg_grade_designers':
                    case 'recently_buy': 
                    case 'recently_view':
                    case 'rating':
                    case 'grade':
                    case 'orderby':
                    case 'top2013':
                    case 'top2014':
                    case 'top2015':
                    case 'top2016':
                    case 'artdir': 
        
                        $orderBy = $v;
        
                        $this->view->setVar('orderBy', $orderBy);
        
                        switch ($v) {
                            case 'new':
                                $PD[] = 'новинки';
                                break;
                            case 'popular':
                                $PD[] = 'популярные';
                                break;
                        }
        
                    break;
        
                    case 'page':
                    
                        $page = intval($reqUrl[$k + 1]);
                        unset($reqUrl[$k + 1]);
                        $breaked = TRUE;
                        
                        break;
        
                    case 'nikon':
        
                        $task = 'main';
        
                        $q = "SELECT t.`tag_id`, t.`name`, t.`slug`, t.`tag_ps_goods` as `count`
                              FROM `tags` AS t, `tags_relationships` tr, `goods` g
                              WHERE
                                    t.`tag_id` = tr.`tag_id`
                                AND tr.`object_id` = g.`good_id`
                                AND tr.`object_type` = '1'
                                AND g.`competition_id` = '2469'
                                AND g.`good_visible` = 'true'
                                AND g.`good_status` != 'deny'
                              GROUP BY t.`tag_id`
                              ORDER BY count DESC
                              LIMIT 20";
        
                        $sth = App::db()->query($q);
                        $tags = $sth->fetchAll();
        
                        $this->view->setVar('tags', $tags);
        
                        break;
        
                    // Запрошена страничка с работами определённого дизайнера
                    default:
                
                        if (!$breaked)
                        {
                            if (!in_array($reqUrl[$k - 1], array('color', 'size', 'category')) && !in_array($task, array('shopwindow', 'authors', 'gadget', 'sex', 'male', 'female', 'savePersonalData', 'fittingroom')) && !in_array($reqUrl[1], array('tag', 'page')) && !$continue)
                            {
                                if (!array_search(strtolower($v), $CATALOG_COLORS['slug']) && !array_search(strtolower($v), $CATALOG_SIZES)) 
                                {
                                    $orderBy = 'new';
        
                                    // временно убрали поиск по номеру автора   
                                    //$sth = App::db()->query("SELECT u.`user_id` FROM `users` AS u WHERE (u.`user_login` = '" . addslashes(urldecode($v)) . "' OR CAST(u.`user_id` as CHAR(150)) = '" . addslashes(urldecode($v)) . "') AND u.`user_status` = 'active' GROUP BY u.`user_id` LIMIT 1");
                                    $sth = App::db()->query("SELECT u.`user_id` FROM `users` AS u WHERE u.`user_login` = '" . addslashes(urldecode($v)) . "' AND u.`user_status` = 'active' GROUP BY u.`user_id` LIMIT 1");
            
                                    // Такой дизайнер найден
                                    if ($sth->rowCount() == 1)
                                    {
                                        $row = $sth->fetch();
            
                                        $U = new user($row['user_id']);
            
                                        $sth = App::db()->prepare("SELECT COUNT(DISTINCT(s.`user_id`)) AS selected FROM `selected` s WHERE s.`selected_id` = :user");
                                        $sth->execute(array('user' => $U->id));
                                        $foo = $sth->fetch();
                                        
                                        $U->selected             = $foo['selected'];
                                        $U->user_name            = ($U->user_show_name == 'true') ? $U->user_name : '';
                                        $U->avatar               = user::userAvatar($U->id, 100);
                                        $U->user_designer_level  = user::designerLevel2Picture($U->user_designer_level);
                                        $U->user_city            = cityId2name($U->user_city);
                                        $U->pretendents          = $U->getUserGoodsCount(array('printed', 'pretendent'));
            
                                        if ($v == $U->id || urldecode(strtolower($v)) != strtolower($U->user_login))
                                        {
                                            header("HTTP/1.1 301 Moved Permanently");
                                            header('location: /' . $this->page->module . '/' . $U->user_login . '/');
                                            exit();
                                        }
                                           
                                        // мои работы лайкнули
                                        $foo = App::db()->query("SELECT COUNT(DISTINCT(g.`good_id`)) AS c FROM `good_likes` gl, `goods` g WHERE g.`good_id` = gl.`good_id` AND g.`user_id` = '" . $U->id . "' AND gl.`negative` = '0' AND g.`good_status` != 'voting'")->fetch();
                                        $U->me_liked  = (int) $foo['c'];
            
                                        // я лайкнул
                                        $foo = App::db()->query("SELECT COUNT(DISTINCT(g.`good_id`)) AS c FROM `good_likes` gl, `goods` g WHERE gl.`user_id` = '" . $U->id . "' AND gl.`negative` = '0' AND gl.`good_id` = g.`good_id` AND g.`good_visible` = 'true' AND g.`user_id` != gl.`user_id`")->fetch();
                                        $U->liked  = (int) $foo['c'];
            
                                        
                                        if (is_numeric($reqUrl[2]))
                                        {
                                            $good_id = intval($reqUrl[2]);
                                            $task    = 'good';
            
                                            // если мы смотрим на страницу дизайна, то дальнейший анализ урла останавливаем
                                            $breaked = true;
                                        }
                                        else
                                        {
                                            if ($U->user_activation != 'done') {
                                                $this->page->go('/catalog/', 301);
                                            }
                                            
                                            if ($reqUrl[2] == 'tag') {
                                                $this->page->noindex = TRUE;
                                            }
                                            
                                            if (array_search('ajax', $reqUrl))
                                                $task = 'main';
                                            else
                                                $task = 'shopwindow';
                                            
                                            $this->page->breadcrump[] = array('link'    => '/people/designers/',
                                                          'caption' => 'Авторы');
                                
                                            $this->page->breadcrump[] = array('link'    => '/catalog/' . $U->user_login . '/',
                                                          'caption' => $U->user_login);
                                        }
            
                                        if (!empty($U->meta->personal_header) && empty($good_id)) {
                                            $U->meta->personal_header = pictureId2path($U->meta->personal_header);
                                        } else {
                                            $U->meta->personal_header = '';
                                        }
            
                                        if (mb_strlen($U->meta->meta_description, 'utf-8') <= 500) {
                                            $U->meta->meta_description_1 = substr($U->meta->meta_description, 0, 140);
                                            $U->meta->meta_description_2 = substr($U->meta->meta_description, 140);
                                        } else {
                                            $this->page->seo = $U->meta->meta_description;
                                            $U->meta->meta_description = '';
                                        } 
            
                                        $this->view->setVar('userId', $U->id);
                                        $this->view->setVar('user_id', $U->id);
                                        $this->view->setVar('user', array_merge((array) $U->info, (array) $U->meta));
                                        $this->view->setVar('designer', $U);
                                        $this->view->setVar('userInfo', array('user_login' => $U->user_login));
            
                                        /*
                                        if ($this->user->id != $U->id && !SpiderDetect($_SERVER['HTTP_USER_AGENT']))
                                        {
                                            // считаем посещение
                                            $sth = App::db()->query("SELECT `id`
                                                        FROM `good__visits`
                                                        WHERE
                                                                `ip`      = INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "')
                                                            AND `user_id` = '" . $U->id . "'
                                                            AND `good_id` = '" . intval($good_id) . "'
                                                            AND `date` > NOW() - INTERVAL 30 MINUTE
                                                            AND `page` = 'catalog'");
        
                                            if ($sth->rowCount() == 0) {
                                                $sth = App::db()->query("INSERT
                                                            INTO `good__visits`
                                                            SET
                                                                `ip`      = INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "'),
                                                                `user_id` = '" . $U->id . "',
                                                                `good_id` = '" . intval($good_id) . "',
                                                                `visitor_id` = '" . $this->user->id . "',
                                                                `page`    = 'catalog'");
                                             }
                                        }
                                        */
                                    }
                                    else
                                    {
                                        $this->page->go('/catalog/', 301);
                                    }
                                }
                            }
                            // пропустить элемент урла без анализа (например, название носителя)
                            elseif ($continue)
                            {
                                unset($continue); continue;
                            }
                            else {
                                $this->page404();
                            }
                        }
                        
                        break;
                }
            }
            
            $this->view->setVar('task', $task);
            
            //printr($filters, 1);
            //printr($this->page);
            //printr($task, 1);
            
            switch ($task)
            {
                case 'setdefaultstyle':
                    
                    $this->page->index_tpl = 'index.popup.tpl';
                    $this->page->tpl = 'catalog/setdefaultstyle.tpl';
                    
                    try
                    {
                        if (!empty($this->page->reqUrl[2]))
                        {
                            $g = new good($this->page->reqUrl[2]);
                            
                            if ($g->user_id == $this->user->id || $this->user->meta->mjteam == "super-admin" || $this->user->meta->mjteam == "developer")
                            {
                                if ($_POST['style_id'])
                                {
                                    $g->good_default_style = (int) $_POST['style_id'];
                                    $g->save(); 
                                    
                                    // если превью нет - генерим
                                    if (!$g->pics['catalog_preview'][$_POST['style_id']])
                                    {
                                        $w = 382;
                                        $h = 391;
                                    
                                        $r = $g->preview($_POST['style_id'], $w, $h, '', '', 1);
            
                                        $g->addPic('catalog_preview_' . $_POST['style_id'], $r['id'], $w, $h);
                
                                        exit('no preview');
                                    }
                                    
                                    exit();
                                }
                                
                                $Thumb = new S3Thumb(array('ic1.maryjane.ru', 'ic2.maryjane.ru', 'ic3.maryjane.ru', 'ic4.maryjane.ru'), S3AccessKey, S3SecretKey, S3CryptKey);
                                
                                $styles = array();
                                
                                $sth = App::db()->query("SELECT 
                                    s.`style_id`, s.`style_name`, s.`style_slug`, s.`style_description`, s.`style_category`, s.`style_sex`, s.`style_color`, s.`style_order`, sc.`id` AS category_id, sc.`name` AS category_name, sc.`cat_slug`, sc.`cat_parent`,
                                    p1.`picture_path` AS front,
                                    p2.`picture_path` AS back
                                  FROM 
                                    `styles` s
                                        LEFT JOIN `pictures` p1 ON s.`style_front_picture` = p1.`picture_id`
                                        LEFT JOIN `pictures` p2 ON s.`style_back_picture` = p2.`picture_id`, 
                                    `styles_category` sc, 
                                    `good_stock` gs 
                                  WHERE
                                         s.`style_category` = sc.`id`
                                     AND gs.`good_stock_quantity` +- gs.`good_stock_inprogress_quantity` > '0' 
                                     AND gs.`good_stock_visible` > '0'
                                     AND s.`style_visible`  = '1'
                                     AND s.`style_id` NOT IN ('291')
                                     AND s.`style_category` NOT IN ('71')
                                     AND s.`style_id` = gs.`style_id`
                                     AND gs.`good_stock_visible` = '1'
                                     AND gs.`good_id` = '0'
                                     AND (s.`style_front_picture` > '0' OR s.`style_back_picture` > '0')
                                  GROUP BY
                                    s.`style_id`
                                  ORDER BY 
                                    sc.`cat_parent`, sc.`id`, s.`style_order`, s.`style_id` 
                                  DESC");
                                
                                $pp = array();
                                
                                foreach ($sth->fetchAll() AS $s)
                                {
                                    if ($s['cat_parent'] > 1) {
                                        $s['category'] = styleCategory::$BASECATSid[$s['cat_parent']];
                                    } else {
                                        $s['category'] = $s['cat_slug'];
                                    }
                                    
                                    $s['src'] = styleCategory::$BASECATS[$s['category']]['src_name'];
                                    
                                    // полностью исключаемые  носители
                                    if (
                                        //!$g->pics[styleCategory::$BASECATS[$s['category']]['src_name']] || 
                                         //$g->pics[styleCategory::$BASECATS[$s['category']]['src_name']]['id'] < 0 || 
                                         $s['style_category'] == 87 ||
                                         $s['style_category'] == 71 ||
                                         $s['style_category'] == 65 ||
                                         $s['style_category'] == 83 ||
                                         $s['style_category'] == 85 ||
                                         $s['cat_parent'] == 65 ||
                                         $s['cat_parent'] == 76 ||
                                         ($s['cat_parent'] == 1 && (!empty($g->good_sex) || !empty($g->good_sex_alt)) && !in_array($s['style_sex'], array($g->good_sex, $g->good_sex_alt))) ||
                                         ($s['category'] == 'touchpads' && $s['style_id'] != styleCategory::$BASECATS[$s['category']]['def_style']) ||
                                         ($s['category'] == 'laptops' && $s['style_id'] != styleCategory::$BASECATS[$s['category']]['def_style']) ||
                                         //($s['category'] == 'ipodmp3' && $s['style_id'] != styleCategory::$BASECATS[$s['category']]['def_style']) ||
                                         $s['category'] == 'ipodmp3' ||
                                         $s['category'] == 'bag' ||
                                         $s['category'] == 'textile' ||
                                         $s['category'] == 'longsleeve_tshirt' ||
                                         ($s['category'] == 'phones' && !in_array($s['style_id'], array(styleCategory::$BASECATS[$s['category']]['def_style'], 630))) ||
                                         ($s['category'] == 'cases' && !in_array($s['style_id'], array(styleCategory::$BASECATS[$s['category']]['def_style'], 628, 666))) 
                                    ) 
                                    {
                                        continue;
                                    }
                                    
                                    
                                    if ($s['category'] == 'poster') {
                                        if (!$g->pics['catalog_preview_' . $s['style_id']] || $g->pics['catalog_preview_' . $s['style_id']]['id'] < 0) {
                                            continue;
                                        } else {
                                            if ($pp[$s['style_category']])
                                                continue;
                                            else
                                                $pp[$s['style_category']] = $s['style_id'];
                                        }
                                    }
                                    
                                    if ($s['category'] == 'cases') {
                                        $s['category'] = 'phones';
                                    }
                                    
                                    $s['preview'] = $Thumb->url(styleCategory::$BASECATS[$s['category']]['def_side'] == 'front' ? $s['front'] : $s['back'], 100);
                                    
                                    // отключаемые носители
                                    if ((!$g->pics[styleCategory::$BASECATS[$s['category']]['src_name']] || $g->pics[styleCategory::$BASECATS[$s['category']]['src_name']]['id'] < 0) && $s['style_id'] != styleCategory::$BASECATS[$s['category']]['def_style']) {
                                        $arr = &$a2;
                                        $s['disabled'] = true;
                                    } else {
                                        $arr = &$a1;
                                    }
                                    
                                    if (!$arr[$s['category'] . '-' . $s['style_sex']]) {
                                        
                                        $title = styleCategory::$BASECATS[$s['category']]['title'];
                                        
                                        if (styleCategory::$BASECATS[$s['category']]['sexes']) {
                                            if ($s['style_sex'] == 'male')
                                                $title .= ' мужские';
                                            elseif ($s['style_sex'] == 'female')
                                                $title .= ' женские';
                                            elseif ($s['style_sex'] == 'kids')
                                                $title .= ' детские'; 
                                        }
                                        
                                        $arr[$s['category'] . '-' . $s['style_sex']] = array(
                                            'name' => $s['category'],
                                            'title' => $title,
                                        );
                                    }
                                    
                                    $arr[$s['category'] . '-' . $s['style_sex']]['styles'][] = $s;
                                }
                
                                $styles = ((array) $a1 + (array) $a2);
                
                                $this->view->setVar('good', $g);
                                $this->view->setVar('styles', $styles);
                                
                            }
                            else 
                            {
                                exit('no access');
                            }
                        }
                    }
                    catch (Exception $e)
                    {
                        printr($e);
                    }
                
                    break;
                    
                /**
                 * Витрина автора
                 */
                case 'shopwindow':
                    
                    $this->page->tpl = 'catalog/list.shopwindow.tpl';
                    
                    $page = $page ? $page : 1;
                    $onPage = 100;
                    
                    if ($this->user->id != $U->id)
                    {
                        // Selected (add / remove)
                        $sth = App::db()->query("SELECT `selected_id` FROM `selected` WHERE `user_id` = '" . $this->user->id . "' AND `selected_id` = '" . $U->id . "' LIMIT 1");
                        $this->view->setVar('selected', $sth->rowCount() == 0 ? false : true);
                        
                        // код для подписки / отписки на уведомления
                        $this->view->setVar('subscribe_code', md5($this->user->id . $this->user->user_email . $this->user->user_register_date));
                    }
                    
                    if ($this->user->authorized)
                    {
                        $rs = App::db()->query("SELECT `good_id` FROM `good_likes` WHERE `user_id` = '" . $this->user->id . "'")->fetchAll();
                        
                        foreach ($rs as $r) {
                            $likes[$r['good_id']] = $r['good_id'];
                        }
                    }
                    
                    switch ($orderBy)
                    {
                        default:
                        case 'popular':
                            $order = "(g.`good_sold_allskins` + g.`good_sold_printshop`) DESC";
                            $this->view->setVar('orderby', 'popular');
                            break;
                        case 'new':
                            $order = " g.`good_voting_end` DESC, g.`good_likes` DESC";
                            $this->view->setVar('orderby', 'new');
                            break;
                        case 'grade':
                            $order = "g.`good_likes` DESC";
                            $this->view->setVar('orderby', 'grade');
                            $this->page->title[] = 'популярные';
                            break;
                         case 'win_date':
                            $order = " g.`good_voting_end` DESC";
                            break;
                    }
            
                    
                    try 
                    {
                        // если смотрим партнёрскую витрину (избранное)
                        if (array_search('selected', $this->page->reqUrl) && $U) 
                        {
                            // выбираем все работы которые лайкнул автор
                            $sth = App::db()->prepare("SELECT SQL_CALC_FOUND_ROWS g.* FROM `good_likes` gl, `goods` g WHERE gl.`user_id` = :user AND g.`good_id` = gl.`good_id` GROUP BY gl.`good_id` ORDER BY gl.`id` DESC LIMIT " . (($page - 1) * $onPage) . ", " . $onPage);
                            $sth->execute(array('user' => $U->id));
                            
                            $foo = App::db()->query("SELECT FOUND_ROWS() AS s")->fetch();
                            $total = $foo['s'];
                        
                            foreach ($sth->fetchAll() AS $g) {
                               $user_goods[$g['good_id']] = $g; 
                            }
                            
                            $aq['goods'] = "g.`good_id` IN ('" . implode("', '", array_keys($user_goods)) . "')";
                        } 
                        // если смотрим витрину автора
                        else 
                        {
                            // выбираем все работы автора
                            $user_goods = good::findAll(array('user' => $U->id, 'visible' => 1, 'domain' => 'mj'));
                            
                            $total = count($user_goods);
                            
                            $aq[] = "g.`user_id` = '" . $U->id . "'";
                        }
                        
                        if (count($user_goods) == 0) 
                        {
                            $this->page->noindex = TRUE;
                        }
                    
                    
                        //$raw_styles = style::findAll(array('onstock' => 1, 'exclude_style' => array(330, 472, 473, 476, 222), 'excludecat' => array(3)));
                        $raw_styles = style::findAll(array('onstock' => 1, 'exclude_style' => array(330, 472, 473, 476, 222), 'cat' => 'wear'));
                    
                        $styles = array();
                        
                        foreach ($raw_styles as $s) 
                        {
                            $ss = array(
                                'style_id' => $s['style_id'], 
                                'category' => $s['category'], 
                                'sex'      => $s['style_sex'],
                                'price'    => $s['tprice'],
                            );
                            
                            $styles[$s['style_id']] = $ss;
                            $gender_styles[$s['style_sex']][$s['style_id']] = $ss;
                        }
                        
                        //printr($styles);
                        //printr($gender_styles);
                        
                        $step1styles = $styles; // копия массива промо-носителей для первого этапа вывода работ
                        
                        // получаем список всех картинок для каталога работ
                        $sth = App::db()->prepare("SELECT 
                                g.`good_id`,
                                g.`good_name`,
                                g.`good_sex`,
                                g.`good_sex_alt`,
                                g.`good_status`,
                                g.`good_visible`,
                                g.`competition_id`,
                                g.`good_default_style`,
                                gp.`pic_name`,
                                gp.`pic_w`,
                                gp.`pic_h`,
                                p.`picture_path`,
                                u.`user_id`,
                                u.`user_login`
                            FROM 
                                `good_pictures` gp, 
                                `goods` g, 
                                `users` u,
                                `pictures` p
                                " . ($at ? ', ' .implode(', ', $at) : '') . "
                            WHERE 
                                    gp.`pic_name` IN ('catalog_preview_" . implode("', 'catalog_preview_", array_keys($styles)) . "', 'good_preview', 'stickerset_preview', 'ps_740x', 'poster_big')
                                AND (g.`good_domain` = 'mj' || g.`good_domain` = 'all')
                                AND gp.`good_id` = g.`good_id`
                                AND gp.`pic_id` = p.`picture_id`
                                AND g.`competition_id` != '2475'
                                AND u.`user_id` = g.`user_id`
                                AND (g.`good_visible` = 'true' " . ($this->user->id == $U->id || $this->user->meta->mjteam == 'super-admin' ? "OR g.`good_visible` = 'modify' OR g.`good_visible` = 'false'" : '') . ")
                                " . ($aq ? ' AND ' . implode(' AND ', $aq) : '') . "
                            ORDER BY " . $order);
                        
                        $sth->execute();
                        
                        $pics = $sth->fetchAll();
                        
                        //printr($pics);
                        $goods = array();
                        
                        foreach ($pics as $pic) 
                        {
                            $sid = 0;
                            
                            if (!$goods[$pic['good_id']]) {
                                $goods[$pic['good_id']] = array(
                                    'good_id' => $pic['good_id'], 
                                    'good_name' => $pic['good_name'],
                                    'user_id' => $pic['user_id'], 
                                    'user_login' => $pic['user_login'],
                                );
                            }
                            
                            if ($pic['pic_name'] == 'stickerset_preview') {
                                $sid = 537;
                            } elseif ($pic['pic_name'] == 'ps_740x' || $pic['pic_name'] == 'poster_big') {
                                if (!$goods[$pic['good_id']]['zoom']) {
                                    $goods[$pic['good_id']]['zoom'] = $pic['picture_path'];
                                }
                            } elseif ($pic['pic_name'] == 'good_preview') {
                                $goods[$pic['good_id']]['preview'] = $pic['picture_path'];
                                $goods[$pic['good_id']]['preview_w'] = $pic['pic_w'];
                                $goods[$pic['good_id']]['preview_h'] = $pic['pic_h'];
                            } else {
                                $sid = str_replace('catalog_preview_', '', $pic['pic_name']);
                            }
                            
                            if ($sid) {
                                $goods[$pic['good_id']]['styles'][$sid] = array(
                                    'style_id' => $sid, 
                                    'picture_path' => $pic['picture_path'],
                                    'pic_w' => $pic['pic_w'],
                                    'pic_h' => $pic['pic_h'],
                                );
                            }
                                
                            $goods[$pic['good_id']]['good_status'] = $pic['good_status'];
                            $goods[$pic['good_id']]['good_visible'] = $pic['good_visible'];
                            $goods[$pic['good_id']]['good_sex'] = $pic['good_sex'];
                            $goods[$pic['good_id']]['good_sex_alt'] = $pic['good_sex_alt'];
                            $goods[$pic['good_id']]['competition_id'] = $pic['competition_id'];
                            $goods[$pic['good_id']]['good_default_style'] = $pic['good_default_style'];
                        }
                        
                        //printr($goods);
                        //printr($user_goods);
                        
                        // по умолчанию не идексируем витрину
                        $this->page->noindex = true;
                        
                        foreach ($goods as $good_id => $g) 
                        {
                            if (!$g['preview']) {
                                unset($goods[$good_id]);
                                continue;
                            }
                            
                            if ($g['good_default_style'] && $goods[$good_id]['styles'][$g['good_default_style']])
                            {
                                $style = array('style_id' => $g['good_default_style']);
                            }
                            else 
                            {
                                if (($g['good_sex'] == 'male' && $g['good_sex_alt'] == 'female') || ($g['good_sex'] == 'female' && $g['good_sex_alt'] == 'male')) 
                                {
                                    $combine = array_intersect_key($gender_styles['male'] + $gender_styles['female'], $goods[$good_id]['styles']);
                                    shuffle($combine);
                                    $style = reset($combine);
                                }
                                elseif (($g['good_sex'] == 'male' || $g['good_sex'] == 'female') && $g['good_sex_alt'] == '') 
                                {
                                    $combine = array_intersect_key($gender_styles[$g['good_sex']], $goods[$good_id]['styles']);
                                    shuffle($combine);
                                    $style = reset($combine);
                                }
                                elseif (($g['good_sex_alt'] == 'male' || $g['good_sex_alt'] == 'female') && $g['good_sex'] == '') 
                                {
                                    $combine = array_intersect_key($gender_styles[$g['good_sexa_alt']], $goods[$good_id]['styles']);
                                    shuffle($combine);
                                    $style = reset($combine);
                                }
                                elseif ($g['good_sex'] == 'kids' || $g['good_sex_alt'] == 'kids')
                                {
                                    $combine = array_intersect_key($gender_styles['kids'], $goods[$good_id]['styles']);
                                    shuffle($combine);
                                    $style = reset($combine);
                                }
                                else
                                {
                                    if (count($step1styles) > 0)
                                    {
                                        $foo = array_intersect_key($step1styles, $goods[$good_id]['styles']);
                                        $style = reset($foo);
                                        unset($step1styles[$style['style_id']]);
                                    }
                                }
                            }
                             
            
                            if (!$style)
                            {
                                if ($goods[$good_id]['styles']) {
                                    shuffle($goods[$good_id]['styles']);
                                    $goods[$good_id] = array_merge($goods[$good_id], $goods[$good_id]['styles'][0]);
                                } else {
                                    // у работы нет вообще никаких превью на носителе (работа была отклонена например)
                                    $goods[$good_id]['picture_path'] = $goods[$good_id]['preview'];
                                }
                            }
                            else 
                            {
                                $goods[$good_id] = array_merge($goods[$good_id], $goods[$good_id]['styles'][$style['style_id']]);
                            }
            
                            // для монохромных наклеек, берём основное превью
                            if ($goods[$good_id]['style_id'] == 629)
                                $goods[$good_id]['picture_path'] = $goods[$good_id]['preview'];
            
                            
                            $goods[$good_id]['style_name'] = $raw_styles[$goods[$good_id]['style_id']]['style_name'];
                            $goods[$good_id]['style_color'] = $raw_styles[$goods[$good_id]['style_id']]['color_hex'];
                            $goods[$good_id]['good_id'] = $good_id;
                            $goods[$good_id]['good_status'] = $g['good_status'];
                            $goods[$good_id]['likes'] = $user_goods[$good_id]['good_likes'];
                            $goods[$good_id]['visits'] = $user_goods[$good_id]['visits'] + $user_goods[$good_id]['visits_catalog'];
                            $goods[$good_id]['competition_id'] = $g['competition_id'];
                            $goods[$good_id]['good_visible'] = $g['good_visible'];
                            $goods[$good_id]['style_sex'] = $raw_styles[$goods[$good_id]['style_id']]['style_sex'];
                            $goods[$good_id]['price'] = $raw_styles[$goods[$good_id]['style_id']]['tprice'];
                            $goods[$good_id]['category'] = $styles[$goods[$good_id]['style_id']]['category'];
                            $goods[$good_id]['link'] = '/catalog/' . $goods[$good_id]['user_login'] . '/' . $good_id . '/' . ($raw_styles[$goods[$good_id]['style_id']] ? $raw_styles[$goods[$good_id]['style_id']]['style_slug'] . '/' : '');
                            $goods[$good_id]['good_sex'] = $g['good_sex'];
                            $goods[$good_id]['good_sex_alt'] = $g['good_sex_alt'];
                            
                            if ($styles[$goods[$good_id]['style_id']]['category']) {
                                if (styleCategory::$BASECATS[$styles[$goods[$good_id]['style_id']]['category']]['sexes']) {
                                     $goods[$good_id]['link'] .= 's/';
                                }
                            }
                            
                            if ($likes[$good_id])
                              $goods[$good_id]['liked'] = TRUE;
                            
                            unset($goods[$good_id]['styles']);
                            
                            if (!in_array($g['good_status'], ['deny', 'new', 'customize']) && $g['good_visible'] == true) {
                                $this->page->noindex = false;
                            }
                        }
                        
                        //printr($goods);
                        
                        // для конкурса одноцветных наклеек оставляем только самую первую работу
                        $comp_2475_limit = 0;
                        
                        if ($this->page->isAjax && ($filters['good_status'] || $page > 1)) {
                            $collumnsname = $_SESSION['collumnsname'];
                        } else {
                            $_SESSION['collumnsname'] = $collumnsname = implode('', $this->page->reqUrl) . 'collumns';
                        }
                        
                        
                        if (!$this->page->isAjax || (!$filters['good_status'] && $page == 1)) { 
                            unset($_SESSION[$collumnsname]);
                        }
                        
                        $columns = 3;
                        
                        // поднимаем "недооформленные" работы и работы на ХС вверх
                        foreach ($goods AS $k => $g)
                        {
                            if ($g['good_status'] == 'new' || $g['good_visible'] == 'modify' || ($g['good_status'] == 'new' && $g['good_visible'] == 'true'))
                            {
                                $up[] = $g;
                                unset($goods[$k]);
                            }
                            
                            if ($g['competition_id'] == 2475) {
                                if ($comp_2475_limit > 0) {
                                    unset($goods[$k]);
                                } else {
                                    $comp_2475_limit++;
                                    $goods[$k]['link'] = '/catalog/' . $U->user_login . '/1color/';
                                }
                            }
                            
                            /*
                            $i = $k % $columns;
                            $goods[$k]['i'] = $i;
                            $goods[$k]['h'] = ($g['pic_h'] + $y_paddings) + $y_offset;
                            $goods[$k]['x'] = ($i * $g['pic_w']) + ($i * $x_offset) + ($i * $x_paddings);
                            $goods[$k]['y'] = (int) $_SESSION[$collumnsname][$i];
                
                            $_SESSION[$collumnsname][$i] += $goods[$k]['h'] + 24;
                            */
                        }
                        
                        if (is_array($up)) {
                            $goods = array_merge($up, (array) $goods);
                        }
                        
                        //printr($raw_styles);
                        
                        //printr($styles);
                        
                        $this->view->setVar('designer', $U);
                        $this->view->setVar('shopwindow', $goods);
                        
                        if ($total > $onPage)
                            $rest = intval($total) - ($page * $onPage);
                        else
                            $rest = 0;
                        
                        $link = $this->page->reqUrl;
            
                        foreach ($link AS $k => $l)
                        {
                            if ($l == 'page') {
                                unset($link[$k]);
                                unset($link[$k + 1]);
                            }
                            
                            if ($tag['slug'] == $link[1] && $link[0] == 'tag' && $collection) {
                                unset($link[0]);
                            }
                
                            if ($l == 'ajax') {
                                unset($link[$k]);
                            }
                
                            if ($l == 'search') {
                                $link[$k + 1] = urldecode($link[$k + 1]);
                            }
                        }
                        
                        $this->view->setVar('link', '/' . implode('/', $link));
                        $this->view->setVar('rest', $rest);
                        $this->view->setVar('page', $page);
                        $this->view->setVar('onPage', $onPage);
                        //$this->view->setVar('ULheight', max($_SESSION[$collumnsname]));
                        
                        if ($this->page->isAjax || $ajax)
                        {
                            $this->view->generate('catalog/list.goods.user_shopwindow.tpl');
                            exit();
                        }
                    }
                    catch (Exception $e) 
                    {
                        printr($e->getMessage());
                    }
                    
                    
                    // автор смотри на свою витрину
                    if ($U->id == $this->user->id)
                    {
                        try 
                        {
                            $promo = $this->user->getPromoUrl($this->page->url);
                            $this->view->setVar('promoUrl',  $promo['link']);
                        }
                        catch (Exception $e) 
                        {
                            printr($e->getMessage());
                        }
                    }
            
                    $this->page->canonical = '/catalog/' . $U->user_login . '/';
            
            
                    if (!empty($U->meta->personal_title))
                        $this->page->title[0] = $U->meta->personal_title;
                    else
                        $this->page->title[0] = 'Магазин футболок ' . $U->user_login;
            
                    $PD[] = (!empty($U->meta->personal_title)) ? $U->meta->personal_title . ', ' . $U->user_login : 'Магазин футболок ' . $U->user_login;
                    
                    if ($U->user_show_name == 'true') {
                        $PD[] = $U->user_name;
                    }
                    
                    if (!empty($U->user_city)) {
                        $PD[] = $U->user_city;
                    }
                    
                    $PD[] = 'авторский магазин футболок, виниловые наклейки, дизайнерские толстовки, постеры и прочее';
            
                    // описание для витрины автора - теги к его работам
                    if (!array_search('selected', $this->page->reqUrl) && $U) 
                    {
                        $sth = App::db()->prepare("SELECT t.`name`, t.`slug`, COUNT(g.`good_id`) AS goods
                                    FROM `tags_relationships` tr, `tags` t, `goods` g, `good_pictures` gp
                                    WHERE 
                                            g.`user_id`        = :user
                                        AND g.`good_visible`   = 'true'
                                        AND g.`good_status`   != 'customize'
                                        AND g.`good_status`   != 'deny'
                                        AND g.`good_id`        = tr.`object_id`
                                        AND tr.`tag_id`        = t.`tag_id`
                                        AND t.`tag_id` != '13775'
                                        AND tr.`object_type`   = '1'
                                        AND gp.`good_id`       = g.`good_id`
                                        AND gp.`pic_id`        > '0'
                                        AND gp.`pic_name`      IN ('ps_src', 'phones', 'laptops', 'touchpads', 'poster', 'auto')
                                    GROUP BY t.`tag_id`
                                    ORDER BY goods DESC");
                
                        $sth->execute(array('user' => $U->id));
                
                        $user_tags = $sth->fetchAll();
                
                        if (count($user_tags) > 0)
                        {
                            $this->page->keywords = '';
                            
                            foreach ($user_tags as $tag) {
                                $this->page->keywords .= ', ' . $tag['name'];
                            }
                            
                            $this->page->keywords = trim($this->page->keywords, ', ');
                
                            $this->view->setVar('user_tags', $user_tags);
                            $this->view->setVar('user_top_tags', array_slice($user_tags, 0, 10));
                        }
                        
                        $this->page->keywords .= ', ' . $U->user_login . ', футболки, толстовки, майки, кружки, свитшоты, постеры';
                
                        // "следующий / предыдущий" автор
                        $sth = App::db()->query("SELECT u.`user_id`, u.`user_login`
                                    FROM `goods` AS g, `users` AS u, `users_meta` um
                                    WHERE
                                            um.`meta_name`   = 'goodApproved'
                                        AND um.`user_id`     = u.`user_id`
                                        AND g.`user_id`      = u.`user_id`
                                        AND g.`good_visible` = 'true'
                                        AND g.`good_status` NOT IN ('customize', 'deny')
                                        AND u.`user_status`  = 'active'
                                    GROUP BY u.`user_id`");
                                    
                        $users = $sth->fetchAll();
                
                        foreach ($users as $k => $u)
                        {
                            if ($u['user_id'] == $U->id)
                            {
                                if ($users[$k - 1])
                                    $prev_user = $users[$k - 1];
                                else
                                    $prev_user = $users[count($users) - 1];
                
                                if ($users[$k + 1])
                                    $next_user = $users[$k + 1];
                                else
                                    $next_user = $users[0];
                            }
                        }
                
                        $next_user['user_login'] = str_replace('.livejournal.com', '', stripslashes($next_user['user_login']));
                        $prev_user['user_login'] = str_replace('.livejournal.com', '', stripslashes($prev_user['user_login']));
                
                        $this->view->setVar('next_user', $next_user);
                        $this->view->setVar('prev_user', $prev_user);
                    }
                    else 
                    {
                        // выдёргиваем теги избранных работ
                        $user_tags = App::db()->query("SELECT t.`name`, t.`slug`, COUNT(tr.`object_id`) AS goods
                                    FROM `tags_relationships` tr, `tags` t
                                    WHERE 
                                            tr.`object_id` IN ('" . implode("', '", array_keys($user_goods)) . "')
                                        AND tr.`tag_id`        = t.`tag_id`
                                        AND t.`tag_id` != '13775'
                                        AND tr.`object_type`   = '1'
                                    GROUP BY t.`tag_id`
                                    ORDER BY goods DESC")->fetchAll();
                
                        if (count($user_tags) > 0)
                        {
                            $this->view->setVar('user_tags', $user_tags);
                            $this->view->setVar('user_top_tags', array_slice($user_tags, 0, 10));
                        }
                    }
                    
                    break;
                    
                    
                /**
                 * Стартовая входная страница
                 * /catalog/[male|female|kids|futbolki|tolstovki|sweatshirts|stickers|cases|home]/
                 */
                case 'start':
                    
                    $this->page->tpl = 'catalog/startpage.tpl';
                    
                    $this->page->import(array(
                        '/js/2012/button_to_top.js',
                        '/js/vote_catalog.js',
                    ));
                
                    /**
                     * Для дома
                     */
                    if (in_array($this->page->reqUrl[1], ['', 'male', 'female', 'kids', 'home']))
                    {
                         $styles = array(
                            styleCategory::$BASECATS['pillows']['def_style'] => [],
                            styleCategory::$BASECATS['textile']['def_style']['female'] => [],
                            styleCategory::$BASECATS['patterns-bag']['def_style']['female'] => [],
                            styleCategory::$BASECATS['poster']['def_style_p']['vertical'] => [
                                'style_slug' => styleId2styleSlug(styleCategory::$BASECATS['poster']['def_style_p']['vertical'])
                            ], 
                        );
                    
                        $sth = App::db()->query("SELECT 
                                g.`good_id`, g.`good_name`, g.`good_voting_end`, gp.`pic_name`, p.`picture_path`
                            FROM 
                                `goods` g,
                                `good_pictures` gp,
                                `pictures` p
                            WHERE 
                                    g.`good_status` = 'pretendent'
                                AND g.`good_voting_end` >= NOW() - INTERVAL 6 MONTH
                                AND g.`good_visible` = 'true'
                                AND g.`good_id` = gp.`good_id`
                                AND gp.`pic_name` IN ('catalog_preview_" . implode("', 'catalog_preview_", array_keys($styles)) . "')
                                AND gp.`pic_id` = p.`picture_id`
                            ORDER BY g.`good_voting_end` DESC
                            ");
                                
                        $pics = $sth->fetchAll();
                        
                        //printr($pics);
                        
                        // разбиваем все картинки на группы по дате награждения
                        foreach ($pics as $p) {
                            $groups[$p['good_voting_end']][] = $p;
                        }
                        
                        // сбрасываем ключи
                        $groups = array_values($groups);
                        
                        shuffle($groups[0]);
                        shuffle($groups[1]);
                        
                        // заполняем массив картинками, перебирая группы награждения по очереди
                        foreach ($groups as $g) 
                        {
                            foreach ($g as $p) 
                            {
                                $sid = str_replace('catalog_preview_', '', $p['pic_name']);
                                
                                if (!$styles[$sid]['good_id'] && !in_array($p['good_id'], $exclude)) 
                                {
                                    $exclude[] = $p['good_id'];
                                    $styles[$sid] = array_merge($styles[$sid], $p);
                                }
                            }
                        }
                        
                        $this->view->setVar('goods', $styles);
                        
                        /**
                         * Рандомная кружка
                         */
                        if (!$cups = App::memcache()->get('CUPS'))
                        {
                            $sth = App::db()->prepare("SELECT 
                                    g.`good_id`, g.`good_name`, p.`picture_path`
                                  FROM 
                                    `goods` g, `good_pictures` gp, `pictures` p, `good_pictures` gp1
                                  WHERE
                                        gp.`pic_name` = 'catalog_preview_" . styleCategory::$BASECATS['cup']['promo'][0] . "'
                                    AND gp.`pic_id` = p.`picture_id`
                                    AND gp.`good_id` = g.`good_id`
                                    
                                    AND gp1.`pic_name` = 'cup'
                                    AND gp1.`good_id` = g.`good_id`
                                    AND gp1.`pic_id` > '0'
                                    
                                    AND g.`good_status` IN ('printed', 'pretendent')
                                    AND g.`good_visible` = 'true'
                                  GROUP BY 
                                    g.`good_id`");
                                                
                            $sth->execute();
                            
                            $cups = $sth->fetchAll();
                            
                            App::memcache()->set('CUPS', $cups, false, 2 * 86400);
                        }
                            
                        shuffle($cups);
            
                        $this->view->setVar('cup', array_shift($cups));
                    }
                    
                    /**
                     * Чехлы
                     */
                    if (in_array($this->page->reqUrl[1], ['cases']))
                    {
                        $styles = [];
                        
                        foreach (style::findAll(['category' => 73, 'onstock' => true, 'orderby' => "s.`style_order` DESC"]) as $k => $s) 
                        {
                            $s['old_price'] = $s['price'];
                            $s['price'] = round($s['price'] * (1 - ($s['discount']) / 100));
                            
                            $styles[$s['style_id']] = $s;
                        }
                        
                        $sth = App::db()->query("SELECT 
                                g.`good_id`, g.`good_name`, g.`good_voting_end`, gp.`pic_name`, p.`picture_path`
                            FROM 
                                `goods` g,
                                `good_pictures` gp,
                                `pictures` p
                            WHERE 
                                    g.`good_status` = 'pretendent'
                                AND g.`good_voting_end` >= NOW() - INTERVAL 6 MONTH
                                AND g.`good_visible` = 'true'
                                AND g.`good_id` = gp.`good_id`
                                AND gp.`pic_name` IN ('catalog_preview_" . implode("', 'catalog_preview_", array_keys($styles)) . "')
                                AND gp.`pic_id` = p.`picture_id`
                            ORDER BY g.`good_voting_end` DESC
                            ");
                                
                        $pics = $sth->fetchAll();
                        
                        //printr($pics);
                        
                        // разбиваем все картинки на группы по дате награждения
                        foreach ($pics as $p) {
                            $groups[$p['good_voting_end']][] = $p;
                        }
                        
                        // сбрасываем ключи
                        $groups = array_values($groups);
                        
                        shuffle($groups[0]);
                        shuffle($groups[1]);
                        
                        // заполняем массив картинками, перебирая группы награждения по очереди
                        foreach ($groups as $g) 
                        {
                            foreach ($g as $p) 
                            {
                                $sid = str_replace('catalog_preview_', '', $p['pic_name']);
                                
                                if (empty($styles[$sid]['picture_path']) && !in_array($p['good_id'], $exclude)) 
                                {
                                    $exclude[] = $p['good_id'];
                                    $styles[$sid]['picture_path'] = $p['picture_path'];
                                }
                            }
                        }
                        
                        //printr($styles, 1);
                        
                        $this->view->setVar('cases', $styles);
                    }
                    
                    /**
                     * Наклейки
                     */
                    if (in_array($this->page->reqUrl[1], ['', 'male', 'female', 'kids', 'stickers']))
                    {
                        if ($this->page->reqUrl[1] == 'stickers') {
                            array_unshift($this->page->breadcrump, array('link' => '', 'caption' => 'Наклейки'));
                        }
                    
                        /**
                         * 3 рандомных картинки из новинок для наклеек на гаджеты 
                         */
                        $styles = array(
                            styleCategory::$BASECATS['phones']['def_style'] => '', 
                            styleCategory::$BASECATS['laptops']['def_style'] => '', 
                            styleCategory::$BASECATS['touchpads']['def_style'] => '',
                        );
                    
                        $sth = App::db()->query("SELECT 
                                g.`good_id`, g.`good_name`, g.`good_voting_end`, gp.`pic_name`, p.`picture_path`
                            FROM 
                                `goods` g,
                                `good_pictures` gp,
                                `pictures` p
                            WHERE 
                                    g.`good_status` = 'pretendent'
                                AND g.`good_voting_end` >= NOW() - INTERVAL 6 MONTH
                                AND g.`good_visible` = 'true'
                                AND g.`good_id` = gp.`good_id`
                                AND gp.`pic_name` IN ('catalog_preview_" . implode("', 'catalog_preview_", array_keys($styles)) . "')
                                AND gp.`pic_id` = p.`picture_id`
                            ORDER BY g.`good_voting_end` DESC
                            ");
                                
                        $pics = $sth->fetchAll();
                        
                        //printr($pics);
                        
                        // разбиваем все картинки на группы по дате награждения
                        foreach ($pics as $p) {
                            $groups[$p['good_voting_end']][] = $p;
                        }
                        
                        // сбрасываем ключи
                        $groups = array_values($groups);
                        
                        shuffle($groups[0]);
                        shuffle($groups[1]);
                        
                        // заполняем массив картинками, перебирая группы награждения по очереди
                        foreach ($groups as $g) 
                        {
                            foreach ($g as $p) 
                            {
                                $sid = str_replace('catalog_preview_', '', $p['pic_name']);
                                
                                if (empty($styles[$sid]) && !in_array($p['good_id'], $exclude)) 
                                {
                                    $exclude[] = $p['good_id'];
                                    $styles[$sid] = $p;
                                }
                            }
                        }
                        
                        $this->view->setVar('vinil', $styles);
                    
                        /**
                         * автонаклейки
                         */
                        $sth = App::db()->prepare("SELECT
                                g.`good_id`, 
                                g.`good_name`, 
                                gpic.`pic_name`,
                                p.`picture_path`
                              FROM
                                `goods` AS g,
                                `users` AS u,
                                `pictures` AS p,
                                `good_pictures` gpic,
                                `good_pictures` gpic1 
                              WHERE 
                                    g.`user_id`      = u.`user_id`
                                AND g.`good_status` IN ('printed', 'pretendent')
                                AND g.`good_visible` = 'true'
                                
                                AND gpic1.`good_id`  = g.`good_id` 
                                AND gpic1.`pic_name` = 'auto'  
                                AND gpic1.`pic_id`   > 0 
                                
                                AND gpic.`good_id`  = g.`good_id` 
                                AND gpic.`pic_name` IN ('as_oncar_0', 'as_oncar_1', 'as_oncar_2', 'as_oncar_3', 'as_oncar_4', 'as_sticker') 
                                AND gpic.`pic_id`   = p.`picture_id`  
                                 
                                AND g.`competition_id` = '0'
                                
                              ORDER BY g.`good_voting_end` DESC
                              LIMIT 20");
                              
                        $sth->execute();
                        
                        $rs = $sth->fetchAll();
                        
                        shuffle($rs);
                        
                        foreach ($rs as $p) 
                        {
                            if (!$auto[$p['pic_name']]) {
                                $auto[$p['pic_name']] = $p;
                            }
                        }
                        
                        $this->view->setVar('auto', $auto);
                    
                        /**
                         * Рандомный стикерсет
                         */
                        if (!$stickersets = App::memcache()->get('STICKERSETS'))
                        {
                            $sth = App::db()->prepare("SELECT 
                                    g.`good_id`, g.`good_name`, p.`picture_path`
                                  FROM 
                                    `goods` g, `good_pictures` gp, `pictures` p, `good_pictures` gp1
                                  WHERE
                                        gp.`pic_name` = 'stickerset_preview'
                                    AND gp.`pic_id` = p.`picture_id`
                                    AND gp.`good_id` = g.`good_id`
                                    
                                    AND gp1.`pic_name` = 'stickerset'
                                    AND gp1.`good_id` = g.`good_id`
                                    AND gp1.`pic_id` > '0'
                                    
                                    AND g.`good_status` NOT IN ('new', 'deny', 'customize')
                                  GROUP BY 
                                    g.`good_id`");
                                                
                            $sth->execute();
                            
                            $stickersets = $sth->fetchAll(PDO::FETCH_ASSOC);
                            
                            App::memcache()->set('STICKERSETS', $stickersets, false, 2 * 86400);
                        }
                            
                        shuffle($stickersets);
            
                        $this->view->setVar('stickerset', array_shift($stickersets));
                    
                        /**
                         * 3 рандомных наклейки на доски
                         */
                        /*
                        $styles += array(593 => '', 592 => '', 611 => '');
                    
                            $sth = App::db()->prepare("SELECT 
                                    g.`good_id`, g.`good_name`, g.`good_voting_end`, gp.`pic_name`, p.`picture_path`
                                FROM 
                                    `goods` g,
                                    `good_pictures` gp,
                                    `pictures` p
                                WHERE 
                                        g.`good_status` = 'pretendent'
                                    AND g.`good_voting_end` >= NOW() - INTERVAL 2 MONTH
                                    AND g.`good_visible` = 'true'
                                    AND g.`good_id` = gp.`good_id`
                                    AND gp.`pic_name` IN ('catalog_preview_" . implode("', 'catalog_preview_", array_keys($styles)) . "')
                                    AND gp.`pic_id` = p.`picture_id`
                                ORDER BY g.`good_voting_end` DESC");
                                    
                            $sth->execute();
                            
                            $pics = $sth->fetchAll(PDO::FETCH_ASSOC);
                            
                            // разбиваем все картинки на группы по дате награждения
                            foreach ($pics as $p) {
                                $groups[$p['good_voting_end']][] = $p;
                            }
                            
                            // сбрасываем ключи
                            $groups = array_values($groups);
                            
                            shuffle($groups[0]);
                            shuffle($groups[1]);
                            
                            //$Thumb = new S3Thumb(array('ic1.maryjane.ru', 'ic2.maryjane.ru', 'ic3.maryjane.ru', 'ic4.maryjane.ru'), S3AccessKey, S3SecretKey, S3CryptKey);
                            
                            // заполняем массив картинками, перебирая группы награждения по очереди
                            foreach ($groups as $g) 
                            {
                                foreach ($g as $p) 
                                {
                                    $sid = str_replace('catalog_preview_', '', $p['pic_name']);
                                    
                                    if (empty($styles[$sid])) 
                                    {
                                        //$p['path'] = $Thumb->url($row['picture_path'], 252, 260);
                                        $styles[$sid] = $p;
                                    }
                                }
                            }
                
                            $this->view->setVar('goods', $styles);
                        */
                    }
                    
                    /**
                     * Одежда
                     */
                    if (in_array($this->page->reqUrl[1], ['', 'male', 'female', 'kids', 'futbolki', 'tolstovki', 'sweatshirts']))
                    {
                        $sth = App::db()->query("SELECT g.`good_id`, g.`good_sex`, g.`good_sex_alt`, p.`picture_path`, gp.`pic_name`
                                    FROM `goods` g, `good_pictures` gp, `pictures` p
                                    WHERE
                                            g.`good_voting_winner` = '1'  
                                        AND g.`good_voting_end` >= NOW() - INTERVAL 5 YEAR
                                        AND g.`good_visible` = 'true'
                                        AND gp.`good_id` = g.`good_id`
                                        AND gp.`pic_name` like 'catalog_preview_%'
                                        AND gp.`pic_id` = p.`picture_id`
                                        AND g.`good_status` = 'pretendent'
                                        AND g.`good_visible` = 'true'
                                        AND (g.`good_domain` = 'mj' || g.`good_domain` = 'all')
                                    ORDER BY g.`good_voting_end` DESC");
                        
                        foreach ($sth->fetchAll() as $g) 
                        {
                            $g['pic_name'] = str_replace('catalog_preview_', '', $g['pic_name']);
                            
                            if (!$goods['sexes'][$g['good_id']]) {
                                $goods['sexes'][$g['good_id']] = ['gid' => $g['good_id'], $g['good_sex'] => true, $g['good_sex_alt'] => true]; 
                            }
                            
                            $goods['pics'][$g['good_id']][$g['pic_name']] = $g['picture_path'];
                        }

                        shuffle($goods['sexes']);
                        
                        //printr($goods);
                        if ($filters['category']) {
                            $categorys = [styleCategory::$BASECATS[$filters['category']]['id']];
                            
                            if ($filters['category'] == 'sweatshirts') {
                                array_push($categorys, 88);
                            }
                            
                            if ($filters['category'] == 'tolstovki') {
                                array_push($categorys, 122);
                            }
                        }
                        
                        foreach (style::findAll(['cat' => 'wear', 'onstock' => true, 'sex' => $filters['sex'], 'categorys' => $categorys, 'excludecat' => [87, 100, 71, 118, 24], 'orderby' => 'IF (s.`style_sex` = "male", 1, 0) DESC, IF (s.`style_category` = 2, 1, 0) DESC, s.`style_id` ASC']) as $k => $s) 
                        {
                            $s['old_price'] = $s['price'];
                            $s['price'] = round($s['price'] * (1 - ($s['discount']) / 100));
                            $s['style_name'] = str_replace('(полная запечатка)', '<br>(полная запечатка)', $s['style_name']);
                            
                            // когда мы просматриваем толстовок или свитшотов, то дополняем её носителями из смежных категорий с полной запечаткой
                            if ($filters['category'] && ($s['category'] == 'patterns-sweatshirts' || $s['category'] == 'patterns-tolstovki')) {
                                $s['category'] = str_replace('patterns-', '', $s['category']);
                            }
                            
                            $cats[$s['style_sex']][$s['category']][$s['style_id']] = $s;
                        }
                        
                        $styles = [];
                        
                        foreach ($cats as $s => $sex) 
                        {
                            foreach ($sex as $k => $v) 
                            {
                                // для детских свитшотов с полной запечаткой или выбрана категория носителей выбираем все носители 
                                if (($s == 'kids' && $k == 'patterns-sweatshirts') || ($s == 'female' && $k == 'patterns-sweatshirts') || $filters['category']) {
                                    $sids = array_keys($v);
                                }
                                // для всех остальных категорий ноисителей по одному случайному 
                                else 
                                {
                                    $keys = array_keys($v);
                                    shuffle($keys);
                                    $sids = [array_pop($keys)];
                                }
                                
                                foreach ($sids AS $sid)
                                {
                                    //printr("$s $k -> $sid");
                                    
                                    foreach ($goods['sexes'] as $kk => $g) 
                                    {
                                        // если работа подходящего пола и у неё есть нужная картинка
                                        if ($g[$s] && $goods['pics'][$g['gid']][$sid]) {
                                            $styles[$s][$sid]['picture_path'] = $goods['pics'][$g['gid']][$sid];
                                            unset($goods['sexes'][$kk]);
                                            break;
                                        }
                                    }
                                    
                                    if ($styles[$s][$sid]) {
                                        $styles[$s][$sid] = array_merge($styles[$s][$sid], $v[$sid]);
                                    }
                                }
                            }
                        }
                        
                        $this->view->setVar('wear', $styles);
                    }
                    
                    /**
                     * Топ-теги
                     */
                    if (!$toptags = App::memcache()->get('CATALOG_TOP_TAGS_START'))
                    {
                        $sth = App::db()->query("SELECT t.`tag_id`, t.`name`, t.`slug`, p.`picture_path`, COUNT(DISTINCT(g.`good_id`)) as `count`
                                      FROM `tags` AS t, `tags_relationships` tr, `goods` g, `pictures` p
                                      WHERE
                                            tr.`tag_id` = t.`tag_id`
                                        AND tr.`object_id` = g.`good_id`
                                        AND tr.`object_type` = '1'
                                        AND g.`good_status` IN ('printed', 'pretendent')
                                        AND g.`good_visible` = 'true'
                                        AND t.`pic_id` = p.`picture_id`
                                        AND t.`tag_id` != '13775'
                                        AND t.`show_in_collections` = '1'
                                      GROUP BY t.`tag_id`
                                      HAVING count > 2
                                      ORDER BY t.`name`
                                      LIMIT 25");
            
                        $toptags = $sth->fetchAll();
            
                        App::memcache()->set('CATALOG_TOP_TAGS_START', $toptags, false, 2 * 86400);
                    }
            
                    foreach ($toptags AS $k => $tt) {
                        if (in_array($tt['slug'], array('detskie', '8marta')))
                            unset($toptags[$k]);
                            
                        /*
                        if ($tt['slug'] == '14february' || $tt['slug'] == '23february') {
                            $ng = $toptags[$k];
                            unset($toptags[$k]);
                            array_unshift($toptags, $ng);
                        }
                        */
                    }
            
                    $this->view->setVar('TAGS', $toptags);
                    
                    break;
                
                /**
                 * Стартовая страница паттернов, не указано какой именно носитель выбран
                 */    
                case 'start-patterns':
                
                    $this->page->import(['/js/vote_catalog.js', '/css/catalog/list.patterns.css']);
                
                    $this->page->tpl = 'catalog/patterns.tpl';
                    $this->page->import(array('/js/p/catalog.patterns.js',));
                    
                    if (!$goods = App::memcache()->get('GOOD_PATTERNS'))
                    {
                        $sth = App::db()->prepare("SELECT 
                                g.`good_id`,
                                g.`good_name`,                          
                                g.`good_sex`,
                                g.`good_sex_alt`,
                                u.`user_id`,
                                u.`user_login`,
                                u.`user_designer_level`,
                                city.`name` AS city_name
                            FROM 
                                `goods` g,
                                `users` u
                                    LEFT JOIN `city` AS city ON u.`user_city` = city.`id`,
                                `good_pictures` gp
                            WHERE
                                    gp.`pic_name` = 'patterns'
                                AND gp.`pic_id` > '0'
                                AND gp.`good_id` = g.`good_id`
                                AND g.`good_status` IN ('printed', 'pretendent')
                                AND g.`good_domain`  IN ('mj', 'all')
                                AND g.`good_visible` = 'true'
                                AND g.`user_id` = u.`user_id`
                                AND u.`user_status` = 'active'
                                ");
                        
                        $sth->execute(array(
                        ));
                        
                        $goods = $sth->fetchAll();
                        
                        App::memcache()->set('GOOD_PATTERNS', $goods, false, 86400);
                    }
                    
                    shuffle($goods);
                    
                    foreach (array_slice($goods, 0, 20) as $g) {
                        $ggoods[$g['good_id']] = $g;
                        $ggoods[$g['good_id']]['good_name'] = stripslashes($g['good_name']);
                        $ggoods[$g['good_id']]['user_login'] = stripslashes($g['user_login']);
                        $ggoods[$g['good_id']]['user_designer_level'] = user::designerLevel2Picture($g['user_designer_level']);
                        $ggoods[$g['good_id']]['user_avatar'] = user::userAvatar($g['user_id'], 25);
                        
                        $keys[] = $g['good_id'];
                    }
                    
                    //printr($ggoods);
                    
                    // грудь    
                    $sth = App::db()->prepare("SELECT 
                            p.`picture_path`,
                            IF (gp2.`pic_id` < 0, '1', '0') AS hidden,
                            gs.`style_id`,
                            gs.`good_stock_id`,
                            gs.`good_stock_price` AS p,
                            gs.`good_stock_discount` AS d,
                            ROUND(gs.`good_stock_price` * (1 - gs.`good_stock_discount` / 100)) AS price,
                            s.`style_color`,
                            s.`style_sex`,
                            s.`style_name`,
                            s.`style_slug`,
                            gs.`size_id`,
                            sc.`cat_slug`,
                            gp2.`pic_name`,
                            g.`good_id`
                        FROM 
                            `goods` g,
                            `good_pictures` gp2,
                            `pictures` p,
                            `good_stock` gs,
                            `styles_category` sc,
                            `styles` s
                        WHERE
                                g.`good_id` IN ('" . implode("', '", $keys) . "')
                            AND gp2.`good_id` = g.`good_id`
                            AND gp2.`pic_name` IN ('catalog_preview_619', 'catalog_preview_620', 'catalog_preview_621', 'catalog_preview_752')
                            AND ABS(gp2.`pic_id`) = p.`picture_id`
                            AND gp2.`pic_name` = CONCAT('catalog_preview_', gs.`style_id`)
                            AND gs.`good_id` = '0'
                            AND gs.`style_id` = s.`style_id`
                            AND s.`style_category` = sc.`id`
                        GROUP BY CONCAT(g.`good_id`, gs.`style_id`)");
                    
                    $sth->execute();
                    
                    foreach ($sth->fetchAll() as $p) {
                        $pics[$p['good_id']][$p['pic_name']] = $p;
                    }
                    
                    // спинки
                    $sth = App::db()->prepare("SELECT 
                            p.`picture_path`,
                            gp2.`pic_name`,
                            gp2.`good_id`
                        FROM 
                            `good_pictures` gp2,
                            `pictures` p
                        WHERE
                                gp2.`good_id` IN ('" . implode("', '", $keys) . "')
                            AND gp2.`pic_name` IN ('catalog_preview_619_back', 'catalog_preview_620_back', 'catalog_preview_621_back', 'catalog_preview_752_back')
                            AND ABS(gp2.`pic_id`) = p.`picture_id`");
                        
                    $sth->execute(array(
                        'good_id' => $good['good_id'],
                    ));
                    
                    foreach ($sth->fetchAll() as $p) {
                        $pics[$p['good_id']][str_replace('_back', '', $p['pic_name'])]['back'] = $p['picture_path'];
                    }
                    
                    foreach ($pics as $k => $g) 
                    {
                        shuffle($g);
                        
                        foreach ($g as $sid => $pic) 
                        {
                            if ($pic['style_sex'] == $ggoods[$k]['good_sex'] || $pic['style_sex'] == $ggoods[$k]['good_sex_alt'] || ($ggoods[$k]['good_sex'] == '' && $ggoods[$k]['good_sex_alt'] == '')) {
                                break;
                            }
                        }
                        
                        $summary[] = array_merge($ggoods[$k], $pic);
                    }
                    
                    shuffle($summary);
                    
                    $this->view->setVar('pics', $summary);
                   
                    break;
                    
                /**
                 * СПИСОК ДИЗАЙНОВ, тряпки
                 */
                default:
                case 'top':
                case 'main':
                case 'winners':
                case 'male':
                case 'female':
                case 'gadget':
                
                    if ($top)
                    {
                        $this->page->canonical = '/' . implode('/', array_merge(array($this->page->module, 'top'), array_diff($this->page->reqUrl, array($this->page->module, 'top')))) . '/';
                    }
                    
                    if (!$tag)
                    {
                        if ($filters['color'])
                        {
                            $this->page->canonical = str_replace(';color,' . $filters['color'], '', $this->page->url);
                        }
                        
                        if ($filters['size'])
                        {
                            $this->page->canonical = '/catalog/';
                        }
                    }
                    
                    $this->page->import(array(
                        '/js/2012/autoscroll.js',
                        '/js/vote_catalog.js',
                        '/js/p/catalog.quickBuy.js'
                    ));
                 
                    /**
                     * шаблоны отображения
                     */
                    $list_tpl = 'list.goods.photos.tpl';
                   
                    if (!$specific_content_tpl) {
                        
                        if ($filters['category'] && $mode != 'preview') 
                        {
                            if (styleCategory::$BASECATS[$filters['category']]['sexes'] || $Style->category == 'textile' || $Style->category == 'cup' || $Style->category == 'bag' || $Style->category == 'poster') {
                                if (in_array($filters['category'], ['patterns', 'patterns-sweatshirts', 'patterns-tolstovki', 'body', 'bomber'])) {
                                    $this->page->tpl = 'catalog/list.patterns.tpl';
                                    $this->page->import(['/css/catalog/list.patterns.css']);
                                    $list_tpl = 'list.goods.patterns.tpl';
                                } else {
                                    $this->page->tpl = 'catalog/list.photos.tpl';
                                }
                            } else {
                                $this->page->tpl = 'catalog/list.gadgets.tpl';
                            }
                            
                            if (!styleCategory::$BASECATS[$filters['category']]['sexes']) {
                                $list_tpl  = 'list.goods.' . $filters['category'] . '.tpl';
                            }
                            
                            if ($filters['category'] == 'poster' && !$Style) {
                                $this->page->tpl = 'catalog/list.tpl';
                                $list_tpl = 'list.goods.tpl';
                            }
                        } else {
                            $this->page->tpl = 'catalog/list.tpl';
                            $list_tpl = 'list.goods.tpl';
                        }
                    } else {
                        $this->page->tpl = $specific_content_tpl;
                    }
                    /**
                     * end шаблоны отображения
                     */ 
            
                    if (empty($page))
                        $page = 1;
            
                    $baseOnPage = 36;
            
                    if ($notees && $page > 1)
                        $baseOnPage = 15;
            
                    if (!$onPage)
                        $onPage = $baseOnPage;
            
                    if (empty($orderBy))
                    {
                        if ($_COOKIE['datasort'][$this->page->url_encoded]) {
                            $orderBy = $_COOKIE['datasort'][$this->page->url_encoded];
                        } else {
                            if (!$orderBy && $Style->category == 'cup') {
                                $orderBy = 'new';
                            } else {
                                    $orderBy = 'popular';
                            }
                        }
                    }
            
                    /**
                     * авторская витрина
                     */ 
                    if ($U)
                    {
                        if (!array_search('selected', $this->page->reqUrl))
                        {
                            $aq[] = " g.`user_id` = '" . $U->id . "'";
                            
                            $page       = 1;
                            $baseOnPage = $onPage = 1000;
                            
                            if (!$orderBy) {
                                if (in_array($this->user->id, array($U->id, 27278, 6199))) {
                                    $orderBy    = 'shopwindow';
                                } else {
                                    $orderBy    = 'new';        
                                }
                            }
                                        
                            if ($this->user->id != $U->id)
                            {
                                // Selected (add / remove)
                                $sth = App::db()->query("SELECT `selected_id` FROM `selected` WHERE `user_id` = '" . $this->user->id . "' AND `selected_id` = '" . $U->id . "' LIMIT 1");
                                
                                if ($sth->rowCount() == 0)
                                    $this->view->setVar('selected', false);
                                else
                                    $this->view->setVar('selected', true);
                                
                                // код для подписки или отписки на уведомления
                                $this->view->setVar('subscribe_code', md5($this->user->id . $this->user->user_email . $this->user->user_register_date));
                            }
                             
                            // автор смотри на свою витрину
                            if ($U->id == $this->user->id)
                            {
                                $promo = $this->user->getPromoUrl($this->page->url);
                                $this->view->setVar('promoUrl', $promo['link']);
                            }
                
                            $this->page->canonical = '/catalog/' . $U->user_login . '/';
                
                            
                            if (!empty($U->meta->personal_title))
                                $this->page->title[0] = $U->meta->personal_title;
                            else
                                $this->page->title[0] = 'Магазин футболок ' . $U->user_login;
                    
                            $PD[] = (!empty($U->meta->personal_title)) ? $U->meta->personal_title . ', ' . $U->user_login : 'Магазин футболок ' . $U->user_login;
                            
                            if ($U->user_show_name == 'true') {
                                $PD[] = $U->user_name;
                            }
                            
                            if (!empty($U->user_city)) {
                                $PD[] = $U->user_city;
                            }
                            
                            $PD[] = 'авторский магазин футболок, виниловые наклейки, дизайнерские толстовки, постеры и прочее';
                
                            // описание для витрины автора - теги к его работам
                            $sth = App::db()->query("SELECT t.`name`, t.`slug`, COUNT(g.`good_id`) AS goods
                                        FROM `tags_relationships` tr, `tags` t, `goods` g, `good_pictures` gp
                                        WHERE 
                                                g.`user_id`        = '" . $U->id . "'
                                            AND g.`good_visible`   = 'true'
                                            AND g.`good_status`   != 'customize'
                                            AND g.`good_status`   != 'deny'
                                            AND g.`good_id`        = tr.`object_id`
                                            AND tr.`tag_id`        = t.`tag_id`
                                            AND t.`tag_id`        != '13775'
                                            AND tr.`object_type`   = '1'
                                            AND gp.`good_id`       = g.`good_id`
                                            AND gp.`pic_name`      IN ('" . implode("', '", array_keys(good::$srcs)) . "')
                                            AND gp.`pic_id`        > '0'
                                        GROUP BY t.`tag_id`
                                        ORDER BY goods DESC");
                
                            $user_tags = $sth->fetchAll();
                            
                            if (count($user_tags) > 0)
                            {
                                $this->page->keywords = '';
                                foreach ($user_tags as $ut) {
                                    $this->page->keywords .= ', ' . $ut['name'];
                                }
                                $this->page->keywords = trim($this->page->keywords, ', ');
                
                                $this->view->setVar('user_tags', $user_tags);
                                $this->view->setVar('user_top_tags', array_slice($user_tags, 0, 10));
                            }
                
                            // "следующий / предыдущий" автор
                            $users = App::db()->query("SELECT u.`user_id`, u.`user_login`
                                        FROM `goods` AS g, `users` AS u, `users_meta` um
                                        WHERE
                                                um.`meta_name`   = 'goodApproved'
                                            AND um.`user_id`     = u.`user_id`
                                            AND g.`user_id`      = u.`user_id`
                                            AND g.`good_visible` = 'true'
                                            AND g.`good_status` != 'customize'
                                            AND g.`good_status` != 'deny'
                                            AND u.`user_status`  = 'active'
                                        GROUP BY u.`user_id`")->fetchAll();
                                        
                            foreach ($users as $k => $u)
                            {
                                if ($u['user_id'] == $U->id)
                                {
                                    if ($users[$k - 1])
                                        $prev_user = $users[$k - 1];
                                    else
                                        $prev_user = $users[count($users) - 1];
                
                                    if ($users[$k + 1])
                                        $next_user = $users[$k + 1];
                                    else
                                        $next_user = $users[0];
                                }
                            }
                
                            $next_user['user_login'] = str_replace('.livejournal.com', '', stripslashes($next_user['user_login']));
                            $prev_user['user_login'] = str_replace('.livejournal.com', '', stripslashes($prev_user['user_login']));
                
                            $this->view->setVar('next_user', $next_user);
                            $this->view->setVar('prev_user', $prev_user);
                        }
                        else 
                        {
                            // выбираем все работы, которые лайкнул автор
                            $sth = App::db()->prepare("SELECT gl.`good_id`
                                                       FROM `good_likes` gl 
                                                       WHERE 
                                                            gl.`user_id` = :user AND gl.`negative` = '0'" . ($Style ? "AND gl.`pic_name` = 'catalog_preview_" . $Style->id . "'" : '') . "
                                                       GROUP BY gl.`good_id`");
                                                       
                            $sth->execute(array('user' => $U->id));
                            
                            foreach ($sth->fetchAll() AS $g) {
                               $user_goods[$g['good_id']] = $g; 
                            }
                            
                            $aq['goods'] = "g.`good_id` IN ('" . implode("', '", array_keys($user_goods)) . "')";
                        }
                    }
                    
            
                    if ($tag)
                    {
                        if ($tag['slug'] == 'krokodil' && $Style->style_slug == 'iphone-5-bumper') {}
                        elseif (in_array($tag['slug'], array('cherep', 'lisa')) && $Style->style_slug == 'case-iphone-4') {}
                        elseif (in_array($tag['slug'], array('kosmos')) && $Style->style_slug == 'iphone-5') {}
                        else {
                            //$this->page->noindex = TRUE;
                        }
                    }
                    
                    
                    if ($filters['category'])
                    {
                        if (styleCategory::$BASECATS[$filters['category']]['sexes'])
                        {
                            if ($Style)
                                $this->page->breadcrump[] = array(
                                    'link' => '/catalog/' . $Style->style_sex . '/', 
                                    'caption' => ($Style->style_sex == 'male' ? 'Мужские' : '') . ($Style->style_sex == 'female' ? 'Женские' : '') . ($Style->style_sex == 'kids' ? 'Детские' : '')
                                );
                            else
                                $this->page->breadcrump[] = array(
                                    'link' => '/catalog/' . ($filters['sex'] ? $filters['sex'] : 'male') . '/', 
                                    'caption' => (($filters['sex'] == 'male' || !$filters['sex']) ? 'Мужские' : '') . ($filters['sex'] == 'female' ? 'Женские' : '') . ($filters['sex'] == 'kids' ? 'Детские' : '')
                                );
                            
                            $this->page->breadcrump[] = array(
                                'link'    => '/' . $this->page->module . '/' . $filters['category'] . '/' . ($filters['sex'] ? $filters['sex'] : 'male') . '/',
                                'caption' => styleCategory::$BASECATS[$filters['category']]['title']
                            );
                            
                            if (!$filters['sex']) {
                                $filters['sex'] = styleCategory::$BASECATS[$filters['category']]['sexes'][0];
                                $this->view->setVar('fsex_not_selected', TRUE);
                            }
                            
                            if ($filters['sex'] != 'kids') {
                                $foo = array_diff(styleCategory::$BASECATS[$filters['category']]['sexes'], array($filters['sex']));
                                $another_sex = array_pop($foo);
                            }
                        }
                        
                        if ($filters['size'])
                            $this->page->title[] = 'размер - ' . sizeId2sizeName($filters['size']);
                        else {
                            $fsize_not_selected = TRUE;
                            $this->view->setVar('fsize_not_selected', $fsize_not_selected);
                        }
                        
                        
                        if ($filters['color']) {
                            //$this->page->title[] = 'цвет - ' . colorId2Name($filters['color']);
                        } else {
                            $fcolor_not_selected = TRUE;
                            $this->view->setVar('fcolor_not_selected', $fcolor_not_selected);
                        }
                        
                        if ($orderBy == 'new') {
                            $this->page->breadcrump[count($this->page->breadcrump) - 1]['caption'] .= ' | Новинки';
                            $this->page->title[] = 'Новинки';
                        } elseif ($orderBy == 'popular') {
                            $this->page->breadcrump[count($this->page->breadcrump) - 1]['caption'] .= ' | Популярные';
                            $this->page->title[] = 'Популярные';
                        } elseif ($orderBy == 'win_date') {
                            $this->page->breadcrump[count($this->page->breadcrump) - 1]['caption'] .= ' | Победители';
                            $this->page->title[] = 'Победители';
                        } elseif ($orderBy == 'top2013' || $orderBy == 'top2014' || $orderBy == 'top2015' || $orderBy == 'top2016') {
                            $this->page->breadcrump[count($this->page->breadcrump) - 1]['caption'] .= ' | Популярные в ' . str_replace('top', '', $orderBy) . ' году';
                            $this->page->title[] = 'Популярные в ' . str_replace('top', '', $orderBy) . ' году';
                        }
                        
                        if ($page > 1) {
                            $this->page->title[] = 'Страница ' . $page;
                            $this->page->description = $this->page->udescription = '';
                            $this->page->keywords = $this->page->keywords = '';
                        }
                 
                        // ---------------------------------------------------------------------------------------------
                        // Фильтры по размерам и цветам
                        // ---------------------------------------------------------------------------------------------
                        if (in_array($filters['category'], ['futbolki', 'longsleeve_tshirt', 'mayki-alkogolichki', 'tolstovki', 'sweatshirts'])) 
                        {
                            $Fcolorsizes = array();
                            
                            $sth = App::db()->query("SELECT s.`style_slug`, sz.`size_id`, sz.`size_name`, gs.`size_rus`, c.`id` AS id, c.`name` AS name, c.`name_en`, c.`group`, c.`hex`, gs.`good_stock_status` AS status
                                        FROM `sizes` sz, `good_stock_colors` c, `styles` s, `styles_category` sc, `good_stock` gs
                                        WHERE
                                                sc.`cat_slug` = '" . $filters['category'] . "'
                                            AND s.`style_category` = sc.`id`
                                            AND s.`style_sex` = '" . $filters['sex'] . "'
                                            AND s.`style_color` = c.`id`
                                            AND s.`style_front_picture` > '0'
                                            AND gs.`style_id` = s.`style_id` 
                                            AND gs.`size_id`  = sz.`size_id`
                                            AND gs.`good_stock_quantity` +- gs.`good_stock_inprogress_quantity` > '0'
                                            AND gs.`good_stock_visible` > '0'
                                            AND gs.`good_id` = '0'
                                        ORDER BY s.`style_order` DESC, sz.`order`");
                            
                            foreach ($sth->fetchAll() as $r) {
                                $Fcolorsizes[$r['id']][$r['size_id']] = $r;
                                $sizescolors[$r['size_id']][$r['id']] = $r;
                                
                                $Fsizes[$r['size_id']] = $r;
                                
                                $Fcolors[$r['id']] = $r;
                            }
                            
                            // дефолтный цвет для группы носителей ставим на первое место
                            if (styleCategory::$BASECATS[$filters['category']]['def_color'][$filters['sex']] && $Fcolors)
                            {
                                foreach ($Fcolors as $sk => $fc) 
                                {
                                    if ($sk == styleCategory::$BASECATS[$filters['category']]['def_color'][$filters['sex']])
                                    {
                                        unset($Fcolors[$sk]);
                                        $Fcolors = array($sk => $fc) + $Fcolors;
                                    }
                                }
                            }
                        
                            if (!$fsize_not_selected) {
                                $Fcolors = $sizescolors[$filters['size']];
                            }
                            
                            if (!$fcolor_not_selected) {
                                $Fsizes = $Fcolorsizes[$filters['color']];
                            }
                            
                            // ебучий хардкод
                            // если смотрим сумки
                            if ($fcolor_not_selected && $filters['category'] == 'sumki')
                            {
                                // если сортировка по новым
                                if ($orderBy == 'new')
                                    $filters['color'] = 102;
                                // если указан явно пол
                                elseif (array_search('female', $reqUrl)) {
                                    $filters['color'] = 4;
                                }
                                
                            }
                            
                            if (!$filters['color']) {
                                $filters['color'] = styleCategory::$BASECATS[$filters['category']]['def_color'][$filters['sex'] ? $filters['sex'] : 'male'];
                            }
                        
                            if ($Fcolors) {
                                foreach ($Fcolors as $ck => &$fc) {
                                    if (!$filters['color']) {
                                        $filters['color'] = $ck;
                                        $Fcolors[$ck]['class'] = 'on';
                                        
                                        break;
                                    } elseif (isset($filters['color']) && $filters['color'] == $ck) {
                                        $fc['class'] = 'on';
                                    }
                                }
                            }
                
                            if ($Fsizes) {
                                foreach ($Fsizes as $sk => &$fs) {
                                    if (!$filters['size']) {
                                        $foo = array_keys($Fcolorsizes[$filters['color']]);
                                        $filters['size'] = array_shift($foo);
                                        break;
                                    } elseif (isset($filters['size']) && $filters['size'] == $fs['size_id']) {
                                        $fs['class'] = 'on';
                                    }
                                }
                            }
                
                            if ($Fcolors) {
                                foreach ($Fcolors as $ck => $c) {
                                    $F2colors[(($c['status'] == '') ? '-' : $c['status'])][] = $c;
                                }
                            }
                            
                            $Fcolors = array();
                            
                            if (is_array($F2colors['new'])) {
                                $Fcolors = array_merge($Fcolors, $F2colors['new']);
                            }
                
                            if (is_array($F2colors['-'])) {
                                $Fcolors = array_merge($Fcolors, $F2colors['-']);
                            }
                
                            if (is_array($F2colors['few'])) {
                                $Fcolors = array_merge($Fcolors, $F2colors['few']);
                            }
                            
                            if (is_array($F2colors['preorder'])) {
                                $Fcolors = array_merge($Fcolors, $F2colors['preorder']);
                            }
                
                            $this->view->setVar('Fsizes', $Fsizes);
                            $this->view->setVar('Fcolors', $Fcolors);
                        }
                        // ---------------------------------------------------------------------------------------------
                        // end Фильтры
                        // ---------------------------------------------------------------------------------------------
                    }
                    else 
                    {
                        if ($orderBy == 'new') {
                            $this->page->breadcrump[] = ['caption' => 'Новинки'];
                            $this->page->title[] = 'Новинки';
                        } elseif ($orderBy == 'popular') {
                            $this->page->breadcrump[] = ['caption' => 'Популярные'];
                        } elseif ($orderBy == 'win_date') {
                            $this->page->breadcrump[] = ['caption' => 'Победители'];
                            $this->page->title[] = 'Победители';
                        } elseif ($orderBy == 'top2013' || $orderBy == 'top2014' || $orderBy == 'top2015' || $orderBy == 'top2016') {
                            $this->page->breadcrump[] = ['caption' => 'Популярные в ' . str_replace('top', '', $orderBy) . ' году'];
                            $this->page->title[] = 'Популярные в ' . str_replace('top', '', $orderBy) . ' году';
                        }
                    }
            
                    // Выбрана категория носителей без каких-либо дополнительных параметров
                    if ($Style)
                    {
                        $sth = App::db()->query("SELECT gs.`good_stock_id`, gs.`good_stock_quantity`, gs.`good_stock_price` AS p, gs.`good_stock_discount` AS d, gs.`good_stock_discount_promo` AS dp, 
                                        gs.`style_id`, gs.`good_stock_status`, 
                                        s.`style_category`, 
                                        s.`style_slug`, 
                                        s.`style_sex`
                                    FROM 
                                        `styles` s, `styles_category` sc, `good_stock` AS gs 
                                    WHERE 
                                            gs.`good_id` = '0' 
                                        AND gs.`good_stock_visible` = '1' 
                                        AND s.`style_visible` = '1'
                                        AND s.`style_print_block` != '' 
                                        AND gs.`style_id` = s.`style_id` 
                                        AND s.`style_category` = sc.`id` 
                                        AND s.`style_id` = '" . $Style->id . "'
                                        "
                                        . (($filters['size'])     ? " AND gs.`size_id` = '" . $filters['size'] . "'" : '') . "
                                    LIMIT 1");
                        
                        $stock = $sth->fetch();
                        
                        $filters['sex'] = $Style->style_sex;
                        $filters['category'] = $Style->category;
                        $filters['color'] = $Style->style_color;
                    }
                    else
                    {
                        if (count($filters) > 0 && $filters['category'] != 'poster')
                        {
                            if ($filters['category'] && !$filters['color'])
                            {
                                if (styleCategory::$BASECATS[$filters['category']]['def_style']) {
                                     $saq = ' AND s.`style_id` = "' . styleCategory::$BASECATS[$filters['category']]['def_style'][$filters['sex'] ? $filters['sex'] : 'male'] . '"';
                                }
                            }
                            else {
                                if ($filters['category'] && styleCategory::$BASECATS[$filters['category']])
                                   $saq .= " AND sc.`cat_slug` = '" . $filters['category'] . "'";
                                   
                                if ($filters['sex'])
                                   $saq .= " AND s.`style_sex` = '" . $filters['sex'] . "'";
                                   
                                if ($filters['color'])
                                   $saq .= " AND s.`style_color` = '" . $filters['color'] . "'";
                                
                                if ($filters['size'])
                                   $saq .= " AND gs.`size_id` = '" . $filters['size'] . "'";
                            }

                            $sth = App::db()->query("SELECT gs.`good_stock_id`, gs.`good_stock_quantity`, gs.`good_stock_price` AS p, gs.`good_stock_discount` AS d, gs.`good_stock_discount_promo` AS dp, 
                                            gs.`style_id`, gs.`good_stock_status`, 
                                            s.`style_category`, 
                                            s.`style_sex`, 
                                            s.`style_slug`
                                        FROM 
                                            `styles` s, `styles_category` sc, `good_stock` AS gs 
                                        WHERE 
                                                gs.`good_id` = '0' 
                                            AND gs.`good_stock_visible` = '1' 
                                            AND s.`style_visible` = '1'
                                            AND s.`style_print_block` != '' 
                                            AND gs.`style_id` = s.`style_id` 
                                            AND s.`style_category` = sc.`id`
                                            {$saq}  
                                        ORDER BY IF (gs.`good_stock_quantity` > 0, 1, 0) DESC, s.`style_id` ASC
                                        LIMIT 1");
                                   
                            $stock = $sth->fetch();
                            
                            $Style = new \application\models\style($stock['style_id']);
                        }
                    }
                    
                    // Выбираем весь размерный ряд для данного носителя на складе для блока "быстрой покупки"
                    if ($task == 'main' && $Style->cat_parent == 1) 
                    {
                        $sth = App::db()->query("SELECT gs.`good_stock_id`, gs.`good_stock_quantity` AS quantity, gs.`good_stock_price` AS p, gs.`good_stock_discount` AS d, gs.`good_stock_status`, gs.`size_rus`, sz.`size_name`, ROUND(gs.`good_stock_price` * (1 - gs.`good_stock_discount` / 100)) AS price 
                                                 FROM 
                                                    `sizes` sz,
                                                    `good_stock` AS gs 
                                                 WHERE 
                                                        gs.`good_id` = '0' 
                                                    AND gs.`good_stock_visible` = '1' 
                                                    AND gs.`style_id` = '" . $Style->id . "'
                                                    AND gs.`size_id` = sz.`size_id`
                                                    AND gs.`good_stock_quantity` +- gs.`good_stock_inprogress_quantity` > '0'
                                                ORDER BY sz.`order`");
                        
                        $this->view->setVar('style_sizes', $sth->fetchAll());
                    }
                    
                    if ($filters['category'] && $mode != 'preview')
                    {
                        if (in_array($Style->category, array('phones', 'touchpads')) && empty($Style->style_front_picture) && !empty($Style->pics['rez']['path'])) {
                            $aq['pic'] = "gp.`pic_name` = '" . $filters['category'] .  "_small'";
                            $this->page->tpl = 'catalog/list.tpl';
                            $list_tpl = 'list.goods.tpl';
                        } else {
                            if (in_array($filters['category'], ['patterns', 'patterns-sweatshirts', 'patterns-tolstovki', 'body', 'bomber']))
                                $aq['pic'] = "gp.`pic_name` = 'catalog_art_preview_" . $Style->id . "'";
                            elseif ($filters['category'] == 'poster' && !$Style)
                                $aq['pic'] = "gp.`pic_name` = 'poster_catalog_preview'";
                            else
                                $aq['pic'] = "gp.`pic_name` = 'catalog_preview_" . $Style->id . "'";
                        }
                        
                        if ($Style->style_name && (styleCategory::$BASECATS[$filters['category']]['sexes'] || $U)) {
                            array_unshift($this->page->title, $Style->style_name);
                        }
                    }
                    else
                    {
                        $aq['pic'] = "gp.`pic_name` = 'good_preview'";
                        
                        if ($Style->style_name && (styleCategory::$BASECATS[$filters['category']]['sexes'] || $U)) {
                            array_push($this->page->title, $Style->style_name);
                        }
                    }
                    
                    if ($this->user->authorized)
                    {
                        $sth = App::db()->query("SELECT `good_id` FROM `good_likes` WHERE `user_id` = '" . $this->user->id . "' AND `pic_name` = '" . ($filters['category'] ? 'catalog_preview_' . $stock['style_id'] : 'good_preview') . "'");
                        
                        foreach ($sth->fetchAll() as $r) {
                            $likes[$r['good_id']] = $r['good_id'];
                        }
                        
                        if ($likes[$good_id])
                            $goods[$good_id]['liked'] = TRUE;
                    }
                    
                    $this->view->setVar('style_id', $stock['style_id']);
                    $this->view->setVar('stock', $stock);
                    
                    $this->view->setVar('orderBy', $orderBy);
            
                    if ($tag['tag_id'] > 0) {
                        $orders[] = "SUM(IF (t.`tag_id` = '" . $tag['tag_id'] . "', 1, 0)) DESC";
                    }
                    
                    if ($this->page->reqUrl[1] == 'search') {
                        $orders[] = "IF (g.`good_id` IN ('" . implode("', '", $search_main_phrase_goods) . "'), 1, 0) DESC, g.`good_voting_winner` DESC";
                    }
            
                    // варианты сортировки
                    switch($orderBy)
                    {
                        case 'date':
                            $orders[] = " g.`good_date` DESC";
                            break;
                            
                        case 'likes':
                            $orders[] = " g.`good_likes` DESC";
                            break;
            
                        case 'new':
                            
                            if ($tag && $collection)
                                $orders[] = " g.`good_date` DESC, likes DESC";
                            else
                                $orders[] = " g.`good_voting_end` DESC, g.`good_voting_winner` DESC, likes DESC, g.`good_date` DESC";
                            
                            break;
            
                        case 'avg_grade':
                            $orders[] = " g.`good_voting_end` DESC, g.`good_avg_grade` DESC";
                            break;
                            
                        case 'avg_grade_designers':
                            $orders[] = " g.`good_voting_end` DESC, g.`good_avg_grade_designers` DESC";
                            break;
                            
                        case 'rating':
                            $orders[] = " g.`good_voting_end` DESC, g.`good_avg_grade_buyers` DESC";
                            break;
            
                        case 'grade':
                            $orders[] = "g.`good_voting_winner` DESC, g.`good_voting_end` DESC, likes DESC";
                            break;
            
                        case 'search':
                            $orders[] = "g.`good_voting_end` DESC, likes DESC";
                            break;
            
                        case 'win_date':
                            $orders[] = " g.`good_voting_end` DESC";
                            break;
            
                        case 'place':
                            $orders[] = "g.`good_voting_winner` DESC, g.`good_voting_end` DESC, likes DESC";
                            break;
                            
                        case 'recently_buy':
                            
                            if (!$last_buyed_goods = App::memcache()->get('last_buyed_goods_' . $Style->id)) {
                                $sth = App::db()->query("SELECT ubg.`good_id` FROM `" . \application\models\basketItem::$dbtable . "` ubg, `good_stock` gs, `" . good::$dbtable . "` g
                                                         WHERE ubg.`good_id` = g.`good_id` AND g.`good_status` = 'pretendent' AND g.`good_visible` = 'true' AND gs.`good_stock_id` = ubg.`good_stock_id` AND gs.`style_id` = '" . $Style->id . "'
                                                         ORDER BY ubg.`user_basket_good_id` DESC
                                                         LIMIT 100");
                                                         
                                foreach ($sth->fetcHAll() AS $g) {
                                    $last_buyed_goods[] = $g['good_id'];
                                }
                                
                                App::memcache()->set('last_buyed_goods_' . $Style->id, $last_buyed_goods,  false, 1 * 24 * 3600);
                            }
                            
                            $aq['recently_buy'] = " g.`good_id` IN ('" . implode("', '", $last_buyed_goods) . "')";
                            
                            break;
                            
                        case 'recently_view':
                            
                            if (!$last_viewed_goods = App::memcache()->get('last_viewed_goods_' . $Style->id)) {
                                $sth = App::db()->query("SELECT gv.`good_id` FROM `good__visits` gv, `" . good::$dbtable . "` g
                                                         WHERE gv.`good_id` = g.`good_id` AND g.`good_status` = 'pretendent' AND g.`good_visible` = 'true' ANd gv.`page` = 'catalog' AND gv.`style_id` = '" . $Style->id . "'
                                                         ORDER BY gv.`date` DESC
                                                         LIMIT 100");
                                                         
                                foreach ($sth->fetcHAll() AS $g) {
                                    $last_viewed_goods[] = $g['good_id'];
                                }
                                
                                App::memcache()->set('last_viewed_goods_' . $Style->id, $last_viewed_goods,  false, 1 * 24 * 3600);
                            }
                            
                            $aq['recently_view'] = " g.`good_id` IN ('" . implode("', '", $last_viewed_goods) . "')";
                            
                            break;
                            
                        /*
                        case 'search':
                            $orderBy = 'place';
                            $orders = "winners DESC, g.`good_voting_winner` DESC, g.`good_voting_end` DESC, likes DESC";
                            break;
                        */
                        
                        case 'top2013':
                        case 'top2014':
                        case 'top2015':
                        case 'top2016':
                            $orderBy = 'popular';
                            $orders[] = "g.`good_sold_year` DESC, g.`good_date` DESC";
                            break;
                        
                        case 'shopwindow':
                            $orders[] = "g.`good_visible` DESC, g.`good_date` DESC, likes DESC";
                            break;
                        
                        case 'popular_by_category':
                            
                            $orders[] = "o.`count` DESC";
                            
                            break;
                            
                        case 'visits':
                        
                            $orders[] = "g.`visits_2month` DESC";
                        
                            break;
                        
                        case 'artdir':
                        
                            $orders[] = " g.`good_voting_end` DESC";
                            $aq[] = "g.`mark` = 'artdir'";
                        
                            break;
                        
                        default:
                        case 'popular':
                            
                            $orderBy = 'popular';
                            
                            if ($tag) {
                               $orders[] = "g.`good_sold_3month` DESC, g.`good_date` DESC";
                            } else    
                               if (styleCategory::$BASECATS[$filters['category']]['sexes'])
                                   $orders[] = ($filters['category'] && in_array($stock['style_sex'], array('male', 'female')) ? "g.`good_sold_printshop_" . $stock['style_sex'] . "` DESC, " : '') . "g.`good_sold_printshop` DESC, g.`good_date` DESC";
                               else
                                   $orders[] = "(g.`good_sold_allskins` + g.`good_sold_printshop`) DESC, g.`good_date` DESC";
                            
                            break;
                    }
                    
                    
                    // Если не указано ни пользователя, ни тегов, ни темы
                    // - показываем претендентов и победителей, исключая витрины
                    if (!empty($U->id)) {} 
                    elseif ($this->page->module == 'tagall' || in_array($tag['slug'], array('mazda', '8marta', 'dlya_vlublennih'))) {}
                    elseif ($search) {}
                    elseif ($notees) {}
                    else 
                    {
                        if ($orderBy == 'win_date')
                            $aq[] = " g.`good_voting_winner` >= '1'";
                        //else
                            //$aq[] = " (g.`good_status` = 'printed' OR g.`good_status` = 'pretendent')";
                    }
                    
                    if ($search) 
                    {
                        $aq[] = "g.`good_visible` = 'true'";
                        
                        if ($filters['good_status']) {
                            $aq['status'] = "g.`good_status` = '" . $filters['good_status'] . "'";
                        } else {
                            $aq['status'] = "g.`good_status` IN ('printed', 'pretendent')";
                        }
                    }
                    else
                    {
                        // "нам" видны все работы
                        if ($this->user->meta->mjteam == 'super-admin') {
                            if (!$U) {
                                if ($filters['good_status']) {
                                    $aq['status'] = "g.`good_status` = '" . $filters['good_status'] . "'";
                                } else {
                                    if ($notees)
                                        $aq['status'] = "g.`good_status` != 'new' AND g.`good_status` != 'deny'";
                                    else
                                        $aq['status'] = " g.`good_status` IN ('printed', 'pretendent')";
                                    
                                }
                            }
                        }
                        else
                        {
                            // посетитель смотрит основной каталог 
                            if (!$U) {
                                $aq[] = "g.`good_visible` = 'true'";
                                
                                if ($filters['good_status']) {
                                    $aq['status'] = "g.`good_status` = '" . $filters['good_status'] . "'";
                                } else {
                                    if ($notees)
                                        $aq['status'] = "g.`good_status` != 'new' AND g.`good_status` != 'deny'";
                                    else
                                        $aq['status'] = " g.`good_status` IN ('printed', 'pretendent')";
                                }
                                
                            } else {
                                // автор смотри на свою витрину
                                if ($U->id == $this->user->id)
                                {
                                }
                                // посетитель смотри на витрину автора
                                else {
                                    $aq[] = "g.`good_visible` = 'true'";
                                    $aq['status'] = "g.`good_status` != 'deny'";
                                }
                            }
                        }
                    }
                    
                    /* с целью ускорения пока выключено
                    if ($filters['category'] && styleCategory::$BASECATS[$filters['category']]) 
                    {
                     * 
                            " . ($filters['category'] ? ", IF (gps.`pic_id` > 0, 1, 0) AS src_enabled" : '') . "
                        $at[] = "`good_pictures` gps";
                        $aq[] = " g.`good_id` = gps.`good_id` AND gps.`pic_name` = '" . styleCategory::$BASECATS[$filters['category']]['src_name'] . "'"; 
                        
                        if ($this->user->meta->mjteam != 'super-admin') {
                            $aq[] = " gps.`pic_id` > '0'";
                        }
                    }
                    */
                    
                    if (!$user && $stock && styleCategory::$BASECATS[$filters['category']]['sexes']) 
                    {
                        $aq[] = "(g.`good_sex` = '" . $stock['style_sex'] . "' || g.`good_sex_alt` = '" . $stock['style_sex'] . "' || (g.`good_sex` = '' && g.`good_sex_alt` = ''))";
                    }
                    
                    /**
                     * для ноутбуков оставляем только работы моложе 3 месяцев и те которые продавались за ближайшие 3 месяца
                     */
                    if ($filters['category'] == 'laptops' && !array_search('selected', $this->page->reqUrl)) 
                    {
                        $aq['laptops'] = "(g.`good_date` >= NOW() - INTERVAL 3 MONTH OR g.`good_id` IN (115408, 124668, 90985, 115815, 130368, 139380, 125613, 120662, 130566, 135982, 84349, 64618, 97832, 104835, 125789, 67508, 134807, 130933, 110917, 103231, 81333, 69325, 134426, 104737, 120577, 116288, 123077, 63204, 63629, 41603, 119834, 100445, 125079, 135970, 104737, 138066, 136638, 72696, 133535, 92556, 124668, 112442, 129963, 61777, 119373, 133706, 25628, 130009, 131078, 48476, 103124, 127588, 136492, 104093, 132370, 133649, 114329, 123174, 122777, 55702, 132871, 15506, 69499, 85826, 55702, 119373, 121729, 125643, 81333, 69499, 132225, 114234))";
                    }
                    
                    /**
                     * Для чехлов, кружек, подушек и постеров убираем работы которы больше года и у которых меньше 10 лайков
                     */
                     
                    if ((in_array($filters['category'], ['cases','cup','pillows']) || ($filters['category'] == 'poster' && $Style)) && !array_search('selected', $this->page->reqUrl)) {
                        $aq['cases'] = "(g.`good_date` >= NOW() - INTERVAL 1 YEAR AND g.`good_likes` >= 10)";
                    }
            
                    $q = "SELECT
                          SQL_CALC_FOUND_ROWS
                            u.`user_id`,
                            u.`user_login`,
                            u.`user_designer_level`,
                            g.`good_id`,
                            g.`good_name`,
                            g.`good_discount`,
                            g.`visits` + g.`visits_catalog` AS visits_catalog,
                            g.`good_status`,
                            g.`good_visible`,
                            g.`ps_src`,
                            g.`good_date`,
                            g.`good_modif_date`,
                            g.`good_voting_end`,
                            g.`good_likes` AS likes,
                            g.`good_sex`,
                            g.`good_sex_alt`,
                            g.`good_voting_winner` AS place,
                            p.`picture_path`,
                            IF (gp.`pic_id` < 0, '1', '0') AS hidden,
                            IF (g.`good_status` IN ('printed', 'pretendent'), 1, 0) AS winners,
                            gp.`pic_w`,
                            gp.`pic_h`,
                            city.`name` AS city_name
                          FROM
                            `users` AS u
                                LEFT JOIN `city` AS city ON u.`user_city` = city.`id`,
                            `good_pictures` gp,
                            `goods` AS g
                                " . ($orderBy == 'popular_by_category' ? " LEFT JOIN `good__selling_bycategory` o ON o.`category_id` = '" . styleCategory::$BASECATS[$filters['category']]['id'] . "' AND g.`good_id` = o.`good_id`" : '') . "
                                " . ($this->user->meta->mjteam == 'super-admin' ? "  " : '') . "
                                ,
                            `pictures` AS p
                            " . (($at) ? ', ' . implode(', ', $at) : '') . "
                          WHERE 
                                g.`user_id`        = u.`user_id`
                            AND g.`good_status`    NOT IN ('customize')
                            AND g.`good_domain`    IN ('mj', 'all')
                            AND u.`user_status`    = 'active'
                            AND g.`good_id`        = gp.`good_id`
                            " 
                            . 
                            ((($this->user->authorized && $U->id == $this->user->id) || $this->user->meta->mjteam == 'super-admin') ? " AND ABS(gp.`pic_id`) = p.`picture_id`" : "AND gp.`pic_id` = p.`picture_id`")
                            . 
                            ($aq ? ' AND ' . implode(' AND ', $aq) : '')
                            .
                            " GROUP BY g.`good_id`
                         " . ($orders ? "ORDER BY " . implode(', ', $orders) : '') . "
                         LIMIT " . (($page - 1) * $onPage) . ", $onPage";
                    
                    //printr($q, 1);
                
                    $sth = App::db()->query($q);
                    
                    $count = $sth->rowCount();
                    $goods = $sth->fetchAll();
                    
                    $foo = App::db()->query("SELECT FOUND_ROWS() AS s")->fetch();
                    $total = $foo['s'];
                    
                    if ($count == 0 && $page == 1)
                    {
                        $this->page->noindex = TRUE;
                    }
                    
                    // если это поиск
                    if ($search)
                    {
                        // фиксируем сколько было найдено работ
                        $sth = App::db()->prepare("UPDATE `search` SET `founded` = :count WHERE `id` = :id LIMIT 1");
                        $sth->execute(array(
                            'count' => $count, 
                            'id' => $phrase['id'],
                        ));
                    }
            
                    /**
                     * формируем базовую ссылку для кнопок преключения вида
                     */
                    $link = $this->page->reqUrl;
            
                    foreach ($link AS $k => $l)
                    {
                        if ($l == 'page') {
                            unset($link[$k]);
                            unset($link[$k + 1]);
                        }
                        
                        if ($tag['slug'] == $link[1] && $link[0] == 'tag' && $collection) {
                            unset($link[0]);
                        }
            
                        if ($l == 'ajax') {
                            unset($link[$k]);
                        }
            
                        if ($l == 'search') {
                            $link[$k + 1] = urldecode($_GET['q'] ? $_GET['q'] : $link[$k + 1]);
                        }
                    }
                    
                    /**
                     * формируем базовую ссылку для кнопок сортировки
                     */
                    $blink = $link;
                    
                    foreach ($blink as $k => $l) {
                        if ($l == $orderBy) {
                            unset($blink[$k]);
                        }
                    }
                    
                    if ($this->page->isAjax && ($filters['good_status'] || $page > 1)) {
                        $collumnsname = $_SESSION['collumnsname'];
                    } else {
                        $_SESSION['collumnsname'] = $collumnsname = implode('', $this->page->reqUrl) . 'collumns2';
                    }
                    
                    // урл отчищенный от номера страницы
                    $this->page->base_catalog_url = preg_replace('/\/page\/[0-9]\//', '/', $this->page->url);
                    $this->page->base_catalog_url = preg_replace('/\/ajax\//', '/', $this->page->base_catalog_url);
                    
                    // если это не обычная загрузка страницы (не пролистывание)
                    // или это первая страница или номер открываемой страницы = номеру предудущей страницы (смента сортировки в пределах одной страницы)
                    // то сбрасываем координаты листалки в ноль
                    if (!$this->page->isAjax || (!$filters['good_status'] && ($page == 1 || $_SESSION['current_page_' . $this->page->base_catalog_url] == $page))) {
                        unset($_SESSION[$collumnsname]);
                    }
                    
                    // запоминаем номер последней открытой страницы
                    $_SESSION['current_page_' . $this->page->base_catalog_url] = $page;
                    
                    $columns    = 3;   
                    $x_offset   = 2;
                    $y_offset   = 0;
                    $x_paddings = 24;
                    $y_paddings = 100;
            
                    foreach($goods AS $k => &$g)
                    {
                        $goods[$k]['good_name']  = stripslashes($g['good_name']);
                        $goods[$k]['user_login'] = str_replace ('.livejournal.com', '', $g['user_login']);
                        $goods[$k]['user_designer_level'] = designerLevel2Picture($g['user_designer_level']);
                        
                        if ($g['good_status'] == 'new' || $g['good_visible'] == 'modify')
                        {
                            $dd = getDateDiff(($g['good_modif_date'] != '0000-00-00 00:00:00') ? $g['good_modif_date'] : $g['good_date']);
                            $g['timetoend'] = ($dd > 24) ? 0 : 24 - $dd;
                        }
            
                        if ($filters['category'])
                        {
                            $g['link'] = '/catalog/' . $g['user_login'] . '/' . $g['good_id'] . '/' . ($stock['style_slug'] ? $stock['style_slug'] : $Style->style_slug) . ($filters['size'] && $filters['sex'] != 'kids' ? '/' . $CATALOG_SIZES[$filters['size']] : '') . '/';
                        }
            
                        $g['zoomlink'] = $g['link'] . 'zoom/';
                
                        $d = ($g['good_discount'] > 0) ? max($stock['dp'], $stock['d']) : $stock['d'];
                        
                        //$goods[$k]['price_old']  = round($stock['p'] * (1 - ($stock['d']) / 100));
                        $goods[$k]['price_old']  = round($stock['p'] / $this->VARS['usdRate']);
                        $goods[$k]['price']      = round(round($stock['p'] * (1 - ($d) / 100)) / $this->VARS['usdRate']);
                        
                        $g['disabled'] = ($g['good_visible'] == 'false') ? TRUE : '';
                        
                        if ($g['good_visible'] == 'true')
                        {}
                        elseif ($g['good_visible'] == 'false' && in_array($this->user->id, array($g['user_id'], 27278, 6199)))
                        {}
                        else {
                            if (!$U)
                                unset($goods[$k]);
                        }
                        
                        if ($this->user->id == $g['user_id'] && ($g['good_status'] == 'deny' || $g['good_status'] == 'new'))
                        {
                            $g['link'] = '/senddrawing.design/' . $g['good_id'] . '/';
                        }
            
                        if ($likes[$g['good_id']])
                            $g['liked'] = TRUE;
                        
                        // расчитываем координаты
                        if (empty($g['pic_w']) || empty($g['pic_h']))
                            list($iw, $ih) = getimagesize(ROOTDIR . $g['picture_path']);
                        else {
                            $iw = $g['pic_w'];
                            $ih = $g['pic_h'];
                        }
                        
                        $i = $k % $columns;
                        $g['i'] = $i;
                        $g['h'] = ($ih + $y_paddings) + $y_offset;
                        $g['x'] = ($i * $iw) + ($i * $x_offset) + ($i * $x_paddings);
                        $g['y'] = (int) $_SESSION[$collumnsname][$i];
            
                        $_SESSION[$collumnsname][$i] += $g['h'] + 24;
                    }
            
                    $this->view->setVar('ULheight', max($_SESSION[$collumnsname]));
                    
                    $this->view->setVar('goods', $goods);
            
                    if ($page == 1)
                    {
                        $this->view->setVar('canindex', TRUE);
                    }
            
                    if ($_COOKIE['catalog_page'][$this->page->url_encoded] && $page == 1)
                    {
                        $count = $onPage = $baseOnPage;
                        //$page = intval($_COOKIE['catalog_page'][$this->page->url_encoded]);
                    }
            
                    if ($total > $onPage)
                        $rest = intval($total) - (($page) * $onPage);
                    else
                        $rest = 0;
            
                    if (!$U) {   
                        if (!$search && !$winners) {
                            //$rest = min($baseOnPage, $rest);
                        }
                    } else
                        $rest = 0;
                    
                    /**
                     * Если кончились работ в основном каталоге
                     * то смотрим сколько работ ещё есть в архиве
                     */
                    if ($rest <= $baseOnPage && !$filters['good_status'])
                    {
                        $aq['status'] = "g.`good_status` = 'archived'";
                        
                        if (!$aq['pic']) {
                            $aq['pic'] = "gp.`pic_name`  = 'good_preview'";
                        }
                        
                        $sth = App::db()->query("SELECT COUNT(DISTINCT(g.`good_id`)) AS c
                                    FROM
                                        `users` AS u,
                                        `good_pictures` gp,
                                        `goods` AS g,
                                        `pictures` AS p
                                        " . (($at) ? ', ' . implode(', ', $at) : '') . "
                                    WHERE 
                                            g.`user_id`     = u.`user_id`
                                        AND u.`user_status` = 'active'
                                        AND g.`good_id`     = gp.`good_id`
                                        AND gp.`pic_id`     = p.`picture_id`"
                                        . 
                                        (($aq) ? ' AND ' . implode(' AND ', $aq) : ''));
                        
                        $arch = $sth->fetch();
                         
                        if ($arch['c'] > 0)
                        {
                            $this->view->setVar('rest_archived', $arch['c']);
                        }
                    }
                    
                    if ($filters['good_status'])
                        $this->view->setVar('rest_' . $filters['good_status'], $rest);
                    else
                        $this->view->setVar('rest', $rest);
                    
                    
                    $this->view->setVar('onpage', $baseOnPage);
                    $this->view->setVar('page', $page);
                    $this->view->setVar('pages', 3);
                    $this->view->setVar('count', $count);
                    $this->view->setVar('filters', $filters);
                    $this->view->setVar('category', $filters['category']);
                    $this->view->setVar('list_tpl', $list_tpl);
                    $this->view->setVar('Style', $Style);
                    $this->view->setVar('Style_category_title', styleCategory::$BASECATS[$Style->category]['title_single']);
                    
                    if ($filters['good_status'])
                        $this->view->setVar('good_status_filter_link', str_replace($filters['good_status'] . '/', '', $this->page->url));
                    
                    $this->view->setVar('total', $total); 
            
                    $this->view->setVar('showed', $page * $onPage);
                    $this->view->setVar('link', '/' . implode('/', $link));
            
                    $this->view->setVar('mlink', '/' . implode('/', $link) . '/');
                    $this->view->setVar('blink', '/' . implode('/', $blink) . '/');
            
                    if (in_array(end($this->page->reqUrl), array('new', 'popular', 'grade', '', 'top2013', 'top2014', 'top2015', 'top2016', 'visits', 'popular_by_category', 'likes', 'avg_grade', 'avg_grade_designers', 'rating', 'recently_buy', 'recently_view')))
                        $base_link = array_slice($this->page->reqUrl, 0, count($this->page->reqUrl) - 1);
                    else
                        $base_link = $this->page->reqUrl;
                    
                    $this->view->setVar('base_link', '/' . implode('/', $base_link));
            
                    if ($task == 'top')
                    {
                        if ($filters['category'])
                            $this->page->title[] = 'Super stars!';
                        else {
                            $this->page->title[0] = 'Super stars!';
                        }
                    }
            
                    if ($this->page->isAjax || $ajax)
                    {
                        // для подстановки в альты картинок вытаскиваем уникальные заголовки страницы
                        $this->view->setVar('PAGE', $Page);
                        
                        if (count($this->page->title) > 0)
                            $this->page->title = htmlspecialchars(implode(', ', $this->page->title));
                        
                        $this->view->generate('catalog/' . $list_tpl);
            
                        exit();
                    }
            
                    // =====================================================================================================================
                    // Доступные для продажи гаджеты
                    // =====================================================================================================================
                    if (!$GADGETS = App::memcache()->get('GADGETS'))
                    {
                        $models = $GADGETS = array();
                    
                        // группируем по категориям и производителям
                        foreach (style::findAll(array('onstock' => 1, 'cat' => 'gadgets', 'exclude_style' => array(330))) as $k => $sp)
                        {
                            $sp['old_price'] = $sp['price'];
                            $sp['price'] = round($sp['price'] * (1 - $sp['discount'] / 100));
                            
                            if (styleCategory::$BASECATSid[$sp['cat_parent']] && !in_array(styleCategory::$BASECATSid[$sp['cat_parent']], array('auto', 'poster')))
                            {
                                $sp['cat'] = styleCategory::$BASECATSid[$sp['cat_parent']];
                    
                                $models[styleCategory::$BASECATSid[$sp['cat_parent']]][$sp['cat_slug']][$sp['style_id']] = $sp;
                                
                                $models[styleCategory::$BASECATSid[$sp['cat_parent']]][$sp['cat_slug']]['title'] = $sp['category_name'];
                                
                                if (styleCategory::$BASECATSid[$sp['cat_parent']] == 'laptops')
                                    $models[styleCategory::$BASECATSid[$sp['cat_parent']]][$sp['cat_slug']]['style_order'][$sp['style_id']] = $sp['style_order'];
                                else
                                    $models[styleCategory::$BASECATSid[$sp['cat_parent']]][$sp['cat_slug']]['style_order'][$sp['style_id']] = $sp['style_name'];
                            }
                        }
                        
                        $models['laptops'] = array('macbook' => (array) $models['laptops']['macbook'], 'pc' => (array) $models['laptops']['pc'], 'netbook' => (array) $models['laptops']['netbook']);   
                        
                        //printr($models['phones']);
                        
                        // сортируем по категориям носителей
                        foreach (styleCategory::$BASECATS as $c => $cat)
                        {
                            if ($models[$c])
                            {
                                // сортируем внутри производителя по моделям
                                foreach ($models[$c] as $k => $subcat)
                                {
                                    arsort($subcat['style_order']);
                    
                                    foreach ($subcat['style_order'] as $sid => $o)
                                    {
                                        $subcat['style_order'][$sid] = $subcat[$sid];
                                    }
                    
                                    $GADGETS[$c][$k] = $subcat['style_order'];
                                    $GADGETS[$c][$k]['title'] = $subcat['title'];
                                }
                    
                                $GADGETS[$c]['title'] = styleCategory::$BASECATS[$c]['title'];
                            }
                        }
                    
                        App::memcache()->set('GADGETS', $GADGETS, false, 7 * 24 * 3600);
                    }
                    
                    $this->view->setVar('GADGETS', $GADGETS);
            
                    /**
                     * Если открыт раздел телефонов
                     * то показываем скипок брендов и моделей
                     * урл /catalog/phones/
                     */
                    if ($page == 1 && $this->page->reqUrl[0] == 'catalog' && in_array($filters['category'], array('phones', 'cases', 'laptops', 'touchpads')))
                    {
                        if ($Style->id == styleCategory::$BASECATS['phones']['def_style']) 
                        {
                            $this->page->import(array(
                                '/js/p/catalog.modelsmenu.js',
                            ));
                            
                            $this->view->setVar('modelslist', 'phones');
                            
                            $models = $GADGETS['phones'];
                            
                            $models['apple'] = array(
                                'title' => 'Apple',
                                810 => $GADGETS['phones']['apple'][810],
                                633 => $GADGETS['phones']['apple'][633],
                                630 => $GADGETS['phones']['apple'][630], 
                                554 => $GADGETS['phones']['apple'][554], 
                                553 => $GADGETS['phones']['apple'][553], 
                                333 => $GADGETS['phones']['apple'][333], 
                                224 => $GADGETS['phones']['apple'][224], 
                                222 => $GADGETS['phones']['apple'][222], 
                            );
                        }
                        elseif ($Style->id == styleCategory::$BASECATS['laptops']['def_style']) 
                        {
                            $this->page->import(array(
                                '/js/p/catalog.modelsmenu.js',
                            ));
                            
                            $this->view->setVar('modelslist', 'laptops');
                            
                            $models = $GADGETS['laptops'];
                        }
                        elseif ($Style->id == styleCategory::$BASECATS['touchpads']['def_style']) 
                        {
                            $this->page->import(array(
                                '/js/p/catalog.modelsmenu.js',
                            ));
                            
                            $this->view->setVar('modelslist', 'touchpads');
                            
                            $models = $GADGETS['touchpads'];
                        }
                        
                        $this->view->setVar('MODELS', $models);
                    }
            
                    /**
                     * коллекции тегов
                     * для кружек и наклеек на доски отдельный список
                     */
                    if (!$toptags = App::memcache()->get('CATALOG_TOP_TAGS' . ($Style->category == 'cup' || $Style->category == 'boards' ? '_' . $Style->category : '')))
                    {
                        $sth = App::db()->query("SELECT t.`tag_id`, t.`name`, t.`slug`, p.`picture_path`, COUNT(DISTINCT(g.`good_id`)) as `count`
                                  FROM `tags` AS t, `tags_relationships` tr, `goods` g, `pictures` p "  . ($Style->category == 'cup' || $Style->category == 'boards' ? ', `good_pictures` gp' : '') . "
                                  WHERE
                                        tr.`tag_id` = t.`tag_id`
                                    AND tr.`object_id` = g.`good_id`
                                    AND tr.`object_type` = '1'
                                    AND g.`good_status` IN ('printed', 'pretendent')
                                    AND g.`good_visible` = 'true'
                                    AND t.`pic_id` = p.`picture_id`
                                    AND t.`show_in_collections` = '1'
                                    " . ($Style->category == 'cup' || $Style->category == 'boards' ? " AND t.`show_in_collections` = '1' AND g.`good_id` = gp.`good_id` AND gp.`pic_name` = 'catalog_preview_" . styleCategory::$BASECATS[$Style->category]['def_style'] . "' AND gp.`pic_id` > '0'" : '') . "
                                  GROUP BY t.`tag_id`
                                  HAVING count > " . ($Style->category == 'cup' || $Style->category == 'boards' ? 9 : 2) . "
                                  ORDER BY t.`name`");
        
                        $toptags = $sth->fetchAll();
                        
                        App::memcache()->set('CATALOG_TOP_TAGS' . ($Style->category == 'cup' || $Style->category == 'boards' ? '_' . $Style->category : ''), $toptags, false, 2 * 86400);
                    }
            
                    foreach ($toptags AS $k => $tt) {
                        if (in_array($tt['slug'], array('detskie', '8marta')))
                            unset($toptags[$k]);
                            
                        /*
                        if ($tt['slug'] == '14february' || $tt['slug'] == '23february') {
                            $ng = $toptags[$k];
                            unset($toptags[$k]);
                            array_unshift($toptags, $ng);
                        }
                        */
                    }
            
                    $this->view->setVar('TAGS', $toptags);
                    
                    /**
                     * На странице постеров фильтр по типам и размерам постеров
                     */
                    if ($filters['category'] == 'poster') 
                    {
                        $styles = style::findAll(array(
                            'onstock' => true,
                            'category' => styleCategory::$BASECATS['poster']['id'],
                            'orderby' => "sc.`id`, sz.`order`",
                        ));
                        
                        $size_filters = array(
                            'white' => array(), 
                            'black' => array(), 
                            'wood' => array('vertical' => array(), 'horizontal' => array(), 'square' => array()), 
                            //'holst' => array(), 
                            'paper' => array(),
                            //'white-heavy-frame' => array(),
                            //'black-heavy-frame' => array(),
                        );
                        
                        // группируем по ориентации и по цвету
                        foreach ($styles as $sid => $s) 
                        {
                            if (strpos($s['style_slug'], 'horizontal') !== false)
                                $orientation = 'horizontal';
                            elseif (strpos($s['style_slug'], 'vertical') !== false)
                                $orientation = 'vertical';
                            elseif (strpos($s['style_slug'], 'square') !== false) 
                                $orientation = 'square';
                            else
                                continue;
                            
                            if ($s['style_category'] == 48)
                                $group = 'white';
                            elseif ($s['style_category'] == 110)
                                $group = 'black';
                            if ($s['style_category'] == 111)
                                $group = 'white-heavy-frame';
                            elseif ($s['style_category'] == 112)
                                $group = 'black-heavy-frame';
                            elseif ($s['style_category'] == 109)
                                $group = 'wood';
                            elseif ($s['style_category'] == 67)
                                $group = 'paper';
                            //else
                                //$group = 'holst';
                            
                            if ($s['style_id'] == $Style->id) {
                                $size_filters_active = $group;
                            }
                            
                            $size_filters[$group][$orientation][] = $s;
                        }
                        
                        $this->view->setVar('size_filters', $size_filters);
                        $this->view->setVar('size_filters_active', $size_filters_active);
                    }
                    
                    break;
            
                /**
                 * Просмотр работы
                 * (выбор носителя цвета, размера и добавление в корзину)
                 */
                case 'good':
            
                    $PD = array();
            
                    $this->page->tpl = 'catalog/good.tpl';
            
                    $this->page->import(array(
                        '/css/catalog/good.tpl.css',
                        '/css/glissegallery.css',
                        '/js/glissegallery.js',
                        '/js/2012/goods_slider.js',
                        '/js/cloud-zoom.1.0.2_v3.js',
                    ));
                    
                    if (isset($_COOKIE['temporaryNoticeVisible']))
                    {
                        if ($_COOKIE['temporaryNoticeVisible'] != 'false' && $_COOKIE['temporaryNoticeVisible'] >= 10) {
                            $this->view->setVar('temporaryNotice', true);
                        } else {
                            if ($_COOKIE['temporaryNoticeVisible'] != 'false' && $_COOKIE['temporaryNoticeVisible'] <= 10) {
                                setcookie('temporaryNoticeVisible', ($_COOKIE['temporaryNoticeVisible'] + 1), (time() +  + 2592000), '/', '.maryjane.ru');
                            }
                        }
                    } else {
                        setcookie('temporaryNoticeVisible', 1, (time() +  + 2592000), '/', '.maryjane.ru');
                    }
            
                    if (!empty($good_id))
                    {
                        try
                        {
                            $good = new good($good_id);
                        }
                        catch (Exception $e)
                        {
                            $this->page404();
                        }
                    
                    
                        /**
                         * Для работ у которых был запрос от админа на продажу его работы
                         * показываем специальную страничку-заглушку
                         */
                        if ($good->meta->trash == 'request' && ($this->user->id == $good->user_id || $this->user->meta->mjteam == 'super-admin'))
                        {
                            $this->page->tpl = 'catalog/trashRequest.tpl';
                            $this->page->title = 'Начните продавать Ваши работы';
                            
                            $this->view->setVar('g', $good);
                            $this->view->setVar('code', sha1($good->good_id . '-' . $good->user_id));
                            $this->view->setVar('promoUrl', $good->user->getPromoUrl('/catalog/' . $good->user->user_login . '/' . $good->id . '/'));
                            $this->view->setVar('PAGE', $Page);
                            $this->view->generate('index.tpl');
                            
                            exit();
                        }
                        
                        
                        // работа выключена
                        if (($good->good_visible == 'false' && $good->good_status != 'new') || $good->good_status == 'deny' || ($good->good_status == 'customize' && !$sale))
                        {
                            $this->page404();
                        }
                        
                        // работа на редактировании
                        if ($good->good_visible == 'modify' && !in_array($this->user->id, array($good->user_id, 6199, 27278)))
                        {
                            $this->page404();
                        }
                        
                        // несоответствие указанного автора и реального автора работы
                        if (!$sale && $good->user_id != $U->id)
                        {
                            $this->page->go('/' . $this->page->module . '/' . $good->user_login . '/' . $good->id . '/');
                        }
                        
                        // если это не распродажа и нет ни одного базового исходника или основного превью или нет вообще ни одной картинки
                        if (count($good->pics) == 0 || (!$sale && count(array_intersect(array_keys($good->pics), array_keys(good::$srcs))) == 0) || (!$sale && !$good->pics['good_preview']))
                        {
                            $this->page404();
                        }
                        
                        if (!$good->pics['ps_740x']) {
                            $good->pics['ps_740x'] = $good->pics['poster_big'];
                        }
                        
                        // аватарка автора работы
                        $good->user_avatar = user::userAvatar($good->user_id);
            
                        if (!in_array('zoom', $this->page->reqUrl)) 
                        {
                            // автор смотри на свою работу
                            // промоссылка
                            if ($good->user_id == $this->user->id)
                            {
                                $promo = $this->user->getPromoUrl($this->page->url);
                                $this->view->setVar('promoUrl', $promo['link']);
                                
                                if ($good->good_status != 'modify') {
                                    if (generateTurn::find($good->id) > 0) {
                                        $this->view->setVar('picsInTurn', TRUE);   
                                    }
                                }
                            }
                        }
                        
                        // запоминаем просмотр работы в сессию
                        if (!$_SESSION['good_visits']) {
                            $_SESSION['good_visits'] = [];
                        }
                        array_unshift($_SESSION['good_visits'], $good->id);
                        $_SESSION['good_visits'] = array_unique($_SESSION['good_visits']);
                        $_SESSION['good_visits'] = array_slice($_SESSION['good_visits'], 0, 20);
            
            
                        $PD[] = $good->good_name;
                        $PD[] = $good->user_login;
                        $PD[] = $good->good_description;
                        $PD[] = 'покупайте дизайнерские футболки, толстовки, виниловые наклейки Maryjane.ru';
                        
                        if (!empty($this->page->reqUrl[3]))
                        {
                            try
                            {
                                $Style = style::find($this->page->reqUrl[3]);
                            }
                            catch (Exception $e) 
                            {
                            }
                        }
            
                        if ($Style) 
                        {
                            if ($Style->category == 'cases' || $Style->category == 'bumper') {
                                $Style->category = 'phones'; 
                            }
                            
                            $filters['category'] = $Style->category;
                            
                            if ($Style->cat_parent == 1) 
                            {
                                $filters['sex'] = $Style->style_sex;
                                $filters['color'] = $Style->style_color;
                            }
                            
                            $good->mainPreview = 'http://cache.maryjane.ru/' . $Style->category . '/' . $Style->id . '/' . $good->id . '.' . ($sale ? 'sale' : 'model') . '.';
                            
                            if (styleCategory::$BASECATS[$Style->category]['src_name'] == 'ps_src') {
                                if ($good->pics['ps_src_' . $Style->color_group] && $good->pics['ps_src_' . $Style->color_group]['update_timestamp'] != '0000-00-00 00:00:00') {
                                    $good->mainPreview .= $good->pics['ps_src_' . $Style->color_group]['update_timestamp'];
                                } else {
                                    $good->mainPreview .= $good->pics['ps_src']['update_timestamp'];
                                }
                            } else {
                                $good->mainPreview .= $good->pics[styleCategory::$BASECATS[$Style->category]['src_name']]['update_timestamp'];
                                
                                // для кружек добавляем дефолтную сторону изделия
                                if ($Style->category == 'cup') {
                                    if ($good->meta->{'cup_default_side_' . $Style->id}) {
                                    } else {
                                        $good->mainPreview .= '.' . styleCategory::$BASECATS['cup']['def_side'];
                                    }
                                    
                                    $good->mainPreview .= '.front';
                                }
                            }
                            
                            $good->mainPreview .= '.jpeg';  
                        }
                        
                        $this->page->keywords = '';
                        
                        if (count($good->tags) > 0)
                        {
                            foreach ($good->tags as $tag) {
                                $this->page->keywords .= ', ' . $tag['name'];
                                $PD[] = $tag['name'];
                            }
                            
                            $this->page->keywords = trim($this->page->keywords, ', ');
                        }
                        
                        if ($good->competition_id > 0 && $good->place > 0)
                        {
                            $sth = App::db()->prepare("SELECT c.`competition_name`, c.`competition_slug` FROM `competition_winners` cw, `competitions` c WHERE cw.`good_id` = :gid and c.`competition_id` = cw.`competition_id`");
                            
                            $sth->execute(array(
                                'gid' => $good->id,
                            ));
                            
                            if ($comp = $sth->fetch()) {
                                $comp['competition_name'] = stripslashes($comp['competition_name']);
                                $good->competition_winner = $comp['competition_name'];
                                $good->competition_slug = $comp['competition_slug'];
                            }
                        }
                        
                        $good->good_name_escaped = addslashes($good->good_name);
                        
                        $this->view->setVar('good', $good->info);
                        $this->view->setVar('tags', $good->tags);
                        $this->view->setVar('style', $Style);
                        
                        $this->page->canonical = '/catalog/' . $good->user_login . '/' . $good->id . '/';
                        $this->page->ogUrl = '/catalog/' . $good->user_login . '/' . $good->id . '/';
                         
                        if (count($filters) > 0)
                        {
                            $default = $filters;
                            
                            if ($default['category'])
                            {
                                if (styleCategory::$BASECATS[$default['category']]['parent'])
                                {
                                    $default['category'] = styleCategory::$BASECATS[$default['category']]['parent'];
                                }
                            }
                        }
                        
                        //printr($filters);
                        //printr($default);
            
                        // распродажа
                        if ($sale)
                        {
                            $q = "SELECT
                                    s.`style_id`,
                                    s.`style_name`,
                                    s.`style_description`,
                                    s.`style_sex`,
                                    s.`style_slug`,
                                    s.`style_viewsize` AS faq_id,
                                    s.`style_composition`,
                                    s.`style_order`,
                                    sz.`size_id`,
                                    sz.`size_name`,
                                    sz.`size_meta`,
                                    sz.`order`,
                                    gs.`size_rus`,
                                    gs.`good_stock_id`,
                                    gs.`good_stock_price`,
                                    gs.`good_stock_quantity`,
                                    gs.`good_stock_discount`,
                                    gs.`good_stock_status`,
                                    s.`style_color` AS color_id,
                                    c.`name` AS color_name,
                                    c.`hex`,
                                    c.`group`,
                                    sc.`id` AS category, 
                                    sc.`name` AS category_name,
                                    sc.`cat_slug` AS category_slug,
                                    sc.`cat_parent`
                                FROM
                                    `good_stock` AS gs,
                                    `good_stock_colors` AS c,
                                    `styles` AS s,
                                    `sizes` AS sz,
                                    `styles_category` AS sc
                                    " . (($at) ? ', ' . implode(', ', $at) : '') . "
                                WHERE
                                        gs.`good_id`                   = '" . $good->id . "'
                                    AND gs.`good_id`        > '0'
                                    AND gs.`good_stock_visible`        = '1' 
                                    AND gs.`style_id`                  = s.`style_id`
                                    AND gs.`size_id`                   = sz.`size_id`
                                    AND s.`style_color`                = c.`id`
                                    AND s.`style_category`             = sc.`id`
                                    AND (gs.`good_stock_quantity` +- gs.`good_stock_inprogress_quantity`) > '0'
                                    " . (($aq) ? ' AND ' . implode(' AND ', $aq) : '') . "
                                ORDER BY gs.`good_stock_id` DESC";
                        }
                        // принтшоп
                        // СПИСОК ВСЕХ НОСИТЕЛЕЙ ВО ВСЕХ ЦВЕТАХ
                        else
                        {
                            if ($Style) {
                                if ($Style->cat_parent == 1)
                                    $aq[] = "sc.`cat_parent` = '1'";
                                else
                                    if (in_array('zoom', $this->page->reqUrl))
                                        $aq[] = "s.`style_category` = '" . $Style->style_category . "'";
                            }
                            
                            $q = "SELECT
                                    s.`style_id`,
                                    s.`style_name`,
                                    s.`style_slug`,
                                    s.`style_sex`,
                                    s.`style_viewsize` AS faq_id,
                                    s.`style_composition`,
                                    s.`style_order`,
                                    sz.`size_id`,
                                    sz.`size_name`,
                                    sz.`size_meta`,
                                    sz.`order`,
                                    gs.`good_stock_id`,
                                    gs.`good_stock_price`, 
                                    gs.`good_stock_quantity`,
                                    gs.`good_stock_discount`,
                                    gs.`good_stock_discount_promo`,
                                    gs.`good_stock_status`,
                                    s.`style_color` AS color_id,
                                    gs.`good_id`,
                                    c.`name` AS color_name,
                                    c.`hex`,
                                    c.`group`,
                                    gs.`size_rus`,
                                    sc.`id` AS category, 
                                    sc.`name` AS category_name,
                                    sc.`cat_slug` AS category_slug,
                                    sc.`cat_parent`
                                FROM
                                    `good_stock` AS gs,
                                    `good_stock_colors` AS c,
                                    `styles` AS s,
                                    `sizes` AS sz,
                                    `styles_category` AS sc
                                    " . (($at) ? ', ' . implode(', ', $at) : '') . "
                                WHERE
                                        gs.`good_id`              = '0'
                                    AND gs.`good_stock_visible`   = '1'
                                    AND gs.`style_id`             NOT IN ('178', '331')
                                    AND gs.`style_id`             = s.`style_id`
                                    AND gs.`size_id`              = sz.`size_id`
                                    AND s.`style_color`           = c.`id`
                                    AND s.`style_category`        = sc.`id`
                                    AND sc.`id` NOT IN ('71', '87')
                                    AND ((gs.`good_stock_quantity` +- gs.`good_stock_inprogress_quantity`) > '0')
                                    " . (($aq) ? ' AND ' . implode(' AND ', $aq) : '') . "
                                ORDER BY s.`style_order`";
                        }
            
                        //  OR gs.`good_stock_status` = 'preorder'
                        //printr($q, 1);
                        
                        $sth = App::db()->query($q);
            
                        $i = 0;
            
                        if ($sth->rowCount() > 0)
                        {
                            $styles = array();
            
                            // Группируем данные из базы в единый массив для отправки в шаблон
                            // с "сортировкой" по полу
                            foreach($sth->fetchAll() AS $sk => $s)
                            {
                                if (!$sale && $good->good_discount > 0)
                                    $s['good_stock_discount'] = max($s['good_stock_discount'], $s['good_stock_discount_promo']);
            
                                $s['price'] = round($s['good_stock_price'] * (1 - $s['good_stock_discount'] / 100)); 
                                    
                                // хинт для гаджетов
                                if ($s['cat_parent'] > 1)
                                {
                                    $s['style_sex']     = (styleCategory::$BASECATSid[$s['cat_parent']] == 'cases') ? 'phones' : styleCategory::$BASECATSid[$s['cat_parent']];
                                    $s['subcategory']   = $s['category_slug'];
                                    $s['category_slug'] = $s['style_id'];
                                }
            
                                // если цвет носителя попадает в группу отключённых
                                if (!$sale && $s['cat_parent'] == 1 && styleCategory::$BASECATS[$s['category_slug']]['src_name'] == 'ps_src' && ((isset($good->pics['ps_src_colors']['ps_src_' . $s['group']]) && $good->pics['ps_src_colors']['ps_src_' . $s['group']]['id'] < 0) || !$good->pics['ps_src']))
                                {
                                    continue;
                                }
                                
                                $styles[$s['style_sex']][$s['category_slug']]['category']          = $s['category_name'];
                                $styles[$s['style_sex']][$s['category_slug']]['subcategory']       = $s['subcategory'];
                                $styles[$s['style_sex']][$s['category_slug']]['style_id']          = $s['style_id'];
                                $styles[$s['style_sex']][$s['category_slug']]['style_name']        = stripslashes($s['style_name']);
                                $styles[$s['style_sex']][$s['category_slug']]['style_slug']        = stripslashes($s['style_slug']);
                                $styles[$s['style_sex']][$s['category_slug']]['style_composition'] = stripslashes($s['style_composition']);
                                $styles[$s['style_sex']][$s['category_slug']]['style_description'] = stripslashes($s['style_description']);
                                $styles[$s['style_sex']][$s['category_slug']]['style_order']       = $s['style_order'];
                                $styles[$s['style_sex']][$s['category_slug']]['faq_id']            = $s['faq_id'];
                                $styles[$s['style_sex']][$s['category_slug']]['cat_parent']        = $s['cat_parent'];
            
                                $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['gsId'] = $s['good_stock_id'];
                                $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['en']   = stripslashes($s['size_name']);
                                $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['ru']   = stripslashes($s['size_rus']);
                                $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['o']    = $s['order'];
                                $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['size_meta'] = stripslashes($s['size_meta']);
            
                                if ($s['good_stock_quantity'] > 0)
                                {
                                    $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['style_id']   = stripslashes($s['style_id']);
                                    $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['style_name'] = stripslashes($s['style_name']);
                                    $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['id']         = $s['good_stock_id'];
                                    $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['status']     = $s['good_stock_status'];
                                    $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['color_name'] = $s['color_name'];
                                    $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['color_hex']  = $s['hex'];
                                    $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['group']      = $s['group'];
                                    $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['price']      = $s['price'];
                                    
                                    $styles[$s['style_sex']][$s['category_slug']]['colors'][$s['color_id']]['sizes'][$s['size_id']] = $s['size_name']; 
                                    
                                    $preview_path = 'http://cache.maryjane.ru/';
            
                                    // превью 500*512
                                    // гаджеты
                                    if ($s['cat_parent'] > 1) 
                                        $preview_path .= styleCategory::$BASECATSid[$s['cat_parent']] . '/' . $s['style_id'] . '/' . $good->id . '.' . $good->pics[styleCategory::$BASECATS[$s['style_sex']]['src_name']]['update_timestamp'];
                                    // тряпки
                                    else
                                    {
                                        if (styleCategory::$BASECATS[$s['category_slug']]['src_name'] == 'ps_src') 
                                            $src_upd_time = $good->pics['ps_src_' . $s['group']]['update_timestamp'];
                                        else
                                            $src_upd_time = $good->pics[styleCategory::$BASECATS[$s['category_slug']]['src_name']]['update_timestamp'];
                                            
                                        $preview_path .= $s['category_slug'] . '/' . $s['style_id'] . '/' . $good->id . ($sale ? '.sale.' : '.model.') . $src_upd_time;
                                    }
                                                
                                    $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['preview'] = $preview_path . '.jpeg';
            
                                    if ($sale && $s['category_slug'] == 'futbolki')
                                        $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['price_old']  = 1090;
                                    elseif ($sale && $s['category_slug'] == 'tolstovki')
                                        $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['price_old']  = 1990;
                                    else
                                        $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['price_old']  = $s['good_stock_price'];
                                    
                                    $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['price_back'] = round(($styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['price']) / 100 * $this->user->buyerLevel2discount());
            
                                    // дефолтный цвет
                                    if (!$Style && ((!$default && $s['color_id'] == $good->ps_onmain_id && $s['cat_parent'] == 1 && $s['style_sex'] != 'kids') || ($default && !$default['style_id'] && $default['category'] == $s['category_slug'] && $default['sex'] == $s['style_sex'] && $default['color'] == $s['color_id'])))
                                    {
                                        $default['sex']      = $s['style_sex'];
                                        $default['category'] = $s['category_slug'];
                                        $default['color']    = $s['color_id'];
                                        $default['size']     = $s['size_id'];
                                        $default['bg']       = $s['hex'];
                                        $default['style_id'] = $s['style_id'];
                                        
                                        $default_price       = $s['price'];
                                    }
                                    
                                    if ($Style && !$filters['size'] && $Style->id == $s['style_id'] && !$default['size']) {
                                        $default['size']     = $s['size_id'];
                                    }
                                    
                                    // вариант входа по старым ссылкам
                                    if ($s['category_slug'] == $filters['category'] && $s['style_sex'] == $filters['sex'] && $s['color_id'] == $filters['color'] && $s['size_id'] == $filters['size']) 
                                    {
                                        $default_price = $s['price'];
                                        $default_stockid = $s['good_stock_id'];
                                    }
                                    
                                    // вариант входа по новым ссылкам
                                    if ($s['style_slug'] == $Style->style_slug)
                                    {
                                        $default_price = $s['price'];
                                        $default_stockid = $s['good_stock_id'];
                                    }
                                }
                            }
            
                            //printr($good->ps_onmain_id);
                            //printr($disabled);
                            //printr($styles);
                            //printr($good->ps_onmain_id);
                            //printr($first);
                            //printr($default);
                            //printr($default_price);
            
                            if (count($disabled) == 4)
                                $good->pics['ps_src']['id'] = $good->pics['ps_src']['id'] * -1; 
                            
            
                            if (!$good->pics['ps_src'] && !$good->pics['patterns']) {
                                unset($styles['female']);
                                unset($styles['male']);
                                unset($styles['kids']);
                            }
            
                            // поста-обработка массива
                            foreach (styleCategory::$BASECATS as $bk => $bv)
                            {
                                if (!$good->pics[$bv['src_name']] || $good->pics[$bv['src_name']]['id'] < 0 || !$styles[$bk]) {
                                    unset($styles[$bk]);
                                    continue;
                                }
                                
                                switch ($bk)
                                {
                                    // в массиве оставляем только дефолтный телефон + добавляем хардкейс
                                    case 'phones':
            
                                        // группируем по производителю
                                        foreach ($styles[$bk] as $k => $sp) 
                                        {
                                            $sp['style_name']      = trim(str_ireplace($sp['category'], '', $sp['style_name']));
                                            $sp['phones_category'] = styleCategory::$BASECATSid[$sp['cat_parent']];
                                            
                                            if ($sp['phones_category'] == 'cases') {
                                                $cases[$sp['category']][$sp['style_id']] = $sp;
                                            } else {
                                                $phones[$sp['category']][$sp['style_id']] = $sp;
                                                $phones[$sp['category']]['style_order'][$sp['style_id']] = $sp['style_order'];
                                            }
                                        }
                                        
                                        // сортируем внутри производителя по моделям
                                        foreach ($phones as $k => $cat) 
                                        {
                                            arsort($cat['style_order']);
                                            
                                            foreach ($cat['style_order'] as $sid => $o)
                                            {
                                                $cat['style_order'][$sid] = $cat[$sid];
                                            }
                                            
                                            
                                            $phones[$k] = $cat['style_order'];
                                        }
            
                                        // эплы и айфон и самсунги в самый верх
                                        $styles[$bk] = (array) $cases['Apple'] + (array) $cases['Samsung'] + (array) $phones['Apple'] + (array) $phones['Samsung'];
                                    
                                        unset($phones['Apple']);
                                        unset($phones['Samsung']);
                                    
                                        foreach ($phones as $k => $v) {
                                            $styles[$bk] += $v;
                                        }
                                        
                                        unset($phones);
                                        unset($cases);
            
                                    break;
            
                                    // в массиве оставляем только дефолтный ноутбук
                                    case 'laptops':
            
                                        $laptops = array();
            
                                        foreach ($styles[$bk] as $k => $sp) 
                                        {
                                            if (!in_array($sp['subcategory'], array('macbook')))
                                                $laptops['other'][$sp['style_id']] = $sp;
                                            else 
                                            {
                                                $laptops[$sp['subcategory']][$sp['style_id']] = $sp;
                                            }
                                        }
            
                                        if (in_array(styleCategory::$BASECATS[$bk]['def_style'], array_keys($laptops['macbook'])))
                                        {
                                            $ds = $laptops['macbook'][styleCategory::$BASECATS[$bk]['def_style']];
                                            unset($laptops['macbook'][styleCategory::$BASECATS[$bk]['def_style']]);
                                            array_unshift($laptops['macbook'], $ds);
                                        }
                                        
                                        $styles[$bk] = (array) $laptops['macbook'] + (array) $laptops['other'];
                                        
                                        break;
            
                                    // первым ("дефолтным") ставим тач из настроек
                                    case 'touchpads':
                                        
                                        foreach ($styles[$bk] as $k => $sp) 
                                        {
                                            $order[$sp['style_id']] = $sp['style_order'];
                                        }
                                        
                                        // сортируем внутри производителя по моделям
                                        arsort($order);
                                        
                                        foreach ($order as $sid => $o)
                                        {
                                            $order[$sid] = $styles[$bk][$sid];
                                        }
                                        
                                        $styles[$bk] = $order;
                                        
                                        break;
            
            
                                    case 'auto':
            
                                        if (empty($good->pics['sticker']['pic_w']) || empty($good->pics['sticker']['pic_h']))
                                        {
                                            list($good->pics['sticker']['pic_w'], $good->pics['sticker']['pic_h']) = getimagesize(ROOTDIR . $good->pics['sticker']['path']);
                                        }
                                        
                                        // для работ присланных на конкурс одноцветных наклеек оставляем только 629ый носитель
                                        if ($good->competition_id == 2475) {
                                            $styles[$bk] = array(629 => $styles[$bk][629]);
                                        }
                                        
                                        // формируем список доступных для печати размеров на основе пропорций стикера загруженного автором
                                        foreach($styles[$bk] AS $style_id => $style)
                                        {
                                            foreach($style['sizes'] AS $size_id => $size)
                                            {
                                                $size['size_meta'] = json_decode($size['size_meta'], 1);
                                                
                                                if ($good->meta->maxautosize && $size['size_meta']['h'] > $good->meta->maxautosize)
                                                {
                                                    unset($styles[$bk][$style_id]['sizes'][$size_id]);
                                                    continue;
                                                }
                                                
                                                $h = $size['size_meta']['h'] * good::$pxPerCm;
                                                $w = round(($h / $good->pics['sticker']['pic_h']) * $good->pics['sticker']['pic_w']);
                
                                                $w = round($w / good::$pxPerCm);
                                                $h = round($h / good::$pxPerCm);
                
                                                if (($good_id == 41603 && !$added) || $good_id != 41603)
                                                {
                                                    // чит для стикербомбов
                                                    if ($good_id == 41603)
                                                    {
                                                        $w = 73;
                                                        $h = 30;
                                                        $added = TRUE;
                                                    }
                                                    
                                                    $styles[$bk][$style_id]['sizes'][$size_id]['en'] = "$w x $h";
                                                    
                                                    // монохромные наклейки
                                                    if ($style_id == 629)
                                                    {
                                                        $dc = $styles[$bk][$style_id]['sizes'][$size_id]['colors'][0];
                                                        
                                                        $styles[$bk][$style_id]['sizes'][$size_id]['colors'] = array();
                                                        
                                                        // присутсвуют во множестве цветов
                                                        foreach ($autocolors AS $cck => $cc) 
                                                        {
                                                            if ($cck == 1 && $good->pics['as_oncar'][0]['id'] < 0)
                                                                continue;
                                                            if ($cck == 37 && $good->pics['as_oncar'][1]['id'] < 0)
                                                                continue;
                                                            
                                                            $dc['color_name'] = $cc['color_name'];
                                                            $dc['color_hex'] = $cc['color_hex'];
                                                            
                                                            $styles[$bk][$style_id]['sizes'][$size_id]['colors'][$cck] = $dc;
                                                        }
                                                    }
                                                    // обыкновенные наклейки
                                                    else 
                                                    {
                                                        // присутсвуют только в одном цвете (прозрачном)
                                                    }
                                                    
                                                    foreach ($styles[$bk][$style_id]['sizes'][$size_id]['colors'] as &$acolor) 
                                                    {
                                                        $acolor['price'] = stock::stickerPriceCalculate($w, $h, 1, '', ($style_id == 629 ? 'mono' : ($style_id == 618 ? 108 : 27)));
                                                    
                                                        $acolor['author_gonorar'] = ceil(($acolor['price'] / 100) * (10 * $good->good_payment_multiplier));
                                                    }
                                                }
                                                else 
                                                {
                                                    unset($styles[$bk][$style_id]['sizes'][$size_id]);
                                                }
                                            }
                
                                            // если у носителя есть размер "свой размер"
                                            if ($style['sizes'][50])
                                            {
                                                // по меняем его id на custom
                                                $styles[$bk][$style_id]['sizes']['custom'] = $styles[$bk][$style_id]['sizes'][50];
                                                unset($styles[$bk][$style_id]['sizes'][50]);
                                            }
                
                                            ksort($styles[$bk][$style_id]['sizes']);
                                            $styles[$bk][$style_id]['sizes_list'] = implode(',', array_keys($styles[$bk][$style_id]['sizes']));
                                        }
            
                                        break;
            
                                    // постер
                                    // оставляем только носители для которых есть кэш
                                    case 'poster':
                                        
                                        if ($filters['category'] == $bk && (!$good->pics[$bk] || $good->pics[$bk]['id'] < 0))
                                        {
                                            header('location: /' . $this->page->module . '/' . $good->user_login . '/' . $good->id . '/');
                                            exit();
                                        }
                                        
                                        $good->pics['poster']['pic_w'] = $good->pics['poster']['pic_w'] * 2;
                                        $good->pics['poster']['pic_h'] = $good->pics['poster']['pic_h'] * 2;
                                        
                                        foreach($styles[$bk] AS $style_id => $style)
                                        {
                                            foreach ($style['sizes'] as $size_id => $size) 
                                            {
                                                $size['size_meta'] = json_decode($size['size_meta'], 1);
                                                
                                                // вертикальный постер
                                                if ($good->pics['poster']['pic_h'] > $good->pics['poster']['pic_w'] && ($size['size_meta']['w'] >= $size['size_meta']['h'] || $good->pics['poster']['pic_w'] < $size['size_meta']['w'] * good::$pxPerCm || $good->pics['poster']['pic_h'] < $size['size_meta']['h'] * good::$pxPerCm))
                                                {
                                                    unset($styles[$bk][$style_id]['sizes'][$size_id]);
                                                }
                                                // постер горизонтальный
                                                elseif ($good->pics['poster']['pic_w'] > $good->pics['poster']['pic_h'] && ($size['size_meta']['h'] >= $size['size_meta']['w'] || $good->pics['poster']['pic_w'] < $size['size_meta']['h'] * good::$pxPerCm || $good->pics['poster']['pic_h'] < $size['size_meta']['w'] * good::$pxPerCm))
                                                {
                                                    unset($styles[$bk][$style_id]['sizes'][$size_id]);
                                                }
                                                // постер квадратный
                                                elseif ($good->pics['poster']['pic_h'] == $good->pics['poster']['pic_w'] && ($size['size_meta']['w'] != $size['size_meta']['h'] || $good->pics['poster']['pic_w'] < $size['size_meta']['w'] * good::$pxPerCm || $good->pics['poster']['pic_h'] < $size['size_meta']['h'] * good::$pxPerCm))
                                                {
                                                    unset($styles[$bk][$style_id]['sizes'][$size_id]);
                                                }
                                                else
                                                    $size_order[$size['o']] = $size_id;
                                            }
                
                                            if (count($styles[$bk][$style_id]['sizes']) == 0)
                                            {
                                                unset($styles[$bk][$style_id]);
                                            }
                                            else
                                            {
                                                ksort($size_order);
                                                
                                                $sizes = $styles[$bk][$style_id]['sizes'];
                                                unset($styles[$bk][$style_id]['sizes']);
                                                
                                                foreach ($size_order as $size_id) 
                                                {
                                                    $styles[$bk][$style_id]['sizes'][$size_id] = $sizes[$size_id];
                                                }
                
                                                unset($sizes);
                                                unset($size_order);
                                            }
                                        }
                                        
                                        if (count($styles[$bk]) == 0)
                                        {
                                            unset($styles[$bk]);
                                        }
                                        
                                        $good->pics['poster']['pic_w'] = $good->pics['poster']['pic_w'] / 2;
                                        $good->pics['poster']['pic_h'] = $good->pics['poster']['pic_h'] / 2;
            
                                        break;
                                        
                                    case 'textile':
                                        
                                        foreach (stock::$textilePrice as $k => $p) {
                                            if (strpos($p, '$') !== false) {
                                                $p = trim($p, '$') * usdRateDaily();
                                            }
                                            if ($styles['textile'][$k])
                                                $styles['textile'][$k]['selfPrice'] = $p;
                                        }
                                        
                                        $this->view->setVar('textileMargin', stock::$textileMargin);
                                        $this->view->setVar('textilePrice', json_encode(stock::$textilePrice));
                                        $this->view->setVar('textilePrintPrice', json_encode(stock::$textilePrintPrice));
                                        $this->view->setVar('textileStandartSize', stock::$textileStandartSize);
                                        
                                        break;
                                }
                            }
            
                            if (!$default) 
                            {
                                $foo = array_keys($styles);
                                $default['category'] = array_shift($foo);
                                
                                if (in_array($default['category'], array('male', 'female', 'kids')))
                                {
                                    $default['sex'] = $default['category'];
                                    $default['category'] = 'futbolki';
                                }
                            }
            
                            $this->view->setVar('styles',  $styles);
                            $this->view->setVar('jstyles', json_encode($styles));
                            $this->view->setVar('default', $default);
                            $this->view->setVar('default_price', $default_price);
                            //$this->view->setVar('default_stockid', $default_stockid);
            
                            if (!$Style && $default['style_id']) {
                                $Style = new \application\models\style($default['style_id']);
                            }
                            
                            if ($Style) {
                            
                                $this->view->setVar('Style',  $Style);
                            
                                if (in_array($Style->style_sex, array('male', 'female', 'kids'))) {
                                    $this->page->breadcrump[] = array(
                                        'link'    => '',
                                        'caption' => ($Style->style_sex == 'male' ? '<a href="/catalog/male/">Мужское</a>' : ($Style->style_sex == 'female' ? '<a href="/catalog/female/">Женское</a>' : '<a href="/catalog/kids/">Детское</a>') ) .  ' <span class="breadcrumpDropDownMenuSwitcher"></span><ul class="breadcrumpDropDownMenu"><li ' . ($Style->style_sex == 'male' ? 'class="selected"' : '') . '><a href="/catalog/male/">Мужское</a></li><li ' . ($Style->style_sex == 'female' ? 'class="selected"' : '') . '><a href="/catalog/female/">Женское</a></li><li ' . ($Style->style_sex == 'kids' ? 'class="selected"' : '') . '><a href="/catalog/kids/new/">Детское</a></li></ul>');
                                } else {
                                    
                                    if (strpos($Style->style_slug, 'bumper') !== false) {
                                        $Style->category = 'bumper';
                                    }
                                    
                                    switch ($Style->category) {
                                        case 'cases':
                                            $this->page->breadcrump[] = array(
                                                'link'    => '/catalog/cases/case-iphone-5/',
                                                'caption' => 'Чехлы',
                                            );
                                            break;
                                        
                                        case 'bumper':
                                            $this->page->breadcrump[] = array(
                                                'link'    => '/catalog/phones/iphone-5-bumper/',
                                                'caption' => 'Бампер',
                                            );
                                            break;
                                            
                                        case 'cup':
                                        case 'poster':
                                        case 'textile':
                                            $this->page->breadcrump[] = array(
                                                'link'    => '/catalog/poster/poster-frame-vertical-white-30-x-40/',
                                                'caption' => 'Для дома',
                                            );
                                            break;
                                        
                                        default:
                                            $this->page->breadcrump[] = array(
                                                'link'    => '/catalog/stickers/',
                                                'caption' => 'Наклейки',
                                            );
                                            
                                            if (in_array($Style->category, array('phones', 'laptops', 'touchpads', 'ipodmp3'))) {
                                                $this->page->breadcrump[] = array(
                                                    'link'    => '/catalog/' . $Style->category . '/new/',
                                                    'caption' => styleCategory::$BASECATS[$Style->category]['title'],
                                                );
                                            }
                                            
                                            break;
                                    }
                                    
                                }
                                
                                $this->page->breadcrump[] = array(
                                    'link'    => '/catalog/' . $Style->category . '/' . $Style->style_slug . '/new/',
                                    'caption' => $Style->style_name);
                            }
                            
                            $this->page->breadcrump[] = array(
                                'link'    => '/' . $this->page->module . '/' . $U->user_login . '/' . $good->id . '/',
                                'caption' => $good->good_name);
                            
                            // Картинка из галлереи
                            $sth = App::db()->query("SELECT ga.`gallery_picture_id` AS picId, p1.`picture_path` AS small, p2.`picture_path` AS big, ga.`style_id`
                                        FROM `gallery` AS ga, `pictures` AS p1, `pictures` AS p2 
                                        WHERE 
                                                ga.`good_id` = '" . $good->id . "' 
                                            AND ga.`gallery_picture_visible_printshop` = '1' 
                                            AND ga.`gallery_tiny_picture` = p1.`picture_id` 
                                            AND ga.`gallery_catalog_picture` = p2.`picture_id` 
                                        ORDER BY ga.`gallery_picture_date` DESC");
            
                            if ($sth->rowCount() > 0)
                            {
                                $photos = $sth->fetchAll();
                                
                                $this->view->setVar('photos', $photos);
                                $this->view->setVar('galleryPictureId',  $photos[0]['small'] );
                                $this->view->setVar('galleryPicturePath', $photos[0]['big']);
                            }
            
            
                            if (in_array('zoom', $this->page->reqUrl) && $Style->category == 'auto')
                            {}
                            else
                            {
                                // Фото носителя
                                $sth = App::db()->query("SELECT s.`style_id`, s.`style_color`, sc.`cat_slug` AS cat, sc.`cat_parent`, sp.`pic_name`, p.`picture_path` 
                                            FROM `styles_pictures` AS sp, `styles` s, `pictures` AS p, `styles_category` sc, `good_stock` gs 
                                            WHERE 
                                                    sp.`style_id` = s.`style_id` 
                                                AND sp.`pic_id` = p.`picture_id` 
                                                AND sp.`pic_name` NOT IN ('side', 'lside', 'front', 'back', 'front_model', 'back_model', 'rez', 'mock-right', 'mock-right_preview', 'front_gray', 'back_gray', 'back_gray_preview', 'auto', 'auto2', 'auto_preview', 'auto2_preview') 
                                                AND s.`style_category` = sc.`id` 
                                                AND gs.`style_id` = s.`style_id`
                                                AND sc.`cat_slug` = '" . $Style->category . "'
                                                AND s.`style_sex` = '" . $Style->style_sex . "'
                                                AND gs.`good_stock_quantity` > '0'
                                                AND gs.`good_id` = '0'
                                            ORDER BY sp.`id` DESC");
                                            
                                if ($sth->rowCount() > 0)
                                {
                                    if ($good->pics['poster']['id'] > 0)
                                        $style_photos['poster']['original'] = array('small' => $good->pics['good_preview']['path'], 'category' => 'poster');
                
                                    foreach ($sth->fetchAll() as $r)
                                    {
                                        if (strpos($r['pic_name'], '_preview') !== false)
                                            $style_photos[$r['style_id']][str_replace('_preview', '', $r['pic_name'])]['small'] = $r['picture_path'];
                                        else
                                            $style_photos[$r['style_id']][$r['pic_name']]['big'] = $r['picture_path'];
                                    }
                                }
                                
                                if ($good->pics['cup']['id'] > 0)
                                {
                                    //$style_photos[725]['front'] = array('small' => 'http://cache.maryjane.ru/cup/725/' . $good->id . '.0.front.jpeg', 'big' => 'http://cache.maryjane.ru/cup/725/' . $good->id . '.0.front.jpeg');
                                    $style_photos[725]['lside'] = array('small' => 'http://cache.maryjane.ru/cup/725/' . $good->id . '.' . $good->pics['cup']['update_timestamp'] . '.lside.jpeg', 'big' => 'http://cache.maryjane.ru/cup/725/' . $good->id . '.' . $good->pics['cup']['update_timestamp'] . '.lside.jpeg');
                                    $style_photos[725]['side'] = array('small' => 'http://cache.maryjane.ru/cup/725/' . $good->id . '.' . $good->pics['cup']['update_timestamp'] . '.side.jpeg', 'big' => 'http://cache.maryjane.ru/cup/725/' . $good->id . '.' . $good->pics['cup']['update_timestamp'] . '.side.jpeg');
                                    
                                    //unset($style_photos[725][$good->meta->cup_default_side_725]);
                                }
                                
                                $this->view->setVar('style_photos', $style_photos);
                            }
            
                            //printr($style_photos);
                            //printr($styles);
            
                            // also avalible on
                            if (!$sale && !in_array('zoom', $this->page->reqUrl))
                            {
                                $raw_styles = style::findAll(array('onstock' => 1));
                                
                                $also = array();
                                
                                foreach (styleCategory::$BASECATS as $k => $cat) 
                                {
                                    if (!$cat['sexes'] && $k != 'cup' && $k != 'cases' && $k != 'pillows' && $k != 'touchpads' && $k != 'laptops'&& $k != 'poster')
                                        continue;
                                    
                                    if ($k == 'bag')
                                        continue;
                                    
                                    if (!$cat['promo'])
                                        continue;
                                    
                                    // если исходник для данной категории носителей не найден или выключен
                                    if (!$good->pics[$cat['src_name']] || $good->pics[$cat['src_name']]['id'] < 0) {
                                        continue;
                                    }
                                    
                                    // если исходник для постеров загружен
                                    if ($k == 'poster') 
                                    {
                                        // но он меньше допустимых пределов, то убираем его
                                        if (!$styles['poster'] || $good->pics['poster']['pic_w'] < styleCategory::$BASECATS['poster']['src']['w'] || $good->pics['poster']['pic_h'] < styleCategory::$BASECATS['poster']['src']['h']) {
                                            continue;
                                        }
                                    }
            
                                    $i = 0;
                                    
                                    foreach ((array) $cat['promo'] as $sex => $s) 
                                    {
                                        if ($k == 'auto' && $also['288']) {
                                            continue;
                                        }
                                    
                                        if ($k == 'auto')
                                            $pic_name = 'as_sticker';
                                        else
                                            $pic_name = 'catalog_preview_' . $s;
                                        
                                        if ($good->pics[$pic_name]['id'] > 0 && $raw_styles[$s])
                                        {
                                            $also[$s]['pic_name'] = $pic_name;
                                            
                                            if ($k == 'bumper' || $k == 'cases')
                                                $also[$s]['category'] = 'phones';
                                            else
                                                $also[$s]['category'] = $k;
                                            
                                            $i++;
                                        }
                                    }
                                    
                                    // если это тряпки а на промоутируемомо цвете работа выключена берём первый любой носитель из категории
                                    if ($cat['sexes'] && $i == 0)
                                    {
                                        foreach ($raw_styles as $s => $style) 
                                        {
                                            $pic_name = 'catalog_preview_' . $s;
                                            
                                            if ($k == $style['cat_slug'] && $good->pics[$pic_name]['id'] > 0)
                                            {
                                                $also[$s]['pic_name'] = $pic_name;
                                                $also[$s]['category'] = $k;
                                                break;
                                            }
                                        }
                                    }
                                }
                                
                                foreach ($also as $s => $a) 
                                {
                                    $also[$s]['path'] = $good->pics[$a['pic_name']]['path'];
                                    $also[$s]['style_name'] = $raw_styles[$s]['style_name'];
            
                                    $also[$s]['link'] = '/' . $this->page->module . '/' . $good->user_login . '/' . $good->id . '/' . $raw_styles[$s]['style_slug'] . '/' . ($raw_styles[$s]['cat_parent'] == 1 ? strtolower(array_shift($styles[$raw_styles[$s]['style_sex']][$raw_styles[$s]['category']]['colors'][$raw_styles[$s]['color_id']]['sizes'])) . '/' : '');
                                    $also[$s]['sex']  = $raw_styles[$s]['style_sex'];
                                     
                                    if (styleCategory::$BASECATS[$a['category']]['sexes']) 
                                    {
                                        // перебираем все размеры в носителе и если данный размер содержит нужный нам цвет выбираем его
                                        foreach ($styles[$raw_styles[$s]['style_sex']][$a['category']]['sizes'] AS $size_id => $ss)
                                        {
                                            if (in_array($raw_styles[$s]['color_id'], array_keys($ss['colors']))) {
                                                $def_size = $size_id;
                                                break;
                                            }
                                        }
                                    } else { 
                                    }
                                }
                                
                                $this->view->setVar('also', $also);
                            }
                            
                            unset($default);
                            unset($data);
                            unset($styles);
                            
                            /**
                             * Дата доставки
                             */
                            if ($this->user->user_city == 1)
                            {
                                $hour   = (int) date('G');
                                $minute = (int) date('i');
                                
                                $day    = (int) date('w');
                                $year   = (int) date('Y');
                                $month  = (int) date('m');
                                
                                $dday   = (int) date('d'); // число
                                
                                if (date('z') >= 364 || date('z') < 12)
                                {
                                    $deliver_srok = '12 января';
                                }
                                
                                // майские праздники
                                if (in_array(date('m-d'), array('05-09', '05-10', '05-11')) || (date('m-d') == '05-08' && date('H') >= 20))
                                {
                                    $deliver_srok = '12 мая';
                                }
                                
                                if (in_array(date('d-m'), array('12-06', '13-06', '14-06')))
                                {
                                    $deliver_srok = '15 июня';
                                }
                                
                                if ($Style->category == 'patterns' || $Style->category == 'patterns-sweatshirts' || $Style->category == 'patterns-tolstovki' || $Style->category == 'patterns-bag') {
                                    $offset = 10;
                                }
                                
                                if (empty($deliver_srok))
                                {
                                    if (!$offset)
                                    {
                                        // одежа
                                        if (!$Style || ($Style->style_sex == 'male' || $Style->style_sex == 'female' || $Style->style_sex == 'kids'))
                                        {
                                            if ($hour >= 0 && ($hour . $minute < workend . workend_min))
                                            {
                                                if ($day >= 1 && $day <= 4)
                                                    $deliver_srok = 'завтра ' . datefromdb2textdate(date('Y-m-d', time() + 86400), 0, 1); // пн - чт
                                                elseif ($day == 5)
                                                    $deliver_srok = 'в субботу'; // пят до 18
                                                else
                                                    $deliver_srok = 'понедельник'; // суб, вск до 18
                                            }
                                            else 
                                            {
                                                if ($day >= 1 && $day <= 4)
                                                    $deliver_srok = 'послезавтра ' . datefromdb2textdate(date('Y-m-d', time() + 86400 * 2), 0, 1); // пн - чт после 18
                                                elseif ($day == 5)
                                                    $deliver_srok = 'понедельник'; // пят после 18
                                                elseif ($day == 6)
                                                    $deliver_srok = 'понедельник'; // суб после 18
                                                else
                                                    $deliver_srok = 'вторник'; // вск после 18
                                            }
                                        }
                                        // наклейки и чехлы
                                        else 
                                        {
                                            if ($hour >= 0 && ($hour . $minute < 1700))
                                            {
                                                if ($day >= 1 && $day <= 4)
                                                    $deliver_srok = 'завтра ' . datefromdb2textdate(date('Y-m-d', time() + 86400), 0, 1);
                                                elseif ($day == 5)
                                                    $deliver_srok = 'в субботу'; // пят до 18
                                                else
                                                    $deliver_srok = 'вторник'; // суб, вск до 18
                                            }
                                            else 
                                            {
                                                if ($day >= 1 && $day <= 4)
                                                    $deliver_srok = 'послезавтра ' . datefromdb2textdate(date('Y-m-d', time() + 86400 * 2), 0, 1); // пн - чт после 18
                                                elseif ($day == 5)
                                                    $deliver_srok = 'понедельник'; // пят после 18
                                                else
                                                    $deliver_srok = 'вторник'; // суб, вск после 18
                                            }
                                        }
                                    } else {
                                        $deliver_srok = datefromdb2textdate(date('Y-m-d', time() + ($offset * 86400)), 0, 1);
                                    }
                                }
                            } else {
                                
                                $sth = App::db()->query("SELECT `service`, `time`, `time1`, `time2` FROM `delivery_services` WHERE `city_id` = '" . $this->user->user_city . "' ORDER BY `time1` LIMIT 1");
                
                                if ($service = $sth->fetch()) {
                                    $deliver_srok = 'через ';
                                    
                                    if (!empty($service['time1'])) {
                                        $deliver_srok .= (($service['time1'] != $service['time2']) ? $service['time1'] . '-' . $service['time2'] : $service['time1']) . ' дня (дней)';
                                    }
                                }
                            }
                        }
                        else
                        {
                            $this->page->go('/catalog/');
                        }
            
                        $this->view->setVar('deliver_srok', $deliver_srok);
            
                        $this->view->setVar('pics', $good->pics);
            
                        //printr($good->pics['as_oncar']);
            
                        // PAGE SETTINGS
                        $PT = array();
                        
                        $this->page->title[] = $good->good_name;
                        $this->page->title[] = $good->user_login;
                        
                        if ($good->user->meta->personal_title) {
                            $this->page->title[] = $good->user->meta->personal_title;
                        }
                        
                        if ($Style)
                        {
                            $this->page->title[] = $Style->style_name;
                        }
                        
                        if ($this->user->id != $U->id && $this->user->authorized)
                        {
                            // Selected user (add / remove)
                            $sth = App::db()->query("SELECT `selected_id` FROM `selected` WHERE `user_id` = '". $this->user->id ."' AND `selected_id` = '".$U->id."'");
                            
                            if ($sth->rowCount() == 0)
                                $this->view->setVar("add_to_selected", 'false');
                            else
                                $this->view->setVar("add_to_selected", 'true');
                        }
                        
                        
                        // LIKES
                        $sth = App::db()->prepare("SELECT `pic_name` FROM `good_likes` WHERE `good_id` = :id AND `user_id` = :user AND `negative` = '0'");
                        
                        $sth->execute(array('id' => $good->id, 'user' => $this->user->id));
                        
                        foreach ($sth->fetchAll() as $u => $l)
                        {
                            $mylikes[str_replace('catalog_preview_', '', $l['pic_name'])]++;
                        }
            
                        $this->view->setVar('mylikes', $mylikes);
            
                        /**
                         * передаём переменные необходимые для расчёта цены наклейки
                         */
                        $this->view->setVar('price_margins', json_encode(stock::$margins));
                        $this->view->setVar('production_time_printing', $this->VARS['production_time_printing']);
                        $this->view->setVar('production_time_cutting', $this->VARS['production_time_cutting']);
                        $this->view->setVar('production_cost', round($this->VARS['production_cost'] / ($this->page->lang == 'en' ? $this->VARS['usdRate'] : 1), 1));
                        $this->view->setVar('ink_cost', round($this->VARS['ink_cost'] / ($this->page->lang == 'en' ? $this->VARS['usdRate'] : 1), 1));
                        
                        if ($good->competition_id == 2475)
                        {
                            if (empty($good->pics['sticker']['pic_w']) || empty($good->pics['sticker']['pic_h']))
                            {
                                list($good->pics['sticker']['pic_w'], $good->pics['sticker']['pic_h']) = getimagesize(ROOTDIR . $good->pics['sticker']['path']);
                            }
                            
                            $this->view->setVar('monochrome_koef', round($good->pics['sticker']['pic_w'] / $good->pics['sticker']['pic_h'], 1));
                        }
                        
                        // квик-внутряк во всплывающем окне
                        if ($zoom)
                        {
                            $this->view->setVar('modal', TRUE);
                            
                            $this->view->generate('catalog/list.zoom.ajax.tpl');
                            
                            exit();
                        }
                        
                        // время последней модификации размерных сеток носителей для версии кэша на клиенте
                        if (!$versions = App::memcache()->get('CATALOG_GOOD_VERSIONS'))
                        {
                            $foo = App::db()->query("SELECT MAX(`last_update`) AS lu FROM `faq` WHERE `group` = '8' LIMIT 1")->fetch();
                            
                            $versions = array(
                                'faq' => time($foo['lu']),
                            );
                            
                            App::memcache()->set('CATALOG_GOOD_VERSIONS', $versions, false, 30 * 86400);
                        }
                        
                        $this->view->setVar('versions', $versions);
                        
                        $this->page->ogPAGE_TITLE = implode(', ', $PT);
                        $this->page->ogImage = $good->pics['good_preview']['path'];
                        
                        /**
                         * Комментарии
                         */
                        try
                        {
                            $comments = $good->getCommentsFlatV2(0, 10000, $this->user);
                        }
                        catch (Exception $e)
                        {
                            printr($e);
                        }
                        
                        // WATCHING
                        $this->view->setVar('WFT', array(
                            'watchForThisChecked' => $this->user->isWatching($id, 'good'), 
                            'to' => $id, 
                            'type' => 'good'
                        ));
                        
                        $this->view->setVar('type', 'good');
                        $this->view->setVar('COMMENT_TYPE_FORM', 'good');
                        $this->view->setVar('comments', $comments);
                    }
                    else
                    {
                        $this->page->go('location: /', 301);
                    }
            
                    break;
            
                /**
                 * все авторы
                 */
                case 'authors':
            
                    $q = "SELECT u.`user_id`, u.`user_login`, u.`user_carma`, COUNT(DISTINCT(g.`good_id`)) AS gCount
                          FROM 
                            `users` AS u
                                LEFT OUTER JOIN `shopwindow` AS sw ON u.`user_id` = sw.`user_id`,
                            `goods` AS g
                          WHERE 
                                 g.`user_id`     = u.`user_id`
                            AND g.`good_status` IN ('printed', 'pretendent') 
                            AND g.`good_visible` = 'true'
                            AND u.`user_status` = 'active' 
                            AND sw.`id` IS NULL
                          GROUP BY u.`user_id`
                          ORDER BY u.`user_login`";
                          
                    $sth = App::db()->query($q);
                    $rs = $sth->fetchAll();
            
                    $tmp    = array();
                    $counts = array();
            
                    foreach($rs AS $row) {
                        $counts[] = $row['gCount'];
                        $tmp[]    = $row;
                    }
            
                    $smallest  = 12;
                    $largest   = 26;
                    $min_count = min($counts);
                    $max_count = max($counts);
            
                    $spread = $max_count - $min_count;
                    if ( $spread <= 0 ) $spread = 1;
                    $font_spread = $largest - $smallest;
                    if ( $font_spread <= 0 ) $font_spread = 1;
                    $font_step = $font_spread / $spread;
            
                    $authors = array();
            
                    foreach($tmp AS $k => $row) {
                        $row['font']       = $smallest + (($row['gCount'] - $min_count) * $font_step);
                        $row['user_login'] = str_replace('.livejournal.com', '', strtolower($row['user_login']));
                        $authors[] = $row;
                    }
            
                    $this->view->setVar('authors', $authors);
            
                    $this->page->tpl = 'catalog/authors.tpl';
            
                    $this->page->breadcrump[0] = array(
                        'link'    => '/catalog/',
                        'caption' => 'Сообщество');
            
                    $this->page->breadcrump[] = array(
                        'link'    => '/catalog/authors/',
                        'caption' => 'Все авторы');
            
                    break;
            
                /**
                 * распродажа шелкографии
                 */
                case 'sale':
            
                    $this->page->go('location: /catalog/futbolki/', 301);
            
                    break;
            
                /**
                 * облако тегов
                 */
                case 'tags':
            
                    $this->page->go('/tag/', 301);
                
                    break;
                    
                /**
                 * наклейки на тачки
                 */
                case 'auto':
                case '1color':
                
                    $this->page->tpl = 'catalog/list.auto.tpl';
                    $list_tpl = 'list.goods.auto.tpl';
                    
                    $this->page->import(array(
                        '/js/2012/autoscroll.js',
                        '/js/vote_catalog.js',
                    ));
                    
                    $onPage   = 36;
                    
                    if (empty($page)) 
                        $page = 1;
            
                    switch ($orderBy)
                    {
                        default:
                            $order = "g.`good_sold_allskins` DESC";
                            $this->view->setVar('orderby', 'popular');
                            //$orderBy = 'popular';
                            $this->page->title[0] = 'Виниловые наклейки на автомобиль. Наклейки на машину';
                            $this->view->setVar('mode', 'preview');
                            break;
                        case 'popular':
                            $order = 'g.`good_sold_allskins` DESC';
                            $this->page->noindex = TRUE;
                            $this->page->title[0] = 'Наклейки на автомобиль';
                            break;
                        case 'new':
                            //$order = " g.`good_voting_end` DESC, gw.`place` DESC, likes DESC, g.`good_date` DESC";
                            $order = " g.`good_date` DESC, likes DESC";
                            $this->page->title[0] = 'Наклейки на авто купить';
                            break;
            
                        case 'grade':
                            $this->page->noindex = TRUE;
                            $order = " g.`good_likes` DESC";
                            $this->page->title[0] = 'Наклейки на авто';
                            break;
                            
                        case 'top2015':
                        case 'top2016':
                            $order = "g.`good_sold_year` DESC, g.`good_date` DESC";
                            $this->page->breadcrump[] = array('caption' => 'Популярные в ' . str_replace('top', '', $orderBy) . ' году');
                            $this->page->title[0] = 'Популярные в ' . str_replace('top', '', $orderBy) . ' году';
                            break;
                    }
                    
                    if ($tag)
                    {
                        $this->page->title[] = $tag['name'];
                        $this->page->canonical = '/catalog/' . $task . '/';
                    }
                    
                    if ($this->user->authorized)
                    {
                         $this->user->getLikes();
                    }
                    
                    if ($U && !array_search('selected', $this->page->reqUrl))
                    {
                        $aq[] = " g.`user_id` = '" . $U->id . "'";
                        
                        $this->page->title[] = $U->user_login;
            
                        $this->page->canonical = '/catalog/' . $U->user_login . '/';
                    }
                    
                    if ($task == '1color' || $Style->id == 629)
                    {
                        $aq[] = "g.`competition_id` = '2475'";
                        
                        $this->page->breadcrump[] = array(
                            'link'    => '/catalog/stickers/',
                            'caption' => 'Наклейки');
                        
                        $this->page->breadcrump[] = array(
                            'link' => '', 
                            'caption' => 'Одноцветные наклейки');
                    }
                    
                    if ($search) 
                    {
                        if ($filters['good_status']) {
                            $aq['status'] = "g.`good_status` = '" . $filters['good_status'] . "'";
                        } else {
                            $aq['status'] = "g.`good_status` IN ('printed', 'pretendent')";
                        }
                    }
                    else
                    {
                        // "нам" видны все работы
                        if ($this->user->meta->mjteam == 'super-admin') {
                            if (!$U) {
                                if ($filters['good_status']) {
                                    $aq['status'] = "g.`good_status` = '" . $filters['good_status'] . "'";
                                } else {
                                    if ($notees)
                                        $aq['status'] = "g.`good_status` != 'new' AND g.`good_status` != 'deny'";
                                    else
                                        $aq['status'] = " g.`good_status` IN ('printed', 'pretendent')";
                                    
                                }
                            }
                        }
                        else
                        {
                            // посетитель смотрит основной каталог 
                            if (!$U) {
                                $aq[] = "g.`good_visible` = 'true'";
                                
                                if ($filters['good_status']) {
                                    $aq['status'] = "g.`good_status` = '" . $filters['good_status'] . "'";
                                } else {
                                    if ($notees)
                                        $aq['status'] = "g.`good_status` != 'new' AND g.`good_status` != 'deny'";
                                    else
                                        $aq['status'] = " g.`good_status` IN ('printed', 'pretendent')";
                                }
                                
                            } else {
                                // автор смотри на свою витрину
                                if ($U->id == $this->user->id)
                                {
                                }
                                // посетитель смотри на витрину автора
                                else {
                                    $aq[] = "g.`good_visible` = 'true'";
                                    $aq['status'] = "g.`good_status` != 'deny'";
                                }
                            }
                        }
                    }
            
                    if ($Style)
                    {
                        $sth = App::db()->query("SELECT gs.`good_stock_id`, gs.`good_stock_quantity`, gs.`good_stock_price` AS p, gs.`good_stock_discount` AS d, gs.`good_stock_discount_promo` AS dp, 
                                        gs.`style_id`, gs.`good_stock_status`, 
                                        s.`style_category`, 
                                        s.`style_slug`, 
                                        s.`style_sex`
                                    FROM 
                                        `styles` s, `styles_category` sc, `good_stock` AS gs 
                                    WHERE 
                                            gs.`good_id` = '0' 
                                        AND gs.`good_stock_visible` = '1' 
                                        AND s.`style_visible` = '1'
                                        AND s.`style_print_block` != '' 
                                        AND gs.`style_id` = s.`style_id` 
                                        AND s.`style_category` = sc.`id` 
                                        AND s.`style_id` = '" . $Style->id . "'
                                    LIMIT 1");
                        
                        $stock = $sth->fetch();
                    }
            
                    if ($task == '1color' || $Style->id == 629)
                        $q = "SELECT SQL_CALC_FOUND_ROWS
                                g.`good_id`, 
                                g.`good_name`, 
                                g.`good_status`,
                                u.`user_id`, 
                                u.`user_login`, 
                                u.`user_designer_level`,
                                p2.`picture_path`,
                                g.`visits_catalog`,
                                g.`good_likes` AS likes,
                                IFNULL(gw.`place`, 0) place,
                                city.`name` AS city_name
                              FROM
                                `goods` AS g
                                    LEFT JOIN `good_winners` AS gw ON g.`good_id` = gw.`good_id`,
                                `users` AS u
                                    LEFT JOIN `city` AS city ON u.`user_city` = city.`id`,
                                `good_pictures` gpic1, 
                                `good_pictures` gpic2,
                                `good_pictures` gpic3, 
                                `pictures` AS p2 
                                " . (($at) ? ', ' . implode(', ', $at) : '') . "
                              WHERE 
                                    g.`good_visible` = 'true'
                                AND g.`good_domain`  IN ('mj', 'all')
                                AND gpic1.`pic_name` = 'auto'
                                AND gpic1.`good_id`  = g.`good_id`
                                AND gpic1.`pic_id` > 0  
                                AND gpic2.`pic_name` = 'sticker'
                                AND gpic2.`good_id`  = g.`good_id` 
                                AND gpic2.`pic_id` > 0 
                                AND gpic3.`pic_name` = 'as_oncar_1'
                                AND gpic3.`good_id`  = g.`good_id` 
                                AND gpic3.`pic_id`   = p2.`picture_id`
                                AND g.`user_id`      = u.`user_id`
                                AND u.`user_status`  = 'active'
                                " . (($aq) ? ' AND ' . implode(' AND ', $aq) : '') . "";
                    else
                        $q = "SELECT SQL_CALC_FOUND_ROWS
                                g.`good_id`, 
                                g.`good_name`, 
                                g.`good_status`,
                                c.`hex`, 
                                u.`user_id`, 
                                u.`user_login`, 
                                u.`user_designer_level`,
                                p.`picture_path`,
                                p2.`picture_path` AS preview,
                                g.`visits_catalog`,
                                g.`good_likes` AS likes,
                                IFNULL(gw.`place`, 0) place,
                                city.`name` AS city_name
                              FROM
                                `goods` AS g
                                    LEFT JOIN `good_winners` AS gw ON g.`good_id` = gw.`good_id`,
                                `users` AS u
                                    LEFT JOIN `city` AS city ON u.`user_city` = city.`id`,
                                `pictures` AS p,
                                `pictures` AS p2,
                                `good_stock_colors` AS c, 
                                `good_pictures` gpic,
                                `good_pictures` gpic1 , 
                                `good_pictures` gpic2 , 
                                `good_pictures` gpic3
                                " . (($at) ? ', ' . implode(', ', $at) : '') . "
                              WHERE 
                                    g.`good_visible` = 'true'
                                AND g.`good_domain`  IN ('mj', 'all')
                                AND c.`id`           = g.`ps_onmain_id`
                                AND gpic.`good_id`   = g.`good_id` 
                                AND gpic.`pic_name`  = 'as_sticker'
                                AND gpic.`pic_id`    = p.`picture_id`  
                                AND gpic1.`good_id`  = g.`good_id` 
                                AND gpic2.`good_id`  = g.`good_id` 
                                AND gpic3.`good_id`  = g.`good_id` 
                                AND gpic1.`pic_name` = 'auto' 
                                AND gpic2.`pic_name` = 'sticker' 
                                AND gpic3.`pic_name` IN ('as_oncar_0', 'as_oncar_1', 'as_oncar_2', 'as_oncar_3', 'as_oncar_4') 
                                AND gpic1.`pic_id` > 0 
                                AND gpic2.`pic_id` > 0 
                                AND gpic3.`pic_id` = p2.`picture_id` 
                                AND g.`user_id`      = u.`user_id`
                                AND u.`user_status`  = 'active'
                                " . (($aq) ? ' AND ' . implode(' AND ', $aq) : '') . "";
                    
                    $q .= "GROUP BY g.`good_id`";
                    $q .= "ORDER BY $order ";
                    $q .= "LIMIT " . (($page - 1) * $onPage) . ", $onPage";
            
                    //printr($q);
                    
                    $sth = App::db()->query($q);
            
                    $count = $sth->rowCount();
                    $goods = $sth->fetchAll();
                    
                    $foo = App::db()->query("SELECT FOUND_ROWS() AS c")->fetch();
                    $total = $foo['c'];
            
                    foreach($goods AS $k => $g)
                    {
                        $goods[$k]['good_name']    = stripslashes($goods[$k]['good_name']);
                        $goods[$k]['category']     = $filters['category'];
                        $goods[$k]['subcategory']  = $subcategory;
                        $goods[$k]['style_id']     = (!empty($g['style_slug'])) ? $g['style_slug'] : $Style->id;
                        $goods[$k]['price']        = round($g['p'] * (1 - ($g['d']) / 100));
                        $goods[$k]['user_designer_level'] = user::designerLevel2Picture($g['user_designer_level']);
                        
                        $goods[$k]['link'] = '/catalog/' . $g['user_login'] . '/' . $g['good_id'] . '/' . ($task == '1color' ? 'auto-monochrome' : $Style->style_slug) . '/';
                        $goods[$k]['zoomlink'] = '/catalog/' . $g['user_login'] . '/' . $g['good_id'] . '/' . ($task == '1color' ? 'auto-monochrome' : $Style->style_slug) . '/zoom/';
                        
                        if (!$g['preview']) 
                            $goods[$k]['preview'] = $g['picture_path'];
                        
                        $style_name = $g['style_name'];
            
                        if ($this->user->likes[$g['good_id']]){
                            $goods[$k]['liked'] = true;
                        }
                    }
            
                    $this->view->setVar('goods', $goods);
            
                    $this->view->setVar('category', $filters['category']);
                    $this->view->setVar('subcategory', $subcategory);
                    $this->view->setVar('style_id', $Style->id);
                    $this->view->setVar('styleId', $Style->id);
                    $this->view->setVar('style_name', $Style->style_name);
                    $this->view->setVar('list_tpl', $list_tpl);
                    $this->view->setVar('orderBy', $orderBy);
                    $this->view->setVar('stock', $stock);
            
                    if ($count <= 0) {
                        $this->page->noindex = true;
                        //$this->view->setVar('noindex', true);
                    }
            
                    $rest = intval($total) - (($page) * $onPage);
                    $this->view->setVar('rest', min($rest, $count));
            
                    $pages = ceil($total / $onPage);
            
                    $link = $this->page->reqUrl;
            
                    foreach($link AS $k => $l)
                    {
                        if (empty($l))
                            unset($link[$k]);
            
                        if ($l == 'page') {
                            unset($link[$k]);
                            unset($link[$k + 1]);
                        }
            
                        if ($l == 'ajax') {
                            unset($link[$k]);
                        }
                    }
            
                    // текущий урл без признака сортировки
                    $base_link = array_diff($this->page->reqUrl, array('new', 'popular', 'grade', 'top2013', 'top2014', 'top2015', 'top2015', 'preview'));
            
                    $this->view->setVar('rest', min($rest, $count));
                    $this->view->setVar('page', $page);
                    $this->view->setVar('count', $count);
                    $this->view->setVar('base_link', '/' . implode('/', $base_link));
                    $this->view->setVar('link', '/' . implode('/', $link));
                    $this->view->setVar('total', $total); 
            
                    if ($this->page->isAjax || $ajax)
                    {
                        $this->view->generate('catalog/' . $list_tpl);
                        exit();
                    }
                    
                    if (!$toptags = App::memcache()->get('CATALOG_TOP_TAGS'))
                    {
                        $sth = App::db()->query("SELECT t.`tag_id`, t.`name`, t.`slug`, p.`picture_path`, COUNT(DISTINCT(g.`good_id`)) as `count`
                                      FROM `tags` AS t, `tags_relationships` tr, `goods` g, `pictures` p
                                      WHERE
                                            tr.`tag_id` = t.`tag_id`
                                        AND tr.`object_id` = g.`good_id`
                                        AND tr.`object_type` = '1'
                                        AND g.`good_status` IN ('printed', 'pretendent')
                                        AND g.`good_visible` = 'true'
                                        AND t.`pic_id` = p.`picture_id`
                                        AND t.`show_in_collections` = '1'
                                      GROUP BY t.`tag_id`
                                      HAVING count > 2
                                      ORDER BY t.`name`");
            
                        $toptags = $sth->fetchAll();
            
                        App::memcache()->set('CATALOG_TOP_TAGS', $toptags, false, 2 * 86400);
                    }
            
                    foreach ($toptags AS $k => $tt) {
                        if (in_array($tt['slug'], array('detskie', '8marta')))
                            unset($toptags[$k]);
                        /*
                        if ($tt['slug'] == 'novuygod') 
                        {
                            $ng = $toptags[$k];
                            unset($toptags[$k]);
                            array_unshift($toptags, $ng);
                        }
                        */
                    }
            
                    $this->view->setVar('TAGS', $toptags);
                    
                    if ($page > 1) {
                        $this->page->title[] = 'Страница ' . $page;
                    }
                    
                    break;
                
                case 'moto':
                    break;
                
                case 'styles':
                    
                    header('Content-Type:application/x-javascript; charset=utf-8');
                    
                    $good_id = (int) $this->page->reqUrl[2];
                     
                    try
                    {
                        $good = new good($good_id);
                    }
                    catch (Exception $e)
                    {
                        exit('error: ' . $e->getMessage());
                    }
                    
                    if (!empty($this->page->reqUrl[3])) {
                        try
                        {
                            $Style = new \application\models\style($this->page->reqUrl[3]);
                            
                            $default['sex']      = $Style->style_sex;
                            $default['category'] = $Style->category;
                            $default['color']    = $Style->style_color;
                            $default['style_id'] = $Style->id;
                        }
                        catch (Exception $e)
                        {
                        }
                    }
                    
                    // извлекаем адишнал
                    if (!in_array('zoom', $this->page->reqUrl)) 
                    {
                        $sth = App::db()->query("SELECT gs.*, g.`gift_name`, g.`gift_price`, g.`gift_discount` FROM `gifts_styles` gs, `gifts` g WHERE gs.`gift_id` = g.`gift_id` AND g.`gift_quantity` > '0' AND g.`gift_visible` = '1'");
                        
                        foreach ($sth->fetchAll() AS $g) 
                        {
                            $g['price'] = round($g['gift_price'] * (1 - $g['gift_discount'] / 100));
                            
                            if (!empty($g['style_id']))
                                $styles_gifts[$g['style_id']][] = $g;
                            if (!empty($g['cat_id']))
                                $cats_gifts[$g['cat_id']][] = $g;
                            if (!empty($g['good_stock_id']))
                                $stock_gifts[$g['good_stock_id']][] = $g;
                        }
                    }
                    
                    
                    // распродажа
                    if ($sale)
                    {
                        $q = "SELECT
                                s.`style_id`,
                                s.`style_name`,
                                s.`style_name_en`,
                                s.`style_description`,
                                s.`style_sex`,
                                s.`style_slug`,
                                s.`style_viewsize` AS faq_id,
                                s.`style_composition`,
                                s.`style_relations`,
                                s.`style_cost`,
                                sz.`size_id`,
                                sz.`size_name`,
                                sz.`order`,
                                sz.`size_meta`,
                                gs.`good_stock_id`,
                                gs.`good_stock_price`,
                                (gs.`good_stock_quantity` +- gs.`good_stock_inprogress_quantity`) AS quantity,
                                gs.`good_stock_discount`,
                                gs.`good_stock_status`,
                                s.`style_color` AS color_id,
                                c.`name` AS color_name,
                                c.`hex`,
                                c.`group`,
                                gs.`size_rus`,
                                sc.`id` AS category, 
                                sc.`name` AS category_name,
                                sc.`cat_slug` AS category_slug,
                                sc.`cat_parent`
                            FROM
                                `good_stock` AS gs,
                                `good_stock_colors` AS c,
                                `styles` AS s,
                                `sizes` AS sz,
                                `styles_category` AS sc
                                " . (($at) ? ', ' . implode(', ', $at) : '') . "
                            WHERE
                                    gs.`good_id`                   = '$good_id'
                                AND gs.`good_id`                   > '0'
                                AND gs.`good_stock_visible`        = '1' 
                                AND gs.`style_id`                  = s.`style_id`
                                AND gs.`size_id`                   = sz.`size_id`
                                AND s.`style_color`                = c.`id`
                                AND s.`style_category`             = sc.`id`
                                AND (gs.`good_stock_quantity` +- gs.`good_stock_inprogress_quantity`) > '0'
                                " . (($aq) ? ' AND ' . implode(' AND ', $aq) : '') . "
                            ORDER BY gs.`good_stock_id` DESC";
                    }
                    // каталог, СПИСОК ВСЕХ НОСИТЕЛЕЙ ВО ВСЕХ ЦВЕТАХ
                    else
                    {
                        $aq = '';
                        
                        if ($Style->id > 0) {
                            if ($Style->cat_parent == 1)
                                $aq .= "AND s.`style_category` = '" . $Style->style_category . "' AND s.`style_sex` = '" . $Style->style_sex . "'";
                            else
                                if (in_array('zoom', $this->page->reqUrl))
                                    $aq .= "AND s.`style_category` = '" . $Style->style_category . "'";
                                else
                                    $aq .= "AND sc.`cat_parent` > '1'";
                        }
                        
                        $q = "SELECT
                                s.`style_id`,
                                s.`style_name`,
                                s.`style_name_en`,
                                s.`style_slug`,
                                s.`style_sex`,
                                s.`style_viewsize` AS faq_id,
                                s.`style_description`,
                                s.`style_composition`,
                                s.`style_relations`,
                                s.`style_print_block`,
                                s.`style_cost`,
                                sz.`size_id`,
                                sz.`size_name`,
                                sz.`order`,
                                sz.`size_meta`,
                                gs.`size_rus`,
                                gs.`good_stock_id`,
                                gs.`good_stock_price`, 
                                gs.`good_stock_discount`,
                                gs.`good_stock_discount_promo`,
                                gs.`good_stock_status`,
                                (gs.`good_stock_quantity` +- gs.`good_stock_inprogress_quantity`) AS quantity,
                                s.`style_color` AS color_id,
                                gs.`good_id`,
                                c.`name` AS color_name,
                                c.`hex`,
                                c.`group`,
                                sc.`id` AS category, 
                                sc.`name` AS category_name,
                                sc.`cat_slug` AS category_slug,
                                sc.`cat_parent`
                            FROM
                                `good_stock` AS gs,
                                `good_stock_colors` AS c,
                                `styles` AS s,
                                `sizes` AS sz,
                                `styles_category` AS sc
                            WHERE
                                    gs.`good_id`   = '0'
                                AND gs.`good_stock_visible`   = '1'
                                AND gs.`style_id`             NOT IN ('331', '472', '473', '474')
                                AND gs.`style_id`             = s.`style_id`
                                AND gs.`size_id`              = sz.`size_id`
                                AND ((gs.`good_stock_quantity` +- gs.`good_stock_inprogress_quantity`) > '0')
                                AND s.`style_color`           = c.`id`
                                AND s.`style_category`        = sc.`id`
                                AND sc.`id` NOT IN ('71', '118', '87', '24')
                                "
                                .
                                $aq
                                .
                                "
                            ORDER BY s.`style_order`";
                    }
                    
                    //  OR gs.`good_stock_status` = 'preorder' OR s.`style_color` IN ('37', '1', '21')
                    //printr($q, 1);
                    
                    $sth = App::db()->query($q);
                    
                    $i = 0;
            
                    if ($sth->rowCount() > 0)
                    {
                        $styles = array();
            
                        $discounts[4]  = userId2userBirthdayDiscount($this->user->id);
                        //$discounts[10] = $_COOKIE['informer'] ? 5 : 0;
                        if (!in_array(date('Y-m-d'), ['2017-11-23', '2017-11-24', '2017-11-25'])) {
                            $discounts[11] = $this->user->city && $this->user->city != 'Москва' && $this->user->city_region != 'Московская область' && $this->user->city_region != 'Москва' && $this->user->user_partner_status <= 0 && $this->user->id != 215735 && $this->user->id != 81706 ? 20 : 0;
                        }
                        
                        $discounts[7] = floatval($this->basket->user_basket_discount);
            
                        // Группируем данные из базы в единый массив для отправки в шаблон
                        // с "сортировкой" по полу
                        foreach($sth->fetchAll() AS $s)
                        {
                            if ($good->pics['catalog_preview_' . $s['style_id']]['id'] < 0) {
                                continue;
                            }

                            // вычисляем скидку на носитель
                            if (!$sale) 
                            {
                                $discounts[1] = $s['good_stock_discount'];
                                
                                if ($good->good_discount > 0) {
                                    $discounts[8] = $s['good_stock_discount_promo'];
                                }
                                
                                $s['good_stock_discount'] = max($discounts);
                            } 
                            else 
                            {
                                $s['good_stock_discount'] = 0;
                            }
            
                            $s['style_name'] = ($this->page->lang == 'en' && !empty($s['style_name_en'])) ? stripslashes($s['style_name_en']) : stripslashes($s['style_name']);
                            
                            // хинт для гаджетов
                            if ($s['cat_parent'] != 1)
                            {
                                // корневая категория
                                $s['style_sex']     = styleCategory::$BASECATSid[$s['cat_parent'] == 73 ? 22 : $s['cat_parent']];
                                // подкатегория
                                $s['subcategory']   = $s['category_slug'];
                                $s['category_slug'] = $s['style_id'];
                            }
                            
                            // отключённы цвет для одежды
                            if (!$sale && $s['cat_parent'] == 1 && styleCategory::$BASECATS[$s['category_slug']]['src_name'] == 'ps_src' && ((isset($good->pics['ps_src_' . $s['group']]) && $good->pics['ps_src_' . $s['group']]['id'] < 0) || !$good->pics['ps_src'] || $good->pics['ps_src']['id'] < 0)) {
                                continue;
                            }
            
                           
                            // если у этой категории есть родительская то кладём носитель в неё
                            // например, свитшоты в толстовки или длинный руква - в футболки
                            if (styleCategory::$BASECATS[$s['category_slug']]['parent'] && styleCategory::$BASECATS[styleCategory::$BASECATS[$s['category_slug']]['parent']]) {
                                $s['category_slug'] = styleCategory::$BASECATS[$s['category_slug']]['parent'];
                            }
                                
                            $styles[$s['style_sex']][$s['category_slug']]['category']          = $s['category_name'];
                            $styles[$s['style_sex']][$s['category_slug']]['subcategory']       = $s['subcategory'];
                            
                            // Если носитель одежда то группируем в массиве их по полу-категории
                            // гаджеты идут "сплошным" списком
                            if (!$styles[$s['style_sex']][$s['category_slug']]['style_id'] && (!$Style || $Style->id == $s['style_id'] || !in_array($Style->style_sex, ['male', 'female', 'kids'])))
                            {
                                $styles[$s['style_sex']][$s['category_slug']]['style_id']          = $s['style_id'];
                                $styles[$s['style_sex']][$s['category_slug']]['style_name']        = $s['style_name'];
                                $styles[$s['style_sex']][$s['category_slug']]['style_cost']        = $s['style_cost'];
                                $styles[$s['style_sex']][$s['category_slug']]['style_slug']        = stripslashes($s['style_slug']);
                                $styles[$s['style_sex']][$s['category_slug']]['style_composition'] = stripslashes($s['style_composition']);
                                $styles[$s['style_sex']][$s['category_slug']]['style_description'] = stripslashes($s['style_description']);
                                $styles[$s['style_sex']][$s['category_slug']]['relations']         = json_decode(stripslashes($s['style_relations']));
                                $styles[$s['style_sex']][$s['category_slug']]['faq_id']            = $s['faq_id'];
                                $styles[$s['style_sex']][$s['category_slug']]['cat_parent']        = $s['cat_parent'] == 73 ? 22 : $s['cat_parent'];
                                $styles[$s['style_sex']][$s['category_slug']]['style_print_block'] = $s['style_print_block'];
                                $styles[$s['style_sex']][$s['category_slug']]['dt']                = $this->basket->getStyleDeliveryDate($s['cat_parent'] == 1 ? $s['category_slug'] : styleCategory::$BASECATSid[$s['cat_parent']]);
                            }

                            $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['gsId'] = $s['good_stock_id'];
                            $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['en']   = stripslashes($s['size_name']);
                            $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['ru']   = stripslashes($s['size_rus']);
                            $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['o']    = $s['order'];
                            $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['size_meta'] = stripslashes($s['size_meta']);
            
                            if (!$Style && (
                                        (!$default && $s['color_id'] == $good->ps_onmain_id && $s['cat_parent'] == 1 && $s['style_sex'] != 'kids') || 
                                        ($default && !$default['style_id'] && $default['category'] == $s['category_slug'] && $default['sex'] == $s['style_sex'] && $default['color'] == $s['color_id'])
                                    )
                                )
                            {
                                $default['sex']      = $s['style_sex'];
                                $default['category'] = $s['category_slug'];
                                $default['color']    = $s['color_id'];
                                $default['size']     = $s['size_id'];
                                $default['bg']       = $s['hex'];
                                $default['style_id'] = $s['style_id'];
                                
                                $default_price       = $s['price'];
                            }
                            
                            // вариант входа по старым ссылкам
                            if ($s['category_slug'] == $filters['category'] && $s['style_sex'] == $filters['sex'] && $s['color_id'] == $filters['color'] && $s['size_id'] == $filters['size']) 
                            {
                                $default_price = $s['price'];
                                $default_stockid = $s['good_stock_id'];
                            }
                            
                            // вариант входа по новым ссылкам
                            if ($s['style_slug'] == $Style->style_slug)
                            {
                                $default_price = $s['price'];
                                $default_stockid = $s['good_stock_id'];
                            }
                            
                            // если носитель такого цвета уже есть в массиве, то берём только первый
                            if (!$styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']] && (!$Style || $s['cat_parent'] > 1 || $Style->id == $s['style_id'] || $Style->style_color != $s['color_id']))
                            {
                                $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['style_id']   = stripslashes($s['style_id']);
                                $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['style_name'] = $s['style_name'];
                                $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['id']         = $s['good_stock_id'];
                                $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['status']     = $s['good_stock_status'];
                                $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['quantity']   = $s['quantity'];
                                $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['color_name'] = $s['color_name'];
                                $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['color_hex']  = $s['hex'];
                                $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['group']      = $s['group'];
                                $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['faq_id']     = $s['faq_id'];
                                $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['discount']   = $s['good_stock_discount'];
                                $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['price']      = round($s['good_stock_price'] * (1 - $s['good_stock_discount'] / 100));
                                
                                if ($sale && $s['good_stock_discount'] == 0 && $s['category_slug'] == 'futbolki')
                                    $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['price_old']  = 1090;
                                elseif ($sale && $s['good_stock_discount'] == 0 && $s['category_slug'] == 'tolstovki')
                                    $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['price_old']  = 1990;
                                else
                                    $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['price_old']  = $s['good_stock_price'];
                                
                                if ($this->page->lang == 'en') {
                                    $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['price']     = round($styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['price'] / $this->VARS['usdRate'], 1);
                                    $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['price_old'] = round($styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['price_old'] / $this->VARS['usdRate'], 1);
                                }
                                
                                // отчисление покупашке при заказе
                                //if ($this->user->user_partner_status <= 0)
                                    //$styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['price_back'] = round($styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['price'] / 100 * $this->user->buyerLevel2discount());
                                
                                // отчисление автору при продаже
                                $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['author_gonorar'] = ceil(($styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['price'] / 100) * (10 * $good->good_payment_multiplier));
                            
                                $preview_path = 'http://cache.maryjane.ru/';
                
                                // превью 500*512
                                // гаджеты
                                if ($s['cat_parent'] > 1) 
                                {
                                    $preview_path .= styleCategory::$BASECATSid[$s['cat_parent']] . '/' . $s['style_id'] . '/' . $good->id . '.';
                                    
                                    if ($s['cat_parent'] == 65) {
                                        $foo = array_keys($good->pics['as_oncar']);
                                        $preview_path .= $good->pics['as_oncar'][array_shift($foo)]['update_timestamp'];
                                    } else {
                                        $preview_path .= $good->pics[styleCategory::$BASECATS[$s['style_sex']]['src_name']]['update_timestamp'];
                                    }
                                    
                                    // для кружек добавляем дефолтную сторону изделия
                                    if (styleCategory::$BASECATSid[$s['cat_parent']] == 'cup')
                                    {
                                        if ($good->meta->{'cup_default_side_' . $s['style_id']}) {
                                            //$preview_path .= '.' . $good->meta->{'cup_default_side_' . $s['style_id']};
                                        } else {
                                            $preview_path .= '.' . styleCategory::$BASECATS['cup']['def_side'];
                                        }
                                        
                                        $preview_path .= '.front';
                                    }
                                }
                                // тряпки
                                else 
                                {
                                    if (styleCategory::$BASECATS[$s['category_slug']]['src_name'] == 'ps_src') {
                                        if ($good->pics['ps_src_' . $s['group']] && $good->pics['ps_src_' . $s['group']]['update_timestamp'] != '0000-00-00 00:00:00') {
                                            $src_upd_time = $good->pics['ps_src_' . $s['group']]['update_timestamp'];
                                        } else {
                                            $src_upd_time = $good->pics['ps_src']['update_timestamp'];
                                        }
                                    } else {
                                        $src_upd_time = $good->pics[styleCategory::$BASECATS[$s['category_slug']]['src_name']]['update_timestamp'];
                                    }
                                    
                                    $preview_path .= $s['category_slug'] . '/' . $s['style_id'] . '/' . $good->id . ($sale ? '.sale.' : '.model.') . $src_upd_time;
                                }
                                    
                                                    
                                $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['preview'] = $preview_path . '.jpeg';
                
                                // спинка
                                if (in_array($s['category_slug'], array('patterns-sweatshirts', 'patterns-tolstovki', 'patterns'))) {
                                    $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['preview_back'] = 'http://cache.maryjane.ru/' . $s['category_slug'] . '/' . $s['style_id'] . '/' . $good->id . '.model_back.' . intval($good->pics[styleCategory::$BASECATS[$s['category_slug']]['src_name'] . '_back']['update_timestamp']) . '.jpeg';
                                }
                
                                // картинка зума
                                if ($s['cat_parent'] > 1)
                                {
                                    // гаджет
                                    if ($good->pics[styleCategory::$BASECATSid[$s['cat_parent']]]['big']) {
                                        $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['big'] = $good->pics[styleCategory::$BASECATSid[$s['cat_parent']]]['big'];
                                    } else {
                                        if (styleCategory::$BASECATSid[$s['cat_parent']] == 'auto') {
                                            $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['big'] = $good->pics['sticker'];
                                        } else {
                                            $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['big'] = $good->pics['ps_740x'];
                                        }
                                    }
                                }
                                else
                                {
                                    // одежда
                                    if ($good->pics[styleCategory::$BASECATS[$s['category_slug']]['src_name'] . '_big']) {
                                        $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['big'] = $good->pics[styleCategory::$BASECATS[$s['category_slug']]['src_name'] . '_big'];
                                    } else {
                                         if ($good->pics['ps_src_' . $s['group']]['big'])
                                            $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['big'] = $good->pics['ps_src_' . $s['group'] . '_big'];
                                        else
                                            $styles[$s['style_sex']][$s['category_slug']]['sizes'][$s['size_id']]['colors'][$s['color_id']]['big'] = $good->pics['ps_740x'];
                                    }
                                   
                                }
                            }

                            // адишнал
                            $styles[$s['style_sex']][$s['category_slug']]['gifts'] = array_merge((array) $cats_gifts[$s['category']], (array) $styles_gifts[$s['style_id']]);
                        }
            
                        
            
                        /**
                         * переносим размеры с предзаказом вперёд в списке
                         */
                        foreach ($styles as $k1 => $v1) 
                        {
                            foreach ($v1 as $k2 => $v2) 
                            {
                                foreach ($v2['sizes'] as $k3 => $v3) 
                                {
                                    $q = 0;
                                    $preorder = $onstock = array();
                                    
                                    foreach ($v3['colors'] as $k4 => $v4) 
                                    {
                                        $q += $v4['quantity'];
                                        
                                        if ($v4['quantity'] <= 0) {
                                            $preorder[$k4] = $v4;
                                        } else
                                            $onstock[$k4] = $v4;
                                    }
                                    
                                    $styles[$k1][$k2]['sizes'][$k3]['quantity'] = $q;
                                    $styles[$k1][$k2]['sizes'][$k3]['colors'] = $preorder + $onstock;
                                    
                                    if ($k1 != 'auto') {
                                        $styles[$k1][$k2]['sizes'][$k3]['colors_string'] = implode(',', array_merge(array_keys($preorder), array_keys($onstock)));
                                    }
                                }
                            }
                        }
     
                        if ((!$good->pics['ps_src'] || $good->pics['ps_src']['id'] < 0) && !$good->pics['patterns'] && !$good->pics['patterns_bag']) {
                            unset($styles['female']);
                            unset($styles['male']);
                            unset($styles['kids']);
                        }
              
                        // поста-обработка массива
                        foreach (styleCategory::$BASECATS as $bk => $bv)
                        {
                            if ($bk != 'moto' && (!$good->pics[$bv['src_name']] || $good->pics[$bv['src_name']]['id'] < 0) || (!$bv['sexes'] && !$styles[$bk])) {
                                unset($styles[$bk]);
                                unset($styles['male'][$bk]);
                                unset($styles['female'][$bk]);
                                continue;
                            }
                            
                            switch ($bk)
                            {
                                case 'phones':
            
                                    $phones = array();
                                        
                                    foreach ($styles[$bk] as $k => $sp) 
                                    {
                                        if ($good->pics['catalog_preview_' . $k]['id'] < 0) {
                                            continue;
                                        }
                                        
                                        $sp['style_name'] = trim(str_ireplace($sp['category'], '', $sp['style_name']));
                                        $phones[$sp['category']][$sp['style_id']] = $sp;
                                    }
            
                                    $styles[$bk] = (array) $phones['Apple'] + (array) $phones['Samsung'];
                                
                                    unset($phones['Apple']);
                                    unset($phones['Samsung']);
                                
                                    foreach ($phones as $k => $v) {
                                        $styles[$bk] += $v;
                                    }
                                    
                                    unset($phones);
            
            
            
                                break;
            
                                // дефолтный ноутбук ставим первым в списке
                                case 'laptops':
            
                                    $laptops = array();
            
                                    foreach ($styles[$bk] as $k => $sp) 
                                    {
                                        if (!in_array($sp['subcategory'], array('macbook')))
                                            $laptops['other'][$sp['style_id']] = $sp;
                                        else 
                                        {
                                            $laptops[$sp['subcategory']][$sp['style_id']] = $sp;
                                        }
                                    }
            
                                    if (in_array(styleCategory::$BASECATS[$bk]['def_style'], array_keys($laptops['macbook'])))
                                    {
                                        $ds = $laptops['macbook'][styleCategory::$BASECATS[$bk]['def_style']];
                                        unset($laptops['macbook'][styleCategory::$BASECATS[$bk]['def_style']]);
                                        
                                        $styles[$bk] = array(styleCategory::$BASECATS[$bk]['def_style'] => $ds);
                                    }
            
                                    $styles[$bk] += (array) $laptops['macbook'] + (array) $laptops['other'];
            
                                    break;
            
                                // первым ("дефолтным") ставим тач из настроек
                                case 'touchpads':
            
                                    if (!$sale)
                                    {
                                        $def = $styles[$bk][styleCategory::$BASECATS[$bk]['def_style']];
                                        unset($styles[$bk][styleCategory::$BASECATS[$bk]['def_style']]);
                                        $others = $styles[$bk];
                                        unset($styles[$bk]);
                                        $styles[$bk][styleCategory::$BASECATS[$bk]['def_style']] = $def;
                                        $styles[$bk] += (array) $others;
                                    }
                                    
                                    break;
            
                                case 'auto':
            
                                    if (empty($good->pics['sticker']['pic_w']) || empty($good->pics['sticker']['pic_h']))
                                    {
                                        list($good->pics['sticker']['pic_w'], $good->pics['sticker']['pic_h']) = getimagesize(ROOTDIR . $good->pics['sticker']['path']);
                                    }
                                    
                                    // для работ присланных на конкурс одноцветных наклеек оставляем только 629ый носитель
                                    if ($good->competition_id == 2475) {
                                        $styles[$bk] = array(629 => $styles[$bk][629]);
                                    }
                                    
                                    foreach($styles[$bk] AS $style_id => $style)
                                    {
                                        foreach($style['sizes'] AS $size_id => $size)
                                        {
                                            $size['size_meta'] = json_decode($size['size_meta'], 1);
                                            
                                            if ($good->meta->maxautosize && $size['size_meta']['h'] > $good->meta->maxautosize)
                                            {
                                                unset($styles[$bk][$style_id]['sizes'][$size_id]);
                                                continue;
                                            }
            
                                            $h = $size['size_meta']['h'] * good::$pxPerCm;
                                            $w = round(($h / $good->pics['sticker']['pic_h']) * $good->pics['sticker']['pic_w']);
            
                                            $w = round($w / good::$pxPerCm);
                                            $h = round($h / good::$pxPerCm);
            
                                            if (($good_id == 41603 && !$added) || $good_id != 41603)
                                            {
                                                // чит для стикербомбов
                                                if ($good_id == 41603)
                                                {
                                                    $w = 73;
                                                    $h = 30;
                                                    $added = TRUE;
                                                }
                                                
                                                $styles[$bk][$style_id]['sizes'][$size_id]['en'] = "$w x $h";
                                                
                                                // монохромные наклейки
                                                if ($style_id == 629)
                                                {
                                                    $foo = array_keys($styles[$bk][$style_id]['sizes'][$size_id]['colors']);
                                                    // вынимаем первый элемент из списка цветов
                                                    $dc = $styles[$bk][$style_id]['sizes'][$size_id]['colors'][array_shift($foo)];
                                                    
                                                    $styles[$bk][$style_id]['sizes'][$size_id]['colors'] = array();
                                                    
                                                    // присутсвуют во множестве цветов
                                                    foreach ($autocolors AS $cck => $cc) 
                                                    {
                                                        $dc['color_name'] = $cc['color_name'];
                                                        $dc['color_hex'] = $cc['color_hex'];
                                                        
                                                        $styles[$bk][$style_id]['sizes'][$size_id]['colors'][$cck] = $dc;
                                                    }
                                                }
                                                // обыкновенные наклейки
                                                else 
                                                {
                                                    // присутсвуют только в одном цвете (прозрачном)
                                                }
                                                
                                                foreach ($styles[$bk][$style_id]['sizes'][$size_id]['colors'] as &$acolor) 
                                                {
                                                    $acolor['price'] = stock::stickerPriceCalculate($w, $h, 1, '', ($style_id == 629 ? 'mono' : ($style_id == 618 ? 108 : 27)));
                                                    
                                                    if ($acolor['discount']) {
                                                        $acolor['price_old'] = $acolor['price'];
                                                        $acolor['price'] = round($acolor['price'] * (1 - $acolor['discount'] / 100));
                                                    }
                                                    
                                                    if ($this->page->lang == 'en') {
                                                        $acolor['price_old'] = round($acolor['price_old'] / $this->VARS['usdRate']);
                                                        $acolor['price'] = round($acolor['price'] / $this->VARS['usdRate']);
                                                    }
                                                    
                                                    $acolor['author_gonorar'] = ceil(($acolor['price'] / 100) * (10 * $good->good_payment_multiplier));
                                                }
                                            }
                                            else 
                                            {
                                                unset($styles[$bk][$style_id]['sizes'][$size_id]);
                                            }
                                        }
            
                                        // если у носителя есть размер "свой размер"
                                        if ($style['sizes'][50])
                                        {
                                            // по меняем его id на custom
                                            $styles[$bk][$style_id]['sizes']['custom'] = $styles[$bk][$style_id]['sizes'][50];
                                            unset($styles[$bk][$style_id]['sizes'][50]);
                                        }
            
                                        ksort($styles[$bk][$style_id]['sizes']);
                                        $styles[$bk][$style_id]['sizes_list'] = implode(',', array_keys($styles[$bk][$style_id]['sizes']));
                                    }
            
                                    break;
            
                                // постер
                                case 'poster':
                                    
                                    $good->pics['poster']['pic_w'] = $good->pics['poster']['pic_w'] * 2;
                                    $good->pics['poster']['pic_h'] = $good->pics['poster']['pic_h'] * 2;
                                    
                                    foreach($styles[$bk] AS $style_id => $style)
                                    {
                                        $size_order = array();
                                        
                                        foreach ($style['sizes'] as $size_id => $size) 
                                        {
                                            if ($size['quantity'] <= 0) {
                                                unset($styles[$bk][$style_id]['sizes'][$size_id]);
                                            }
                                            
                                            $size['size_meta'] = json_decode($size['size_meta'], 1);
                                                                        
                                            // постер вертикальный
                                            if ($good->pics['poster']['pic_h'] > $good->pics['poster']['pic_w'] && ($size['size_meta']['w'] >= $size['size_meta']['h'] || $good->pics['poster']['pic_w'] < $size['size_meta']['w'] * good::$pxPerCm || $good->pics['poster']['pic_h'] < $size['size_meta']['h'] * good::$pxPerCm))
                                            {
                                                unset($styles[$bk][$style_id]['sizes'][$size_id]);
                                            }
                                            // постер горизонтальный
                                            elseif ($good->pics['poster']['pic_w'] > $good->pics['poster']['pic_h'] && ($size['size_meta']['h'] >= $size['size_meta']['w'] || $good->pics['poster']['pic_w'] < $size['size_meta']['w'] * good::$pxPerCm || $good->pics['poster']['pic_h'] < $size['size_meta']['h'] * good::$pxPerCm))
                                            {
                                                unset($styles[$bk][$style_id]['sizes'][$size_id]);
                                            }
                                            // постер квадратный
                                            elseif ($good->pics['poster']['pic_h'] == $good->pics['poster']['pic_w'] && ($size['size_meta']['w'] != $size['size_meta']['h'] || $good->pics['poster']['pic_w'] < $size['size_meta']['w'] * good::$pxPerCm || $good->pics['poster']['pic_h'] < $size['size_meta']['h'] * good::$pxPerCm))
                                            {
                                                unset($styles[$bk][$style_id]['sizes'][$size_id]);
                                            }
                                            else
                                            {
                                                if (count($styles[$bk][$style_id]['sizes']) > 0)
                                                    $size_order[$size['o']] = $size_id;
                                            }
                                        }
            
                                        if (count($styles[$bk][$style_id]['sizes']) == 0)
                                        {
                                            unset($styles[$bk][$style_id]);
                                        }
                                        else
                                        {
                                            ksort($size_order);
                                            
                                            $styles[$bk][$style_id]['sizes_list'] = implode(',', $size_order);
                                        }
                                    }
                                    
                                    if (count($styles[$bk]) == 0)
                                    {
                                        unset($styles[$bk]);
                                    }
                                    
                                    $good->pics['poster']['pic_w'] = $good->pics['poster']['pic_w'] / 2;
                                    $good->pics['poster']['pic_h'] = $good->pics['poster']['pic_h'] / 2;
                                    
                                    break;
                                
                                case 'moto':
                                
                                    foreach ($styles[$bk] AS $sid => $s) 
                                    {
                                        $pb = unserialize($s['style_print_block']);
                                        
                                        $styles[$bk][$sid]['top_ratio'] = round($pb[styleCategory::$BASECATS[$bk]['def_side']]['w'] / $pb[styleCategory::$BASECATS[$bk]['def_side']]['h'], 3);      
                                        $styles[$bk][$sid]['bottom_ratio'] = round($pb[styleCategory::$BASECATS[$bk]['def_side']]['w_bottom'] / $pb[styleCategory::$BASECATS[$bk]['def_side']]['h'], 3);
                                        
                                        unset($styles[$bk][$sid]['style_print_block']);
                                    }
                                
                                    break;
                                    
                                case 'ipodmp3':
                                case 'stickers':
                                case 'bumper':
                                case 'cases': 
                                case 'boards': 
                                case 'cup': 
                                    
                                    break;
                                    
                                case 'stickerset':
                                    
                                    if (!$good->pics[styleCategory::$BASECATS[$bk]['src_name']]) {
                                        unset($styles[$bk]);
                                        continue;
                                    }
                                    
                                    break;
                                
                                case 'postcards':
                                
                                    // новогодние
                                    //$rel = array(47647 => 345, 32514 => 551, 49692 => 550);
                                    // валентинки
                                    $rel = array(46740 => 562, 46741 => 563, 46742 => 564);
                                
                                    if (in_array($good->id, array_keys($rel)))
                                    {
                                        $styles[$bk] = array($rel[$good->id] => $styles[$bk][$rel[$good->id]]);
                                    }
                                    else
                                        unset($styles[$bk]);
                                        
                                    break;
                                
                                // тряпки
                                // сортируем цвета и размеры
                                default:

                                    if (count(styleCategory::$BASECATS[$bk]['sexes']) > 0)
                                    {
                                        foreach (styleCategory::$BASECATS[$bk]['sexes'] AS $sex)
                                        {
                                            if (!$styles[$sex]) {
                                                continue;
                                            }
                                            
                                            foreach ($styles[$sex] AS $k1 => $cats)
                                            {
                                                $sizes = array();
                        
                                                foreach ($cats['sizes'] AS $k2 => $size)
                                                {
                                                    $sizes[$k2] = $size['o'];
                                                }
                        
                                                asort($sizes);
                                                
                                                $styles[$sex][$k1]['sizes_list'] = implode(',', array_keys($sizes));
                                            }
                                        }
                                    }
                                    
                                    break;
                            }
                        }
                    } 
            
                    //printr($styles);
                    //printr($default);
                    
                    if (!$default) 
                    {
                        $foo = array_keys($styles);
                        $default['category'] = array_shift($foo);
                        
                        if (in_array($default['category'], array('male', 'female', 'kids')))
                        {
                            $default['sex'] = $default['category'];
                            $default['category'] = 'futbolki';
                        }
                    }
                    
                    // время последней модификации размерных сеток носителей для версии кэша на клиенте
                    if (!$versions = App::memcache()->get('CATALOG_GOOD_VERSIONS'))
                    {
                        $foo = App::db()->query("SELECT MAX(`last_update`) AS lu FROM `faq` WHERE `group` = '8' LIMIT 1")->fetch();
                            
                        $versions = array(
                            'faq' => time($foo['lu']),
                        );
                        
                        App::memcache()->set('CATALOG_GOOD_VERSIONS', $versions, false, 30 * 86400);
                    }
                                    
                    $styles['versions'] = $versions;
            
                    $good->good_name_escaped = addslashes($good->good_name);
            
                    $this->view->setVar('good', $good->info);
                    $this->view->setVar('jstyles', json_encode($styles));
                    $this->view->setVar('default', $default);
                    $this->view->setVar('versions', $versions);
                    
                    /**
                     * передаём переменные необходимые для расчёта цены наклейки
                     */
                    $this->view->setVar('price_margins', json_encode(stock::$margins));
                    $this->view->setVar('production_time_printing', $this->VARS['production_time_printing']);
                    $this->view->setVar('production_time_cutting', $this->VARS['production_time_cutting']);
                    $this->view->setVar('production_cost', round($this->VARS['production_cost'] / ($this->page->lang == 'en' ? $this->VARS['usdRate'] : 1), 1));
                    $this->view->setVar('ink_cost', round($this->VARS['ink_cost'] / ($this->page->lang == 'en' ? $this->VARS['usdRate'] : 1), 1));
                     
                    $this->view->generate('catalog/good.styles.tpl');
                    exit();
                    
                    break;

                /**
                 * Sitemap
                 */                
                case 'sitemap':
            
                    if (!$reqUrl[2])
                        $reqUrl[2] = 407; // мужская футболка белая
                    
                    $style = new style($reqUrl[2]);
                    
                    $sth = App::db()->prepare("SELECT g.`good_id`, g.`good_name`, u.`user_login`
                                FROM `goods` g, `users` u, `good_pictures` gp
                                WHERE
                                        g.`good_status` IN ('printed', 'pretendent')
                                    AND g.`good_visible` = 'true'
                                    AND g.`user_id` = u.`user_id`
                                    AND u.`user_status` = 'active'
                                    AND gp.`good_id` = g.`good_id`
                                    AND gp.`pic_name` = ?
                                    AND gp.`pic_id` > '0'
                                GROUP BY g.`good_id`
                                LIMIT 1");
                    
                    $sth->execute(['catalog_preview_' . $style->id]);
                    
                    $rs = $sth->fetchAll();
                    
                    foreach ($rs as $g) 
                    {
                        $g['good_name']    = htmlspecialchars(stripslashes($g['good_name']));
                        $g['user_login']   = htmlspecialchars(str_replace('.livejournal.com', '', stripslashes($g['user_login'])));
                        $g['picture_path'] = 'http://cache.maryjane.ru/' . $style->category . '/' . $style->id . DS . $g['good_id'] . '.jpeg'; 
                        
                                $i = sprintf("<image:loc>%s</image:loc><image:caption>%s, %s</image:caption>", $g['picture_path'], $style->style_name, $g['good_name']);
                            $is = sprintf("<image:image>%s</image:image>", $i);
                        $u .= sprintf("<url><loc>%s</loc>%s</url>", 'http://www.maryjane.ru/catalog/' . $g['user_login'] . '/' . $g['good_id'] . '/', $is);
                    }
                    
                    $us = sprintf('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">%s</urlset>', $u);
                    $xml = simplexml_load_string('<?xml version="1.0" encoding="UTF-8"?>' . $us);
                    header('Content-type: application/xml; charset=UTF-8');
                    exit('<?xml version="1.0"?>' . $us);
                    
                    break;
                    
                /**
                 * Получить список ссылок для сайдбара с тегами
                 */
                case 'relatedTags':
            
                    $this->view->setVar('filters', $filters);
            
                    if (array_search($filters['category'], styleCategory::$BASECATSid))
                        $this->view->generate('catalog/list.sidebar.filters.tags.gadget.tpl');
                    else
                        $this->view->generate('catalog/list.sidebar.filters.tags.tpl');
            
                    exit();
            
                    break;
                    
                /**
                 * Список отключённых работ автора
                 */
                case 'disabled':
                    
                    try
                    {
                        // доступные носители
                        $sth = App::db()->query("SELECT s.`style_id`, sc.`cat_parent`, sc.`name` AS cat_name
                                              FROM `styles` s, `styles_category` sc
                                              WHERE 
                                                    s.`style_category` = sc.`id`
                                                AND s.`style_visible` > '0'");
                                                
                        foreach ($sth->fetchAll() as $s) {
                            $styles[$s['style_id']] = $s;
                        }
                        
                        // отключённые картинки каталога
                        $sth = App::db()->prepare("SELECT g.`good_id`, g.`good_name`, gp.`pic_name`
                                              FROM `goods` g, `good_pictures` gp
                                              WHERE 
                                                    g.`user_id` = :user
                                                AND g.`good_id` = gp.`good_id`
                                                AND gp.`pic_name` LIKE 'catalog_preview_%'
                                                AND gp.`pic_id` < '0'
                                                AND g.`good_status` NOT IN ('deny', 'new', 'customize')");
                                                
                        $sth->execute(array(
                            'user' => $U->id,
                        ));
                        
                        foreach ($sth->fetchAll() as $p) {
                            
                            $p['style_id'] = (int) str_replace('catalog_preview_', '', $p['pic_name']);
                            
                            $p['category'] = $styles[$p['style_id']]['cat_parent'];
                            $p['user_login'] = $U->user_login;
                            
                            if ($p['category'] == 1)
                                $p['category_name'] = 'Одежда';
                            else
                                $p['category_name'] = styleCategory::$BASECATS[styleCategory::$BASECATSid[$p['category']]]['title'];
                                
                            $pics[$p['good_id']][$p['category']] = $p;
                        }
                        
                        $this->view->setVar('pics', $pics);
                    }
                    catch (Exception $e) 
                    {
                        printr($e->getMessage());
                    }   
                    
                    $this->view->generate('catalog/disabled.tpl');
                    exit();
                    
                    break;
                
                    
                case 'enduro':
                case 'jetski':
                case 'atv':
                case 'snowmobile':
                case 'helmet':
                case 'helm':
                case 'zakaz-nakleek':
                    
                    /**
                     * Отправка данных о заказе
                     */
                    if (isset($_POST['enduro_order_description']) > 0)
                    {
                        if (!isset($_POST['keystring']) || $_POST['keystring'] == trim($_SESSION['captcha_keystring']) /*|| $this->user->id == 27278 */) {
                            App::mail()->send([27278, 6199], 827, ['data' => $_POST, 'referer' => $_SERVER['HTTP_REFERER'], 'user' => $this->user]);
                            exit('ok');
                        }
                        else {
                            exit('Ошибка капчи');
                        }
                    }
                    
                    $this->page->tpl = 'catalog/list.auto.tpl';
                    
                    $this->page->import(array(
                        '/js/blog-slider.js',
                        '/js/p/catalog.enduro.js',
                        '/js/sourcebuster.min.js',
                    ));
                    
                    $this->page->ogImage = '/images/b/enduro.jpg'; 
                    
                    $filters['category'] = $task;
                    
                    /**
                     * каталог готовых дизайнов
                     */
                    if (in_array('ready', $this->page->reqUrl) || in_array('gallery', $this->page->reqUrl))
                    {
                        $this->view->setVar('list_tpl', 'enduro.ready.tpl');
                        
                        if (in_array('ready', $this->page->reqUrl))
                            $xml_file = 'application/views/catalog/enduro.ready.xml';
                        else
                            $xml_file = 'application/views/catalog/enduro.gallery.xml';
                    
                        if ($xml_file)
                        {
                            $xml = simplexml_load_string(file_get_contents($xml_file));
                        
                            if ($xml) 
                            {
                                if ($xml->$filters['category'])
                                {
                                    $columns = $models = array();
                                    
                                    $x_paddings = 0;
                                    $x_offset = 10;
                                    $k = 0;
                                    
                                    foreach ($xml->$filters['category']->model AS $m)
                                    {
                                        $i = $k % 4;
                                        
                                        $models[] = array(
                                            'id' => (string) $m['id'],
                                            'name' => (string) $m['name'],
                                            'link' => (string) $m['link'],
                                            'small' => (string) $m->small,
                                            'big' => (string) $m->big,
                                            'i' => $i,
                                            'w' => (int) $m['width'],
                                            'h' => (int) $m['height'],
                                            'x' => ($i * 184) + ($i * $x_offset) + ($i * $x_paddings),
                                            'y' => (int) $columns[$i],
                                        );
                                        
                                            $columns[$i] += round(184 / (int) $m['width'] * (int) $m['height']) + $x_offset;
                                            //printr($columns);
                                        
                                        $k++;
                                    }
                                    
                                    $this->view->setVar('models', $models);
                                    $this->view->setVar('ULheight', max($columns));
                                }
                                else 
                                {
                                    printr('unknown manufacturer');
                                }
                            }
                            else 
                            {
                                printr('xml crashed');
                            }
                        }
                        else 
                        {
                            printr('xml file not found');
                        }
                    }
                    elseif ($this->page->reqUrl[2] == 'prikolnie-nakleyki-na-mototsikl')
                    {
                        $this->page404();
                    }
                    elseif ($this->page->reqUrl[2] == 'zakaz-nakleek' || $this->page->reqUrl[2] == 'kak-oformit-zakaz' || $this->page->reqUrl[2] == 'kleim-nakleyki-na-mototsikl') 
                    {
                        $this->page->tpl = 'application/views/catalog/enduro.zakaz-nakleek.tpl';
                    }
                    else 
                    {
                        
                        $this->view->setVar('list_tpl', 'list.goods.enduro.tpl');
                        $this->view->setVar('page', 1);
                        
                        //if ($this->user->meta->mjteam)
                            $xml_file = 'application/views/catalog/enduro.v2.xml';
                        //else
                            //$xml_file = 'application/views/catalog/enduro.xml';
                        
                        if ($xml_file)
                        {
                            $xml = simplexml_load_string(file_get_contents($xml_file));
                        
                            if ($xml) 
                            {
                                $foo = array_keys((array) $xml->$filters['category']);
                                $manufacturer = $this->page->reqUrl[2] ? $this->page->reqUrl[2] : array_shift($foo);
                                
                                if ($xml->$filters['category']->$manufacturer)
                                {
                                    $models = $menu = array();
                                    
                                    foreach ($xml->$filters['category']->$manufacturer->model AS $km => $m)
                                    {
                                        $models[] = array(
                                            'id' => (string) $m['id'],
                                            'name' => (string) $m['name'],
                                            'picture' => (string) $m,
                                        );
                                        
                                        if ($m['group']) {
                                            
                                            if (!$menu[(string) $m['group']])
                                                $menu[(string) $m['group']]['name'] = (string) $m['group'];
                                            
                                            $menu[(string) $m['group']]['years'][] = array(
                                                'id' => (string) $m['id'],
                                                'name' => (string) $m['name'],
                                                'year' => (string) $m['year'],
                                            );
                                        } else {
                                            $menu[] = array(
                                                'id' => (string) $m['id'],
                                                'name' => (string) $m['name'],
                                            );
                                        }
                                    }
                                
                                    $this->view->setVar('manufacturer', $manufacturer);
                                    $this->view->setVar('models', $models);
                                    $this->view->setVar('menu', $menu);
                                }
                                else 
                                {
                                    printr('unknown manufacturer');
                                }
                            }
                            else 
                            {
                                printr('xml crashed');
                            }
                        }
                        else 
                        {
                            printr('xml file not found');
                        }
                    }
                    
                    break;
                    
                case 'moto_disk':
                
                    $this->page->tpl = 'catalog/moto_disk.tpl';
                
                    try
                    {
                        $this->page->import(array(
                            '/js/p/catalog.disk.js',
                            '/css/catalog/disk.css',
                        ));
                    }
                    catch (Exception $e)
                    {
                        printr($e->getMessage());
                    }
                
                    break;
                
                /**
                 * Страница парных футболок с конкурса
                 */
                case 'parnie_futbolki':
                    
                    $this->page->tpl = 'catalog/list.photos.tpl';
                    
                    $this->page->import(array(
                        '/js/2012/autoscroll.js',
                        '/js/vote_catalog.js',
                    ));
                    
                    $this->page->title = array('Парные футболки'); 
                    
                    $onPage = 36;
                    
                    if (empty($page))
                        $page = 1;
                    
                    if (!$filters['category']) 
                    {
                        $filters['category'] = 'futbolki';
                        $filters['sex'] = 'male-female';
                        $filters['color'] = '37';
                    } 
                    
                    if (!$filters['color'])
                    {
                        if (!$filters['color'] = styleCategory::$BASECATS[$filters['category']]['def_color'][$filters['sex'] ? $filters['sex'] : 'male'])
                            $filters['color'] = '';
                    }
            
            
                    $sth = App::db()->prepare("SELECT 
                                            s.`style_id`, 
                                            s.`style_slug`, 
                                            s.`style_sex`, 
                                            gs.`good_stock_id`, 
                                            gs.`good_stock_quantity`, 
                                            gs.`good_stock_price` AS p, 
                                            gs.`good_stock_discount` AS d, 
                                            gs.`good_stock_discount_promo` AS dp, 
                                            gs.`style_id`, 
                                            gs.`good_stock_status`
                                        FROM 
                                            `styles` s, 
                                            `good_stock` gs 
                                        WHERE 
                                            s.`style_category` = :cat AND s.`style_color` = :color AND gs.`style_id` = s.`style_id` AND gs.`good_id` = '0' " . ($filters['sex'] ? "AND s.`style_sex` = '" . $filters['sex'] . "'" : '') . " AND s.`style_sex` != 'kids' AND gs.`good_stock_visible` = 1
                                            AND gs.`good_stock_quantity` > '0' 
                                        GROUP BY s.`style_id` 
                                        ORDER BY (IF(s.`style_sex` = 'male', 1, 0)) DESC
                                        LIMIT 2");  
                
                    $sth->execute(array(
                        'cat' => styleCategory::$BASECATS[$filters['category']]['id'],
                        'color' => $filters['color'],
                    ));
                
                    $styles = $sth->fetchAll();
                    
                    //printr($styles);
                    
                    if (!$filters['sex'])
                        $filters['sex'] = 'male';
                    
                    if (count($styles) > 1)
                        $aq2[] = "gp.`pic_name` IN ('catalog_preview_" . $styles[0]['style_id'] . "', 'catalog_preview_" . $styles[1]['style_id'] . "')";
                    else {
                        $aq2[] = "gp.`pic_name` = 'catalog_preview_" . $styles[0]['style_id'] . "'";
                        $aq[] = "g.`good_sex`  = '" . $styles[0]['style_sex'] . "'";
                    }
                    
                    $stock = $styles[0];
                    
                    $q = "SELECT
                              SQL_CALC_FOUND_ROWS
                                u.`user_id`,
                                u.`user_login`,
                                u.`user_designer_level`,
                                g.`good_id`,
                                g.`good_name`,
                                g.`good_discount`,
                                g.`visits` + g.`visits_catalog` AS visits_catalog,
                                g.`good_status`,
                                g.`good_visible`,
                                g.`ps_src`,
                                g.`good_date`,
                                g.`good_modif_date`,
                                g.`good_voting_end`,
                                g.`good_likes` AS likes,
                                g.`good_sex`,
                                g.`good_sex_alt`,
                                c.`hex` AS bg,
                                IFNULL(gw.`place`, 0) place,
                                IF (g.`good_status` IN ('printed', 'pretendent'), 1, 0) AS winners,
                                city.`name` AS city_name
                          FROM 
                            `tags` t,
                            `tags_relationships` gtr, 
                            `goods` g
                                LEFT JOIN `good_winners` AS gw ON g.`good_id` = gw.`good_id`
                                " . ($this->user->meta->mjteam == 'super-admin' ? "LEFT JOIN `good_pictures` gps ON gps.`good_id` = g.`good_id` AND gps.`pic_name` = 'ps_src'" : '') . ",
                            `users` u
                                LEFT JOIN `city` AS city ON u.`user_city` = city.`id`,
                            `good_stock_colors` AS c
                          WHERE
                                g.`good_visible` = 'true'
                            AND g.`good_status` IN ('printed', 'pretendent')
                            AND g.`user_id` = u.`user_id`
                            AND u.`user_status` = 'active'
                            AND c.`id`  = g.`ps_onmain_id`
                            "
                            . 
                            ($aq ? ' AND ' . implode(' AND ', $aq) : '') 
                            . 
                            "
                          GROUP BY g.`good_id` 
                          ORDER BY gw.`place` DESC, g.`good_voting_end` DESC, g.`good_id`
                          LIMIT " . (($page - 1) * $onPage) . ', ' . $onPage;
                    
                    try
                    {        
                        $sth = App::db()->query($q);
                            
                        $sth->execute(array('tag' => $tag['tag_id']));
                    }
                    catch (Exception $e)
                    {
                        printr($e);
                    }
                    
                    $count = $sth->rowCount();
                    $goods = $sth->fetchAll();
            
                    $sth = App::db()->query("SELECT FOUND_ROWS() AS c");
                    $foo = $sth->fetch();
                    $total = $foo['c'];
                    
                    foreach ($goods as $k => $g) 
                    {
                        $ids[] = $g['good_id'];
                    }
                    
                    //printr($styles);
                    
                    try
                    {
                        $q = "SELECT 
                                gp.`good_id`,
                                gp.`pic_name`,
                                p.`picture_path`,
                                gp.`pic_w`,
                                gp.`pic_h`
                              FROM 
                                `good_pictures` gp,
                                `pictures` p
                              WHERE
                                    gp.`good_id` IN ('" . implode("', '", $ids) . "')
                                AND gp.`pic_id` = p.`picture_id`
                                "
                                . 
                                ($aq2 ? ' AND ' . implode(' AND ', $aq2) : '')
                                .
                             "ORDER BY IF(gp.`pic_name` = 'catalog_preview_" . $styles[0]['style_id'] . "', 1, 0) DESC";
                        
                        //printr($q);
                        
                        $sth = App::db()->query($q);
                        
                        foreach ($sth->fetchAll() as $p) 
                        {
                            $pics[$p['good_id']][str_replace('catalog_preview_', '', $p['pic_name'])] = $p;
                        }
                    }
                    catch (Exception $e)
                    {
                        printr($e);
                    }
                    
                    //printr($pics);
                    //printr($styles);
                    
                    foreach ($goods as $k => &$g) 
                    {
                        $goods[$k]['good_name']  = stripslashes($g['good_name']);
                        $goods[$k]['user_login'] = str_replace ('.livejournal.com', '', $g['user_login']);
                        $goods[$k]['user_designer_level'] = designerLevel2Picture($g['user_designer_level']);
                        
                        if ($g['good_status'] == 'new' || $g['good_visible'] == 'modify')
                        {
                            $dd = getDateDiff(($g['good_modif_date'] != '0000-00-00 00:00:00') ? $g['good_modif_date'] : $g['good_date']);
                            $g['timetoend'] = ($dd > 24) ? 0 : 24 - $dd;
                        }
            
                        if (count($styles) == 2) {
                            $slug = $styles[$k % 2]['style_slug'];
                        } else {
                            $slug = $styles[0]['style_slug'];
                        }
                        
                        $g['link'] = '/catalog/' . $g['user_login'] . '/' . $g['good_id'] . '/' . $slug . (($filters['size']) ? '/' . $CATALOG_SIZES[$filters['size']] : '') . '/';
            
                        $d = ($g['good_discount'] > 0) ? max($stock['dp'], $stock['d']) : $stock['d'];
                        
                        $goods[$k]['price_old']  = round($stock['p'] / $this->VARS['usdRate']);
                        $goods[$k]['price']      = round(round($stock['p'] * (1 - ($d) / 100)) / $this->VARS['usdRate']);
                        
                        $g['disabled'] = ($g['good_visible'] == 'false') ? TRUE : '';
                        
                        if (count($styles) > 1)
                            $g['picture_path'] = $pics[$g['good_id']][$styles[$k % 2]['style_id']]['picture_path'];
                        else
                            $g['picture_path'] = $pics[$g['good_id']][$styles[0]['style_id']]['picture_path'];
                        
                        if (empty($g['picture_path']))
                            unset($g);
                        
                        if ($g['good_visible'] == 'true')
                        {}
                        elseif ($g['good_visible'] == 'false' && in_array($this->user->id, array($g['user_id'], 27278, 6199)))
                        {}
                        else {
                            if (!$U)
                                unset($goods[$k]);
                        }
                        
                        if ($this->user->id == $g['user_id'] && ($g['good_status'] == 'deny' || $g['good_status'] == 'new'))
                        {
                            $g['link'] = '/senddrawing.design/' . $g['good_id'] . '/';
                        }
            
                        if ($likes[$g['good_id']])
                            $g['liked'] = TRUE;
                    }       
                    
                    
                    $blink = $link = $this->page->reqUrl;
                
                    foreach ($link AS $k => $l)
                    {
                        if ($l == 'page') {
                            unset($link[$k]);
                            unset($link[$k + 1]);
                            unset($blink[$k]);
                            unset($blink[$k + 1]);
                        }
                        
                        if ($tag['slug'] == $link[1] && $link[0] == 'tag' && $collection) {
                            unset($link[0]);
                        }
            
                        if ($l == 'ajax') {
                            unset($link[$k]);
                        }
            
                        if ($l == 'search') {
                            $link[$k + 1] = urldecode($link[$k + 1]);
                        }
            
                        if ($l == 'male' || $l == 'female' || $l == 'kids' || strpos($l, 'category') !== false) {
                            unset($blink[$k]);
                        }
                        
                        if ($l == $filters['category'])
                        {
                            unset($blink[$k]);
                        }
                    }
                    
                    if ($total > $onPage)
                        $rest = intval($total) - (($page) * $onPage);
                    else
                        $rest = 0;
                
                    $this->view->setVar('goods', $goods);
                    $this->view->setVar('TAG', $tag);
                    $this->view->setVar('filters', $filters);
                    $this->view->setVar('stock', $stock);
                    $this->view->setVar('mlink', '/' . implode('/', $link) . '/');
                    $this->view->setVar('blink', '/' . implode('/', $blink) . '/');
                    $this->view->setVar('link', '/' . implode('/', $link));
                    $this->view->setVar('onpage', $baseOnPage);
                    $this->view->setVar('page', $page);
                    $this->view->setVar('count', $count);
                    $this->view->setVar('total', $total);
                    $this->view->setVar('rest', $rest);
                    
                    
                    
                    if ($this->page->isAjax || $ajax)
                    {
                        $this->view->setVar('PAGE', $Page);
                    
                        if (count($this->page->title) > 0)
                            $this->page->title = htmlspecialchars(implode(', ', $this->page->title));
                        
                        $this->view->generate('catalog/list.goods.photos.tpl');
                            
                        exit();
                    }
                    
                    // ---------------------------------------------------------------------------------------------
                    // Фильтры
                    // ---------------------------------------------------------------------------------------------
                    $sth = App::db()->prepare("SELECT sz.`size_id`, sz.`size_name`, gs.`size_rus`, c.`id` AS id, c.`name` AS name, c.`name_en`, c.`group`, c.`hex`, gs.`good_stock_status` AS status
                                FROM `sizes` sz, `good_stock_colors` c, `styles` s, `styles_category` sc, `good_stock` gs
                                WHERE
                                        sc.`cat_slug` = :cat
                                    AND s.`style_category` = sc.`id`
                                    AND s.`style_sex` = :sex
                                    AND s.`style_color` = c.`id`
                                    AND s.`style_front_picture` > '0'
                                    AND gs.`style_id` = s.`style_id` 
                                    AND gs.`size_id`  = sz.`size_id`
                                    AND gs.`good_stock_quantity` +- gs.`good_stock_inprogress_quantity` > '0'
                                    AND gs.`good_stock_visible` > '0'
                                    AND gs.`good_id` = '0'
                                ORDER BY s.`style_order` DESC, sz.`order`");
                    
                    $sth->execute(array('cat' => $filters['category'], 'sex' => $filters['sex']));
                    
                    $rs = $sth->fetchAll();
                
                    $Fcolorsizes = array();
                
                    foreach ($rs as $r) {
                        $Fcolorsizes[$r['id']][$r['size_id']] = $r;
                        $sizescolors[$r['size_id']][$r['id']] = $r;
                        
                        $Fsizes[$r['size_id']] = $r;
                        
                        $Fcolors[$r['id']] = $r;
                    }
                    
                    // дефолтный цвет для группы носителей ставим на первое место
                    if (styleCategory::$BASECATS[$filters['category']]['def_color'][$filters['sex']])
                    {
                        foreach ($Fcolors as $sk => $fc) 
                        {
                            if ($sk == styleCategory::$BASECATS[$filters['category']]['def_color'][$filters['sex']])
                            {
                                unset($Fcolors[$sk]);
                                $Fcolors = array($sk => $fc) + $Fcolors;
                            }
                        }
                    }
                
                    if (!$fcolor_not_selected) {
                        $Fsizes = $Fcolorsizes[$filters['color']];
                    }
                    
                    foreach ($Fcolors as $ck => &$fc) {
                        if (isset($filters['color']) && $filters['color'] == $ck) {
                            $fc['class'] = 'on';
                        }
                    }
            
                    foreach ($Fsizes as $sk => &$fs) {
                        if (!$filters['size']) {
                            $foo = array_keys($Fcolorsizes[$filters['color']]);
                            $filters['size'] = array_shift($foo);
                            break;
                        } elseif (isset($filters['size']) && $filters['size'] == $fs['size_id']) {
                            $fs['class'] = 'on';
                        }
                    }
            
                    foreach ($Fcolors as $ck => $c)
                    {
                        $F2colors[(($c['status'] == '') ? '-' : $c['status'])][] = $c;
                    }
                    
                    $Fcolors = array();
                    
                    if (is_array($F2colors['new'])) {
                        $Fcolors = array_merge($Fcolors, $F2colors['new']);
                    }
            
                    if (is_array($F2colors['-'])) {
                        $Fcolors = array_merge($Fcolors, $F2colors['-']);
                    }
            
                    if (is_array($F2colors['few'])) {
                        $Fcolors = array_merge($Fcolors, $F2colors['few']);
                    }
                    
                    if (is_array($F2colors['preorder'])) {
                        $Fcolors = array_merge($Fcolors, $F2colors['preorder']);
                    }
                    
                    
                    $this->view->setVar('Fsizes', $Fsizes);
                    $this->view->setVar('Fcolors', $Fcolors);
                    // ---------------------------------------------------------------------------------------------
                    // end Фильтры
                    // ---------------------------------------------------------------------------------------------
                    
                    break;
            }
            
            if (count($PD) > 0) {
                $this->page->description = htmlspecialchars(implode(' ', $PD));
            }
            
            if (count($this->page->title) > 0) {
                $this->page->title = htmlspecialchars(implode(', ', $this->page->title));
            } else {
                $this->page->title = '';
            }

            $this->view->setVar('filters', $filters);
     
            $this->view->generate($this->page->index_tpl);
        }
    }