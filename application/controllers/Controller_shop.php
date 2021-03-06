<?php
    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
    use \application\models\category;
    use \application\models\product;

    use \PDO;
    use \Exception;
    use \DateTime;
    
    class Controller_shop extends Controller_
    {
        protected $tree;
        
        protected $product = 0;
        
        protected function getTree()
        {
            if ($this->tree == NULL) {
                $this->tree = new category;
            }
            
            return $this->tree;
        }
        
        public function __construct(\Routing\Router $router)
        {
            parent::__construct($router);
            
            if (in_array($this->page->reqUrl['2'], ['openproduct'])) {
                
            } else {
                $this->getTree();

                if ($this->product = product::getBySlugPlus($this->page->reqUrl[count($this->page->reqUrl) - 1])) {
                    $this->action_view();
                }
            }
        }                           
                                    
        public function action_index()
        {
            if ($this->product) {
                return;
            }
         
            $this->page->index_tpl = 'index.tpl';
            $this->page->tpl = 'shop/index.tpl';
            
            
            $this->page->import(array(
                '/public/css/bitvmbadges.css', 
            ));
            
            // корень каталога
            if (empty($this->page->reqUrl[2])) {
                $parent = 1;
            } else {
                if (!$parent = category::findNodeBySlug($this->page->reqUrl[count($this->page->reqUrl) - 1])) {
                    $this->page404();
                }
            }
            
            // информация о текущем узле
            $category = $this->tree->getNode($parent);
            // дочерние категории текущего узла
            $childrens = $this->tree->getChildren($parent);
            // вся цепочка категорий от корня до текущего узла
            $chain = array_slice($this->tree->getChain($category), 1);
              
            if ($parent > 1) {
                $this->page->title = $category->title;
            } else {
                $this->page->title = 'Добро пожаловать в Curry Moon';
            }
            
            // хлебная крошка
            $base = '/ru/shop/';
                
            foreach ($chain AS $c) {
                $base .= $c['slug'] . '/';
                $this->page->addBreadCrump($c['title'], $base);
            }
            
            // фильтры по доп.полям
            $options = $category->getAdditionFields(false);
            
            foreach ($options AS $o) {
                if ($_GET[$o['slug']]) {
                    $filters[$o['slug']] = $_GET[$o['slug']];
                }
            }
            
            // список товаров
            $onpage = 18;
            
            $trans_id = uniqid();
            
            // порядок сортировки
            if ($_GET['price'] && in_array($_GET['price'], ['asc', 'desc'])) {
                $orderby = '(pr.`product_price` - pr.`product_price` / 100 * pr.`product_discount`) ' . $_GET['price'];
            } else {
                $orderby = 'pr.`sorting`, pr.`id` DESC';
            }
            
            $products = product::getAll([
                'category' => $category->id, 
                'avalible' => true,
                'status' => 'active', 
                'picture' => true,
                'orderBy' => $orderby,
                'options' => $filters,
                'limit' => $onpage,
                'offset' => intval($_GET['limitstart']),
            ], $trans_id);
            
            $total = $_SESSION['pages_total_' . $trans_id];
            unset($_SESSION['pages_total_' . $trans_id]);
            
            $this->view->setVar('base', $base);
            $this->view->setVar('parentCategory', $category);
            $this->view->setVar('childrenCategorys', $childrens);
            $this->view->setVar('products', $products);
            $this->view->setVar('options', $options);
            $this->view->setVar('filters', $filters);
                
            if ($total > 0) {
                $this->view->setVar('pages', range(1, ceil($total / $onpage)));
                $this->view->setVar('page', $_GET['limitstart'] / $onpage + 1);
                $this->view->setVar('onpage', $onpage);
            }
            
            if (!empty($_GET['limitstart'])) {
                $this->page->canonical = $this->page->url;
            }
            
            unset($_GET['limitstart']);
            $this->view->setVar('get', count($_GET) > 0 ? '&' . http_build_query($_GET) : '');
            
            $this->view->generate($this->page->index_tpl);
        }
    
        public function action_view()
        {
            $this->page->index_tpl = 'index.tpl';
            $this->page->tpl = 'shop/product.tpl';
            $this->page->sidebar_tpl = 'shop/product.sidebar.tpl';
            
            if (!$this->product) {
                $this->page404();
            }
            
            $product = new product($this->product);
            
            $this->page->title = $product->product_name;
            $this->page->keywords = $product->meta_keywords;
            $this->page->description = $product->meta_description;
                
            $base = '/ru/shop';
            
            foreach ($product->getCategorysChain() AS $c) {
                $base .= '/' . $c['slug'];
                $this->page->addBreadCrump($c['title'], $base);
            }
            
            $this->page->addBreadCrump($product->product_name);
            
            $this->page->import([
                '/public/js/fancybox/jquery.fancybox-1.3.4.css', 
                '/public/js/fancybox/jquery.fancybox-1.3.4.pack.js',
                '/public/js/facebox/facebox.css', 
                '/public/js/facebox/facebox.js',
            ]);
            
            
            //if (!$categorys = App::memcache()->get('shop-categorys'))
            //{
                $categorys = [];

                function buildTreeWithLinks($item, $link, &$categorys)
                {
                    $item['link'] = $link . '/' . $item['slug'];

                    array_push($categorys, $item);

                    $childrens = (new category)->getChildren($item['id']);

                    if (count($childrens) > 0) {
                        foreach ($childrens AS $c) {
                            buildTreeWithLinks($c, $item['link'], $categorys);
                        }
                    } else {
                        return true;
                    }
                }

                foreach ((new category)->getChildren(1) AS $c)
                {
                    buildTreeWithLinks($c, '', $categorys);
                }
               
                //App::memcache()->set('shop-categorys', $categorys, false, 24 * 3600);
            //}
            
            $this->view->setVar('product', $product);
            $this->view->setVar('categorys', $categorys);
            
            $this->view->generate($this->page->index_tpl);
        }
    
        public function action_openproduct()
        {
            if ($this->page->reqUrl[3]) 
            {
                $product = new product($this->page->reqUrl[3]);
                $this->page->go($product->getShopLink(), 301);
            }
        }
    }