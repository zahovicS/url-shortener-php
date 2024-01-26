<?php

namespace UrlShortenerPhp\Database;

use PDO;

class Database
{
    private $host;
    private $db;
    private $user;
    private $port;
    private $password;
    private $charset;

    public function __construct()
    {
        $this->host = $_ENV["APP_DB_HOSTNAME"];
        $this->db = $_ENV["APP_DB_DATABASE"];
        $this->user = $_ENV["APP_DB_USERNAME"];
        $this->password = $_ENV["APP_DB_PASSWORD"];
        $this->charset = $_ENV["APP_DB_CHARSET"];
        $this->port = $_ENV["APP_DB_PORT"];
    }

    public function connect()
    {
        $connection = "mysql:host=" . $this->host . ";port=" . $this->port. ";dbname=" . $this->db . ";charset=" . $this->charset;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ];
        $pdo = new PDO($connection, $this->user, $this->password, $options);

        return $pdo;
    }
}
