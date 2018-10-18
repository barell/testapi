<?php

namespace App\StarWars;

use App\StarWars\Response\Prisoner;

/**
 * Class Service
 *
 * @package App\StarWars
 */
class Service extends AbstractService
{
    /**
     * @param string $name
     * @return Prisoner
     * @throws Exception\InvalidResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function prisoner(string $name)
    {
        return new Prisoner($this->getClient()->get('prisoner/' . $name));
    }
}
