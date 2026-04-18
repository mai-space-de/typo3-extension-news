<?php

declare(strict_types=1);

use Maispace\MaiBase\TableConfigurationArray\FieldConfig\InputConfig;
use Maispace\MaiBase\TableConfigurationArray\Helper;
use Maispace\MaiBase\TableConfigurationArray\Table;

$lang = Helper::localLangHelperFactory('mai_news', 'Default/locallang_tca.xlf');

return (new Table($lang('table.tx_mainews_tag')))
    ->setDefaultConfig()
    ->setLabel('name')
    ->setIconFile('EXT:mai_news/Resources/Public/Icons/tx_mainews_tag.svg')
    ->setSortingField()
    ->addColumn(
        'name',
        $lang('tx_mainews_tag.name'),
        (new InputConfig())->setSize(30)->setMax(100)->setEval('trim,unique')->setRequired()
    )
    ->addTypeShowItem(
        '0',
        'name,
        --div--;' . $lang('tab.language') . ', --palette--;;language,
        --div--;' . $lang('tab.access') . ', --palette--;;hidden'
    )
    ->getConfig();
