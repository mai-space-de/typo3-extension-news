<?php

declare(strict_types=1);

namespace Maispace\MaiNews\Tests\Unit\Hook;

use Maispace\MaiNews\Hook\NewsCacheInvalidationHook;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Extbase\Service\CacheService;

final class NewsCacheInvalidationHookTest extends TestCase
{
    private CacheService&MockObject $cacheService;

    private DataHandler&MockObject $dataHandler;

    protected function setUp(): void
    {
        $this->cacheService = $this->createMock(CacheService::class);
        $this->dataHandler = $this->createMock(DataHandler::class);
    }

    #[Test]
    public function newsRecordUpdateFlushesExtbaseCacheTagsTest(): void
    {
        $this->cacheService->expects(self::once())->method('clearCacheForRecord')->with('tx_mainews_news', 42);
        $this->cacheService->expects(self::once())->method('clearCachesOfRegisteredPageIds');

        $this->makeHook()->processDatamap_afterDatabaseOperations(
            'update',
            'tx_mainews_news',
            42,
            ['title' => 'Updated title'],
            $this->dataHandler,
        );
    }

    #[Test]
    public function unrelatedTableFlushesNoCacheTest(): void
    {
        $this->cacheService->expects(self::never())->method('clearCacheForRecord');
        $this->cacheService->expects(self::never())->method('clearCachesOfRegisteredPageIds');

        $this->makeHook()->processDatamap_afterDatabaseOperations(
            'update',
            'tx_maifaq_faq',
            1,
            [],
            $this->dataHandler,
        );
    }

    private function makeHook(): NewsCacheInvalidationHook
    {
        return new NewsCacheInvalidationHook($this->cacheService);
    }
}
