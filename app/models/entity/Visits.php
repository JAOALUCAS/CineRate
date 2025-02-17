<?php

namespace App\models\entity;

use \App\database\Database;
use \App\session\Session;

class Visits{

    private static $db;

    /**
     * Método responável por definir o database
     */
    public function __construct()
    {
        
        self::$db = new Database("visitas");

    }

    /**
     * Método responável por atualizar o número de visitantes
     */
    public static function addVisit()
    {

        $ipVisitante = $_SERVER['REMOTE_ADDR'];

        if(!isset($_SESSION["visitado"])){

            $_SESSION["visitado"] = true;
            
            self::$db->insert(["ip"=>$ipVisitante]);

        }

    }

    /**
     * Métodos responsável por retornar todas as visitas
     */
    public static function getVisits()
    {

        return self::$db->select("data_visita >= DATE_SUB(NOW(), INTERVAL 1 MONTH)", null, null, "COUNT(*)");

    }

    /**
     * Métodos responsável por retornar todas as visitas únicas
     */
    public static function getUniqueVisit()
    {

        return self::$db->select("data_visita >= DATE_SUB(NOW(), INTERVAL 1 MONTH)", null, null,"COUNT(DISTINCT ip)");

    }

}