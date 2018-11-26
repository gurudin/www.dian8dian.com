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
            'open'  => false,
            'child' => [
                ['label' => 'All acticle', 'href' => '/article/index'],
                ['label' => 'Create & Update', 'href' => '/article/save'],
            ]
        ],
        [
            'icon' => 'fas fa-spider',
            'label' => 'Spider',
            'href' => '#',
            'open' => false,
            'child' => [
                ['label' => 'Set spider', 'href' => '/spider/spider'],
            ]
        ],
    ],
];
