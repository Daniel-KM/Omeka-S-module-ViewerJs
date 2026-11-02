<?php declare(strict_types=1);

namespace ViewerJsTest;

use CommonTest\AbstractHttpControllerTestCase;
use ViewerJs\Media\FileRenderer\ViewerJs as ViewerJsRenderer;

/**
 * Tests the services registered by the module.
 */
class ConfigTest extends AbstractHttpControllerTestCase
{
    /**
     * @dataProvider mediaTypeProvider
     */
    public function testFileRenderers(string $name): void
    {
        $fileRenderers = $this->getApplication()->getServiceManager()->get('Omeka\Media\FileRenderer\Manager');
        $this->assertInstanceOf(ViewerJsRenderer::class, $fileRenderers->get($name));
    }

    public function mediaTypeProvider(): array
    {
        return [
            ['viewerJs'],
            ['application/pdf'],
            ['pdf'],
            ['application/vnd.oasis.opendocument.text'],
            ['application/vnd.oasis.opendocument.spreadsheet-flat-xml'],
            ['odp'],
            ['fodt'],
        ];
    }

    public function testViewHelper(): void
    {
        $helpers = $this->getApplication()->getServiceManager()->get('ViewHelperManager');
        $this->assertInstanceOf(\ViewerJs\View\Helper\ViewerJs::class, $helpers->get('viewerJs'));
    }

    public function testBlockLayouts(): void
    {
        $services = $this->getApplication()->getServiceManager();
        $this->assertTrue($services->get('Omeka\BlockLayoutManager')->has('viewerJs'));
        $this->assertTrue($services->get('Omeka\ResourcePageBlockLayoutManager')->has('viewerJs'));
    }

    public function testAssetIsInstalled(): void
    {
        $this->assertFileExists(dirname(__DIR__, 2) . '/asset/vendor/viewerjs/index.html');
        $this->assertFileExists(dirname(__DIR__, 2) . '/asset/vendor/viewerjs/wasm/openjpeg.wasm');
    }
}
