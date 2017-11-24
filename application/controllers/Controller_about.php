<?php
    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
        
    class Controller_about extends \smashEngine\core\Controller
    {
        public function action_index()
        {
            $this->page->breadcrump[] = array(
                'link' => '/about/',
                'caption'=> 'Где купить футболку');
            
            if ($this->page->module == 'contacts')
                $this->page->tpl = 'contacts/index.tpl';
            else
                $this->page->tpl = 'about/about.tpl';
            
            $this->page->index_tpl = 'index.tpl';
            
            $this->view->setVar('TABZ', 'about/about.tabz.tpl');
            
            switch ($this->page->reqUrl[1]) {
                case 'us':
                    $this->view->setVar('tab1', TRUE);
                break;
                case 'projects':
                    $this->view->setVar('tab2', TRUE);
                break;
                default:
                case 'where':
                    $this->view->setVar('tab3', TRUE);
                break;
            }
            
            if ($_GET['print'])
            {
                $this->page->tpl = 'about/about.print.tpl';
                
                $this->view->setVar('contactPhone1', getVariableValue('contactPhone1'));
                
                /**
                 * распечатываем заказы в статусе "заказан"
                 */
                $sth = App::db()->prepare("SELECT
                        ub.`user_basket_id`,
                        ub.`user_basket_status`,
                        ub.`user_basket_date`,
                        ub.`user_basket_payment_type`,
                        ub.`user_basket_delivery_type`,
                        ub.`user_basket_delivery_cost`,
                        ub.`user_basket_payment_partical`,
                        u.`user_name`,
                        u.`user_email`,
                        u.`user_metro`,
                        u.`user_postal_code`,
                        u.`user_postal_address`,
                        u.`user_city`,
                        ub.`user_basket_date`
                    FROM 
                        `user_baskets` AS ub, `users` AS u
                    WHERE 
                            ub.`user_basket_status` = 'ordered'
                        AND ub.`user_id` = :user
                        AND u.`user_id`  = ub.`user_id`");
                
                $sth->execute(array(
                    'user' =>  $this->user->id,
                ));
                
                $baskets = $sth->fetchAll();
                
                foreach($baskets AS &$b)
                {
                    $totalPrice             = 0;
                    $totalPriceWithoutDisc  = 0;
                    $totalDiscount          = 0;
                    
                    // ЗАКАЗАННЫЕ ТОВАРЫ
                    $o = new \application\models\basket($b['user_basket_id']);
                    
                    foreach ($o->basketGoods as $g)
                    {
                        $g['userBasketGoodId'] = $g['user_basket_good_id'];
                        $g['styleId']          = $g['style_id'];
                        $g['styleName']        = stripslashes($g['style_name']) . '<br />' . styleId2styleSex($g['style_id'], true);
                        $g['size']             = $g['size_name'];
                        $g['price']            = $g['user_basket_good_price'];
                        $g['quantity']         = $g['user_basket_good_quantity'];
                        $g['size']             = $g['size_name'];
                        $g['goodName']         = stripslashes($g['good_name']);
                        
                        $price                  = ceil($g['price'] * (1 - $g['user_basket_good_discount'] / 100)) * $g['user_basket_good_quantity'];
                        $totalPriceWithoutDisc += $g['price'] * $g['user_basket_good_quantity'];
                        $totalDiscount         += ceil($g['price'] * $g['user_basket_good_quantity']) - $price;
            
                        $g['discount']   = round($g['user_basket_good_discount']);
                        $g['priceTotal'] = $price;
            
                        $totalPrice += $price;
            
                        $b['goods'][] = $g;
                    }
            
                    foreach ($o->basketGifts as $v)
                    {
                        $v['gift_name'] = stripslashes($v['gift_name']);
                        $b['gifts'][] = $v;
                        
                        $totalPrice += $v['priceTotal'];
                        $totalPriceWithoutDisc += $v['priceTotal'];
                    }
                    
                    $b['total'] = array(
                        'deliveryPrice' => intval($b['user_basket_delivery_cost']),
                        'totalPrice'    => ($totalPrice + $b['user_basket_delivery_cost']),
                        'totalPriceWithoutDisc' => $totalPriceWithoutDisc,
                        'totalDiscount' => $totalDiscount,
                        'particalPay'   => $b['user_basket_payment_partical']);
                }
            
                $this->view->setVar('BASKETS', $baskets);
                
                $this->page->index_tpl = 'index.popup.tpl';
            }

            $this->view->generate($this->page->index_tpl);
        }
    }