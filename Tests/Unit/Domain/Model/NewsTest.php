<?php

declare(strict_types=1);

namespace Maispace\MaiNews\Tests\Unit\Domain\Model;

use Maispace\MaiNews\Domain\Model\News;
use Maispace\MaiNews\Domain\Model\Tag;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

final class NewsTest extends TestCase
{
    // ── Default values ──────────────────────────────────────────────────────

    #[Test]
    public function defaultTitleIsEmptyString(): void
    {
        $news = new News();
        self::assertSame('', $news->getTitle());
    }

    #[Test]
    public function defaultTeaserIsEmptyString(): void
    {
        $news = new News();
        self::assertSame('', $news->getTeaser());
    }

    #[Test]
    public function defaultBodyIsEmptyString(): void
    {
        $news = new News();
        self::assertSame('', $news->getBody());
    }

    #[Test]
    public function defaultDateIsNull(): void
    {
        $news = new News();
        self::assertNull($news->getDate());
    }

    #[Test]
    public function defaultSlugIsEmptyString(): void
    {
        $news = new News();
        self::assertSame('', $news->getSlug());
    }

    // ── ObjectStorage initialisation ────────────────────────────────────────

    #[Test]
    public function constructorInitializesImagesAsObjectStorage(): void
    {
        $news = new News();
        self::assertInstanceOf(ObjectStorage::class, $news->getImages());
    }

    #[Test]
    public function constructorInitializesImagesAsEmpty(): void
    {
        $news = new News();
        self::assertCount(0, $news->getImages());
    }

    #[Test]
    public function constructorInitializesCategoriesAsObjectStorage(): void
    {
        $news = new News();
        self::assertInstanceOf(ObjectStorage::class, $news->getCategories());
    }

    #[Test]
    public function constructorInitializesCategoriesAsEmpty(): void
    {
        $news = new News();
        self::assertCount(0, $news->getCategories());
    }

    #[Test]
    public function constructorInitializesTagsAsObjectStorage(): void
    {
        $news = new News();
        self::assertInstanceOf(ObjectStorage::class, $news->getTags());
    }

    #[Test]
    public function constructorInitializesTagsAsEmpty(): void
    {
        $news = new News();
        self::assertCount(0, $news->getTags());
    }

    // ── initializeObject ────────────────────────────────────────────────────

    #[Test]
    public function initializeObjectCreatesFreshImagesStorage(): void
    {
        $news = new News();
        $original = $news->getImages();
        $news->initializeObject();
        self::assertNotSame($original, $news->getImages());
    }

    #[Test]
    public function initializeObjectCreatesFreshCategoriesStorage(): void
    {
        $news = new News();
        $original = $news->getCategories();
        $news->initializeObject();
        self::assertNotSame($original, $news->getCategories());
    }

    #[Test]
    public function initializeObjectCreatesFreshTagsStorage(): void
    {
        $news = new News();
        $original = $news->getTags();
        $news->initializeObject();
        self::assertNotSame($original, $news->getTags());
    }

    // ── title getter / setter ────────────────────────────────────────────────

    #[Test]
    public function setTitleStoresTheValue(): void
    {
        $news = new News();
        $news->setTitle('Breaking News');
        self::assertSame('Breaking News', $news->getTitle());
    }

    #[Test]
    public function setTitleOverwritesPreviousValue(): void
    {
        $news = new News();
        $news->setTitle('First title');
        $news->setTitle('Second title');
        self::assertSame('Second title', $news->getTitle());
    }

    #[Test]
    public function setTitleAcceptsEmptyString(): void
    {
        $news = new News();
        $news->setTitle('Non-empty');
        $news->setTitle('');
        self::assertSame('', $news->getTitle());
    }

    // ── teaser getter / setter ───────────────────────────────────────────────

    #[Test]
    public function setTeaserStoresTheValue(): void
    {
        $news = new News();
        $news->setTeaser('Short introduction text.');
        self::assertSame('Short introduction text.', $news->getTeaser());
    }

    // ── body getter / setter ─────────────────────────────────────────────────

    #[Test]
    public function setBodyStoresTheValue(): void
    {
        $news = new News();
        $news->setBody('<p>Full article content.</p>');
        self::assertSame('<p>Full article content.</p>', $news->getBody());
    }

    // ── date getter / setter ─────────────────────────────────────────────────

    #[Test]
    public function setDateStoresDateTimeObject(): void
    {
        $news = new News();
        $date = new \DateTime('2024-06-01');
        $news->setDate($date);
        self::assertSame($date, $news->getDate());
    }

    #[Test]
    public function setDateAcceptsNull(): void
    {
        $news = new News();
        $news->setDate(new \DateTime());
        $news->setDate(null);
        self::assertNull($news->getDate());
    }

    // ── slug getter / setter ─────────────────────────────────────────────────

    #[Test]
    public function setSlugStoresTheValue(): void
    {
        $news = new News();
        $news->setSlug('breaking-news');
        self::assertSame('breaking-news', $news->getSlug());
    }

    // ── tags getter / setter ─────────────────────────────────────────────────

    #[Test]
    public function setTagsStoresTheObjectStorage(): void
    {
        $news = new News();
        $storage = new ObjectStorage();
        $tag = new Tag();
        $tag->setName('Sport');
        $storage->attach($tag);
        $news->setTags($storage);
        self::assertSame($storage, $news->getTags());
    }

    #[Test]
    public function twoNewsInstancesHaveIndependentTagStorages(): void
    {
        $news1 = new News();
        $news2 = new News();
        self::assertNotSame($news1->getTags(), $news2->getTags());
    }

    // ── categories getter / setter ───────────────────────────────────────────

    #[Test]
    public function setCategoriesStoresTheObjectStorage(): void
    {
        $news = new News();
        $storage = new ObjectStorage();
        $news->setCategories($storage);
        self::assertSame($storage, $news->getCategories());
    }

    #[Test]
    public function twoNewsInstancesHaveIndependentCategoryStorages(): void
    {
        $news1 = new News();
        $news2 = new News();
        self::assertNotSame($news1->getCategories(), $news2->getCategories());
    }

    // ── images getter / setter ───────────────────────────────────────────────

    #[Test]
    public function setImagesStoresTheObjectStorage(): void
    {
        $news = new News();
        $storage = new ObjectStorage();
        $news->setImages($storage);
        self::assertSame($storage, $news->getImages());
    }

    #[Test]
    public function twoNewsInstancesHaveIndependentImageStorages(): void
    {
        $news1 = new News();
        $news2 = new News();
        self::assertNotSame($news1->getImages(), $news2->getImages());
    }
}
