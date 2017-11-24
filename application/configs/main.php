<?php

    define('appMode', 'dev');
    //define('appMode', 'producton');
    
    define('adminEmail', 'anton.maslennikov@gmail.com');
    
    define('AppDomain', '.shop.loc');

    // DB
    define('DBHOST', 'localhost');
    define('DBNAME', 'shop.loc');
    define('DBUSER', 'shop');
    define('DBPASS', 'root');
    define('DB_CHARSET', 'utf-8');
    define('DB_PREFIX', '');
    
    // ADMIN
    define('ADMIN_login', 'admin');
    define('ADMIN_password', 'admin');
    
    // DIRECTORY SEPARATOR
    define('DS', DIRECTORY_SEPARATOR);
    
    // Paths
    define('UPLOADTODAY', DS . 'public' . DS . 'uploaded' . date(DS . 'Y' . DS . 'm' . DS . 'd' . DS));
    
    define('ROOTDIR', DS . 'home' . DS . 'vhosts' . DS . 'www.maryjane.ru' . DS . 'public_html');
    
    define('mainUrl',     'http://www.shop.loc');
    
    define('SALT', 'SALT');
    define('CSRF_SALT', 'CSRF_SALT');
    
	// Date
    define('NOW',     date('Y-m-d G:i:s'));
    define('NOWDATE', date('Y-m-d'));
    
    define('siteMETA', '');
    define('sideDESCRIPTION', '');
    
    define('LOG_FILE_PATH', '');