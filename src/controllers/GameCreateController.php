<?php

namespace controllers;

use components\core\Utility;
use components\validators\ValidatorException;
use components\validators\GameValidator;
use models\Game;

/**
 * Class GameCreateController
 * Manages the creation of new events.
 * @package controllers
 */
class GameCreateController extends Controller
{
    public function render($params)
    {
        $this->session->checkSession();

        if (isset($_POST["title"]) && isset($_POST["description"])) {
            $game_data = [
                "game_id" => Utility::generateUUIDv4(),
                "title" => trim(htmlspecialchars($_POST["title"])),
                "description" => trim(htmlspecialchars($_POST["description"]))
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
     * @param $data * Data of the event
     */
    private function createGame($data)
    {
        $game_validator = GameValidator::getInstance();
        try {
            $game_validator->validateGameCreateData($data);
        } catch (ValidatorException $exception) {
            $this->setError($exception->getMessage(), $exception->getParams());
        }
        $game = Game::getInstance();
        if (!$game->addGame($data)) {
            $this->setError("Game could not be created.");
        }
    }
}