<?php

use Kirby\Cms\App;

return [
    'code' => [
        'attr' => [
            'lang',
            'language',
        ],
        // TODO: Type as `\Kirby\Text\KirbyTag` for Kirby 4
        'html' => function ($tag) {
            $kirby = App::instance();
            $code = $tag->value;
            $language = $tag->lang ?? $tag->language;
            $block = new \Kirby\Cms\Block([
                'type' => 'code',
                'content' => [
                    'language' => $language ?? 'plaintext',
                    'code' => is_base64_string_s($code) ? base64_decode($code) : $code,
                ]
            ]);

            return $kirby->snippet('blocks/code', ['block' => $block], true);
        }
    ]
];
