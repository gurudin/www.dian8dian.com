<?php
return [
    'adminEmail' => 'admin@example.com',
    'nav' => [
        ["icon" => "fas fa-columns", "label" => "Pages", "href" => "#", "badge" => "", "open" => false, "child" => [
            ["icon" => "fas fa-sign-in-alt", "label" => "Sign In", "href" => "/site/login", "badge" => "11"],
            ["icon" => "", "label" => "Blank Page", "href" => "#", "badge" => ""],
            ["icon" => "", "label" => "Index", "href" => "/site/index", "badge" => ""],
            ["icon" => "", "label" => "Invoice", "href" => "#", "badge" => "1"]
        ]],
        ["icon" => "fas fa-dollar-sign", "label" => "Financial", "href" => "#", "badge" => "New", "open" => false],
        ["icon" => "fas fa-exclamation-triangle", "label" => "Error", "href" => "/site/error", "badge" => "", "open" => false]
    ],
];
