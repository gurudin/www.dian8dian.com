<?php
return [
    'adminEmail' => 'admin@example.com',
    'nav' => [
        [
            'icon'  => 'fas fa-sliders-h',
            'label' => 'Menu',
            'href'  => '#',
            'badge' => '',
            'open'  => false,
            'child' => [
                ['label' => 'All menu', 'href' => '/category/list'],
                ['label' => 'Create & Update', 'href' => '/category/save'],
            ]
        ],
        [
            'icon'  => 'fas fa-newspaper',
            'label' => 'Article',
            'href'  => '#',
            'open'  => true,
            'child' => [
                ['label' => 'All acticle', 'href' => '/article/index'],
                ['label' => 'Create & Update', 'href' => '/article/save'],
            ]
        ],
    ],
];
