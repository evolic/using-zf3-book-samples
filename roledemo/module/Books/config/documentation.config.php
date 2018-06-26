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
);
