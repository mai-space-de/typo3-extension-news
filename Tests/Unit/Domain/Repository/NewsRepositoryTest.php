<?php

declare(strict_types=1);

namespace Maispace\MaiNews\Tests\Unit\Domain\Repository;

use Maispace\MaiNews\Domain\Repository\NewsRepository;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

final class NewsRepositoryTest extends TestCase
{
    #[Test]
    public function repositoryExtendsTYPO3BaseRepository(): void
    {
        self::assertTrue(
            is_subclass_of(NewsRepository::class, Repository::class),
            NewsRepository::class . ' must extend ' . Repository::class,
        );
    }

    #[Test]
    public function defaultOrderingsSortByDateDescending(): void
    {
        $reflection = new \ReflectionClass(NewsRepository::class);
        $defaults = $reflection->getDefaultProperties();

        self::assertArrayHasKey('defaultOrderings', $defaults);
        self::assertIsArray($defaults['defaultOrderings']);
        self::assertArrayHasKey('date', $defaults['defaultOrderings']);
        self::assertSame(QueryInterface::ORDER_DESCENDING, $defaults['defaultOrderings']['date']);
    }

    #[Test]
    public function defaultOrderingsContainExactlyOneSortKey(): void
    {
        $reflection = new \ReflectionClass(NewsRepository::class);
        $defaults = $reflection->getDefaultProperties();

        self::assertCount(1, $defaults['defaultOrderings']);
    }
}
