<?php

namespace App\controllers\admin;

use \App\models\entity\Admin;
use \App\models\entity\User;
use \App\database\Database;
use \Exception;

class ContentController{

    /**
     * Método responsável por retornar opções de busca por inserção
     */
    public static function getSelectsInsercoes()
    {

        $optTime = isset($_SESSION["optTime"]) ? $_SESSION["optTime"] : null;
        $optAdmin = isset($_SESSION["optAdmin"]) ?$_SESSION["optAdmin"] : null;
        $optTabela = isset($_SESSION["optTabela"]) ? $_SESSION["optTabela"] : null;

        unset($_SESSION["optTime"]);
        unset($_SESSION["optAdmin"]);
        unset($_SESSION["optTabela"]);

        $adminsOptions = "<option value='nenhum'>nenhum</option>";

        try {
            
            $admins = Admin::getAdmins();

            $ids = "";

            foreach ($admins as $admin) {

                $ids .= "'{$admin['usuario_id']}',";

            }

            $where = "id IN(" . substr($ids, 0, (strlen($ids) - 1)) . ")";

            $users = User::getUsers($where);

            foreach ($users as $user) {

                $adminsOptions .= "
                    <option value='{$user['id']}' ". ($user['id'] == $optAdmin ? 'selected' : '') .">{$user['nome']}</option>
                ";

            }

        } catch (Exception $e) {
            
            $_SESSION["api_error"] = "Erro na aplicação: " . $e->getMessage();

            header("Location: /admin");

            exit;

        }

        return "<div class='selects-insert'>
                    <form method='post' class='filter-content'>
                        <img src='../../../resources/assets/icons/icons8-filtro-50.png'>
                        <select name='opt-time'>
                            <option value='nenhum'". ($optTime == 'nenhum' ? 'selected' : '') .">nenhum</option>
                            <option value='1hr'". ($optTime == '1hr' ? 'selected' : '') .">última hora</option>
                            <option value='3hr'". ($optTime == '3hr' ? 'selected' : '') .">última 3 horas</option>
                            <option value='1d'". ($optTime == '1d' ? 'selected' : '') .">último dia</option>
                            <option value='3d'". ($optTime == '3d' ? 'selected' : '') .">último 3 dias</option>
                            <option value='1s'". ($optTime == '1s' ? 'selected' : '') .">última semana</option>
                            <option value='1m'". ($optTime == '1m' ? 'selected' : '') .">último mês</option>
                            <option value='3m'". ($optTime == '3m' ? 'selected' : '') .">últimos três meses</option>
                        </select>
                        <img src='../../../resources/assets/icons/icons8-filtro-50.png'>
                        <select name='opt-admin'>
                            $adminsOptions
                        </select>
                        <img src='../../../resources/assets/icons/icons8-filtro-50.png'>
                        <select name='opt-tabela'>
                            <option value='nenhum'". ($optTabela == 'nenhum' ? 'selected' : '') .">nenhuma</option>
                            <option value='atores'". ($optTabela == 'atores' ? 'selected' : '') .">Atores</option>
                            <option value='filmes'". ($optTabela == 'filmes' ? 'selected' : '') .">Filmes</option>
                        </select>
                        <button class='btn-cadastro'>Fazer Buscar</button>
                    </form>
                </div>";
                
    }

    
    /**
     * Método responsável por retornar as inserções sem filtros
     */
    public static function getGenericInsercoes()
    {
        try {

            $db = (new Database("log_insercoes"))->select();

            $html = "";

            if (count($db) > 0) {

                foreach ($db as $infos) {

                    $especificDados = (new Database($infos["nome_tabela"]))->select("id = " . $infos["dado_id"]);

                    if ($infos["nome_tabela"] == "filmes") {

                        $especificHtml = "
                            <p class='update-film-id'>Relatório do item de Id: " . $especificDados[0]['id'] . " </p>
                            <input name='titulo' type='text' value='" . $especificDados[0]['titulo'] . "' maxlength='200'>
                            <input name='tagline' type='text' value='" . $especificDados[0]['tagline'] . "' maxlength='255'>
                            <input name='ano_lancamento' type='number' value='" . $especificDados[0]['ano_lancamento'] . "' min='1900' max='" . date('Y') . "' placeholder='Ano de Lançamento'>
                            <input name='data_lancamento' type='date' value='" . $especificDados[0]['data_lancamento'] . "' placeholder='Data de Lançamento'>
                            <input name='duracao' type='number' value='" . $especificDados[0]['duracao'] . "' min='1' placeholder='Duração (min)'>
                            <input name='nota_gererica' type='number' step='0.1' value='" . $especificDados[0]['nota_generica'] . "' min='0' max='10' placeholder='Nota Genérica'>
                            <input name='votos_genericos' type='number' value='" . $especificDados[0]['votos_generico'] . "' min='0' placeholder='Votos Genéricos'>
                            <input name='generos' type='text' value='" . $especificDados[0]['generos'] . "' maxlength='255' placeholder='Gêneros'>
                            <input name='diretores' type='text' value='" . $especificDados[0]['diretores'] . "' maxlength='255' placeholder='Diretores'>
                            <input name='empresas' type='text' value='" . $especificDados[0]['empresas'] . "' maxlength='255' placeholder='Empresas'>
                            <input name='paises' type='text' value='" . $especificDados[0]['paises'] . "' maxlength='255' placeholder='Países'>
                            <input names='orcamento' type='number' value='" . $especificDados[0]['orcamento'] . "' min='0' placeholder='Orçamento'>
                            <input name='bilheteria' type='number' value='" . $especificDados[0]['bilheteria'] . "' min='0' placeholder='Bilheteira'>
                            <input name='poster_url' type='url' value='" . $especificDados[0]['poster_url'] . "' maxlength='500' placeholder='URL do Poster'>
                            <input name='trailer_url' type='url' value='" . $especificDados[0]['trailer_url'] . "' maxlength='500' placeholder='URL do Trailer'>
                            <input name='streaming' type='text' value='" . $especificDados[0]['streaming_disponivel'] . "' maxlength='255' placeholder='Streaming Disponível'>
                            <div class='btn-especifics-actions'>
                                <button class='btn-danger'>Enviar relatorio com ajustes</button>
                                <button class='btn-cadastro'>Fechar</button>
                            </div>
                        ";

                    } elseif ($infos["nome_tabela"] == "atores") {

                        $especificHtml = "
                            <p>Id: " . $especificDados[0]['id'] . " </p>
                            <input type='text' value='" . $especificDados[0]['nome'] . "' maxlength='100'>
                            <input type='date' value='" . $especificDados[0]['data_nascimento'] . "' placeholder='Data de Nascimento'>
                            <input type='text' value='" . $especificDados[0]['foto_path'] . "' maxlength='500' placeholder='Caminho da Foto'>
                            <input type='text' value='" . $especificDados[0]['genero'] . "' maxlength='20' placeholder='Gênero'>
                            <input type='number' step='0.1' value='" . $especificDados[0]['popularidade'] . "' min='0' max='10' placeholder='Popularidade'>
                            <input type='text' value='" . $especificDados[0]['nacionalidade'] . "' maxlength='50' placeholder='Nacionalidade'>
                        ";

                    }

                    $html .= "<div class='relatorio-container'>
                                <div class='infos'>
                                    <p>{$infos['dado_id']}</p>
                                    <p>{$infos['quem_inseriu_id']}</p>
                                    <p class='tabela-especific'>{$infos['nome_tabela']}</p>
                                    <p>{$infos['timestamp']}</p>
                                    <button class='btn-cadastro especific-btn'>Ver relatório detalhado</button>
                                </div>
                                <div class='most-especific-infos'>
                                    $especificHtml
                                </div>
                            </div>";

                }

                return $html;

            }

            return "Ops.., parece que nenhuma inserção no banco foi feita!";

        } catch (Exception $e) {
            
            $_SESSION["api_error"] = "Erro na aplicação: " . $e->getMessage();

            header("Location: /admin");

            exit;

        }

    }

    /**
     * Método responsável por retornar as inserções de acordo com o filtro especificado
     */
    public static function getInsercoes()
    {

        $optTime = isset($_POST["opt-time"]) ? $_POST["opt-time"] : null;
        $optAdmin = isset($_POST["opt-admin"]) ? $_POST["opt-admin"] : null;
        $optTabela = isset($_POST["opt-tabela"]) ? $_POST["opt-tabela"] : null;

        $_SESSION["optTime"] = $optTime;
        $_SESSION["optAdmin"] = $optAdmin;
        $_SESSION["optTabela"] = $optTabela;

        if ($optTime || $optAdmin) {
            $where = "";

            switch ($optTime) {
                case "1hr":
                    $where .= "(timestamp BETWEEN DATE_SUB(NOW(), INTERVAL 1 HOUR) AND NOW())";
                    break;
                case "3hr":
                    $where .= "(timestamp BETWEEN DATE_SUB(NOW(), INTERVAL 3 HOUR) AND NOW())";
                    break;
                case "1d":
                    $where .= "(timestamp BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW())";
                    break;
                case "3d":
                    $where .= "(timestamp BETWEEN DATE_SUB(NOW(), INTERVAL 3 DAY) AND NOW())";
                    break;
                case "1s":
                    $where .= "(timestamp BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW())";
                    break;
                case "1m":
                    $where .= "(timestamp BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND NOW())";
                    break;
                case "3m":
                    $where .= "(timestamp BETWEEN DATE_SUB(NOW(), INTERVAL 3 MONTH) AND NOW())";
                    break;
                default:
                    $where = ""; 
                    break;
            }

            try {
                
                if ($optAdmin) {

                    Admin::$id = $optAdmin;

                    $idAdmin = Admin::getAdminById();

                    if($where == ""){
                        
                        $where .= $optAdmin !== "nenhum" ? "quem_inseriu_id = " . $idAdmin[0]["id"] : "";

                    }else{
                        
                        $where .= $optAdmin !== "nenhum" ? " AND quem_inseriu_id = " . $idAdmin[0]["id"] : "";

                    }

                }

                $db = (new Database("log_insercoes"))->select($where);

                if (count($db) > 0) {

                    foreach ($db as $infos) {
                                
                        $tabela = isset($optTabela) && $optTabela !== "nenhum" ? $optTabela : $infos["nome_tabela"];
                        
                        $especificDados = (new Database($tabela))->select("id = " . $infos["dado_id"]);

                        if(count($especificDados) > 0){

                            if ($infos["nome_tabela"] == "filmes") {

                                $especificHtml = "
                                    <p class='update-film-id'>Relatório do item de Id: " . $especificDados[0]['id'] . " </p>
                                    <input name='titulo' type='text' value='" . $especificDados[0]['titulo'] . "' maxlength='200'>
                                    <input name='tagline' type='text' value='" . $especificDados[0]['tagline'] . "' maxlength='255'>
                                    <input name='ano_lancamento' type='number' value='" . $especificDados[0]['ano_lancamento'] . "' min='1900' max='" . date('Y') . "' placeholder='Ano de Lançamento'>
                                    <input name='data_lancamento' type='date' value='" . $especificDados[0]['data_lancamento'] . "' placeholder='Data de Lançamento'>
                                    <input name='duracao' type='number' value='" . $especificDados[0]['duracao'] . "' min='1' placeholder='Duração (min)'>
                                    <input name='nota_gererica' type='number' step='0.1' value='" . $especificDados[0]['nota_generica'] . "' min='0' max='10' placeholder='Nota Genérica'>
                                    <input name='votos_genericos' type='number' value='" . $especificDados[0]['votos_generico'] . "' min='0' placeholder='Votos Genéricos'>
                                    <input name='generos' type='text' value='" . $especificDados[0]['generos'] . "' maxlength='255' placeholder='Gêneros'>
                                    <input name='diretores' type='text' value='" . $especificDados[0]['diretores'] . "' maxlength='255' placeholder='Diretores'>
                                    <input name='empresas' type='text' value='" . $especificDados[0]['empresas'] . "' maxlength='255' placeholder='Empresas'>
                                    <input name='paises' type='text' value='" . $especificDados[0]['paises'] . "' maxlength='255' placeholder='Países'>
                                    <input names='orcamento' type='number' value='" . $especificDados[0]['orcamento'] . "' min='0' placeholder='Orçamento'>
                                    <input name='bilheteria' type='number' value='" . $especificDados[0]['bilheteria'] . "' min='0' placeholder='Bilheteira'>
                                    <input name='poster_url' type='url' value='" . $especificDados[0]['poster_url'] . "' maxlength='500' placeholder='URL do Poster'>
                                    <input name='trailer_url' type='url' value='" . $especificDados[0]['trailer_url'] . "' maxlength='500' placeholder='URL do Trailer'>
                                    <input name='streaming' type='text' value='" . $especificDados[0]['streaming_disponivel'] . "' maxlength='255' placeholder='Streaming Disponível'>
                                    <div class='btn-especifics-actions'>
                                        <button class='btn-danger'>Enviar relatorio com ajustes</button>
                                        <button class='btn-cadastro'>Fechar</button>
                                    </div>
                                ";
    
                            } elseif ($infos["nome_tabela"] == "atores") {
    
                                $especificHtml = "
                                    <p>Id: " . $especificDados[0]['id'] . " </p>
                                    <input type='text' value='" . $especificDados[0]['nome'] . "' maxlength='100'>
                                    <input type='date' value='" . $especificDados[0]['data_nascimento'] . "' placeholder='Data de Nascimento'>
                                    <input type='text' value='" . $especificDados[0]['foto_path'] . "' maxlength='500' placeholder='Caminho da Foto'>
                                    <input type='text' value='" . $especificDados[0]['genero'] . "' maxlength='20' placeholder='Gênero'>
                                    <input type='number' step='0.1' value='" . $especificDados[0]['popularidade'] . "' min='0' max='10' placeholder='Popularidade'>
                                    <input type='text' value='" . $especificDados[0]['nacionalidade'] . "' maxlength='50' placeholder='Nacionalidade'>
                                ";
    
                            }
    
                            $_SESSION["especific_inserts"] .= "<div class='relatorio-container'>
                                    <div class='infos'>
                                        <p>{$infos['dado_id']}</p>
                                        <p>{$infos['quem_inseriu_id']}</p>
                                        <p class='tabela-especific'>{$infos['nome_tabela']}</p>
                                        <p>{$infos['timestamp']}</p>
                                        <button class='btn-cadastro especific-btn'>Ver relatório detalhado</button>
                                    </div>
                                    <div class='most-especific-infos'>
                                        $especificHtml
                                    </div>
                                </div>";

                        }

                    }

                }

                if(!isset($_SESSION["especific_inserts"])){

                    $_SESSION["especific_inserts"] = "<div class='relatorio-container'>
                    
                        <div class='infos'>
                            <p class='none'>Nenhum dado retornado da sua consulta!</p>
                        </div>
                    
                    </div>";

                }

                $_SESSION["api_sucess"] = "Busca realizada com sucesso!";

                header("Location: /admin");

                exit();

            } catch (Exception $e) {
                
                $_SESSION["api_error"] = "Erro na aplicação: " . $e->getMessage();

                header("Location: /admin");

                exit();

            }

        }

        $_SESSION["api_error"] = "Não apague os valores das option!";

        header("Location: /admin");

        exit();

    }

}