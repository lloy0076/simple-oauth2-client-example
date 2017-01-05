<?php

require('./vendor/autoload.php');

/**
 * Page gets the authorization URL and performs the redirect.
 */

use App\AuthCodeHandler;

// Required by the AuthCodeHandler.
$dotenv = new Dotenv(__DIR__);
$dotenv->load();

$authHandler      = new AuthCodeHandler();
$authorizationUrl = $authHandler->getAuthorizationRedirect();

header("Location: $authorizationUrl");
