<?php
return array(
    'Books\\V1\\Rest\\Books\\Controller' => array(
        'description' => 'Create, manipulate, and retrieve books.',
        'collection' => array(
            'description' => 'Manipulate lists of books.',
            'POST' => array(
                'description' => 'Create a book.',
            ),
            'GET' => array(
                'description' => 'Retrieve a paginated list of books.',
            ),
        ),
        'entity' => array(
            'GET' => array(
                'description' => 'Retrieve a book.',
            ),
            'PATCH' => array(
                'description' => 'Update a book.',
            ),
            'PUT' => array(
                'description' => 'Replace a book.',
            ),
            'DELETE' => array(
                'description' => 'Delete a book.',
            ),
        ),
    ),
    'Books\\V1\\Rpc\\Hello\\Controller' => array(
        'description' => 'Sends greeting',
        'GET' => array(
            'description' => 'Pings the API for the acknoledgement',
            'response' => '{
    "ack": "acknowledge with timestamp"
}',
        ),
    ),
);
