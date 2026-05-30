<?php

declare(strict_types=1);

return [
    \Maispace\MaiNews\Domain\Model\News::class => [
        'tableName' => 'tx_mainews_news',
    ],
    \Maispace\MaiNews\Domain\Model\Tag::class => [
        'tableName' => 'tx_mainews_tag',
    ],
];
