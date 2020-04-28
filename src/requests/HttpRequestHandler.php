<?php

namespace requests;

interface HttpRequestHandler
{
    /**
     * Handle the request given from the controller
     * @return array * Data depending on the handler
     * @throws HttpRequestException * Gets thrown when an error occurs in the request
     */
    public function handle();
}
