<?php

namespace controllers;

use models\Game;
use models\Player;

/**
 * Class GameOverviewController
 * Controls the game overview and shows all games to the user.
 * @package controllers
 */
class GameOverviewController extends Controller
{
    public function render($params)
    {
        $this->session->checkSession();

        $game = Game::getInstance();
        $games = $game->getGames();

        // Filter the games, so that only games where the current user
        // is invited to, or is the creator from, are shown
        $filtered_games = [];
        $player = Player::getInstance();
        foreach ($games as $g) {
            // Check if current user is game creator
            if ($g->creator_id == $_SESSION['USER_ID']) {
                array_push($filtered_games, $g);
                continue;
            }
            // Check if user is invited to an game
            foreach ($player->getPlayersByGameId($g->game_id) as $p) {
                if ($p->user_id == $_SESSION['USER_ID']) {
                    array_push($filtered_games, $g);
                    continue;
                }
            }
        }

        $this->view->pageTitle = "Game Overview";
        $this->view->games = $filtered_games;
    }

    public function httpRequest()
    {
        // TODO: Implement httpRequest() method.
    }
}
