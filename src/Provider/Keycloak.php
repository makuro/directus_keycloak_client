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
namespace Makuro\Directus\KeycloakClient\Provider;

use Exception;
use Firebase\JWT\JWT;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Makuro\Directus\KeycloakClient\Provider\Exception\EncryptionConfigurationException; // @codingStandardsIgnoreLine
use Psr\Http\Message\ResponseInterface;

/**
 * Class Keycloak
 *
 * @category OAuth_2_Client_Library_Usage_For_Keycloak_With_Directus
 * @package  Makuro\Directus\KeycloakClient\Provider
 * @author   Eric Delaporte <eric.delaporte@build-ideas.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://packagist.org/packages/makuro/directus_keycloak_client
 */
class Keycloak extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * Keycloak URL, eg. http://localhost:8080/auth.
     *
     * @var string
     */
    public $authServerUrl = null;

    /**
     * Realm name, eg. demo.
     *
     * @var string
     */
    public $realm = null;

    /**
     * Encryption algorithm.
     *
     * You must specify supported algorithms for your application. See
     * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
     * for a list of spec-compliant algorithms.
     *
     * @var string
     */
    public $encryptionAlgorithm = null;

    /**
     * Encryption key.
     *
     * @var string
     */
    public $encryptionKey = null;

    /**
     * Attempts to decrypt the given response.
     *
     * @param string|array|null $response Response received
     *
     * @throws EncryptionConfigurationException
     *
     * @return string|array|null
     */
    public function decryptResponse($response)
    {
        if (!is_string($response)) {
            return $response;
        }

        if ($this->usesEncryption()) {
            return json_decode(
                json_encode(
                    JWT::decode(
                        $response,
                        $this->encryptionKey,
                        array($this->encryptionAlgorithm)
                    )
                ),
                true
            );
        }

        throw EncryptionConfigurationException::undeterminedEncryption();
    }

    /**
     * Constructs an OAuth 2.0 service provider.
     *
     * @param array $options       An array of options to set on this
     *                             provider.
     *                             Options include `clientId`, `clientSecret`,
     *                             `redirectUri`, and `state`.
     *                             Individual providers may introduce more
     *                             options, as needed.
     * @param array $collaborators An array of collaborators that may be used to
     *                             override this provider's default behavior.
     *                             Collaborators include `grantFactory`,
     *                             `requestFactory`, `httpClient`, and
     *                             `randomFactory`.
     *                             Individual providers may introduce more
     *                             collaborators, as needed.
     */
    public function __construct(array $options = [], array $collaborators = [])
    {
        if (isset($options['encryptionKeyPath'])) {
            $this->setEncryptionKeyPath($options['encryptionKeyPath']);
            unset($options['encryptionKeyPath']);
        }
        parent::__construct($options, $collaborators);
    }

    /**
     * Get authorization url to begin OAuth flow
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return $this->getBaseUrlWithRealm().'/protocol/openid-connect/auth';
    }

    /**
     * Get access token url to retrieve token
     *
     * @param array $params params read from url
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->getBaseUrlWithRealm().'/protocol/openid-connect/token';
    }

    /**
     * Get provider url to fetch user details
     *
     * @param AccessToken $token access token provided by keycloak
     *
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->getBaseUrlWithRealm().'/protocol/openid-connect/userinfo';
    }

    /**
     * Builds the logout URL.
     *
     * @param array $options options set for logout
     *
     * @return string Authorization URL
     */
    public function getLogoutUrl(array $options = [])
    {
        $base = $this->getBaseLogoutUrl();
        $params = $this->getAuthorizationParameters($options);
        $query = $this->getAuthorizationQuery($params);
        return $this->appendQuery($base, $query);
    }

    /**
     * Get logout url to logout of session token
     *
     * @return string
     */
    private function getBaseLogoutUrl() // @codingStandardsIgnoreLine
    {
        return $this->getBaseUrlWithRealm() . '/protocol/openid-connect/logout';
    }

    /**
     * Creates base url from provider configuration.
     *
     * @return string
     */
    public function getBaseUrlWithRealm()
    {
        return $this->authServerUrl.'/realms/'.$this->realm;
    }

    /**
     * Get the default scopes used by this provider.
     *
     * This should not be a complete list of all scopes, but the minimum
     * required for the provider user interface!
     *
     * @return string[]
     */
    protected function getDefaultScopes()
    {
        return ['name', 'email'];
    }

    /**
     * Check a provider response for errors.
     *
     * @param ResponseInterface $response interface to give the response to
     * @param string            $data     Parsed response data
     *
     * @throws IdentityProviderException
     *
     * @return void
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (!empty($data['error'])) {
            $error = $data['error'].': '.$data['error_description'];
            throw new IdentityProviderException($error, 0, $data);
        }
    }

    /**
     * Generate a user object from a successful user details request.
     *
     * @param array       $response contains response informations
     * @param AccessToken $token    keycloaks access token
     *
     * @return KeycloakResourceOwner
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new KeycloakResourceOwner($response);
    }

    /**
     * Requests and returns the resource owner of given access token.
     *
     * @param AccessToken $token keycloak access token
     *
     * @return KeycloakResourceOwner
     * @throws EncryptionConfigurationException
     */
    public function getResourceOwner(AccessToken $token)
    {
        $response = $this->fetchResourceOwnerDetails($token);

        $response = $this->decryptResponse($response);

        return $this->createResourceOwner($response, $token);
    }

    /**
     * Updates expected encryption algorithm of Keycloak instance.
     *
     * @param string $encryptionAlgorithm algorithm to be used
     *
     * @return Keycloak
     */
    public function setEncryptionAlgorithm($encryptionAlgorithm)
    {
        $this->encryptionAlgorithm = $encryptionAlgorithm;

        return $this;
    }

    /**
     * Updates expected encryption key of Keycloak instance.
     *
     * @param string $encryptionKey encryption key to use
     *
     * @return Keycloak
     */
    public function setEncryptionKey($encryptionKey)
    {
        $this->encryptionKey = $encryptionKey;

        return $this;
    }

    /**
     * Updates expected encryption key of Keycloak instance to content of given
     * file path.
     *
     * @param string $encryptionKeyPath encryption key path to use
     *
     * @return Keycloak
     */
    public function setEncryptionKeyPath(string $encryptionKeyPath)
    {
        try {
            $this->encryptionKey = file_get_contents($encryptionKeyPath);
        } catch (Exception $e) {
            // Not sure how to handle this yet.
        }

        return $this;
    }

    /**
     * Checks if provider is configured to use encryption.
     *
     * @return bool
     */
    public function usesEncryption()
    {
        return (bool) $this->encryptionAlgorithm && $this->encryptionKey;
    }
}
