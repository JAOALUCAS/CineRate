<?php

namespace App\database;

use PDO;
use PDOException;

class Database{

    private static $dbName;

    private static $dbHost;

    private static $dbPort;

    private static $dbUser;

    private static $dbPass;

    private $table;

    private $connection;

    public function __construct($table)
    {
        
        $this->table = $table;

        $this->setConnection();

    }

    public static function setConfig($dbName, $dbHost, $dbPort, $dbUser, $dbPass)
    {

        self::$dbName = $dbName;

        self::$dbHost = $dbHost;

        self::$dbPort = $dbPort;

        self::$dbUser = $dbUser;

        self::$dbPass = $dbPass;     

    }
    
    private function setConnection()
    {

        try{

            $dsn = "mysql:host=".self::$dbHost.";dbname=".self::$dbName.";port=".self::$dbPort.";";

            $this->connection = new PDO($dsn, self::$dbUser, self::$dbPass);

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }catch(PDOException $e){
        
            die("ERROR: " . $e->getMessage());

        }

    }

    public function execute($sql, $params = [])
    {

        try{
                
            $statement = $this->connection->prepare($sql);

            $statement->execute($params);

            return $statement->fetchAll(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            
            die("ERROR: " . $e->getMessage());

        }

    }

    public function insert($values)
    {

        $fields = array_keys($values);

        $binds = array_pad([], count($fields), "?");

        $sql = "INSERT INTO ".$this->table."(".implode(",",$fields).") VALUES (".implode(",",$binds).")";

        return $this->execute($sql, array_values($values));

    }

    public function select($where = null, $order = null, $limit = null, $fields = "*", $params = [])
    {

        $where = !empty($where) ? "WHERE ".$where : "";

        $order = !empty($order) ? "ORDER BY ".$order : ""; 

        $limit = !empty($limit) ? "LIMIT ".$limit : "";

        $sql = "SELECT ".$fields." FROM ".$this->table." ".$where." ".$order." ".$limit." ";

        return $this->execute($sql, $params);

    }

    public function update($where, $values)
    {
        
        $fields = array_keys($values);

        $sql = "UPDATE ".$this->table." SET (".implode("=?",$fields)."=? WHERE ".$where;

        return $this->execute($sql, array_values($values));

    }

    public function delete($values)
    {

        $sql = "DELETE FROM ".$this->table." WHERE ".$values;

        return $this->execute($sql);

    }

}