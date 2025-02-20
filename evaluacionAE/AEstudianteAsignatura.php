<?php
require_once "../evaluacionAE/Conexion.php";
class AEstudianteAsignatura {
    public static function guardar($idEstudiante, $codigoAsignatura){
        $result = Conexion::conectar()->prepare("INSERT INTO estudiante_asignatura (idEstudiante, codigoAsignatura) VALUES (:idEstudiante, :codigoAsignatura)");
        $result->bindParam(":idEstudiante", $idEstudiante, PDO::PARAM_INT);
        $result->bindParam(":codigoAsignatura", $codigoAsignatura, PDO::PARAM_STR);
        return $result->execute();
    }
    public static function listar(){
        $stmt = Conexion::conectar()->prepare("SELECT idEstudiante, codigoAsignatura FROM estudiante_asignatura");
        $stmt->execute();

        $stmt->bindColumn("idEstudiante", $idEstudiante);
        $stmt->bindColumn("codigoAsignatura", $codigoAsignatura);

        $lista = array();
        while ($fila = $stmt->fetch(PDO::FETCH_BOUND)){
            $modelo = array();
            $modelo["idEstudiante"] = $idEstudiante;
            $modelo["codigoAsignatura"] = $codigoAsignatura;
            array_push($lista, $modelo);
        }

        return $lista;
    }

    public static function editar($idEstudiante, $codigoAsignatura, $id){
        $result = Conexion::conectar()->prepare("UPDATE estudiante_asignatura set idEstudiante = :idEstudiante, codigoAsignatura = :codigoAsignatura WHERE idEstudiante = :id AND codigoAsignatura = :codigo");
        $result->bindParam(":idEstudiante", $idEstudiante, PDO::PARAM_INT);
        $result->bindParam(":codigoAsignatura", $codigoAsignatura , PDO::PARAM_STR);
        return $result->execute();
    }

    public static function eliminar($idEstudiante){

         $result = Conexion::conectar()->prepare("DELETE FROM estudiante_asignatura WHERE idEstudiante = :id");
         $result->bindParam(":id", $idEstudiante , PDO::PARAM_INT);
         return $result->execute();
    }
    
    public function guardarSiExisteAsignatura($codigoAsignatura, $idEstudiante){
        // Primero, verifica si el codigoAsignatura existe en la tabla asignatura
        $stmt = Conexion::conectar()->prepare("SELECT codigoAsignatura FROM asignatura WHERE codigoAsignatura = :codigoAsignatura");
        $stmt->bindParam(":codigoAsignatura", $codigoAsignatura, PDO::PARAM_STR);
        $stmt->execute();
    
        // Si el codigoAsignatura existe en la tabla asignatura, entonces verifica la relación estudiante-asignatura
        if($stmt->fetch(PDO::FETCH_ASSOC)){
            // Verifica si la relación estudiante-asignatura ya existe en la tabla estudiante_asignatura
            $stmt3 = Conexion::conectar()->prepare("SELECT idEstudiante, codigoAsignatura FROM estudiante_asignatura WHERE idEstudiante = :idEstudiante AND codigoAsignatura = :codigoAsignatura");
            $stmt3->bindParam(":idEstudiante", $idEstudiante, PDO::PARAM_INT);
            $stmt3->bindParam(":codigoAsignatura", $codigoAsignatura, PDO::PARAM_STR);
            $stmt3->execute();
    
            if($stmt3->fetch(PDO::FETCH_ASSOC)){
                // Si la relación estudiante-asignatura ya existe, entonces retorna falso
                return false;
            }else{
                // Si la relación estudiante-asignatura no existe, entonces inserta la relación en la tabla estudiante_asignatura
                return $this->guardar($idEstudiante, $codigoAsignatura);
            }
        }else{
            // Si el codigoAsignatura no existe en la tabla asignatura, entonces retorna falso
            return false;
        }
    }
    
    public function obtenerAsignaturasEstudiante($idEstudiante){
        $stmt1 = Conexion::conectar()->prepare("
            SELECT asignatura.asignatura, asignatura.claveAsignatura, docente.nombre, docente.apellidos
            FROM asignatura
            JOIN docente ON asignatura.idDocente = docente.idDocente
            JOIN estudiante_asignatura ON asignatura.codigoAsignatura = estudiante_asignatura.codigoAsignatura
            WHERE estudiante_asignatura.idEstudiante = :idEstudiante
        ");
        $stmt1->bindParam(":idEstudiante", $idEstudiante, PDO::PARAM_INT);
        $stmt1->execute();
    
        $stmt1->bindColumn("asignatura", $asignatura);
        $stmt1->bindColumn("claveAsignatura", $claveAsignatura);
        $stmt1->bindColumn("nombre", $nombre);
        $stmt1->bindColumn("apellidos", $apellidos);
    
        $listaAsignaturas = array();
        while ($fila = $stmt1->fetch(PDO::FETCH_BOUND)){
            $modelo = array();
            $modelo["asignatura"] = $asignatura;
            $modelo["claveAsignatura"] = $claveAsignatura;
            $modelo["nombre"] = $nombre;
            $modelo["apellidos"] = $apellidos;
            array_push($listaAsignaturas, $modelo);
        }
        return $listaAsignaturas;
    }



}
?>
