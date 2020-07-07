<?php

return [
    'connections' => [
        'pubsub' => [
            'driver' => 'pubsub',
            'projectId' => env('GOOGLE_CLOUD_PROJECT_ID'),
        ]
    ]
];
