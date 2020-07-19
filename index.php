<?php

@include_once __DIR__ . '/vendor/autoload.php';

use Kirby\Cms\App as Kirby;
use KirbyExtended\HighlightAdapter;

Kirby::plugin('johannschopplich/highlight', [
    'options' => [
        'class' => 'hljs',
        'autolanguages' => [],
        'autodetect' => false,
        'escape' => false
    ],
    'hooks' => [
        'kirbytext:after' => function (?string $text) {
            return HighlightAdapter::highlight($text);
        }
    ]
]);
