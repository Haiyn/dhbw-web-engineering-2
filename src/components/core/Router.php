<?php

namespace components\core;

use components\InternalComponent;
use controllers\NotFoundController;
use requests\HttpRequest;

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
     * Transform the view name and handler to the full handler name
     * @param $viewName * Name of the view e.g. game-create
     * @param $handler * Name of the handler e.g. user_access
     * @return string * Handler name
     */
    private function transformHandlerToHandlerName($viewName, $handler)
    {
        $viewName = str_replace("-", "_", $viewName);
        $exploded_handler = explode("_", $handler);
        $result = "";
        foreach ($exploded_handler as $h) {
            $result .= ucfirst($h);
        }
        return "\\requests\\{$viewName}\\{$result}HttpRequestHandler";
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
        if (
            isset($_POST['action']) && !empty($_POST['action']) && $_POST['action'] == 'http_request' &&
            isset($_POST['handler']) && !empty($_POST['handler'])
        ) {
            // Request is http request
            $handlerName = $this->transformHandlerToHandlerName($viewName, $_POST['handler']);
            $handlerPath = substr(str_replace("\\", "/", "{$handlerName}.php"), 1);
            // Check if the handler exists
            if (file_exists($handlerPath)) {
                // Call the http request
                $httpRequest = new HttpRequest();
                $httpRequest->handleHttpRequest($handlerName);
            }
        } else {
            // Invoke the controller view
            $controller->render($params);
            $controller->showView();
        }
    }
}
