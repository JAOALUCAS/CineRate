<?php

namespace App\controllers\admin;

use \App\models\entity\User;
use \App\models\entity\Visits;
use \Exception;

class DashboardController{

    /**
     * Método responsável por definir as visitas
     */
    public static function getVisits()
    {
        try {

            $visits = Visits::getVisits();

            return (string)$visits[0]["COUNT(*)"];

        } catch (Exception $e) {
            
            $_SESSION["api_error"] = "Erro ao buscar visitas: " . $e->getMessage();

            return "0";

        }

    }
    
    
    /**
     * Método responsável por definir visitas unicas
     */
    public static function getUniqueVisits()
    {

        try {

            $uniqueVisits = Visits::getUniqueVisit();

            return (string)$uniqueVisits[0]["COUNT(DISTINCT ip)"];

        } catch (Exception $e) {
            
            $_SESSION["api_error"] = "Erro ao buscar visitas únicas: " . $e->getMessage();

            return "0";

        }

    }
    
    /**
     * Método responsável por definir os novos usuarios
     */
    public static function getNewUsers()
    {
        
        try {

            $novosUsuarios = User::getUsers("data_criacao >= DATE_SUB(NOW(), INTERVAL 1 MONTH)", null, null, "COUNT(*)");

            return (string)$novosUsuarios[0]["COUNT(*)"];

        } catch (Exception $e) {
            
            $_SESSION["api_error"] = "Erro ao buscar novos usuários: " . $e->getMessage();

            return "0";

        }

    }

}