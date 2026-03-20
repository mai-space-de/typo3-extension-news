<?php

$EM_CONF[$_EXTKEY] = [
    'title'            => 'News / Aktuelles',
    'description'      => 'News extension with categories, tags, RSS feed, and FAL image support.',
    'category'         => 'plugin',
    'author'           => 'MaiSpace',
    'author_email'     => '',
    'state'            => 'stable',
    'version'          => '1.0.0',
    'constraints'      => [
        'depends' => [
            'typo3' => '12.0.0-12.99.99',
        ],
        'conflicts' => [],
        'suggests'  => [],
    ],
];
