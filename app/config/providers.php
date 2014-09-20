<?php

return [
    'wordpress' => [
        'class'        => 'Wordpress',
        'clientId'     => 36936,
        'clientSecret' => '2ZHd5XFTx2rdY7G5g0bOuyMJTxVHuZF8JyjYP6pNYah7dE0T7BEdkxGhQnP2R765',
        'redirectUri'  => 'http://iwant.nl/login/return/wordpress',
        'headers'      => [
            'Authorization' => 'Bearer {token}'
        ],
        'mediaUri' => 'https://public-api.wordpress.com/rest/v1/sites/{blog_id}/media'
    ],
    'bol' => [
        'apikey' => 'E9D7DA0A32024A7AA6D618F452047479'
    ]
];