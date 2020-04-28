<?php

namespace requests\game_create;

use models\User;
use requests\HttpRequestException;
use requests\HttpRequestHandler;

class UserAccessHttpRequestHandler implements HttpRequestHandler
{
    public function handle()
    {
        if (isset($_POST['user_name'])) {
            $user_name = trim(htmlspecialchars($_POST['user_name']));
            // Check if users exists
            $user = User::getInstance();
            $found_user = $user->getUserByUsername($user_name);
            if (empty($found_user)) {
                $found_user = $user->getUserByEmail($user_name);
                if (empty($found_user)) {
                    throw new HttpRequestException("User with name {$user_name} does not exist.");
                }
            }
            // Check if user to be added is the current user (game creator)
            if ($found_user->user_id == $_SESSION['USER_ID']) {
                throw new HttpRequestException("You cannot add yourself to the game.");
            }
            // Check if user has already been added
            if (isset($_POST['user_ids'])) {
                foreach ($_POST['user_ids'] as $user_id) {
                    if ($user_id == $found_user->user_id) {
                        throw new HttpRequestException("User with name {$user_name} is already added.");
                    }
                }
            }
            return ["message" => "User successfully added.", "data" => ["user_id" => $found_user->user_id]];
        }
        return ["message" => "No username given.", "data" => []];
    }
}
