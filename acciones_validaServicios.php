
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
        if ($conn->query($sqlfinalizarActividad)) {
            // Para depuraci籀n, elimina este echo en producci籀n
            echo json_encode(['success' => true]);
        } else {
            // En caso de error, devolver el mensaje de error espec穩fico
            echo json_encode(['error' => true, 'message' => mysqli_error($conn)]);
        }
    }

//LLENA TABLA SERVICIOS AUTORIZADOS
    if ($accion == 'verServiciosPorValidar'){
        $sqlllenaTablaActividades = "SELECT s.*, u.nombre, (SELECT ROUND(SUM(duracion), 2) FROM tiempo_actividad where id_servicio = s.id) AS tiempo,
                                    (SELECT descDatamart FROM area WHERE clave = s.area ) as areaD
                                    FROM servicio s 
                                    INNER JOIN usuarios u ON s.id_usuario = u.noEmpleado
                                    WHERE s.estatus = 'cerrado' 
                                    AND (SELECT COUNT(*) FROM tiempo_actividad WHERE id_servicio = s.id AND estatus_gral = 'Por autorizar') > 0";
                                    
        $resllenaTablaActividades = $conn->query($sqlllenaTablaActividades);
        
        $registros = [];
            
        while ($rowllenaTablaActividades = $resllenaTablaActividades->fetch_assoc()) {
            $registros[] = array(
                'fecha_creacion' => $rowllenaTablaActividades["fecha_creacion"],
                'ot' => $rowllenaTablaActividades["ot"],
                'ov' => $rowllenaTablaActividades["ov"],
                'estatus' => $rowllenaTablaActividades["estatus"],
                'id' => $rowllenaTablaActividades["id"],
                'nombre' => $rowllenaTablaActividades["nombre"],
                'tiempo' => $rowllenaTablaActividades["tiempo"],
                'area' => $rowllenaTablaActividades["area"],
                'areaD' => $rowllenaTablaActividades["areaD"]
            );
        }
        echo json_encode($registros);
    }
    
//LLENA TABLA SERVICIOS AUTORIZADOS
       
    if ($accion == 'verServiciosValidados'){
        
        $sqlllenaTablaActividades = "SELECT s.*, u.nombre, (SELECT ROUND(SUM(duracion), 2) FROM tiempo_actividad where id_servicio = s.id AND estatus_gral = 'Autorizado') AS tiempo,
        (SELECT descDatamart FROM area WHERE clave = s.area ) as areaD
                                    FROM servicio s 
                                    INNER JOIN usuarios u ON s.id_usuario = u.noEmpleado
                                    WHERE s.estatus = 'cerrado' 
                                    AND (SELECT COUNT(*) FROM tiempo_actividad WHERE id_servicio = s.id AND estatus_gral = 'Autorizado') > 0
                                    AND NOT EXISTS (SELECT 1 FROM tiempo_actividad WHERE id_servicio = s.id AND estatus_gral = 'por autorizar')";
        
        $resllenaTablaActividades = $conn->query($sqlllenaTablaActividades);
        
        $registros = [];
        while ($rowllenaTablaActividades = $resllenaTablaActividades->fetch_assoc()) {
            $registros[] = array(
                'fecha_creacion' => $rowllenaTablaActividades["fecha_creacion"],
                'ot' => $rowllenaTablaActividades["ot"],
                'ov' => $rowllenaTablaActividades["ov"],
                'estatus' => $rowllenaTablaActividades["estatus"],
                'id' => $rowllenaTablaActividades["id"],
                'nombre' => $rowllenaTablaActividades["nombre"],
                'tiempo' => $rowllenaTablaActividades["tiempo"],
                'area' => $rowllenaTablaActividades["area"],
                'areaD' => $rowllenaTablaActividades["areaD"]
            );
        }
        echo json_encode($registros);
    } 
    
//Llenar Tabla Actividades
    if ($accion == 'llenaTablaActividades'){
        $sqlllenaTablaActividades = "SELECT id, id_servicio, tipo, DATE_FORMAT(fecha_inicio, '%d-%m-%y %H:%i') as fecha_inicio, DATE_FORMAT(fecha_fin, '%d-%m-%y %H:%i') as fecha_fin, duracion, comentarios
                                     FROM tiempo_actividad WHERE estatus = 'Finalizado'  AND id_servicio = $idServicio AND estatus_gral = 'Por autorizar'
                                     ORDER BY fecha_inicio DESC";
            
        $resllenaTablaActividades = mysqli_query($conn, $sqlllenaTablaActividades);
        
        // Crear un array para almacenar los resultados
        $registros = [];
        
        while ($rowllenaTablaActividades = $resllenaTablaActividades->fetch_assoc()) {
            $registros[] = array(
                'tipo' => $rowllenaTablaActividades["tipo"],
                'fecha_inicio' => $rowllenaTablaActividades["fecha_inicio"],
                'fecha_fin' => $rowllenaTablaActividades["fecha_fin"],
                'duracion' => $rowllenaTablaActividades["duracion"],
                'id' => $rowllenaTablaActividades["id"],
                'id_servicio' => $rowllenaTablaActividades["id_servicio"],
                'comentarios' => $rowllenaTablaActividades["comentarios"]
            );
        }
        echo json_encode($registros);
        
    }
    
    if ($accion == 'llenaTablaActividadesAuto'){
        $sqlllenaTablaActividadesAuto = "SELECT id, id_servicio, tipo, DATE_FORMAT(fecha_inicio, '%d-%m-%y %H:%i') as fecha_inicio, DATE_FORMAT(fecha_fin, '%d-%m-%y %H:%i') as fecha_fin, duracion 
                                     FROM tiempo_actividad WHERE estatus = 'Finalizado'  AND id_servicio = $idServicio AND estatus_gral = 'Autorizado'
                                     ORDER BY fecha_inicio DESC";
                                     
        $resllenaTablaActividadesAuto = $conn->query($sqlllenaTablaActividadesAuto);

        $registroAuto = [];
        while ($rowlllenaTablaActividadesAuto = $resllenaTablaActividadesAuto->fetch_assoc()) {
            $registroAuto[] = array(
                'tipo' => $rowlllenaTablaActividadesAuto["tipo"],
                'fecha_inicio' => $rowlllenaTablaActividadesAuto["fecha_inicio"],
                'fecha_fin' => $rowlllenaTablaActividadesAuto["fecha_fin"],
                'duracion' => $rowlllenaTablaActividadesAuto["duracion"],
                'id' => $rowlllenaTablaActividadesAuto["id"],
                'id_servicio' => $rowlllenaTablaActividadesAuto["id_servicio"]
            );
        }
        echo json_encode($registroAuto);
    }
    
    if ($accion == 'validarActividades'){
            $sqlvalidarActividades = "UPDATE tiempo_actividad 
                        SET estatus_gral = 'Autorizado'
                        WHERE id_servicio = $idServicio";
            
            // Ejecutar la consulta
            if ($resultados = $conn->query($sqlvalidarActividades)) {
                // Para depuraci車n, elimina este echo en producci車n
                echo json_encode(['success' => true]);
            } else {
                // En caso de error, devolver el mensaje de error espec赤fico
                echo json_encode(['error' => true, 'message' => mysqli_error($conn)]);
            }
    }
    
    if ($accion == 'validarActividad'){
            $sqlvalidarActividad = "UPDATE tiempo_actividad 
                        SET estatus_gral = 'Autorizado'
                        WHERE id = $idActividad";
            
            // Ejecutar la consulta
            if ($resultados = $conn->query($sqlvalidarActividad)) {
                // Para depuraci車n, elimina este echo en producci車n
                echo json_encode(['success' => true]);
            } else {
                // En caso de error, devolver el mensaje de error espec赤fico
                echo json_encode(['error' => true, 'message' => mysqli_error($conn)]);
            }
    }    
?>   