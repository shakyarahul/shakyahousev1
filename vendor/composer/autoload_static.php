<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit14a20e89658863e2dbb9b9aedc073019
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit14a20e89658863e2dbb9b9aedc073019::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit14a20e89658863e2dbb9b9aedc073019::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
