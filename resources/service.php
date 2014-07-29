<?php
/*
 * This file is part of the php-discogs-api.
 *
 * (c) Richard van den Brand <richard@vandenbrand.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    'baseUrl' => 'http://api.discogs.com/',
    'operations' => [
        'getArtist' => [
            'httpMethod' => 'GET',
            'uri' => 'artists/{id}',
            'responseModel' => 'GetResponse',
            'parameters' => [
                'id' => [
                    'type' => 'string',
                    'location' => 'uri',
                    'required' => true
                ]
            ]
        ],
        'getArtistReleases' => [
            'httpMethod' => 'GET',
            'uri' => 'artists/{id}/releases',
            'responseModel' => 'GetResponse',
            'parameters' => [
                'id' => [
                    'type' => 'string',
                    'location' => 'uri',
                    'required' => true
                ],
                'per_page' => [
                    'type' => 'integer',
                    'location' => 'query',
                    'required' => false
                ],
                'page' => [
                    'type' => 'integer',
                    'location' => 'query',
                    'required' => false
                ]
            ]
        ],
        'search' => [
            'httpMethod' => 'GET',
            'uri' => 'database/search',
            'responseModel' => 'GetResponse',
            'parameters' => [
                'q' => [
                    'type' => 'string',
                    'location' => 'query',
                    'required' => false
                ],
                'type' => [
                    'type' => 'string',
                    'location' => 'query',
                    'required' => false
                ],
                'title' => [
                    'type' => 'boolean',
                    'location' => 'query',
                    'required' => false
                ],
                'release_title' => [
                    'type' => 'boolean',
                    'location' => 'query',
                    'required' => false
                ],
                'credit' => [
                    'type' => 'boolean',
                    'location' => 'query',
                    'required' => false
                ],
                'artist' => [
                    'type' => 'boolean',
                    'location' => 'query',
                    'required' => false
                ],
                'anv' => [
                    'type' => 'boolean',
                    'location' => 'query',
                    'required' => false
                ],
                'label' => [
                    'type' => 'boolean',
                    'location' => 'query',
                    'required' => false
                ],
                'genre' => [
                    'type' => 'boolean',
                    'location' => 'query',
                    'required' => false
                ],
                'style' => [
                    'type' => 'boolean',
                    'location' => 'query',
                    'required' => false
                ],
                'country' => [
                    'type' => 'boolean',
                    'location' => 'query',
                    'required' => false
                ],
                'year' => [
                    'type' => 'boolean',
                    'location' => 'query',
                    'required' => false
                ],
                'format' => [
                    'type' => 'boolean',
                    'location' => 'query',
                    'required' => false
                ],
                'catno' => [
                    'type' => 'boolean',
                    'location' => 'query',
                    'required' => false
                ],
                'barcode' => [
                    'type' => 'boolean',
                    'location' => 'query',
                    'required' => false
                ],
                'track' => [
                    'type' => 'boolean',
                    'location' => 'query',
                    'required' => false
                ],
                'submitter' => [
                    'type' => 'boolean',
                    'location' => 'query',
                    'required' => false
                ],
                'contributor' => [
                    'type' => 'boolean',
                    'location' => 'query',
                    'required' => false
                ]
            ]
        ],
        'getRelease' => [
            'httpMethod' => 'GET',
            'uri' => 'releases/{id}',
            'responseModel' => 'GetResponse',
            'parameters' => [
                'id' => [
                    'type' => 'string',
                    'location' => 'uri',
                    'required' => true
                ]
            ]
        ],
        'getMaster' => [
            'httpMethod' => 'GET',
            'uri' => 'masters/{id}',
            'responseModel' => 'GetResponse',
            'parameters' => [
                'id' => [
                    'type' => 'string',
                    'location' => 'uri',
                    'required' => true
                ]
            ]
        ],
        'getMasterVersions' => [
            'httpMethod' => 'GET',
            'uri' => 'masters/{id}/versions',
            'responseModel' => 'GetResponse',
            'parameters' => [
                'id' => [
                    'type' => 'string',
                    'location' => 'uri',
                    'required' => true
                ],
                'per_page' => [
                    'type' => 'integer',
                    'location' => 'query',
                    'required' => false
                ],
                'page' => [
                    'type' => 'integer',
                    'location' => 'query',
                    'required' => false
                ]
            ]
        ],
        'getLabel' => [
            'httpMethod' => 'GET',
            'uri' => 'labels/{id}',
            'responseModel' => 'GetResponse',
            'parameters' => [
                'id' => [
                    'type' => 'string',
                    'location' => 'uri',
                    'required' => true
                ]
            ]
        ],
    ],
    'models' => [
        'GetResponse' => [
            'type' => 'object',
            'additionalProperties' => [
                'location' => 'json'
            ],
        ]
    ]
];
