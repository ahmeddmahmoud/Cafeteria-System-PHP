<?php
class DB
{
    private $host = "localhost";
    private $user = "php";
    private $dbname = "cafe";
    private $password = "1234";
    private $connection;

    function __construct()
    {
        $this->connection = new mysqli($this->host, $this->user, $this->password, $this->dbname);
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }
    function getData($tableName, $condition = "1", $column = "*")
{
    $result = $this->connection->query("SELECT $column FROM $tableName WHERE $condition");
    if (!$result) {
        die("Error fetching data: " . $this->connection->error);
    }

    // Check if any rows are returned
    if ($result->num_rows > 0) {
        // Fetch and return the result
        return $result;
    } else {
        // No rows found, return null
        return null;
    }
}


    function getDataSpec($column, $tableName, $condition = "1")
    {
        $result = $this->connection->query("SELECT $column FROM $tableName WHERE $condition");
        if (!$result) {
            die("Error fetching data: " . $this->connection->error);
        }
        return $result;
    }

    function getConnection()
    {
        return $this->connection;
    }

    function updateData($tableName, $setValues, $condition = "1")
    {
        $sql = "UPDATE $tableName SET $setValues WHERE $condition";
        if ($this->connection->query($sql) === FALSE) {
            die("Error updating data: " . $this->connection->error);
        }
    }

    function insertInto($tableName, $columnNames, $values)
    {
        $sql = "INSERT INTO $tableName $columnNames VALUES $values";
        if ($this->connection->query($sql) === FALSE) {
            die("Error inserting data: " . $this->connection->error);
        }
    }

    function delete($tableName, $condition = "1")
    {
        $sql = "DELETE FROM $tableName WHERE $condition";
        if ($this->connection->query($sql) === FALSE) {
            die("Error deleting data: " . $this->connection->error);
        }
    }
    function update_data($tableName, $setData, $condition)
    {
        return $this->connection->query("update $tableName set $setData where $condition");
    }
    function select_data($tableName, $condition = "")
    {

        return $this->connection->query("select * from $tableName $condition");
    }
    function insert_data($tableName, $colNames, $data)
    {

        return $this->connection->query("insert into $tableName ($colNames) values ($data)");
    }
    function getDataPagination($tableName, $condition = "1", $limit, $offset)
    {
        $result = $this->connection->query("SELECT * FROM $tableName WHERE $condition limit $limit offset $offset");
        if (!$result) {
            die("Error fetching data: " . $this->connection->error);
        }
        return $result;
    }
    function getCount($tableName)
    {
        return $this->connection->query("select COUNT(*) as total FROM $tableName");
    }
}
