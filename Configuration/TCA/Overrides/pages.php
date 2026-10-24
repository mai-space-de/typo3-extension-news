<?php

declare(strict_types=1);

defined('TYPO3') or die();

use Maispace\MaiBase\Backend\CustomPageModule\DoktypeIds;
use Maispace\MaiBase\TableConfigurationArray\Field;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\DatetimeConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\FileConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\NumberConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\SelectMultipleConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\TextConfig;
use Maispace\MaiBase\TableConfigurationArray\Helper;
use Maispace\MaiBase\TableConfigurationArray\Palette;

$lang = Helper::localLangHelperFactory('mai_news', 'Default/locallang_tca.xlf');
$newsDoktype = (string) DoktypeIds::NEWS;

(new Field('pages', 'tx_mainews_date', $lang('tx_mainews_news.date'), true))
    ->setConfig((new DatetimeConfig())->setFormat('date')->setRequired())
    ->registerField();

(new Field('pages', 'tx_mainews_teaser', $lang('tx_mainews_news.teaser'), true))
    ->setConfig(
        (new TextConfig())
            ->setRows(4)
            ->setCols(50)
            ->setEval('trim')
            ->enableRte()
            ->setRichtextConfiguration('default')
    )
    ->registerField();

(new Field('pages', 'tx_mainews_images', $lang('tx_mainews_news.images'), true))
    ->setConfig(
        (new FileConfig())
            ->setAllowed('common-image-types')
            ->setAppearance([
                'createNewRelationLinkTitle' => $lang('tx_mainews_news.images.addFile'),
                'enabledControls' => [
                    'info' => true,
                    'dragdrop' => true,
                    'sort' => true,
                    'hide' => true,
                    'delete' => true,
                ],
            ])
    )
    ->registerField();

(new Field('pages', 'tx_mainews_tags', $lang('tx_mainews_news.tags'), true))
    ->setConfig(
        (new SelectMultipleConfig())
            ->setForeignTable('tx_mainews_tag')
            ->setForeignTableWhere('ORDER BY tx_mainews_tag.name')
            ->setMm('tx_mainews_pages_tag_mm')
            ->setSize(7)
            ->setMinItems(0)
    )
    ->registerField();

// Migration idempotency marker (hidden from editors).
(new Field('pages', 'tx_mainews_legacy_news_uid', $lang('tx_mainews_news.legacy_news_uid'), true))
    ->setConfig((new NumberConfig())->setFormat('integer')->setDefault(0)->setReadOnly())
    ->registerField();

(new Palette('pages', 'tx_mainews_meta', $lang('palette.newsMeta')))
    ->override('tx_mainews_date, tx_mainews_images');

$newsTabs = ',--div--;' . $lang('tab.news')
    . ',--palette--;;tx_mainews_meta,'
    . 'tx_mainews_teaser,tx_mainews_tags';

$showitem = (string) ($GLOBALS['TCA']['pages']['types'][$newsDoktype]['showitem'] ?? '');
if ($showitem !== '' && !str_contains($showitem, 'tx_mainews_date')) {
    $GLOBALS['TCA']['pages']['types'][$newsDoktype]['showitem'] = preg_replace(
        '/(--palette--;;title)/',
        '$1' . $newsTabs,
        $showitem,
        1,
    ) ?? ($showitem . $newsTabs);
}

Helper::overrideColumns('pages', $newsDoktype, 'nav_hide', [
    'config' => [
        'default' => 1,
    ],
]);
