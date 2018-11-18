<?php
namespace ViewerJs\Media\FileRenderer;

use Omeka\Api\Representation\MediaRepresentation;
use Omeka\Media\FileRenderer\RendererInterface;
use Zend\View\Renderer\PhpRenderer;

class ViewerJs implements RendererInterface
{
    /**
     * These options are used only when the player is called outside of a site
     * or when the site settings are not set.
     *
     * @var array
     */
    protected $defaultOptions = [
        'attributes' => 'allowfullscreen="allowfullscreen"',
        'style' => 'height: 600px; height: 70vh',
    ];

    /**
     * Render a media via the library ViewerJS.
     *
     * @param PhpRenderer $view,
     * @param MediaRepresentation $media
     * @param array $options These options are managed for sites:
     *   - attributes: set the attributes to add
     *   - style: set the inline style
     * @return string
     */
    public function render(PhpRenderer $view, MediaRepresentation $media, array $options = [])
    {
        $isAdmin = $view->params()->fromRoute('__ADMIN__');
        if ($isAdmin) {
            $attributes = $this->defaultOptions['attributes'];
            $style = $view->setting('viewerjs_style', $this->defaultOptions['style']);
        } else {
            $attributes = isset($options['attributes'])
                ? $options['attributes']
                : $view->siteSetting('viewerjs_attributes', $this->defaultOptions['attributes']);
            $style = isset($options['style'])
                ? $options['style']
                : $view->siteSetting('viewerjs_style', $this->defaultOptions['style']);
        }

        // No fallback for HTML5.
        $html = '<iframe height="100%%" width="100%%" %1$s%2$s src="%3$s"></iframe>';
        $url = $view->assetUrl('vendor/viewerjs', 'ViewerJs') . '&file=' . $media->originalUrl();

        return vsprintf($html, [
            $attributes,
            $style ? ' style="' . $style . '"' : '',
            $view->escapeHtmlAttr($url),
        ]);
    }
}
