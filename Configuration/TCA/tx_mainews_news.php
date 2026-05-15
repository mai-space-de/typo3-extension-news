<?php

declare(strict_types=1);

use Maispace\MaiBase\TableConfigurationArray\FieldConfig\CategoryConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\DatetimeConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\FileConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\InputConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\SelectMultipleConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\TextConfig;
use Maispace\MaiBase\TableConfigurationArray\Helper;
use Maispace\MaiBase\TableConfigurationArray\Table;

$lang = Helper::localLangHelperFactory('mai_news', 'Default/locallang_tca.xlf');

return (new Table($lang('table.tx_mainews_news')))
    ->setDefaultConfig()
    ->setLabel('title')
    ->setIconFile('EXT:mai_base/Resources/Public/Icons/generic_table.svg')
    ->setDefaultSorting('ORDER BY date DESC')
    ->setThumbnailField('images')
    ->addColumn(
        'title',
        $lang('tx_mainews_news.title'),
        (new InputConfig())->setSize(50)->setMax(255)->setEval('trim')->setRequired()
    )
    ->addColumn(
        'teaser',
        $lang('tx_mainews_news.teaser'),
        (new TextConfig())->setRows(4)->setCols(50)->setEval('trim')
    )
    ->addColumn(
        'body',
        $lang('tx_mainews_news.body'),
        (new TextConfig())->setRows(15)->setCols(50)->enableRte()->setRichtextConfiguration('default')
    )
    ->addColumn(
        'date',
        $lang('tx_mainews_news.date'),
        (new DatetimeConfig())->setFormat('date')->setRequired()
    )
    ->addColumn(
        'images',
        $lang('tx_mainews_news.images'),
        (new FileConfig())
            ->setAllowed('common-image-types')
            ->setAppearance([
                'createNewRelationLinkTitle' => $lang('tx_mainews_news.images.addFile'),
                'enabledControls' => ['info' => true, 'dragdrop' => true, 'sort' => true, 'hide' => true, 'delete' => true],
            ])
    )
    ->addColumn(
        'categories',
        $lang('tx_mainews_news.categories'),
        new CategoryConfig()
    )
    ->addColumn(
        'tags',
        $lang('tx_mainews_news.tags'),
        (new SelectMultipleConfig())
            ->setForeignTable('tx_mainews_tag')
            ->setForeignTableWhere('ORDER BY tx_mainews_tag.name')
            ->setMm('tx_mainews_news_tag_mm')
            ->setSize(7)
            ->setMinItems(0)
    )
    ->addTypeShowItem(
        '0',
        'title, date, teaser, body, images,
        --div--;' . $lang('tab.relations') . ', categories, tags,
        --div--;' . $lang('tab.language') . ', --palette--;;language,
        --div--;' . $lang('tab.access') . ', --palette--;;hidden, --palette--;;access'
    )
    ->getConfig();
