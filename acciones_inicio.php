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

$estatus = $_POST["estatus"];

//FUNCION PARA AGREGAR NUEVO SERVICIO

    if($accion == 'nuevoServicio'){
        //USUARIOS MT 123, 204, 310, 329, 397, 414, 415, 424, 432, 440, 464, 472, 485, 490, 509, 442, 472,532
        $mt_users = array(123, 204, 310, 329, 397, 414, 415, 424, 432, 440, 464, 472, 485, 490, 509, 442, 532);

        if (in_array($id_usuario, $mt_users)) {
            $sqlNuevoServicio = "INSERT INTO servicio (id_usuario, tipo_s, ov, ot, estatus, fecha_creacion, area, autoriza_jefe, id_ot, comentarios, autoriza_gerencia) 
                            VALUES ('$id_usuario', '$tipo_servicio', '$ov', '$ot', 'En proceso','$fecha_inicio', '$area', 'Por Autorizar', 0, '$comentarios', 'Autorizado')";
        } else {
            $sqlNuevoServicio = "INSERT INTO servicio (id_usuario, tipo_s, ov, ot, estatus, fecha_creacion, area, autoriza_jefe, id_ot, comentarios, autoriza_gerencia) 
                            VALUES ('$id_usuario', '$tipo_servicio', '$ov', '$ot', 'En proceso','$fecha_inicio', '$area', 'Por Autorizar', 0, '$comentarios', 'Por Autorizar')";
        }
        
                                                                                                                                                    
        $ResNuevoServicio = $conn->query($sqlNuevoServicio);
        
        $exito = array();
        if ($ResNuevoServicio) {
            $exito [] = array(1);
            echo json_encode(['success' => true]);
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
                                    autoriza_gerencia
                                    FROM servicio S
                                    WHERE S.id_usuario = $id_usuario AND autoriza_jefe = 'Por Autorizar' OR autoriza_gerencia = 'Por Autorizar'";
        }
        
        if($_COOKIE['rol'] == 2 || $_COOKIE['rol'] == 4){
            $area = $_COOKIE['area'];
            $sqlllenaTablaSinAuto = "SELECT S.id, S.ot, S.ov, S.tipo_s, S.autoriza_jefe, DATE_FORMAT(S.fecha_creacion, '%d/%m/%Y') AS fecha_creacion, S.comentarios, U.nombre as ingeniero,
                                    S.autoriza_gerencia
                                    FROM servicio S
                                    INNER JOIN usuarios U ON U.noEmpleado = S.id_usuario
                                    WHERE U.departamento = $area AND autoriza_jefe = 'Por Autorizar' OR autoriza_gerencia = 'Por Autorizar'";
        }
        if($_COOKIE['rol'] == 3 && $_COOKIE['noEmpleado'] == 521){
            $area = $_COOKIE['area'];
            $sqlllenaTablaSinAuto = "SELECT S.id, S.ot, S.ov, S.tipo_s, S.autoriza_jefe, DATE_FORMAT(S.fecha_creacion, '%d/%m/%Y') AS fecha_creacion, S.comentarios, U.nombre as ingeniero,
                                    S.autoriza_gerencia
                                    FROM servicio S
                                    INNER JOIN usuarios U ON U.noEmpleado = S.id_usuario
                                    WHERE autoriza_jefe = 'Por Autorizar' OR autoriza_gerencia = 'Por Autorizar'";
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
                    'autoriza_gerencia' => $rowllenaTablaSinAuto["autoriza_gerencia"]
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
?>   