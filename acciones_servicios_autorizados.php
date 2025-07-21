
<?php
include 'conn.php';
//header('Content-Type: application/json');
date_default_timezone_set('America/Mexico_City');

//echo "entro";
$accion = $_POST["accion"];
$id_usuario = $_COOKIE['noEmpleado'];//$_POST["id_usuario"];

$tipo_servicio = $_POST["tipo_servicio"];
$tipo_actividad = $_POST["tipo_actividad"];
$comentarios = $_POST["comentarios"];
$coordenadas = $_POST["coordenadas"];
$departamento = $_POST["departamento"];
$ot = $_POST["ot"];
$ov = $_POST["ov"];
$fecha_inicio = date("Y-m-d H:i:s");

$idActividad = $_POST["idActividad"];
$idServicio = $_POST["idServicio"];

// FINALIZAR ACTIVIDAD
    if ($accion == 'finalizarActividad'){
        $coordenadas = mysqli_real_escape_string($conn, $coordenadas);
        $idActividad = (int) $idActividad;  // Asegurar que sea un entero
        
       // Consulta SQL para finalizar la actividad
        $sqlfinalizarActividad = "UPDATE tiempo_actividad 
                    SET fecha_fin = NOW(), duracion = TIMESTAMPDIFF(MINUTE, fecha_inicio, NOW())/60, 
                    coordenadas_fin = '$coordenadas', estatus = 'Finalizado'
                    WHERE id = $idActividad";
        
        // Ejecutar la consulta
        if (mysqli_query($conn, $sqlfinalizarActividad)) {
            // Para depuración, elimina este echo en producción
            echo json_encode(['success' => true]);
        } else {
            // En caso de error, devolver el mensaje de error específico
            echo json_encode(['error' => true, 'message' => mysqli_error($conn)]);
        }
    }

//DEVUELVE LOS DEPARTAMENTOS
    if($accion == "verDepto"){
        
        $Qdepartamentos = "SELECT * FROM area";
        $resDepartamento = $conn->query($Qdepartamentos);
        
        $departamento = array();
        while ($rowDepartamento = $resDepartamento->fetch_assoc()) {
            $departamento[] = array(
                'id' => $rowDepartamento["id"],
                'area' => $rowDepartamento["area"],
                'clave' => $rowDepartamento["clave"]
            );
        }
        
        echo json_encode($departamento);
    }

//VALIDAR ESTATUS
if ($accion == 'ValidarEstatus'){
        
        $sqlValidarEstatus ="SELECT ta.id_servicio, ta.id_usuario, ta.fecha_creacion, ta.tipo, s.tipo_s, s.ov, s.ot, ta.id as id_actividad, ta.estatus, s.estatus as estatusServ
                                FROM servicio s 
                                LEFT JOIN tiempo_actividad as ta ON ta.id_servicio = s.id
                                WHERE s.estatus = 'En proceso' 
                                AND ta.fecha_creacion = (
                                      SELECT MAX(fecha_creacion) 
                                      FROM tiempo_actividad 
                                      WHERE id_servicio = ta.id_servicio 
                                        AND id_usuario = ta.id_usuario
                                  )
                                AND s.id = $idServicio
                                GROUP BY ta.id_servicio, ta.id_usuario, ta.fecha_creacion, ta.tipo, s.tipo_s, s.ov, s.ot, ta.id, ta.estatus, s.estatus";

        $resultado = $conn->query($sqlValidarEstatus);
        $actividad = array();
        
        if ($resultado->num_rows > 0) {
            // Recorrer resultados
            while($rowActividad = $resultado->fetch_assoc()) {
                $actividad[] = array(
                    'fecha_creacion' => $rowActividad["fecha_creacion"],
                    'tipo' => $rowActividad["tipo"],
                    'ov' => $rowActividad["ov"],
                    'ot' => $rowActividad["ot"],
                    'tipo_s' => $rowActividad["tipo_s"],
                    'id_actividad' => $rowActividad["id_actividad"],
                    'id_servicio' => $rowActividad["id_servicio"],
                    'estatus' => $rowActividad["estatus"],
                    'estatusServ' => $rowActividad["estatusServ"]
                );
            }
            // Enviar el JSON
            echo json_encode($actividad);            
            
        } else {
            
            if($_COOKIE['rol'] == 1){
                $sqlValidarEstatuss ="SELECT s.id as id_servicio, s.id_usuario, '' as fecha_creacion, '' as tipo, s.tipo_s, s.ov, s.ot, '' as id_actividad, '' as estatus, s.estatus as estatusServ 
                FROM servicio s 
                WHERE s.estatus = 'En proceso' AND s.id_usuario = $id_usuario AND s.id = $idServicio";
            }
            
            //Se Agrego ROL 4 --14/02/2025--
            if($_COOKIE['rol'] == 2 || $_COOKIE['rol'] == 4){
                $sqlValidarEstatuss ="SELECT s.id as id_servicio, s.id_usuario, '' as fecha_creacion, '' as tipo, s.tipo_s, s.ov, s.ot, '' as id_actividad, '' as estatus, s.estatus as estatusServ 
                FROM servicio s 
                WHERE s.estatus = 'En proceso'  AND s.id = $idServicio";
            }
            
            $resultados = $conn->query($sqlValidarEstatuss);
            if ($resultados->num_rows > 0) {
                while($rowActividad = $resultados->fetch_assoc()) {
                $actividad[] = array(
                    'fecha_creacion' => $rowActividad["fecha_creacion"],
                    'tipo' => $rowActividad["tipo"],
                    'ov' => $rowActividad["ov"],
                    'ot' => $rowActividad["ot"],
                    'tipo_s' => $rowActividad["tipo_s"],
                    'id_actividad' => $rowActividad["id_actividad"],
                    'id_servicio' => $rowActividad["id_servicio"],
                    'estatus' => $rowActividad["estatus"],
                    'estatusServ' => $rowActividad["estatusServ"]
                );
            }
            // Enviar el JSON
            echo json_encode($actividad);
        }
    }
}

//Llenar Tabla Actividades
    if ($accion == 'llenaTablaActividades'){
        $sqlllenaTablaActividades = "SELECT tipo, DATE_FORMAT(fecha_inicio, '%d-%m-%Y %H:%i') as fecha_inicio, DATE_FORMAT(fecha_fin, '%d-%m-%Y %H:%i') as fecha_fin, duracion 
                                     FROM tiempo_actividad WHERE estatus = 'Finalizado'  AND id_servicio = $idServicio
                                     ORDER BY fecha_inicio DESC";
            
        $resllenaTablaActividades = $conn->query($sqlllenaTablaActividades);
        
        // Crear un array para almacenar los resultados
        $registros = [];
        while ($rowllenaTablaActividades = $resllenaTablaActividades->fetch_assoc()) {
            $registros[] = array(
                'tipo' => $rowllenaTablaActividades["tipo"],
                'fecha_inicio' => $rowllenaTablaActividades["fecha_inicio"],
                'fecha_fin' => $rowllenaTablaActividades["fecha_fin"],
                'duracion' => $rowllenaTablaActividades["duracion"]
            );
        }
        echo json_encode($registros);
    }

//LLENA TABLA SERVICIOS AUTORIZADOS
    if ($accion == 'verServiciosAutorizados'){
        
        if($_COOKIE['rol'] == 1){
            $sqlllenaTablaActividades = "SELECT S.fecha_creacion, S.ot, S.ov, S.estatus, S.id, U.nombre
                                        FROM servicio S
                                        INNER JOIN usuarios U ON U.noEmpleado = S.id_usuario
                                        WHERE S.id_usuario = $id_usuario AND S.estatus = 'En proceso' AND S.autoriza_jefe = 'Autorizado' AND S.autoriza_gerencia = 'Autorizado'";
        }
        if($_COOKIE['rol'] == 2 || $_COOKIE['rol'] == 4){
            $area = $_COOKIE['area'];
            $sqlllenaTablaActividades = "SELECT S.fecha_creacion, S.ot, S.ov, S.estatus, S.id, U.nombre
                                        FROM servicio S
                                        INNER JOIN usuarios U ON U.noEmpleado = S.id_usuario
                                        WHERE U.departamento = $area AND S.estatus = 'En proceso' AND S.autoriza_jefe = 'Autorizado' AND S.autoriza_gerencia = 'Autorizado'";
        }
        $resllenaTablaActividades = $conn->query($sqlllenaTablaActividades);
        
        $registros = [];
        while($rowllenaTablaActividades = $resllenaTablaActividades->fetch_assoc()) {
            $registros[] = array(
                'fecha_creacion' => $rowllenaTablaActividades["fecha_creacion"],
                'ot' => $rowllenaTablaActividades["ot"],
                'ov' => $rowllenaTablaActividades["ov"],
                'estatus' => $rowllenaTablaActividades["estatus"],
                'id' => $rowllenaTablaActividades["id"],
                'nombre' => $rowllenaTablaActividades["nombre"]
            );
        }
        echo json_encode($registros);
    }    
    
//Nueva Actividad
    if($accion == 'ActividadNueva'){
        
        $sqlActividadNueva = "INSERT INTO tiempo_actividad
                                  (id_servicio, id_usuario, tipo, dia, fecha_creacion, fecha_inicio, fecha_fin, duracion, comentarios, estatus_jefe, estatus_gral,coordenadas_inicio, coordenadas_fin, estatus) 
                            VALUES($idServicio, $id_usuario, '$tipo_actividad','0',NOW(),NOW(),null, '0', '$comentarios','Por autorizar','Por autorizar','$coordenadas', '0', 'Proceso')";
        // Ejecutar la consulta
        //echo $sqlActividadNueva;
        if ($conn->query($sqlActividadNueva)) {
            // Para depuración, elimina este echo en producción
            echo json_encode(['success' => true]);
        } else {
            // En caso de error, devolver el mensaje de error específico
            echo json_encode(['error' => true, 'message' => mysqli_error($conn)]);
        }
    }
    
//Cerrar Servicio
    if($accion == 'cerrarServicio'){
        $coordenadas = mysqli_real_escape_string($conn, $coordenadas);
        
        // Consulta SQL para finalizar SERVICIO
        $sqlfinalizarServicio = "UPDATE servicio SET estatus = 'cerrado' WHERE id = $idServicio";
        
        
       // Consulta SQL para finalizar la ACTIVIDAD
        $sqlfinalizarActividad = "UPDATE tiempo_actividad 
                    SET fecha_fin = NOW(), duracion = TIMESTAMPDIFF(MINUTE, fecha_inicio, NOW())/60, 
                    coordenadas_fin = '$coordenadas', estatus = 'Finalizado'
                    WHERE id_usuario = $id_usuario AND estatus = 'Proceso' AND id_servicio = $idServicio";
        
        // Ejecutar la consulta
             
        if ($conn->query($sqlfinalizarServicio)) {
            // Para depuración, elimina este echo en producción
            if ($conn->query($sqlfinalizarActividad)) {
                echo json_encode(['success' => true]);
            }
            else{
                echo "fallo actividad";
            }
        } else {
            // En caso de error, devolver el mensaje de error específico
            echo "fallo servicio";
            echo json_encode(['error' => true, 'message' => mysqli_error($conn)]);
        }
    }
    
?>   