<?php

return [
    'exports' => [
        'chunk_size' => 1000,
    ],
    'imports' => [
        'heading' => 'slugged',
        'force_sheets_collection' => false,
    ],
    'extension_detector' => [
        'xlsx' => 'Excel2007',
        'csv'  => 'Csv',
    ],
];
