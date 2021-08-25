<?php

@include_once __DIR__ . '/vendor/autoload.php';

use Kirby\Cms\App as Kirby;
use KirbyExtended\HighlightAdapter;

Kirby::plugin('kirby-extended/highlighter', [
    'options' => [
        'class' => 'hljs',
        'autodetect' => false,
        'languages' => []
    ],
    'hooks' => [
        'kirbytext:after' => fn ($text) => HighlightAdapter::highlight($text)
    ]
]);
