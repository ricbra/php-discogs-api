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
    'baseUrl' => 'https://api.discogs.com/',
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
                    'type' => 'string',
                    'location' => 'query',
                    'required' => false
                ],
                'release_title' => [
                    'type' => 'string',
                    'location' => 'query',
                    'required' => false
                ],
                'credit' => [
                    'type' => 'string',
                    'location' => 'query',
                    'required' => false
                ],
                'artist' => [
                    'type' => 'string',
                    'location' => 'query',
                    'required' => false
                ],
                'anv' => [
                    'type' => 'string',
                    'location' => 'query',
                    'required' => false
                ],
                'label' => [
                    'type' => 'string',
                    'location' => 'query',
                    'required' => false
                ],
                'genre' => [
                    'type' => 'string',
                    'location' => 'query',
                    'required' => false
                ],
                'style' => [
                    'type' => 'string',
                    'location' => 'query',
                    'required' => false
                ],
                'country' => [
                    'type' => 'string',
                    'location' => 'query',
                    'required' => false
                ],
                'year' => [
                    'type' => 'string',
                    'location' => 'query',
                    'required' => false
                ],
                'format' => [
                    'type' => 'string',
                    'location' => 'query',
                    'required' => false
                ],
                'catno' => [
                    'type' => 'string',
                    'location' => 'query',
                    'required' => false
                ],
                'barcode' => [
                    'type' => 'string',
                    'location' => 'query',
                    'required' => false
                ],
                'track' => [
                    'type' => 'string',
                    'location' => 'query',
                    'required' => false
                ],
                'submitter' => [
                    'type' => 'string',
                    'location' => 'query',
                    'required' => false
                ],
                'contributor' => [
                    'type' => 'string',
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
        'getLabelReleases' => [
            'httpMethod' => 'GET',
            'uri' => 'labels/{id}/releases',
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
        'getOAuthIdentity' => [
            'httpMethod' => 'GET',
            'uri' => 'oauth/identity',
            'responseModel' => 'GetResponse',
        ],
        'getInventory' => [
            'httpMethod' => 'GET',
            'uri' => 'users/{username}/inventory',
            'responseModel' => 'GetResponse',
            'parameters' => [
                'username' => [
                    'type' => 'string',
                    'location' => 'uri',
                    'required' => true
                ],
                'status' => [
                    'type' => 'string',
                    'location' => 'query',
                    'required' => false,
                ],
                'sort' => [
                    'type' => 'string',
                    'location' => 'query',
                    'required' => false,
                ],
                'sort_order' => [
                    'type' => 'string',
                    'location' => 'query',
                    'required' => false,
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
        'getImage' => [
            'httpMethod' => 'GET',
            'uri' => 'images/{filename}',
            'responseModel' => 'GetImage',
            'parameters' => [
                'filename' => [
                    'type' => 'string',
                    'location' => 'uri',
                    'required' => true
                ]
            ]
        ],
        'getOrder' => [
            'httpMethod' => 'GET',
            'uri' => 'marketplace/orders/{id}',
            'responseModel' => 'GetResponse',
            'parameters' => [
                'id' => [
                    'type' => 'string',
                    'location' => 'uri',
                    'required' => true
                ]
            ]
        ],
        'getOrders' => [
            'httpMethod' => 'GET',
            'uri' => 'marketplace/orders',
            'responseModel' => 'GetResponse',
            'parameters' => [
                'status' => [
                    'type' => 'string',
                    'location' => 'query',
                    'required' => false
                ],
                'sort' => [
                    'type' => 'string',
                    'location' => 'query',
                    'required' => false,
                ],
                'sort_order' => [
                    'type' => 'string',
                    'location' => 'query',
                    'required' => false,
                ]
            ]
        ],
        'changeOrder' => [
            'httpMethod' => 'POST',
            'uri' => 'marketplace/orders/{id}',
            'summary' => 'Edit the data associated with an order.',
            'responseModel' => 'GetResponse',
            'parameters' => [
                'id' => [
                    'type' => 'string',
                    'location' => 'uri',
                    'required' => true
                ],
                'status' => [
                    'type' => 'string',
                    'location' => 'json',
                    'required' => false,
                ],
                'shipping' => [
                    'type' => 'number',
                    'location' => 'json',
                    'required' => false,
                ]
            ]
        ],
        'createListing' => [
            'httpMethod' => 'POST',
            'uri' => '/marketplace/listings',
            'summary' => 'Create a Marketplace listing.',
            'responseModel' => 'GetResponse',
            'parameters' => [
                'release_id' => [
                    'type' => 'string',
                    'location' => 'json',
                    'required' => true
                ],
                'condition' => [
                    'type' => 'string',
                    'location' => 'json',
                    'required' => true,
                ],
                'sleeve_condition' => [
                    'type' => 'string',
                    'location' => 'json',
                    'required' => false,
                ],
                'price' => [
                    'type' => 'number',
                    'location' => 'json',
                    'required' => true,
                ],
                'comments' => [
                    'type' => 'string',
                    'location' => 'json',
                    'required' => false,
                ],
                'allow_offers' => [
                    'type' => 'boolean',
                    'location' => 'json',
                    'required' => false,
                ],
                'status' => [
                    'type' => 'string',
                    'location' => 'json',
                    'required' => false,
                ],
                'external_id' => [
                    'type' => 'string',
                    'location' => 'json',
                    'required' => false,
                ],
                'location' => [
                    'type' => 'string',
                    'location' => 'json',
                    'required' => false,
                ],
                'weight' => [
                    'type' => 'number',
                    'location' => 'json',
                    'required' => false,
                ],
                'format_quantity' => [
                    'type' => 'number',
                    'location' => 'json',
                    'required' => false,
                ]
            ]
        ],
        'deleteListing' => [
            'httpMethod' => 'DELETE',
            'uri' => 'marketplace/listings/{listing_id}',
            'responseModel' => 'GetResponse',
            'parameters' => [
                'listing_id' => [
                    'type' => 'string',
                    'location' => 'uri',
                    'required' => true
                ]
            ]
        ]
    ],
    'models' => [
        'GetResponse' => [
            'type' => 'object',
            'additionalProperties' => [
                'location' => 'json'
            ],
        ],
        'GetImage' => [
            'type' => 'object',
            'properties' => [
                'image' => [
                    'location' => 'body',
                    'type' => 'string'
                ]
            ],
        ]
    ]
];
