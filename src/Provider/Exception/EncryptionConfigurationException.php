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
namespace Makuro\Directus\KeycloakClient\Provider\Exception;

use Exception;

/**
 * Class EncryptionConfigurationException
 *
 * @category OAuth_2_Client_Library_Usage_For_Keycloak_With_Directus
 * @package  Makuro\Directus\KeycloakClient\Provider
 * @author   Eric Delaporte <eric.delaporte@build-ideas.de>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://packagist.org/packages/makuro/directus_keycloak_client
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
