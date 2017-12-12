<?php
    namespace application\controllers;
    
    use \smashEngine\core\App AS App;

    use \application\models\post;
    
    class Controller_blog extends Controller_
    {
        public function __construct($r) 
        {
            parent::__construct($r);
            
            $sth = App::db()->query("SELECT 
                    EXTRACT(MONTH FROM `publish_date`) AS m,
                    EXTRACT(YEAR FROM `publish_date`) AS y,
                    CONCAT_WS('-', EXTRACT(MONTH FROM `publish_date`), EXTRACT(MONTH FROM `publish_date`)) AS ym,
                    count(`id`) AS count
                    FROM `posts` 
                    WHERE `status` = '1'
                    GROUP BY ym
                    ORDER BY ym DESC");
            
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
            
            $this->view->setVar('posts', post::getList(['lang' => 'ru', 'is_special' => 0, 'status' => 1, 'orderby' => 'publish_date']));
            
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
                'is_special' => 0, 
                'status' => 1, 
                'orderby' => 'publish_date', 
                'datestart' => $d . '-01', 
                'dateend' => $d . '-31']
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
            $this->page->addBreadCrump('Блог', '/ru/blog');
            $this->page->addBreadCrump($this->page->title);
            
            $this->page->keywords = $post->keywords;
            $this->page->description = $post->description;
                
            $this->view->setVar('post', $post);
            
            $this->view->generate($this->page->index_tpl);
        }
    }