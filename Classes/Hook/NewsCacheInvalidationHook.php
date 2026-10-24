<?php

declare(strict_types=1);

namespace Maispace\MaiNews\Hook;

use Maispace\MaiBase\Backend\CustomPageModule\DoktypeIds;
use Maispace\MaiBase\Hook\AbstractRecordCacheInvalidationHook;
use TYPO3\CMS\Core\DataHandling\DataHandler;

/**
 * Flushes list/RSS page cache tags when a News page changes.
 */
final class NewsCacheInvalidationHook extends AbstractRecordCacheInvalidationHook
{
    protected function getWatchedTable(): string
    {
        return 'pages';
    }

    public function processDatamap_afterDatabaseOperations(
        string $status,
        string $table,
        int|string $id,
        array $fieldArray,
        DataHandler $dataHandler,
    ): void {
        if ($table !== 'pages' || !$this->isNewsPageChange($fieldArray, $dataHandler)) {
            return;
        }

        parent::processDatamap_afterDatabaseOperations($status, $table, $id, $fieldArray, $dataHandler);
    }

    public function processCmdmap_deleteAction(
        string $table,
        int $id,
        array $recordToDelete,
        bool &$recordWasDeleted,
        DataHandler $dataHandler,
    ): void {
        if ($table !== 'pages') {
            return;
        }

        $doktype = (int) ($recordToDelete['doktype'] ?? 0);
        if ($doktype !== DoktypeIds::NEWS) {
            return;
        }

        parent::processCmdmap_deleteAction($table, $id, $recordToDelete, $recordWasDeleted, $dataHandler);
    }

    /**
     * @param array<string, mixed> $fieldArray
     */
    private function isNewsPageChange(array $fieldArray, DataHandler $dataHandler): bool
    {
        if (isset($fieldArray['doktype']) && (int) $fieldArray['doktype'] === DoktypeIds::NEWS) {
            return true;
        }

        foreach (array_keys($fieldArray) as $field) {
            if (str_starts_with((string) $field, 'tx_mainews_')) {
                return true;
            }
        }

        $currentDoktype = (int) ($dataHandler->checkValue_currentRecord['doktype'] ?? 0);

        return $currentDoktype === DoktypeIds::NEWS;
    }
}
