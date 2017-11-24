<?
    namespace smashEngine\core;

    /**
     * Обработчик ошибок и исключений PHP
     */
    class errorHandler {
        
        public function register() {
            
            ini_set('display_errors', true);
            
            set_error_handler([$this, 'errorHandler'], E_ALL & ~E_NOTICE);
            register_shutdown_function([$this, 'fatalErrorHandler']);
            set_exception_handler([$this, 'exceptionHandler']);
        }
        
        /**
         * метод обработчик ошибки
         * @param int $errno номер ошибки
         * @param string $errstr текст ошибки
         * @param string $errfile файл содержащий ошибку
         * @param int $errline номер строки
         */
        public function errorHandler($errno, $errstr, $errfile, $errline)
        {
            // игнорируем ворнинги и нотисы
            if (E_WARNING == $errno || E_NOTICE == $errno || E_DEPRECATED == $errno)
                return;
            
            $this->showError($errno, $errstr, $errfile, $errline);
            
            // если не вернуть false штатный обработчик ошибок не будет обрабатывать ошибку 
            //return false;
        }
        
        /**
         * Метод для перехвата фатальных ошибок
         */
        public function fatalErrorHandler()
        {
            $error = error_get_last();
            
            if (in_array($error['type'], [E_ERROR, E_PARSE, E_COMPILE_ERROR, E_CORE_ERROR])) {
                ob_get_clean();
                $this->showError($error['type'], $error['message'], $error['file'], $error['line']);
            }
            
            // если не вернуть false штатный обработчик ошибок не будет обрабатывать ошибку
            //return false;
        }
        
        /**
         * Метод для перехвата необработанных исключений
         */
        public function exceptionHandler(\Exception $e)
        {
            $this->showError(get_class($e) . ' (code: ' . $e->getCode() . ')', $e->getMessage() . '<hr>' . $e->getTraceAsString() . '<hr>' . json_encode($e->getTrace()), $e->getFile(), $e->getLine());
            // если не вернуть false штатный обработчик ошибок не будет обрабатывать ошибку
            //return false;
        }
        
        /**
         * Отобразить ошибку
         */
        public function showError($errno, $errstr, $errfile, $errline, $status = 500)
        {
            // статус вернуть не получится так как смарти к этому моменту уэе начнёт вывод
            //header('HTTP/1.1 ' . $status);
            
            if (appMode == 'dev') {
                print_r('<div style="border:1px solid #ccc;margin-bottom:20px;padding:10px;">Catch error: ' . $errstr . '<hr> Номер ошибки: ' . $errno . '<hr> Файл: ' . $errfile . ' (строка ' . $errline . ')' . "</div>");
            } else {
                
                $headers  = "From: Maryjane.ru <info@maryjane.ru>\n";
                $headers .= "Content-type: text/html; charset=utf-8 \n";
                $headers .= "Reply-To: <info@maryjane.ru>\n";
                $headers .= "Return-Path: <info@maryjane.ru>\n";
                $headers .= "X-Mailer: PHP/" . phpversion() . "\n";
                
                $message  = '<div style="border:1px solid #ccc;margin-bottom:20px;padding:10px;">';
                $message .= 'Catch error: ' . $errstr . '<hr> Номер ошибки: ' . $errno
                         . '<hr> Файл: ' . $errfile . ' (строка ' . $errline . ')' 
                         . '<hr>Адрес: ' . $_SERVER['REQUEST_URI']
                         . '<hr>Реферер: ' . $_SERVER['HTTP_REFERER'] 
                     . '</div>';
                
                mail(adminEmail, ('=?UTF-8?B?' . base64_encode('Ошибка на сайте') . '?='), $message, $headers);
                
                //print_r('Показать 403ю наверное если фатал еррор');
            }
        }
    }
    