<?php

namespace proyecto\Models;
use proyecto\Models\Models;
use proyecto\Response\Success;




class Usuarios extends Models{

    
    
    protected $fillable = ["ID_USUARIO", "CONTRASEÑA", "FECHA_REGISTRO"];
    protected $table = "Usuarios";
}