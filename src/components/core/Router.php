<?php

namespace components\core;

use components\InternalComponent;
use controllers\NotFoundController;

class Router extends InternalComponent
{
    /**
     * Transforms the URL into a Controller name
     * @param $viewName
     * @return string * controller name
     */
    private function transformViewNameToControllerName($viewName)
    {
        // If the url has '-' in it, convert it to CamelCase
        // e.g.: game-overview --> GameOverview
        $parts = explode("-", $viewName);
        foreach ($parts as &$part) {
            $part = ucfirst(strtolower($part));
        }
        return implode("", $parts);
    }

    /**
     * Transforms a path to a view name
     * @param $path * URI path
     * @return string * view name
     */
    private function transformPathToViewName($path)
    {
        // Cut the argument after the host to size
        // e.g. localhost:8080/game-overview?someparam --> game-overview
        $path = ltrim($path, "/");
        $path = trim($path);
        $path = explode("?", $path)[0];
        $path = explode("/", $path);

        return $path[0];
    }

    /**
     * Routes from the URL to the correct Controller
     * @param $params * URI parameters
     */
    public function route($params)
    {
        $path = $params[0];
        $viewName = $this->transformPathToViewName($path);
        $controllerName = $this->transformViewNameToControllerName($viewName);

        // This sets which Controller will be called if no path is given
        if (empty($controllerName)) {
            $controllerName = "HomeController";
        }

        $controllerClassName = $controllerName . "Controller";

        // See if the called controller exists in the controllers folder
        if (file_exists("controllers/{$controllerClassName}.php")) {
            $className = "\\controllers\\" . "$controllerClassName";
            $controller = new $className();
        } else {
            // If not, use the NotFoundController
            $controller = new NotFoundController();
            $viewName = "not-found";
        }

        // Set the viewname
        $controller->viewName = $viewName;

        // Check if request is http request
        if (isset($_POST['action']) && !empty($_POST['action']) && $_POST['action'] == 'http_request') {
            // Request is http request, call the httpRequest method
            $controller->httpRequest();
        } else {
            // Invoke the controller view
            $controller->render($params);
            $controller->showView();
        }
    }
}
