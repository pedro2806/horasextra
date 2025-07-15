<?php
include 'conn.php';
date_default_timezone_set('America/Mexico_City');
header('Content-Type: application/json; charset=utf-8');

$accion = $_POST["accion"];

$correo =$_POST["correo"];
$password = $_POST["password"];

$token = $_POST['token']; 

/*----------------------------------------------------------------------------*/

// Validar Correo

    /*if ($accion == "CorreoRecuperacion") {
        
        $sqlCorreo = "SELECT usuario, password FROM usuarios WHERE correo = '$correo'";
        $resultCorreo = $conn->query($sqlCorreo);
        
        if ($resultCorreo->num_rows > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => false]);
        }
    }*/

// Cambiar Contraseña
    /*if ($accion == "CambiarPassword") {

        $sqlPassword = "UPDATE usuarios SET password = '$password' WHERE usuario = '$correo'";
    
        if ($conn->query($sqlPassword)) {
            echo json_encode(['status' => 'success', 'message' => 'Contraseña actualizada']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar la contraseña']);
        }
    }*/
    
//Cambiar Contraseña con Token
    if ($accion == "CambiarPassword") {
        //Valida Token y Correo
        $stmt = $conn->prepare("SELECT id
                                FROM usuarios
                                WHERE token = ? AND correo = ? AND estatus_recu_pass = 'Autorizada'");
        $stmt->bind_param("ss", $token, $correo);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id_usuario = $row['id'];
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
           
            //Actualiza Contraseña
            $stmt = $conn->prepare("UPDATE usuarios 
                                    SET password = ?, estatus_recu_pass = 'Usado'
                                    WHERE id = ? AND token = ?");
            $stmt->bind_param("sis", $password, $id_usuario, $token);
            $stmt->execute();
            
            echo json_encode(['success' => true]);
            
        } else {
            
            //echo json_encode(['error' => true]);
                
        }
    }
    
//Enviar Token Correo
    if ($accion == "CorreoRecuperacion") {
        
        $sqlCorreo = "SELECT id, usuario FROM usuarios WHERE correo = '$correo'";
        $resultCorreo = $conn->query($sqlCorreo);
        
        if ($resultCorreo->num_rows > 0) {
            echo json_encode(['success' => true, 'correo' => $correo]);
        }else{
            echo json_encode(['success' => false]);
        }
    }
?>