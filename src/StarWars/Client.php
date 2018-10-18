<?php

namespace App\StarWars;

use App\StarWars\Exception\InvalidAccessTokenDataException;
use App\StarWars\Exception\InvalidResponse;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Client
 *
 * @package App\StarWars
 */
class Client
{
    const API_BASE_URL = 'https://death.star.api/';

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $clientSecret;

    /**
     * @var string
     */
    private $certFilePath;

    /**
     * @var string
     */
    private $grantType;

    /**
     * @var AccessToken
     */
    private $accessToken;

    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @param string $clientId
     * @param string $clientSecret
     */
    public function setCredentials(string $clientId, string $clientSecret): void
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * @param string $certFilePath
     */
    public function setCertFilePath(string $certFilePath): void
    {
        $this->certFilePath = $certFilePath;
    }

    /**
     * @param string $grantType
     */
    public function setGrantType(string $grantType): void
    {
        $this->grantType = $grantType;
    }

    /**
     * @param array $data
     * @throws InvalidAccessTokenDataException
     */
    public function setAccessToken(array $data): void
    {
        $this->accessToken = new AccessToken();

        if (!isset($data['access_token']) || empty($data['access_token'])) {
            throw new InvalidAccessTokenDataException(
                'Missing access token value'
            );
        }

        if (!isset($data['expires_in']) || empty($data['expires_in'])) {
            throw new InvalidAccessTokenDataException(
                'Missing access token expiration time'
            );
        }

        $this->accessToken->setValue($data['access_token']);
        $this->accessToken->setExpiresIn($data['expires_in']);

        if (isset($data['token_type'])) {
            $this->accessToken->setType($data['token_type']);
        }

        if (isset($data['scope'])) {
            $this->accessToken->setScope($data['scope']);
        }
    }

    /**
     * @return AccessToken
     */
    public function getAccessToken(): AccessToken
    {
        return $this->accessToken;
    }

    /**
     * @return AccessToken
     * @throws InvalidAccessTokenDataException
     * @throws InvalidResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function requestNewAccessToken(): AccessToken
    {
        $token = $this->send('POST', 'token', [
            'body'    => [
                'grant_type'    => $this->grantType,
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret
            ],
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ]
        ], false);

        $this->setAccessToken($token);

        return $this->getAccessToken();
    }

    /**
     * @param string $url
     * @param array $headers
     * @return mixed
     * @throws InvalidResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get(string $url, array $headers = [])
    {
        return $this->send('GET', $url, [
            'headers' => $headers
        ]);
    }

    /**
     * @param string $url
     * @param array $params
     * @param array $headers
     * @return mixed
     * @throws InvalidResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post(string $url, array $params = [], array $headers = [])
    {
        return $this->send('POST', $url, [
            'body'    => $params,
            'headers' => $headers
        ]);
    }

    /**
     * @param string $url
     * @param array $headers
     * @return mixed
     * @throws InvalidResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete(string $url, array $headers = [])
    {
        return $this->send('DELETE', $url, [
            'headers' => $headers
        ]);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $options
     * @param bool $authorize
     * @return mixed
     * @throws InvalidResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function send(string $method, string $url, array $options = [], bool $authorize = true)
    {
        if ($authorize) {
            if (!isset($options['headers'])) {
                $options['headers'] = [];
            }

            $options['headers']['Authorization'] = 'Bearer ' . $this->getAccessToken()->getValue();
        }

        $response = $this->getHttpClient()->request($method, $url, $options);

        return $this->handleJsonResponse($response);
    }

    /**
     * @param ResponseInterface $response
     * @return mixed
     * @throws InvalidResponse
     */
    private function handleJsonResponse(ResponseInterface $response)
    {
        $data = json_decode($response->getBody(), true);

        if (json_last_error() != JSON_ERROR_NONE) {
            throw new InvalidResponse('Invalid JSON object received');
        }

        return $data;
    }

    /**
     * @return ClientInterface
     */
    protected function getHttpClient(): ClientInterface
    {
        if ($this->httpClient === null) {

            $options = [
                'base_uri' => self::API_BASE_URL,
                'headers'  => [
                    'Content-Type' => 'application/json'
                ]
            ];

            if (!empty($this->certFilePath)) {
                $options['cert'] = $this->certFilePath;
            }

            $this->httpClient = new \GuzzleHttp\Client($options);
        }

        return $this->httpClient;
    }
}
