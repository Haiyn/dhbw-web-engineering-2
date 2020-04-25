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
            "INSERT INTO games VALUES (:game_id, DEFAULT, :title, :description);",
            $this->mapGameDataToEventTableData($data)
        );
    }

    /**
     * Maps the data to the database
     * @param $data * Data of the game
     * @return array * Modified data
     */
    private function mapGameDataToEventTableData($data)
    {
        return $data = [
            ":game_id" => $data['game_id'],
            ":title" => $data['title'],
            ":description" => $data['description']
        ];
    }
}
