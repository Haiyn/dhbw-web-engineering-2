<?php

namespace controllers;

use components\core\Utility;
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
        $foundUser = $user->getUserById($userID);
        if (empty($foundUser)) {
            $this->redirect("/not-found");
        }

        // Set the data in the view
        $this->initializeViewData($foundUser);
    }

    /**
     * Gets the user id either from the URL or from the session
     * @return mixed|string|null * returns user id (string) if a valid UUIDv4 is set in the URL or if no uuid was given
     * returns null if an invalid user id was given
     */
    private function getUserID()
    {
        // If a GET parameter is set, validate it
        if (isset($_GET['user_id'])) {
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

    /**
     * Sends the user information to the view for displaying
     * @param $user * user object that contains the information to display
     */
    private function initializeViewData($user)
    {
        // Only send the public information to the view
        $this->view->username = $user->username;
        if ($this->view->isLoggedInUser) {
            $this->view->email = $user->email;
        }
        $this->view->firstName = $user->first_name;
        $this->view->lastName = $user->last_name;
        $this->view->age = $user->age;
        $this->view->registrationDate = date(DATE_RFC822, strtotime($user->registration_date));

        $this->view->pageTitle = "Profile of " . $user->username;
    }
}
