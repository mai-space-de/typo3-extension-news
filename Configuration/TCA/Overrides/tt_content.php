<?php

declare(strict_types=1);

defined('TYPO3') or die();

use Maispace\MaiBase\TableConfigurationArray\Helper;

$lang = Helper::localLangHelperFactory('mai_news', 'Default/locallang_tca.xlf');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'MaiNews',
    'List',
    $lang('plugin.list.title'),
    'mai-content',
    'maispace_plugins_lists',
    '',
    'FILE:EXT:mai_news/Configuration/FlexForms/NewsPlugin.xml',
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'MaiNews',
    'Rss',
    $lang('plugin.rss.title'),
    'mai-content',
    'maispace_plugins_lists',
    '',
    'FILE:EXT:mai_news/Configuration/FlexForms/RssPlugin.xml',
);
