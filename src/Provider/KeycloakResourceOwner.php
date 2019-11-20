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

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

/**
 * Class KeycloakResourceOwner
 *
 * @category OAuth_2_Client_Library_Usage_For_Keycloak_With_Directus
 * @package  Makuro\Directus\KeycloakClient\Provider
 * @author   Eric Delaporte <eric.delaporte@build-ideas.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://packagist.org/packages/makuro/directus_keycloak_client
 */
class KeycloakResourceOwner implements ResourceOwnerInterface
{
    /**
     * Raw response
     *
     * @var array
     */
    protected $response;

    /**
     * Creates new resource owner.
     *
     * @param array $response response to be used later
     */
    public function __construct(array $response = [])
    {
        $this->response = $response;
    }

    /**
     * Get resource owner id
     *
     * @return string|null
     */
    public function getId()
    {
        return $this->response['sub'] ?: null;
    }

    /**
     * Get resource owner email
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->response['email'] ?: null;
    }

    /**
     * Get resource owner name
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->response['name'] ?: null;
    }

    /**
     * Return all of the owner details available as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }
}
