<?php

namespace models;

use components\database\DatabaseService;
use components\core\Utility;

class User
{
    private static $instance;
    private static $database;

    public function __construct()
    {
        self::$instance = $this;
        self::$database = DatabaseService::newInstance(null);
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Searches the users table for a user with the passed user id
     * @param $user_id * user id to search for
     * @return array|object * found users
     */
    public function getUserById($user_id)
    {
        $users = self::$database->fetch(
            "SELECT * from users WHERE user_id = :user_id",
            [":user_id" => $user_id]
        );
        if (empty($users)) {
            return [];
        }
        return $users[0];
    }

    /**
     * Searches the users table for a user with the passed username
     * @param $username * username to search for
     * @return array|object * found users
     */
    public function getUserByUsername($username)
    {
        $users = self::$database->fetch(
            "SELECT * from users WHERE username = :username",
            [":username" => $username]
        );
        if (empty($users)) {
            return [];
        }
        return $users[0];
    }

    /**
     * Searches the users table for a user with the passed email
     * @param $email * email to search for
     * @return array|object * found users
     */
    public function getUserByEmail($email)
    {
        $users = self::$database->fetch(
            "SELECT * from users WHERE email = :email",
            [":email" => $email]
        );
        if (empty($users)) {
            return [];
        }
        return $users[0];
    }

    /**
     * Adds a new user to the users table
     * @param $user_data * data needed by database
     * @return bool * successful/not successful
     */
    public function addUser($user_data)
    {
        return self::$database->execute(
            "INSERT INTO users VALUES (:user_id, :username, :email, :password, :first_name, :last_name, :age, DEFAULT)",
            $this->mapRegisterDataToUserTableData($user_data)
        );
    }

    /**
     * Maps the data from user_data to a users database object
     * user_id and creation_date are generated in database
     * @param $user_data * data to map
     * @return array * mapped data that fits users table data
     */
    private function mapRegisterDataToUserTableData($user_data)
    {
        // Check for empty values, postgres must receive null not ""
        if (empty($user_data['first_name'])) {
            $user_data['first_name'] = null;
        }
        if (empty($user_data['last_name'])) {
            $user_data['last_name'] = null;
        }
        if (empty($user_data['age'])) {
            $user_data['age'] = null;
        }

        return $data = [
            ":user_id" => $user_data['user_id'],
            ":username" => $user_data['username'],
            ":email" => $user_data['email'],
            // Hash the password with the salt from config.ini.php
            ":password" => md5(Utility::getIniFile()['AUTH_SALT'] . $user_data["password"]),
            ":first_name" => $user_data['first_name'],
            ":last_name" => $user_data['last_name'],
            ":age" => $user_data['age'],
        ];
    }
}
