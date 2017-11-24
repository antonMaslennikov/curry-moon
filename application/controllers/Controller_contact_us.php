<?php
    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
    use \application\models\review AS review;
    use \application\models\basket AS basket;
    
    use \smashEngine\core\exception\appException AS appException; 
    
    use \PDO;
         
    class Controller_contact_us extends \smashEngine\core\Controller
    {
        public function action_index()
        {
            $this->page->tpl = 'contact_us/form.tpl';
            
            if ($_POST['data']) 
            {
                if (!isset($_POST['keystring']) || $_POST['keystring'] == trim($_SESSION['captcha_keystring']) || $this->user->id == 27278) 
                {
                    if ($_POST['data']['text'])
                    {
                        if (!empty($_POST['data']['name']) && !empty($_POST['data']['email']) && !empty($_POST['data']['phone']))
                        {
                            $_POST['data']['user_id'] = $this->user->id;
                            
                            try
                            {
                                $_POST['data']['city'] = $this->user->city;
                                
                                if ($_FILES['pic1'] && $_FILES['pic1']['error'] != 4) 
                                {
                                    $p = catchFile('pic1', UPLOADTODAY);
                                    
                                    if ($p['status'] != 'error') {
                                        $_POST['data']['pic1'] = $p['path'];
                                    } else {
                                        throw new appException($p['message']);
                                        
                                    }
                                }
                                
                                $r = new review;
                                $r->setAttributes($_POST['data']);
                                
                                $r->save();
                                
                                App::mail()->send(array(6199,), 619, array(
                                    'review' => $r,
                                    'order' => $_POST['order'],
                                    'user'  => $this->user,
                                ));
                                
                                $this->page->go('/' . $this->page->module . '/thankyou/');
                            }
                            catch(appException $e)
                            {
                                $this->view->setVar('error', $e->getMessage());
                            }   
                        }
                        else 
                        {
                            $this->view->setVar('error', 'Не все обязательные поля заполнены');
                        }
                    }
                    else
                    {
                        $this->view->setVar('error', 'Текст отзыва не заполнен');
                    }
                } else {
                   $this->view->setVar('error', 'Указан не правильный проверочный код');
                }
            }

            try
            {
                if ($_GET['order'])
                {
                    $o = new basket($_GET['order']);
                    
                    if (strpos($o->user->user_email, 'yandex') !== false || strpos($o->user->user_email, 'ya.ru') !== false) 
                    {
                        header('location: http://market.yandex.ru/shop/28508/reviews/add?retpath=http%3A%2F%2Fmarket.yandex.ru%2Fshop%2F28508%2Freviews%3Fclid%3D703');
                    }
                    
                    if (strpos($o->user->user_email, 'gmail.com') !== false || $this->user->id == $o->user_id) 
                    {
                      //header('location: https://plus.google.com/+MaryjaneRus/about');
                        $this->view->setVar('gmailForm', true);
                    }
                    
                    if (strpos($o->user->user_email, 'mail.ru') !== false) 
                    {
                        header('location: http://torg.mail.ru/review/shops/maryjane-cid3342/');
                    }
                }
                
                $this->page->tpl = 'contact_us/form.tpl';
                
                $this->page->import(array(
                    '/js/p/contact_us.js',
                    '/css/contact_us.css',
                ));
                
                $stars = array(0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0);
                    
                $foo = App::db()->query("SELECT t.`rating`, COUNT(*) AS c FROM `" . review::$dbtable . "` t WHERE t.`approved` = '1' AND t.`dealers` = '0' GROUP BY t.`rating`")->fetchALL(PDO::FETCH_KEY_PAIR);

                foreach ($stars AS $k => $v) {              
                    if ($foo[$k])
                        $stars[$k] = $foo[$k];
                    
                    if ($k != 0) {
                        $total_stars += $foo[$k];
                    }
                    
                    $sum_stars += $k * $foo[$k];
                    $total += $foo[$k];
                }
                
                
                
                $this->view->setVar('order', $o);
                $this->view->setVar('reviews', review::findAll(array('approved' => 1, 'visible' => 'mj', 'tpl' => str_replace('mailsender_', '', $_GET['utm_campaign']))));
                $this->view->setVar('tpl', str_replace('mailsender_', '', $_GET['utm_campaign']));
                $this->view->setVar('stars', array_reverse($stars, 1));
                $this->view->setVar('total', $total);
                $this->view->setVar('total_stars', $total_stars);
                $this->view->setVar('avg_stars', round($sum_stars / $total_stars, 1));
                    
                if ($fm = $this->page->getFlashMessage())
                {
                    $this->view->setVar('error', $fm);
                }
            }
            catch (Exception $e)
            {
                printr($e->getMessage());
            }
            
            $this->view->generate('index.tpl');
        }
        
        public function action_saveStars()
        {
            if ($this->user->meta->mjteam == 'super-admin')
            {
                try
                {
                    $r = new review($_POST['id']);
                    $r->rating = $_POST['rating'];
                    
                    $r->save();
                }
                catch (Exception $e)
                {
                    printr($e);
                }
            }
        }
        
        public function action_approve()
        {
            if ($this->user->meta->mjteam && $this->user->meta->mjteam != 'fired' && !empty($this->page->reqUrl[2])) 
            {
                try
                {
                    $r = new review($this->page->reqUrl[2]);
                    
                    if ($r->approved != 1)
                    {
                        $r->approved = 1;
                        
                        if ($this->page->reqUrl[3] == 'dealers') {
                            $r->visible = 'dealers';
                        }
                        
                        $r->save();
                        
                        App::mail()->send(array($r->email), 726, array('review' => $r));
                    }
                    
                    header('location: /' . $this->page->module . '/');
                    exit();
                }
                catch(Exception $e)
                {
                    printr($e);
                }   
            }
            else 
            {
                $this->page404();
            }
        }
        
        public function action_thankyou()
        {
            $this->page->tpl = 'contact_us/thankyou.tpl';
            $this->page->footer_tpl = 'order/footer.tpl';
            $this->page->ogUrl = '/';
            $this->page->ogPAGE_DESCRIPTION = 'Все принты нарисованы и выбраны вами. Тысячи принтов, от магазина с восьмилетним опытом. Молниеносная доставка футболок по всему миру. Только кастомные футболки, никаких повторений. Свежие принты по 790 рублей';
            $this->view->generate('index.tpl');
        }
    }