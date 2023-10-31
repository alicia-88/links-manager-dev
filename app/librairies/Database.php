<?php

declare(strict_types=1);

namespace App\Librairies;

require_once __DIR__ . '/../config/dbconfig.php';

class Database
{
    public ?\PDO $database = null;
    public function getConnection(): \PDO
    {
        if ($this->database === null) {
            try {
                $this->database = new \PDO(ATTR, USER, PASS, OPTS);
            } catch (\PDOException $e) {
                var_dump($e);
                throw new \PDOException($e->getMessage(), (int)$e->getCode());
            }
        }
        return $this->database;
    }

    public function insert(string $table, array $columns, array $values)
    {
        var_dump("🎇");
        $markers = preg_filter('/^/', ':', $columns);
        $columnsStr = implode(", ", $columns);
        $markersStr = implode(', ', $markers);
        var_dump($markers);
        var_dump($columnsStr);
        var_dump($markersStr);
        var_dump($values);

        $query = "INSERT INTO `$table` ($columnsStr) VALUES ($markersStr);";
        var_dump($query);
        $statement = $this->getConnection()->prepare($query);
        foreach ($markers as $key => $marker) {
            $statement->bindValue($marker, $values[$columns[$key]], \PDO::PARAM_STR);
        }
        $statement->execute();
        $data = $statement->fetch();
        return $data;
    }

    public function find(string $table, string $className, string $columns = '*'): array
    {

        $query = "SELECT $columns FROM `$table`;";
        $statement = $this->getConnection()->query($query);
        $datas = $statement->fetchAll(\PDO::FETCH_CLASS, $className);
        return $datas;
    }

    public function findById(string $table, string $id_column, int $id, string $className, string $columns = '*'): mixed
    {
        $query = "SELECT $columns FROM `$table` WHERE `$id_column`=:id;";
        $statement = $this->getConnection()->prepare($query);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->setFetchMode(\PDO::FETCH_CLASS, $className);
        $statement->execute();
        $data = $statement->fetch();
        return $data;
    }

    public function findByValue(string $table, string $column, string $value, string $columns = '*'): mixed
    {
        $query = "SELECT $columns FROM `$table` WHERE `$column` = :value;";
        $statement = $this->getConnection()->prepare($query);
        $statement->bindValue(':value', $value, \PDO::PARAM_STR);
        $statement->execute();
        $data = $statement->fetch();
        return $data;
    }

    public function updateOneById(string $table, string $column, string $value, string $id_column, int $id)
    {
        $query = "UPDATE `$table` SET `$column` = :value WHERE `$id_column` = :id;";
        $statement = $this->getConnection()->prepare($query);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->bindValue(':value', $value, \PDO::PARAM_STR);
        $statement->execute();
    }

    public function deleteOne(string $table, string $id_column, int $id)
    {
        $query = "DELETE FROM `$table` WHERE `$id_column` = :id;";
        $statement = $this->getConnection()->prepare($query);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
    public function isExist(string $table, string $column, string $value): bool
    {
        $type = $this->findByValue($table, $column, $value);
        if ($type) {
            return true;
        } else {
            return false;
        }
    }
}
