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
        public function action_index()
        {
            $this->page->index_tpl = 'index.tpl';
            $this->page->tpl = 'shop/index.tpl';
            
            $this->page->import(array(
                '/public/js/p/catalog.js',
                '/public/css/p/catalog.css', 
            ));
            
            $tree = new category;
            
            // корень каталога
            if (empty($this->page->reqUrl[2])) {
                $parent = 1;
                $this->page->title = 'Добро пожаловать в Curry Moon';
            } else {
                if (!$parent = category::findNodeBySlug($this->page->reqUrl[count($this->page->reqUrl) - 1])) {
                    $this->page404();
                }
            }
            
            // информация о текущем узле
            $category = $tree->getNode($parent);
            // дочерние категории текущего узла
            $childrens = $tree->getChildren($parent);
            // вся цепочка категорий от корня до текущего узла
            $chain = array_slice($tree->getChain($category), 1);
                
            // хлебная крошка
            $base = '/ru/shop/';
                
            foreach ($chain AS $c) {
                $base .= $c['slug'] . '/';
                $this->page->addBreadCrump($c['title'], $base);
            }
            
            if ($parent > 1) {
                $this->page->title = $category->title;
            }
            
            // список товаров
            $onpage = 18;
            
            $products = product::getAll(['category' => $category->id, 'status' => 'active', 'picture' => true]);
            
            $this->view->setVar('base', $base);
            $this->view->setVar('parentCategory', $category);
            $this->view->setVar('childrenCategorys', $childrens);
            $this->view->setVar('products', $products);
            
            $this->view->generate($this->page->index_tpl);
        }
    }