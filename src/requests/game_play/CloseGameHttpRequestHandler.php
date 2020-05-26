<?php

namespace requests\game_play;

use models\Game;
use requests\HttpRequestException;
use requests\HttpRequestHandler;

/**
 * Class CloseGameHttpRequestHandler
 * Manages the request to close the game.
 * @package requests\game_play
 */
class CloseGameHttpRequestHandler implements HttpRequestHandler
{
    public function handle()
    {
        if (isset($_POST['game_id'])) {
            $game_id = filter_var(htmlspecialchars($_POST['game_id']), FILTER_SANITIZE_ENCODED);
            $game = Game::getInstance();
            $found_game = $game->getGameById($game_id);
            // Check if current user is game creator
            if (!empty($found_game)) {
                $game->deleteGameById($game_id);
            } else {
                throw new HttpRequestException("Game cannot be closed because it does not exist.");
            }
            return ["message" => "Game successfully closed.", "data" => []];
        }
        throw new HttpRequestException("No game id given.");
    }
}
