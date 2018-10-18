<?php

namespace App\Tests;

use App\Service\DroidSpeak;
use PHPUnit\Framework\TestCase;

class DroidSpeakTest extends TestCase
{
    public function testEmpty()
    {
        $droidSpeak = new DroidSpeak();

        $this->assertEquals('', $droidSpeak->translate(''));
    }

    public function testSpace()
    {
        $droidSpeak = new DroidSpeak();

        $this->assertEquals('', $droidSpeak->translate('  '));
    }

    public function testCellWord()
    {
        $droidSpeak = new DroidSpeak();
        $input = '01000011 01100101 01101100 01101100 00100000 00110010 00110001 00111000 00110111';

        $this->assertEquals('Cell 2187', $droidSpeak->translate($input));
    }

    public function testRandomBytes()
    {
        $droidSpeak = new DroidSpeak();
        $input = '010011100101101';

        $this->assertEquals('-', $droidSpeak->translate($input));
    }

    public function testNonBytesString()
    {
        $droidSpeak = new DroidSpeak();
        $input = '1234test';

        $this->assertEquals('', $droidSpeak->translate($input));
    }

    public function testWithInvalidChar()
    {
        $droidSpeak = new DroidSpeak();
        $input = '01100010 01012012 01110101 01100100';

        $this->assertEquals('bud', $droidSpeak->translate($input));
    }


    public function testWithExtraSpacing()
    {
        $droidSpeak = new DroidSpeak();
        $input = ' 01100010   01110101 01100100  ';

        $this->assertEquals('bud', $droidSpeak->translate($input));
    }
}
