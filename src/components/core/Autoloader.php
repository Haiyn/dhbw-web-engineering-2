<?php

namespace components\core;

use components\InternalComponent;

require_once 'components/InternalComponent.php';

class Autoloader extends InternalComponent
{
    /**
     * Registers all active autoloaders
     */
    public function register()
    {
        spl_autoload_register(array($this, "classAutoLoader"));
    }

    /**
     * Automatic loading of classes
     * @param $className
     */
    private function classAutoLoader($className)
    {
        $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
        $file = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $className . ".php";
        if (is_readable($file)) {
            require_once $file;
        }
    }
}
