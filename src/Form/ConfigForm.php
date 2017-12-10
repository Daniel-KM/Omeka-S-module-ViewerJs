<?php
namespace ViewerJs\Form;

use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\I18n\Translator\TranslatorAwareInterface;
use Zend\I18n\Translator\TranslatorAwareTrait;

class ConfigForm extends Form implements TranslatorAwareInterface
{
    use TranslatorAwareTrait;

    public function init()
    {
        $this->add([
            'name' => 'viewerjs_style',
            'type' => Text::class,
            'options' => [
                'label' => 'Inline style', // @translate
                'info' => $this->translate('If any, this style will be added to the iframe.') // @translate
                    . ' ' . $this->translate('The height may be required.'), // @translate
            ],
        ]);
    }

    protected function translate($args)
    {
        $translator = $this->getTranslator();
        return $translator->translate($args);
    }
}
