<?php

namespace Core;

use PDO;

class Database
{
    public $connection;
    public $statement;
    public function __construct($config, $username = 'root', $password = '')
    {
        $dsn = 'mysql:' . http_build_query($config, '', ';');
        $this->connection = new PDO($dsn, $username, $password, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }
    public function query($val, $params = [])
    {
        $this->statement = $this->connection->prepare($val);
        $this->statement->execute($params);
        return $this;
    }
    public function get()
    {
        return $this->statement->fetchAll();
    }
    public function find()
    {
        return $this->statement->fetch();
    }
    public function findOrFail()
    {
        $result = $this->find();
        if (!$result) {
            abort();
        }
        return $result;
    }

    public function select($table, $columns = ['*'], $where = [])
    {
        $columnList = implode(", ", $columns);
        $sql = "SELECT $columnList FROM $table";
        if (!empty($where)) {
            $whereClause = implode(" AND ", array_map(fn($key) => "$key = :$key", array_keys($where)));
            $sql .= " WHERE $whereClause";
        }
        $statement = $this->connection->prepare($sql);
        $statement->execute($where);
        // Fetch all results
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        return is_array($results) && count($results) === 1 ? [$results[0]] : $results;
    }
    public function insert($table, $data)
    {
        $columns = implode(',', array_keys($data));
        $placeholders = implode(', :', array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES (:$placeholders)";
        $this->statement = $this->connection->prepare($sql);
        return $this->statement->execute($data);
    }
    public function update($table, $data, $id)
    {
        $set = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
        $sql = "UPDATE $table SET $set WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute(['id' => $id] + $data);
    }
    public function delete($table, $id)
    {
        $sql = "DELETE FROM $table WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
