<?php

namespace requests\game_play;

use models\Game;
use requests\HttpRequestException;
use requests\HttpRequestHandler;

class SaveGameResultHttpRequestHandler implements HttpRequestHandler
{
    public function handle()
    {
        if (
            isset($_POST['game_id']) && isset($_POST['minimum']) && isset($_POST['maximum'])
            && isset($_POST['average']) && isset($_POST['most'])
        ) {
            $game_id = filter_var(htmlspecialchars($_POST['game_id']), FILTER_SANITIZE_ENCODED);
            $game = Game::getInstance();
            $result = [
                "minimum" => filter_var(htmlspecialchars($_POST['minimum']), FILTER_SANITIZE_NUMBER_INT),
                "maximum" => filter_var(htmlspecialchars($_POST['maximum']), FILTER_SANITIZE_NUMBER_INT),
                "average" => filter_var(htmlspecialchars($_POST['average']), FILTER_SANITIZE_NUMBER_INT),
                "most" => filter_var(htmlspecialchars($_POST['most']), FILTER_SANITIZE_NUMBER_INT),
            ];
            $success = $game->saveEstimationResult($game_id, $result);
            if ($success) {
                return ["message" => "Game results successfully finished and saved.", "data" => []];
            } else {
                throw new HttpRequestException("Game results could not be saved.");
            }
        }
        throw new HttpRequestException("No game id given.");
    }
}
