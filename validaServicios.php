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
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css" />    
    <!-- Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">

<!-- jQuery -->
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
                    <!-- TABLA DE LOS SERVICIOS POR VALIDAR -->
                    <div id ="FormularioPendientes" name ="FormularioPendientes" class="card shadow mb-0">
                        <div class="card border-left-warning shadow h-60 py-0">
                            <div class="card-header">
                                Servicios Por Validar
                            </div>
                            <div class="card-body">
                                <table id="tablaServiciosPorValidar" name="tablaServiciosPorValidar" class="responsive table table-sm">
                                    <thead>
                                        <tr class="table-warning">
                                            <th style="width: 10%;">Fecha solicitud</th>
                                            <th style="width: 12%;">OT</th>
                                            <th style="width: 12%;">OV</th>
                                            <th style="width: 22%;">Nombre</th>
                                            <th style="width: 10%;">Tiempo</th>
                                            <th style="width: 20%;">Area</th>
                                            <th style="width: 10%;"></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- TABLA DE LOS SERVICIOS VALIDADOS -->
                    <div id ="FormularioPendientes" name ="FormularioPendientes" class="card shadow mb-0">
                        <div class="card border-left-success shadow h-60 py-0">
                            <div class="card-header">
                                Servicios Validados
                            </div>
                            <div class="card-body">
                                <table id="tablaServiciosValidados" name="tablaServiciosValidados" class="responsive table table-sm">
                                    <thead>
                                        <tr class="table-success">
                                            <th style="width: 10%;">Fecha solicitud</th>
                                            <th style="width: 12%;">OT</th>
                                            <th style="width: 12%;">OV</th>
                                            <th style="width: 22%;">Nombre</th>
                                            <th style="width: 14%;">Tiempo</th>
                                            <th style="width: 20%;">Area</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
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
                            <tr class="table-warning">
                                <th>Actividad</th>
                                <th>F. Ini</th>
                                <th>F. Fin</th>
                                <th>Duración</th>
                                <th>Comentario</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <table id="tablaActividadesAuto" name="tablaActividadesAuto" class="display responsive table table-striped table-hover table-sm">
                        <thead >
                            <tr class="table-success">
                                <th>Actividad</th>
                                <th>F. Ini</th>
                                <th>F. Fin</th>
                                <th>Duración</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="idServicioAct" name="idServicioAct" class="form-control">
                    <button class="btn btn-primary btn-sm" onclick="validarActividades()"><i class="fas fa-check"></i>  Validar todo</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- jQuery (necesario para DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    
    <!-- Bootstrap (para estilos y funcionalidades adicionales) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables Core (versión 2.2.1) -->
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
    
    <!-- DataTables Buttons (versión 2.2.3) -->
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    
    <!-- Dependencias para exportación -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script> <!-- Para Excel -->
    
    <!-- Extensiones de exportación (versión 2.2.3) -->
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script> <!-- CSV, Excel, PDF -->
    
    <!-- SweetAlert (para mensajes emergentes) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
    
        $(document).ready(function () {
            verServiciosPorValidar();
            verServiciosValidados();
            
            $('#tablaServiciosPorValidar').DataTable({
                "paging": true,
                "searching": true,
                "language": {
                    "lengthMenu": "Mostrar _MENU_ ",
                    "zeroRecords": "No se encontraron resultados",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                    "infoFiltered": "(filtrado de _MAX_ registros en total)",
                    "search": "Buscar:",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "emptyTable": "No hay datos disponibles en la tabla",
                    "thousands": ",",
                    "aria": {
                        "sortAscending": ": activar para ordenar la columna de manera ascendente",
                        "sortDescending": ": activar para ordenar la columna de manera descendente"
                    }
                },
                
                dom:'<"top d-flex justify-content-between"fB>rt<"bottom ip><"clear">', // Añade los botones al DOM
                buttons: [
                    {
                        extend: 'excelHtml5', // Exporta a Excel
                        text: 'Excel',
                        titleAttr: 'Excel',
                        filename: 'Servicios_Por_Validar', // Nombre del archivo
                        sheetName: 'Servicios_Por_Validar', // Nombre de la hoja en Excel
                        exportOptions: {
                            columns: ':visible' // Exporta solo columnas visibles
                        },
                        className: 'btn-excel' // Aplica la clase personalizada
                    }
                ],
                initComplete: function() {
                    // Agrega estilos en línea al botón de Excel
                    $('.btn-excel').css({
                        'background-color': '#28a745',
                        'color': 'white',
                        'border': 'none',
                        'padding': '5px 10px',
                        'border-radius': '5px',
                        'font-weight': 'bold',
                        'cursor': 'pointer'
                    }).hover(
                        function() {
                            $(this).css('background-color', '#218838'); // Color más oscuro al pasar el mouse
                        }, 
                        function() {
                            $(this).css('background-color', '#28a745'); // Color original
                        }
                    );
                }
            });
            $('#tablaServiciosPorValidar').css('font-size', '12px');
            $('#tablaServiciosValidados').DataTable({
                "paging": true,
                "searching": true,
                "language": {
                    "lengthMenu": "Mostrar _MENU_ ",
                    "zeroRecords": "No se encontraron resultados",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                    "infoFiltered": "(filtrado de _MAX_ registros en total)",
                    "search": "Buscar:",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "emptyTable": "No hay datos disponibles en la tabla",
                    "thousands": ",",
                    "aria": {
                        "sortAscending": ": activar para ordenar la columna de manera ascendente",
                        "sortDescending": ": activar para ordenar la columna de manera descendente"
                    }
                },
                
                dom:'<"top d-flex justify-content-between"fB>rt<"bottom ip><"clear">', // Añade los botones al DOM
                buttons: [
                    {
                        extend: 'excelHtml5', // Exporta a Excel
                        text: 'Excel',
                        titleAttr: 'Excel',
                        filename: 'Servicios_Validados', // Nombre del archivo
                        sheetName: 'Servicios_Validados', // Nombre de la hoja en Excel
                        exportOptions: {
                            columns: ':visible' // Exporta solo columnas visibles
                        },
                        className: 'btn-excel' // Aplica la clase personalizada
                    }
                ],
                initComplete: function() {
                    // Agrega estilos en línea al botón de Excel
                    $('.btn-excel').css({
                        'background-color': '#28a745',
                        'color': 'white',
                        'border': 'none',
                        'padding': '5px 10px',
                        'border-radius': '5px',
                        'font-weight': 'bold',
                        'cursor': 'pointer'
                    }).hover(
                        function() {
                            $(this).css('background-color', '#218838'); // Color más oscuro al pasar el mouse
                        }, 
                        function() {
                            $(this).css('background-color', '#28a745'); // Color original
                        }
                    );
                }
            });
            
            $('#tablaServiciosValidados').css('font-size', '12px');
            
            $('#tablaActividades').DataTable({
                "paging": false,
                "searching": false,
                "language": {
                    "info": "_START_ a _END_ de _TOTAL_",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente"
                    }
                }
            });
            $('#tablaActividades').css('font-size', '12px');
            
            $('#tablaActividadesAuto').DataTable({
                "paging": false,
                "searching": false,
                "language": {
                    "info": "_START_ a _END_ de _TOTAL_",
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente"
                    }
                }
            });
            $('#tablaActividadesAuto').css('font-size', '12px');
            
        });
        
        function verServiciosPorValidar(){
            $.ajax({
                    url: 'acciones_validaServicios.php',
                    type: 'POST',
                    data: { accion: 'verServiciosPorValidar'  },
                    dataType: 'json', 
                    success: function(registros) {
                        var table = $('#tablaServiciosPorValidar').DataTable();
                        table.clear().draw();
                        
                        registros.forEach(function(Registro) { //fecha_creacion, ot, ov, estatus
                            table.row.add([
                                Registro.fecha_creacion,
                                Registro.ot,
                                Registro.ov,
                                Registro.nombre,
                                Registro.tiempo,
                                Registro.area+' '+Registro.areaD,
                                `<button class="btn btn-primary btn-sm" onclick="VerActividades(${Registro.id}, '${Registro.ot}')"><i class="fas fa-eye"></i></button></button>`
                                ]).draw(false);
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error al obtener los registros:', textStatus, errorThrown);
                    }
                });
        }
        
        function verServiciosValidados(){
            $.ajax({
                    url: 'acciones_validaServicios.php',
                    type: 'POST',
                    data: { accion: 'verServiciosValidados'  },
                    dataType: 'json', 
                    success: function(registros) {
                        var table = $('#tablaServiciosValidados').DataTable();
                        table.clear().draw();
                        
                        registros.forEach(function(Registro) { //fecha_creacion, ot, ov, estatus
                            table.row.add([
                                Registro.fecha_creacion,
                                Registro.ot,
                                Registro.ov,
                                Registro.nombre,
                                Registro.tiempo,
                                Registro.area+' '+Registro.areaD,
                                ]).draw(false);
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        //console.error('Error al obtener los registros:', textStatus, errorThrown);
                    }
                });
        }        
        
        function VerActividades(id, ot){
             $('#modalActividades').modal('show');
                VerActividadesAuto(id);
                idServicio =id;
                $('#idServicioAct').val(idServicio);
                $.ajax({
                    url: 'acciones_validaServicios.php',
                    type: 'POST',
                    data: { accion: 'llenaTablaActividades', idServicio:idServicio },
                    dataType: 'json', 
                    success: function(registros) {
                        var table = $('#tablaActividades').DataTable();
                        table.clear().draw();
                        idServicioAct
                        registros.forEach(function(Registro) { //tipo, fecha_inicio, fecha_fin, duracion
                            table.row.add([
                                Registro.tipo,
                                Registro.fecha_inicio,
                                Registro.fecha_fin,
                                Registro.duracion,
                                `<button type="button" class="btn btn-info btn-sm"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-custom-class="custom-tooltip"
                                        data-bs-title="${Registro.comentarios}">
                                <i class="fas fa-eye"></i></button>`,
                                `<button class="btn btn-warning btn-sm" onclick="validarActividad(${Registro.id}, ${idServicio})"><i class="fas fa-check"></i></button>`
                                ]).draw(false);
                        });
                        
                        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                            return new bootstrap.Tooltip(tooltipTriggerEl);
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
        
        function VerActividadesAuto(id){
             
                idServicio =id;
                $('#idServicioAct').val(idServicio);
                $.ajax({
                    url: 'acciones_validaServicios.php',
                    type: 'POST',
                    data: { accion: 'llenaTablaActividadesAuto', idServicio:idServicio },
                    dataType: 'json', 
                    success: function(registros) {
                        var table = $('#tablaActividadesAuto').DataTable();
                        table.clear().draw();
                        idServicioAct
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
        
        function validarActividad(idActividad, idServicio){
            accion = 'validarActividad';
            $.ajax({
                    url: 'acciones_validaServicios.php',
                    method: 'POST',
                    dataType: 'json',
                    data:{accion, idActividad},
                    success: function(data) {
                        Swal.fire({
                              icon: "success",
                              text: "¡Se proceso tu solicitud con éxito!",
                        });
                        VerActividades(idServicio);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                              icon: "error",
                              text: "¡No se pudo cerrar el servicio!",
                        });
                        
                    }
            });
        }
        
        function validarActividades(){
            idServicio = $("#idServicioAct").val();
            accion = 'validarActividades';
            $.ajax({
                    url: 'acciones_validaServicios.php',
                    method: 'POST',
                    dataType: 'json',
                    data:{accion, idServicio},
                    success: function(data) {
                        Swal.fire({
                              icon: "success",
                              text: "¡Se proceso tu solicitud con éxito!",
                        });
                        VerActividades(idServicio);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                              icon: "error",
                              text: "¡No se pudo cerrar el servicio!",
                        });
                        
                    }
            });
        }
        
        
    </script>
</body>
</html>
