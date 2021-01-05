<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf8a026e2f5e1efc44fb3737106c5f9f2
{
    public static $files = array (
        'b6ec61354e97f32c0ae683041c78392a' => __DIR__ . '/..' . '/scrivo/highlight.php/HighlightUtilities/functions.php',
        'a4a119a56e50fbb293281d9a48007e0e' => __DIR__ . '/..' . '/symfony/polyfill-php80/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Polyfill\\Php80\\' => 23,
        ),
        'K' => 
        array (
            'Kirby\\' => 6,
            'KirbyExtended\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Polyfill\\Php80\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-php80',
        ),
        'Kirby\\' => 
        array (
            0 => __DIR__ . '/..' . '/getkirby/composer-installer/src',
        ),
        'KirbyExtended\\' => 
        array (
            0 => __DIR__ . '/../..' . '/classes/KirbyExtended',
        ),
    );

    public static $prefixesPsr0 = array (
        'H' => 
        array (
            'Highlight\\' => 
            array (
                0 => __DIR__ . '/..' . '/scrivo/highlight.php',
            ),
            'HighlightUtilities\\' => 
            array (
                0 => __DIR__ . '/..' . '/scrivo/highlight.php',
            ),
        ),
    );

    public static $classMap = array (
        'Attribute' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/Attribute.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Highlight\\Autoloader' => __DIR__ . '/..' . '/scrivo/highlight.php/Highlight/Autoloader.php',
        'Highlight\\HighlightResult' => __DIR__ . '/..' . '/scrivo/highlight.php/Highlight/HighlightResult.php',
        'Highlight\\Highlighter' => __DIR__ . '/..' . '/scrivo/highlight.php/Highlight/Highlighter.php',
        'Highlight\\JsonRef' => __DIR__ . '/..' . '/scrivo/highlight.php/Highlight/JsonRef.php',
        'Highlight\\Language' => __DIR__ . '/..' . '/scrivo/highlight.php/Highlight/Language.php',
        'Highlight\\Mode' => __DIR__ . '/..' . '/scrivo/highlight.php/Highlight/Mode.php',
        'Highlight\\ModeDeprecations' => __DIR__ . '/..' . '/scrivo/highlight.php/Highlight/ModeDeprecations.php',
        'Highlight\\RegEx' => __DIR__ . '/..' . '/scrivo/highlight.php/Highlight/RegEx.php',
        'Highlight\\RegExMatch' => __DIR__ . '/..' . '/scrivo/highlight.php/Highlight/RegExMatch.php',
        'Highlight\\RegExUtils' => __DIR__ . '/..' . '/scrivo/highlight.php/Highlight/RegExUtils.php',
        'Highlight\\Terminators' => __DIR__ . '/..' . '/scrivo/highlight.php/Highlight/Terminators.php',
        'KirbyExtended\\HTML5DOMDocument' => __DIR__ . '/../..' . '/classes/KirbyExtended/HTML5DOMDocument.php',
        'KirbyExtended\\HighlightAdapter' => __DIR__ . '/../..' . '/classes/KirbyExtended/HighlightAdapter.php',
        'Kirby\\ComposerInstaller\\CmsInstaller' => __DIR__ . '/..' . '/getkirby/composer-installer/src/ComposerInstaller/CmsInstaller.php',
        'Kirby\\ComposerInstaller\\Installer' => __DIR__ . '/..' . '/getkirby/composer-installer/src/ComposerInstaller/Installer.php',
        'Kirby\\ComposerInstaller\\Plugin' => __DIR__ . '/..' . '/getkirby/composer-installer/src/ComposerInstaller/Plugin.php',
        'Kirby\\ComposerInstaller\\PluginInstaller' => __DIR__ . '/..' . '/getkirby/composer-installer/src/ComposerInstaller/PluginInstaller.php',
        'Stringable' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/Stringable.php',
        'Symfony\\Polyfill\\Php80\\Php80' => __DIR__ . '/..' . '/symfony/polyfill-php80/Php80.php',
        'UnhandledMatchError' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/UnhandledMatchError.php',
        'ValueError' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/ValueError.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf8a026e2f5e1efc44fb3737106c5f9f2::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf8a026e2f5e1efc44fb3737106c5f9f2::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitf8a026e2f5e1efc44fb3737106c5f9f2::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitf8a026e2f5e1efc44fb3737106c5f9f2::$classMap;

        }, null, ClassLoader::class);
    }
}
