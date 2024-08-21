<?php

namespace proyecto\Models;
use proyecto\Models\Models;
use proyecto\Response\Success;


/**
 * Class Persona
 */
class socios extends Models
{
    
    protected $filleable = ["ID_SOCIO,ID_CLIENTE,MEMBRESIA,FECHA_INICIO,FECHA_FIN,ESTADO_DE_MEMB"];
    protected $table = "socios";



}