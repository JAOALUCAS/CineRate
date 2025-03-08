<?php

namespace App\controllers;

class Manutenance
{

    /**
     * Método responsável por definir o status de manutenção
     */
    public static function defineStatus($status = false)
    {
        
        self::updateEnv($status);

    }

    /**
     * Método responsável por atualizar o .env
     */
    private static function updateEnv($status)
    {
        $caminho = __DIR__ . "/../../.env";

        if (!file_exists($caminho)) {

            return;

        }

        $linhas = file($caminho, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $novaEnv = [];

        $atualizado = false;

        foreach ($linhas as $linha) {

            if (strpos($linha, "MANUTENANCE=") === 0) {

                $novaEnv[] = "MANUTENANCE={$status}";

                $atualizado = true;

            } else {

                $novaEnv[] = $linha;

            }

        }

        if (!$atualizado) {

            $novaEnv[] = "MANUTENANCE={$status}";

        }

        file_put_contents($caminho, implode("\n", $novaEnv) . "\n");

    }
    
    /**
     * Método responsável por definir a manutenção das páginas
     */
    public static function defineManutenance()
    {

        $statusServer = isset($_POST["manutenanceForm"]) ? $_POST["manutenanceForm"] : null;

        if(isset($statusServer)){
            
            $booleanValue = filter_var($statusServer, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            
            Manutenance::defineStatus($booleanValue);
            
            $_SESSION["api_sucess"] = "Status de manutenção definido com sucesso!";
                
            header("Location: /admin");

            exit();

        }

        $_SESSION["api_error"] = "Status de manutenção não pode ser definido!";
    
        header("Location: /admin");

        exit();

    }
    
    /**
     * Método responsável por retornar o botão de manutenção
     */
    public static function getManutenanceBtn()
    {

        $envValue = getenv("MANUTENANCE");
                
        $booleanValue = filter_var($envValue, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        
        $msg = "Colocar em Manutenção";

        $value = "true";

        $class = "danger";
        
        if($booleanValue){
            
            $msg = "Tirar da manutenção";

            $value = "false";

            $class = "cadastro";

        }

        return "<form method='post' class='manutenance-form'>
                    <input type='hidden' name='manutenanceForm' value='$value'>
                    <button class='btn-$class' id='btn-manutenance'>$msg</button>
                </form>";

    }

}
