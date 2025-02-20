<?php
// Importa la clase de conexión
require_once "../evaluacionAE/Conexion.php";

/**
 * Clase ADocente: maneja las operaciones relacionadas con los docentes en la base de datos.
 */
class ADocente {

    /**
     * Guarda un nuevo docente en la base de datos.
     *
     * @param string $nombre Nombre del docente.
     * @param string $apellidos Apellidos del docente.
     * @param string $correo Correo electrónico del docente.
     * @param string $matricula Matrícula del docente.
     * @param string $password Contraseña del docente.
     * @return bool True si la operación fue exitosa, False en caso contrario.
     */
    public static function guardar($nombre, $apellidos, $correo, $matricula, $password){
        $result = Conexion::conectar()->prepare("INSERT INTO docente (nombre, apellidos, correo, matricula, password) VALUES (:nombre, :apellidos, :correo, :matricula, :password)");
        $result->bindParam(":nombre", $nombre, PDO::PARAM_STR);
        $result->bindParam(":apellidos", $apellidos, PDO::PARAM_STR);
        $result->bindParam(":correo", $correo, PDO::PARAM_STR);
        $result->bindParam(":matricula", $matricula, PDO::PARAM_STR);
        $result->bindParam(":password", $password, PDO::PARAM_STR);
        return $result->execute();
    }
    public static function obtenerDocentePorIdAsignatura($idAsignatura){
    $stmt = Conexion::conectar()->prepare("SELECT docente.nombre, docente.apellidos FROM asignatura INNER JOIN docente ON asignatura.idDocente = docente.idDocente WHERE asignatura.idAsignaturas = :idAsignatura");
    $stmt->bindParam(':idAsignatura', $idAsignatura);
    $stmt->execute();

    $stmt->bindColumn("nombre", $nombre);
    $stmt->bindColumn("apellidos", $apellidos);

    $docente = array();
    while ($fila = $stmt->fetch(PDO::FETCH_BOUND)){
        $modelo = array();
        $modelo["nombre"] = $nombre;
        $modelo["apellidos"] = $apellidos;
        array_push($docente, $modelo);
    }

    return $docente;
}


    /**
     * Actualiza el nombre de un docente en la base de datos.
     *
     * @param int $idDocente ID del docente.
     * @param string $nombreNuevo Nuevo nombre del docente.
     * @param string $apellidosNuevo Nuevos apellidos del docente.
     * @return bool True si la operación fue exitosa, False en caso contrario.
     */
    public static function actualizarNombre($idDocente, $nombreNuevo, $apellidosNuevo){
        $result = Conexion::conectar()->prepare("UPDATE docente SET nombre = :nombreNuevo, apellidos = :apellidosNuevo WHERE idDocente = :idDocente");
        $result->bindParam(":nombreNuevo", $nombreNuevo, PDO::PARAM_STR);
        $result->bindParam(":apellidosNuevo", $apellidosNuevo, PDO::PARAM_STR);
        $result->bindParam(":idDocente", $idDocente, PDO::PARAM_INT);
        return $result->execute();
    }

    /**
     * Lista todos los docentes en la base de datos.
     *
     * @return array Lista de docentes.
     */
    public static function listar(){
        $stmt = Conexion::conectar()->prepare("SELECT idDocente, nombre, apellidos, correo, matricula, password FROM docente");
        $stmt->execute();
        $stmt->bindColumn("idDocente", $idDocente);
        $stmt->bindColumn("nombre", $nombre);
        $stmt->bindColumn("apellidos", $apellidos);
        $stmt->bindColumn("correo", $correo);
        $stmt->bindColumn("matricula", $matricula);
        $stmt->bindColumn("password", $password);

        $lista = array();
        while ($fila = $stmt->fetch(PDO::FETCH_BOUND)){
            $modelo = array();
            $modelo["idDocente"] = $idDocente;
            $modelo["nombre"] = $nombre;
            $modelo["apellidos"] = $apellidos;
            $modelo["correo"] = $correo;
            $modelo["matricula"] = $matricula;
            $modelo["password"] = $password;

            array_push($lista, $modelo);
        }

        return $lista;
    }

    /**
     * Edita la información de un docente en la base de datos.
     *
     * @param string $nombre Nuevo nombre del docente.
     * @param string $apellidos Nuevos apellidos del docente.
     * @param string $correo Nuevo correo electrónico del docente.
     * @param string $matricula Nueva matrícula del docente.
     * @param string $password Nueva contraseña del docente.
     * @param int $id ID del docente.
     * @return bool True si la operación fue exitosa, False en caso contrario.
     */
    public static function editar($nombre, $apellidos, $correo, $matricula, $password, $id){
        $result = Conexion::conectar()->prepare("UPDATE docente set nombre = :nombre, apellidos = :apellidos, correo = :correo, matricula = :matricula, password = :password WHERE idDocente = :id");
        $result->bindParam(":nombre", $nombre, PDO::PARAM_STR);
        $result->bindParam(":apellidos", $apellidos, PDO::PARAM_STR);
        $result->bindParam(":correo", $correo, PDO::PARAM_STR);
        $result->bindParam(":matricula", $matricula, PDO::PARAM_INT);
        $result->bindParam(":password", $password, PDO::PARAM_STR);
        $result->bindParam(":id", $idDocente, PDO::PARAM_INT);

        return $result->execute();
    }

    /**
     * Elimina un docente de la base de datos.
     *
     * @param int $id ID del docente.
     * @return bool True si la operación fue exitosa, False en caso contrario.
     */
    public static function eliminar($id){
        $result = Conexion::conectar()->prepare("DELETE FROM docente WHERE idDocente = :id");
        $result->bindParam(":id", $id , PDO::PARAM_INT);
        return $result->execute();
    }
     /**
     * Busca la contraseña de un docente en la base de datos.
     *
     * @param string $correo Correo electrónico del docente.
     * @param string $contrasenia Contraseña del docente.
     * @return array|null Arreglo con el nombre y la contraseña del docente si existe, o null si no existe.
     */
        public static function buscarContrasenia($correo, $contrasenia){
            $result = Conexion::conectar()->prepare("SELECT nombre, password FROM docente WHERE correo = :correo AND password = :contrasenia");
            $result->bindParam(":correo", $correo, PDO::PARAM_STR);
            $result->bindParam(":contrasenia", $contrasenia, PDO::PARAM_STR);
            $result->execute();
        
            if ($result->rowCount() > 0){
                $datos = $result->fetch(PDO::FETCH_ASSOC);
                return array($datos["nombre"], $datos["password"]);
            } else {
                return null;
            }
        }
        /**
     * Obtiene el ID de un docente a partir de su correo electrónico.
     *
     * @param string $correo Correo electrónico del docente.
     * @return int El ID del docente si existe, o -1 si no existe.
     */
        public static function obtenerIdDocente($correo){
            $result = Conexion::conectar()->prepare("SELECT idDocente FROM docente WHERE correo = :correo");
            $result->bindParam(":correo", $correo, PDO::PARAM_STR);
            $result->execute();
        
            if ($result->rowCount() > 0){
                $datos = $result->fetch(PDO::FETCH_ASSOC);
                return $datos["idDocente"];
            } else {
                return -1;
            }
        }
        
      /**
     * Actualiza el correo electrónico de un docente en la base de datos.
     *
     * @param string $correoAntiguo Correo electrónico antiguo del docente.
     * @param string $correoNuevo Nuevo correo electrónico del docente.
     * @return bool True si la operación fue exitosa, False en caso contrario.
     */
    public static function actualizarCorreo($correoAntiguo, $correoNuevo){
        $result = Conexion::conectar()->prepare("UPDATE docente SET correo = :correoNuevo WHERE correo = :correoAntiguo");
        $result->bindParam(":correoNuevo", $correoNuevo, PDO::PARAM_STR);
        $result->bindParam(":correoAntiguo", $correoAntiguo, PDO::PARAM_STR);
        $result->execute();

        // Si se actualizó al menos una fila, entonces la actualización fue exitosa
        return $result->rowCount() > 0;
    }

    /**
     * Actualiza la contraseña de un docente en la base de datos.
     *
     * @param int $idDocente ID del docente.
     * @param string $passwordAntiguo Contraseña antigua del docente.
     * @param string $passwordNuevo Nueva contraseña del docente.
     * @return bool True si la operación fue exitosa, False en caso contrario.
     */
    public static function actualizarPassword($idDocente, $passwordAntiguo, $passwordNuevo){
        $result = Conexion::conectar()->prepare("UPDATE docente SET password = :passwordNuevo WHERE idDocente = :idDocente AND password = :passwordAntiguo");
        $result->bindParam(":passwordNuevo", $passwordNuevo, PDO::PARAM_STR);
        $result->bindParam(":idDocente", $idDocente, PDO::PARAM_INT);
        $result->bindParam(":passwordAntiguo", $passwordAntiguo, PDO::PARAM_STR);
        $result->execute();

        // Si se actualizó al menos una fila, entonces la actualización fue exitosa
        return $result->rowCount() > 0;
    }

    /**
     * Actualiza la contraseña de un docente en la base de datos sin verificar la contraseña antigua.
     *
     * @param string $correo Correo electrónico del docente.
     * @param string $passwordNuevo Nueva contraseña del docente.
     * @return bool True si la operación fue exitosa, False en caso contrario.
     */
    public static function actualizarPasswordSinVerificar($correo, $passwordNuevo){
        $result = Conexion::conectar()->prepare("UPDATE docente SET password = :passwordNuevo WHERE correo = :correo");
        $result->bindParam(":correo", $correo, PDO::PARAM_STR);
        $result->bindParam(":passwordNuevo", $passwordNuevo, PDO::PARAM_STR);
        $result->execute();

        // Si se actualizó al menos una fila, entonces la actualización fue exitosa
        return $result->rowCount() > 0;
    }

    /**
     * Verifica si un correo electrónico existe en la base de datos de docentes.
     *
     * @param string $correo Correo electrónico a verificar.
     * @return bool True si el correo existe, False en caso contrario.
     */
    public static function verificarCorreo($correo){
        $result = Conexion::conectar()->prepare("SELECT * FROM docente WHERE correo = :correo");
        $result->bindParam(":correo", $correo, PDO::PARAM_STR);
        $result->execute();

        // Si se encontró al menos una fila, entonces el correo existe en la base de datos
        return $result->rowCount() > 0;
    }


    }

?>