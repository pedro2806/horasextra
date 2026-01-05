<?php
include 'conn.php';

$accion = isset($_POST['accion']) ? $_POST['accion'] : '';

// Validación de usuario (otro caso)
$id_usuario = isset($_POST['id_usuarioHR']) ? $_POST['id_usuarioHR'] : '';
$nombredelusuario = isset($_POST['nombredelusuarioHR']) ? $_POST['nombredelusuarioHR'] : '';
$noEmpleado = isset($_POST['noEmpleadoHR']) ? $_POST['noEmpleadoHR'] : '';
$rol = isset($_POST['rolHR']) ? $_POST['rolHR'] : '';
$usuario = isset($_POST['correoHR']) ? $_POST['correoHR'] : '';


    $Qempresas  =  "SELECT  *, TIMESTAMPDIFF(YEAR,fechaIngreso,CURDATE()) AS antiguedad, rol, departamento FROM usuarios WHERE usuario  = '".$usuario."' AND estatus = 1";
    $res2 =  mysqli_query( $conn, $Qempresas ) or die (mysqli_error($conn));
    $nr = mysqli_num_rows($res2);

    while ($row2 = mysqli_fetch_array($res2)){
        $id_usuario = $row2["id"];
        $nombreEmpleado = $row2["nombre"];
        $noEmpleado = $row2["noEmpleado"];
        $antiguedad = $row2["antiguedad"];
        $diasD = $row2["diasdisponibles"];
        $rol = $row2["rol"];
        $area = $row2["departamento"];
    }

    if($nr == 1){
        
        echo '<script>document.cookie = "nombre='.$nombreEmpleado.';expires=" + new Date(Date.now() + 99900000).toUTCString() + ";SameSite=Lax;";</script>';
        echo '<script>document.cookie = "region='.$region.';expires=" + new Date(Date.now() + 99900000).toUTCString() + ";SameSite=Lax;";</script>';
        echo '<script>document.cookie = "noEmpleado='.$noEmpleado.';expires=" + new Date(Date.now() + 99900000).toUTCString() + ";SameSite=Lax;";</script>';
        echo '<script>document.cookie = "area='.$area.';expires=" + new Date(Date.now() + 99900000).toUTCString() + ";SameSite=Lax;";</script>';
        echo '<script>document.cookie = "rol='.$rol.';expires=" + new Date(Date.now() + 99900000).toUTCString() + ";SameSite=Lax;";</script>';
        echo '<script>document.cookie = "SesionLogin=LoginMaster; expires=" + new Date(Date.now() + 99900000).toUTCString() + ";SameSite=Lax;";</script>';
        echo '<script>window.location.assign("inicio")</script>';

        session_start();
        $_SESSION['nombredelusuario'] = $nombreEmpleado;
        $_SESSION['noEmpleado'] = $noEmpleado;
        $_SESSION['rol'] = $rol;
        $_SESSION['correo'] = $usuario;
        $_SESSION['id_usuario'] = $id_usuario;

        echo json_encode(['success' => true]);
        exit;
    }

    // Si no hay usuario válido
    echo json_encode([
        'success' => false,
        'message' => 'Usuario no válido.',
    ]);
    exit;

?>
