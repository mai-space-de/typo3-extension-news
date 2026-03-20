<?php

declare(strict_types=1);

namespace Maispace\MaiNews\Domain\Repository;

use Maispace\MaiNews\Domain\Model\NewsArticle;
use Maispace\MaiNews\Domain\Model\NewsCategory;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Repository for NewsArticle records.
 *
 * Provides filter methods for category, tag, and date-range queries.
 */
class NewsArticleRepository extends Repository
{
    /**
     * Default ordering: newest articles first.
     *
     * @var array<string, string>
     */
    protected $defaultOrderings = [
        'publishDate' => QueryInterface::ORDER_DESCENDING,
        'crdate'      => QueryInterface::ORDER_DESCENDING,
    ];

    /**
     * Returns all articles that belong to the given category.
     */
    public function findByCategory(NewsCategory $category): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->contains('categories', $category)
        );
        return $query->execute();
    }

    /**
     * Returns all articles that carry the given tag (case-insensitive substring match).
     */
    public function findByTag(string $tag): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->like('tags', '%' . $tag . '%')
        );
        return $query->execute();
    }

    /**
     * Returns all articles whose publishDate lies within the given date range (inclusive).
     */
    public function findByDateRange(\DateTime $start, \DateTime $end): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->greaterThanOrEqual('publishDate', $start),
                $query->lessThanOrEqual('publishDate', $end)
            )
        );
        return $query->execute();
    }

    /**
     * Returns the $limit most recent articles.
     */
    public function findLatest(int $limit = 10): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->setLimit($limit);
        return $query->execute();
    }

    /**
     * Returns all articles for a given category and optional tag, ordered by publishDate desc.
     */
    public function findByCategoryAndTag(NewsCategory $category, string $tag = ''): QueryResultInterface
    {
        $query = $this->createQuery();

        $constraints = [
            $query->contains('categories', $category),
        ];

        if ($tag !== '') {
            $constraints[] = $query->like('tags', '%' . $tag . '%');
        }

        $query->matching($query->logicalAnd(...$constraints));
        return $query->execute();
    }

    /**
     * Returns all articles grouped by their first category.
     * Returns a plain array of NewsArticle objects (grouping must be done in the template/controller).
     *
     * @return array<string, NewsArticle[]>
     */
    public function findGroupedByCategory(): array
    {
        /** @var NewsArticle[] $allArticles */
        $allArticles = $this->findAll()->toArray();
        $grouped = [];

        foreach ($allArticles as $article) {
            $categories = $article->getCategories();
            if ($categories->count() === 0) {
                $grouped['Uncategorized'][] = $article;
                continue;
            }
            foreach ($categories as $category) {
                $grouped[$category->getTitle()][] = $article;
            }
        }

        return $grouped;
    }
}
