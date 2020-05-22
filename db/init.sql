CREATE DATABASE IF NOT EXISTS web_engineering;
USE web_engineering;
CREATE TABLE IF NOT EXISTS users (
    user_id VARCHAR(36) PRIMARY KEY,
    username VARCHAR(32) UNIQUE NOT NULL,
    email VARCHAR(32) UNIQUE NOT NULL,
    password VARCHAR(32) NOT NULL,
    first_name VARCHAR(32),
    last_name VARCHAR(32),
    age SMALLINT,
    registration_date TIMESTAMP DEFAULT NOW()
);
CREATE TABLE IF NOT EXISTS games (
    game_id VARCHAR(36) PRIMARY KEY,
    creator_id VARCHAR(36) NOT NULL REFERENCES users (user_id),
    creation_date TIMESTAMP DEFAULT NOW(),
    title VARCHAR(32) NOT NULL,
    description VARCHAR(256) NOT NULL,
    status ENUM('running', 'finished') DEFAULT 'running',
    minimum FLOAT NOT NULL DEFAULT 0,
    maximum FLOAT NOT NULL DEFAULT 0,
    average FLOAT NOT NULL DEFAULT 0,
    most_picker FLOAT NOT NULL DEFAULT 0
);
CREATE TABLE IF NOT EXISTS players (
    player_id VARCHAR(36) PRIMARY KEY,
    user_id VARCHAR(36) NOT NULL REFERENCES users (user_id),
    game_id VARCHAR(36) NOT NULL REFERENCES games (game_id),
    estimated_value FLOAT NOT NULL DEFAULT 0
);
CREATE TABLE IF NOT EXISTS sessions (
    session_id VARCHAR(36) PRIMARY KEY,
    user_id VARCHAR(36) NOT NULL REFERENCES users (user_id),
    ip_address VARCHAR(16) NOT NULL,
    user_agent TEXT NOT NULL
);
