<?php
// Importa la clase de conexión
require_once "../evaluacionAE/Conexion.php";

/**
 * Clase AAtributoE: maneja las operaciones relacionadas con los atributos de egreso en la base de datos.
 */
class AAtributoE {

    /**
     * Guarda un nuevo atributo de egreso en la base de datos.
     *
     * @param int $logro Logro del atributo de egreso.
     * @param int $meta Meta del atributo de egreso.
     * @param int $idAsignaturas ID de la asignatura asociada al atributo de egreso.
     * @param string $atributoE Descripción del atributo de egreso.
     * @return bool True si la operación fue exitosa, False en caso contrario.
     */
    public static function guardarAtributoE($logro, $meta, $idAsignaturas, $atributoE){
        $result = Conexion::conectar()->prepare("INSERT INTO atributoE (logro, meta, idAsignaturas, atributoE) VALUES (:logro, :meta, :idAsignaturas, :atributoE)");
        $result->bindParam(":logro", $logro, PDO::PARAM_INT);
        $result->bindParam(":meta", $meta, PDO::PARAM_INT);
        $result->bindParam(":idAsignaturas", $idAsignaturas, PDO::PARAM_INT);
        $result->bindParam(":atributoE", $atributoE, PDO::PARAM_STR);
        $result->execute();
    
        // Si se insertó al menos una fila, entonces la inserción fue exitosa
        return $result->rowCount() > 0;
    }

    /**
     * Lista todos los atributos de egreso en la base de datos.
     *
     * @return array Lista con todos los atributos de egreso.
     */
    public static function listar(){
        $stmt = Conexion::conectar()->prepare("SELECT idAtributoE, logro, meta, idAsignaturas FROM atributoE");
        $stmt->execute();

        $stmt->bindColumn("idAtributoE", $idAtributoE);
        $stmt->bindColumn("logro", $logro);
        $stmt->bindColumn("meta", $meta);
        $stmt->bindColumn("idAsignaturas", $idAsignaturas);

        $lista = array();
        while ($fila = $stmt->fetch(PDO::FETCH_BOUND)){
            $modelo = array();
            $modelo["idAtributoE"] = $idAtributoE;
            $modelo["logro"] = $logro;
            $modelo["meta"] = $meta;
            $modelo["idAsignaturas"] = $idAsignaturas;
            array_push($lista, $modelo);
        }

        return $lista;
    }

    /**
     * Edita un atributo de egreso en la base de datos.
     *
     * @param int $logro Nuevo logro del atributo de egreso.
     * @param int $meta Nueva meta del atributo de egreso.
     * @param int $idAsignaturas Nuevo ID de la asignatura asociada al atributo de egreso.
     * @param int $id ID del atributo de egreso a editar.
     * @return bool True si la operación fue exitosa, False en caso contrario.
     */
    public static function editar($logro, $meta, $idAsignaturas, $id){
        $result = Conexion::conectar()->prepare("UPDATE atributoE set logro = :logro, meta = :meta, idAsignaturas = :idAsignaturas WHERE idAtributoE = :id");
        $result->bindParam(":logro", $logro, PDO::PARAM_INT);
        $result->bindParam(":meta", $meta , PDO::PARAM_INT);
        $result->bindParam(":idAsignaturas", $idAsignaturas , PDO::PARAM_INT);
        $result->bindParam(":id", $idAtributoE, PDO::PARAM_INT);
        return $result->execute();
    }

    /**
     * Elimina un atributo de egreso de la base de datos.
     *
     * @param int $idAtributoE ID del atributo de egreso a eliminar.
     * @return bool True si la operación fue exitosa, False en caso contrario.
     */
    public static function eliminar($idAtributoE){
         $result = Conexion::conectar()->prepare("DELETE FROM atributoE WHERE idAtributoE = :id");
         $result->bindParam(":id", $idAtributoE , PDO::PARAM_INT);

         return $result->execute();
    }

    /**
     * Obtiene el ID de un atributo de egreso específico por su descripción y asignatura asociada.
     *
     * @param string $atributoE Descripción del atributo de egreso.
     * @param int $idAsignaturas ID de la asignatura asociada al atributo de egreso.
     * @return array Lista con el ID del atributo de egreso.
     */
    public static function obtenerIdAtributoE($atributoE, $idAsignaturas){
        $stmt = Conexion::conectar()->prepare("
            SELECT idAtributoE
            FROM atributoE
            WHERE atributoE = :atributoE AND idAsignaturas = :idAsignaturas
        ");
        $stmt->bindParam(':atributoE', $atributoE);
        $stmt->bindParam(':idAsignaturas', $idAsignaturas);
        $stmt->execute();
    
        $stmt->bindColumn("idAtributoE", $idAtributoE);
    
        $lista = array();
        while ($fila = $stmt->fetch(PDO::FETCH_BOUND)){
            $modelo = array();
            $modelo["idAtributoE"] = $idAtributoE;
            array_push($lista, $modelo);
        }
    
        return $lista;
    }

    /**
     * Lista todos los atributos de egreso asociados a una asignatura específica.
     *
     * @param int $idAsignatura ID de la asignatura.
     * @return array Lista con todos los atributos de egreso asociados a la asignatura.
     */
    public static function listarAtributosEgresoPorIdAsignatura($idAsignatura){
        $stmt = Conexion::conectar()->prepare("
            SELECT idAtributoE, atributoE, meta, logro
            FROM atributoE
            WHERE idAsignaturas = :idAsignatura
        ");
        $stmt->bindParam(':idAsignatura', $idAsignatura);
        $stmt->execute();
    
        $lista = array();
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)){
            array_push($lista, $fila);
        }
    
        return $lista;
    }
    
public static function contarAprobadosPorAtributo($idAtributoE){
    $stmt = Conexion::conectar()->prepare("
        SELECT COALESCE(SUM(calificacion.calificacion >= 30), 0) as AlumnosAprobados
        FROM estudiante
        JOIN calificacion ON estudiante.idEstudiante = calificacion.idEstudiante
        JOIN criteriosEval ON calificacion.idCriterio = criteriosEval.idCriterio
        WHERE criteriosEval.idAtributoE = :idAtributoE
        GROUP BY criteriosEval.idAtributoE
    ");
    $stmt->bindParam(':idAtributoE', $idAtributoE);
    $stmt->execute();

    $aprobados = $stmt->fetch(PDO::FETCH_ASSOC);
    return $aprobados ? $aprobados['AlumnosAprobados'] : 0;
}


    public static function obtenerNivelPorIdAtributoE($idAtributoE){
    $stmt = Conexion::conectar()->prepare("
        SELECT nivel
        FROM criteriosEval
        WHERE idAtributoE = :idAtributoE
    ");
    $stmt->bindParam(':idAtributoE', $idAtributoE);
    $stmt->execute();

    $nivel = $stmt->fetchColumn();

    return $nivel;
}

}
?>
