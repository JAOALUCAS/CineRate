<?php

namespace App\models\entity;

use \App\database\Database;

class Admin{

    /**
     * Id do admin
     *
     * @var int
     */
    public $id;

    /**
     * Nome do admin
     *
     * @var string
     */
    public $nome;

    /**
     * Email do admin
     *
     * @var string
     */
    public $email;

    /**
     * Senha do admin
     *
     * @var string
     */
    public $senha;

    /**
     * Método responsável por cadastrar novo admin
     */
    public function cadastrar()
    {

        return (new Database("admins"))->insert([
            "nome" => $this->nome,
            "email" => $this->email,
            "senha" => $this->senha
        ]);

    }

    /**
     * Método responsável por registrar a inserção de um novo item no banco de dados
     */
    public static function cadastrarInsercao()
    {

        return (new Database("log_insercoes"))->insert([
            "dado_id" => $_SESSION["last_id"],
            "quem_inseriu_id" => $_SESSION["admin_id"]
        ]);

    }

    /**
     * Método responsável por excluir registros
     */
    public function excluir()
    {
     
        return (new Database("admins"))->delete("id = ". $this->id);

    }

    /**
     * Método responsável por atualizar os registros 
     */
    public function atualizar()
    {

        return (new Database("admins"))->update("id = " . $this->id, [
            "nome" => $this->nome,
            "email" => $this->email,
            "senha" => $this->senha
        ]);

    }
    
    /**
     * Método responsável por selecionar o admin pelo id
     */
    public function getAdminById()
    {

        return (new Database("usuarios"))->select("usuario_id = ?", null, null, "*", [$this->id]);

    }
    
    /**
     * Método responsável por selecionar o admin pelo email
     */
    public function getAdminByEmail()
    {

        return (new Database("admin"))->select("email = ?", null, null, "*", [$this->email]);

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