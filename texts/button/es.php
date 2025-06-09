<?php
return [
    'main_menu' => [
        [
            ['text' => 'Cerrar',        'callback_data' => 'cerrar'],
            ['text' => 'Gateways',      'callback_data' => 'gateways'],
            ['text' => 'Herramientas',  'callback_data' => 'herramientas'],
        ],
        [
            ['text' => 'Informacion',   'callback_data' => 'info'],
            ['text' => 'xCloud [☁️]',   'callback_data' => 'xcloud'],
            ['text' => 'xCommerce [☁️]', 'callback_data' => 'xcommerce'],
        ],
    ],

    'gateways_menu' => [
        [
            ['text' => 'Auth',           'callback_data' => 'auth'],
            ['text' => 'CCN Gates',      'callback_data' => 'ccngates'],
            ['text' => 'Charge',         'callback_data' => 'charge'],
        ],
        [
            ['text' => 'Mass Checking',  'callback_data' => 'masschecking'],
        ],
        [
            ['text' => 'Back',           'callback_data' => 'back_start'],
        ],
    ],
];
