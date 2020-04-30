<?php

namespace requests\game_play;

use models\Player;
use requests\HttpRequestException;
use requests\HttpRequestHandler;

class UpdateEstimatedValueHttpRequestHandler implements HttpRequestHandler
{
    public function handle()
    {
        if (isset($_POST['game_id']) && isset($_POST['estimated_value'])) {
            $game_id = filter_var(htmlspecialchars($_POST['game_id']), FILTER_SANITIZE_ENCODED);
            $estimated_value = filter_var(
                htmlspecialchars($_POST['estimated_value']),
                FILTER_SANITIZE_NUMBER_FLOAT,
                FILTER_FLAG_ALLOW_FRACTION
            );
            $player = Player::getInstance();
            $player_data = [
                "game_id" => $game_id,
                "user_id" => $_SESSION['USER_ID'],
                "estimated_value" => $estimated_value];
            if (!$player->updatePlayer($player_data)) {
                throw new HttpRequestException("An error occurred while updating the estimated value.");
            }
            $estimated_value = $estimated_value == -1 ? "?" : $estimated_value;
            return ["message" => "You have successfully estimated the number '{$estimated_value}'.", "data" => []];
        }
        throw new HttpRequestException("No game id or estimated value given.");
    }
}
