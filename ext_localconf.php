<?php

declare(strict_types=1);

use MaiSpace\MaiNews\Controller\NewsController;
use MaiSpace\MaiNews\Controller\RssFeedController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();

// News plugin – overview, detail, teaser, category list
ExtensionUtility::configurePlugin(
    'MaiNews',
    'News',
    [
        NewsController::class => 'list,show,teaser,categoryList',
    ],
    // Non-cacheable actions
    [
        NewsController::class => '',
    ]
);

// RSS Feed plugin
ExtensionUtility::configurePlugin(
    'MaiNews',
    'RssFeed',
    [
        RssFeedController::class => 'index',
    ],
    [
        RssFeedController::class => '',
    ]
);
