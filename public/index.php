<?php

namespace proyecto;

require("../vendor/autoload.php");

use proyecto\Controller\crearPersonaController;
use proyecto\Controller\PersonasController;
use proyecto\Controller\MostrarSociosController;
use proyecto\Controller\LoginController;
use proyecto\Controller\LoginSociosController;
use proyecto\Controller\ProductosController;
use proyecto\Controller\EmpleadosController;
use proyecto\Controller\ClasesController;
use proyecto\Controller\CarritoController;
use proyecto\Models\User;
use proyecto\Models\clientes;
use proyecto\Models\inbody_citas;
use proyecto\Models\productos_servicios;
use proyecto\Models\Categorias_productos;
use proyecto\Models\Personas;
use proyecto\Models\Empleados;
use proyecto\Models\Clases;
use proyecto\Response\Failure;
use proyecto\Response\Success;



Router::get('/prueba', [crearPersonaController::class, "prueba"]);
Router::get('/empleados', [Empleados::class, "mostrarEmpleados"]);
Router::get('/socios', [MostrarSociosController::class, "mostrarsocios"]);
Router::get('/citas', [inbody_citas::class, "mostrarcitas"]);
Router::get('/categorias', [Categorias_productos::class, "obtenerCategorias"]);
Router::get('/crearpersona', [crearPersonaController::class, "crearPersona"]);
Router::get('/productos', [productos_servicios::class, "mostrarProductos"]);
Router::get('/adminproductos', [productos_servicios::class, "mostrarProductosAdmin"]);
Router::get('/producto', [ProductosController::class, "obtenerProductoPorId"]);
Router::get('/clases', [Clases::class, "mostrarClases"]);
Router::get('/alumnos', [Clases::class, "mostrarAlumnos"]);
Router::get('/empleado/obtener', [EmpleadosController::class, "obtenerEmpleadoPorId"]);
Router::get('/usuario/buscar/$id', function ($id) {
    $user = User::find($id);
    if (!$user) {
        $r = new Failure(404, "no se encontró el usuario");
        return $r->Send();
    }
    $r = new Success($user);
    return $r->Send();
});
Router::get('/respuesta', [crearPersonaController::class, "response"]);

// Rutas POST
Router::post('/registro', [PersonasController::class, "registroclientes"]);
Router::post('/loginSocios', [LoginSociosController::class, "loginsocios"]);
Router::post('/insertarproducto', [ProductosController::class, "insertarProducto"]);
Router::post('/producto/actualizar', [ProductosController::class, "actualizarProducto"]);
Router::post('/registro',[PersonasController::class,"registroclientes"]);
Router::post('/registroEmpleados',[EmpleadosController::class,"registroempleados"]);
Router::post('/loginClientes',[LoginController::class,"login"]);
Router::post('/loginSocios',[LoginSociosController::class,"loginsocios"]);
Router::post('/agregarclase',[ClasesController::class,"agregarClase"]);
Router::post('/editarclase', [ClasesController::class, "editarClase"]);
Router::post('/inscribir',[ClasesController::class,"inscripcionClases"]);
Router::post('/asistencia/registrar', [ClasesController::class, "registrarAsistencia"]);
Router::post('/carrito/compra', [CarritoController::class, "procesarCompra"]);

// Rutas DELETE
Router::delete('/producto/eliminar', [ProductosController::class, "eliminarProducto"]);
Router::delete('/empleado/eliminar', [EmpleadosController::class, "eliminarEmpleado"]);

// Ruta para manejar errores 404
Router::any('/404', '../views/404.php');