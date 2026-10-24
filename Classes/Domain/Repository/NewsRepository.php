<?php

declare(strict_types=1);

namespace Maispace\MaiNews\Domain\Repository;

use Maispace\MaiBase\Backend\CustomPageModule\DoktypeIds;
use Maispace\MaiNews\Domain\Model\News;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Reads News doktype pages (120). Parent UIDs in FlexForm `pages` filter by pid.
 */
class NewsRepository extends Repository
{
    protected $defaultOrderings = [
        'date' => QueryInterface::ORDER_DESCENDING,
    ];

    public function initializeObject(): void
    {
        $querySettings = $this->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    public function findByCategoryUid(int $categoryUid): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $this->doktypeConstraint($query),
                $query->contains('categories', $categoryUid),
            ),
        );

        return $query->execute();
    }

    public function findByTagUid(int $tagUid): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $this->doktypeConstraint($query),
                $query->contains('tags', $tagUid),
            ),
        );

        return $query->execute();
    }

    /**
     * @param list<int> $parentPageUids Parent page UIDs (News pages live as children)
     */
    public function findFromPages(array $parentPageUids): QueryResultInterface
    {
        $query = $this->createQuery();
        $constraints = [$this->doktypeConstraint($query)];
        if ($parentPageUids !== []) {
            $constraints[] = $query->in('pid', $parentPageUids);
        }
        $query->matching($query->logicalAnd(...$constraints));

        return $query->execute();
    }

    /**
     * @param list<int> $parentPageUids
     */
    public function findFromPagesByCategoryUid(array $parentPageUids, int $categoryUid): QueryResultInterface
    {
        $query = $this->createQuery();
        $constraints = [
            $this->doktypeConstraint($query),
            $query->contains('categories', $categoryUid),
        ];
        if ($parentPageUids !== []) {
            $constraints[] = $query->in('pid', $parentPageUids);
        }
        $query->matching($query->logicalAnd(...$constraints));

        return $query->execute();
    }

    /**
     * @param list<int> $parentPageUids
     */
    public function findFromPagesByTagUid(array $parentPageUids, int $tagUid): QueryResultInterface
    {
        $query = $this->createQuery();
        $constraints = [
            $this->doktypeConstraint($query),
            $query->contains('tags', $tagUid),
        ];
        if ($parentPageUids !== []) {
            $constraints[] = $query->in('pid', $parentPageUids);
        }
        $query->matching($query->logicalAnd(...$constraints));

        return $query->execute();
    }

    public function findLatest(int $limit = 5): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching($this->doktypeConstraint($query));
        $query->setLimit($limit);

        return $query->execute();
    }

    public function findForRss(int $limit = 20): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching($this->doktypeConstraint($query));
        $query->setLimit($limit);

        return $query->execute();
    }

    public function findAll(): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching($this->doktypeConstraint($query));

        return $query->execute();
    }

    public function findByUid($uid): ?News
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('uid', (int) $uid),
                $this->doktypeConstraint($query),
            ),
        );
        $query->setLimit(1);
        /** @var News|null $result */
        $result = $query->execute()->getFirst();

        return $result;
    }

    private function doktypeConstraint(QueryInterface $query): \TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface
    {
        return $query->equals('doktype', DoktypeIds::NEWS);
    }
}
