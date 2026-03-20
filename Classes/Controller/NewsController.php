<?php

declare(strict_types=1);

namespace MaiSpace\MaiNews\Controller;

use MaiSpace\MaiNews\Domain\Model\NewsArticle;
use MaiSpace\MaiNews\Domain\Model\NewsCategory;
use MaiSpace\MaiNews\Domain\Repository\NewsArticleRepository;
use MaiSpace\MaiNews\Domain\Repository\NewsCategoryRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Controller for news list, detail, teaser, and category-list views.
 */
class NewsController extends ActionController
{
    public function __construct(
        private readonly NewsArticleRepository $newsArticleRepository,
        private readonly NewsCategoryRepository $newsCategoryRepository,
    ) {}

    /**
     * News overview – all articles, optionally filtered by category or tag.
     */
    public function listAction(
        ?NewsCategory $category = null,
        string $tag = '',
    ): ResponseInterface {
        if ($category !== null && $tag !== '') {
            $news = $this->newsArticleRepository->findByCategoryAndTag($category, $tag);
        } elseif ($category !== null) {
            $news = $this->newsArticleRepository->findByCategory($category);
        } elseif ($tag !== '') {
            $news = $this->newsArticleRepository->findByTag($tag);
        } else {
            $news = $this->newsArticleRepository->findAll();
        }

        $this->view->assignMultiple([
            'news'           => $news,
            'categories'     => $this->newsCategoryRepository->findAll(),
            'activeCategory' => $category,
            'activeTag'      => $tag,
        ]);

        return $this->htmlResponse();
    }

    /**
     * News detail page.
     */
    public function showAction(NewsArticle $newsArticle): ResponseInterface
    {
        $this->view->assign('newsArticle', $newsArticle);
        return $this->htmlResponse();
    }

    /**
     * Teaser widget – shows the N most recent articles.
     */
    public function teaserAction(int $limit = 3): ResponseInterface
    {
        $limit = max(1, $limit);
        $this->view->assign('news', $this->newsArticleRepository->findLatest($limit));
        return $this->htmlResponse();
    }

    /**
     * Category overview – all categories with their articles.
     */
    public function categoryListAction(): ResponseInterface
    {
        $this->view->assignMultiple([
            'categories' => $this->newsCategoryRepository->findAll(),
            'grouped'    => $this->newsArticleRepository->findGroupedByCategory(),
        ]);
        return $this->htmlResponse();
    }
}
