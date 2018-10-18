<?php

namespace App\Service;

use App\StarWars\Service;

/**
 * Class Mission
 *
 * @package App\Service
 */
class Mission
{
    /**
     * @var DroidSpeak
     */
    private $droidSpeak;

    /**
     * @var Service
     */
    private $starWarsService;

    /**
     * Mission constructor.
     *
     * @param DroidSpeak $droidSpeak
     * @param Service $starWarsService
     */
    public function __construct(
        DroidSpeak $droidSpeak,
        Service $starWarsService
    ) {
        $this->droidSpeak = $droidSpeak;
        $this->starWarsService = $starWarsService;
    }

    /**
     * @param string $prisonerName
     * @return array
     * @throws \App\StarWars\Exception\InvalidResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function hack(string $prisonerName)
    {
        $prisoner = $this->starWarsService->prisoner($prisonerName);

        return [
            'cell'  => $this->droidSpeak->translate($prisoner->getCell()),
            'block' => $this->droidSpeak->translate($prisoner->getBlock())
        ];
    }
}
