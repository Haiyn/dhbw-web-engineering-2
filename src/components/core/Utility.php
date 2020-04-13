<?php

namespace components\core;

use components\InternalComponent;

class Utility extends InternalComponent
{
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
}
