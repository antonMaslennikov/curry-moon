<?php
    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
    use \smashEngine\core\exception\appException;

    use \application\models\category;
    use \application\models\product;
    use \application\models\basketItem;

    use \PDO;
    use \Exception;
    use \DateTime;
    
    class Controller_cart extends Controller_
    {
        public function action_index()
        {
            $this->page->index_tpl = 'index.tpl';
            $this->page->tpl = 'cart/index.tpl';
            $this->page->title = 'Корзина';
            
            
            
            $this->view->generate($this->page->index_tpl);
        }
        
        /**
         * Добавить в корзину
         */
        public function action_add()
        {
            $this->page->tpl = 'cart/add.tpl';
            
            if ($_POST)
            {
                try
                {
                    if (!$_POST['product_id']) {
                        throw new appException('Не указан товар');
                    }
                    
                    if (!$_POST['quantity'] || $_POST['quantity'] < 0 || !is_numeric($_POST['quantity'])) {
                        throw new appException('Указано некорректное количество');
                    }
                    
                    $product = new product($_POST['product_id']);
                    
                    $item = new basketItem;
                    
                    
                    //$this->basket->addToBasket($item);
                    
                    $status = 'ok';
                }
                catch (appException $e)
                {
                    $status = 'error';
                    $error = $e->getMessage();
                }
                
            }
            
            $this->view->setVar('status', $status);
            $this->view->setVar('error', $error);
            $this->view->setVar('quantity', $_POST['quantity']);
            $this->view->setVar('product', $product);
            
            if ($this->page->isAjax) {
                ob_start();
                $this->view->generate($this->page->tpl);
                $text = ob_get_contents();
                ob_end_clean();
                
                exit(json_encode(['status' => $status, 'message' => $text]));
                
            } else {
                $this->page->index_tpl = 'index.tpl';
                $this->view->generate($this->page->index_tpl);
            }
        }
    }