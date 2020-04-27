<?php

namespace models;

use components\database\DatabaseService;

/**
 * Class Game
 * Database model for the games table. Includes all needed queries.
 * @package models
 */
class Game
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
     * Add the game to the database
     * @param $data * Data of the game
     * @return bool * Successful/ not successful
     */
    public function addGame($data)
    {
        return self::$database->execute(
            "INSERT INTO games VALUES (:game_id, :creator_id, DEFAULT, :title, :description);",
            $this->mapGameDataToGameTableData($data)
        );
    }

    /**
     * Searches the games table for a game with the passed game id
     * @param $game_id * game id to search for
     * @return array|object * found users
     */
    public function getGameById($game_id)
    {
        $games = self::$database->fetch(
            "SELECT * from games WHERE game_id = :game_id",
            [":game_id" => $game_id]
        );
        if (empty($games)) {
            return [];
        }
        return $games[0];
    }

    /**
     * Get all games
     * @return array * Array of games
     */
    public function getGames()
    {
        $games = self::$database->fetch(
            "SELECT * FROM games",
            []
        );
        if (empty($games)) {
            return [];
        }
        return $games;
    }

    /**
     * Maps the data to the database
     * @param $data * Data of the game
     * @return array * Modified data
     */
    private function mapGameDataToGameTableData($data)
    {
        return $data = [
            ":game_id" => $data['game_id'],
            ":creator_id" => $data['creator_id'],
            ":title" => $data['title'],
            ":description" => $data['description']
        ];
    }
}
