<?php

namespace App\database;

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

    }

    public function insert($values){

    }

    public function select($values){

    }

    public function update($values){

    }

    public function delete($values){

    }

}