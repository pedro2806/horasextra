<?php
// controlador_horas_extra.php

header('Content-Type: application/json; charset=utf-8');
require_once 'conn.php'; 

$accion = $_POST['accion'] ?? '';

switch ($accion) {

    // PESTAÑA 1: POR VALIDAR (Tu versión correcta)
    case 'listar_proceso':
        $sql = "SELECT h.id, u.nombre AS usuario, h.fecha_inicio, h.duracion, h.comentarios, u.noEmpleado,
                        (SELECT GROUP_CONCAT(CONCAT(ot, '- Area: ', area) SEPARATOR ', ') FROM servicio_ots WHERE id_servicio = h.id_servicio) AS ot_desglose
                FROM tiempo_actividad h
                INNER JOIN usuarios u ON u.noEmpleado = h.id_usuario
                WHERE h.estatus = 'Finalizado' AND h.estatus_gral = 'Por Autorizar'
                ORDER BY h.fecha_inicio DESC";
        
        $result = $conn->query($sql);
        if ($result) {
            $data = [];
            while ($row = $result->fetch_assoc()) { $data[] = $row; }
            echo json_encode(["data" => $data]);
        } else { echo json_encode(["error" => $conn->error]); }
        break;

    // PESTAÑA 2: AUTORIZADAS
    case 'listar_autorizados':
        $sql = "SELECT h.id, u.nombre AS usuario, h.fecha_inicio, h.duracion, h.comentarios, u.noEmpleado,
                            (SELECT GROUP_CONCAT(CONCAT(ot, '- Area: ', area) SEPARATOR ', ') FROM servicio_ots WHERE id_servicio = h.id_servicio) AS ot_desglose
                FROM tiempo_actividad h
                INNER JOIN usuarios u ON u.noEmpleado = h.id_usuario
                WHERE h.estatus_gral = 'Autorizado'
                ORDER BY h.fecha_inicio DESC";
        
        $result = $conn->query($sql);
        if ($result) {
            $data = [];
            while ($row = $result->fetch_assoc()) { $data[] = $row; }
            echo json_encode(["data" => $data]);
        } else { echo json_encode(["error" => $conn->error]); }
        break;

    // PESTAÑA 3: NUEVO ESTATUS "POR PAGAR" (CONTROL NÓMINA)
    case 'listar_por_pagar':
        $sql = "SELECT h.id, u.nombre AS usuario, h.fecha_inicio, h.duracion, h.comentarios, u.noEmpleado,
                            (SELECT GROUP_CONCAT(CONCAT(ot, '- Area: ', area) SEPARATOR ', ') FROM servicio_ots WHERE id_servicio = h.id_servicio) AS ot_desglose
                FROM tiempo_actividad h
                INNER JOIN usuarios u ON u.noEmpleado = h.id_usuario
                WHERE h.estatus_gral = 'Por Pagar'
                ORDER BY h.fecha_inicio DESC";
        
        $result = $conn->query($sql);
        if ($result) {
            $data = [];
            while ($row = $result->fetch_assoc()) { $data[] = $row; }
            echo json_encode(["data" => $data]);
        } else { echo json_encode(["error" => $conn->error]); }
        break;

    // PESTAÑA 4: HISTORIAL PAGADOS
    case 'listar_pagados':
        $sql = "SELECT h.id, u.nombre AS usuario, h.fecha_inicio, h.duracion, h.comentarios, h.estatus_gral, u.noEmpleado,
                            (SELECT GROUP_CONCAT(CONCAT(ot, '- Area: ', area) SEPARATOR ', ') FROM servicio_ots WHERE id_servicio = h.id_servicio) AS ot_desglose
                FROM tiempo_actividad h
                INNER JOIN usuarios u ON u.noEmpleado = h.id_usuario
                WHERE h.estatus_gral = 'Pagado'
                ORDER BY h.fecha_inicio DESC";
        
        $result = $conn->query($sql);
        if ($result) {
            $data = [];
            while ($row = $result->fetch_assoc()) { $data[] = $row; }
            echo json_encode(["data" => $data]);
        } else { echo json_encode(["error" => $conn->error]); }
        break;

    // LOGICA TRANSICIONAL DE FLUJO (BLINDADA)
    case 'cambiar_estatus':
        $id = intval($_POST['id'] ?? 0);
        $nuevo_estatus = $_POST['nuevo_estatus'] ?? '';

        if ($id <= 0 || !in_array($nuevo_estatus, ['Autorizado', 'Por Pagar', 'Pagado'])) {
            echo json_encode(["success" => false, "message" => "Parámetros de flujo incorrectos."]);
            exit;
        }

        // Control estricto del Pipeline secuencial
        if ($nuevo_estatus === 'Autorizado') {
            $sql = "UPDATE tiempo_actividad SET estatus_gral = 'Autorizado' 
                    WHERE id = ? AND estatus = 'Finalizado' AND estatus_gral = 'Por Autorizar'";
        } else if ($nuevo_estatus === 'Por Pagar') {
            // Pasa a lista de nómina desde Autorizado
            $sql = "UPDATE tiempo_actividad SET estatus_gral = 'Por Pagar' 
                    WHERE id = ? AND estatus_gral = 'Autorizado'";
        } else if ($nuevo_estatus === 'Pagado') {
            // Solo se puede finiquitar lo que ya pasó por la lista de nómina
            $sql = "UPDATE tiempo_actividad SET estatus_gral = 'Pagado' 
                    WHERE id = ? AND estatus_gral = 'Por Pagar'";
        }

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(["success" => true, "message" => "Registro movido a '$nuevo_estatus' con éxito."]);
            } else {
                echo json_encode(["success" => false, "message" => "No se aplicaron cambios. Verifica la secuencia lógica."]);
            }
            $stmt->close();
        } else {
            echo json_encode(["success" => false, "message" => "Error SQL: " . $conn->error]);
        }
        break;

    default:
        echo json_encode(["error" => "Acción no válida."]);
        break;
}

$conn->close();