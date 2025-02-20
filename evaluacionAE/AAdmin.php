<?php
// Importa la clase de conexión
require_once "../evaluacionAE/Conexion.php";

class AAdmin {

    public static function buscarContraseniaAdmin($correo, $contrasenia){
        $result = Conexion::conectar()->prepare("SELECT nombre, contrasenia FROM admin WHERE correo = :correo AND contrasenia = :contrasenia");
        $result->bindParam(":correo", $correo, PDO::PARAM_STR);
        $result->bindParam(":contrasenia", $contrasenia, PDO::PARAM_STR);
        $result->execute();

        if ($result->rowCount() > 0){
            $datos = $result->fetch(PDO::FETCH_ASSOC);
            return array($datos["nombre"], $datos["contrasenia"]);
        } else {
            return null;
        }
    }

    public static function obtenerIdAdmin($correo){
        $result = Conexion::conectar()->prepare("SELECT idAdmin FROM admin WHERE correo = :correo");
        $result->bindParam(":correo", $correo, PDO::PARAM_STR);
        $result->execute();

        if ($result->rowCount() > 0){
            $datos = $result->fetch(PDO::FETCH_ASSOC);
            return $datos["idAdmin"];
        } else {
            return -1;
        }
    }

    public static function actualizarNombreAdmin($idAdmin, $nombreNuevo, $apellidoNuevo){
        $result = Conexion::conectar()->prepare("UPDATE admin SET nombre = :nombreNuevo, apellidoP = :apellidoNuevo WHERE idAdmin = :idAdmin");
        $result->bindParam(":nombreNuevo", $nombreNuevo, PDO::PARAM_STR);
        $result->bindParam(":apellidoNuevo", $apellidoNuevo, PDO::PARAM_STR);
        $result->bindParam(":idAdmin", $idAdmin, PDO::PARAM_INT);
        return $result->execute();
    }

    public static function actualizarCorreoAdmin($correoAntiguo, $correoNuevo){
        $result = Conexion::conectar()->prepare("UPDATE admin SET correo = :correoNuevo WHERE correo = :correoAntiguo");
        $result->bindParam(":correoNuevo", $correoNuevo, PDO::PARAM_STR);
        $result->bindParam(":correoAntiguo", $correoAntiguo, PDO::PARAM_STR);
        return $result->execute();
    }

    public static function actualizarPasswordAdmin($idAdmin, $passwordAntiguo, $passwordNuevo){
        $result = Conexion::conectar()->prepare("UPDATE admin SET contrasenia = :passwordNuevo WHERE idAdmin = :idAdmin AND contrasenia = :passwordAntiguo");
        $result->bindParam(":passwordNuevo", $passwordNuevo, PDO::PARAM_STR);
        $result->bindParam(":idAdmin", $idAdmin, PDO::PARAM_INT);
        $result->bindParam(":passwordAntiguo", $passwordAntiguo, PDO::PARAM_STR);
        return $result->execute();
    }

}

?>