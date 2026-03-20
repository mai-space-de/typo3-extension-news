<?php

declare(strict_types=1);

namespace Maispace\MaiNews\Domain\Model;

use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class NewsArticle extends AbstractEntity
{
    protected string $title = '';

    protected string $teaser = '';

    protected string $bodyText = '';

    protected string $author = '';

    protected ?\DateTime $publishDate = null;

    protected string $tags = '';

    protected string $slug = '';

    /**
     * @var ObjectStorage<NewsCategory>
     */
    #[Lazy]
    protected ObjectStorage $categories;

    /**
     * @var ObjectStorage<FileReference>
     */
    #[Lazy]
    protected ObjectStorage $image;

    public function __construct()
    {
        $this->categories = new ObjectStorage();
        $this->image = new ObjectStorage();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTeaser(): string
    {
        return $this->teaser;
    }

    public function setTeaser(string $teaser): void
    {
        $this->teaser = $teaser;
    }

    public function getBodyText(): string
    {
        return $this->bodyText;
    }

    public function setBodyText(string $bodyText): void
    {
        $this->bodyText = $bodyText;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    public function getPublishDate(): ?\DateTime
    {
        return $this->publishDate;
    }

    public function setPublishDate(?\DateTime $publishDate): void
    {
        $this->publishDate = $publishDate;
    }

    public function getTags(): string
    {
        return $this->tags;
    }

    public function setTags(string $tags): void
    {
        $this->tags = $tags;
    }

    /**
     * Returns tags as an array.
     *
     * @return string[]
     */
    public function getTagsArray(): array
    {
        if ($this->tags === '') {
            return [];
        }
        return array_map('trim', explode(',', $this->tags));
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return ObjectStorage<NewsCategory>
     */
    public function getCategories(): ObjectStorage
    {
        return $this->categories;
    }

    /**
     * @param ObjectStorage<NewsCategory> $categories
     */
    public function setCategories(ObjectStorage $categories): void
    {
        $this->categories = $categories;
    }

    public function addCategory(NewsCategory $category): void
    {
        $this->categories->attach($category);
    }

    public function removeCategory(NewsCategory $category): void
    {
        $this->categories->detach($category);
    }

    /**
     * @return ObjectStorage<FileReference>
     */
    public function getImage(): ObjectStorage
    {
        return $this->image;
    }

    /**
     * @param ObjectStorage<FileReference> $image
     */
    public function setImage(ObjectStorage $image): void
    {
        $this->image = $image;
    }

    public function getFirstImage(): ?FileReference
    {
        $this->image->rewind();
        return $this->image->current() ?: null;
    }
}
