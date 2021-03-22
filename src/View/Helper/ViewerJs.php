<?php declare(strict_types=1);

namespace ViewerJs\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use Omeka\Api\Representation\AbstractResourceEntityRepresentation;

class ViewerJs extends AbstractHelper
{
    /**
     * @var array
     */
    protected $defaultOptions = [
        'attributes' => 'class="viewer-js" allowfullscreen="allowfullscreen" style="height: 600px; height: 70vh;"',
        'template' => \ViewerJs\Media\FileRenderer\ViewerJs::PARTIAL_NAME,
    ];

    /**
     * Get the ViewerJs Viewer for the provided resource.
     *
     * Proxies to {@link render()}.
     *
     * @param AbstractResourceEntityRepresentation|null $resource
     * @param array $options
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

        $resourceName = $resource->resourceName();
        if ($resourceName === 'item') {
            $medias = $resource->media();
            // Get the media that is readable by the viewer.
            foreach ($medias as $media) {
                if ($media->renderer() === 'viewerjs') {
                    $options['source'] = $media->originalUrl();
                    return $this->render($resource, $options);
                }
            }
            return '';
        }

        $media = $resource->primaryMedia();
        if ($media && $media->renderer() !== 'viewerjs') {
            $options['source'] = $media->originalUrl();
            return $this->render($resource, $options);
        }

        return '';
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
