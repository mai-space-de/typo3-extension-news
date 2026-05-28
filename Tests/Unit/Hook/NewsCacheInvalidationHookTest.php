<?php

declare(strict_types=1);

namespace Maispace\MaiNews\Tests\Unit\Hook;

use Doctrine\DBAL\Result;
use Maispace\MaiNews\Hook\NewsCacheInvalidationHook;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Expression\ExpressionBuilder;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\DataHandling\DataHandler;

final class NewsCacheInvalidationHookTest extends TestCase
{
    private CacheManager&MockObject $cacheManager;
    private ConnectionPool&MockObject $connectionPool;
    private DataHandler&MockObject $dataHandler;

    protected function setUp(): void
    {
        $this->cacheManager = $this->createMock(CacheManager::class);
        $this->connectionPool = $this->createMock(ConnectionPool::class);
        $this->dataHandler = $this->createMock(DataHandler::class);
    }

    private function makeHook(): NewsCacheInvalidationHook
    {
        return new NewsCacheInvalidationHook($this->cacheManager, $this->connectionPool);
    }

    private function makePoolWithPid(int $pid): ConnectionPool
    {
        $exprBuilder = $this->createMock(ExpressionBuilder::class);
        $exprBuilder->method('eq')->willReturn('1=1');

        $result = $this->createMock(Result::class);
        $result->method('fetchAssociative')->willReturn(['pid' => $pid]);

        $qb = $this->createMock(QueryBuilder::class);
        $qb->method('select')->willReturnSelf();
        $qb->method('from')->willReturnSelf();
        $qb->method('where')->willReturnSelf();
        $qb->method('expr')->willReturn($exprBuilder);
        $qb->method('createNamedParameter')->willReturn(':p1');
        $qb->method('executeQuery')->willReturn($result);

        $pool = $this->createMock(ConnectionPool::class);
        $pool->method('getQueryBuilderForTable')->willReturn($qb);

        return $pool;
    }

    private function makePoolWithNoPid(): ConnectionPool
    {
        $exprBuilder = $this->createMock(ExpressionBuilder::class);
        $exprBuilder->method('eq')->willReturn('1=1');

        $result = $this->createMock(Result::class);
        $result->method('fetchAssociative')->willReturn(false);

        $qb = $this->createMock(QueryBuilder::class);
        $qb->method('select')->willReturnSelf();
        $qb->method('from')->willReturnSelf();
        $qb->method('where')->willReturnSelf();
        $qb->method('expr')->willReturn($exprBuilder);
        $qb->method('createNamedParameter')->willReturn(':p1');
        $qb->method('executeQuery')->willReturn($result);

        $pool = $this->createMock(ConnectionPool::class);
        $pool->method('getQueryBuilderForTable')->willReturn($qb);

        return $pool;
    }

    #[Test]
    public function ignoredTableFlushesNoCacheTest(): void
    {
        $this->cacheManager->expects(self::never())->method('flushCachesByTags');

        $this->makeHook()->processDatamap_afterDatabaseOperations(
            'update',
            'pages',
            42,
            [],
            $this->dataHandler,
        );
    }

    #[Test]
    public function unknownTableFlushesNoCacheTest(): void
    {
        $this->cacheManager->expects(self::never())->method('flushCachesByTags');

        $this->makeHook()->processDatamap_afterDatabaseOperations(
            'update',
            'tt_content',
            1,
            [],
            $this->dataHandler,
        );
    }

    #[Test]
    public function newNewsRecordFlushesCacheForPidFromFieldArrayTest(): void
    {
        $this->cacheManager
            ->expects(self::once())
            ->method('flushCachesByTags')
            ->with(['pageId_7']);

        $this->makeHook()->processDatamap_afterDatabaseOperations(
            'new',
            'tx_mainews_news',
            'NEW12345',
            ['pid' => 7, 'title' => 'Test news'],
            $this->dataHandler,
        );
    }

    #[Test]
    public function newNewsRecordWithMissingPidFlushesNoCacheTest(): void
    {
        $this->cacheManager->expects(self::never())->method('flushCachesByTags');

        $this->makeHook()->processDatamap_afterDatabaseOperations(
            'new',
            'tx_mainews_news',
            'NEW99',
            [],
            $this->dataHandler,
        );
    }

    #[Test]
    public function updateWithPidInFieldArrayFlushesCacheTest(): void
    {
        $this->cacheManager
            ->expects(self::once())
            ->method('flushCachesByTags')
            ->with(['pageId_15']);

        $this->makeHook()->processDatamap_afterDatabaseOperations(
            'update',
            'tx_mainews_news',
            42,
            ['pid' => 15, 'title' => 'Updated title'],
            $this->dataHandler,
        );
    }

    #[Test]
    public function updateWithoutPidInFieldArrayFetchesPidFromDatabaseTest(): void
    {
        $this->cacheManager
            ->expects(self::once())
            ->method('flushCachesByTags')
            ->with(['pageId_33']);

        $hook = new NewsCacheInvalidationHook($this->cacheManager, $this->makePoolWithPid(33));
        $hook->processDatamap_afterDatabaseOperations(
            'update',
            'tx_mainews_news',
            99,
            ['title' => 'Only title changed'],
            $this->dataHandler,
        );
    }

    #[Test]
    public function updateWithUnresolvablePidFlushesNoCacheTest(): void
    {
        $this->cacheManager->expects(self::never())->method('flushCachesByTags');

        $hook = new NewsCacheInvalidationHook($this->cacheManager, $this->makePoolWithNoPid());
        $hook->processDatamap_afterDatabaseOperations(
            'update',
            'tx_mainews_news',
            55,
            [],
            $this->dataHandler,
        );
    }

    #[Test]
    public function updateWithZeroIdAndNoPidInFieldArrayFlushesNoCacheTest(): void
    {
        $this->cacheManager->expects(self::never())->method('flushCachesByTags');

        $this->makeHook()->processDatamap_afterDatabaseOperations(
            'update',
            'tx_mainews_news',
            0,
            [],
            $this->dataHandler,
        );
    }
}
