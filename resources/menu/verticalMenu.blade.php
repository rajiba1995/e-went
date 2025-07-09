<?php
$menuData = [
    'menu' => [
        [
            'url' => route('admin.dashboard'), // Dynamically generated URL from route name
            'name' => 'Dashboards',
            'icon' => 'menu-icon tf-icons ri-home-smile-line',
            'slug' => 'dashboard',
            'badge' => ['danger', ''],
        ],
        [
            'url' => route('admin.product.management'), // Dynamically generated URL from route name
            'name' => 'Product Management',
            'icon' => 'menu-icon tf-icons ri-store-line',
            'slug' => 'dashboard',
            'badge' => ['danger', ''],
            'submenu' => [
                [
                    'url' => route('admin.product.categories'), // Dynamically generated URL
                    'name' => 'Category',
                    'slug' => 'dashboard-crm',
                ],
                [
                    'url' => route('admin.product.sub-categories'), // Dynamically generated URL
                    'name' => 'Sub Category',
                    'slug' => 'dashboard-crm',
                ],
                [
                    'url' => route('admin.product.index'), // Dynamically generated URL
                    'name' => 'Product',
                    'slug' => 'dashboard-crm',
                ],
            ],
        ],
        [
            'url' => route('admin.product.stocks'), // Dynamically generated URL
            'name' => 'Stock Management',
            'icon' => 'menu-icon tf-icons ri-stock-line',
            'slug' => 'dashboard',
            'badge' => ['danger', ''],
            'submenu' => [
                [
                    'url' => route('admin.product.stocks.report'), // Dynamically generated URL
                    'name' => 'Stock Report',
                    'slug' => 'dashboard-crm',
                ],
            ],
        ],
        [
            'url' => 'https://themeselection.com/demo/materio-bootstrap-html-admin-template/documentation/laravel-introduction.html', // External URL remains unchanged
            'icon' => 'menu-icon tf-icons ri-article-line',
            'name' => 'Documentation',
            'slug' => 'documentation',
            'target' => '_blank',
        ],
    ],
];
