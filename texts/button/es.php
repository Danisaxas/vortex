<?php
return [
    // Botones para el comando /start
    'start_menu' => [
        // Fila 1
        [
            ['text' => 'Gateways',      'callback_data' => 'gateways'],
            ['text' => 'Herramientas',  'callback_data' => 'herramientas'],
            ['text' => 'Informacion',   'callback_data' => 'info'],
        ],
        // Fila 2
        [
            ['text' => 'xCloud [☁️]',   'callback_data' => 'xcloud'],
            ['text' => 'Cerrar',        'callback_data' => 'cerrar'],
            ['text' => 'xCommerce [☁️]', 'callback_data' => 'xcommerce'],
        ],
    ],
    // Botones que se muestran al pulsar "Gateways"
    'gateways_menu' => [
        [
            ['text' => 'Auth',           'callback_data' => 'auth'],
            ['text' => 'Charge',         'callback_data' => 'charge'],
            ['text' => 'CCN Gates',      'callback_data' => 'ccngates'],
        ],
        [
            ['text' => 'Mass Checking',  'callback_data' => 'masschecking'],
        ],
        [
            ['text' => 'Back',           'callback_data' => 'back_start'],
        ],
    ],
];
