<?php

namespace chrismcgahan;

class Db {
    public function __construct($params) {
        $this->pdo = new \PDO(
            "mysql:dbname={$params['name']};host={$params['host']}",
            $params['user'],
            $params['pass']
        );
    }

    public function query($query, $args = []) {
        $stmt = $this->pdo->prepare($query);
        foreach ($args as $i => $arg) {
            $stmt->bindValue($i + 1, $arg);
        }
        if (! $stmt->execute()) {
            throw new \Exception($stmt->errorInfo()[2]);
        }

        if (preg_match('/^(INSERT|UPDATE|DELETE)/i', $query)) {
            return $stmt->rowCount();
        }
        else {
            return $stmt;
        }
    }

    public function getRow($query, $args = []) {
        return $this->query($query, $args)->fetch(\PDO::FETCH_ASSOC);
    }

    public function getAll($query, $args = []) {
        return $this->query($query, $args)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getOne($query, $args = []) {
        return $this->query($query, $args)->fetchColumn();
    }

    public function getColumn($query, $args = []) {
        $stmt = $this->query($query, $args);

        $results = [];

        while (($field = $stmt->fetchColumn()) !== false) {
            $results[] = $field;
        }

        return $results;
    }

    public function insert($table, $data) {
        $query = "INSERT INTO $table(" . implode(',', array_map(function ($key) {
            return "`$key`";
        }, array_keys($data))) . ") VALUES(" . implode(',', array_map(function ($key) {
            return '?';
        }, array_values($data))) . ')';

        $this->query($query, array_values($data));

        return $this->pdo->lastInsertId();
    }

    public function update($table, $data, $where) {
        $query = "UPDATE $table SET " . implode(',', array_map(function ($key) {
            return "`$key` = ?";
        }, array_keys($data))) . " WHERE " . implode(',', array_map(function ($key) {
            return "`$key` = ?";
        }, array_keys($where)));

        return $this->query($query, array_merge(array_values($data), array_values($where)));
    }
}