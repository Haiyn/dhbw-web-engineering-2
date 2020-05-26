<?php

namespace controllers;

/**
 * Class HomeController
 * Shows the home page to the user.
 * @package controllers
 */
class HomeController extends Controller
{
    public function render($parameters)
    {
        $this->view->pageTitle = "Home";
    }
}
