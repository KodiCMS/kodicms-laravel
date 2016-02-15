<?php

namespace KodiCMS\API\Helpers;

/**
 * Class Keys.
 */
class Keys
{
    /**
     * @return string
     */
    public function generate()
    {
        $microTime = microtime();
        list($a_dec, $a_sec) = explode(' ', $microTime);

        $dec_hex = dechex($a_dec * 1000000);
        $sec_hex = dechex($a_sec);

        $dec_hex = $this->ensureLength($dec_hex, 5);
        $sec_hex = $this->ensureLength($sec_hex, 6);

        $guid = '';
        $guid .= $dec_hex;
        $guid .= $this->createGuidSection(3);
        $guid .= '-';
        $guid .= $this->createGuidSection(4);
        $guid .= '-';
        $guid .= $this->createGuidSection(4);
        $guid .= '-';
        $guid .= $this->createGuidSection(4);
        $guid .= '-';
        $guid .= $sec_hex;
        $guid .= $this->createGuidSection(6);

        return $guid;
    }

    /**
     * @param string $characters
     *
     * @return string
     */
    protected function createGuidSection($characters)
    {
        $characters = (int) $characters;
        $return = '';

        for ($i = 0; $i < $characters; $i++) {
            $return .= dechex(mt_rand(0, 15));
        }

        return $return;
    }

    /**
     * @param string $string
     * @param string $length
     *
     * @return string
     */
    protected function ensureLength($string, $length)
    {
        $length = (int) $length;
        $strlen = strlen($string);

        if ($strlen < $length) {
            $string = str_pad($string, $length, 0);
        } elseif ($strlen > $length) {
            $string = substr($string, 0, $length);
        }

        return $string;
    }
}
