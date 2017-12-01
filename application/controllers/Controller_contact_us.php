<?php
    namespace application\controllers;
    
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
                    
                    $f = new feedback;

                    $f->setAttributes($_POST['jform']);

                    $f->save();
                    
                    // отправить копию письма на свой же адрес
                    if ($_POST['jform']['contact_email_copy']) 
                    {
                        
                    }
                    
                    $this->page->refresh();
                }
                catch (appException $e)
                {
                    
                }
            }
            
            $this->view->generate('index.tpl'); 
        }
    }