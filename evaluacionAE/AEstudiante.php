<?php
require_once "../evaluacionAE/Conexion.php";
class AEstudiante {
    public static function guardar($nombre, $apellido, $correo, $matricula, $password){
        $result = Conexion::conectar()->prepare("INSERT INTO estudiante (nombre, apellido, correo, matricula, password) VALUES (:nombre, :apellido, :correo, :matricula, :password)");
        $result->bindParam(":nombre", $nombre, PDO::PARAM_STR);
        $result->bindParam(":apellido", $apellido, PDO::PARAM_STR);
        $result->bindParam(":correo", $correo, PDO::PARAM_STR);
        $result->bindParam(":matricula", $matricula, PDO::PARAM_STR);
        $result->bindParam(":password", $password, PDO::PARAM_STR);
        return $result->execute();
    }
    
    public static function actualizarNombreEstudiante($idEstudiante, $nombreNuevo, $apellidosNuevo){
            $result = Conexion::conectar()->prepare("UPDATE estudiante SET nombre = :nombreNuevo, apellido = :apellidosNuevo WHERE idEstudiante = :idEstudiante");
            $result->bindParam(":nombreNuevo", $nombreNuevo, PDO::PARAM_STR);
            $result->bindParam(":apellidosNuevo", $apellidosNuevo, PDO::PARAM_STR);
            $result->bindParam(":idEstudiante", $idEstudiante, PDO::PARAM_INT);
            return $result->execute();
        }
        
           public static function listar($codigoAsignatura){
            $stmt = Conexion::conectar()->prepare("
                SELECT e.idEstudiante, e.nombre, e.apellido, e.correo, e.matricula 
                FROM estudiante e
                INNER JOIN estudiante_asignatura ea ON e.idEstudiante = ea.idEstudiante
                WHERE ea.codigoAsignatura = :codigoAsignatura
            ");
            $stmt->bindParam(':codigoAsignatura', $codigoAsignatura);
            $stmt->execute();
        
            $stmt->bindColumn("idEstudiante", $idEstudiante);
            $stmt->bindColumn("nombre", $nombre);
            $stmt->bindColumn("apellido", $apellido);
            $stmt->bindColumn("correo", $correo);
            $stmt->bindColumn("matricula", $matricula);
        
            $lista = array();
            while ($fila = $stmt->fetch(PDO::FETCH_BOUND)){
                $modelo = array();
                $modelo["idEstudiante"] = $idEstudiante;
                $modelo["nombre"] = $nombre;
                $modelo["apellido"] = $apellido;
                $modelo["correo"] = $correo;
                $modelo["matricula"] = $matricula;
                array_push($lista, $modelo);
            }
        
            return $lista;
        }


    public static function editar($nombre, $apellido, $correo, $matricula, $id){
        $result = Conexion::conectar()->prepare("UPDATE estudiante set nombre = :nombre, apellido = :apellido, correo = :correo, matricula = :matricula WHERE idEstudiante = :id");
        $result->bindParam(":nombre", $nombre, PDO::PARAM_STR);
        $result->bindParam(":apellido", $apellido , PDO::PARAM_STR);
        $result->bindParam(":correo", $correo , PDO::PARAM_STR);
        $result->bindParam(":matricula", $matricula , PDO::PARAM_STR);
        $result->bindParam(":id", $idEstudiante, PDO::PARAM_INT);
        return $result->execute();
    }

    public static function eliminar($idEstudiante){

         $result = Conexion::conectar()->prepare("DELETE FROM estudiante WHERE idEstudiante = :id");
         $result->bindParam(":id", $idEstudiante , PDO::PARAM_INT);

         return $result->execute();
    }
    
    public static function buscarContraseniaEstudiante($correo, $contrasenia){
            $result = Conexion::conectar()->prepare("SELECT nombre, password FROM estudiante WHERE correo = :correo AND password = :contrasenia");
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
        public static function obtenerIdEstudiante($correo){
            $result = Conexion::conectar()->prepare("SELECT idEstudiante FROM estudiante WHERE correo = :correo");
            $result->bindParam(":correo", $correo, PDO::PARAM_STR);
            $result->execute();
        
            if ($result->rowCount() > 0){
                $datos = $result->fetch(PDO::FETCH_ASSOC);
                return $datos["idEstudiante"];
            } else {
                return -1;
            }
        }
        public static function actualizarCorreoEstudiante($correoAntiguo, $correoNuevo){
            $result = Conexion::conectar()->prepare("UPDATE estudiante SET correo = :correoNuevo WHERE correo = :correoAntiguo");
            $result->bindParam(":correoNuevo", $correoNuevo, PDO::PARAM_STR);
            $result->bindParam(":correoAntiguo", $correoAntiguo, PDO::PARAM_STR);
            $result->execute();
        
            // Si se actualizó al menos una fila, entonces la actualización fue exitosa
            return $result->rowCount() > 0;
        }
        public static function actualizarPasswordEstudiante($idEstudiante, $passwordAntiguo, $passwordNuevo){
            $result = Conexion::conectar()->prepare("UPDATE estudiante SET password = :passwordNuevo WHERE idEstudiante = :idEstudiante AND password = :passwordAntiguo");
            $result->bindParam(":passwordNuevo", $passwordNuevo, PDO::PARAM_STR);
            $result->bindParam(":idEstudiante", $idEstudiante, PDO::PARAM_INT);
            $result->bindParam(":passwordAntiguo", $passwordAntiguo, PDO::PARAM_STR);
            $result->execute();
            // Si se actualizó al menos una fila, entonces la actualización fue exitosa
            return $result->rowCount() > 0;
        }
        
        public static function actualizarPasswordSinVerificarEstudiante($correo, $passwordNuevo){
            $result = Conexion::conectar()->prepare("UPDATE estudiante SET password = :passwordNuevo WHERE correo = :correo");
            $result->bindParam(":correo", $correo, PDO::PARAM_STR);
            $result->bindParam(":passwordNuevo", $passwordNuevo, PDO::PARAM_STR);
            $result->execute();
        
            // Si se actualizó al menos una fila, entonces la actualización fue exitosa
            return $result->rowCount() > 0;
        }
        public static function verificarCorreoEstudiante($correo){
            $result = Conexion::conectar()->prepare("SELECT * FROM estudiante WHERE correo = :correo");
            $result->bindParam(":correo", $correo, PDO::PARAM_STR);
            $result->execute();
        
            // Si se encontró al menos una fila, entonces el correo existe en la base de datos
            return $result->rowCount() > 0;
        }
        public static function obtenerNombrePorIdEstudiante($idEstudiante){
            $stmt = Conexion::conectar()->prepare("SELECT nombre, apellido FROM estudiante WHERE idEstudiante = :idEstudiante");
            $stmt->bindParam(':idEstudiante', $idEstudiante);
            $stmt->execute();
        
            $stmt->bindColumn("nombre", $nombre);
            $stmt->bindColumn("apellido", $apellido);
        
            $lista = array();
            while ($fila = $stmt->fetch(PDO::FETCH_BOUND)){
                $modelo = array();
                $modelo["nombre"] = $nombre;
                $modelo["apellido"] = $apellido;
                array_push($lista, $modelo);
            }
        
            return $lista;
        }

}
?>
