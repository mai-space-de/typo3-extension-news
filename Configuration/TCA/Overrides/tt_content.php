<?php

declare(strict_types=1);

defined('TYPO3') or die();

use Maispace\MaiBase\TableConfigurationArray\CType;
use Maispace\MaiBase\TableConfigurationArray\Helper;

$lang = Helper::localLangHelperFactory('mai_news', 'Default/locallang_tca.xlf');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'MaiNews',
    'List',
    $lang('plugin.list.title'),
    'mai-content',
    'maispace_feature',
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'MaiNews',
    'Rss',
    $lang('plugin.rss.title'),
    'mai-content',
    'maispace_feature',
);

(new CType('maispace_news_list', $lang('ctype.news_list'), 'mai-content'))
    ->addDefaultHeaderPalette()
    ->addCustomFields('pi_flexform')
    ->addDefaultLanguageTab()
    ->addDefaultAccessTab()
    ->setGroup('maispace_feature')
    ->register();

(new CType('maispace_news_rss', $lang('ctype.news_rss'), 'mai-content'))
    ->addDefaultHeaderPalette()
    ->addCustomFields('pi_flexform')
    ->addDefaultLanguageTab()
    ->addDefaultAccessTab()
    ->setGroup('maispace_feature')
    ->register();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:mai_news/Configuration/FlexForms/NewsPlugin.xml',
    'maispace_news_list',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:mai_news/Configuration/FlexForms/RssPlugin.xml',
    'maispace_news_rss',
);
