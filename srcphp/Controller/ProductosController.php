<?php

namespace proyecto\Controller;

use proyecto\Models\Table;
use proyecto\Response\Success;
use Exception;

class ProductosController 
{
    public function insertarProducto() {
        // Asegúrate de que la solicitud sea de tipo multipart/form-data
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagen'])) {

            // Verificar que los demás datos estén presentes
            if (!isset($_POST['nombre']) || !isset($_POST['descripcion']) ||
                !isset($_POST['precio']) || !isset($_POST['stock']) ||
                !isset($_POST['categoria'])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
                return;
            }

            // Extraer datos del formulario
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $precio = $_POST['precio'];
            $stock = $_POST['stock'];
            $categoria = $_POST['categoria'];

            // Procesar la imagen
            $imagenTempPath = $_FILES['imagen']['tmp_name'];
            $imagenType = $_FILES['imagen']['type'];

            // Verificar que el archivo es una imagen válida (jpg o png)
            if ($imagenType !== 'image/jpeg' && $imagenType !== 'image/png') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Formato de imagen no válido']);
                return;
            }

            // Convertir la imagen a Base64
            $imagenBase64 = base64_encode(file_get_contents($imagenTempPath));

            // Llamar al procedimiento almacenado para registrar el producto
            $query = "CALL RegistrarProductos(
                '$nombre', 
                '$descripcion', 
                '$precio', 
                '$stock',
                '$imagenBase64',
                '$categoria'
            )";

            // Ejecutar la consulta
            try {
                $resultados = Table::query($query);
                $r = new Success(['success' => true, 'message' => 'Registro exitoso']);
                return $r->send();
            } catch (\Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Error en el registro: ' . $e->getMessage()]);
                return;
            }

        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Método de solicitud no permitido o imagen no proporcionada']);
            return;
        }
    }

    public function eliminarProducto() {
        // Leer datos del cuerpo de la solicitud
        $id = $_GET['id'] ?? '';

        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'ID del producto no proporcionado']);
            return;
        }

        // Ejecutar consulta para eliminar el producto
        $query = "DELETE FROM PRODUCTOS_SERVICIOS WHERE ID_PRODUCTO = '$id'";

        try {
            Table::query($query);
            $r = new Success(['success' => true, 'message' => 'Producto eliminado con éxito']);
            return $r->send();
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el producto: ' . $e->getMessage()]);
            return;
        }
    }
}
