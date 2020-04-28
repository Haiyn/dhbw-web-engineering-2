<?php

namespace requests;

use components\authorization\AuthorizationService;
use stdClass;

/**
 * Class HttpRequest
 * Handles the http requests
 * @package requests
 */
class HttpRequest
{
    protected $session;

    public function __construct()
    {
        $this->session = new AuthorizationService();
        $this->session->resumeSession();
    }

    /**
     * Handle the http request, called from the router
     * @param $handlerClassName * Name of the handler
     */
    public function handleHttpRequest($handlerClassName)
    {
        $this->session->checkSession();

        $handler = new $handlerClassName();
        $result = null;
        try {
            $result = $handler->handle();
        } catch (HttpRequestException $exception) {
            $this->setHttpRequestError($exception->getMessage(), $exception->getData());
        }
        $this->setHttpRequestSuccess($result['message'], $result['data']);
    }

    /**
     * Successfully finish the http request
     * @param $successMessage * Success message to be given to the response
     * @param array $data * Additional data
     */
    protected function setHttpRequestSuccess($successMessage, $data = [])
    {
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode(array("message" => $successMessage, "data" => $data));
    }

    /**
     * Finish the http request with an error
     * @param  * Error message to be given to the response
     * @param array $data * Additional data
     */
    protected function setHttpRequestError($errorMessage, $data = [])
    {
        http_response_code(500);
        header("Content-Type: application/json");
        die(json_encode(array("message" => $errorMessage, "data" => $data)));
    }
}
