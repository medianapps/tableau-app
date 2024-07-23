<?php

namespace App\Http\Controllers;

use App\Helpers\Tableau;

class TableauController extends Controller
{
    /**
     * Creates a token for authentication.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response containing the token.
     *
     * @var string $userName The Tableau email address.
     * @var string $secretId The secret ID to use for authentication.
     * @var string $secretValue The secret value to use for authentication.
     * @var string $clientId The client ID to use for authentication.
     * @var string $scope The scope of the token.
     *
     * @see \App\Helpers\Tableau::createToken()
     */
    public function createToken()
    {
        $userName = "gforceid152@gmail.com"; // The Tableau email address.
        $secretId = "cea287f8-982e-405f-884c-365e23ce1fd8"; // The secret ID to use for authentication.
        $secretValue = "QkyyGUBua+bFvJz1wBDxLTbHRjAd8GgaH3uZufRnHM8="; // The secret value to use for authentication.
        $clientId = "734033af-2d5f-4119-ab59-3e23160abb85"; // The client ID to use for authentication.
        $scope = "tableau:views:embed"; // The scope of the token.

        $token = Tableau::createToken($userName, $secretId, $secretValue, $clientId, $scope);

        return response()->json($token, 200);
    }
}
