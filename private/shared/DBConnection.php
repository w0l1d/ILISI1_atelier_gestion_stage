<?php

class Connection
{

    protected static $instance;

    /*
    ******************************************
    Don't worry this is not production version :)
    ******************************************
    */
    private static $dbname = "ilisi1_atelier1_gestion_stages";

    private static $servername = "localhost";

    private static $dsn = 'mysql:host=localhost;dbname=ilisi1_atelier1_gestion_stages';

    private static $username = 'root';

    private static $password = '';

    private function __construct()
    {
        try {
            self::$instance = new PDO(self::$dsn, self::$username, self::$password);
        } catch (PDOException $e) {
            echo "MySql Connection Error: " . $e->getMessage();
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            new Connection();
        }
        return self::$instance;
    }
}

function getDBConnection()
{
    return Connection::getInstance();
}






