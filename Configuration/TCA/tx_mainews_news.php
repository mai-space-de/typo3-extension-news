<?php

declare(strict_types=1);

use Maispace\MaiBase\TableConfigurationArray\Helper;
use Maispace\MaiBase\TableConfigurationArray\Table;

$lang = Helper::localLangHelperFactory('mai_news', 'Default/locallang_tca.xlf');

return (new Table($lang('table.tx_mainews_news')))
    ->setDefaultConfig()
    ->setLabel('title')
    ->setSearchFields('title, teaser, body')
    ->setIconFile('EXT:mai_news/Resources/Public/Icons/tx_mainews_news.svg')
    ->setDefaultSorting('ORDER BY date DESC')
    ->setThumbnailField('images')
    ->addColumn(
        'title',
        $lang('tx_mainews_news.title'),
        ['type' => 'input', 'size' => 50, 'max' => 255, 'eval' => 'trim,required']
    )
    ->addColumn(
        'teaser',
        $lang('tx_mainews_news.teaser'),
        ['type' => 'text', 'rows' => 4, 'cols' => 50, 'eval' => 'trim']
    )
    ->addColumn(
        'body',
        $lang('tx_mainews_news.body'),
        [
            'type' => 'text',
            'rows' => 15,
            'cols' => 50,
            'enableRichtext' => true,
            'richtextConfiguration' => 'default',
        ]
    )
    ->addColumn(
        'date',
        $lang('tx_mainews_news.date'),
        ['type' => 'datetime', 'format' => 'date', 'eval' => 'required']
    )
    ->addColumn(
        'images',
        $lang('tx_mainews_news.images'),
        [
            'type' => 'file',
            'allowed' => 'common-image-types',
            'appearance' => [
                'createNewRelationLinkTitle' => $lang('tx_mainews_news.images.addFile'),
                'enabledControls' => ['info' => true, 'dragdrop' => true, 'sort' => true, 'hide' => true, 'delete' => true],
            ],
        ]
    )
    ->addColumn(
        'categories',
        $lang('tx_mainews_news.categories'),
        ['type' => 'category']
    )
    ->addColumn(
        'tags',
        $lang('tx_mainews_news.tags'),
        [
            'type' => 'select',
            'renderType' => 'selectMultipleSideBySide',
            'foreign_table' => 'tx_mainews_tag',
            'foreign_table_where' => 'ORDER BY tx_mainews_tag.name',
            'MM' => 'tx_mainews_news_tag_mm',
            'size' => 7,
            'minitems' => 0,
        ]
    )
    ->addTypeShowItem(
        '0',
        'title, date, teaser, body, images,
        --div--;' . $lang('tab.relations') . ', categories, tags,
        --div--;' . $lang('tab.language') . ', --palette--;;language,
        --div--;' . $lang('tab.access') . ', --palette--;;hidden, --palette--;;access'
    )
    ->getConfig();
