<?php

return [
    'code' => [
        'attr' => [
            'lang',
            'language',
        ],
        'html' => function($tag) {
            $code = $tag->value;
            $language = $tag->lang ?? $tag->language;
            $block = new \Kirby\Cms\Block([
                'type' => 'code',
                'content' => [
                    'language' => $language ?? 'plaintext',
                    'code' => is_base64_string_s($code) ? base64_decode($code) : $code,
                ]
            ]);

            return snippet('blocks/code', ['block' => $block], true);
        }
    ]
];
