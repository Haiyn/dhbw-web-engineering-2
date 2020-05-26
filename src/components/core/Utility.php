<?php

namespace components\core;

use components\InternalComponent;

/**
 * Class Utility
 * Various methods needed throughout different functions in the application.
 * Methods must be static.
 * @package components\core
 */
class Utility extends InternalComponent
{
    /**
     * [...] generates VALID RFC 4211 COMPLIANT Universally Unique Identifiers (UUID) version [...] 4 [...].
     *
     * (This function was taken from the official PHP Manual by Andrew Moore:
     * https://www.php.net/manual/en/function.uniqid.php#94959)
     * @return string * generated uuidv4
     */
    public static function generateUUIDv4()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    /**
     * This function opens and returns the contents of the config.ini.php file
     * @param bool $process_sections * Get the ini contents as array with sections or without (default is without)
     * @return array|boolean * array of ini file contents or false on failure
     */
    public static function getIniFile($process_sections = false)
    {
        // Specifies where the ini file is located
        // Edit this if you want to change the location or name of the ini
        $fileLocation = $_SERVER['DOCUMENT_ROOT'];
        $fileName = "config.ini.php";
        $filePath = $fileLocation . DIRECTORY_SEPARATOR . $fileName;

        if (file_exists($filePath)) {
            return parse_ini_file($filePath, $process_sections);
        } else {
            return false;
        }
    }

    /**
     * Checks if a given string is a valid UUIDv4
     * @param string $str * string to check
     * @return boolean * true if valid, false if invalid
     */
    public static function isValidUUIDv4($str)
    {
        if (preg_match('/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i', $str) !== 1) {
            return false;
        }
        return true;
    }
}
