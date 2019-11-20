<?php
/**
 * Application: directus_keycloak_client
 * Author: Eric Delaporte <eric.delaporte@build-ideas.de>
 * Date: 19.11.19
 * Time: 23:59
 */
namespace Makuro\Directus\KeycloakClient\Provider\Exception;

use Exception;

/**
 * Class EncryptionConfigurationException
 * @package Makuro\Directus\KeycloakClient\Provider\Exception
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
