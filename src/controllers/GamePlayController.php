<?php

namespace controllers;

use models\Game;
use models\Player;

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
            } else {
                // The user is not invited or the creator of the game, so he gets redirected to game overview page
                $this->redirect("game-overview");
            }
        }
    }

    public function httpRequest()
    {
        $this->session->checkSession();
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
