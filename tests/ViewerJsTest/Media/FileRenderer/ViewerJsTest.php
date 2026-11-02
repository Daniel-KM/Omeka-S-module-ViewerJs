<?php declare(strict_types=1);

namespace ViewerJsTest\Media\FileRenderer;

use CommonTest\AbstractHttpControllerTestCase;
use Omeka\Api\Representation\MediaRepresentation;
use ViewerJs\Media\FileRenderer\ViewerJs;

/**
 * Tests the rendering of a media via the iframe of ViewerJS.
 */
class ViewerJsTest extends AbstractHttpControllerTestCase
{
    public function testRenderIframe(): void
    {
        $output = $this->render($this->media('https://example.org/files/original/a b.pdf'));
        $this->assertStringContainsString('<iframe', $output);
        $this->assertStringContainsString('class="viewer-js"', $output);
        $this->assertStringContainsString('/vendor/viewerjs/index.html?', $this->src($output));
        $this->assertStringEndsWith('&file=' . rawurlencode('https://example.org/files/original/a b.pdf'), $this->src($output));
    }

    public function testRenderNothingWithoutOriginal(): void
    {
        $this->assertSame('', trim($this->render($this->media(null))));
    }

    public function testAdminIgnoresCustomTemplate(): void
    {
        $output = $this->render($this->media('https://example.org/a.pdf'), [
            'template' => 'common/missing-template',
            'attributes' => 'class="custom"',
        ]);
        $this->assertStringContainsString('class="viewer-js"', $output);
        $this->assertStringNotContainsString('class="custom"', $output);
    }

    protected function render(MediaRepresentation $media, array $options = []): string
    {
        $view = $this->getApplication()->getServiceManager()->get('ViewRenderer');
        return (new ViewerJs())->render($view, $media, $options);
    }

    protected function media(?string $originalUrl): MediaRepresentation
    {
        $media = $this->createMock(MediaRepresentation::class);
        $media->method('primaryMedia')->willReturnSelf();
        $media->method('hasOriginal')->willReturn($originalUrl !== null);
        $media->method('originalUrl')->willReturn($originalUrl);
        return $media;
    }

    protected function src(string $output): string
    {
        preg_match('~ src="([^"]*)"~', $output, $matches);
        return html_entity_decode($matches[1] ?? '', ENT_QUOTES | ENT_HTML5);
    }
}
