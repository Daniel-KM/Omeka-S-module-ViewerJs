<?php declare(strict_types=1);

namespace ViewerJs\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use Omeka\Api\Representation\AbstractResourceEntityRepresentation;
use Omeka\Api\Representation\ItemRepresentation;
use Omeka\Api\Representation\MediaRepresentation;
use Omeka\Media\FileRenderer\Manager as FileRendererManager;
use ViewerJs\Media\FileRenderer\ViewerJs as ViewerJsRenderer;

class ViewerJs extends AbstractHelper
{
    /**
     * @var \Omeka\Media\FileRenderer\Manager
     */
    protected $fileRendererManager;

    /**
     * @var array
     */
    protected $defaultOptions = [
        'attributes' => 'class="viewer-js" allowfullscreen="allowfullscreen" style="height: 600px; height: 70vh;"',
        'template' => \ViewerJs\Media\FileRenderer\ViewerJs::PARTIAL_NAME,
    ];

    public function __construct(FileRendererManager $fileRendererManager)
    {
        $this->fileRendererManager = $fileRendererManager;
    }

    /**
     * Get the ViewerJs Viewer for the provided resource.
     *
     * Proxies to {@link render()}.
     *
     * @param AbstractResourceEntityRepresentation|null $resource
     * @param array $options
     * - source (string): url to display, required when resource is not available.
     * - template (string): the template to use.
     * - attributes (string): attributes to add to the iframe.
     * - heading (string): Deprecated. Will be removed in a future version.
     * Other options are passed
     * @return string Html string corresponding to the viewer.
     */
    public function __invoke($resource, $options = [])
    {
        if (isset($options['source'])) {
            return $this->render($resource, $options);
        }

        if (empty($resource)) {
            return '';
        }

        if (is_array($resource)) {
            return '';
        }

        $view = $this->getView();

        // Determine the url of the source from a field in metadata. No check.
        $sourceProperty = $view->setting('viewerjs_source_property');
        if ($sourceProperty) {
            $urlSource = $resource->value($sourceProperty);
            if ($urlSource) {
                // Manage the case where the url is saved as an uri or a text.
                $options['source'] = $urlSource->uri() ?: $urlSource->value();
                return $this->render($resource, $options);
            }
        }

        // Get the first media that is readable by the viewer.
        $medias = $resource instanceof ItemRepresentation
            ? $resource->media()
            : array_filter([$resource->primaryMedia()]);
        foreach ($medias as $media) {
            if ($this->isViewerJsMedia($media)) {
                $options['source'] = $media->originalUrl();
                return $this->render($resource, $options);
            }
        }

        return '';
    }

    /**
     * Check if a media is a file rendered by ViewerJS.
     */
    protected function isViewerJsMedia(MediaRepresentation $media): bool
    {
        if ($media->renderer() !== 'file' || !$media->hasOriginal()) {
            return false;
        }
        foreach ([$media->mediaType(), $media->extension()] as $name) {
            if ($name && $this->fileRendererManager->has($name)) {
                return $this->fileRendererManager->get($name) instanceof ViewerJsRenderer;
            }
        }
        return false;
    }

    /**
     * Render a ViewerJs for a resource, according to options.
     *
     * @todo Factorize with the media renderer.
     *
     * @param AbstractResourceEntityRepresentation|null $resource
     * @param array $options It must contains the source url.
     * @return string Html code.
     */
    protected function render($resource, array $options = [])
    {
        $view = $this->getView();

        $status = $view->status();
        if ($status->isSiteRequest()) {
            $template = $options['template'] ?? $this->defaultOptions['template'];
            $options['attributes'] = $options['attributes'] ?? $this->defaultOptions['attributes'];
        } else {
            $template = $this->defaultOptions['template'];
            $options['attributes'] = $this->defaultOptions['attributes'];
        }

        unset($options['template']);
        return $view->partial($template, [
            'resource' => $resource,
            'options' => $options,
        ]);
    }
}
