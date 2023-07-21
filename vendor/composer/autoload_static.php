<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1154fe1fa2e998b0f30068a822c93e09
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'AlbuquerqueLucas\\UserTaskManager\\' => 33,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'AlbuquerqueLucas\\UserTaskManager\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1154fe1fa2e998b0f30068a822c93e09::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1154fe1fa2e998b0f30068a822c93e09::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit1154fe1fa2e998b0f30068a822c93e09::$classMap;

        }, null, ClassLoader::class);
    }
}
