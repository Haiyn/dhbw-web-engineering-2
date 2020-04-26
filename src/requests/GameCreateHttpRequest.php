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

    public function checkUserExists($user_name)
    {
        $user = User::getInstance();
        if (empty($user->getUserByUsername($user_name))) {
            if (empty($user->getUserByEmail($user_name))) {
                return false;
            }
        }
        return true;
    }
}
