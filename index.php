<?php

@include_once __DIR__ . '/vendor/autoload.php';

\Kirby\Cms\App::plugin('kirby-extended/highlighter', [
    'hooks' => [
        'kirbytext:after' => fn ($text) => \KirbyExtended\HighlightAdapter::highlight($text)
    ]
]);
