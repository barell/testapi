<?php

namespace App\StarWars\Response;

/**
 * Class Prisoner
 *
 * @package App\StarWars\Response
 */
class Prisoner
{
    /**
     * @var string
     */
    private $cell;

    /**
     * @var string
     */
    private $block;

    /**
     * Prisoner constructor.
     *
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->cell = $response['cell'];
        $this->block = $response['block'];
    }

    /**
     * @return string
     */
    public function getCell(): string
    {
        return $this->cell;
    }

    /**
     * @return string
     */
    public function getBlock(): string
    {
        return $this->block;
    }
}
