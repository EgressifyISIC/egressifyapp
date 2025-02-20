<?php

/**
 * Clase Conexion: maneja la conexión a la base de datos.
 */
class Conexion
{
    /**
     * Función para establecer una conexión a la base de datos.
     *
     * @return PDO La instancia de PDO que representa la conexión a la base de datos.
     */
    public static function conectar()
    {
        // Configuración de la conexión a la base de datos
        $localhost = "localhost"; // Dirección del servidor de la base de datos
        $database = "id21547340_atributesegre"; // Nombre de la base de datos
        $user = "id21547340_admin"; // Usuario de la base de datos
        $password = "AtributesAssesment:1T503H"; // Contraseña de la base de datos

        // Intentar establecer la conexión usando PDO
        try {
            // Crear una nueva instancia de PDO
            $link = new PDO("mysql:host=$localhost;dbname=$database;charset=utf8mb4", $user, $password);

            // Configurar el modo de error para excepciones
            $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Devolver la instancia de PDO
            return $link;
        } catch (PDOException $e) {
            // En caso de error, imprimir el mensaje de error y terminar la ejecución
            die("Error de conexión: " . $e->getMessage());
        }
    }
}

?>
