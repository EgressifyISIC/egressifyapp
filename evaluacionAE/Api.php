<?php

require_once '../evaluacionAE/ADocente.php';
require_once '../evaluacionAE/AEstudiante.php';
require_once '../evaluacionAE/AAsignatura.php';
require_once '../evaluacionAE/AAtributoE.php';
require_once '../evaluacionAE/AAdmin.php';
require_once '../evaluacionAE/ACalificacion.php';
require_once '../evaluacionAE/ACriteriosEval.php';
require_once '../evaluacionAE/AEstudianteAsignatura.php';
require_once '../evaluacionAE/Conexion.php';

//función validando todos los parametros disponibles
//pasaremos los parámetros requeridos a esta función

function verificadoDeParametros($params){
	//suponiendo que todos los parametros estan disponibles
	$available = true;
	$missingparams = "";

	foreach ($params as $param) {
		if(!isset($_POST[$param]) || strlen($_POST[$param]) <= 0){
			$available = false;
			$missingparams = $missingparams . ", " . $param;
		}
	}

	//si faltan parametros
	if(!$available){
		$response = array();
		$response['error'] = true;
		$response['message'] = 'Parametro' . substr($missingparams, 1, strlen($missingparams)) . ' vacio';

		//error de visualización
		echo json_encode($response);

		//detener la ejecición adicional
		die();
	}
}

//una matriz para mostrar las respuestas de nuestro api
$response = array();

//si se trata de una llamada api
//que significa que un parametro get llamado se establece un la URL
//y con estos parametros estamos concluyendo que es una llamada api

if(isset($_GET['apicall'])){

	//Aqui iran todos los llamados de nuestra api
	switch ($_GET['apicall']) {

		//opreacion guardar persona
		//ROL: DOCENTE
		case 'guardarDocente':
			//primero haremos la verificación de parametros.
			
			verificadoDeParametros(array('nombre', 'apellidos', 'correo', 'matricula', 'password'));
			$db = new ADocente();
			$result = $db->guardar($_POST['nombre'],$_POST['apellidos'],$_POST['correo'],$_POST['matricula'], $_POST['password']);

			if($result){

				//esto significa que no hay ningun error
				$response['error'] = false;
				//mensaje que se ejecuto correctamente
				$response['message'] = 'Docente guardado correctamente';

				$response['contenido'] = $db->listar();
			}else{
				$response['error'] = true;
				$response['message'] = 'ocurrio un error, intenta nuevamente';
			}
		break;
		case 'actualizarNombre':
            // Primero haremos la verificación de parametros.
            verificadoDeParametros(array('idDocente', 'nombreNuevo', 'apellidosNuevo'));
            $db = new ADocente();
            $result = $db->actualizarNombre($_POST['idDocente'], $_POST['nombreNuevo'], $_POST['apellidosNuevo']);
        
            if($result){
                // Esto significa que no hay ningun error
                $response['error'] = false;
                // Mensaje que se ejecuto correctamente
                $response['message'] = 'Nombre actualizado correctamente';
            }else{
                // Si no se cumple la condición anterior, se establece el error en verdadero y se envía un mensaje de error.
                $response['error'] = true;
                $response['message'] = 'No se pudo actualizar el nombre';
            }
        break;

		case 'obtenerIdDocente':
            // Primero haremos la verificación de parametros.
            verificadoDeParametros(array('correo'));
            $db = new ADocente();
            $idDocente = $db->obtenerIdDocente($_POST['correo']);
        
            if($idDocente != -1){
                // Esto significa que no hay ningun error
                $response['error'] = false;
                // Mensaje que se ejecuto correctamente
                $response['message'] = 'ID del docente obtenido correctamente';
                $response['contenido'] = $idDocente;
            }else{
                // Si no se cumple la condición anterior, se establece el error en verdadero y se envía un mensaje de error.
                $response['error'] = true;
                $response['message'] = 'No se encontró un docente con ese correo';
            }
        break;

    case 'contarEstudiantesPorAsignatura':
        verificadoDeParametros(array('codigoAsignatura'));
        $totalEstudiantes = AAsignatura::contarEstudiantesPorAsignatura($_POST['codigoAsignatura']);
    
        if ($totalEstudiantes != null) {
            $response['error'] = false;
            $response['message'] = 'Total de estudiantes obtenido correctamente';
            $response['totalEstudiantes'] = $totalEstudiantes;
        } else {
            $response['error'] = true;
            $response['message'] = 'No se pudo obtener el total de estudiantes';
        }
        break;

		//ROL: DOCENTE, ESTUDIANTE, ADMINISTRADOR
		case 'listarDocente':
			$db = new ADocente();
			$lista = $db->listar();
			$response['error'] = false;
			$response['message'] = 'Solicitud completada correctamente';
			$response['contenido'] = $lista;
		break;
		case 'actualizarCorreo':
            // Primero haremos la verificación de parametros.
            verificadoDeParametros(array('correoAntiguo', 'correoNuevo'));
            $db = new ADocente();
            $result = $db->actualizarCorreo($_POST['correoAntiguo'], $_POST['correoNuevo']);
        
            if($result){
                // Esto significa que no hay ningun error
                $response['error'] = false;
                // Mensaje que se ejecuto correctamente
                $response['message'] = 'Correo actualizado correctamente';
            }else{
                // Si no se cumple la condición anterior, se establece el error en verdadero y se envía un mensaje de error.
                $response['error'] = true;
                $response['message'] = 'No se pudo actualizar el correo';
            }
        break;
        case 'actualizarPassword':
            // Primero haremos la verificación de parametros.
            verificadoDeParametros(array('idDocente', 'passwordAntiguo', 'passwordNuevo'));
            $db = new ADocente();
            $result = $db->actualizarPassword($_POST['idDocente'], $_POST['passwordAntiguo'], $_POST['passwordNuevo']);
        
            if($result){
                // Esto significa que no hay ningun error
                $response['error'] = false;
                // Mensaje que se ejecuto correctamente
                $response['message'] = 'Contraseña actualizada correctamente';
            }else{
                // Si no se cumple la condición anterior, se establece el error en verdadero y se envía un mensaje de error.
                $response['error'] = true;
                $response['message'] = 'No se pudo actualizar la contraseña';
            }
        break;
        case 'verificarCorreo':
            // Primero haremos la verificación de parametros.
            verificadoDeParametros(array('correo'));
            $db = new ADocente();
            $result = $db->verificarCorreo($_POST['correo']);
        
            if($result){
                // Esto significa que no hay ningun error
                $response['error'] = false;
                // Mensaje que se ejecuto correctamente
                $response['message'] = 'El correo existe en la base de datos';
            }else{
                // Si no se cumple la condición anterior, se establece el error en verdadero y se envía un mensaje de error.
                $response['error'] = true;
                $response['message'] = 'El correo no existe en la base de datos';
            }
        break;
case 'contarAprobadosPorAtributo':
    verificadoDeParametros(array('idAtributoE'));
    $db = new AAtributoE();
    $aprobados = $db->contarAprobadosPorAtributo($_POST['idAtributoE']);

    if ($aprobados != null) {
        $response['error'] = false;
        $response['message'] = 'Número de aprobados obtenido correctamente';
        $response['aprobados'] = $aprobados;
    } else {
        $response['error'] = true;
        $response['message'] = 'No se pudo obtener el número de aprobados';
    }
    break;

        case 'actualizarPasswordSinVerificar':
            // Primero haremos la verificación de parametros.
            verificadoDeParametros(array('correo', 'passwordNuevo'));
            $db = new ADocente();
            $result = $db->actualizarPasswordSinVerificar($_POST['correo'], $_POST['passwordNuevo']);
        
            if($result){
                // Esto significa que no hay ningun error
                $response['error'] = false;
                // Mensaje que se ejecuto correctamente
                $response['message'] = 'Contraseña actualizada correctamente';
            }else{
                // Si no se cumple la condición anterior, se establece el error en verdadero y se envía un mensaje de error.
                $response['error'] = true;
                $response['message'] = 'No se pudo actualizar la contraseña';
            }
        break;



		//ROL: DOCENTE
		case 'editarDocente':
			// Primero haremos la verificación de parametros.
			verificadoDeParametros(array('nombre', 'apellidos', 'correo', 'matricula', 'password'));
			$db = new ADocente();
			$result = $db->editar($_POST['nombre'], $_POST['apellidos'], $_POST['correo'], $_POST['matricula'], $_POST['password'], $_POST['id']);
			if($result){
				// Esto significa que no hay ningun error
				$response['error'] = false;
				// Mensaje que se ejecuto correctamente
				$response['message'] = 'Docente editado correctamente';
				$response['contenido'] = $db->listar();
			}else{
				// Si no se cumple la condición anterior, se establece el error en verdadero y se envía un mensaje de error.
				$response['error'] = true;
				$response['message'] = 'Ocurrio un error, intenta nuevamente';
			}
			break;
			
			case 'buscarContrasenia':
                // Primero haremos la verificación de parametros.
                verificadoDeParametros(array('correo', 'contrasenia'));
                $db = new ADocente();
                $datos = $db->buscarContrasenia($_POST['correo'], $_POST['contrasenia']);
            
                if($datos != null){
                    // Esto significa que no hay ningun error
                    $response['error'] = false;
                    // Mensaje que se ejecuto correctamente
                    $response['message'] = 'Contraseña encontrada correctamente';
                    $response['contenido'] = $datos;
                }else{
                    // Si no se cumple la condición anterior, se establece el error en verdadero y se envía un mensaje de error.
                    $response['error'] = true;
                    $response['message'] = 'No se encontró una contraseña que coincida';
                }
            break;

			
			// Caso para eliminar un docente.
			//ROL: DOCENTE
			case 'eliminarDocente':
				// Verifica si el id del docente se ha establecido y no está vacío.
				if(isset($_POST['id']) && !empty($_POST['id'])){
					// Crea una nueva instancia de la clase ADocente.
					$db = new ADocente();
					// Si el método eliminar es exitoso, se establece el error en falso, se envía un mensaje y se lista el contenido actualizado.
					if($db->eliminar($_POST['id'])){
						$response['error'] = false;
						$response['message'] = 'Docente eliminado';
						$response['contenido'] = $db->listar();
					}else{
						// Si el método eliminar no es exitoso, se establece el error en verdadero y se envía un mensaje de error.
						$response['error'] = true;
						$response['message'] = 'El docente no fue eliminado';
					}
				}
			break;
			
			// Caso para guardar una asignatura
			//ROL: DOCENTE.
    		case 'guardarAsignatura':
                // Primero haremos la verificación de parametros.
                verificadoDeParametros(array('asignatura', 'claveAsignatura', 'claveGrupo', 'codigoAsignatura', 'idDocente'));
                $db = new AAsignatura();
                $result = $db->guardar($_POST['asignatura'], $_POST['claveAsignatura'], $_POST['claveGrupo'], $_POST['codigoAsignatura'], $_POST['idDocente']);
            
                if($result){
                    // Esto significa que no hay ningun error
                    $response['error'] = false;
                    // Mensaje que se ejecuto correctamente
                    $response['message'] = 'Asignatura guardada correctamente';
                }else{
                    // Si no se cumple la condición anterior, se establece el error en verdadero y se envía un mensaje de error.
                    $response['error'] = true;
                    $response['message'] = 'Ocurrió un error, intenta nuevamente';
                }
            break;
            case 'listarAsignaturaPorIdDocente':
                verificadoDeParametros(array('idDocente'));
                $db = new AAsignatura();
                $lista = $db->listarPorIdDocente($_POST['idDocente']);
                $response['error'] = false;
                $response['message'] = 'Solicitud completada correctamente';
                $response['contenido'] = $lista;
            break;
            
            
            case 'listarAsignatura':
                $db = new AAsignatura();
                $lista = $db->listarAsignatura();
                $response['error'] = false;
                $response['message'] = 'Solicitud completada correctamente';
                $response['contenido'] = $lista;
            break;
            
            

			
			// Caso para listar las asignaturas.
			//ROL: DOCENTE, ESTUDIANTE, ADMINISTRADOR
			
			case 'listarAsignatura':
				// Crea una nueva instancia de la clase AAsignatura.
				$db = new AAsignatura();
				// Lista las asignaturas y las almacena en la variable lista.
				$lista = $db->listar();
				// Se establece el error en falso, se envía un mensaje y se envía el contenido de la lista.
				$response['error'] = false;
				$response['message'] = 'Solicitud completada correctamente';
				$response['contenido'] = $lista;
			break;
			
			// Caso para editar una asignatura.
			//ROL: DOCENTE
			case 'editarAsignatura':
				verificadoDeParametros(array('asignatura', 'claveAsignatura', 'claveGrupo', 'codigoAsignatura', 'idDocente'));
				// Crea una nueva instancia de la clase AAsignatura.
				$db = new AAsignatura();
				// Si el método editar es exitoso, se establece el error en falso, se envía un mensaje y se lista el contenido actualizado.
				$result = $db->editar($_POST['asignatura'], $_POST['claveAsignatura'], $_POST['claveGrupo'], $_POST['codigoAsignatura'], $_POST['idDocente'], $_POST['id']);
				if($result){
					$response['error'] = false;
					$response['message'] = 'Asignatura editada correctamente';
					$response['contenido'] = $db->listar();
				}else{
					// Si el método editar no es exitoso, se establece el error en verdadero y se envía un mensaje de error.
					$response['error'] = true;
					$response['message'] = 'Ocurrió un error, intenta nuevamente';
				}
			break;
		
				// Caso para eliminar una asignatura.
		//ROL: DOCENTE
		case 'eliminarAsignatura':
			// Verifica si el id de la asignatura se ha establecido y no está vacío.
			if(isset($_POST['codigoAsignatura']) && !empty($_POST['codigoAsignatura'])){
				// Crea una nueva instancia de la clase AAsignatura.
				$db = new AAsignatura();
				// Si el método eliminar es exitoso, se establece el error en falso, se envía un mensaje y se lista el contenido actualizado.
				if($db->eliminar($_POST['codigoAsignatura'])){
					$response['error'] = false;
					$response['message'] = 'Asignatura eliminada';
					$response['contenido'] = $db->listar();
				}else{
					// Si el método eliminar no es exitoso, se establece el error en verdadero y se envía un mensaje de error.
					$response['error'] = true;
					$response['message'] = 'La asignatura no fue eliminada';
				}
			}
		break;
        case 'obtenerIdAsignatura':
            // Primero haremos la verificación de parametros.
            verificadoDeParametros(array('codigoAsignatura'));
            $db = new AAsignatura();
            $result = $db->obtenerIdAsignatura($_POST['codigoAsignatura']);
        
            if($result){
                // Esto significa que no hay ningun error
                $response['error'] = false;
                // Mensaje que se ejecuto correctamente
                $response['message'] = 'idAsignatura obtenido correctamente';
                $response['contenido'] = $result;
            }else{
                // Si no se cumple la condición anterior, se establece el error en verdadero y se envía un mensaje de error.
                $response['error'] = true;
                $response['message'] = 'Ocurrió un error, intenta nuevamente';
            }
        break;



		// Caso para guardar un atributo educativo.
		//ROL: DOCENTE
		case 'guardarAtributoE':
            // Primero haremos la verificación de parametros.
            verificadoDeParametros(array('logro', 'meta', 'idAsignaturas', 'atributoE'));
            $db = new AAtributoE();
            $result = $db->guardarAtributoE($_POST['logro'], $_POST['meta'], $_POST['idAsignaturas'], $_POST['atributoE']);
        
            if($result){
                // Esto significa que no hay ningun error
                $response['error'] = false;
                // Mensaje que se ejecuto correctamente
                $response['message'] = 'AtributoE guardado correctamente';
            }else{
                // Si no se cumple la condición anterior, se establece el error en verdadero y se envía un mensaje de error.
                $response['error'] = true;
                $response['message'] = 'Ocurrió un error, intenta nuevamente';
            }
        break;
        case 'obtenerIdAtributoE':
            // Primero haremos la verificación de parametros.
            verificadoDeParametros(array('atributoE', 'idAsignaturas'));
            $db = new AAtributoE();
            $result = $db->obtenerIdAtributoE($_POST['atributoE'], $_POST['idAsignaturas']);
        
            if($result){
                // Esto significa que no hay ningun error
                $response['error'] = false;
                // Mensaje que se ejecuto correctamente
                $response['message'] = 'idAtributoE obtenido correctamente';
                $response['contenido'] = $result;
            }else{
                // Si no se cumple la condición anterior, se establece el error en verdadero y se envía un mensaje de error.
                $response['error'] = true;
                $response['message'] = 'Ocurrió un error, intenta nuevamente';
            }
        break;


        case 'listarAtributosEgresoPorIdAsignatura':
            // Primero haremos la verificación de parametros.
            verificadoDeParametros(array('idAsignatura'));
            $db = new AAtributoE();
            $result = $db->listarAtributosEgresoPorIdAsignatura($_POST['idAsignatura']);
        
            if($result){
                // Esto significa que no hay ningun error
                $response['error'] = false;
                // Mensaje que se ejecuto correctamente
                $response['message'] = 'Atributos de egreso listados correctamente';
                $response['contenido'] = $result;
            }else{
                // Si no se cumple la condición anterior, se establece el error en verdadero y se envía un mensaje de error.
                $response['error'] = true;
                $response['message'] = 'Ocurrió un error, intenta nuevamente';
            }
        break;

		// Caso para listar los atributos educativos.
		//ROL: DOCENTE, ESTUDIANTE, ADMINISTRADOR
		case 'listarAtributoE':
			// Crea una nueva instancia de la clase AAtributoE.
			$db = new AAtributoE();
			// Lista los atributos educativos y los almacena en la variable lista.
			$lista = $db->listar();
			// Se establece el error en falso, se envía un mensaje y se envía el contenido de la lista.
			$response['error'] = false;
			$response['message'] = 'Solicitud completada correctamente';
			$response['contenido'] = $lista;
		break;

		// Caso para editar un atributo educativo.
		//ROL: DOCENTE
		case 'editarAtributoE':
			verificadoDeParametros(array('logro', 'meta', 'idAsignaturas'));
			// Crea una nueva instancia de la clase AAtributoE.
			$db = new AAtributoE();
			// Si el método editar es exitoso, se establece el error en falso, se envía un mensaje y se lista el contenido actualizado.
			$result = $db->editar($_POST['logro'], $_POST['meta'], $_POST['idAsignaturas'], $_POST['id']);
			if($result){
				$response['error'] = false;
				$response['message'] = 'Atributo editado correctamente';
				$response['contenido'] = $db->listar();
			}else{
				// Si el método editar no es exitoso, se establece el error en verdadero y se envía un mensaje de error.
				$response['error'] = true;
				$response['message'] = 'Ocurrió un error, intenta nuevamente';
			}
		break;

		// Caso para eliminar un atributo educativo.
		//ROL: DOCENTE
		case 'eliminarAtributoE':
			// Verifica si el id del atributo educativo se ha establecido y no está vacío.
			if(isset($_POST['id']) && !empty($_POST['id'])){
				// Crea una nueva instancia de la clase AAtributoE.
				$db = new AAtributoE();
				// Si el método eliminar es exitoso, se establece el error en falso, se envía un mensaje y se lista el contenido actualizado.
				if($db->eliminar($_POST['id'])){
					$response['error'] = false;
					$response['message'] = 'Atributo eliminado';
					$response['contenido'] = $db->listar();
				}else{
					// Si el método eliminar no es exitoso, se establece el error en verdadero y se envía un mensaje de error.
					$response['error'] = true;
					$response['message'] = 'El atributo no fue eliminado';
				}
			}
		break;		

		// Caso para guardar un estudiante.
		//ROL: ESTUDIANTE
		case 'guardarEstudiante':
			verificadoDeParametros(array('nombre', 'apellido', 'correo', 'matricula','password'));
			// Crea una nueva instancia de la clase AEstudiante.
			$db = new AEstudiante();
			// Si el método guardar es exitoso, se establece el error en falso, se envía un mensaje y se lista el contenido actualizado.
			$result = $db->guardar($_POST['nombre'], $_POST['apellido'], $_POST['correo'], $_POST['matricula'],$_POST['password']);
			if($result){
				$response['error'] = false;
				$response['message'] = 'Estudiante guardado correctamente';
				$response['contenido'] = $db->listar();
			}else{
				// Si el método guardar no es exitoso, se establece el error en verdadero y se envía un mensaje de error.
				$response['error'] = true;
				$response['message'] = 'Ocurrió un error, intenta nuevamente';
			}
		break;
		
		case 'obtenerNombrePorIdEstudiante':
            verificadoDeParametros(array('idEstudiante'));
            $db = new AEstudiante();
            $nombreYApellidos = $db->obtenerNombrePorIdEstudiante($_POST['idEstudiante']);
            $response['error'] = false;
            $response['message'] = 'Solicitud completada correctamente';
            $response['nombreYApellidos'] = $nombreYApellidos;
        break;

		
		case 'actualizarNombreEstudiante':
            // Primero haremos la verificación de parametros.
            verificadoDeParametros(array('idEstudiante', 'nombreNuevo', 'apellidosNuevo'));
            $db = new AEstudiante();
            $result = $db->actualizarNombreEstudiante($_POST['idEstudiante'], $_POST['nombreNuevo'], $_POST['apellidosNuevo']);
        
            if($result){
                // Esto significa que no hay ningun error
                $response['error'] = false;
                // Mensaje que se ejecuto correctamente
                $response['message'] = 'Nombre actualizado correctamente';
            }else{
                // Si no se cumple la condición anterior, se establece el error en verdadero y se envía un mensaje de error.
                $response['error'] = true;
                $response['message'] = 'No se pudo actualizar el nombre';
            }
        break;

		case 'obtenerIdEstudiante':
            // Primero haremos la verificación de parametros.
            verificadoDeParametros(array('correo'));
            $db = new AEstudiante();
            $idEstudiante = $db->obtenerIdEstudiante($_POST['correo']);
        
            if($idEstudiante != -1){
                // Esto significa que no hay ningun error
                $response['error'] = false;
                // Mensaje que se ejecuto correctamente
                $response['message'] = 'ID del estudiante obtenido correctamente';
                $response['contenido'] = $idEstudiante;
            }else{
                // Si no se cumple la condición anterior, se establece el error en verdadero y se envía un mensaje de error.
                $response['error'] = true;
                $response['message'] = 'No se encontró un docente con ese correo';
            }
        break;
        
        case 'actualizarCorreoEstudiante':
            // Primero haremos la verificación de parametros.
            verificadoDeParametros(array('correoAntiguo', 'correoNuevo'));
            $db = new AEstudiante();
            $result = $db->actualizarCorreoEstudiante($_POST['correoAntiguo'], $_POST['correoNuevo']);
        
            if($result){
                // Esto significa que no hay ningun error
                $response['error'] = false;
                // Mensaje que se ejecuto correctamente
                $response['message'] = 'Correo actualizado correctamente';
            }else{
                // Si no se cumple la condición anterior, se establece el error en verdadero y se envía un mensaje de error.
                $response['error'] = true;
                $response['message'] = 'No se pudo actualizar el correo';
            }
        break;
        
        case 'actualizarPasswordEstudiante':
            // Primero haremos la verificación de parametros.
            verificadoDeParametros(array('idEstudiante', 'passwordAntiguo', 'passwordNuevo'));
            $db = new AEstudiante();
            $result = $db->actualizarPasswordEstudiante($_POST['idEstudiante'], $_POST['passwordAntiguo'], $_POST['passwordNuevo']);
        
            if($result){
                // Esto significa que no hay ningun error
                $response['error'] = false;
                // Mensaje que se ejecuto correctamente
                $response['message'] = 'Contraseña actualizada correctamente';
            }else{
                // Si no se cumple la condición anterior, se establece el error en verdadero y se envía un mensaje de error.
                $response['error'] = true;
                $response['message'] = 'No se pudo actualizar la contraseña';
            }
        break;
        
        case 'buscarContraseniaEstudiante':
                // Primero haremos la verificación de parametros.
                verificadoDeParametros(array('correo', 'contrasenia'));
                $db = new AEstudiante();
                $datos = $db->buscarContraseniaEstudiante($_POST['correo'], $_POST['contrasenia']);
            
                if($datos != null){
                    // Esto significa que no hay ningun error
                    $response['error'] = false;
                    // Mensaje que se ejecuto correctamente
                    $response['message'] = 'Contraseña encontrada correctamente';
                    $response['contenido'] = $datos;
                }else{
                    // Si no se cumple la condición anterior, se establece el error en verdadero y se envía un mensaje de error.
                    $response['error'] = true;
                    $response['message'] = 'No se encontró una contraseña que coincida';
                }
            break;

		
		// Caso para listar los estudiantes.
		//ROL: DOCENTE, ESTUDIANTE, ADMINISTRADOR
        	case 'listarEstudiante':
            // Obtén el codigoAsignatura de la solicitud
            $codigoAsignatura = $_POST['codigoAsignatura'];
        
            // Crea una nueva instancia de la clase AEstudiante.
            $db = new AEstudiante();
        
            // Lista los estudiantes y los almacena en la variable lista.
            $lista = $db->listar($codigoAsignatura);
        
            // Se establece el error en falso, se envía un mensaje y se envía el contenido de la lista.
            $response['error'] = false;
            $response['message'] = 'Solicitud completada correctamente';
            $response['contenido'] = $lista;
        break;


		// Caso para editar un estudiante.
		//ROL: ESTUDIANTE
		case 'editarEstudiante':
			verificadoDeParametros(array('nombre', 'apellido', 'correo', 'matricula', 'password'));
			// Crea una nueva instancia de la clase AEstudiante.
			$db = new AEstudiante();
			// Si el método editar es exitoso, se establece el error en falso, se envía un mensaje y se lista el contenido actualizado.
			$result = $db->editar($_POST['nombre'], $_POST['apellido'], $_POST['correo'], $_POST['matricula'], $_POST['password'], $_POST['id']);
			if($result){
				$response['error'] = false;
				$response['message'] = 'Estudiante editado correctamente';
				$response['contenido'] = $db->listar();
			}else{
				// Si el método editar no es exitoso, se establece el error en verdadero y se envía un mensaje de error.
				$response['error'] = true;
				$response['message'] = 'Ocurrió un error, intenta nuevamente';
			}
		break;

		// Caso para eliminar un estudiante.
		//ROL: ESTUDIANTE
		case 'eliminarEstudiante':
			// Verifica si el id del estudiante se ha establecido y no está vacío.
			if(isset($_POST['id']) && !empty($_POST['id'])){
				// Crea una nueva instancia de la clase AEstudiante.
				$db = new AEstudiante();
				// Si el método eliminar es exitoso, se establece el error en falso, se envía un mensaje y se lista el contenido actualizado.
				if($db->eliminar($_POST['id'])){
					$response['error'] = false;
					$response['message'] = 'Estudiante eliminado';
					$response['contenido'] = $db->listar();
				}else{
					// Si el método eliminar no es exitoso, se establece el error en verdadero y se envía un mensaje de error.
					$response['error'] = true;
					$response['message'] = 'El estudiante no fue eliminado';
				}
			}
		break;
		
		case 'verificarCorreoEstudiante':
            // Primero haremos la verificación de parametros.
            verificadoDeParametros(array('correo'));
            $db = new AEstudiante();
            $result = $db->verificarCorreoEstudiante($_POST['correo']);
        
            if($result){
                // Esto significa que no hay ningun error
                $response['error'] = false;
                // Mensaje que se ejecuto correctamente
                $response['message'] = 'El correo existe en la base de datos';
            }else{
                // Si no se cumple la condición anterior, se establece el error en verdadero y se envía un mensaje de error.
                $response['error'] = true;
                $response['message'] = 'El correo no existe en la base de datos';
            }
        break;

        case 'actualizarPasswordSinVerificarEstudiante':
            // Primero haremos la verificación de parametros.
            verificadoDeParametros(array('correo', 'passwordNuevo'));
            $db = new AEstudiante();
            $result = $db->actualizarPasswordSinVerificarEstudiante($_POST['correo'], $_POST['passwordNuevo']);
        
            if($result){
                // Esto significa que no hay ningun error
                $response['error'] = false;
                // Mensaje que se ejecuto correctamente
                $response['message'] = 'Contraseña actualizada correctamente';
            }else{
                // Si no se cumple la condición anterior, se establece el error en verdadero y se envía un mensaje de error.
                $response['error'] = true;
                $response['message'] = 'No se pudo actualizar la contraseña';
            }
        break;

		// Caso para guardar una relación Estudiante-Asignatura.
		//ROL: ESTUDIANTE
		case 'guardarEstudianteAsignatura':
			verificadoDeParametros(array('idEstudiante', 'codigoAsignatura'));
			// Crea una nueva instancia de la clase AEstudianteAsignatura.
			$db = new AEstudianteAsignatura();
			// Si el método guardar es exitoso, se establece el error en falso, se envía un mensaje y se lista el contenido actualizado.
			$result = $db->guardar($_POST['idEstudiante'], $_POST['codigoAsignatura']);
			if($result){
				$response['error'] = false;
				$response['message'] = 'Relación Estudiante-Asignatura guardada correctamente';
				$response['contenido'] = $db->listar();
			}else{
				// Si el método guardar no es exitoso, se establece el error en verdadero y se envía un mensaje de error.
				$response['error'] = true;
				$response['message'] = 'Ocurrió un error, intenta nuevamente';
			}
		break;

		// Caso para listar las relaciones Estudiante-Asignatura.
		//ROL: DOCENTE, ESTUDIANTE, ADMINISTRADOR
		case 'listarEstudianteAsignatura':
			// Crea una nueva instancia de la clase AEstudianteAsignatura.
			$db = new AEstudianteAsignatura();
			// Lista las relaciones Estudiante-Asignatura y las almacena en la variable lista.
			$lista = $db->listar();
			// Se establece el error en falso, se envía un mensaje y se envía el contenido de la lista.
			$response['error'] = false;
			$response['message'] = 'Solicitud completada correctamente';
			$response['contenido'] = $lista;
		break;
		
		case 'obtenerNivelPorIdAtributoE':
        verificadoDeParametros(array('idAtributoE'));
        $db = new AAtributoE();
        $nivel = $db->obtenerNivelPorIdAtributoE($_POST['idAtributoE']);

        if ($nivel != null) {
            $response['error'] = false;
            $response['message'] = 'Nivel obtenido correctamente';
            $response['nivel'] = $nivel;
        } else {
            $response['error'] = true;
            $response['message'] = 'No se pudo obtener el nivel';
        }
        break;



		// Caso para eliminar una relación Estudiante-Asignatura.
		//ROL: DOCENTE, ESTUDIANTE
		case 'eliminarEstudianteAsignatura':
			// Verifica si el id de la relación Estudiante-Asignatura se ha establecido y no está vacío.
			if(isset($_POST['id']) && !empty($_POST['id'])){
				// Crea una nueva instancia de la clase AEstudianteAsignatura.
				$db = new AEstudianteAsignatura();
				// Si el método eliminar es exitoso, se establece el error en falso, se envía un mensaje y se lista el contenido actualizado.
				if($db->eliminar($_POST['id'])){
					$response['error'] = false;
					$response['message'] = 'Relación Estudiante-Asignatura eliminada';
					$response['contenido'] = $db->listar();
				}else{
					// Si el método eliminar no es exitoso, se establece el error en verdadero y se envía un mensaje de error.
					$response['error'] = true;
					$response['message'] = 'La relación Estudiante-Asignatura no fue eliminada';
				}
			}
		break;
	
        case 'guardarEstudianteAsignaturaSiExiste':
            verificadoDeParametros(array('codigoAsignatura', 'idEstudiante'));
            // Crea una nueva instancia de la clase AEstudianteAsignatura.
            $db = new AEstudianteAsignatura();
            // Si el método guardarSiExisteAsignatura es exitoso, se establece el error en falso, se envía un mensaje y se lista el contenido actualizado.
            $result = $db->guardarSiExisteAsignatura($_POST['codigoAsignatura'], $_POST['idEstudiante']);
            if($result){
                $response['error'] = false;
                $response['message'] = 'Relación Estudiante-Asignatura guardada correctamente';
                $response['contenido'] = $db->listar();
            }else{
                // Si el método guardarSiExisteAsignatura no es exitoso, se establece el error en verdadero y se envía un mensaje de error.
                $response['error'] = true;
                $response['message'] = 'El codigoAsignatura no existe o ocurrió un error, intenta nuevamente';
            }
        break;
        
       case 'obtenerAsignaturasEstudiante':
            verificadoDeParametros(array('idEstudiante'));
            // Crea una nueva instancia de la clase AEstudianteAsignatura.
            $db = new AEstudianteAsignatura();
            // Si el método obtenerAsignaturasEstudiante es exitoso, se establece el error en falso, se envía un mensaje y se lista el contenido actualizado.
            $result = $db->obtenerAsignaturasEstudiante($_POST['idEstudiante']);
            if($result){
                $response['error'] = false;
                $response['message'] = 'Asignaturas obtenidas correctamente';
                $response['contenido'] = $result;
            }else{
                // Si el método obtenerAsignaturasEstudiante no es exitoso, se establece el error en verdadero y se envía un mensaje de error.
                $response['error'] = true;
                $response['message'] = 'El idEstudiante no existe o ocurrió un error, intenta nuevamente';
            }
        break;








		// Caso para guardar un criterio de evaluación.
		//ROL: DOCENTE
	case 'guardarCriteriosEval':
        verificadoDeParametros(array('indicadorEspecifico', 'nivel', 'idAtributoE', 'puntos'));
        $db = new ACriteriosEval();
        $result = $db->guardarCriteriosEval($_POST['indicadorEspecifico'], $_POST['nivel'], $_POST['idAtributoE'], $_POST['puntos']);
    
        if($result){
            // Esto significa que no hay ningun error
            $response['error'] = false;
            // Mensaje que se ejecuto correctamente
            $response['message'] = 'Criterio de evaluación guardado correctamente';
        }else{
            // Si no se cumple la condición anterior, se establece el error en verdadero y se envía un mensaje de error.
            $response['error'] = true;
            $response['message'] = 'Ocurrió un error, intenta nuevamente';
        }
    break;
    case 'obtenerIdCriterioPorIndicador':
        verificadoDeParametros(array('indicadorEspecifico'));
        $db = new ACriteriosEval();
        $lista = $db->obtenerIdCriterioPorIndicador($_POST['indicadorEspecifico']);
        $response['error'] = false;
        $response['message'] = 'Solicitud completada correctamente';
        $response['contenido'] = $lista;
    break;
    case 'obtenerDocentePorIdAsignatura':
        verificadoDeParametros(array('idAsignatura'));
        $db = new ADocente();
        $docente = $db->obtenerDocentePorIdAsignatura($_POST['idAsignatura']);
        $response['error'] = false;
        $response['message'] = 'Solicitud completada correctamente';
        $response['contenido'] = $docente;
        
    break;
    case 'obtenerCriteriosPorAtributo':
        verificadoDeParametros(array('idAtributoE'));
        $db = new ACriteriosEval();
        $lista = $db->obtenerCriteriosPorAtributo($_POST['idAtributoE']);
        $response['error'] = false;
        $response['message'] = 'Solicitud completada correctamente';
        $response['contenido'] = $lista;
    break;



		// Caso para listar los criterios de evaluación.
		//ROL: DOCENTE, ESTUDIANTE
		case 'listarCriteriosEval':
			// Crea una nueva instancia de la clase ACriteriosEval.
			$db = new ACriteriosEval();
			// Lista los criterios de evaluación y los almacena en la variable lista.
			$lista = $db->listar();
			// Se establece el error en falso, se envía un mensaje y se envía el contenido de la lista.
			$response['error'] = false;
			$response['message'] = 'Solicitud completada correctamente';
			$response['contenido'] = $lista;
		break;

		// Caso para editar un criterio de evaluación.
		//ROL: DOCENTE
		case 'editarCriteriosEval':
			verificadoDeParametros(array('indicadorEspecifico', 'idAtributoE', 'puntos'));
			// Crea una nueva instancia de la clase ACriteriosEval.
			$db = new ACriteriosEval();
			// Si el método editar es exitoso, se establece el error en falso, se envía un mensaje y se lista el contenido actualizado.
			$result = $db->editar($_POST['indicadorEspecifico'], $_POST['idAtributoE'], $_POST['puntos'], $_POST['id']);
			if($result){
				$response['error'] = false;
				$response['message'] = 'Criterio de evaluación editado correctamente';
				$response['contenido'] = $db->listar();
			}else{
				// Si el método editar no es exitoso, se establece el error en verdadero y se envía un mensaje de error.
				$response['error'] = true;
				$response['message'] = 'Ocurrió un error, intenta nuevamente';
			}
		break;

		// Caso para eliminar un criterio de evaluación.
		//ROL: DOCENTE
		case 'eliminarCriteriosEval':
			// Verifica si el id del criterio de evaluación se ha establecido y no está vacío.
			if(isset($_POST['id']) && !empty($_POST['id'])){
				// Crea una nueva instancia de la clase ACriteriosEval.
				$db = new ACriteriosEval();
				// Si el método eliminar es exitoso, se establece el error en falso, se envía un mensaje y se lista el contenido actualizado.
				if($db->eliminar($_POST['id'])){
					$response['error'] = false;
					$response['message'] = 'Criterio de evaluación eliminado';
					$response['contenido'] = $db->listar();
				}else{
					// Si el método eliminar no es exitoso, se establece el error en verdadero y se envía un mensaje de error.
					$response['error'] = true;
					$response['message'] = 'El criterio de evaluación no fue eliminado';
				}
			}
		break;

		// Caso para guardar una calificación.
		//ROL: DOCENTE
        case 'guardarCalificacion':
            verificadoDeParametros(array('idEstudiante', 'idCriterio', 'calificacion'));
            $db = new ACalificacion();
            $result = $db->guardarCalificacion($_POST['idEstudiante'], $_POST['idCriterio'], $_POST['calificacion']);
            if($result){
                $response['error'] = false;
                $response['message'] = 'Calificación guardada correctamente';
            }else{
                $response['error'] = true;
                $response['message'] = 'Ocurrió un error, intenta nuevamente';
            }
        break;


		// Caso para listar las calificaciones.
		//ROL: DOCENTE, ESTUDIANTE, ADMINISTRADOR
		case 'listarCalificaciones':
			// Crea una nueva instancia de la clase ACalificaciones.
			$db = new ACalificacion();
			// Lista las calificaciones y las almacena en la variable lista.
			$lista = $db->listar();
			// Se establece el error en falso, se envía un mensaje y se envía el contenido de la lista.
			$response['error'] = false;
			$response['message'] = 'Solicitud completada correctamente';
			$response['contenido'] = $lista;
		break;

		// Caso para editar las calificaciones.
		//ROL: DOCENTE
		case 'editarCalificaciones':
			verificadoDeParametros(array('idEstudiante', 'idCriterio', 'calificacion'));
			// Crea una nueva instancia de la clase ACalificaciones.
			$db = new ACalificacion();
			// Si el método editar es exitoso, se establece el error en falso, se envía un mensaje y se lista el contenido actualizado.
			$result = $db->editar($_POST['idEstudiante'], $_POST['idCriterio'], $_POST['calificacion'], $_POST['id']);
			if($result){
				$response['error'] = false;
				$response['message'] = 'Calificación editada correctamente';
				$response['contenido'] = $db->listar();
			}else{
				// Si el método editar no es exitoso, se establece el error en verdadero y se envía un mensaje de error.
				$response['error'] = true;
				$response['message'] = 'Ocurrió un error, intenta nuevamente';
			}
		break;

		// Caso para eliminar las calificaciones.
		//ROL: DOCENTE
		case 'eliminarCalificaciones':
			// Verifica si el id de la calificación se ha establecido y no está vacío.
			if(isset($_POST['id']) && !empty($_POST['id'])){
				// Crea una nueva instancia de la clase ACalificaciones.
				$db = new ACalificacion();
				// Si el método eliminar es exitoso, se establece el error en falso, se envía un mensaje y se lista el contenido actualizado.
				if($db->eliminar($_POST['id'])){
					$response['error'] = false;
					$response['message'] = 'Calificación eliminada';
					$response['contenido'] = $db->listar();
				}else{
					// Si el método eliminar no es exitoso, se establece el error en verdadero y se envía un mensaje de error.
					$response['error'] = true;
					$response['message'] = 'La calificación no fue eliminada';
				}
			}
		break;
		
		//ROL: Administrador
		 case 'buscarContraseniaAdmin':
        verificadoDeParametros(array('correo', 'contrasenia'));
        $db = new AAdmin();
        $datos = $db->buscarContraseniaAdmin($_POST['correo'], $_POST['contrasenia']);
        if ($datos != null) {
            $response['error'] = false;
            $response['message'] = 'Contraseña encontrada correctamente';
            $response['contenido'] = $datos;
        } else {
            $response['error'] = true;
            $response['message'] = 'No se encontró una contraseña que coincida';
        }
        break;

    case 'obtenerIdAdmin':
        verificadoDeParametros(array('correo'));
        $db = new AAdmin();
        $idAdmin = $db->obtenerIdAdmin($_POST['correo']);
        if ($idAdmin != -1) {
            $response['error'] = false;
            $response['message'] = 'ID del admin obtenido correctamente';
            $response['contenido'] = $idAdmin;
        } else {
            $response['error'] = true;
            $response['message'] = 'No se encontró un admin con ese correo';
        }
        break;

    case 'actualizarNombreAdmin':
        verificadoDeParametros(array('idAdmin', 'nombreNuevo', 'apellidoNuevo'));
        $db = new AAdmin();
        $result = $db->actualizarNombreAdmin($_POST['idAdmin'], $_POST['nombreNuevo'], $_POST['apellidoNuevo']);
        if ($result) {
            $response['error'] = false;
            $response['message'] = 'Nombre actualizado correctamente';
        } else {
            $response['error'] = true;
            $response['message'] = 'No se pudo actualizar el nombre';
        }
        break;

    case 'actualizarCorreoAdmin':
        verificadoDeParametros(array('correoAntiguo', 'correoNuevo'));
        $db = new AAdmin();
        $result = $db->actualizarCorreoAdmin($_POST['correoAntiguo'], $_POST['correoNuevo']);
        if ($result) {
            $response['error'] = false;
            $response['message'] = 'Correo actualizado correctamente';
        } else {
            $response['error'] = true;
            $response['message'] = 'No se pudo actualizar el correo';
        }
        break;

    case 'actualizarPasswordAdmin':
        verificadoDeParametros(array('idAdmin', 'passwordAntiguo', 'passwordNuevo'));
        $db = new AAdmin();
        $result = $db->actualizarPasswordAdmin($_POST['idAdmin'], $_POST['passwordAntiguo'], $_POST['passwordNuevo']);
        if ($result) {
            $response['error'] = false;
            $response['message'] = 'Contraseña actualizada correctamente';
        } else {
            $response['error'] = true;
            $response['message'] = 'No se pudo actualizar la contraseña';
        }
        break;
		}


	}else{
		//si no es un api el que se esta invocando
		//empujar los valores apropiados en la estructura json
		$response['error'] = true;
		$response['message'] = 'No se llamo a Apicall';
	}

echo json_encode($response);

?>