<?php

declare(strict_types=1);

return [
    'ctrl' => [
        'title'         => 'LLL:EXT:mai_news/Resources/Private/Language/locallang_db.xlf:tx_mainews_domain_model_newscategory',
        'label'         => 'title',
        'tstamp'        => 'tstamp',
        'crdate'        => 'crdate',
        'delete'        => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields'  => 'title,description',
        'iconfile'      => 'EXT:mai_news/Resources/Public/Icons/tx_mainews_domain_model_newscategory.svg',
    ],
    'types' => [
        '1' => [
            'showitem' => 'title, description, slug, --div--;Access, hidden',
        ],
    ],
    'columns' => [
        'hidden' => [
            'exclude' => true,
            'label'   => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config'  => [
                'type'       => 'check',
                'renderType' => 'checkboxToggle',
                'items'      => [
                    [
                        'label'              => '',
                        'invertStateDisplay' => true,
                    ],
                ],
            ],
        ],
        'title' => [
            'label'  => 'LLL:EXT:mai_news/Resources/Private/Language/locallang_db.xlf:tx_mainews_domain_model_newscategory.title',
            'config' => [
                'type'     => 'input',
                'size'     => 50,
                'eval'     => 'trim',
                'required' => true,
            ],
        ],
        'description' => [
            'label'  => 'LLL:EXT:mai_news/Resources/Private/Language/locallang_db.xlf:tx_mainews_domain_model_newscategory.description',
            'config' => [
                'type' => 'text',
                'cols' => 60,
                'rows' => 5,
                'eval' => 'trim',
            ],
        ],
        'slug' => [
            'label'  => 'LLL:EXT:mai_news/Resources/Private/Language/locallang_db.xlf:tx_mainews_domain_model_newscategory.slug',
            'config' => [
                'type'              => 'slug',
                'generatorOptions'  => [
                    'fields'         => ['title'],
                    'fieldSeparator' => '-',
                    'prefixParentPageSlug' => false,
                ],
                'fallbackCharacter' => '-',
                'eval'              => 'uniqueInSite',
            ],
        ],
    ],
];
