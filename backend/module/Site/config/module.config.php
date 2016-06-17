<?php

return array(
    'translator' => array(
        'locale' => 'en_EN',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ),
        ),

    ),

    'controllers' => array(
        'invokables' => array(
            'Site_Controller' => 'Site\Controller\IndexController',
            'Site_Load_Controller'=> 'Site\Controller\commands\LoadSiteController'
        ),
    ),
);

