<?php
    require_once 'configs/main.php';
    
	require '/vendor/autoload.php';
    
    // регистрируем класс обработки ошибок
    (new smashEngine\core\errorHandler())->register();
    
    smashEngine\core\App::run();