<?php
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
        ],
        'aliases' => [
            'application/pdf' => 'viewerJs',
            'pdf' => 'viewerJs',
            'application/vnd.oasis.opendocument.presentation' => 'viewerJs',
            'application/vnd.oasis.opendocument.presentation-flat-xml' => 'viewerJs',
            'application/vnd.oasis.opendocument.presentation-template' => 'viewerJs',
            'application/vnd.oasis.opendocument.spreadsheet' => 'viewerJs',
            'application/vnd.oasis.opendocument.spreadsheet-flat-xml' => 'viewerJs',
            'application/vnd.oasis.opendocument.spreadsheet-template' => 'viewerJs',
            'application/vnd.oasis.opendocument.text' => 'viewerJs',
            'application/vnd.oasis.opendocument.text-flat-xml' => 'viewerJs',
            'application/vnd.oasis.opendocument.text-template' => 'viewerJs',
            'odp' => 'viewerJs',
            'ods' => 'viewerJs',
            'odt' => 'viewerJs',
            'fodp' => 'viewerJs',
            'fods' => 'viewerJs',
            'fodt' => 'viewerJs',
            'otp' => 'viewerJs',
            'ots' => 'viewerJs',
            'ott' => 'viewerJs',
            // Managed by Omeka S by default.
            // // Images.
            // 'jpg' => 'viewerJs',
            // 'png' => 'viewerJs',
            // 'gif' => 'viewerJs',
            // 'bmp' => 'viewerJs',
            // // Audio.
            // 'audio/ogg' => 'viewerJs',
            // 'audio/x-aac' => 'viewerJs',
            // 'audio/mpeg' => 'viewerJs',
            // 'audio/mp4' => 'viewerJs',
            // 'audio/x-wav' => 'viewerJs',
            // 'audio/x-aiff' => 'viewerJs',
            // // Video.
            // 'application/ogg' => 'viewerJs',
            // 'video/mp4' => 'viewerJs',
            // 'video/quicktime' => 'viewerJs',
            // 'video/x-msvideo' => 'viewerJs',
            // 'video/ogg' => 'viewerJs',
            // 'video/webm' => 'viewerJs',
            // 'mp3' => 'viewerJs',
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
