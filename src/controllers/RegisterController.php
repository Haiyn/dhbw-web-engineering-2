<?php

namespace controllers;

use components\core\Utility;
use components\validators\ValidatorException;
use components\validators\UserValidator;
use models\User;

class RegisterController extends Controller
{
    public function render($parameters)
    {
        // The register button was pressed
        if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email']))
        {
            // Sanitize the received data
            $sanitized_data = $this->sanitizeRegisterData();

            // Validate the data with the User Validator
            $userValidator = UserValidator::getInstance();
            try {
                $userValidator->validateRegisterData($sanitized_data);
            } catch (ValidatorException $exception) {
                $this->setError($exception->getMessage());
            }

            // Try to register the user
            $this->registerUser($sanitized_data);

            // Everything worked
            $this->setSuccess("You have been successfully registered to the website!");
        }

        $this->view->pageTitle = "Register";
        $this->view->isSuccess = isset($_GET["success"]);
        $this->view->isError = isset($_GET["error"]);
    }

    public function httpRequest()
    {
        // TODO: Implement httpRequest() method.
    }

    /**
     * Sanitizes all received POST data from the user to remove malicious code and invalid data
     * @return array * sanitized user data
     */
    private function sanitizeRegisterData() {
        // Sanitize the data by removing any harmful code and markup
        $user_data = [
            'user_id' => Utility::generateUUIDv4(),
            'username' => filter_var(htmlspecialchars($_POST['username']), FILTER_SANITIZE_STRING),
            'password' => htmlspecialchars($_POST['password']),
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
     * Tries to register the user
     * Redirects with an error if something is wrong with the data.
     * @param $user_data * data array for adding the user
     */
    private function registerUser($user_data)
    {
        $user = User::getInstance();

        // Check if username is already in database
        $existingUser = $user->getUserByUsername($user_data["username"]);
        if (!empty($existingUser)) {
            $this->setError("This username is already taken!");
        }

        // Check if email is already in database
        $existingUser = $user->getUserByEmail($user_data["email"]);
        if (!empty($existingUser)) {
            $this->setError("An account with this E-Mail is already registered!");
        }

        // Add the user to the database
        if (!$user->addUser($user_data)) {
            $this->setError("Sorry, something went wrong while creating your user! Please try again.");
        }
    }
}
