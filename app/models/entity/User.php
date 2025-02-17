<?php

namespace App\models\entity;

use \App\database\Database;

class User{

    /**
     * Id do usuário
     *
     * @var int
     */
    public $id;

    /**
     * Nome do usuário
     *
     * @var string
     */
    public $nome;

    /**
     * Email do usuário
     *
     * @var string
     */
    public $email;

    /**
     * Senha do usuário
     *
     * @var string
     */
    public $senha;

    /**
     * Método responsável por cadastrar novo usuário
     */
    public function cadastrar()
    {

        return (new Database("usuarios"))->insert([
            "nome" => $this->nome,
            "email" => $this->email,
            "senha" => $this->senha
        ]);

    }

    /**
     * Método responsável por excluir registros
     */
    public function excluir()
    {
     
        return (new Database("usuarios"))->delete("id = ". $this->id);

    }

    /**
     * Método responsável por atualizar os registros 
     */
    public function atualizar()
    {

        return (new Database("usuarios"))->update("id = " . $this->id, [
            "nome" => $this->nome,
            "email" => $this->email,
            "senha" => $this->senha
        ]);

    }
    
    /**
     * Método responsável por selecionar o usuário pelo id
     */
    public function getUserById($admin = false)
    {

        $where = "id = ?";

        if($admin){
            
            $where = "usuario_id = ?"; 

        }

        return (new Database("usuarios"))->select($where, null, null, "*", [$this->id]);

    }
    
    /**
     * Método responsável por selecionar o usuário pelo email
     */
    public function getUserByEmail()
    {

        return (new Database("usuarios"))->select("email = ?", null, null, "*", [$this->email]);

    }

    /**
     * Método responsável por retornar todos os usuários
     *
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     */
    public static function getUsers($where = null, $order = null, $limit = null, $fields = "*")
    {

        return (new Database("usuarios"))->select($where, $order, $limit, $fields);

    }

}