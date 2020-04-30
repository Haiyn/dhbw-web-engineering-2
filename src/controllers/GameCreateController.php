<?php

namespace controllers;

use components\core\Utility;
use components\validators\ValidatorException;
use components\validators\GameValidator;
use models\Game;
use models\Player;
use models\User;

/**
 * Class GameCreateController
 * Manages the creation of new games.
 * @package controllers
 */
class GameCreateController extends Controller
{
    public function render($params)
    {
        $this->session->checkSession();

        if (isset($_POST["title"]) && isset($_POST["description"]) && isset($_POST["users"])) {
            $game_data = [
                "game_id" => Utility::generateUUIDv4(),
                "title" => trim(htmlspecialchars($_POST["title"])),
                "description" => trim(htmlspecialchars($_POST["description"])),
                "users" => json_decode($_POST['users'], true)
            ];

            $this->createGame($game_data);

            $this->setSuccess("Game successfully created.");
        }

        $this->view->pageTitle = "Create Game";
        $this->view->isSuccess = isset($_GET["success"]);
        $this->view->isError = isset($_GET["error"]);
    }

    /**
     * Create the game after data validation
     * @param $data * Data of the game
     */
    private function createGame($data)
    {
        if (isset($_SESSION["USER_ID"])) {
            $data["creator_id"] = $_SESSION["USER_ID"];
        }
        // Validate the data
        $game_validator = GameValidator::getInstance();
        try {
            $game_validator->validateGameCreateData($data);
        } catch (ValidatorException $exception) {
            $this->setError($exception->getMessage(), $exception->getParams());
        }
        // Add the game
        $game = Game::getInstance();
        if (!$game->addGame($data)) {
            $this->setError("Game could not be created.");
        }
        // Add the creator to the players
        $player = Player::getInstance();
        $data['user_id'] = $_SESSION['USER_ID'];
        $data['player_id'] = Utility::generateUUIDv4();
        $data['estimated_value'] = 0;
        $player->addPlayer($data);
        if (empty($data['users'])) {
            $data['users'] = [];
        }
        // Add all other players
        foreach ($data['users'] as $u) {
            if (isset($u['name'])) {
                // Get user by username oder email
                $user = User::getInstance();
                $found_user = $user->getUserByUsername($u['name']);
                if (empty($found_user)) {
                    $found_user = $user->getUserByEmail($u['name']);
                    if (empty($found_user)) {
                        continue;
                    }
                }
                $data['user_id'] = $found_user->user_id;
                $data['player_id'] = Utility::generateUUIDv4();
                $data['estimated_value'] = 0;
                if (!$player->addPlayer($data)) {
                    $this->setError("An error occurred while adding a player to the game.");
                }
            }
        }
    }
}
