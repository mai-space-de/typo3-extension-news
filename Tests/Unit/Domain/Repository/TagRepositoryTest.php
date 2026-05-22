<?php

declare(strict_types=1);

namespace Maispace\MaiNews\Tests\Unit\Domain\Repository;

use Maispace\MaiNews\Domain\Repository\TagRepository;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Extbase\Persistence\Repository;

final class TagRepositoryTest extends TestCase
{
    #[Test]
    public function repositoryExtendsTYPO3BaseRepository(): void
    {
        self::assertTrue(
            is_subclass_of(TagRepository::class, Repository::class),
            TagRepository::class . ' must extend ' . Repository::class,
        );
    }

    #[Test]
    public function repositoryHasNoCustomDefaultOrderings(): void
    {
        $reflection = new \ReflectionClass(TagRepository::class);
        $defaults = $reflection->getDefaultProperties();

        // TagRepository is a plain repository with no defaultOrderings override
        self::assertArrayHasKey('defaultOrderings', $defaults);
        self::assertIsArray($defaults['defaultOrderings']);
        self::assertCount(0, $defaults['defaultOrderings']);
    }
}
