<?php
namespace ViewerJs\Service\Form;

use Interop\Container\ContainerInterface;
use ViewerJs\Form\ConfigForm;
use Zend\ServiceManager\Factory\FactoryInterface;

class ConfigFormFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $services, $requestedName, array $options = null)
    {
        $translator = $services->get('MvcTranslator');
        $form = new ConfigForm(null, $options);
        $form->setTranslator($translator);
        return $form;
    }
}
