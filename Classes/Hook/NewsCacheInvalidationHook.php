<?php

declare(strict_types=1);

namespace Maispace\MaiNews\Hook;

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\DataHandler;

/**
 * DataHandler hook that flushes the TYPO3 page cache whenever a news record
 * is saved, so news list and detail pages always reflect the latest content.
 *
 * TYPO3 tags each cached page response with `pageId_<uid>`. Flushing that tag
 * invalidates every cached variant of the page (all languages, all user groups)
 * without touching unrelated cached pages.
 */
final class NewsCacheInvalidationHook
{
    private const WATCHED_TABLE = 'tx_mainews_news';

    public function __construct(
        private readonly CacheManager $cacheManager,
        private readonly ConnectionPool $connectionPool,
    ) {}

    /**
     * Called by DataHandler after every INSERT or UPDATE on any table.
     * Resolves the owning page UID and flushes its cached responses.
     */
    public function processDatamap_afterDatabaseOperations(
        string $status,
        string $table,
        int|string $id,
        array $fieldArray,
        DataHandler $dataHandler,
    ): void {
        if ($table !== self::WATCHED_TABLE) {
            return;
        }

        $pageUid = $this->resolvePageUid($status, $table, $id, $fieldArray);

        if ($pageUid === 0) {
            return;
        }

        $this->cacheManager->flushCachesByTags(['pageId_' . $pageUid]);
    }

    /**
     * Resolves the pid (owning page UID) for a saved news record.
     *
     * For new records the pid is always present in $fieldArray.
     * For updates the pid may be absent when it was not part of the change —
     * in that case we fall back to a lightweight DB lookup.
     */
    private function resolvePageUid(
        string $status,
        string $table,
        int|string $id,
        array $fieldArray,
    ): int {
        if ($status === 'new') {
            return (int) ($fieldArray['pid'] ?? 0);
        }

        // Updates: pid is present in fieldArray only when the record was moved.
        if (isset($fieldArray['pid'])) {
            return (int) $fieldArray['pid'];
        }

        $recordUid = (int) $id;
        if ($recordUid === 0) {
            return 0;
        }

        return $this->fetchPidForRecord($table, $recordUid);
    }

    private function fetchPidForRecord(string $table, int $uid): int
    {
        $qb = $this->connectionPool->getQueryBuilderForTable($table);
        $row = $qb
            ->select('pid')
            ->from($table)
            ->where($qb->expr()->eq('uid', $qb->createNamedParameter($uid, Connection::PARAM_INT)))
            ->executeQuery()
            ->fetchAssociative();

        return $row !== false ? (int) $row['pid'] : 0;
    }
}
