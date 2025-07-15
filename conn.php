<?php 
/*                                        //USUARIO               CONTRASENIA         BD 
$conn = mysqli_connect("localhost", "mess_log_tiempo_extra", "tiempo*2024", "mess_log_tiempo_extra");

    
    // Check connection
    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }else{
    //echo "Connected successfully";
    }*/
?>

<?php
// Crear conexión
$conn = new mysqli("localhost", "mess_log_tiempo_extra", "tiempo*2024", "mess_log_tiempo_extra");
// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
    
}
//echo "Conexión exitosa";
?>
