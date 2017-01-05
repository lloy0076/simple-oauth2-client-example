<?php

require_once('./vendor/autoload.php');

/**
 * Page receives response from the Oauth2 server.
 *
 * If successful, a token object will be returned.
 */

use App\AuthCodeHandler;

use Carbon\Carbon;
use Dotenv\Dotenv;

$dotenv = new Dotenv(__DIR__);
$dotenv->load();

$errors = [];

try {
    $authHandler = new AuthCodeHandler();

    $token = $authHandler->getAccessToken();
} catch (\Exception $e) {
    $errors[] = $e->getMessage();
}

$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
$twig = new Twig_Environment(
    $loader, 
    [
        'cache' => __DIR__ . '/cache',
        'debug' => true,
    ]
);

if (count($errors) > 0) {
    $error_msgs = implode("<br />", $errors);
    $data = [
        'Error' => $error_msgs,
    ];
} else {
    // NB. The token expiry is a timestamp from Epoch!
    $expires = $token->getExpires();
    $time    = Carbon::createFromTimestamp($expires)->toDateTimeString();

    $data = [
        'Token'             => $token->getToken(),
        'Refresh Token'     => $token->getRefreshToken(),
        'Expires'           => $time,
        'Orig Expires'      => $expires,
        'Has Expired (Y/N)' => $token->hasExpired() ? 'Y' : 'N',
    ];
}

print $twig->render('auth.html', [ 'errors' => $errors, 'data' => $data, ]);
