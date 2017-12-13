<?php
    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
    use \smashEngine\core\exception\appException;

    use \application\models\category;
    use \application\models\product;
    use \application\models\basketItem;
    use \application\models\certificate;

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
            
            $this->page->import([
                '/public/css/onepage.css',
                '/public/js/facebox/facebox.css', 
                '/public/js/facebox/facebox.js',
            ]);
            
            $this->user->user_city_name = cityId2name($this->user->user_city_id);
            $this->user->user_country_name = countryId2name($this->user->user_country_id);
            
            $this->view->setVar('USER', $this->user);
            
            if ($_POST)
            {
                printr($_POST, 1);
            }
            
            $this->view->generate($this->page->index_tpl);
        }
        
        public function action_quick()
        {
            $this->page->tpl = 'cart/quick.tpl';
            $this->view->generate($this->page->tpl);
        }
        
        public function action_terms()
        {
            $this->page->tpl = 'cart/terms-of-service.tpl';
            $this->view->generate($this->page->tpl);
        }
        
        public function action_products()
        {
            exit((string) $this->basket->getGoodsCount());
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
                    
                    if ($product->quantity <= 0) {
                        throw new appException('Данный товар закончился на складе');
                    }
                    
                    if ($product->status <= 0) {
                        throw new appException('Данный товар не доступен к продаже');
                    }
                    
                    if ($product->quantity < $_POST['quantity']) {
                        throw new appException('Для заказа доступно только ' . $product->quantity . ' шт.');
                    }
                    
                    
                    $item = new basketItem;
                    
                    $item->good_id = $product->id;
                    $item->price = $product->product_price;
                    $item->discount = $product->product_discount;
                    $item->quantity = (int) $_POST['quantity'];
                    
                    $this->basket->addToBasket($item);
                    
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
        
        /**
         * Удалить позицию из корзины
         */
        public function action_delete()
        {
            try
            {
                $this->basket->removeGood($_GET['product_id']);
            }
            catch (appException $e)
            {
                $this->page->setFlashMessage($e->getMessage());
            }
            
            $this->page->refresh();
        }
        
        /**
         * Изменить количество позиции в корзине
         */
        public function action_updatecart()
        {
            try
            {
                $this->basket->chQuanity($_POST['product_id'], $_POST['quantity']);
            }
            catch (appException $e)
            {
                $this->page->setFlashMessage($e->getMessage());
            }
            
            $this->page->refresh();
        }
        
        /**
         * Активировать купон
         */
        public function action_setcoupon()
        {
            try
            {
                if ($_POST['csrf_token'] != $_SESSION['csrf_token']) {
                    throw new appException('Ошибка при проверке токена', 1);
                }
                
                $c = certificate::find($_POST['coupon_code']);
                
                $c->activate($this->user, $this->basket);
                
                $this->page->setFlashMessage('Купон успешно активирован');
            }
            catch (appException $e)
            {
                $this->page->setFlashMessage($e->getMessage());
            }
            
            $this->page->refresh();
        }
    }