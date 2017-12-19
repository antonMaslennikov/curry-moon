<?php
    namespace application\controllers;
    
    use \smashEngine\core\App;
    use \smashEngine\core\exception\appException;
    use \application\models\feedback;

    class Controller_contact_us extends Controller_
    {
        public function action_index()
        {
            $this->page->tpl = 'contact_us/index.tpl';
            $this->page->addBreadCrump('Контакты');
            $this->page->title = 'Контакты';
            
            if ($_POST['jform'])
            {
                try
                {
                    if ($_POST['csrf_token'] != $_SESSION['csrf_token']) {
                        throw new appException('Ошибка при проверке токена', 1);
                    }
                    
                    if (time() - $_SESSION['feedback_accepted'] < 60) {
                        throw new appException('Вы отправляете сообщения слишком часто', 2);
                    }
                    
                    $f = new feedback;

                    $f->setAttributes($_POST['jform']);

                    $f->save();
                    
                    // отправить копию письма на свой же адрес
                    if ($_POST['jform']['contact_email_copy']) 
                    {
                        App::mail()->send($_POST['jform']['feedback_email'], 19, ['message' => $f]);
                    }
                    
                    $_SESSION['feedback_accepted'] = time();
                    
                    $this->page->setFlashSuccess('Ваш вопрос отправлен. Скоро Вам ответят. Спасибо.');
                    
                    $this->page->refresh();
                }
                catch (appException $e)
                {
                    $this->page->setFlashMessage($e->getMessage());
                }
            }
            
            if ($_SESSION['feedback_accepted']) {
                $this->view->setVar('feedback_accepted', true);
            }
            
            $this->view->generate('index.tpl'); 
        }
    }