<?php

declare(strict_types=1);

namespace Maispace\MaiNews\Domain\Repository;

use Maispace\MaiNews\Domain\Model\News;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class NewsRepository extends Repository
{
    protected $defaultOrderings = [
        'date' => QueryInterface::ORDER_DESCENDING,
    ];

    public function findByCategoryUid(int $categoryUid): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->contains('categories', $categoryUid),
        );

        return $query->execute();
    }

    public function findByTagUid(int $tagUid): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->contains('tags', $tagUid),
        );

        return $query->execute();
    }

    public function findFromPages(array $pageUids): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setStoragePageIds($pageUids);

        return $query->execute();
    }

    public function findFromPagesByCategoryUid(array $pageUids, int $categoryUid): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setStoragePageIds($pageUids);
        $query->matching(
            $query->contains('categories', $categoryUid),
        );

        return $query->execute();
    }

    public function findFromPagesByTagUid(array $pageUids, int $tagUid): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setStoragePageIds($pageUids);
        $query->matching(
            $query->contains('tags', $tagUid),
        );

        return $query->execute();
    }

    public function findLatest(int $limit = 5): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->setLimit($limit);

        return $query->execute();
    }

    public function findForRss(int $limit = 20): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->setLimit($limit);

        return $query->execute();
    }
}
