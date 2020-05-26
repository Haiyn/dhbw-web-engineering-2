<?php

namespace requests;

use Exception;

/**
 * Class HttpRequestException
 * Thrown by httpRequestHandlers. Contains all information for the response.
 * @package requests
 */
class HttpRequestException extends Exception
{
    private $data;

    public function __construct($message, $data = [])
    {
        $this->data = $data;
        parent::__construct($message);
    }

    /**
     * Return array of data
     * @return array * Array of data
     */
    public function getData()
    {
        return $this->data;
    }
}
