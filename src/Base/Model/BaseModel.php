<?php

namespace UrlShortenerPhp\Base\Model;

use Exception;
use UrlShortenerPhp\Database\Database;

class BaseModel
{
    protected $table = "";
    protected $primarykey = "id";
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function connect()
    {
        return $this->db->connect();
    }

    public function query($query)
    {
        return $this->db->connect()->query($query);
    }
    protected function count()
    {
        if (!$this->table) {
            throw new Exception("No table name");
        }
        $queryString = "SELECT COUNT({$this->primarykey}) as count from {$this->table}";
        $query = $this->prepare($queryString);
        $query->execute();
        $match = $query->fetch();
        return $match === false ? null : $match;
    }
    protected function countBy(array $wheres)
    {
        if (!$this->table) {
            throw new Exception("No table name");
        }
        $queryString = "SELECT COUNT({$this->primarykey}) as count from {$this->table}";
        if (count($wheres) > 0) {
            $queryString .= " WHERE";
        }
        $i = 0;
        foreach ($wheres as $key => $where) {
            $condition = "";
            if ($i > 0) {
                $condition = "AND";
            }
            $queryString .= " $condition {$key} = :{$key}";
            $i++;
        }
        $query = $this->prepare($queryString);
        $query->execute($wheres);
        $match = $query->fetch();
        return $match === false ? null : $match;
    }
    protected function findBy(array $wheres)
    {
        if (!$this->table) {
            throw new Exception("No table name");
        }
        $queryString = "SELECT * from {$this->table}";
        if (count($wheres) > 0) {
            $queryString .= " WHERE";
        }
        $i = 0;
        foreach ($wheres as $key => $where) {
            $condition = "";
            if ($i > 0) {
                $condition = "AND";
            }
            $queryString .= " $condition {$key} = :{$key}";
            $i++;
        }
        $queryString .= " LIMIT 1";
        $query = $this->prepare($queryString);
        $query->execute($wheres);
        $match = $query->fetch();
        return $match === false ? null : $match;
    }
    protected function findAllBy(array $wheres, array $likes = [])
    {
        if (!$this->table) {
            throw new Exception("No table name");
        }
        $queryString = "SELECT * from {$this->table}";
        $queryString .= " WHERE";
        foreach ($wheres as $key => $where) {
            $queryString .= " {$key} = :{$key}";
        }
        if (count($likes) > 0) {
            $i = 0;
            foreach ($likes as $key => $like) {
                $condition = "AND (";
                if ($i > 0) {
                    $condition = "OR";
                }
                $queryString .= " {$condition} {$key} LIKE :{$key}";
                $i++;
            }
            $queryString .= ")";
        }
        $query = $this->prepare($queryString);
        $query->execute(array_merge($wheres, $likes));
        $match = $query->fetchAll();
        return $match === false ? null : $match;
    }

    protected function findAll()
    {
        if (!$this->table) {
            throw new Exception("No table name");
        }
        $query = $this->prepare("SELECT * FROM {$this->table};");
        $query->execute();
        return $query->fetchAll() ?? [];
    }

    protected function find($id)
    {
        if (!$this->table) {
            throw new Exception("No table name");
        }
        $query = $this->prepare("SELECT * FROM {$this->table} WHERE {$this->primarykey} = ? LIMIT 1");
        $query->execute([$id]);
        $match = $query->fetch();
        return $match === false ? null : $match;
    }
    protected function findOrFail($id)
    {
        if (!$this->table) {
            throw new Exception("No table name");
        }
        $query = $this->prepare("SELECT * FROM {$this->table} WHERE {$this->primarykey} = ? LIMIT 1");
        $query->execute([$id]);
        $result = $query->fetch();
        if (!$result) {
            throw new Exception("No result for PRIMARY KEY {$id} in table {$this->table}");
        }
        return $result;
    }

    public function prepare($query)
    {
        return $this->db->connect()->prepare($query);
    }
}
