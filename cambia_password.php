<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MESS - Cambiar Contraseña</title>
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
                                        <h1 class="h4 text-gray-900 mb-4">Cambiar Contraseña</h1>
                                    </div>
                                    <form id="cambiarPasswordForm">
                                        <div class="form-group">
                                            <label for="token">Token</label>
                                            <input type="text" class="form-control" id="token" name="token" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="nuevaPassword">Nueva contraseña</label>
                                            <input type="password" class="form-control" id="nuevaPassword" name="nuevaPassword">
                                        </div>
                                        <div class="form-group">
                                            <label for="confirmarPassword">Confirmar contraseña</label>
                                            <input type="password" class="form-control" id="confirmarPassword" name="confirmarPassword">
                                        </div>
                                        <input type="hidden" id="correoUsuario" name="correoUsuario">
                                        <center>
                                            <button type="button" class="btn btn-success" id="btnGuardarPassword">Confirmar</button>
                                        </center>
                                    </form>
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
            // Detectar parámetros en la URL y llenar el formulario
            const urlParams = new URLSearchParams(window.location.search);
            const token = urlParams.get('token');
            const correo = urlParams.get('correo');

            if (token && correo) {
                $('#token').val(token);
                $('#correoUsuario').val(decodeURIComponent(correo));
            }

            // Enviar Contraseña Nueva
            $('#btnGuardarPassword').click(function() {
                var nuevaPassword = $('#nuevaPassword').val();
                var confirmarPassword = $('#confirmarPassword').val();
                var correo = $('#correoUsuario').val();
                var token = $('#token').val();

                if (nuevaPassword !== confirmarPassword) {
                    Swal.fire({
                        title: 'Error',
                        text: "¡No coinciden las contraseñas!",
                        icon: 'error'
                    });
                    return;
                }
                $.ajax({
                    type: 'POST',
                    url: 'acciones_recupera_password.php',
                    data: { accion: 'CambiarPassword', correo: correo, password: nuevaPassword, token: token },
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire({
                            title: 'Confirmado',
                            text: "¡Se ha cambiado la contraseña!",
                            icon: 'success'
                        }).then(() => {
                            window.location.href = 'index'; // Redirigir al inicio de sesión
                        });
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: "¡Token ya utilizado, solicita uno nuevo!",
                            icon: 'error'
                        }).then(() => {
                            window.location.href = 'recupera_contrasena'; // Redirigir al inicio de sesión
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>