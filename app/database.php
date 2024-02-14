<?php
require_once '../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__, '../.env');
$dotenv->safeLoad();

/*
    this file is for database connection. Make sure all of these env variables existe in your .env file
*/

class DatabaseConnector
{
    private $db_connection = null;
    public function __construct()
    {
        $host = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];
        $db   = $_ENV['DB_DATABASE'];
        $user = $_ENV['DB_USERNAME'];
        $pass = $_ENV['DB_PASSWORD'];

        try {
            $this->db_connection = new \PDO(
                $_ENV['DB_SOCKET_PATH'] . $host . ';port=' . $port . ';charset=utf8mb4;dbname=' . $db,
                $user,
                $pass
            );
        } catch (\PDOException $e) {
            throw new \PDOException('Echec de connexion à la BD.' . PHP_EOL . $e->getMessage());
        }
    }

    public function get_connection()
    {
        return $this->db_connection;
    }
}
