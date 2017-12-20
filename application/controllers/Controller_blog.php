<?php
    namespace application\controllers;
    
    use \smashEngine\core\App AS App;

    use \application\models\post;
    use \application\models\tag;
    
    class Controller_blog extends Controller_
    {
        public function __construct($r) 
        {
            parent::__construct($r);
            
            $sth = App::db()->query("SELECT 
                    EXTRACT(MONTH FROM `publish_date`) AS m,
                    EXTRACT(YEAR FROM `publish_date`) AS y,
                    CONCAT_WS('-', EXTRACT(YEAR FROM `publish_date`), EXTRACT(MONTH FROM `publish_date`)) AS ym,
                    count(`id`) AS count
                    FROM `posts` 
                    WHERE `status` = '1'
                    GROUP BY ym
                    ORDER BY y DESC, m DESC");
            
            foreach ($sth->fetchAll() AS $a) {
                $a['month_name'] = single_month2textmonth($a['m']);
                $archive[] = $a;
            }
            
            $this->view->setVar('archive', $archive);
            $this->view->setVar('tags1', post::getAllTagsFull());
        }
        
        public function action_index()
        {
            $this->page->index_tpl = 'index.tpl';
            $this->page->tpl = 'blog/index.tpl';
            $this->page->sidebar_tpl = 'blog/sidebar.tpl';
            $this->page->title = 'Блог';
            $this->page->addBreadCrump($this->page->title);
            
            $onpage = 10;
            
            $trans_id = uniqid();
            
            $this->view->setVar('posts', post::getList([
                'lang' => 'ru', 
                'category' => 0, 
                'status' => 1, 
                'orderby' => 'publish_date DESC',
                'limit' => $onpage,
                'offset' => intval($_GET['limitstart']),
            ], $trans_id));
            
            $total = $_SESSION['pages_total_' . $trans_id];
            unset($_SESSION['pages_total_' . $trans_id]);
            
            $this->view->setVar('base', '/' . $this->page->reqUrl[0] . '/blog/');
            $this->view->setVar('pages', range(1, ceil($total / $onpage)));
            $this->view->setVar('page', $_GET['limitstart'] / $onpage + 1);
            $this->view->setVar('onpage', $onpage);
            
            if (!empty($_GET['limitstart'])) {
                $this->page->canonical = $this->page->url;
            }
            
            $this->view->generate($this->page->index_tpl);
        }
        
        public function action_aktcii()
        {
            $this->page->index_tpl = 'index.tpl';
            $this->page->tpl = 'blog/index.tpl';
            $this->page->sidebar_tpl = 'blog/sidebar.tpl';
            $this->page->title = 'Акции';
            $this->page->addBreadCrump($this->page->title);
            
            $this->view->setVar('posts', post::getList(['lang' => 'ru', 'category' => 1, 'status' => 1, 'orderby' => 'publish_date DESC']));
            
            $this->view->generate($this->page->index_tpl);
        }
        
        public function action_archive()
        {
            $this->page->index_tpl = 'index.tpl';
            $this->page->tpl = 'blog/index.tpl';
            $this->page->sidebar_tpl = 'blog/sidebar.tpl';
            $this->page->title = $this->page->h1 = 'Материалы отфильтрованы по дате: ' . single_month2textmonth($this->page->reqUrl[3]) . ' ' . $this->page->reqUrl[2];
            $this->page->addBreadCrump($this->page->title);
            
            $d = (int) $this->page->reqUrl[2] . '-' .(int) $this->page->reqUrl[3];
            
            $this->view->setVar('posts', post::getList([
                'lang' => 'ru', 
                'status' => 1, 
                'orderby' => 'publish_date', 
                'datestart' => $d . '-01', 
                'dateend' => $d . '-31']));
            
            $this->view->generate($this->page->index_tpl);
        }
        
        public function action_tegi()
        {
            $tag = tag::findBySlug($this->page->reqUrl[2]);
            
            $this->page->index_tpl = 'index.tpl';
            $this->page->tpl = 'blog/index.tpl';
            $this->page->sidebar_tpl = 'blog/sidebar.tpl';
            $this->page->title = 'Тег: ' . $tag->name;
            $this->page->h1 = 'Показать содержимое по тегу: ' . $tag->name;
            $this->page->addBreadCrump($this->page->h1);
            
            $this->view->setVar('posts', post::getList([
                'lang' => 'ru', 
                'status' => 1, 
                'orderby' => 'publish_date',
                'withtag' => $tag->id,]
                                                      ));
            
            $this->view->generate($this->page->index_tpl);
        }
        
    
        /**
         * Просмотр поста
         */
        public function action_view()
        {
            $this->page->index_tpl = 'index.tpl';
            $this->page->tpl = 'blog/view.tpl';
            $this->page->sidebar_tpl = 'blog/sidebar.tpl';
            
            $post = post::findBySlug($this->page->reqUrl[2]);
            $post->tags = post::getAllTagsFull($post->id);
                
            if (!$post) {
                $this->page404();
            }
            
            $this->page->title = $post->title;
            
            if ($post->category == 1)
                $this->page->addBreadCrump('Акции', '/ru/aktcii');
            elseif ($post->category == 0)
                $this->page->addBreadCrump('Блог', '/ru/blog');
            
            $this->page->addBreadCrump($this->page->title);
            
            $this->page->keywords = $post->keywords;
            $this->page->description = $post->description;
                
            $this->view->setVar('post', $post);
            
            $this->view->generate($this->page->index_tpl);
        }
    }