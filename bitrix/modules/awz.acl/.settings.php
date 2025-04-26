<?php
return [
    'ui.entity-selector' => [
        'value' => [
            'entities' => [
                [
                    'entityId' => 'awzacl-user',
                    'provider' => [
                        'moduleId' => 'awz.acl',
                        'className' => '\\Awz\\Acl\\Access\\EntitySelectors\\User'
                    ],
                ],
                [
                    'entityId' => 'awzacl-group',
                    'provider' => [
                        'moduleId' => 'awz.acl',
                        'className' => '\\Awz\\Acl\\Access\\EntitySelectors\\Group'
                    ],
                ],
            ]
        ],
        'readonly' => true,
    ]
];