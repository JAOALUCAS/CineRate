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

}
