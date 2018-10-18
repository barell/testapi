<?php

namespace App\Service;

/**
 * Class DroidSpeak
 *
 * @package App\Service
 */
class DroidSpeak
{
    /**
     * @param string $input
     * @return string
     */
    public function translate(string $input)
    {
        $input = trim($input);

        return implode(array_map([$this, 'bin2ascii'], explode(' ', $input)));
    }

    /**
     * @param string $binary
     * @return string
     */
    private function bin2ascii(string $binary)
    {
        $binary = trim($binary);

        if (!preg_match('/^[01 ]+$/', $binary)) {
            return '';
        }

        return chr(bindec($binary));
    }
}