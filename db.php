<?php
class DB {
    private $host = "localhost";
    private $user = "root";
    private $dbname = "cafee";
    private $password = "1234";
    private $connection;

    function __construct() {
        $this->connection = new mysqli($this->host, $this->user, $this->password, $this->dbname);
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }
    function getData($tableName, $condition = "1", $column="*") {
        // echo "SELECT $column FROM $tableName WHERE $condition";
        $result = $this->connection->query("SELECT $column FROM $tableName WHERE $condition");
        if (!$result) {
            die("Error fetching data: " . $this->connection->error);
        }
        return $result;
    }

    function getDataSpec($column , $tableName,$condition = "1") {
        $result = $this->connection->query("SELECT $column FROM $tableName WHERE $condition");
        if (!$result) {
            die("Error fetching data: " . $this->connection->error);
        }
        return $result;
    }
    
    function getConnection() {
        return $this->connection;
    }

    function updateData($tableName, $setValues, $condition = "1") {
        $sql = "UPDATE $tableName SET $setValues WHERE $condition";
        if ($this->connection->query($sql) === FALSE) {
            die("Error updating data: " . $this->connection->error);
        }
    }

    function insertInto($tableName, $columnNames, $values) {
        $sql = "INSERT INTO $tableName $columnNames VALUES $values";
        if ($this->connection->query($sql) === FALSE) {
            die("Error inserting data: " . $this->connection->error);
        }
    }

    function delete($tableName, $condition = "1") {
        $sql = "DELETE FROM $tableName WHERE $condition";
        if ($this->connection->query($sql) === FALSE) {
            die("Error deleting data: " . $this->connection->error);
        }
    }
}
?>
