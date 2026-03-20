<?php

declare(strict_types=1);

return [
    'ctrl' => [
        'title'          => 'LLL:EXT:mai_news/Resources/Private/Language/locallang_db.xlf:tx_mainews_domain_model_newsarticle',
        'label'          => 'title',
        'label_alt'      => 'publish_date',
        'label_alt_force' => true,
        'sortby'         => 'sorting',
        'tstamp'         => 'tstamp',
        'crdate'         => 'crdate',
        'delete'         => 'deleted',
        'enablecolumns'  => [
            'disabled'  => 'hidden',
            'starttime' => 'starttime',
            'endtime'   => 'endtime',
        ],
        'searchFields'   => 'title,teaser,body_text,author,tags',
        'iconfile'       => 'EXT:mai_news/Resources/Public/Icons/tx_mainews_domain_model_newsarticle.svg',
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --div--;General,
                    title, teaser, publish_date, author, image,
                --div--;Content,
                    body_text,
                --div--;Classification,
                    categories, tags, slug,
                --div--;Access,
                    hidden, starttime, endtime
            ',
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
        'starttime' => [
            'exclude' => true,
            'label'   => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config'  => [
                'type'       => 'datetime',
                'default'    => 0,
            ],
            'l10n_mode' => 'exclude',
        ],
        'endtime' => [
            'exclude' => true,
            'label'   => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config'  => [
                'type'    => 'datetime',
                'default' => 0,
                'range'   => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2106),
                ],
            ],
            'l10n_mode' => 'exclude',
        ],
        'title' => [
            'label'  => 'LLL:EXT:mai_news/Resources/Private/Language/locallang_db.xlf:tx_mainews_domain_model_newsarticle.title',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'teaser' => [
            'label'  => 'LLL:EXT:mai_news/Resources/Private/Language/locallang_db.xlf:tx_mainews_domain_model_newsarticle.teaser',
            'config' => [
                'type' => 'text',
                'cols' => 60,
                'rows' => 5,
                'eval' => 'trim',
            ],
        ],
        'body_text' => [
            'label'  => 'LLL:EXT:mai_news/Resources/Private/Language/locallang_db.xlf:tx_mainews_domain_model_newsarticle.body_text',
            'config' => [
                'type'                  => 'text',
                'enableRichtext'        => true,
                'richtextConfiguration' => 'default',
                'cols'                  => 60,
                'rows'                  => 20,
            ],
        ],
        'author' => [
            'label'  => 'LLL:EXT:mai_news/Resources/Private/Language/locallang_db.xlf:tx_mainews_domain_model_newsarticle.author',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim',
            ],
        ],
        'publish_date' => [
            'label'  => 'LLL:EXT:mai_news/Resources/Private/Language/locallang_db.xlf:tx_mainews_domain_model_newsarticle.publish_date',
            'config' => [
                'type'    => 'datetime',
                'default' => 0,
            ],
        ],
        'tags' => [
            'label'  => 'LLL:EXT:mai_news/Resources/Private/Language/locallang_db.xlf:tx_mainews_domain_model_newsarticle.tags',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim',
            ],
        ],
        'image' => [
            'label'  => 'LLL:EXT:mai_news/Resources/Private/Language/locallang_db.xlf:tx_mainews_domain_model_newsarticle.image',
            'config' => [
                'type'     => 'file',
                'allowed'  => 'common-image-types',
                'maxitems' => 5,
            ],
        ],
        'categories' => [
            'label'  => 'LLL:EXT:mai_news/Resources/Private/Language/locallang_db.xlf:tx_mainews_domain_model_newsarticle.categories',
            'config' => [
                'type'                => 'select',
                'renderType'          => 'selectMultipleSideBySide',
                'foreign_table'       => 'tx_mainews_domain_model_newscategory',
                'foreign_table_where' => 'ORDER BY tx_mainews_domain_model_newscategory.title ASC',
                'MM'                  => 'tx_mainews_article_category_mm',
                'size'                => 5,
                'maxitems'            => 99,
            ],
        ],
        'slug' => [
            'label'  => 'LLL:EXT:mai_news/Resources/Private/Language/locallang_db.xlf:tx_mainews_domain_model_newsarticle.slug',
            'config' => [
                'type'          => 'slug',
                'generatorOptions' => [
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
