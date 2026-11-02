<?php declare(strict_types=1);

namespace ViewerJsTest;

use CommonTest\AbstractHttpControllerTestCase;
use ViewerJs\Module;

/**
 * Tests the check of the file renderers set in the config.
 */
class ModuleTest extends AbstractHttpControllerTestCase
{
    public function testNoInvalidFileRenderers(): void
    {
        $this->assertSame([], $this->invalidFileRenderers());
    }

    public function testInvalidFileRenderers(): void
    {
        // The override of a previous version of the readme, without the module
        // Pdf Viewer.
        $services = $this->getApplication()->getServiceManager();
        $override = ['application/pdf' => 'pdfViewer', 'pdf' => 'pdfViewer'];
        $config = $services->get('Config');
        $config['file_renderers']['invokables'] = $override + $config['file_renderers']['invokables'];
        $services->setAllowOverride(true);
        $services->setService('Config', $config);
        $fileRenderers = $services->get('Omeka\Media\FileRenderer\Manager');
        $fileRenderers->setAllowOverride(true);
        $fileRenderers->configure(['invokables' => $override]);

        $this->assertSame(['application/pdf', 'pdf'], $this->invalidFileRenderers());
    }

    public function testCoreFileRenderersAreNotLoaded(): void
    {
        // The FallbackRenderer of Omeka S 4.1 and 4.2 cannot be compiled, so it
        // should never be loaded by the check.
        $fileRenderers = $this->getApplication()->getServiceManager()->get('Omeka\Media\FileRenderer\Manager');
        $this->invalidFileRenderers();
        $services = (fn () => $this->services)->call($fileRenderers);
        $this->assertArrayNotHasKey(\Omeka\Media\FileRenderer\FallbackRenderer::class, $services);
        $this->assertArrayNotHasKey(\Omeka\Media\FileRenderer\ThumbnailRenderer::class, $services);
    }

    protected function invalidFileRenderers(): array
    {
        $module = new Module();
        $module->setServiceLocator($this->getApplication()->getServiceManager());
        return (fn () => $this->invalidFileRenderers())->call($module);
    }
}
