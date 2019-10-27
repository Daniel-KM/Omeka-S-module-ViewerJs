<?php
namespace ViewerJs\Media\FileRenderer;

use Omeka\Api\Representation\MediaRepresentation;
use Omeka\Media\FileRenderer\RendererInterface;
use Zend\View\Renderer\PhpRenderer;

class ViewerJs implements RendererInterface
{
    /**
     * The default partial view script.
     */
    const PARTIAL_NAME = 'common/viewer-js';

    /**
     * @var array
     */
    protected $defaultOptions = [
        'attributes' => 'class="viewer-js" allowfullscreen="allowfullscreen" style="height: 600px; height: 70vh;"',
        'template' => self::PARTIAL_NAME,
    ];

    /**
     * Render a media via the library ViewerJS.
     *
     * @todo Factorize with the view helper.
     *
     * @param PhpRenderer $view,
     * @param MediaRepresentation $media
     * @param array $options These options are managed for sites:
     *   - template: the partial to use
     *   - attributes: set the attributes to add
     * @return string
     */
    public function render(PhpRenderer $view, MediaRepresentation $media, array $options = [])
    {
        // Omeka 1.2.0 doesn't support $view->status().
        $isPublic = $view->params()->fromRoute('__SITE__');
        if ($isPublic) {
            $template = isset($options['template'])
                ? $options['template']
                : $this->defaultOptions['template'];
            $options['attributes'] = isset($options['attributes'])
                ? $options['attributes']
                : $this->defaultOptions['attributes'];
        } else {
            $template = $this->defaultOptions['template'];
            $options['attributes'] = $this->defaultOptions['attributes'];
        }

        unset($options['template']);
        return $view->partial($template, [
            'resource' => $media,
            'options' => $options,
        ]);
    }
}
