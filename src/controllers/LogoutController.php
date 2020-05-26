<?php

namespace controllers;

use components\authorization\AuthorizationService;

/**
 * Class LogoutController
 * Logs out a logged in user. Has no view.
 * @package controllers
 */
class LogoutController extends Controller
{
    public function render($parameters)
    {
        if (isset($_SESSION['USER_ID'])) {
            $authorizationService = AuthorizationService::getInstance();
            $authorizationService->unsetSession();
        } else {
            $this->redirect("/home");
        }
    }
}
