<?php

namespace requests;

use models\User;

class GameCreateHttpRequest
{
    private static $instance;

    public function __construct()
    {
        self::$instance = $this;
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Check if the user exists and is not the game creator
     * @param $user_name * Name of the user to be checked
     * @throws HttpRequestException * Throw if user in invalid
     */
    public function checkUser($user_name)
    {
        // Check if users exists
        $user = User::getInstance();
        $found_user = $user->getUserByUsername($user_name);
        if (empty($found_user)) {
            $found_user = $user->getUserByEmail($user_name);
            if (empty($found_user)) {
                throw new HttpRequestException("User with name {$user_name} doesn't exist.");
            }
        }
        // Check if user to be added is the current user (game creator)
        if ($found_user == $_SESSION['USER_ID']) {
            throw new HttpRequestException("You cannot add yourself to the game.");
        }
    }
}
