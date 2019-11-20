<?php
/**
 * PHP version 7
 * Application: directus_keycloak_client
 * @package Makuro\Directus\KeycloakClient\Provider
 * @author Eric Delaporte <eric.delaporte@build-ideas.de>
 * @license MIT
 * @link https://packagist.org/packages/makuro/directus_keycloak_client
 * @category OAuth 2 Client library usage for keycloak with directus
 * Date: 19.11.19
 * Time: 23:59
 */
namespace Makuro\Directus\KeycloakClient\Provider\Exception;

use Exception;

/**
 * Class EncryptionConfigurationException
 *
 * @package Makuro\Directus\KeycloakClient\Provider\Exception
 * @author Eric Delaporte <eric.delaporte@build-ideas.de>
 * @license MIT
 * @link https://packagist.org/packages/makuro/directus_keycloak_client
 * @category OAuth 2 Client library usage for keycloak with directus
 */
class EncryptionConfigurationException extends Exception
{
    /**
     * Returns properly formatted exception when response decryption fails.
     *
     * @return EncryptionConfigurationException
     */
    public static function undeterminedEncryption()
    {
        return new static(
            'The given response may be encrypted and sufficient '.
            'encryption configuration has not been provided.',
            400
        );
    }
}
