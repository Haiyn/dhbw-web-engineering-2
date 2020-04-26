<?php

namespace models;

use components\database\DatabaseService;

/**
 * Class Player
 * Database model for the players table. Includes all needed queries.
 * @package models
 */
class Player
{
    private static $instance;
    private static $database;

    public function __construct()
    {
        self::$instance = $this;
        self::$database = DatabaseService::newInstance(null);
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get all players of a game
     * @param $game_id * Id of the game
     * @return array * Array of found players
     */
    public function getPlayersByGameId($game_id)
    {
        return self::$database->fetch(
            "SELECT * FROM players WHERE game_id = :game_id",
            [":game_id" => $game_id]
        );
    }

    /**
     * Adds a new player to the players table
     * @param $player_data * data needed by database
     * @return bool * successful/not successful
     */
    public function addPlayer($player_data)
    {
        return self::$database->execute(
            "INSERT INTO players VALUES (:player_id, :user_id, :game_id, :estimated_value)",
            $this->mapPlayerDataToPlayerTableData($player_data)
        );
    }

    /**
     * Maps the data to the database
     * @param $data * Data of the player
     * @return array * Modified data
     */
    private function mapPlayerDataToPlayerTableData($data)
    {
        return $data = [
            ":player_id" => $data['player_id'],
            ":user_id" => $data['user_id'],
            ":game_id" => $data['game_id'],
            ":estimated_value" => $data['estimated_value']
        ];
    }
}
