<?php

declare(strict_types=1);

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();

ExtensionUtility::registerPlugin(
    'MaiNews',
    'News',
    'LLL:EXT:mai_news/Resources/Private/Language/locallang_db.xlf:plugin.mai_news_news.title',
    'EXT:mai_news/Resources/Public/Icons/Extension.svg',
    'news',
    'LLL:EXT:mai_news/Resources/Private/Language/locallang_db.xlf:plugin.mai_news_news.description',
);

ExtensionUtility::registerPlugin(
    'MaiNews',
    'RssFeed',
    'LLL:EXT:mai_news/Resources/Private/Language/locallang_db.xlf:plugin.mai_news_rssfeed.title',
    'EXT:mai_news/Resources/Public/Icons/Extension.svg',
    'news',
    'LLL:EXT:mai_news/Resources/Private/Language/locallang_db.xlf:plugin.mai_news_rssfeed.description',
);
