<?php
    require_once 'configs/main.php';
    
	require '/vendor/autoload.php';
    
    // регистрируем класс обработки ошибок
	$handler = new smashEngine\core\errorHandler();
	$handler->register();
    
    smashEngine\core\App::run(new application\routings);