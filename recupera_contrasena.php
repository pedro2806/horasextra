<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MESS - Olvidé mi contraseña</title>
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Recuperar contraseña</h1>
                                    </div>
                                    <form class="user" id="resetForm">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" id="InputEmail" name="InputEmail" aria-describedby="emailHelp" placeholder="Usuario">
                                            <span>@mess.com.mx</span>
                                        </div>
                                        <center>
                                            <button type="button" class="btn btn-primary" id="btnRecuperar">Recuperar contraseña</button>
                                        </center>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="index.php">Regresar.</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        $(document).ready(function() {
            //Validar Correo
            $('#btnRecuperar').click(function() {
                var usuario = $('#InputEmail').val();
                var dominio = "@mess.com.mx";
                
                // Verifica si el usuario ya ingresó un dominio
                if (usuario.indexOf("@") === -1) {
                    usuario += dominio;
                }
        
                $.ajax({
                    type: 'POST',
                    url: 'acciones_recupera_password.php',
                    data: { accion: 'CorreoRecuperacion', correo: usuario },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success === true) {
                            $('#correoUsuario').val(response.correo);
                            enviaCorreo(response.correo);
                            Swal.fire({
                              title: 'Enviado, ¡Revisa tu correo para continuar!',
                              text: "Te llegara un link y un token, con el cual podras actulizar tu contraseña.",
                              icon: 'success'
                            })
                        } 
                    },error: function(xhr, textStatus, errorThrown) {
                        Swal.fire({
                          title: 'Error',
                          text: "¡No se pudo validar el correo!",
                          icon: 'error'
                        });
                        console.log("xhr:", xhr);
                        console.log("textStatus:", textStatus);
                        console.log("errorThrown:", errorThrown);
                    }
                });
            });
            
            //Enviar Token
            function enviaCorreo(correo) { // Corregido: recibir correo como parámetro
                $.ajax({
                    url: 'enviaToken.php',
                    method: 'POST',
                    dataType: 'json',
                    data: { correo: correo }, // Corregido: enviar el correo
                    success: function(data) {
                        if (data.success === false) {
                            Swal.fire({
                                title: 'Enviado',
                                text: "¡Revisa tu correo!",
                                icon: 'success'
                            });
                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        //console.error("Error en enviaToken.php:", xhr, textStatus, errorThrown);
                        Swal.fire({
                            title: 'Error',
                            text: data.error || "¡Error al enviar el correo!",
                            icon: 'error'
                        });
                    }
                });
            }
        });
    </script>
</body>
</html>