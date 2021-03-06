<?php

namespace App;

use App\AppLogger;

/**
 * Class to provide simple authentication handling.
 */
class AuthCodeHandler {
    /**
     * The state key; used as a further form of CSRF.
     */
    const STATE_KEY = 'oauth2state';

    /**
     * The League/Oauth2-Client Generic Provider.
     */
    private $provider;

    /**
     * Provider options.
     */
    private $providerOptions;

    /**
     * Constructs a new AuthCodeHandler.
     * 
     * This requires the following environment variables to be set:
     *
     * - @b CLIENT_ID
     *   As provided by the Oauth2 provider.
     * - @b CLIENT_SECRET
     *   As provided by the Oauth2 provider.
     * - @b CLIENT_REDIRECT_URI
     *   As agreed with the Oauth2 provider. WARNING: The Oauth2 provider and
     *   the requestor (i.e. the code using this class) must agree on this.
     * - @b URL_AUTHORIZE
     *   The URL to redirect the requestor to.
     * - @b URL_ACCESS_TOKEN
     *   The URL to request the longer term access and refresh tokens from.
     * .
     *
     * It is suggested that a library such as @code vlucas/dotenv be utilised.
     *
     * @param  $owner_details A URL that describes the owner/requestor. 
     * @return An AuthCodeHandler class.
     */
    public function __construct($owner_details = 'http://localhost/') {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $this->providerOptions = [
             'clientId'       => getenv('CLIENT_ID'),
             'clientSecret'   => getenv('CLIENT_SECRET'),
             'redirectUri'    => getenv('CLIENT_REDIRECT_URI'),
             'urlAuthorize'   => getenv('URL_AUTHORIZE'),
             'urlAccessToken' => getenv('URL_ACCESS_TOKEN'),

             // Technically not neccessary in my opinion, but the provider
             // requires it.
             'urlResourceOwnerDetails' => $owner_details,
        ];

        if (isset($_REQUEST['scopes']) && $_REQUEST['scopes'] != '') {
            $this->providerOptions['scopes'] = $_REQUEST['scopes'];
        }

        $this->provider = new \League\OAuth2\Client\Provider\GenericProvider(
            $this->providerOptions
        );
        
        return $this;
    }

    /**
     * Gets the URL to redirect for authorization.
     * 
     * @param  $setState Whether the state should be set in the PHP session;
     *                   default (true).
     * @param  $stateKey The key to use; default (self::STATE-KEY).
     * @return An appropriate URL.
     */
    public function getAuthorizationRedirect($setState = true, $stateKey = self::STATE_KEY) {
        $authorizationUrl = $this->provider->getAuthorizationUrl();

        if ($setState === true) {
            $this->state = $this->provider->getState();

            $_SESSION[$stateKey] = $this->state;
        } 

        return $authorizationUrl;
    }

    /**
     * Gets the access token.
     *
     * @param  $setState Whether the state should be set in the PHP session;
     *                   default (true).
     * @param  $stateKey The key to use; default (self::STATE-KEY).
     * @return $token       The token.
     * @throws \Exception if the client code is invalid.
     */
    public function getAccessToken($setState = true, $stateKey = self::STATE_KEY) {
        if ($setState === true && $_REQUEST['state'] != $_SESSION[$stateKey]) {
            throw new \Exception('Invalid Authentication');
        }

        $token = $this->provider->getAccessToken(
            'authorization_code',
            [ 'code' => $_REQUEST['code'], ]
        );

        return $token;
    }
}
