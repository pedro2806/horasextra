<?php
include 'conn.php';

//header('Content-Type: application/json');
date_default_timezone_set('America/Mexico_City');

$accion = $_POST["accion"];
$id_usuario = $_COOKIE['noEmpleado'];

$tipo_servicio = $_POST["tipo_servicio"];
$tipo_actividad = $_POST["tipo_actividad"];
$comentarios = $_POST["comentarios"];
$coordenadas = $_POST["coordenadas"];
$area = $_POST["area"];
$ot = $_POST["ot"];
$ov = $_POST["ov"];
$fecha_inicio = date("Y-m-d H:i:s");
$autoriza_jefe = $_POST["autoriza_jefe"];
$id_ot = $_POST["id_ot"];

$idActividad = $_POST["idActividad"];
$idServicio = $_POST["idServicio"];

$fechaEjecucion = $_POST["fecha_ejecucion"];

$estatus = $_POST["estatus"];

//FUNCION PARA AGREGAR NUEVO SERVICIO

if($accion == 'nuevoServicio'){

    $cuantos = $_POST["cuantos"];
    $inf_adicional = $_POST["inf_adicional"];
    // USUARIOS MT 
    $mt_users = explode(",", $inf_adicional);
    $mt_users = array_map('trim', $mt_users);

    // [NUEVO] Recibimos los arreglos dinámicos desde el formulario
    $areas_dinamicas   = isset($_POST['area']) ? $_POST['area'] : [];
    $ots_dinamicas     = isset($_POST['ot']) ? $_POST['ot'] : [];
    $tiempos_dinamicos = isset($_POST['tiempo']) ? $_POST['tiempo'] : [];

    // Mantenemos la lógica de la primera fila o primer registro para la tabla vieja "servicio"
    $area_principal   = isset($areas_dinamicas[0]) ? $areas_dinamicas[0] : '';
    $ot_principal     = isset($ots_dinamicas[0]) ? $ots_dinamicas[0] : '';
    $tiempo_principal = isset($tiempos_dinamicos[0]) ? $tiempos_dinamicos[0] : 0;

    // TUS INSERTS ORIGINALES (Se quedan exactamente igual, usando las variables del primer elemento)
    if (in_array($id_usuario, $mt_users)) {
        $sqlNuevoServicio = "INSERT INTO servicio (id_usuario, tipo_s, ov, ot, estatus, fecha_creacion, area, autoriza_jefe, id_ot, comentarios, autoriza_gerencia, fecha_ejecucion ) 
                        VALUES ('$id_usuario', '$tipo_servicio', '$ov', '$ot_principal', 'En proceso','$fecha_inicio', '$area_principal', 'Por Autorizar', 0, '$comentarios', 'Autorizado', '$fechaEjecucion')";
    } else {
        $sqlNuevoServicio = "INSERT INTO servicio (id_usuario, tipo_s, ov, ot, estatus, fecha_creacion, area, autoriza_jefe, id_ot, comentarios, autoriza_gerencia, fecha_ejecucion ) 
                        VALUES ('$id_usuario', '$tipo_servicio', '$ov', '$ot_principal', 'En proceso','$fecha_inicio', '$area_principal', 'Por Autorizar', 0, '$comentarios', 'Por Autorizar', '$fechaEjecucion')";
    }
                                                                                                                                                                                
    $ResNuevoServicio = $conn->query($sqlNuevoServicio);
    
    if ($ResNuevoServicio) {
        // [NUEVO] Obtenemos el ID del servicio recién creado para asociarlo
        $id_servicio_recien_creado = $conn->insert_id;

        // [NUEVO] Iteramos e insertamos los registros en tu nueva tabla "servicio_ots"
        $total_items = count($ots_dinamicas);
        $error_detalle = false;

        for ($i = 0; $i < $total_items; $i++) {
            $a_dinamica  = $conn->real_escape_string($areas_dinamicas[$i]);
            $o_dinamica  = $conn->real_escape_string($ots_dinamicas[$i]);
            $t_dinamica  = floatval($tiempos_dinamicos[$i]); // hrs es decimal

            // Cambia 'id_servicio' si en tu tabla el campo relacional tiene otro nombre
            $sqlDetalle = "INSERT INTO servicio_ots (id_servicio, area, ot, hrs) 
                            VALUES ('$id_servicio_recien_creado', '$a_dinamica', '$o_dinamica', '$t_dinamica')";
            
            if (!$conn->query($sqlDetalle)) {
                $error_detalle = true;
            }
        }

        if (!$error_detalle) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => true, 'warning' => 'Servicio creado pero con errores en el desglose de OTs']);
        }
    }
    else {
        echo $sqlNuevoServicio;
        echo json_encode(['error' => false]);
    }
}

//DEVUELVE LOS DEPARTAMENTOS

    if($accion == "verDepto"){
        
        $Qdepartamentos = "SELECT * FROM area";
        $resArea = $conn->query($Qdepartamentos);
        
        $area = array();
        while ($rowArea = $resArea->fetch_assoc()) {
            $area[] = array(
                'id' => $rowArea["id"],
                'area' => $rowArea["area"],
                'clave' => $rowArea["clave"]
            );
        }
        echo json_encode($area);
    }
    
//Llenar tabla Sin Autorizar
    
    if ($accion == 'llenaTablaSinAuto'){

        if($_COOKIE['rol'] == 1){
            $sqlllenaTablaSinAuto = "SELECT id, ot, ov, tipo_s, autoriza_jefe, DATE_FORMAT(fecha_creacion, '%d/%m/%Y') AS fecha_creacion, comentarios, (SELECT nombre FROM usuarios WHERE noEmpleado = S.id_usuario) as ingeniero,
                                    autoriza_gerencia, (SELECT GROUP_CONCAT(CONCAT(ot, '- Hrs: ', hrs) SEPARATOR ', ') FROM servicio_ots WHERE id_servicio = S.id) AS ot_desglose
                                    FROM servicio S
                                    WHERE S.id_usuario = $id_usuario AND autoriza_jefe = 'Por Autorizar' OR autoriza_gerencia = 'Por Autorizar' ORDER BY S.fecha_creacion ASC";
        }
        
        if($_COOKIE['rol'] == 2 || $_COOKIE['rol'] == 4){
            $area = $_COOKIE['area'];
            $sqlllenaTablaSinAuto = "SELECT S.id, S.ot, S.ov, S.tipo_s, S.autoriza_jefe, DATE_FORMAT(S.fecha_creacion, '%d/%m/%Y') AS fecha_creacion, S.comentarios, U.nombre as ingeniero,
                                    S.autoriza_gerencia, (SELECT GROUP_CONCAT(CONCAT(ot, '- Hrs: ', hrs) SEPARATOR ', ') FROM servicio_ots WHERE id_servicio = S.id) AS   ot_desglose    
                                    FROM servicio S
                                    INNER JOIN usuarios U ON U.noEmpleado = S.id_usuario
                                    WHERE U.departamento = $area AND autoriza_jefe = 'Por Autorizar' OR autoriza_gerencia = 'Por Autorizar' ORDER BY S.fecha_creacion ASC";
        }
        if($_COOKIE['rol'] == 3 && $_COOKIE['noEmpleado'] == 521){
            $area = $_COOKIE['area'];
            $sqlllenaTablaSinAuto = "SELECT S.id, S.ot, S.ov, S.tipo_s, S.autoriza_jefe, DATE_FORMAT(S.fecha_creacion, '%d/%m/%Y') AS fecha_creacion, S.comentarios, U.nombre as ingeniero,
                                    S.autoriza_gerencia, (SELECT GROUP_CONCAT(CONCAT(ot, '- Hrs: ', hrs) SEPARATOR ', ') FROM servicio_ots WHERE id_servicio = S.id) AS ot_desglose        
                                    FROM servicio S
                                    INNER JOIN usuarios U ON U.noEmpleado = S.id_usuario
                                    WHERE autoriza_jefe = 'Por Autorizar' OR autoriza_gerencia = 'Por Autorizar' ORDER BY S.fecha_creacion ASC";
        }
        
        $resllenaTablaSinAuto = $conn->query($sqlllenaTablaSinAuto);
        
        //$registros2 = [];
        if ($resllenaTablaSinAuto->num_rows > 0) {
            while ($rowllenaTablaSinAuto = $resllenaTablaSinAuto->fetch_assoc()) {
                $registros2[] = array(
                    'id' => $rowllenaTablaSinAuto["id"],
                    'ot' => $rowllenaTablaSinAuto["ot"],
                    'ov' => $rowllenaTablaSinAuto["ov"],
                    'tipo_s' => $rowllenaTablaSinAuto["tipo_s"],
                    'autoriza_jefe' => $rowllenaTablaSinAuto["autoriza_jefe"],
                    'fecha_creacion' => $rowllenaTablaSinAuto["fecha_creacion"],
                    'comentarios' => $rowllenaTablaSinAuto["comentarios"],
                    'ingeniero' => $rowllenaTablaSinAuto["ingeniero"],
                    'autoriza_gerencia' => $rowllenaTablaSinAuto["autoriza_gerencia"],
                    'ots' => $rowllenaTablaSinAuto["ot_desglose"]   
                );
            }
            echo json_encode($registros2);
        }  
    }
    
//AUTORIZAR SERVICIO (JEFE)
    
    if ($accion == 'autorizarServicio'){
        
        $sqlAutorizaServicio = "UPDATE servicio 
                                SET autoriza_jefe = '$estatus'
                                WHERE id = $idServicio";
        
        $resAutorizaServicio = $conn->query($sqlAutorizaServicio);
    }

    //AUTORIZAR SERVICIO (JEFE)
    
    if ($accion == 'autorizarServicioG'){
        
        $sqlAutorizaServicio = "UPDATE servicio 
                                SET autoriza_gerencia = '$estatus'
                                WHERE id = $idServicio";
        
        $resAutorizaServicio = $conn->query($sqlAutorizaServicio);
    }

    //GUARDAR CAMBIOS SERVICIO
    if ($accion == 'guardarCambios'){
        $nuevaFechaEjecucion = $_POST["nuevaFecha"];
        $idServicio = $_POST["idServicio"];
        $sqlGuardarCambios = "UPDATE servicio 
                                SET 
                                fecha_ejecucion = '$nuevaFechaEjecucion'
                                WHERE id = $idServicio";
        
        $resGuardarCambios = $conn->query($sqlGuardarCambios);

        if (!$conn->query($sqlGuardarCambios)) {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        } else {
            echo json_encode(['success' => true]);
        }
    }

    if ($accion == 'cancelarServicio') {
        $sqlCancelarServicio = "UPDATE servicio 
                                SET estatus = 'Cancelado'
                                WHERE id = $idServicio";
        
        $resCancelarServicio = $conn->query($sqlCancelarServicio);

        if (!$conn->query($sqlCancelarServicio)) {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        } else {
            echo json_encode(['success' => true]);
        }
    }
?>   