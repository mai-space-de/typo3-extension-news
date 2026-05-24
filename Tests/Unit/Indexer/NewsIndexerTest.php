<?php

declare(strict_types=1);

namespace Maispace\MaiNews\Tests\Unit\Indexer;

use Maispace\MaiNews\Domain\Model\News;
use Maispace\MaiNews\Indexer\NewsIndexer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class NewsIndexerTest extends TestCase
{
    private NewsIndexer $subject;

    protected function setUp(): void
    {
        $this->subject = new NewsIndexer();
    }

    #[Test]
    public function getTypeReturnsNews(): void
    {
        self::assertSame('news', $this->subject->getType());
    }

    #[Test]
    public function supportsNewsTable(): void
    {
        self::assertTrue($this->subject->supports('tx_mainews_news'));
    }

    #[Test]
    public function doesNotSupportOtherTables(): void
    {
        self::assertFalse($this->subject->supports('tx_maifaq_question'));
        self::assertFalse($this->subject->supports('pages'));
        self::assertFalse($this->subject->supports('tt_content'));
    }

    #[Test]
    public function getIconReturnsExpectedValue(): void
    {
        self::assertSame('content-news', $this->subject->getIcon('news'));
    }

    #[Test]
    public function buildContentConcatenatesTeaserAndBody(): void
    {
        $news = new News();
        $news->setTeaser('Short intro.');
        $news->setBody('<p>Full article content.</p>');

        $content = $this->invokeBuildContent($news);

        self::assertStringContainsString('Short intro.', $content);
        self::assertStringContainsString('Full article content.', $content);
    }

    #[Test]
    public function buildContentStripsHtmlTags(): void
    {
        $news = new News();
        $news->setTeaser('Intro');
        $news->setBody('<p>Paragraph with <strong>bold</strong> text.</p>');

        $content = $this->invokeBuildContent($news);

        self::assertStringNotContainsString('<p>', $content);
        self::assertStringNotContainsString('<strong>', $content);
        self::assertStringContainsString('bold', $content);
    }

    #[Test]
    public function buildContentReturnsEmptyStringForNonNewsRecord(): void
    {
        $content = $this->invokeBuildContent(new \stdClass());

        self::assertSame('', $content);
    }

    #[Test]
    public function formatResultReturnsSearchResultWithCorrectType(): void
    {
        $solrDoc = [
            'title_s' => 'Test Article',
            'content_t' => 'Some content here',
            'url_s' => '/news/test-article',
            'score' => 2.5,
        ];

        $result = $this->subject->formatResult($solrDoc);

        self::assertSame('news', $result->type);
        self::assertSame('Test Article', $result->title);
        self::assertSame('/news/test-article', $result->url);
        self::assertSame('content-news', $result->icon);
        self::assertSame(2.5, $result->score);
    }

    #[Test]
    public function formatResultDefaultsToEmptyStringsWhenFieldsAreMissing(): void
    {
        $result = $this->subject->formatResult([]);

        self::assertSame('', $result->title);
        self::assertSame('', $result->url);
        self::assertSame(0.0, $result->score);
        self::assertNull($result->date);
    }

    private function invokeBuildContent(object $record): string
    {
        $reflection = new \ReflectionMethod($this->subject, 'buildContent');
        $reflection->setAccessible(true);

        /** @var string $result */
        return $reflection->invoke($this->subject, $record);
    }
}
