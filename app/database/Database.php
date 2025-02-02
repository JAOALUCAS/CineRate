<?php

namespace App\database;

class Database{

    /**
     * Array responsável por guadar as configurações do banco de dados
     *
     * @var array
     */
    private $dbConfigs = [];

    private $table;

    private  $params;

    private static function setConnection($querry)
    {

    }

    public static function setConfig(Array $dbConfigs)
    {

    }

    public function buildQuerry($querry, $params, $table)
    {

    }

    private static function update($params, $table)
    {

    }

    
    private static function insert($params, $table)
    {
        
    }

    private static function delete($params, $table)
    {
        
    }

    private static function select($params, $table)
    {
        
    }

}