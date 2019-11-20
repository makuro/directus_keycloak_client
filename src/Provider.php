<?php
/**
 * PHP version 7
 * Application: directus_keycloak_client
 *
 * @category OAuth_2_Client_Library_Usage_For_Keycloak_With_Directus
 * @package  Makuro\Directus\KeycloakClient\Provider
 * @author   Eric Delaporte <eric.delaporte@build-ideas.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://packagist.org/packages/makuro/directus_keycloak_client
 * Date: 19.11.19
 * Time: 23:59
 */
namespace Makuro\Directus\KeycloakClient;

use Directus\Authentication\Sso\TwoSocialProvider;
use League\OAuth2\Client\Provider\AbstractProvider;
use Makuro\Directus\KeycloakClient\Provider\Keycloak;

/**
 * Class Provider
 *
 * @category OAuth_2_Client_Library_Usage_For_Keycloak_With_Directus
 * @package  Makuro\Directus\KeycloakClient\Provider
 * @author   Eric Delaporte <eric.delaporte@build-ideas.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://packagist.org/packages/makuro/directus_keycloak_client
 */
class Provider extends TwoSocialProvider
{
    /**
     * Holds the actual provider class
     *
     * @var Keycloak
     */
    protected $provider = null;

    /**
     * Returns scopes
     *
     * @inheritDoc
     * @return     array
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
        $this->provider = new Keycloak(
            [
                'authServerUrl'         => $this->config->get('authServerUrl'),
                'realm'                 => $this->config->get('realm'),
                'clientId'              => $this->config->get('clientId'),
                'clientSecret'          => $this->config->get('clientSecret'),
                'redirectUri'           => $this->getRedirectUrl()
            ]
        );

        return $this->provider;
    }


}