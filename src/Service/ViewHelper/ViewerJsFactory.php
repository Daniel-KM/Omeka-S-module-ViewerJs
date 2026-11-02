<?php declare(strict_types=1);

namespace ViewerJs\Service\ViewHelper;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use ViewerJs\View\Helper\ViewerJs;

class ViewerJsFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $services, $requestedName, ?array $options = null)
    {
        return new ViewerJs(
            $services->get('Omeka\Media\FileRenderer\Manager')
        );
    }
}
