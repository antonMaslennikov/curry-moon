<?
    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
    use \SimpleXMLElement;
        
    class Controller_sitemap extends \smashEngine\core\Controller
    {
        protected $sitemap;
        
        public function __construct(\Routing\Router $router)
        {
            $this->sitemap = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8" ?><urlset></urlset>');
            $this->sitemap->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
            
            parent::__construct($router);
        }
        
        public function __destruct()
        {
            header('Content-type: application/xml; charset=utf-8');
            exit($this->sitemap->asXML());
        }
        
        public function action_index()
        {
            if (is_readable($this->page->pmfile))
            {
                $xml = simplexml_load_string(file_get_contents($this->page->pmfile));
                
                foreach ($xml AS $u => $p)
                {
                    if (in_array($u, array('selected', 'myphoto', 'login.mobile', 'basket2', 'order', 'profile', 'editprofile', 'main.2015.07', 'main.2015.08', 'odnocvet', 'statistic', 'stat', 'payback', 'blog', 'tag', 'prikol', 'order.seotexts', 'unsubscribe', 'hudsovet')))
                        continue;
                    
                    if (strpos($u, 'senddrawing') !== false || strpos($u, 'sendphoto') !== false || strpos($u, '/tag') !== false || strpos($u, 'main') !== false)
                        continue;
                    
                    if ($p->noindex)
                        continue;
                    
                    if ($p->title) {
                        $url = $this->sitemap->addChild('url');
                        $loc = $url->addChild('loc', 'http://www' . AppDomain . '/' . ltrim($u, '_') . '/');
                    }
                    
                    if ($p->unique) 
                    {
                        foreach ($p->unique[0] as $k => $p) 
                        {
                            if ($p->noindex)
                                continue;
                            
                            $url = $this->sitemap->addChild('url');
                            $uu = ltrim(trim((string) $p->attributes()->url, '/'), '_');
                            $loc = $url->addChild('loc', 'http://www' . AppDomain . '/' . $uu . (strpos($uu, '.php') === false ? '/' : ''));
                        }
                    }
                }
            }
        }
        
        public function action_dealer()
        {
            if (is_readable(ROOTDIR . '/dealer/pagemeta.xml'))
            {
                $xml = simplexml_load_string(file_get_contents(ROOTDIR . '/dealer/pagemeta.xml'));
                
                foreach ($xml AS $u => $p)
                {
                    if ($p->noindex)
                        continue;
                    
                    if ($p->title) {
                        $url = $this->sitemap->addChild('url');
                        $loc = $url->addChild('loc', 'http://www' . AppDomain . '/' . ltrim($u, '_') . '/');
                    }
                    
                    if ($p->unique) 
                    {
                        foreach ($p->unique[0] as $k => $p) 
                        {
                            if ($p->noindex)
                                continue;
                            
                            $url = $this->sitemap->addChild('url');
                            $uu = ltrim(trim((string) $p->attributes()->url, '/'), '_');
                            $loc = $url->addChild('loc', 'http://www' . AppDomain . '/' . $uu . (strpos($uu, '.php') === false ? '/' : ''));
                        }
                    }
                }
            }
        }
        
        public function action_authors()
        {
            $sth = App::db()->query("SELECT u.`user_login`
                                FROM `users` u, `users_meta` um, `goods` g
                                WHERE 
                                        um.`user_id` = u.`user_id`
                                    AND um.`meta_name` = 'goodApproved'
                                    AND g.`user_id` = u.`user_id`
                                    AND g.`good_visible` = 'true'
                                    AND g.`good_status` NOT IN ('deny', 'customize', 'new')
                                    AND u.`user_status` = 'active'
                                GROUP BY u.`user_id`");
                                
            foreach ($sth->fetchAll() AS $u)
            {               
                $url = $this->sitemap->addChild('url');
                $url->addChild('loc', 'http://www' . AppDomain . '/catalog/' . $u['user_login'] . '/');
                $url->addChild('changefreq', 'weekly');
            }
        }
        
        public function action_goods()
        {
            $sth = App::db()->query("SELECT u.`user_login`, g.`good_id`
                                FROM `users` u, `users_meta` um, `goods` g
                                WHERE 
                                        um.`user_id` = u.`user_id`
                                    AND um.`meta_name` = 'goodApproved'
                                    AND g.`user_id` = u.`user_id`
                                    AND g.`good_visible` = 'true'
                                    AND u.`user_status` = 'active'
                                    AND g.`good_status` != 'customize'
                                    AND g.`good_status` != 'new'
                                    AND g.`good_status` != 'deny'
                                GROUP BY g.`good_id`");
                                
            foreach ($sth->fetchAll() AS $g)
            {               
                $url = $this->sitemap->addChild('url');
                $url->addChild('loc', 'http://www' . AppDomain . '/catalog/' . $g['user_login'] . '/' . $g['good_id'] . '/');
                $url->addChild('changefreq', 'weekly');
            }
        }
        
        public function action_tags()
        {
            $sth = App::db()->query("SELECT t.`slug`, t.`tag_ps_goods`
                                FROM `tags` t, `tags_relationships` tr, `goods` g
                                WHERE 
                                        t.`tag_id` = tr.`tag_id`
                                    AND tr.`object_type` = '1'
                                    AND g.`good_id` = tr.`object_id`
                                    AND g.`good_visible` = 'true'
                                    AND t.`synonym_id` = '0'
                                    AND t.`tag_ps_goods` >= '5'
                                GROUP BY t.`tag_id`");
                                
            foreach ($sth->fetchAll() AS $tag)
            {
                $url = $this->sitemap->addChild('url');
                $url->addChild('loc', 'http://www' . AppDomain . '/tag/' . $tag['slug'] . '/');
                $url->addChild('changefreq', 'daily');
            }
        }
    
        public function action_blog()
        {
            try
            {
                $sth = App::db()->query("SELECT up.`post_slug` FROM `user_posts` up, `users` u WHERE up.`post_status` = 'publish' AND up.`r301` = '0' AND up.`post_author` = u.`user_id` AND u.`user_status` = 'active'");
                
                foreach ($sth->fetchAll() AS $u => $p)
                {
                    $url = $this->sitemap->addChild('url');
                    $loc = $url->addChild('loc', 'http://www' . AppDomain . '/blog/view/' . $p['post_slug'] . '/');
                }
            }
            catch (Exception $e)
            {
                printr($e);
            }
        }
    }