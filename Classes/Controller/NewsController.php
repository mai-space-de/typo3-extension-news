<?php

declare(strict_types=1);

namespace Maispace\MaiNews\Controller;

use Maispace\MaiBase\Controller\AbstractActionController;
use Maispace\MaiBase\Controller\Traits\AppendDataToPluginVariablesTrait;
use Maispace\MaiBase\Controller\Traits\DetailActionTrait;
use Maispace\MaiBase\Controller\Traits\PageRendererTrait;
use Maispace\MaiBase\Controller\Traits\PaginationTrait;
use Maispace\MaiNews\Domain\Repository\NewsRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\Page\PageRenderer;

class NewsController extends AbstractActionController
{
    use AppendDataToPluginVariablesTrait;
    use PageRendererTrait;
    use PaginationTrait;
    use DetailActionTrait;

    public function __construct(
        private readonly NewsRepository $newsRepository,
    ) {}

    public function injectPageRenderer(PageRenderer $pageRenderer): void
    {
        $this->pageRenderer = $pageRenderer;
    }

    public function injectAssetCollector(AssetCollector $assetCollector): void
    {
        $this->assetCollector = $assetCollector;
    }

    public function listAction(): ResponseInterface
    {
        $settings = $this->getSettings();
        $pageUids = $this->resolveStoragePageUids();
        $categoryUid = (int) ($settings['categoryUid'] ?? 0);
        $tagUid = (int) ($settings['tagUid'] ?? 0);

        if ($pageUids !== [] && $categoryUid > 0) {
            $news = $this->newsRepository->findFromPagesByCategoryUid($pageUids, $categoryUid);
        } elseif ($pageUids !== [] && $tagUid > 0) {
            $news = $this->newsRepository->findFromPagesByTagUid($pageUids, $tagUid);
        } elseif ($pageUids !== []) {
            $news = $this->newsRepository->findFromPages($pageUids);
        } elseif ($categoryUid > 0) {
            $news = $this->newsRepository->findByCategoryUid($categoryUid);
        } elseif ($tagUid > 0) {
            $news = $this->newsRepository->findByTagUid($tagUid);
        } else {
            $news = $this->newsRepository->findAll();
        }

        $pagination = $this->paginateQueryResult($news);

        $this->view->assignMultiple([
            'news' => $news,
            'paginator' => $pagination['paginator'],
            'pagination' => $pagination['pagination'],
            'settings' => $settings,
        ]);

        return $this->htmlResponse();
    }

    public function detailAction(): ResponseInterface
    {
        $news = $this->resolveDetailOrNotFound($this->newsRepository, 'news');

        $this->view->assignMultiple([
            'news' => $news,
            'settings' => $this->getSettings(),
        ]);

        return $this->htmlResponse();
    }

    public function rssAction(): ResponseInterface
    {
        $settings = $this->getSettings();
        $limit = (int) ($settings['limit'] ?? 20);
        $pageUids = $this->resolveStoragePageUids();

        if ($pageUids !== []) {
            $news = $this->newsRepository->findFromPages($pageUids);
        } else {
            $news = $this->newsRepository->findForRss($limit);
        }

        $this->view->assignMultiple([
            'news' => $news,
            'settings' => $settings,
        ]);

        return $this->htmlResponse()
            ->withHeader('Content-Type', 'application/rss+xml; charset=utf-8');
    }

    private function resolveStoragePageUids(): array
    {
        $pages = $this->settings['pages'] ?? '';
        if (empty($pages)) {
            return [];
        }

        return array_filter(
            array_map('intval', explode(',', (string) $pages)),
            static fn(int $uid): bool => $uid > 0,
        );
    }
}
