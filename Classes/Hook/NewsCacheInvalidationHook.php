<?php

declare(strict_types=1);

namespace Maispace\MaiNews\Hook;

use Maispace\MaiBase\Hook\AbstractRecordCacheInvalidationHook;

/**
 * Flushes list/detail page cache tags when a news record is saved or deleted.
 */
final class NewsCacheInvalidationHook extends AbstractRecordCacheInvalidationHook
{
    protected function getWatchedTable(): string
    {
        return 'tx_mainews_news';
    }
}
