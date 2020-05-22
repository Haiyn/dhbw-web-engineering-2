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
            "INSERT INTO games VALUES (:game_id, :creator_id, DEFAULT, :title, :description, DEFAULT, DEFAULT,
                  DEFAULT, DEFAULT, DEFAULT);",
            $this->mapGameDataToGameTableData($data)
        );
    }

    /**
     * Searches the games table for a game with the passed game id
     * @param $game_id * game id to search for
     * @return array|object * found game
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
     * Update the status of the game to running/finished
     * @param $game_id * Id of the game to be updated
     * @param $new_status * The new status
     * @return bool * Successful/ not successful
     */
    public function updateGameStatus($game_id, $new_status)
    {
        return self::$database->execute(
            "UPDATE games
            SET status = :status
            WHERE game_id = :game_id",
            [":game_id" => $game_id, ":status" => $new_status]
        );
    }

    /**
     * Saves estimation results to a game entry
     * @param $game_id * the game to be updated
     * @param $result * the estimation result values as array
     * @return bool * successful/not successful
     */
    public function saveEstimationResult($game_id, $result) {
        $data = ["game_id" => $game_id];
        $data += $this->mapGameResultToGameTableData($result);
        return self::$database->execute(
            "UPDATE games
            SET minimum = :minimum, maximum = :maximum, average = :average, most_picker = :most
            WHERE game_id = :game_id",
            $data
        );
    }

    /**
     * Deletes a game
     * @param $game_id * game id to delete
     * @return bool * successful/not successful
     */
    public function deleteGameById($game_id) {
        return self::$database->execute(
            "DELETE FROM games
            WHERE game_id = :game_id",
            [":game_id" => $game_id]
        );
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

    /**
     * Maps estimation result data to the database format
     * @param $data * estimation result data
     * @return array * mapped data
     */
    private function mapGameResultToGameTableData($data) {
        return $data = [
            ":minimum" => $data['minimum'],
            ":maximum" => $data['maximum'],
            ":average" => $data['average'],
            ":most" => $data['most']
        ];
    }
}
