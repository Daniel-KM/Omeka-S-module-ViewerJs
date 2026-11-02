<?php declare(strict_types=1);

namespace ViewerJsTest\View\Helper;

use CommonTest\AbstractHttpControllerTestCase;
use Omeka\Api\Representation\ItemRepresentation;
use Omeka\Api\Representation\MediaRepresentation;

/**
 * Tests the view helper that renders ViewerJS for any source.
 */
class ViewerJsTest extends AbstractHttpControllerTestCase
{
    public function testRenderSource(): void
    {
        $output = $this->helper()(null, ['source' => 'https://example.org/doc.odt']);
        $this->assertStringContainsString('<iframe', $output);
        $this->assertStringEndsWith('&file=' . rawurlencode('https://example.org/doc.odt'), $this->src($output));
    }

    public function testRenderHeading(): void
    {
        $output = $this->helper()(null, ['source' => 'https://example.org/doc.odt', 'heading' => 'A <b>title</b>']);
        $this->assertStringContainsString('<h2>A &lt;b&gt;title&lt;/b&gt;</h2>', $output);
    }

    public function testRenderNothingWithoutResourceNorSource(): void
    {
        $this->assertSame('', $this->helper()(null));
        $this->assertSame('', $this->helper()([]));
    }

    public function testRenderFirstReadableMediaOfItem(): void
    {
        $item = $this->createMock(ItemRepresentation::class);
        $item->method('media')->willReturn([
            $this->media('image/png', 'png', 'https://example.org/a.png'),
            $this->media('application/pdf', 'pdf', 'https://example.org/b.pdf'),
            $this->media('application/vnd.oasis.opendocument.text', 'odt', 'https://example.org/c.odt'),
        ]);
        $this->assertStringEndsWith('&file=' . rawurlencode('https://example.org/b.pdf'), $this->src($this->helper()($item)));
    }

    public function testRenderNothingForItemWithoutReadableMedia(): void
    {
        $item = $this->createMock(ItemRepresentation::class);
        $item->method('media')->willReturn([
            $this->media('image/png', 'png', 'https://example.org/a.png'),
            $this->media('application/pdf', 'pdf', null),
        ]);
        $this->assertSame('', $this->helper()($item));
    }

    public function testRenderMedia(): void
    {
        $media = $this->media('application/vnd.oasis.opendocument.text', 'odt', 'https://example.org/c.odt');
        $this->assertStringEndsWith('&file=' . rawurlencode('https://example.org/c.odt'), $this->src($this->helper()($media)));
        $this->assertSame('', $this->helper()($this->media('image/png', 'png', 'https://example.org/a.png')));
    }

    protected function media(string $mediaType, string $extension, ?string $originalUrl): MediaRepresentation
    {
        $media = $this->createMock(MediaRepresentation::class);
        $media->method('renderer')->willReturn('file');
        $media->method('mediaType')->willReturn($mediaType);
        $media->method('extension')->willReturn($extension);
        $media->method('hasOriginal')->willReturn($originalUrl !== null);
        $media->method('originalUrl')->willReturn($originalUrl);
        $media->method('primaryMedia')->willReturnSelf();
        return $media;
    }

    protected function helper(): \ViewerJs\View\Helper\ViewerJs
    {
        return $this->getApplication()->getServiceManager()->get('ViewHelperManager')->get('viewerJs');
    }

    protected function src(string $output): string
    {
        preg_match('~ src="([^"]*)"~', $output, $matches);
        return html_entity_decode($matches[1] ?? '', ENT_QUOTES | ENT_HTML5);
    }
}
