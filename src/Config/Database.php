<?php

class Database
{
    private $host = "localhost";
    private $db_name = "tournament_y2";
    private $username = "root";
    private $password = "";

    public $USER_TABLE = "users";
    public $TOURNAMENT_TABLE = "tournaments";
    public $STAGE_TABLE = "stages";
    public $MATCH_TABLE = "matches"; //TODO: Can not use "match", rename to matchup in SQL and here.
    public $ACTIVITY_TABLE = "activities";

    public $connection;

    public function __construct(){
        $this->get_db_connect();
    }

    private function get_db_connect()
    {
        $this->connection = null;

        try {
            $this->connection = new mysqli(
                $this->host, $this->username,
                $this->password, $this->db_name
            );
        } catch (mysqli_sql_exception $e) {
            echo "Connection Failed: " . $e . "<br>";
        }
        return $this->connection;
    }

    public function close()
    {
        return $this->connection->close();
    }

    public function prepared_query($query, $params, $types = "") {
        $types = $types ?: str_repeat("s", count($params));
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt;
    }

    public function stripString($something){
        return $something;
    }

//    public function lastInsertedId(){
//        return $this->get_db_connect()->insert_id;
//    }
}
