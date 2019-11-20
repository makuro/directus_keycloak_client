<?php
/**
 * Application: directus
 * Author: Eric Delaporte <eric.delaporte@build-ideas.de>
 * Date: 13.11.19
 * Time: 15:13
 */


namespace Directus\Authentication\Sso\Provider\Keycloak;

require_once 'Provider/Keycloak.php';
require_once 'Provider/KeycloakResourceOwner.php';

use Directus\Authentication\Sso\TwoSocialProvider;
use Directus\Authentication\Sso\Provider\Keycloak\Provider\Keycloak;

class Provider extends TwoSocialProvider
{
    /**
     * @var Keycloak
     */
    protected $provider = null;

    /**
     * @inheritdoc
     */
    public function getScopes()
    {
        return [
            'email'
        ];
    }

    /**
     * Creates the Google provider oAuth client
     *
     * @return Google
     */
    protected function createProvider()
    {
        $this->provider = new Keycloak([
            'authServerUrl'         => $this->config->get('authServerUrl'),
            'realm'                 => $this->config->get('realm'),
            'clientId'              => $this->config->get('clientId'),
            'clientSecret'          => $this->config->get('clientSecret'),
            'redirectUri'           => $this->getRedirectUrl()
        ]);

        return $this->provider;
    }


}