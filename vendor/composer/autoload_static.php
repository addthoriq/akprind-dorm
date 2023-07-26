<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita7349a04024e8468872830430eb369a2
{
    public static $prefixLengthsPsr4 = array (
        'K' => 
        array (
            'Kang\\AkprindDorm\\' => 17,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Kang\\AkprindDorm\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita7349a04024e8468872830430eb369a2::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita7349a04024e8468872830430eb369a2::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInita7349a04024e8468872830430eb369a2::$classMap;

        }, null, ClassLoader::class);
    }
}