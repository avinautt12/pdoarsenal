<?php

namespace proyecto\Models;
use proyecto\Models\Models;
use proyecto\Models\Table;
use proyecto\Response\Success;


/**
 * Class Inbody_citas
 */
class inbody_citas extends Models
{
    protected $fillable = ["ID_CITA,ID_CLIENTE,ID_FECHA_HORA,PRECIO,FORMA_PAGO,ESTADO_CITA"];
    protected $table = "inbody_citas";

    public function mostrarcitas(){
        $inbody_citas = inbody_citas::all();
        $success = new Success($inbody_citas);
        return $success->send();
    }

    public function obtenerFechasFuturas(){
        $fechasFuturas = new table();
        $todaslasfechas = $fechasFuturas->query("SELECT FECHAS FROM FECHAS WHERE FECHAS > CURRENT_DATE");
        $success = new Success($todaslasfechas);
        return $success->send();
    }

    public function obtenerHoras(){
        $horas = new table();
        $todaslashoras = $horas->query("SELECT HORA FROM HORARIOS");
        $success = new Success($todaslashoras);
        return $success->send();
    }
    
}
