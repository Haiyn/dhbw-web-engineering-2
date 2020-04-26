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
}
