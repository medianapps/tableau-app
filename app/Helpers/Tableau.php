<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Http;

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
            'exp' => time() + 600, // Expiration time of the token (600 seconds from now)
        ];

        return JWT::encode($claimSet, $secretValue, 'HS256', null, $header);
    }

    /**
     * Authenticate with Tableau Server and get the auth token
     *
     * @param string $username The username of the user to authenticate
     * @param string $secretId The ID of the secret key used to sign the token
     * @param string $secretValue The value of the secret key used to sign the token
     * @param string $clientId The ID of the client that is making the request
     * @param string $scope The scope of the request (e.g. "webauthoring")
     * @return array The authentication token and site ID
     */
    public static function authenticate($username, $secretId, $secretValue, $clientId, $scope)
    {
        $jwtToken = self::createToken($username, $secretId, $secretValue, $clientId, $scope);
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post('https://prod-apsoutheast-a.online.tableau.com/api/3.2/auth/signin', [
            'credentials' => [
                'name' => $username,
                'jwt' => $jwtToken,
                'site' => [
                    'contentUrl' => ''
                ]
            ]
        ]);


        dd($response);

        if ($response->failed()) {
            throw new \Exception('Failed to authenticate with Tableau.');
        }

        $data = $response->json();
        return [
            'token' => $data['credentials']['token'],
            'siteId' => $data['credentials']['site']['id'],
            'userId' => $data['credentials']['user']['id']
        ];
    }

    /**
     * Get permissions for a specific workbook
     *
     * @param string $authToken The authentication token
     * @param string $siteId The site ID
     * @param string $workbookId The ID of the workbook to get permissions for
     * @return array The permissions for the workbook
     */
    public static function getPermissions($authToken, $siteId, $workbookId)
    {
        $response = Http::withHeaders([
            'X-Tableau-Auth' => $authToken
        ])->get("https://prod-apsoutheast-a.online.tableau.com/api/3.9/sites/{$siteId}/workbooks/{$workbookId}/permissions");

        if ($response->failed()) {
            throw new \Exception('Failed to get permissions from Tableau.');
        }

        return $response->json();
    }
}
