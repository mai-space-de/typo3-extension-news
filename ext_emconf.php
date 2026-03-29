<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Mai News',
    'description' => 'News / Aktuelles extension with categories, tags, RSS feed, and FAL image support. Categories use TYPO3 `sys_category`, sharing the same tree as `mai_gallery`, `mai_faq`, and `mai_timeline`.',
    'category' => 'module',
    'author' => 'Maispace',
    'author_email' => '',
    'state' => 'stable',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-14.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
