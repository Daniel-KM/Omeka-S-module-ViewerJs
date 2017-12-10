<?php
namespace ViewerJs\Media\FileRenderer;

use Omeka\Api\Representation\MediaRepresentation;
use Omeka\Media\FileRenderer\RendererInterface;
use Omeka\Stdlib\Message;
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
        'attributes' => 'allowfullscreen="1"',
        'style' => 'height: 600px; 70vh',
    ];

    /**
     * @var PhpRenderer
     */
    protected $view;

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
        $this->setView($view);

        $isSite = $view->params()->fromRoute('__SITE__');
        // For admin board.
        if (empty($isSite)) {
            $attributes = $this->defaultOptions['attributes'];
            $style = $view->setting('viewerjs_style', $this->defaultOptions['style']);
        }
        // For sites.
        else {
            $attributes = isset($options['attributes'])
                ? $options['attributes']
                : $view->siteSetting('viewerjs_attributes', $this->defaultOptions['attributes']);

            $style = isset($options['style'])
                ? $options['style']
                : $view->siteSetting('viewerjs_style', $this->defaultOptions['style']);
        }

        $html = '<iframe height="100%%" width="100%%" %1$s%2$s src="%3$s">%4$s</iframe>';
        $url = version_compare(\Omeka\Module::VERSION, '1.1.0-alpha', '<')
            ? $view->assetUrl('vendor/viewerjs', 'ViewerJs') . '?file=' . $media->originalUrl()
            : ($view->assetUrl('vendor/viewerjs', 'ViewerJs') . '&file=' . $media->originalUrl());

        return vsprintf($html, [
            $attributes,
            $style ? ' style="' . $style . '"' : '',
            $url,
            $this->fallback($media),
        ]);
    }

    protected function fallback($media)
    {
        $view = $this->getView();
        $text = $view->escapeHtml(sprintf($view->translate('This browser does not support %s (%s).'), // @translate
            $media->extension(), $media->mediaType()));
        $text .= ' ' . sprintf($view->translate('You may %sdownload it%s to view it offline.'), // @translate
            '<a href="' . $media->originalUrl() . '">', '</a>');
        $html = '<p>' . $text . '</p>'
            . '<img src="' . $media->thumbnailUrl('large') . '" height="600px" />';
        return $html;
    }

    /**
     * @param PhpRenderer $view
     */
    protected function setView(PhpRenderer $view)
    {
        $this->view = $view;
    }

    /**
     * @return PhpRenderer
     */
    protected function getView()
    {
        return $this->view;
    }
}
