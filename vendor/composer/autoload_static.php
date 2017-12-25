<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb40310e37915b0c32370293f384e069c
{
    public static $files = array (
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        'f084d01b0a599f67676cffef638aa95b' => __DIR__ . '/..' . '/smarty/smarty/libs/bootstrap.php',
        'b7dfd3648f7f2dac8e65dda31293edcf' => __DIR__ . '/..' . '/functions/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        's' => 
        array (
            'smashEngine\\' => 12,
        ),
        'a' => 
        array (
            'application\\' => 12,
            'admin\\application\\' => 18,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Component\\Debug\\' => 24,
            'Symfony\\Component\\Console\\' => 26,
        ),
        'R' => 
        array (
            'Routing\\' => 8,
            'ReCaptcha\\' => 10,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'smashEngine\\' => 
        array (
            0 => __DIR__ . '/..' . '/smashEngine',
        ),
        'application\\' => 
        array (
            0 => __DIR__ . '/../..' . '/application',
        ),
        'admin\\application\\' => 
        array (
            0 => __DIR__ . '/../..' . '/admin/application',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Component\\Debug\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/debug',
        ),
        'Symfony\\Component\\Console\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/console',
        ),
        'Routing\\' => 
        array (
            0 => __DIR__ . '/..' . '/itlessons/php-routing/src/Routing',
        ),
        'ReCaptcha\\' => 
        array (
            0 => __DIR__ . '/..' . '/google/recaptcha/src/ReCaptcha',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'I' => 
        array (
            'Imagine' => 
            array (
                0 => __DIR__ . '/..' . '/imagine/imagine/lib',
            ),
        ),
    );

    public static $classMap = array (
        'Geo' => __DIR__ . '/..' . '/classes/Geo.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb40310e37915b0c32370293f384e069c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb40310e37915b0c32370293f384e069c::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitb40310e37915b0c32370293f384e069c::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitb40310e37915b0c32370293f384e069c::$classMap;

        }, null, ClassLoader::class);
    }
}
