<?php

require_once('./vendor/autoload.php');

/**
 * Page simply sets up a basic form, with all fields disabled except the submit
 * button, to being the authorisation test.
 */
use Dotenv\Dotenv;

$dotenv = new Dotenv(__DIR__);
$dotenv->load();

$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
$twig   = new Twig_Environment(
    $loader, 
    [
        'cache' => __DIR__ . '/cache',
        'debug' => getenv('TWIG_DEBUG'),
    ]
);

dump($twig);

$data = [
    'client_id'    => getenv('CLIENT_ID'),
    'redirect_uri' => getenv('CLIENT_REDIRECT_URI'),
];

print $twig->render('index.html', [ 'data' => $data ]);
