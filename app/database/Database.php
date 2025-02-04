<?php

namespace App\database;

class Database{

    /**
     * Array responsável por guadar as configurações do banco de dados
     *
     * @var array
     */
    private $dbConfigs = [];

    private  $params = [];

    private static function setConnection($query)
    {

    }

    public static function setConfig(Array $dbConfigs)
    {

    }

    private function buildQuery($request, $query, $params)
    {

        $value = $request->getPostVars();



    }

    public function prepare($request, $query, $params = [])
    {

        $this->params = $params;

        $this->buildQuery($request, $query, $this->params);

    }

}