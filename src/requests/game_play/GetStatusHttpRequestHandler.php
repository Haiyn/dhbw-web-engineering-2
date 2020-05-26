<?php

namespace requests\game_play;

use models\Game;
use requests\HttpRequestException;
use requests\HttpRequestHandler;

/**
 * Class GetStatusHttpRequestHandler
 * Manages the request to get the status of the game.
 * @package requests\game_play
 */
class GetStatusHttpRequestHandler implements HttpRequestHandler
{
    public function handle()
    {
        if (isset($_POST['game_id'])) {
            $game_id = filter_var(htmlspecialchars($_POST['game_id']), FILTER_SANITIZE_ENCODED);
            $game = Game::getInstance();
            $found_game = $game->getGameById($game_id);
            return ["message" => "", "data" => ["status" => $found_game->status]];
        }
        throw new HttpRequestException("No game id given.");
    }
}
