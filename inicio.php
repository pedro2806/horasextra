<?php
    session_start();
    include 'conn.php';
    if ($_COOKIE['nombre'] == '') {
        echo $_COOKIE['nombredelusuario'];
        echo '<script>window.location.assign("index")</script>';
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>MESS Log Horas Extras</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
    <style>
        .dataTables_wrapper .dataTables_length select {
            width: auto;
        }
        .dataTables_wrapper .dataTables_filter input {
            width: auto;
        }
        table.dataTable {
            width: 100% !important;
        }
    </style>
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php
            include 'menu.php'; 
        ?>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <?php
                    include 'encabezado.php';
                ?>
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    
                    <!-- BOTONES -->
                    <center>
                        <div class="alert alert-light" role="alert">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button id="NuevoServicio" name="NuevoServicio" class="btn btn-outline-success btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFormularioInicio" aria-expanded="false" aria-controls="collapseExample">
                                    Nuevo Servicio
                                </button>
                                <button id="sin_autorizar" name="sin_autorizar" class="btn btn-outline-warning btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSinAutorizar" aria-expanded="false" aria-controls="collapseExample">
                                    Servicios Sin Autorizar
                                </button>
                                <a id= "autorizados" name= "autorizados" class="btn btn-outline-info btn-sm" href="servicios_autorizados" role="button" aria-expanded="false" aria-controls="collapseExample">
                                    Servicios Autorizados
                                </a>
                            </div>
                        </div>
                    </center>
                    
                    <!-- FORMULARIO INICIO SERVICIO -->
                    <div id ="collapseFormularioInicio" name ="collapseFormularioInicio" class="collapse">
                        <div class="card border-left-success shadow h-60 py-0 py-0">
                            <div class="card card-header">
                                Servicio Nuevo
                            </div>
                            <div class="card card-body">
                                <form id="form" name="form">
                                    <div class="row">
                                        <div class="col-12 col-md-3 col-sm-6 mb-3 d-flex align-items-center">
                                            <label for="ot" class="mr-2">OT</label>
                                            <input type="text" id="ot" name="ot" class="form-control" required>
                                        </div>
                                        <div class="col-12 col-md-3 col-sm-6 mb-3 d-flex align-items-center">
                                            <label for="ov" class="mr-2">OV</label>
                                            <input type="text" id="ov" name="ov" class="form-control" required>
                                        </div>
                                        <div class="col-12 col-md-3 col-sm-6 mb-3 d-flex align-items-center">
                                            <label for="area" class="mr-2">Área</label>
                                            <select id="area" name="area" class="form-select" required>
                                                <option value="">Selecciona...</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-3 col-sm-6 mb-0">
                                            <label for="tipo_servicio" class="mr-2">Tipo de Servicio</label>
                                            <select id="tipo_servicio" name="tipo_servicio" class="form-select" required>
                                                <option value="">Selecciona...</option>
                                                <option value="Externo">Externo</option>
                                                <option value="Interno">Interno</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-9 col-sm-6 mb-0">
                                            <label for="comentarios">Comentarios</label>
                                            <textarea id="comentarios" name="comentarios" rows="2" class="form-control"></textarea>
                                            <input type="hidden" id="coordenadas" name="coordenadas" class="form-control">
                                        </div>
                                    </div>
                                </form>
                                <hr>
                                <!--HIDDEN-->
                                <div class="row">
                                    <div class="col-sm-4">
                                        <input id="accion" name="accion" type="hidden" value="nuevo">
                                    </div>
                                    <div class="col-sm-4">
                                        <center>
                                            <button type="button" class="btn btn-success" id="Confirmar" onClick= "nuevoServicio()">Confirmar</button>
                                        </center>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- SIN AUTORIZAR-->
                    <div id ="collapseSinAutorizar" name ="collapseSinAutorizar" class="collapse">
                        <div class="card border-left-warning shadow h-60 py-0 py-0">        
                            <div class="card card-header">
                                Servicios sin autorizar
                            </div>
                            <div class="card card-body">
                                <div class="row">
                                    <table id="tablaSinAutorizar" name="tablaSinAutorizar" class="display responsive table table-striped table-hover table-sm">
                                        <thead>
                                            <tr class="table-primary">
                                                <th>Fecha/OT</th>
                                                <th>Ing</th>
                                                <th>T. Servicio</th>
                                                <th> </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; MESS</span>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    
    <!--<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    
    <script type="text/javascript">
    
        $(document).ready(function () {
            muestraDepto();
            obtenerCoordenadas();
            llenaTablaSinAuto();
            $('#tablaActividades').DataTable();
            $('#tablaSinAutorizar').DataTable({
                "paging": true,
                "searching": false,
                "language": {
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ ",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente"
                    }
                }
            });
            $('#tablaSinAutorizar').css('font-size', '12px');
            
            // Cuando cualquier botón con "data-bs-toggle=collapse" sea clickeado
            $('[data-bs-toggle="collapse"]').on('click', function () {
                // Cierra todos los collapses
                $('.collapse').collapse('hide');
                
                // Abre el collapse clickeado
                var target = $(this).data('bs-target');
                $(target).collapse('show');
            });
        });
        
        //AREA
        function muestraDepto(){
            var accion = "verDepto";
            
                $.ajax({
                    url: 'acciones_inicio.php',
                    method: 'POST',
                    dataType: 'json',
                    data:{accion},
                    success: function(data) {
                        var select = $('#area');
                        data.forEach(function(area) {
                            var option = $('<option></option>').attr('value', area.clave).text(area.clave+' - '+area.area);
                            select.append(option);
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                          icon: "error",
                          text: "!No se pudieron cargar las areas!",
                        });
                        
                    }
                });
        }
        
        function nuevoServicio(){
            const form = document.querySelector("#form"); // Obtener el formulario
            if (!form.checkValidity()) {  // Verificar si el formulario es válido
                Swal.fire({
                    icon: "warning",
                    text: "Por favor, completa todos los campos requeridos.",
                });
                return;  // No continuar con el envío si el formulario no es válido
            }
    
            accion = "nuevoServicio";
            coordenadas = $('#coordenadas').val();
            comentarios = $('#comentarios').val();
            tipo_actividad = $('#tipo_actividad').val();
            tipo_servicio = $('#tipo_servicio').val();
            area = $('#area').val();
            ot = $('#ot').val();
            ov = $('#ov').val();
            
            $.ajax({
                    url: 'acciones_inicio.php',
                    method: 'POST',
                    dataType: 'json',
                    data:{accion, area, tipo_servicio, tipo_actividad, comentarios, coordenadas, ot, ov},
                    success: function(data) {
                        
                        Swal.fire({
                              icon: "success",
                              text: "Se proceso con exito.",
                        });
                        llenaTablaSinAuto();
                        limpiarFormulario(); 
                        // Ocultar el modal de "Nuevo Servicio"
                        $('#collapseFormularioInicio').collapse('hide');
                        $('#collapseSinAutorizar').collapse('show');
                        enviaCorreo();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                              icon: "info",
                              text: "!Atención! Tu servicio no se pudo procesar.",
                        });
                        
                    }
            });
        }
        
        function enviaCorreo(){
            
            $.ajax({
                url: 'enviaNotificacion.php',
                method: 'POST',
                dataType: 'json',
                data:{ },
                success: function(data) {
                }
            });
        }
        
        //Llena Tabla "Sin Autorizar"
        function llenaTablaSinAuto(){
            rolUsuario = <?php echo $_COOKIE["rol"]; ?>;
            
            $.ajax({
                    url: 'acciones_inicio.php',
                    type: 'POST',
                    data: { accion: 'llenaTablaSinAuto'},
                    dataType: 'json', 
                    success: function(registros2) {
                        var table = $('#tablaSinAutorizar').DataTable();
                        
                        table.clear().draw();
                        
                        registros2.forEach(function(Registro) {
                            
                            if (rolUsuario == 2 || rolUsuario == 3 || rolUsuario == 4) {
                            Botones = `<button class="btn btn-primary btn-sm" onclick="autorizarServicio(${Registro.id}, 'Autorizado')"><i class="fa fa-check"></i></button>
                                       <button class="btn btn-danger btn-sm" onclick="autorizarServicio(${Registro.id}, 'Rechazado')"><i class="fa fa-times"></i></button>`;
                            }
                            else{
                                Botones = `<span class="badge text-bg-warning">Validando</span>`;
                            }
                            
                            table.row.add([
                                Registro.fecha_creacion + `<br>`+ Registro.ot,
                                Registro.ingeniero,
                                Registro.tipo_s,
                                Botones, //`<button class="btn btn-info btn-sm" onclick="autorizarServicio(${Registro.id})">Validar</button>`,
                                ]).draw(false);
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                          icon: "info",
                          text: "!No hay servicios pendientes por autorizar!",
                        });
                    }
                });
        }
        
        function autorizarServicio(idServicio, estatus) {
            var estatusText = ''
            if(estatus == 'Autorizado'){
                estatusText = 'Autorizo';
            }else{
                estatusText = 'Rechazo';
            }
            
            Swal.fire({
                title: "¿" + estatusText + " Servicio?",
                showDenyButton: true,
                confirmButtonText: estatusText,
                icon: "question",
                denyButtonText: "Cerrar"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'acciones_inicio.php',
                        type: 'POST',
                        dataType: 'json',
                        data: {accion: 'autorizarServicio', idServicio, estatus},
                        success: function(response) {
                            Swal.fire("Servicio " + estatus + " con éxito");
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            Swal.fire({
                                icon: "success",
                                text: "¡Se " + estatusText + " el servicio!",
                            });
                        }
                    });
                    llenaTablaSinAuto();
                } else if (result.isDenied) {
                    Swal.fire("Cambios no guardados", "");
                }
            });
        }
        
        function limpiarFormulario() {
            document.getElementById("ov").value = "";
            document.getElementById("ot").value = "";
            document.getElementById("area").selectedIndex = 0;
            document.getElementById("tipo_servicio").selectedIndex = 0; 
            document.getElementById("comentarios").value = "";
            document.getElementById("coordenadas").value = "";
        }

        
        /* *********************************************************************** */
        function obtenerCoordenadas() {
            if ("geolocation" in navigator) {
                // El navegador soporta la API de Geolocalización
                navigator.geolocation.getCurrentPosition(mostrarPosicion, mostrarError);
            } else {
                alert("Geolocalización no es soportada por este navegador.");
            }
        }

        function mostrarPosicion(position) {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;
            const precision = position.coords.accuracy;

            document.getElementById('coordenadas').value = `${lat}, ${lon}`;
        }

        function mostrarError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    alert("Permiso denegado para obtener la ubicación.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Información de ubicación no disponible.");
                    break;
                case error.TIMEOUT:
                    alert("La solicitud de ubicación ha expirado.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("Ocurrió un error desconocido.");
                    break;
            }
        }
    </script>
</body>
</html>
