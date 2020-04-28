<?php

namespace controllers;

use components\database\DatabaseService;

class HomeController extends Controller
{
    public function render($parameters)
    {
        $db = DatabaseService::getInstance();

        if (!isset($_GET["success"])) {
            $this->setSuccess("Great Success!");
        }


        $this->view->isSuccess = isset($_GET["success"]);
        $this->view->pageTitle = "Home";
    }
}
