<!DOCTYPE html>
<html lang="en">
<?php
    session_start();
    include 'conn.php';
    if ($_COOKIE['nombre'] == '') {
        echo $_COOKIE['nombredelusuario'];
        echo '<script>window.location.assign("index")</script>';
    }
?>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>MESS Log Horas Extras</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
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
                    <!-- Page Heading -->
                    
                    <!--<div class="d-sm-flex align-items-center justify-content-between mb-0">
                        <h3 class="mb-0 text-gray-800">Horas Extras</h3>
                    </div>-->
                    <!-- TABLA DE LOS SERVICIOS PENDIENTES -->
                    <div id ="FormularioPendientes" name ="FormularioPendientes" class="card shadow mb-0">
                        <div class="card border-left-primary shadow h-60 py-0">
                            <div class="card-header">
                                Servicios Pendientes <br><?php echo $_COOKIE['nombre']; ?>
                            </div>
                            <div class="card-body">
                                <table id="tablaServiciosAutorizados" name="tablaServiciosAutorizados" class="responsive table table-sm">
                                    <thead>
                                        <tr class="table-primary">
                                            <th style="width: 25%;">Usuario</th>
                                            <th style="width: 25%;">OT</th>
                                            <th style="width: 25%;">Estatus</th>
                                            <th style="width: 25%;"></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- FORMULARIO PARA CAPTURAR NUEVA ACTIVIDAD -->
                    <div id ="FormularioInicio" name ="FormularioInicio" class="card shadow mb-0">
                        <div class="card border-left-warning shadow h-60 py-0">
                            <div class="card-header">
                                Iniciar activad <label id="badgeOT" name="badgeOT" class="badge text-bg-warning" style="font-size: 16px;"></label>
                            </div>
                            <div class="card-body">
                                
                                <div class="row no-gutters align-items-center">
                                    <div class="row">
                                        <div class="col-12 col-md-3 col-sm-6 mb-0">
                                            <label for="tipo_actividad">Actividad</label>
                                            <select id="tipo_actividad" name="tipo_actividad" class="form-select" required>
                                                <option value="">Selecciona...</option>
                                                <option value="Traslado">Traslado</option>
                                                <option value="Servicio">Servicio</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-9 col-sm-6 mb-0">
                                            <label for="comentarios">Comentarios</label>
                                            <textarea id="comentarios" name="comentarios" rows="2" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <center>
                                                <button type="button" class="btn btn-success btn-sm" onClick= "ActividadNueva()">Confirmar</button>
                                            </center>
                                        </div>
                                    </div>
                                    <br>
                            </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- FORMULARIO ACTIVIDADES YA INICIADAS -->
                    <div id ="FormularioFin" name ="FormularioFin" class="card shadow mb-0">
                        <div class="card border-left-info shadow h-60 py-0">
                            <div class="card-header mb-0">
                                Actividades <h4 id="badgeOTfin" name="badgeOTfin" class="badge text-bg-info" style="font-size: 16px;"></h4>
                            </div>
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-0 p-0">
                                        <div class="mb-0 font-weight-bold text-gray-800" id="FechaINC" name="FechaINC">
                                            Fecha de Inicio: <label id="lblFechaIni" name="lblFechaIni"></label>
                                        </div>
                                        <div class="mb-0 font-weight-bold text-gray-800" id="Tipo" name="Tipo">
                                            Servicio: <label id="lblTipoS" name="lblTipo"></label>
                                        </div>
                                        <div class="alert alert-warning mb-0" role="alert" id="divServPendiente" name="divServPendiente">
                                            <label id="lblSerPendiente" name="lblSerPendiente"></label> 
                                        </div>
                                    </div>                    
                                </div>
                            </div>
                            <div class="card-footer">
                                <input type="hidden" id="idActividad" name="idActividad" class="form-control">
                                <input type="hidden" id="idServicio" name="idServicio" class="form-control">
                                <input type="hidden" id="otServicio" name="otServicio" class="form-control">
                                <input type="hidden" id="coordenadas" name="coordenadas" class="form-control">
                                <center>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-info btn-sm" id="btnNuevaActividad" name="btnNuevaActividad" onClick="showModalNuevaA()">Nueva Actividad</button>
                                        <button type="button" class="btn btn-warning btn-sm" id="btnFinActividad" name="btnFinActividad" onClick= "finalizarActividad()">Finalizar Actividad</button>
                                        <button type="button" class="btn btn-success btn-sm" id="btnCerrarServicio" name="btnCerrarServicio"onClick= "cerrarServicio()">Cerrar Servicio</button>
                                        <button type="button" class="btn btn-primary btn-sm" id="btnVerActividades" name="btnVerActividades"onclick="showModalActividades()">Ver Actividades</button>
                                    </div>
                                </center>
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
<!-- Modal actividades -->
    <div class="modal fade" id="modalActividades" name="modalActividades" tabindex="-1" aria-labelledby="modalActividadesLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSuccessLabel">Actividades del servicio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table id="tablaActividades" name="tablaActividades" class="display responsive table table-striped table-hover table-sm">
                        <thead >
                            <tr class="table-primary">
                                <th>Actividad</th>
                                <th>Fecha Ini</th>
                                <th>Fecha Fin</th>
                                <th>Duración</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    
                </div>
            </div>
        </div>
    </div>

<!-- Modal Nueva Actividad -->
    <div class="modal fade" id="modalNuevaA" name="modalNuevaA" tabindex="-1" aria-labelledby="modalNuevaALabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSuccessLabel">Nueva Actividad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-0 p-0 mb-0">
                            <form id ="FormularioNuevaA" name ="FormularioNuevaA">  
                                <div class="mb-0 font-weight-bold text-gray-800" id="TipoN" name="TipoN">
                                    Servicio:
                                    <select id="TipoSNuevo" name="TipoSNuevo" class="form-select" required>
                                        <option value="">Selecciona...</option>
                                        <option value="Traslado">Traslado</option>
                                        <option value="Servicio">Servicio</option>
                                    </select>
                                </div>
                                <br>
                                <div class="mb-0 font-weight-bold text-gray-800" id="Comentd" name="Comentd">
                                    Comentarios: <textarea id="Coment" name="Coment" class="form-control"></textarea>
                                </div>
                            </form>
                            <br>
                            <center>
                                <button type="button" class="btn btn-success" id="ConfirmarN" onClick= "ActividadNueva()">Confirmar</button>
                            </center>
                        </div>                    
                    </div>   
                </div>
                <div class="modal-footer">
                    
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
    
        $(document).ready(function () {
            obtenerCoordenadas();
            verServiciosAutorizados();
            $('#FormularioFin').hide();
            $('#FormularioInicio').hide();
            
            $('#tablaActividades').DataTable({
                "paging": false,
                "searching": false,
                "language": {
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ actividades",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente"
                    }
                }
            });
            $('#tablaActividades').css('font-size', '11px');
            
            $('#tablaServiciosAutorizados').DataTable({
                "paging": false,
                "searching": false,
                "language": {
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Servicios",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente"
                    }
                }
            });
            $('#tablaServiciosAutorizados').css('font-size', '12px');
        });
        
        function verServiciosAutorizados(){
            $.ajax({
                    url: 'acciones_servicios_autorizados.php',
                    type: 'POST',
                    data: { accion: 'verServiciosAutorizados'  },
                    dataType: 'json', 
                    success: function(registros) {
                        var table = $('#tablaServiciosAutorizados').DataTable();
                        table.clear().draw();
                        
                        registros.forEach(function(Registro) { //fecha_creacion, ot, ov, estatus
                            table.row.add([
                                Registro.nombre,
                                Registro.ot,
                                Registro.estatus,
                                `<button class="btn btn-info btn-sm" onclick="validarEstatus(${Registro.id}, '${Registro.ot}')">Ver</button>`
                                ]).draw(false);
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        //console.error('Error al obtener los registros:', textStatus, errorThrown);
                    }
                });
        }
        
        function validarEstatus(idServicio, ot){
            var accion = "ValidarEstatus"
            $.ajax({
                url: 'acciones_servicios_autorizados.php',
                method: 'POST',
                dataType: 'json',
                data:{accion : accion, idServicio},
                success: function(data) {
                    if (Array.isArray(data)) {
                        data.forEach(function(actividad) {
                            
                            $('#lblOV').text(actividad.ov);
                            $('#lblOT').text(actividad.ot);
                            $('#lblFechaIni').text(actividad.fecha_creacion);
                            $('#lblTipoS').text(actividad.tipo_s);
                            
                            $('#idActividad').val(actividad.id_actividad);
                            $('#idServicio').val(actividad.id_servicio);
                            
                            if(actividad.estatusServ == 'En proceso'){
                                //$('#FormularioInicio').hide();
                                $('#FormularioFin').show();
                                $('#badgeOTfin').text(ot);
                            }
                            else{
                                $('#FormularioFin').show();
                                //$('#FormularioInicio').hide();
                                $('#badgeOTfin').text(ot);
                            }
                            //ACTIVIDAD
                            if(actividad.estatus == 'Proceso'){
                                $('#lblSerPendiente').text('Acitvidad corriendo: '+actividad.tipo);
                                
                                $("#btnFinActividad").show();
                                $("#btnNuevaActividad").hide();
                                
                                $('#badgeOTfin').text(ot);
                                $('#otServicio').val(ot);
                            }
                            else{
                                $("#btnFinActividad").hide();
                                $("#btnNuevaActividad").show();
                                $('#lblSerPendiente').text('No hay actividades pendientes');
                            }          
                        });
                    } else {
                        $('#FormularioFin').show();
                        //$('#FormularioInicio').hide();
                        
                        $('#otServicio').val(ot);
                        $("#btnFinActividad").hide();
                        $("#btnNuevaActividad").show();
                        
                        $('#lblSerPendiente').text('No hay actividades pendientes');
                        /*$('#FormularioFin').show();
                        $('#FormularioInicio').show();
                        $('#badgeOT').text(ot);
                        */
                        
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#FormularioFin').show();
                    //$('#FormularioInicio').show();
                    $("#btnFinActividad").hide();
                    $("#btnNuevaActividad").hide();
                    $('#lblSerPendiente').text('No hay actividades pendientes');
                    Swal.fire({
                            icon: "info",
                            text: "¡Sin Registros!",
                    });
                }
            });
        }
        
        function showModalActividades(type, id) {
                $('#modalActividades').modal('show');
                idServicio = $('#idServicio').val();
                
                $.ajax({
                    url: 'acciones_servicios_autorizados.php',
                    type: 'POST',
                    data: { accion: 'llenaTablaActividades', idServicio:idServicio },
                    dataType: 'json', 
                    success: function(registros) {
                        var table = $('#tablaActividades').DataTable();
                        table.clear().draw();
                        
                        registros.forEach(function(Registro) { //tipo, fecha_inicio, fecha_fin, duracion
                            table.row.add([
                                Registro.tipo,
                                Registro.fecha_inicio,
                                Registro.fecha_fin,
                                Registro.duracion
                                ]).draw(false);
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            icon: "error",
                            text: "¡Error cargando informacion!",
                    });
                    }
                });
            
        }
        
        function showModalNuevaA(type, id) {
            $('#modalNuevaA').modal('show');
        }
        
        function ActividadNueva(type, id){
                comentarios = $('#Coment').val();
                tipo_actividad = $('#TipoSNuevo').val();
                coordenadas = $('#coordenadas').val();
                idServicio = $('#idServicio').val();
                ot = $('#otServicio').val();
                
                $.ajax({
                    url: 'acciones_servicios_autorizados.php',
                    type: 'POST',
                    data: { accion: 'ActividadNueva', idServicio, tipo_actividad, comentarios, coordenadas },
                    dataType: 'json', 
                    success: function(registros) {
                        Swal.fire({
                                icon: "success",
                                text: "¡Se inicio la actividad!",
                        });
                        $('#modalNuevaA').modal('hide');
                        
                        $("#btnNuevaActividad").hide();
                        $("#btnFinActividad").show();
                        
                        validarEstatus(idServicio, ot);
                        
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            icon: "error",
                            text: "¡Error procesando la solicitud!",
                        });
                    }
                });
        }
        
        function finalizarActividad(){
            var accion = "finalizarActividad";
            idActividad = $('#idActividad').val();
            idServicio = $('#idServicio').val();
            coordenadas = $('#coordenadas').val();
            ot = $('#otServicio').val();
            
            Swal.fire({
                title: "¿Desea finalizar la actividad?",
                showDenyButton: true,
                confirmButtonText: "Finalizar",
                denyButtonText: `Cancelar`
            }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    url: 'acciones_servicios_autorizados.php',
                    method: 'POST',
                    dataType: 'json',
                    data:{accion, idActividad, idServicio, coordenadas},
                    success: function(data) {
                        Swal.fire({
                            icon: "success",
                            text: "¡Se proceso tu solicitud con éxito!",
                        });
                        $("#btnNuevaActividad").show();
                        validarEstatus(idServicio, ot);
                        
                        //$('#modalNuevaA').modal('show');
                        
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            icon: "error",
                            text: "¡No se pudo finalizar la actividad!",
                        });
                        
                    }
                });
            } else if (result.isDenied) {
                //Swal.fire("Changes are not saved", "", "info");
            }
            });
        }
        
        function cerrarServicio(){
            var accion = "cerrarServicio";
            idActividad = $('#idActividad').val();
            idServicio = $('#idServicio').val();
            coordenadas = $('#coordenadas').val();
            ot = $('#otServicio').val();
            
            Swal.fire({
                title: "¿Desea cerrar el servicio?",
                showDenyButton: true,
                confirmButtonText: "Finalizar",
                denyButtonText: `Cancelar`
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: 'acciones_servicios_autorizados.php',
                        method: 'POST',
                        dataType: 'json',
                        data:{accion, idActividad, idServicio, coordenadas},
                        success: function(data) {
                            Swal.fire({
                                icon: "success",
                                text: "¡Se proceso tu solicitud con éxito!",
                            });
                            $("#btnNuevaActividad").hide();
                            $("#btnFinActividad").hide();
                            $("#btnCerrarServicio").hide();
                            $("#btnVerActividades").hide();
                            
                            validarEstatus(idServicio, ot);
                            verServiciosAutorizados();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            Swal.fire({
                                icon: "error",
                                text: "¡No se pudo cerrar el servicio!",
                            });
                            
                        }
                    });
                    
                } else if (result.isDenied) {
                }
            });
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
                    //alert("Información de ubicación no disponible.");
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
