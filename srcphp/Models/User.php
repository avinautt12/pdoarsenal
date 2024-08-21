<?php

namespace proyecto\Models;

use PDO;
use proyecto\Auth;
use proyecto\Response\Failure;
use proyecto\Response\Response;
use proyecto\Response\Success;
use function json_encode;

class User extends Models
{
    public $user = "";
    public $contrasena = "";
    public $nombre = "";
    public $edad = "";
    public $correo = "";
    public $apellido = "";

    public $id_usuario = "";

    /**
     * @var array
     */
    protected $filleable = [
        "nombre",
        "edad",
        "correo",
        "apellido",
        "contrasena",
        "user",
        "id_usuario",
    ];

    protected $table = "USUARIOS";
    protected $primaryKey = "ID_USUARIO";

    // Método para autenticación de usuarios
    public static function auth($identificador, $contrasena)
    {
        $class = get_called_class();
        $c = new $class();
    
        // Define la clave de encriptación utilizada para AES
        $clave_encriptacion = 'administrador'; // Asegúrate de que coincida con la clave utilizada en el procedimiento almacenado
    
        try {
            if (filter_var($identificador, FILTER_VALIDATE_EMAIL)) {
                // Es un correo electrónico
                $stmt = self::$pdo->prepare("
                    SELECT
                        p.NOMBRE AS nombre,               -- Nombre del usuario
                        p.APELLIDO AS apellido,           -- Apellido del usuario
                        p.CORREO AS correo,               -- Correo electrónico del usuario
                        u.ID_USUARIO AS id_usuario,       -- ID del usuario
                        CAST(AES_DECRYPT(u.CONTRASEÑA, :clave_encriptacion) AS CHAR) AS contrasena
                    FROM {$c->table} u
                    INNER JOIN PERSONA p ON u.ID_USUARIO = p.ID_USUARIO
                    WHERE p.CORREO = :identificador
                ");
            } else {
                // Es un ID de socio
                $stmt = self::$pdo->prepare("
                    SELECT 
                        p.NOMBRE AS nombre,               -- Nombre del usuario
                        p.APELLIDO AS apellido,           -- Apellido del usuario
                        p.CORREO AS correo,               -- Correo electrónico del usuario
                        u.ID_USUARIO AS id_usuario,       -- ID del usuario
                        socios.ID_SOCIO AS id_socio,      -- ID del socio
                        CAST(AES_DECRYPT(u.CONTRASEÑA, :clave_encriptacion) AS CHAR) AS contrasena
                    FROM socios
                    INNER JOIN clientes ON socios.ID_CLIENTE = clientes.ID_CLIENTES
                    INNER JOIN persona p ON clientes.ID_PERSONA = p.ID_PERSONA
                    INNER JOIN usuarios u ON p.ID_USUARIO = u.ID_USUARIO
                    WHERE socios.ID_SOCIO = :identificador
                ");
            }
    
            $stmt->bindParam(':identificador', $identificador);
            $stmt->bindParam(':clave_encriptacion', $clave_encriptacion);
            $stmt->execute();
    
            $resultado = $stmt->fetch(PDO::FETCH_OBJ);
    
            if ($resultado && $resultado->contrasena === $contrasena) {
                // Determina el tipo de usuario
                $tipoUsuario = $resultado->id_socio ? 'socio' : 'cliente';
    
                // Usuario encontrado, retornar éxito con datos completos
                return [
                    'success' => true,
                    'usuario' => [
                        'nombre' => $resultado->nombre,
                        'apellido' => $resultado->apellido,
                        'correo' => $resultado->correo,
                        'id_usuario' => $resultado->id_usuario,
                        'id_socio' => $resultado->id_socio ?? null,
                        'tipoUsuario' => $tipoUsuario // Añadido
                    ],
                    '_token' => Auth::generateToken([$resultado->id_usuario ?? $resultado->id_socio])
                ];
            }
    
            // No se encontraron resultados, credenciales inválidas
            return [
                'success' => false,
                'msg' => "Credenciales inválidas."
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'msg' => $e->getMessage()
            ];
        }
    }
    


    


    public function find_name($name)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM $this->table WHERE nombre = :name");
        $stmt->bindParam(":name", $name);
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_OBJ);
        if ($resultados == null) {
            return json_encode([]);
        }
        return json_encode($resultados[0]);
    }

    public function reportecitas()
    {
        $JSONData = file_get_contents("php://input");
        $dataObject = json_decode($JSONData);

        $name = $dataObject->name;
        $d = Table::query("SELECT * FROM $this->table WHERE nombre = '".$name."'");
        $r = new Success($d);

        return $r->Send();
    }
}