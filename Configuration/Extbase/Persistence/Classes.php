<?php

declare(strict_types=1);

return [
    \Maispace\MaiNews\Domain\Model\News::class => [
        'tableName' => 'pages',
        'properties' => [
            'title' => [
                'fieldName' => 'title',
            ],
            'teaser' => [
                'fieldName' => 'tx_mainews_teaser',
            ],
            // Body lives in tt_content after cutover; kept on the model for BC/tests.
            'date' => [
                'fieldName' => 'tx_mainews_date',
            ],
            'slug' => [
                'fieldName' => 'slug',
            ],
            'images' => [
                'fieldName' => 'tx_mainews_images',
            ],
            'tags' => [
                'fieldName' => 'tx_mainews_tags',
            ],
            'doktype' => [
                'fieldName' => 'doktype',
            ],
            // categories uses the core pages.categories MM (tablenames=pages).
        ],
    ],
    \Maispace\MaiNews\Domain\Model\Tag::class => [
        'tableName' => 'tx_mainews_tag',
    ],
];
