<?php
namespace ViewerJs\Form;

use Zend\Form\Element;
use Zend\Form\Fieldset;

class ViewerJsFieldset extends Fieldset
{
    public function init()
    {
        $this
            ->add([
                'name' => 'o:block[__blockIndex__][o:data][heading]',
                'type' => Element\Text::class,
                'options' => [
                    'label' => 'Block title', // @translate
                    'info' => 'Heading for the block, if any.', // @translate
                ],
                'attributes' => [
                    'id' => 'viewer-js-heading',
                    'required' => false,
                ],
            ])
            ->add([
                'name' => 'o:block[__blockIndex__][o:data][source]',
                'type' => Element\Url::class,
                'options' => [
                    'label' => 'Source', // @translate
                    'info' => 'The url to display. Note: media attached to items are automatically rendered via common blocks, in particular "Media".', // @translate
                ],
                'attributes' => [
                    'id' => 'viewer-js-source',
                    'required' => true,
                ],
            ])
        ;
    }
}