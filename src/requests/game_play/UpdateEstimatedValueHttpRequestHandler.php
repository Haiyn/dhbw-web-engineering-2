<?php

namespace requests\game_play;

use models\Game;
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
            // Check if estimated value is valid
            $values = [0, 1, 1.5, 2, 3, 5, 8, 13, 20, 40, 100, -1];
            $valid = false;
            foreach ($values as $value) {
                if ($value == $estimated_value) {
                    $valid = true;
                    break;
                }
            }
            if (!$valid) {
                throw new HttpRequestException("The estimated value '{$estimated_value}' is not valid.");
            }
            // Check if game is running
            $game = Game::getInstance();
            $found_game = $game->getGameById($game_id);
            if ($found_game->status != 'running') {
                throw new HttpRequestException("You cannot estimate this task, because it has already been estimated.");
            }
            // Update the estimated value
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
