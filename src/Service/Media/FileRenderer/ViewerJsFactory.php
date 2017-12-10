<?php

namespace ViewerJs\Service\Media\FileRenderer;

use Interop\Container\ContainerInterface;
use ViewerJs\Media\FileRenderer\ViewerJs;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Service factory for the ViewerJs file renderer.
 */
class ViewerJsFactory implements FactoryInterface
{
    /**
     * Create and return the ViewerJs file renderer.
     *
     * @return ViewerJs
     */
    public function __invoke(ContainerInterface $serviceLocator, $requestedName, array $options = null)
    {
        $currentTheme = $serviceLocator->get('Omeka\Site\ThemeManager')
            ->getCurrentTheme();
        return new ViewerJs($currentTheme);
    }
}
