<?php

namespace controllers;

class HomeController extends Controller
{
    public function render($parameters)
    {
        $this->view->pageTitle = "Home";
    }
}
