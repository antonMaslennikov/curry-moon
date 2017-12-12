<?php
    namespace application\controllers;
    
    use \smashEngine\core\App AS App;

    use \application\models\post;
    
    class Controller_blog extends Controller_
    {
        public function action_index()
        {
            $this->page->index_tpl = 'index.tpl';
            $this->page->tpl = 'blog/index.tpl';
            $this->page->sidebar_tpl = 'blog/sidebar.tpl';
            $this->page->title = 'Блог';
            $this->page->addBreadCrump($this->page->title);
            
            $this->view->setVar('posts', post::getList(['lang' => 'ru', 'is_special' => 0, 'status' => 1, 'orderby' => 'publish_date']));
            
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
            
            $this->view->generate($this->page->index_tpl);
        }
    }