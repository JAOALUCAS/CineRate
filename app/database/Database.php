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

    private static $table;

    private static $connection;

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
    
    private static function setConnection()
    {

        try{

            $dsn = "mysql:host=".self::$dbHost.",dbname=".self::$dbName.",port=".self::$dbPort;

            self::$connection = new PDO($dsn, self::$dbUser, self::$dbPass);

            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }catch(PDOException $e){
            
            die("ERROR: " . $e->getMessage());

        }

    }

    public function execute($sql, $params = [])
    {

        try{
                
            $statement = self::$connection->prepare($sql);

            $statement->execute($params);

            return $statement;

        }catch(PDOException $e){
            
            die("ERROR: " . $e->getMessage());

        }

    }

    public function insert($values)
    {

        $fields = array_keys($values);

        $binds = array_pad([], count($fields), "?");

        $sql = "INSERT INTO ".$this->table."(".implode(",",$fields).") VALUES (".implode(",",$fields).")";

        $this->execute($sql, array_values($values));

    }

    public function select($where = null, $order = null, $limit = null, $fields = "*")
    {

        $where = strlen($where) ? "WHERE " : "";

        $order = strlen($order) ? "ORDER" : ""; 

        $limit = strlen($limit) ? "LIMIT" : "";

        $sql = "SELECT ".$fields." FROM ".$this->table." ".$where." ".$order." ".$limit." ";

        $this->execute($sql);

    }

    public function update($where, $values)
    {
        
        $fields = array_keys($values);

        $sql = "UPDATE ".$this->table."SET (".implode("=?",$fields)."=? WHERE ".$where;

        $this->execute($sql, array_values($values));

    }

    public function delete($values)
    {

        $sql = "DELETE FROM ".$this->table." WHERE ".$values;

        $this->execute($sql);

    }

}