<?php

namespace controllers;

use components\validators\UserValidator;
use components\validators\ValidatorException;
use models\User;

/**
 * Class ProfileEditController
 * Controls the editing of the own profile.
 * @package controllers
 */
class ProfileEditController extends Controller
{
    public function render($parameters)
    {
        $this->session->checkSession();

        // Get the user for the logged in user ID
        $user = User::getInstance();
        $currentUser = $user->getUserById($_SESSION['USER_ID']);

        $this->initializeViewData($currentUser);

        // Update the personal information (left form)
        if (isset($_POST['username'])) {
            $this->updatePersonalData($currentUser);
        }

        // Update the password (right form)
        if (isset($_POST['new_password_1']) && isset($_POST['new_password_2'])) {
            // Get the data, sanitize and validate
            $password1 = htmlspecialchars($_POST['new_password_1']);
            $password2 = htmlspecialchars($_POST['new_password_2']);

            if ($password1 != $password2) {
                $this->setError("Passwords do not match!");
            }

            // Update the database entry
            $success = $user->updatePassword($currentUser->user_id, $password1);
            if ($success) {
                $this->setSuccess("Your password was successfully changed!");
            } else {
                $this->setError("We could not update your password.");
            }
        }
    }

    /**
     * Controls the updating of a users personal information (left form)
     * @param $currentUser * The user object of the currently editing user
     */
    private function updatePersonalData($currentUser)
    {
        // Sanitize and validate the data
        $newUserData = $this->sanitizePersonalData();
        $userValidator = UserValidator::getInstance();
        try {
            $userValidator->validateRegisterData($newUserData += ["password" => "placeholder"]);
        } catch (ValidatorException $exception) {
            $this->setError($exception->getMessage(), $exception->getParams());
        }

        // Check if new username and email are still unique
        $user = User::getInstance();
        $foundUser = $user->getUserByUsername($newUserData['username']);
        if ($foundUser->user_id != $currentUser->user_id) {
            // Someone else already has this username
            $this->setError("The username <strong>{$newUserData['username']}</strong> is already taken!");
        }

        $foundUser = $user->getUserByEmail($newUserData['email']);
        if ($foundUser->user_id != $currentUser->user_id) {
            // Someone else already has this email
            $this->setError("The email <strong>{$newUserData['email']}</strong> is already taken!");
        }

        // Update the database entry
        $success = $user->updatePersonalInformation((array)$currentUser, $newUserData);
        if ($success) {
            $this->setSuccess("Your personal data was updated!");
        } else {
            $this->setError("We could not update your personal data.");
        }
    }

    /**
     * Sanitizes the POST data of the personal data form
     * @return array * Sanitized data
     */
    private function sanitizePersonalData()
    {
        // Sanitize the data by removing any harmful code and markup
        $user_data = [
            'username' => filter_var(htmlspecialchars($_POST['username']), FILTER_SANITIZE_STRING),
            'email' => filter_var(htmlspecialchars($_POST['email']), FILTER_SANITIZE_EMAIL),
            'first_name' => filter_var(htmlspecialchars($_POST['first_name']), FILTER_SANITIZE_STRING),
            'last_name' => filter_var(htmlspecialchars($_POST['last_name']), FILTER_SANITIZE_STRING),
            'age' => filter_var(htmlspecialchars($_POST['age']), FILTER_SANITIZE_NUMBER_INT)
        ];

        // Trim every value to assert that no whitespaces are submitted
        foreach ($user_data as $key => &$value) {
            $user_data[$key] = trim($value);
        }

        return $user_data;
    }

    /**
     * Sends the information of the currently editing user to the view for displaying
     * @param $currentUser * The user object of the currently editing user
     */
    private function initializeViewData($currentUser)
    {
        // Only send the public information to the view
        $this->view->username = $currentUser->username;
        $this->view->email = $currentUser->email;
        $this->view->firstName = $currentUser->first_name;
        $this->view->lastName = $currentUser->last_name;
        $this->view->age = $currentUser->age;

        $this->view->isError = isset($_GET['error']);
        $this->view->isSuccess = isset($_GET['success']);
    }
}
