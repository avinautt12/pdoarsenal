<?php

namespace proyecto\Models;

use proyecto\Models\Models;
use proyecto\Response\Success;
use proyecto\Models\Table;

class Categorias_productos Extends models {
    protected $table = "categoria_productos";
    protected $id = "ID_CATEGORIA";
    protected $filleable = ['ID_CATEGORIA', 'NOMBRE'];

    public function obtenerCategorias() {
        $categoria = new Table();
        $todaslascategorias = $categoria->query("SELECT NOMBRE FROM categoria_productos");

        $success = new Success($todaslascategorias);
        return $success->send();
    }
} 