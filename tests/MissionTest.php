<?php

namespace App\Tests;

use App\Service\DroidSpeak;
use App\Service\Mission;
use App\StarWars\Client;
use App\StarWars\Service;
use PHPUnit\Framework\TestCase;

class MissionTest extends TestCase
{
    public function testSuccess()
    {
        $json = '{  
           "cell":"01000011 01100101 01101100 01101100 00100000 00110010 00110001 00111000 00110111",
           "block":"01000100 01100101 01110100 01100101 01101110 01110100 01101001 01101111 01101110 00100000 01000010 01101100 01101111 01100011 01101011 00100000 01000001 01000001 00101101 00110010 00110011 00101100"
        }';

        $clientStub = $this->createMock(Client::class);
        $clientStub->method('get')
            ->willReturn(json_decode($json, true));

        $mission = new Mission(
            new DroidSpeak(),
            new Service($clientStub)
        );

        $response = $mission->hack('leia');

        $this->assertArrayHasKey('cell', $response);
        $this->assertArrayHasKey('block', $response);

        $this->assertEquals('Cell 2187', $response['cell']);
        $this->assertEquals('Detention Block AA-23,', $response['block']);
    }

    public function testEmpty()
    {
        $json = '{  
           "cell":"",
           "block":""
        }';

        $clientStub = $this->createMock(Client::class);
        $clientStub->method('get')
            ->willReturn(json_decode($json, true));

        $mission = new Mission(
            new DroidSpeak(),
            new Service($clientStub)
        );

        $response = $mission->hack('leia');

        $this->assertArrayHasKey('cell', $response);
        $this->assertArrayHasKey('block', $response);

        $this->assertEquals('', $response['cell']);
        $this->assertEquals('', $response['block']);
    }
}