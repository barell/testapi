<?php

require 'vendor/autoload.php';

$config = [
    'clientId'      => 'R2D2',
    'clientSecret'  => 'Alderan',
    'certFilePath'  => 'resources/cert.pem',
    'tokenFilePath' => 'var/token.json'
];

/**
 * Create and configure client object
 */
$client = new App\StarWars\Client();
$client->setCredentials($config['clientId'], $config['clientSecret']);
$client->setCertFilePath($config['certFilePath']);
$client->setGrantType('client_credentials');

/**
 * Load access token data from file and check if valid - if not,
 * request new token from /Token endpoint and save to file
 */

try {
    $token = json_decode(file_get_contents($config['tokenFilePath']), true);
    $client->setAccessToken($token);

    if (!$client->getAccessToken()->isValid()) {
        $token = $client->requestNewAccessToken();

        file_put_contents($config['tokenFilePath'], json_encode($token));
    }
} catch (\Exception $e) {
    echo 'Authentication failed: ' . $e->getMessage();
    exit(1);
}

/**
 * Example API calls
 */
$service = new \App\StarWars\Service($client);
$prisoner = $service->prisoner('leia');

$prisoner->getCell();
$prisoner->getBlock();

$reactorService = new \App\StarWars\Service\Reactor($client);
$reactorService->exhaust(1);

/**
 * Example usage of DroidSpeak and Mission services
 */
$droidSpeakService = new \App\Service\DroidSpeak();
$missionService = new \App\Service\Mission(
    $droidSpeakService,
    $service
);

$response = $missionService->hack('leia');