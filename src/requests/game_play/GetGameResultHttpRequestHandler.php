<?php

namespace requests\game_play;

use models\Game;
use models\Player;
use requests\HttpRequestException;
use requests\HttpRequestHandler;

class GetGameResultHttpRequestHandler implements HttpRequestHandler
{
    public function handle()
    {
        if (isset($_POST['game_id'])) {
            $game_id = filter_var(htmlspecialchars($_POST['game_id']), FILTER_SANITIZE_ENCODED);
            $game = Game::getInstance();
            $foundGame = $game->getGameById($game_id);
            if (!empty($foundGame)) {
                return [
                    "message" => "",
                    "data" => [
                        "minimum" => $foundGame->minimum,
                        "maximum" => $foundGame->maximum,
                        "average" => $foundGame->average,
                        "most" => $foundGame->most_picker
                    ]
                ];
            } else {
                throw new HttpRequestException("Could not get game result because the game does not exist.");
            }

        }
        throw new HttpRequestException("No game id given.");
    }
}
