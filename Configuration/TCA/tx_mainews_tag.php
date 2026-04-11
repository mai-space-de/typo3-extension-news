<?php

declare(strict_types=1);

use Maispace\MaiBase\TableConfigurationArray\Helper;
use Maispace\MaiBase\TableConfigurationArray\Table;

$lang = Helper::localLangHelperFactory('mai_news', 'Default/locallang_tca.xlf');

return (new Table($lang('table.tx_mainews_tag')))
    ->setDefaultConfig()
    ->setLabel('name')
    ->setSearchFields('name')
    ->setIconFile('EXT:mai_news/Resources/Public/Icons/tx_mainews_tag.svg')
    ->setSortingField()
    ->addColumn(
        'name',
        $lang('tx_mainews_tag.name'),
        ['type' => 'input', 'size' => 30, 'max' => 100, 'eval' => 'trim,required,unique']
    )
    ->addTypeShowItem(
        '0',
        'name,
        --div--;' . $lang('tab.language') . ', --palette--;;language,
        --div--;' . $lang('tab.access') . ', --palette--;;hidden'
    )
    ->getConfig();
