<?php
namespace ViewerJs\Form;

use Zend\Form\Element;
use Zend\Form\Fieldset;

class SettingsFieldset extends Fieldset
{
    public function init()
    {
        $this->setLabel('Viewer JS'); // @translate

        $this->add([
            'name' => 'viewerjs_style',
            'type' => Element\Text::class,
            'options' => [
                'label' => 'Inline style', // @translate
                'info' => 'If any, this style will be added to the iframe. The height may be required.', // @translate
            ],
        ]);
    }
}
