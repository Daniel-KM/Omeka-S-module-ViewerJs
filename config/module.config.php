<?php declare(strict_types=1);
namespace ViewerJs;

return [
    'view_manager' => [
        'template_path_stack' => [
            dirname(__DIR__) . '/view',
        ],
    ],
    'file_renderers' => [
        'invokables' => [
            'viewerJs' => Media\FileRenderer\ViewerJs::class,

            // Aliases are not used to speed loading and to decrease memory use.

            'application/pdf' => Media\FileRenderer\ViewerJs::class,
            'pdf' => Media\FileRenderer\ViewerJs::class,
            'application/vnd.oasis.opendocument.presentation' => Media\FileRenderer\ViewerJs::class,
            'application/vnd.oasis.opendocument.presentation-flat-xml' => Media\FileRenderer\ViewerJs::class,
            'application/vnd.oasis.opendocument.presentation-template' => Media\FileRenderer\ViewerJs::class,
            'application/vnd.oasis.opendocument.spreadsheet' => Media\FileRenderer\ViewerJs::class,
            'application/vnd.oasis.opendocument.spreadsheet-flat-xml' => Media\FileRenderer\ViewerJs::class,
            'application/vnd.oasis.opendocument.spreadsheet-template' => Media\FileRenderer\ViewerJs::class,
            'application/vnd.oasis.opendocument.text' => Media\FileRenderer\ViewerJs::class,
            'application/vnd.oasis.opendocument.text-flat-xml' => Media\FileRenderer\ViewerJs::class,
            'application/vnd.oasis.opendocument.text-template' => Media\FileRenderer\ViewerJs::class,
            'odp' => Media\FileRenderer\ViewerJs::class,
            'ods' => Media\FileRenderer\ViewerJs::class,
            'odt' => Media\FileRenderer\ViewerJs::class,
            'fodp' => Media\FileRenderer\ViewerJs::class,
            'fods' => Media\FileRenderer\ViewerJs::class,
            'fodt' => Media\FileRenderer\ViewerJs::class,
            'otp' => Media\FileRenderer\ViewerJs::class,
            'ots' => Media\FileRenderer\ViewerJs::class,
            'ott' => Media\FileRenderer\ViewerJs::class,
            // Managed by Omeka S by default.
            // // Images.
            // 'jpg' => Media\FileRenderer\ViewerJs::class,
            // 'png' => Media\FileRenderer\ViewerJs::class,
            // 'gif' => Media\FileRenderer\ViewerJs::class,
            // 'bmp' => Media\FileRenderer\ViewerJs::class,
            // // Audio.
            // 'audio/ogg' => Media\FileRenderer\ViewerJs::class,
            // 'audio/x-aac' => Media\FileRenderer\ViewerJs::class,
            // 'audio/mpeg' => Media\FileRenderer\ViewerJs::class,
            // 'audio/mp4' => Media\FileRenderer\ViewerJs::class,
            // 'audio/x-wav' => Media\FileRenderer\ViewerJs::class,
            // 'audio/x-aiff' => Media\FileRenderer\ViewerJs::class,
            // // Video.
            // 'application/ogg' => Media\FileRenderer\ViewerJs::class,
            // 'video/mp4' => Media\FileRenderer\ViewerJs::class,
            // 'video/quicktime' => Media\FileRenderer\ViewerJs::class,
            // 'video/x-msvideo' => Media\FileRenderer\ViewerJs::class,
            // 'video/ogg' => Media\FileRenderer\ViewerJs::class,
            // 'video/webm' => Media\FileRenderer\ViewerJs::class,
            // 'mp3' => Media\FileRenderer\ViewerJs::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'viewerJs' => View\Helper\ViewerJs::class,
        ],
    ],
    'block_layouts' => [
        'invokables' => [
            'viewerJs' => Site\BlockLayout\ViewerJs::class,
        ],
    ],
    'form_elements' => [
        'invokables' => [
            Form\SettingsFieldset::class => Form\SettingsFieldset::class,
            Form\ViewerJsFieldset::class => Form\ViewerJsFieldset::class,
        ],
    ],
    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => dirname(__DIR__) . '/language',
                'pattern' => '%s.mo',
                'text_domain' => null,
            ],
        ],
    ],
    'viewerjs' => [
        'settings' => [
            'viewerjs_source_property' => null,
        ],
        'block_settings' => [
            'viewerJs' => [
                'heading' => '',
                'source' => '',
            ],
        ],
    ],
];
