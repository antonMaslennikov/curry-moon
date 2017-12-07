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
            
            $this->getTree();
            
            if ($this->product = product::getBySlugPlus($this->page->reqUrl[count($this->page->reqUrl) - 1])) {
                $this->action_view();
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
                '/public/js/p/catalog.js',
                '/public/css/p/catalog.css', 
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
            
            // список товаров
            $onpage = 18;
            
            $products = product::getAll([
                'category' => $category->id, 
                'status' => 'active', 
                'picture' => true,
                'limit' => $onpage,
            ]);
            
            $this->view->setVar('base', $base);
            $this->view->setVar('parentCategory', $category);
            $this->view->setVar('childrenCategorys', $childrens);
            $this->view->setVar('products', $products);
            
            $this->view->generate($this->page->index_tpl);
        }
    
        public function action_view()
        {
            $this->page->index_tpl = 'index.tpl';
            $this->page->tpl = 'shop/product.tpl';
            $this->page->sidebar_tpl = 'shop/product.sidebar.tpl';
            
            $this->view->generate($this->page->index_tpl);
        }
    }