<?php
// Importa la clase de conexión
require_once "../evaluacionAE/Conexion.php";

/**
 * Clase ACriteriosEval: maneja las operaciones relacionadas con los criterios de evaluación en la base de datos.
 */
class ACriteriosEval {
    
    /**
     * Guarda un nuevo criterio de evaluación en la base de datos.
     *
     * @param string $indicadorEspecifico Indicador específico del criterio.
     * @param string $nivel Nivel del criterio.
     * @param int $idAtributoE ID del atributo específico asociado al criterio.
     * @param int $puntos Puntos asignados al criterio.
     * @return bool True si la operación fue exitosa, False en caso contrario.
     */
    public static function guardarCriteriosEval($indicadorEspecifico, $nivel, $idAtributoE, $puntos){
        $result = Conexion::conectar()->prepare("INSERT INTO criteriosEval (indicadorEspecifico, nivel, idAtributoE, puntos) VALUES (:indicadorEspecifico, :nivel, :idAtributoE, :puntos)");
        $result->bindParam(":indicadorEspecifico", $indicadorEspecifico, PDO::PARAM_STR);
        $result->bindParam(":nivel", $nivel, PDO::PARAM_STR);
        $result->bindParam(":idAtributoE", $idAtributoE, PDO::PARAM_INT);
        $result->bindParam(":puntos", $puntos, PDO::PARAM_INT);
        $result->execute();

        // Si se insertó al menos una fila, entonces la inserción fue exitosa
        return $result->rowCount() > 0;
    }
    
    /**
     * Obtiene el ID de un criterio de evaluación por su indicador específico.
     *
     * @param string $indicadorEspecifico Indicador específico del criterio.
     * @return array Lista con el ID del criterio.
     */
    public static function obtenerIdCriterioPorIndicador($indicadorEspecifico){
        $stmt = Conexion::conectar()->prepare("
            SELECT idCriterio
            FROM criteriosEval
            WHERE indicadorEspecifico = :indicadorEspecifico
        ");
        $stmt->bindParam(':indicadorEspecifico', $indicadorEspecifico);
        $stmt->execute();
    
        $stmt->bindColumn("idCriterio", $idCriterio);
    
        $lista = array();
        while ($fila = $stmt->fetch(PDO::FETCH_BOUND)){
            $modelo = array();
            $modelo["idCriterio"] = $idCriterio;
            array_push($lista, $modelo);
        }
    
        return $lista;
    }
    
    /**
     * Obtiene todos los criterios de evaluación asociados a un atributo específico.
     *
     * @param int $idAtributoE ID del atributo específico.
     * @return array Lista con los criterios de evaluación.
     */
    public static function obtenerCriteriosPorAtributo($idAtributoE){
        $stmt = Conexion::conectar()->prepare("
            SELECT *
            FROM criteriosEval
            WHERE idAtributoE = :idAtributoE
        ");
        $stmt->bindParam(':idAtributoE', $idAtributoE);
        $stmt->execute();
    
        $stmt->bindColumn("idCriterio", $idCriterio);
        $stmt->bindColumn("indicadorEspecifico", $indicadorEspecifico);
        $stmt->bindColumn("nivel", $nivel);
        $stmt->bindColumn("puntos", $puntos);
    
        $lista = array();
        while ($fila = $stmt->fetch(PDO::FETCH_BOUND)){
            $modelo = array();
            $modelo["idCriterio"] = $idCriterio;
            $modelo["indicadorEspecifico"] = $indicadorEspecifico;
            $modelo["nivel"] = $nivel;
            $modelo["puntos"] = $puntos;
            array_push($lista, $modelo);
        }
    
        return $lista;
    }

    /**
     * Lista todos los criterios de evaluación en la base de datos.
     *
     * @return array Lista con todos los criterios de evaluación.
     */
    public static function listar(){
        $stmt = Conexion::conectar()->prepare("SELECT idCriterio, indicadorEspecifico, idAtributoE, puntos FROM criteriosEval");
        $stmt->execute();

        $stmt->bindColumn("idCriterio", $idCriterio);
        $stmt->bindColumn("indicadorEspecifico", $indicadorEspecifico);
        $stmt->bindColumn("idAtributoE", $idAtributoE);
        $stmt->bindColumn("puntos", $puntos);

        $lista = array();
        while ($fila = $stmt->fetch(PDO::FETCH_BOUND)){
            $modelo = array();
            $modelo["idCriterio"] = $idCriterio;
            $modelo["indicadorEspecifico"] = $indicadorEspecifico;
            $modelo["idAtributoE"] = $idAtributoE;
            $modelo["puntos"] = $puntos;
            array_push($lista, $modelo);
        }

        return $lista;
    }

    /**
     * Edita un criterio de evaluación en la base de datos.
     *
     * @param string $indicadorEspecifico Nuevo indicador específico del criterio.
     * @param int $idAtributoE Nuevo ID del atributo específico asociado al criterio.
     * @param int $puntos Nuevos puntos asignados al criterio.
     * @param int $id ID del criterio a editar.
     * @return bool True si la operación fue exitosa, False en caso contrario.
     */
    public static function editar($indicadorEspecifico, $idAtributoE, $puntos, $id){
        $result = Conexion::conectar()->prepare("UPDATE criteriosEval set indicadorEspecifico = :indicadorEspecifico, idAtributoE = :idAtributoE, puntos = :puntos WHERE idCriterio = :id");
        $result->bindParam(":indicadorEspecifico", $indicadorEspecifico, PDO::PARAM_STR);
        $result->bindParam(":idAtributoE", $idAtributoE , PDO::PARAM_INT);
        $result->bindParam(":puntos", $puntos , PDO::PARAM_INT);
        $result->bindParam(":id", $id , PDO::PARAM_INT); // Asegúrate de agregar esta línea
        return $result->execute();
    }
    
    /**
     * Elimina un criterio de evaluación de la base de datos.
     *
     * @param int $idCriterio ID del criterio a eliminar.
     * @return bool True si la operación fue exitosa, False en caso contrario.
     */
    public static function eliminar($idCriterio){
         $result = Conexion::conectar()->prepare("DELETE FROM criteriosEval WHERE idCriterio = :id");
         $result->bindParam(":id", $idCriterio , PDO::PARAM_INT);
         return $result->execute();
    }
}
?>
