<?php

namespace controllers;

use components\authorization\AuthorizationService;
use stdClass;

abstract class Controller
{
    public $viewName;
    protected $view;
    protected $session;

    public function __construct()
    {
        $this->view = new stdClass();
        $this->session = new AuthorizationService();
        $this->session->resumeSession();
    }

    abstract public function render($params);

    abstract public function httpRequest();

    /*
     * Redirects to the given url. Makes use of the router.
     */
    final protected function redirect($url)
    {
        header("Location: $url");
        header("Connection: close");
        exit;
    }

    /*
     * Opens the phtml file in /views/[viewname]/[viewname].phtml
     */
    final public function showView()
    {
        extract((array)$this->view);
        require dirname(__DIR__) . "/views/{$this->viewName}/{$this->viewName}.phtml";
    }

    /*
     * These message setters set a new session variable according to their view name and message level and store the
     * passed message in it. It then redirects to their current view with the message level as a parameter
     *
     * e.g. in register, the _setError method creates $_SESSION['REGISTER_ERROR'] and reroutes to /register?error
     */
    protected function setError($errorMessage, $params = [])
    {
        $_SESSION[str_replace("-", "_", strtoupper($this->viewName)) . "_ERROR"] = $errorMessage;
        $redirect = "/{$this->viewName}?error";
        foreach ($params as $key => $value) {
            $redirect = $redirect . "&" . $key . (!empty($value) ? "=" . $value : "");
        }
        $this->redirect($redirect);
    }

    protected function setWarning($warningMessage, $params = [])
    {
        $_SESSION[str_replace("-", "_", strtoupper($this->viewName)) . "_WARNING"] = $warningMessage;
        $redirect = "/{$this->viewName}?warning";
        foreach ($params as $key => $value) {
            $redirect = $redirect . "&" . $key . (!empty($value) ? "=" . $value : "");
        }
        $this->redirect($redirect);
    }

    protected function setSuccess($successMessage, $params = [])
    {
        $_SESSION[str_replace("-", "_", strtoupper($this->viewName)) . "_SUCCESS"] = $successMessage;
        $redirect = "/{$this->viewName}?success";
        foreach ($params as $key => $value) {
            $redirect = $redirect . "&" . $key . (!empty($value) ? "=" . $value : "");
        }
        $this->redirect($redirect);
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
