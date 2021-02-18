<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0a3d27740768738d8d258d190ac73adb
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
            $loader->prefixLengthsPsr4 = ComposerStaticInit0a3d27740768738d8d258d190ac73adb::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0a3d27740768738d8d258d190ac73adb::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
