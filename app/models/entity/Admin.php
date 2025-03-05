<?php

namespace App\models\entity;

use \App\database\Database;

class Admin{

    /**
     * Id do admin
     *
     * @var int
     */
    public static $id;
    
    /**
     * Email do admin
     *
     * @var string
     */
    public static $email;

    /**
     * Senha do admin
     *
     * @var string
     */
    public static $senha;

    /**
     * Método responsável por cadastrar novo admin
     */
    public static function cadastrar()
    {

        return (new Database("admins"))->insert([
            "usuario_id" => self::$id,
            "senha" => self::$senha
        ]);

    }

    /**
     * Método responsável por registrar a inserção de um novo item no banco de dados
     */
    public static function cadastrarInsercao()
    {

        return (new Database("log_insercoes"))->insert([
            "dado_id" => $_SESSION["last_id"],
            "nome_tabela" => $_SESSION["last_table"],
            "quem_inseriu_id" => $_SESSION["admin_id"]
        ]);

    }

    /**
     * Método responsável por excluir registros
     */
    public static function excluir()
    {
     
        return (new Database("admins"))->delete("id = ". self::$id);

    }
    
    /**
     * Método responsável por selecionar o admin pelo id
     */
    public static function getAdminById()
    {

        return (new Database("admins"))->select("usuario_id = ?", null, null, "*", [self::$id]);

    }
    
    /**
     * Método responsável por selecionar o admin pelo email
     */
    public static function getAdminByEmail()
    {

        return (new Database("admins"))->select("email = ?", null, null, "*", [self::$email]);

    }

    /**
     * Método responsável por retornar todos os admins
     *
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     */
    public static function getAdmins($where = null, $order = null, $limit = null, $fields = "*")
    {

        return (new Database("admins"))->select($where, $order, $limit, $fields);

    }

}