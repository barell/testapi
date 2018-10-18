<?php

namespace App\StarWars;

/**
 * Class AbstractService
 *
 * @package App\StarWars
 */
abstract class AbstractService
{
    /**
     * @var Client
     */
    private $client;

    /**
     * AbstractService constructor.
     *
     * @param $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }
}
