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
    creation_date TIMESTAMP DEFAULT NOW(),
    title VARCHAR(32) NOT NULL,
    description VARCHAR(256) NOT NULL
);
CREATE TABLE IF NOT EXISTS players (
    player_id VARCHAR(36) PRIMARY KEY,
    user_id VARCHAR(36) NOT NULL REFERENCES users (user_id),
    game_id VARCHAR(36) NOT NULL REFERENCES games (game_id),
    estimated_value INTEGER NOT NULL DEFAULT 0
);
CREATE TABLE IF NOT EXISTS sessions (
    session_id VARCHAR(36) PRIMARY KEY,
    user_id VARCHAR(36) NOT NULL REFERENCES users (user_id),
    login_time INTEGER  NOT NULL,
    ip_address VARCHAR(16) NOT NULL,
    user_agent TEXT NOT NULL
);
