<?php

namespace proyecto\Models;

use proyecto\Models\Models;

class CarritoModel {
    public function crearOrdenVenta($idOrden, $idCliente, $fechaOrden) {
        $conexion = Conexion::getConexion();
        $stmt = $conexion->prepare("CALL CrearOrdenVenta(?, ?, ?)");
        $stmt->bind_param("sss", $idOrden, $idCliente, $fechaOrden);
        return $stmt->execute();
    }

    public function agregarDetalleVenta($idOrden, $idProducto, $cantidad, $total) {
        $conexion = Conexion::getConexion();
        $stmt = $conexion->prepare("CALL AgregarDetalleVenta(?, ?, ?, ?)");
        $stmt->bind_param("ssid", $idOrden, $idProducto, $cantidad, $total);
        return $stmt->execute();
    }

    public function crearPago($idPago, $idOrden, $formaPago, $estadoPago) {
        $conexion = Conexion::getConexion();
        $stmt = $conexion->prepare("CALL CrearPago(?, ?, ?, ?)");
        $stmt->bind_param("ssss", $idPago, $idOrden, $formaPago, $estadoPago);
        return $stmt->execute();
    }
}
