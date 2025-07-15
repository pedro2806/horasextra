<?php
header('Content-Type: application/json; charset=utf-8');
include 'conn.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require("PHPMailer-master/src/PHPMailer.php");
require("PHPMailer-master/src/SMTP.php");

$deAsunto = "Restablecimiento de Contrase&Ntilde;a";
$correo = $_POST['correo'];

$token = bin2hex(random_bytes(3));
$estatus_recu_pass = "Autorizada";

$sqlUsuario = "SELECT id,usuario FROM usuarios WHERE correo = '$correo'";
$resUsuario = $conn->query($sqlUsuario);
if ($resUsuario->num_rows > 0) {
    $rowUsuario = $resUsuario->fetch_assoc();
    $id_usuario = $rowUsuario['id'];

    $stmt = $conn->prepare("UPDATE usuarios SET token = ?, estatus_recu_pass = ? WHERE id = ?");
    $stmt->bind_param("ssi", $token, $estatus_recu_pass, $id_usuario);
    $stmt->execute();

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 465;
        $mail->isHTML(true);

        $mail->Username = "mess.metrologia@gmail.com";
        $mail->Password = "hglidvwsxcbbefhe";

        $mail->SetFrom("mess.metrologia@gmail.com", "Restablecimiento de Contraseña");
        $mail->Subject = mb_encode_mimeheader($deAsunto, 'UTF-8');
        error_log("Token: " . $token);
        $mail->Body = '
            <html>
            <head>
                <center>
                    <img src="https://messbook.com.mx/mess_logooficial.jpg" style="width: 20%">
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
                    Restablecimiento de Contrase&ntilde;a
                </div>
                <center>
                    <h1>
                        Tu TOKEN de seguridad es:
                    </h1>
                        <div style="color: blue; font-size: 32px;">
                            ' .$token. '
                        </div>
                </center>
                <center>
                    <h1>
                        Haz clic en el siguiente enlace para restablecer tu contrase&ntilde;a:
                    </h1>
                    <a href="https://www.messbook.com.mx/horasextra/cambia_password.php?token=' . $token . '&correo=' . urlencode($correo) . '">Restablecer Contrase&ntilde;a</a>
                </center>
                <center>
                    <p>Este es un mensaje autom&aacute;tico, por favor no responda a este correo.</p>
                </center>
            </body>
            </html>';
        $mail->addAddress($correo);

        if (!$mail->send()) {
            echo json_encode(['success' => false, 'error' => $mail->ErrorInfo]);
        } else {
            echo json_encode(['success' => true]);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $mail->ErrorInfo]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Correo no encontrado']);
}
?>