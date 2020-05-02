<?php

namespace requests\game_play;

use models\Game;
use requests\HttpRequestException;
use requests\HttpRequestHandler;

class FinishEstimationHttpRequestHandler implements HttpRequestHandler
{
    public function handle()
    {
        if (isset($_POST['game_id'])) {
            $game_id = filter_var(htmlspecialchars($_POST['game_id']), FILTER_SANITIZE_ENCODED);
            $game = Game::getInstance();
            $found_game = $game->getGameById($game_id);
            // Check if current user is game creator
            if ($found_game->creator_id != $_SESSION['USER_ID']) {
                throw new HttpRequestException("You cannot finish the estimation, because you are not the creator.");
            }
            $game->updateGameStatus($game_id, 'finished');
            return ["message" => "Game successfully finished.", "data" => []];
        }
        throw new HttpRequestException("No game id given.");
    }
}
