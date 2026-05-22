<?php

declare(strict_types=1);

namespace Maispace\MaiNews\Tests\Unit\Domain\Model;

use Maispace\MaiNews\Domain\Model\Tag;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class TagTest extends TestCase
{
    // ── Default values ──────────────────────────────────────────────────────

    #[Test]
    public function defaultNameIsEmptyString(): void
    {
        $tag = new Tag();
        self::assertSame('', $tag->getName());
    }

    // ── name getter / setter ────────────────────────────────────────────────

    #[Test]
    public function setNameStoresTheValue(): void
    {
        $tag = new Tag();
        $tag->setName('Sport');
        self::assertSame('Sport', $tag->getName());
    }

    #[Test]
    public function setNameOverwritesPreviousValue(): void
    {
        $tag = new Tag();
        $tag->setName('First tag');
        $tag->setName('Second tag');
        self::assertSame('Second tag', $tag->getName());
    }

    #[Test]
    public function setNameAcceptsEmptyString(): void
    {
        $tag = new Tag();
        $tag->setName('Non-empty');
        $tag->setName('');
        self::assertSame('', $tag->getName());
    }

    #[Test]
    public function twoTagInstancesAreIndependent(): void
    {
        $tag1 = new Tag();
        $tag2 = new Tag();
        $tag1->setName('Culture');
        self::assertSame('', $tag2->getName());
    }
}
