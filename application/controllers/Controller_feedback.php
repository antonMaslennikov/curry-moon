<?
    namespace application\controllers;
    
    use \smashEngine\core\App AS App;
    use \application\models\feedback AS feedback;
    use \Exception;
    
    class Controller_feedback extends \smashEngine\core\Controller
    {
        public function action_save()
        {
            $feedback = array();
        
            if ($this->user->authorized) 
                $feedback["userId"] = $this->user->id; 
            else
                $feedback["userId"] = 0;
    
            if (isset($_POST['name']) && !empty($_POST['name']))
                 $feedback["name"] = addslashes(substr(trim($_POST['name']),0,255));
            else
                 $feedback["name"] = '';
    
            if (isset($_POST['email']) && !empty($_POST['email'])) 
                $feedback["email"] = addslashes(substr(trim($_POST['email']),0,255));
            else 
                $error = 2;
    
            $feedback["subj"] = intval($_POST['subj']);
    
            if (isset($_POST['text']) && !empty($_POST['text'])) 
                $feedback["text"] = addslashes(trim($_POST['text']));
            else 
                $error = 3;
                
            $feddback['error'] = addslashes(trim($_POST['error']));
        
            if (!$this->user->authorized)
            {
                if (!isset($_SESSION['captcha_keystring']) || $_SESSION['captcha_keystring'] != trim($_POST['keystring'])) {
                    $error = 0;
                }
                    
                unset($_SESSION['captcha_keystring']);
            }
    
            if ($error !== 0 && $error !== 1 && $error !== 2 && $error !== 3) 
            {
                $feedback['text'] .= '<br /><br /><b>Город:</b> ' . $this->user->city;
                
                // вопрос по партнёрской программе
                if ($feedback['subj'] == 15 && $this->user->id > 0)
                {
                    $m = new \application\models\message;
                    $m->text = $feedback['text'];
                    $m->save();
                     
                    $m->setAdressat($this->user->id, 6199);
                    
                    App::mail()->send([6199], 178, array(
                        'from_id'    => $this->user->id,
                        'from_login' => stripslashes($this->user->user_login),
                        'user_login' => stripslashes($this->user->user_login),
                        'message_id' => $m->id,
                        'replay'     => nl2br(strip_tags(trim($_POST['text']))),
                        'user_avatar'=> userId2userGoodAvatar($this->user->id, 50, '', 1),
                        'date'       => datefromdb2textdate(date('Y-m-d H:i:s'), 1),
                        'referer'    => $_SERVER['HTTP_REFERER'],
                    ), '', '', 'noreplay@maryjane.ru');
                    
                    exit('ok:' . $m->id);
                }
                else 
                {
                    $feed = new \application\models\feedback;
                
                    $feed->feedback_name = $feedback["name"];
                    $feed->feedback_email = $feedback["email"];
                    $feed->feedback_topic = $feedback["subj"];
                    $feed->feedback_text = $feedback["text"];
                    $feed->feedback_user = $feedback["userId"];
                    $feed->feedback_error = $_SERVER['HTTP_REFERER'];
                    $feed->feedback_pict = intval($_POST['error_pict']);
                    $feed->feedback_webclient = addslashes($_SERVER['HTTP_USER_AGENT']);
                    $feed->basket_id = intval($_POST['feedbackbasket']);
                    
                    $feed->save();        
                    
                    exit('ok:' . $feed->id);    
                }
            }
            else { 
                exit('err:' . $error);
            }
        }

        public function action_upload()
        {
            $file   = 'error_pict';
            $result = catchFileNew('file', IMAGEUPLOAD . '/feedback/' . date('Y/m/d/'), '', 'png,gif,jpg,jpeg,jpe');
            exit(json_encode($result));
        }
        
        public function action_index()
        {
            if ($_GET['theme'] && feedback::$subjects[$_GET['theme']])
            {
                $this->view->setVar('selected_theme', $_GET['theme']);
            }
            
            if (strpos($_SERVER['HTTP_REFERER'], '/promo/') !== false)
            {
                $this->view->setVar('selected_theme', 15);
            }
        
            $this->page->tpl = 'feedback.tpl';
            //$this->page->index_tpl = 'index.popup.tpl';
            $this->page->noindex = true;
            
            $this->view->generate($this->page->tpl);
        }
    }