<?php
    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
    
    use \Exception;
        
    class Controller_faq extends \smashEngine\core\Controller
    {
        public function action_index()
        {
            $this->page->breadcrump[] = array(
                'link' => '/faq/',
                'caption'=> 'Помощь');
            
            $PD[] = 'Помощь';
            $PD[] = 'Maryjane.ru магазин дизайнерских футболок №1. Все принты нарисованы и выбраны Вами!';
            
            if (!empty($this->page->reqUrl[1]) && !in_array($this->page->reqUrl[1], array('group', 'search', 'designers', 'buyers', 'partners', 'users')))
            {
                $this->page->noindex = TRUE;
                $this->page->tpl = 'faq/popup.tpl';
                $this->page->index_tpl = 'index.popup.tpl';
            
                $pos = strpos($this->page->reqUrl[1],'group,');
                
                try
                {
                    if ($pos !== false) {
                        $group = trim(str_replace('group,', '', trim($this->page->reqUrl[1])));
                        $r = App::db()->query("SELECT `title`, `text` FROM `faq` WHERE `group` IN (" . addslashes($group) . ") AND `visible` = '1'");
                    } else {
                        $r = App::db()->query("SELECT `title`, `text` FROM `faq` WHERE `id` IN (" . addslashes($this->page->reqUrl[1]) . ") AND `visible` = '1'");
                    }
                
                    $faq = array();
                
                    foreach ($r->fetchAll() AS $row) {
                        $titles[] = $row['title'] = stripslashes($row['title']);
                        $row['text']  = stripslashes($row['text']);
                        
                        $faq[] = $row;
                    }
                }
                catch (Exception $e)
                {
                    printr($e);
                }
                
                $this->view->setVar('FAQ', $faq);
                $this->view->setVar('PAGE_TITLE', 'F.A.Q. ' . implode(',', $titles));
                $this->view->setVar('content_tpl', $this->page->tpl);
            }
            elseif (!empty($this->page->reqUrl[1]) && $this->page->reqUrl[1] == 'users')
            {
                $this->page->tpl = 'faq/users.tpl';
                
                if ($_POST['save'] && ($this->user->meta->mjteam == 'super-admin' || $this->user->id == 250919))
                {
                    $m = new \application\models\message($_POST['id']);
                    $m->text = $_POST['text'];
                    $m->save();
                    
                    $this->page->refresh();
                }
                
                $sth = App::db()->query("SELECT m1.`id` AS question_id, m1.`text` AS question, m2.`id` AS answer_id, m2.`text` AS answer
                                         FROM `faq__feedback` ff, `messages` m1, `messages` m2 
                                         WHERE 
                                                ff.`message_id` = m1.`id`
                                            AND ff.`answer_id` = m2.`id`
                                            AND ff.`active` > '0'
                                         ORDER BY ff.`id` DESC");
                                            
                foreach ($sth->fetchAll() as $row) {
                    $row['question'] = stripslashes($row['question']);
                    $row['answer'] = stripslashes($row['answer']);
                    $messages[] = $row;
                }
                
                $this->view->setVar('messages', $messages);
            }
            else
            {
                if (($this->page->reqUrl[1] == '0' || $this->page->reqUrl[1] == '') && $this->page->reqUrl[2]) {
                    $this->page404();
                }
                
                if ($this->page->reqUrl[1] == 'designers') {
                    $this->page->title .= ', дизайнерам';
                    $PD[] = 'дизайнерам';
                } elseif ($this->page->reqUrl[1] == 'partners') { 
                    $this->page->title .= ', партнёрам';
                    $PD[] = 'партнёрам';
                }
                    
                if ($this->page->reqUrl[1] == 'search')
                {
                    $search = addslashes(trim($_GET['q']));
                    
                    $aq[] = "(f.`title` LIKE '%" . $search . "%' OR f.`text`  LIKE '%" . $search . "%')";
                    
                    $this->view->setVar('search', TRUE);
                    $this->page->noindex = TRUE;
                }
                
                if ($this->page->reqUrl[1] != 'group')
                {
                    if ($this->page->reqUrl[1] == 'designers')
                        $aq[] = "fg.`audience` IN ('all', 'designers')";
                    elseif ($this->page->reqUrl[1] == 'partners')
                        $aq[] = "fg.`audience` IN ('partners')";
                    else    
                        $aq[] = "fg.`audience` IN ('all', 'buyers')";
                }
                
                if ($this->page->reqUrl[1] == 'group') {
                    $fg = intval($this->page->reqUrl[2]);
                    $this->view->setVar('fgroup', $fg);
                }
                        
                if ($this->page->reqUrl[3] == 'view') {
                    $id = intval($this->page->reqUrl[4]);
                    $this->view->setVar('fid', $id);
                }
                
                $this->page->tpl = 'faq/index.tpl';
                $this->page->sidebar_tpl = 'faq/sidebar.tpl';
            
                $sth = App::db()->query("SELECT f.`id`, f.`title`,f.`text`,f.`group`, fg.`name`, fg.`slug` 
                           FROM `faq` AS f, `faq_group` AS fg 
                           WHERE 
                                    f.`group` = fg.`id` 
                                AND (fg.`visible_in` = 'all' || fg.`visible_in` = 'main') 
                                AND f.`visible` = '1' 
                                " . (count($aq) > 0 ? ' AND ' . implode(' AND ', $aq) : '') . "
                          ORDER BY fg.`order`, f.`order`");
                
                $faq = array();
               
                foreach ($sth->fetchAll() AS $row)
                {
                    $row['title'] = stripslashes($row['title']); 
                    $row['text']  = stripslashes($row['text']); 
                    
                    $faq[$row['group']]['items'][$row['id']] = $row;
                    $faq[$row['group']]['name'] = $row['name'];
                    $faq[$row['group']]['slug'] = $row['slug'];
                    
                    $faq_title[$row['group']]['items'][$row['id']] = $row;
                    $faq_title[$row['group']]['name'] = $row['name'];
                    $faq_title[$row['group']]['slug'] = $row['slug'];
                }
               
                //printr($faq);
               
                if (!empty($fg) && $faq[$fg])
                {
                    $faq = array($fg => $faq[$fg]);
                    
                    $this->page->breadcrump[] = array(
                        'link' => '/faq/group/' . $fg . '/',
                        'caption'=> $faq[$fg]['name']);     
                    
                    if (!empty($id))
                    {
                        $items = array($id => $faq[$fg]['items'][$id]);
                        $faq = array($fg => $faq[$fg]);
                        unset($faq['items']);
                        $faq[$fg]['items'] = $items;
                        
                        $this->page->breadcrump[] = array(
                            'link' => '/faq/',
                            'caption'=> $faq[$fg]['items'][$id]['title']);      
                    }
                }
            
                $this->view->setVar('faq', $faq);
                $this->view->setVar('faq_title', $faq_title);
            }
            
            if (count($PD) > 0)
                $this->page->description = implode(', ', $PD);
                
            $this->view->generate($this->page->index_tpl ? $this->page->index_tpl : 'index.tpl');
        }
    }