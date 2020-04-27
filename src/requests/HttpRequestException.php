<?php

namespace requests;

use Exception;

class HttpRequestException extends Exception
{
    private $params;

    public function __construct($message, $params = [])
    {
        $this->params = $params;
        parent::__construct($message);
    }

    /**
     * Return array of params
     * @return array * Array of params
     */
    public function getParams()
    {
        return $this->params;
    }
}
