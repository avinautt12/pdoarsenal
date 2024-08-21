<?php

namespace proyecto\Controller;

use proyecto\Models\CarritoModel;
use proyecto\Response\Success;
use proyecto\Response\Failure;

class CarritoController {
    public function procesarCompra() {
        $carritoModel = new CarritoModel();
        
        $idOrden = uniqid('ORD_');
        $idCliente = $_POST['idCliente'];
        $fechaOrden = date('Y-m-d');
        
        if ($carritoModel->crearOrdenVenta($idOrden, $idCliente, $fechaOrden)) {
            foreach ($_POST['productos'] as $producto) {
                $carritoModel->agregarDetalleVenta(
                    $idOrden, 
                    $producto['id'], 
                    $producto['cantidad'], 
                    $producto['precio'] * $producto['cantidad']
                );
            }

            $carritoModel->crearPago(uniqid('PAGO_'), $idOrden, $_POST['formaPago'], 'PENDIENTE');
            
            return new Success(["message" => "Compra procesada correctamente"]);
        }
        
        return new Failure(500, "Error al procesar la compra");
    }
}
