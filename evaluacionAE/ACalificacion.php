<?php
// Importa la clase de conexión
require_once "../evaluacionAE/Conexion.php";

/**
 * Clase ACalificacion: maneja las operaciones relacionadas con las calificaciones en la base de datos.
 */
class ACalificacion {
    
    /**
     * Guarda una nueva calificación en la base de datos.
     *
     * @param int $idEstudiante ID del estudiante asociado a la calificación.
     * @param int $idCriterio ID del criterio asociado a la calificación.
     * @param int $calificacion Calificación asignada.
     * @return bool True si la operación fue exitosa, False en caso contrario.
     */
    public static function guardar($idEstudiante, $idCriterio, $calificacion){
        $result = Conexion::conectar()->prepare("INSERT INTO calificacion (idEstudiante, idCriterio, calificacion) VALUES (:idEstudiante, :idCriterio, :calificacion)");
        $result->bindParam(":idEstudiante", $idEstudiante, PDO::PARAM_INT);
        $result->bindParam(":idCriterio", $idCriterio, PDO::PARAM_INT);
        $result->bindParam(":calificacion", $calificacion, PDO::PARAM_INT);
        return $result->execute();
    }

    /**
     * Lista todas las calificaciones en la base de datos.
     *
     * @return array Lista con todas las calificaciones.
     */
    public static function listar(){
        $stmt = Conexion::conectar()->prepare("SELECT idCalificacion, idEstudiante, idCriterio, calificacion FROM calificacion");
        $stmt->execute();
        $stmt->bindColumn("idCalificacion", $idCalificacion);
        $stmt->bindColumn("idEstudiante", $idEstudiante);
        $stmt->bindColumn("idCriterio", $idCriterio);
        $stmt->bindColumn("calificacion", $calificacion);

        $lista = array();
        while ($fila = $stmt->fetch(PDO::FETCH_BOUND)){
            $modelo = array();
            $modelo["idCalificacion"] = $idCalificacion;
            $modelo["idEstudiante"] = $idEstudiante;
            $modelo["idCriterio"] = $idCriterio;
            $modelo["calificacion"] = $calificacion;
            array_push($lista, $modelo);
        }

        return $lista;
    }

    /**
     * Edita una calificación en la base de datos.
     *
     * @param int $idEstudiante Nuevo ID del estudiante asociado a la calificación.
     * @param int $idCriterio Nuevo ID del criterio asociado a la calificación.
     * @param int $calificacion Nueva calificación asignada.
     * @param int $id ID de la calificación a editar.
     * @return bool True si la operación fue exitosa, False en caso contrario.
     */
    public static function editar($idEstudiante, $idCriterio, $calificacion, $id){
        $result = Conexion::conectar()->prepare("UPDATE calificacion set idEstudiante = :idEstudiante, idCriterio = :idCriterio, calificacion = :calificacion WHERE idCalificacion = :id");
        $result->bindParam(":idEstudiante", $idEstudiante, PDO::PARAM_INT);
        $result->bindParam(":idCriterio", $idCriterio , PDO::PARAM_INT);
        $result->bindParam(":calificacion", $calificacion , PDO::PARAM_INT);
        $result->bindParam(":id", $id , PDO::PARAM_INT);
        return $result->execute();
    }

    /**
     * Elimina una calificación de la base de datos.
     *
     * @param int $idCalificacion ID de la calificación a eliminar.
     * @return bool True si la operación fue exitosa, False en caso contrario.
     */
    public static function eliminar($idCalificacion){
         $result = Conexion::conectar()->prepare("DELETE FROM calificacion WHERE idCalificacion = :id");
         $result->bindParam(":id", $idCalificacion , PDO::PARAM_INT);
         return $result->execute();
    }
    
    /**
     * Guarda una nueva calificación en la base de datos.
     *
     * @param int $idEstudiante ID del estudiante asociado a la calificación.
     * @param int $idCriterio ID del criterio asociado a la calificación.
     * @param int $calificacion Calificación asignada.
     * @return bool True si la operación fue exitosa, False en caso contrario.
     */
    public static function guardarCalificacion($idEstudiante, $idCriterio, $calificacion){
        $result = Conexion::conectar()->prepare("INSERT INTO calificacion (idEstudiante, idCriterio, calificacion) VALUES (:idEstudiante, :idCriterio, :calificacion)");
        $result->bindParam(":idEstudiante", $idEstudiante, PDO::PARAM_INT);
        $result->bindParam(":idCriterio", $idCriterio, PDO::PARAM_INT);
        $result->bindParam(":calificacion", $calificacion, PDO::PARAM_INT);
        $result->execute();
    
        // Si se insertó al menos una fila, entonces la inserción fue exitosa
        return $result->rowCount() > 0;
    }
}
?>
