<?php

namespace controllers;

use components\core\Utility;
use components\database\DatabaseService;
use models\User;

class ProfileController extends Controller
{
    public function render($parameters)
    {
        $this->session->checkSession();

        // Get the User ID from either the URL or the session (if own profile)
        $userID = $this->getUserID();
        if (empty($userID)) {
            $this->redirect("/not-found");
        }

        // Get the user for the user ID
        $user = User::getInstance();
        $userInformation = $user->getUserById($userID);
        if (empty($userInformation)) {
            $this->redirect("/not-found");
        }

        // Set the data in the view
        $this->setData($userInformation);
    }

    private function getUserID() {
        // If a GET parameter is set, validate it
        if(isset($_GET['user_id'])) {
            if (Utility::isValidUUIDv4($_GET['user_id'])) {
                $this->view->isLoggedInUser = false;
                return htmlspecialchars($_GET['user_id']);
            } else {
                return null;
            }
        }

        // Otherwise, return the logged in user's ID
        $this->view->isLoggedInUser = true;
        return $_SESSION['USER_ID'];
    }

    private function setData($user) {
        // Only send the public information to the view
        $this->view->username = $user->username;
        if($this->view->isLoggedInUser) {
            $this->view->email = $user->email;
        }
        $this->view->firstName = $user->first_name;
        $this->view->lastName = $user->last_name;
        $this->view->age = $user->age;
        $this->view->registrationDate = date(DATE_RFC822, strtotime($user->registration_date));

        $this->view->pageTitle = "Profile of " . $user->username;
    }
}
