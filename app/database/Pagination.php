<?php

namespace App\database;

use \App\database\Database;

class Pagination{

    /**
     * Número máximo de registros por página
     */
    private $limit;

    /**
     * Quantidade de resultados do banco
     */
    private $results;

    /**
     * Quantidade de páginas
     */
    private $pages;

    /**
     * Página atual
     */
    private $currentPage;

    /**
     * Construtor da classe
     *
     * @param integer $limit
     * @param integer $results
     * @param integer $currentPage
     */
    public function __construct($limit, $results, $currentPage = 1)
    {
        
        $this->limit = $limit;

        $this->results = $results;

        $this->currentPage = (is_numeric($currentPage) and $currentPage > 0) ? $currentPage : 1;

    }

    /**
     * Método responsável por calcular o total de páginas
     */
    private function calculate()
    {

        $this->pages = $this->results > 0 ? ceil($this->results / $this->limit) : 1;

        $this->currentPage = $this->currentPage <= $this->pages ? $this->currentPage : $this->pages;

    }

    /**
     * Método responsável por retornar o limit do sql
     */
    public function getLimit()
    {

        $offset = ($this->limit * ($this->currentPage - 1));

        return $offset.",".$this->limit;

    }

}