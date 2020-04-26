<?php

namespace controllers;

class NotFoundController extends Controller
{
    public function render($parameters)
    {
        $this->view->pageTitle = "404 Not Found";
    }

    public function httpRequest()
    {
        // TODO: Implement httpRequest() method.
    }
}
