
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
            // Para depuración, elimina este echo en producción
            echo json_encode(['success' => true]);
        } else {
            // En caso de error, devolver el mensaje de error específico
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

    if ($accion == 'verServiciosTodos'){
        $ingeniero = isset($_POST['ing']) ? $_POST['ing'] : '';

        $sqlllenaTablaActividades = "SELECT s.*, u.nombre, (SELECT ROUND(SUM(duracion), 2) FROM tiempo_actividad where id_servicio = s.id) AS tiempo,
                                    (SELECT descDatamart FROM area WHERE clave = s.area ) as areaD
                                    FROM servicio s 
                                    INNER JOIN usuarios u ON s.id_usuario = u.noEmpleado
                                    WHERE s.estatus = 'cerrado' AND s.fecha_creacion >= DATE_SUB(NOW(), INTERVAL 2 MONTH)";
        $whereClauses = [];
        $params = [];
        $param_types = "";       // Inicialmente vacío, solo se agrega si hay parámetros
        
    // Manejo del ingeniero
        if (!empty($ingeniero)) {
            $whereClauses[] = "u.nombre LIKE ?";
            $params[] = "%" . $ingeniero . "%"; // Añade comodines para LIKE
            $param_types .= "s"; // El ingeniero es un string
        }

        if (!empty($whereClauses)) {
            $sqlllenaTablaActividades .= " AND " . implode(' AND ', $whereClauses);
        }

        //echo $sqlllenaTablaActividades.'-'.$params;
        // Preparar la consulta
        if ($stmt = $conn->prepare($sqlllenaTablaActividades)) {
            // Enlazar los parámetros dinámicamente solo si existen
            if (!empty($params)) {
                $stmt->bind_param($param_types, ...$params);
            }

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener el resultado
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $registros = [];
                while ($row = $result->fetch_assoc()) {
                    $registros[] = $row;
                }
                echo json_encode($registros);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se encontraron actividades planeadas o error en la consulta.']);
            }

            $stmt->close(); // Cerrar el statement
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al preparar la consulta: ' . $conn->error]);
        }
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
    
    if ($accion == 'llenaTodasActividades'){
        $sqlllenaTablaActividadesAuto = "SELECT id, id_servicio, tipo, DATE_FORMAT(fecha_inicio, '%d-%m-%y %H:%i') as fecha_inicio, DATE_FORMAT(fecha_fin, '%d-%m-%y %H:%i') as fecha_fin, duracion, comentarios
                                            FROM tiempo_actividad WHERE estatus = 'Finalizado'  AND id_servicio = $idServicio
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
                'id_servicio' => $rowlllenaTablaActividadesAuto["id_servicio"],
                'comentarios' => $rowlllenaTablaActividadesAuto["comentarios"]
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
                // Para depuraci��n, elimina este echo en producci��n
                echo json_encode(['success' => true]);
            } else {
                // En caso de error, devolver el mensaje de error espec��fico
                echo json_encode(['error' => true, 'message' => mysqli_error($conn)]);
            }
    }
    
    if ($accion == 'validarActividad'){
            $sqlvalidarActividad = "UPDATE tiempo_actividad 
                        SET estatus_gral = 'Autorizado'
                        WHERE id = $idActividad";
            
            // Ejecutar la consulta
            if ($resultados = $conn->query($sqlvalidarActividad)) {
                // Para depuraci��n, elimina este echo en producci��n
                echo json_encode(['success' => true]);
            } else {
                // En caso de error, devolver el mensaje de error espec��fico
                echo json_encode(['error' => true, 'message' => mysqli_error($conn)]);
            }
    }    
?>   