<?php

namespace App\StarWars\Service;

use App\StarWars\AbstractService;

/**
 * Class Reactor
 *
 * @package App\StarWars\Service
 */
class Reactor extends AbstractService
{
    /**
     * @param int $id
     * @param int $torpedoes
     * @return mixed
     * @throws \App\StarWars\Exception\InvalidResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function exhaust(int $id, int $torpedoes = 2)
    {
        return $this->getClient()->delete('reactor/exhaust/' . $id, [
            'headers' => [
                'x-torpedoes' => $torpedoes
            ]
        ]);
    }
}
