<?php

declare(strict_types=1);

defined('TYPO3') or die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'MaiNews',
    'List',
    [
        \Maispace\MaiNews\Controller\NewsController::class => 'list,detail',
    ],
    [],
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'MaiNews',
    'Rss',
    [
        \Maispace\MaiNews\Controller\NewsController::class => 'rss',
    ],
    [],
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] =
    \Maispace\MaiNews\Hook\NewsCacheInvalidationHook::class;
