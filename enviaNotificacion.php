<?php
    include 'conn.php';
    $deAsunto="Aprovacion de Horas Extra";

    require("PHPMailer-master/src/PHPMailer.php");
    require("PHPMailer-master/src/SMTP.php");
    
    $id_usuario = $_COOKIE['noEmpleado'];
    $solicita = $_COOKIE['nombre'];
    
    $sqlCorreo = "SELECT correo 
                    FROM usuarios 
                    WHERE noEmpleado = (SELECT jefe FROM usuarios WHERE noEmpleado = '$id_usuario')";
                    //echo $sqlCorreo; 
    $resCorreo = $conn->query($sqlCorreo);
    
        while ($rowCorreo = $resCorreo->fetch_assoc()) {
                $correo = $rowCorreo["correo"];
        }
    
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    
    $mail->IsSMTP();
	
    $mail->SMTPDebug = 0; // PONER EN 0 SI NO QUIERES QUE SALGA EL LOG EN LA PANTALLA
                          //PONER EN 2 PARA DEPURACION DETALLADA
    $mail->SMTPAuth = true; 
    $mail->SMTPSecure = 'ssl';
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465; // o 587
    $mail->IsHTML(true);
    
    
    $mail->Username = "mess.metrologia@gmail.com";//////////////////////////////////PONER CUENTA GMAIL
    $mail->Password = "hglidvwsxcbbefhe";////CONTRASENIA DE APLICACION GENERADA DESDE CONSOLA DE GOOGLE
    
    
    $mail->SetFrom("mess.metrologia@gmail.com", "Aprovacion de Horas Extra");
    $mail->Subject = $deAsunto;
    $mail->Body = ' 
<html>
<head>
    <center> 
        <img src="https://messbook.com.mx/mess_logooficial.jpg" style = "width: 20%">
    </center>
    
    <meta charset="UTF-8">
    <style>
        .header {
            background-color: #007BFF;
            color: #ffffff;
            padding: 10px;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px 5px 0 0;
        }
    </style>
</head>
<body>
    <div class="header">
        Aviso de Nuevo Servicio para Validar
    </div>
        
    <center>
    <h1>
        '.mb_convert_encoding($solicita).' creo un nuevo servicio con horas extra.
    </h1>
    </center> 
    
    <center>
    <p>Este es un mensaje automático, por favor no responda a este correo.</p>
    </center>
</body>
</html>';

    //Envío de correo
    
        $correos = $correo; 
        $correos .= ',hugo.soria@mess.com.mx';
        $Arraycorreos  = explode (",", $correos);
        $mensaje = '';
        
        error_log("Correos recibidos: " . print_r($correos, true));
        error_log("Mensaje recibido: '$mensaje'");

        foreach ($Arraycorreos as $correo) {
            
            if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $mail->addAddress(trim($correo));
            } else {
                error_log("Correo inválido: '$correo'");
            }
        }
        
        if(!$mail->send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        } 
        else{
        ?>
            <div style="color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
            position: relative;
            padding: .75rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: .25rem;
            ">
            Mensaje Enviado
            </div>
        <?php
        header("location: https://messbook.com.mx/horasextra/");
        }
    echo json_encode(true);
?>