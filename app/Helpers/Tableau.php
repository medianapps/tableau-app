<?php

namespace App\Helpers;

use Firebase\JWT\JWT;

class Tableau
{
    /**
     * Create a JWT token for Tableau authentication
     *
     * @param string $username The username of the user to authenticate
     * @param string $secretId The ID of the secret key used to sign the token
     * @param string $secretValue The value of the secret key used to sign the token
     * @param string $clientId The ID of the client that is making the request
     * @param string $scope The scope of the request (e.g. "webauthoring")
     * @return string The JWT token
     */
    public static function createToken($username, $secretId, $secretValue, $clientId, $scope)
    {
        $header = [
            'alg' => 'HS256', // Algorithm used to sign the token
            'typ' => 'JWT', // Type of the token
            'iss' => $clientId, // Issuer of the token (client ID)
            'kid' => $secretId, // Key ID of the secret key used to sign the token
        ];

        $claimSet = [
            'sub' => $username, // Subject of the token (username)
            'aud' => 'tableau', // Audience of the token (Tableau server)
            'nbf' => time() - 100, // Not before timestamp (100 seconds ago)
            'jti' => strval(time()), // JWT ID (unique identifier)
            'iss' => $clientId, // Issuer of the token (client ID)
            'scp' => explode(',', $scope), // Scope of the request
            'exp' => time() + 600, // Expiration time of the token (100 seconds from now)
        ];

        $jwt = JWT::encode($claimSet, $secretValue, 'HS256', null, $header);

        return $jwt;
    }
}
