<?php
// Importa la clase de conexión
require_once "../evaluacionAE/Conexion.php";

/**
 * Clase AAsignatura: maneja las operaciones relacionadas con las asignaturas en la base de datos.
 */

class AAsignatura {

    /**
     * Lista todas las asignaturas en la base de datos.
     *
     * @return array Lista con todas las asignaturas.
     */
    public static function listar(){
        $stmt = Conexion::conectar()->prepare("SELECT idAsignaturas, asignatura, claveAsignatura, claveGrupo, codigoAsignatura, idDocente FROM asignatura");
        $stmt->execute();

        $stmt->bindColumn("idAsignaturas", $idAsignaturas);
        $stmt->bindColumn("asignatura", $asignatura);
        $stmt->bindColumn("claveAsignatura", $claveAsignatura);
        $stmt->bindColumn("claveGrupo", $claveGrupo);
        $stmt->bindColumn("codigoAsignatura", $codigoAsignatura);
        $stmt->bindColumn("idDocente", $idDocente);

        $lista = array();
        while ($fila = $stmt->fetch(PDO::FETCH_BOUND)){
            $modelo = array();
            $modelo["idAsignaturas"] = $idAsignaturas;
            $modelo["asignatura"] = $asignatura;
            $modelo["claveAsignatura"] = $claveAsignatura;
            $modelo["claveGrupo"] = $claveGrupo;
            $modelo["codigoAsignatura"] = $codigoAsignatura;
            $modelo["idDocente"] = $idDocente;
            array_push($lista, $modelo);
        }

        return $lista;
    }

    /**
     * Lista todas las asignaturas asociadas a un docente específico.
     *
     * @param int $idDocente ID del docente.
     * @return array Lista con todas las asignaturas asociadas al docente.
     */
    public static function listarAsignatura(){
        $stmt = Conexion::conectar()->prepare("SELECT * FROM asignatura");
        $stmt->execute();
    
        $stmt->bindColumn("idAsignaturas", $idAsignaturas);
        $stmt->bindColumn("asignatura", $asignatura);
        $stmt->bindColumn("claveAsignatura", $claveAsignatura);
        $stmt->bindColumn("claveGrupo", $claveGrupo);
        $stmt->bindColumn("codigoAsignatura", $codigoAsignatura);
        $stmt->bindColumn("idDocente", $idDocente);
    
        $lista = array();
        while ($fila = $stmt->fetch(PDO::FETCH_BOUND)){
            $modelo = array();
            $modelo["idAsignaturas"] = $idAsignaturas;
            $modelo["asignatura"] = $asignatura;
            $modelo["claveAsignatura"] = $claveAsignatura;
            $modelo["claveGrupo"] = $claveGrupo;
            $modelo["codigoAsignatura"] = $codigoAsignatura;
            $modelo["idDocente"] = $idDocente;
            array_push($lista, $modelo);
        }
    
        return $lista;
    }
public static function contarEstudiantesPorAsignatura($codigoAsignatura){
    $stmt = Conexion::conectar()->prepare("
        SELECT COALESCE(COUNT(*), 0) as TotalEstudiantes
        FROM estudiante_asignatura
        WHERE codigoAsignatura = :codigoAsignatura
    ");
    $stmt->bindParam(':codigoAsignatura', $codigoAsignatura);
    $stmt->execute();

    $total = $stmt->fetch(PDO::FETCH_ASSOC);
    return $total ? $total['TotalEstudiantes'] : 0;
}



    
    public static function listarPorIdDocente($idDocente){
        $stmt = Conexion::conectar()->prepare("SELECT * FROM asignatura WHERE idDocente = :idDocente");
        $stmt->bindParam(':idDocente', $idDocente);
        $stmt->execute();
    
        $stmt->bindColumn("idAsignaturas", $idAsignaturas);
        $stmt->bindColumn("asignatura", $asignatura);
        $stmt->bindColumn("claveAsignatura", $claveAsignatura);
        $stmt->bindColumn("claveGrupo", $claveGrupo);
        $stmt->bindColumn("codigoAsignatura", $codigoAsignatura);
        $stmt->bindColumn("idDocente", $idDocente);
    
        $lista = array();
        while ($fila = $stmt->fetch(PDO::FETCH_BOUND)){
            $modelo = array();
            $modelo["idAsignaturas"] = $idAsignaturas;
            $modelo["asignatura"] = $asignatura;
            $modelo["claveAsignatura"] = $claveAsignatura;
            $modelo["claveGrupo"] = $claveGrupo;
            $modelo["codigoAsignatura"] = $codigoAsignatura;
            $modelo["idDocente"] = $idDocente;
            array_push($lista, $modelo);
        }
    
        return $lista;
    }
    

    /**
     * Edita una asignatura en la base de datos.
     *
     * @param string $asignatura Nuevo nombre de la asignatura.
     * @param string $claveAsignatura Nueva clave de la asignatura.
     * @param string $claveGrupo Nueva clave del grupo.
     * @param string $codigoAsignatura Nueva clave de la asignatura.
     * @param int $idDocente Nuevo ID del docente asociado a la asignatura.
     * @param int $id ID de la asignatura a editar.
     * @return bool True si la operación fue exitosa, False en caso contrario.
     */
    public static function editar($asignatura, $claveAsignatura, $claveGrupo, $codigoAsignatura, $idDocente, $id){
        $result = Conexion::conectar()->prepare("UPDATE asignatura set asignatura = :asignatura, claveAsignatura = :claveAsignatura, claveGrupo = :claveGrupo, codigoAsignatura = :codigoAsignatura, idDocente = :idDocente WHERE idAsignaturas = :id");
        $result->bindParam(":asignatura", $asignatura, PDO::PARAM_STR);
        $result->bindParam(":claveAsignatura", $claveAsignatura, PDO::PARAM_STR);
        $result->bindParam(":claveGrupo", $claveGrupo, PDO::PARAM_STR);
        $result->bindParam(":codigoAsignatura", $codigoAsignatura, PDO::PARAM_STR);
        $result->bindParam(":idDocente", $idDocente , PDO::PARAM_INT);
        $result->bindParam(":id", $idAsignaturas, PDO::PARAM_INT);
        return $result->execute();
    }

    /**
     * Elimina una asignatura de la base de datos.
     *
     * @param string $codigoAsignatura Clave de la asignatura a eliminar.
     * @return bool True si la operación fue exitosa, False en caso contrario.
     */
    public static function eliminar($codigoAsignatura){
         $result = Conexion::conectar()->prepare("DELETE FROM asignatura WHERE codigoAsignatura = :codigoAsig");
         $result->bindParam(":codigoAsig", $codigoAsignatura , PDO::PARAM_STR);
         return $result->execute();
    }

    /**
     * Guarda una nueva asignatura en la base de datos.
     *
     * @param string $asignatura Nombre de la nueva asignatura.
     * @param string $claveAsignatura Clave de la nueva asignatura.
     * @param string $claveGrupo Clave del nuevo grupo.
     * @param string $codigoAsignatura Clave de la nueva asignatura.
     * @param int $idDocente ID del nuevo docente asociado a la asignatura.
     * @return bool True si la operación fue exitosa, False en caso contrario.
     */
    public static function guardar($asignatura, $claveAsignatura, $claveGrupo, $codigoAsignatura, $idDocente){
        $result = Conexion::conectar()->prepare("INSERT INTO asignatura (asignatura, claveAsignatura, claveGrupo, codigoAsignatura, idDocente) VALUES (:asignatura, :claveAsignatura, :claveGrupo, :codigoAsignatura, :idDocente)");
        $result->bindParam(":asignatura", $asignatura, PDO::PARAM_STR);
        $result->bindParam(":claveAsignatura", $claveAsignatura, PDO::PARAM_STR);
        $result->bindParam(":claveGrupo", $claveGrupo, PDO::PARAM_STR);
        $result->bindParam(":codigoAsignatura", $codigoAsignatura, PDO::PARAM_STR);
        $result->bindParam(":idDocente", $idDocente, PDO::PARAM_INT);
        $result->execute();
    
        // Si se insertó al menos una fila, entonces la inserción fue exitosa
        return $result->rowCount() > 0;
    }
    
    /**
     * Obtiene el ID de una asignatura a partir de su clave.
     *
     * @param string $codigoAsignatura Clave de la asignatura.
     * @return array Lista con el ID de la asignatura.
     */
    public static function obtenerIdAsignatura($codigoAsignatura){
        $stmt = Conexion::conectar()->prepare("
            SELECT idAsignaturas
            FROM asignatura
            WHERE codigoAsignatura = :codigoAsignatura
        ");
        $stmt->bindParam(':codigoAsignatura', $codigoAsignatura);
        $stmt->execute();
    
        $stmt->bindColumn("idAsignaturas", $idAsignaturas);
    
        $lista = array();
        while ($fila = $stmt->fetch(PDO::FETCH_BOUND)){
            $modelo = array();
            $modelo["idAsignaturas"] = $idAsignaturas;
            array_push($lista, $modelo);
        }
    
        return $lista;
    }
    
    public static function obtenerNombreAsignatura($idEstudiante, $codigoAsignatura){
        $stmt = Conexion::conectar()->prepare("
            SELECT asignatura
            FROM estudiante_asignatura
            WHERE idEstudiante = :idEstudiante AND codigoAsignatura = :codigoAsignatura
        ");
        $stmt->bindParam(':idEstudiante', $idEstudiante);
        $stmt->bindParam(':codigoAsignatura', $codigoAsignatura);
        $stmt->execute();
    
        if($stmt->rowCount() > 0){
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);
            return $fila['asignatura'];
        }else{
            return false;
        }
    }
}

?>