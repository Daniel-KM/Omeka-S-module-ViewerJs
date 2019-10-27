<?php
namespace ViewerJs\Form;

use Omeka\Form\Element\PropertySelect;
use Zend\Form\Fieldset;

class SettingsFieldset extends Fieldset
{
    protected $label = 'Viewer JS'; // @translate

    public function init()
    {
        $this
            ->add([
                'name' => 'viewerjs_source_property',
                'type' => PropertySelect::class,
                'options' => [
                    'label' => 'Property used for external file', // @translate
                    'info' => 'The property supplying the file via URL, for example "dcterms:hasFormat" or "dcterms:isFormatOf".', // @translate
                    'empty_option' => '',
                    'term_as_value' => true,
                ],
                'attributes' => [
                    'id' => 'viewerjs_source_property',
                    'class' => 'chosen-select',
                    'data-placeholder' => 'Select a property…', // @translate
                ],
            ])
        ;
    }
}
