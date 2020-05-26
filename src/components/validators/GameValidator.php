<?php

namespace components\validators;

/**
 * Class GameValidator
 * Validates all user input for game functions. Throws ValidatorError if validation fails.
 * @package components\validators
 */
class GameValidator
{
    private static $instance;

    public function __construct()
    {
        self::$instance = $this;
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Validate the data of the game when being created, throws an error if something is wrong
     * @param $data * Data of the game
     * @throws ValidatorException
     */
    public function validateGameCreateData($data)
    {
        // Double check if all required fields have been set
        if (empty($data["title"]) || empty($data["description"])) {
            throw new ValidatorException("Please fill out all required fields.");
        }

        // Check if maxlength is exceeded
        if (strlen($data["title"]) > 32) {
            throw new ValidatorException("Length of title cannot exceed max length of 32.");
        }
        if (strlen($data["description"]) > 256) {
            throw new ValidatorException("Length of description cannot exceed max length of 256.");
        }
    }
}
