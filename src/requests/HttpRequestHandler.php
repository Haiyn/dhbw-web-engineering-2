<?php

namespace requests;

interface HttpRequestHandler
{
    /**
     * Handle the request given from the controller
     * @return array * Data depending on the handler, must be given in the form
     * ["message" => "example message.", "data" => ["some_data" => 12]]
     * @throws HttpRequestException * Gets thrown when an error occurs in the request
     */
    public function handle();
}
