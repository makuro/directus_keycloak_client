<?php
/**
 * Application: directus_keycloak_client
 * Author: Eric Delaporte <eric.delaporte@build-ideas.de>
 * Date: 19.11.19
 * Time: 23:59
 */
namespace Makuro\Directus\KeycloakClient;

use Directus\Authentication\Sso\TwoSocialProvider;
use League\OAuth2\Client\Provider\AbstractProvider;
use Makuro\Directus\KeycloakClient\Provider\Keycloak;

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
     * @return AbstractProvider
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