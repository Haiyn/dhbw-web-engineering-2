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
            $game->updateGameStatus($game_id, 'finished');
            return ["message" => "Game successfully finished.", "data" => []];
        }
        throw new HttpRequestException("No game id given.");
    }
}
