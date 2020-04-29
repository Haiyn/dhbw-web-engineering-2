<?php

namespace requests\game_play;

use models\Game;
use models\Player;
use requests\HttpRequestHandler;

class GetPlayersHttpRequestHandler implements HttpRequestHandler
{
    public function handle()
    {
        if (isset($_POST['game_id'])) {
            $game_id = filter_var(htmlspecialchars($_POST['game_id']), FILTER_SANITIZE_ENCODED);
            $player = Player::getInstance();
            $players = $player->getPlayersByGameId($game_id);
            return ["message" => "", "data" => ["players" => $players]];
        }
        return ["message" => "", "data" => []];
    }
}
