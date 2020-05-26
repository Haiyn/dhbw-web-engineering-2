<?php

namespace controllers;

use models\Game;
use models\Player;

/**
 * Class GamePlayController
 * Controls the game process (estimation and result).
 * @package controllers
 */
class GamePlayController extends Controller
{
    public function render($params)
    {
        $this->session->checkSession();

        if (isset($_GET['game_id'])) {
            $game = Game::getInstance();
            $gameById = $game->getGameById($_GET['game_id']);
            // Check if game with id exists, if not, redirect to game overview
            if (empty($gameById)) {
                $this->redirect("game-overview");
            }
            if ($this->checkIfUserHasAccess($gameById)) {
                // At this point the user is either invited or the creator of the game
                $this->view->title = $gameById->title;
                $this->view->description = $gameById->description;
                $player = Player::getInstance();
                $players = $player->getPlayersByGameId($_GET['game_id']);
                // Get the current estimated value of the player
                foreach ($players as $p) {
                    if ($p->user_id == $_SESSION['USER_ID']) {
                        $this->view->estimatedValue = $p->estimated_value;
                        break;
                    }
                }
            } else {
                // The user is not invited or the creator of the game, so he gets redirected to game overview page
                $this->redirect("game-overview");
            }
        }
    }

    /**
     * Check if the user is either invited or the creator of the game
     * @param $game * Game to be checked
     * @return bool * Invited, creator/ not invited, not creator
     */
    public function checkIfUserHasAccess($game)
    {
        $player = Player::getInstance();
        // Check if current user is game creator
        if ($game->creator_id == $_SESSION['USER_ID']) {
            $this->view->isCreator = true;
            return true;
        }
        // Check if user is invited to an game
        foreach ($player->getPlayersByGameId($game->game_id) as $p) {
            if ($p->user_id == $_SESSION['USER_ID']) {
                return true;
            }
        }
        return false;
    }
}
