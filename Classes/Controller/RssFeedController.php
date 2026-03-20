<?php

declare(strict_types=1);

namespace Maispace\MaiNews\Controller;

use Maispace\MaiNews\Domain\Repository\NewsArticleRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Controller for the RSS feed.
 *
 * Renders an RSS 2.0 feed of the most recent news articles and returns
 * the response with the correct application/rss+xml content-type header.
 */
class RssFeedController extends ActionController
{
    public function __construct(
        private readonly NewsArticleRepository $newsArticleRepository,
    ) {}

    /**
     * RSS feed – outputs the latest articles as an RSS 2.0 XML document.
     */
    public function indexAction(int $limit = 20): ResponseInterface
    {
        $limit = max(1, $limit);

        $this->view->assignMultiple([
            'news'     => $this->newsArticleRepository->findLatest($limit),
            'feedLink' => $this->request->getRequestTargetUri(),
        ]);

        $content = $this->view->render();

        return $this->responseFactory
            ->createResponse()
            ->withHeader('Content-Type', 'application/rss+xml; charset=utf-8')
            ->withBody($this->streamFactory->createStream($content));
    }
}
