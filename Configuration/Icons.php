<?php

declare(strict_types=1);

use Maispace\MaiBase\Icon\StreamlineSvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

$allIcons = 'EXT:mai_base/Resources/Public/Icons/all-icons/';

return [
    'ext-maispace-mai_news' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:mai_news/Resources/Public/Icons/Extension.svg',
    ],
    'mai-news' => [
        'provider' => StreamlineSvgIconProvider::class,
        'source' => $allIcons . 'interface-content-book-open--content-books-book-open--Streamline-Core.svg',
    ],
    'mai-news-rss' => [
        'provider' => StreamlineSvgIconProvider::class,
        'source' => $allIcons . 'programming-rss-symbol--wireless-feed-rss-transmit-broadcast--Streamline-Core.svg',
    ],
    'mai-news-tag' => [
        'provider' => StreamlineSvgIconProvider::class,
        'source' => $allIcons . 'interface-bookmark-tag--tags-bookmark-favorite--Streamline-Core.svg',
    ],
];
