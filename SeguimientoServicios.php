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
                    

                    <div id ="FormularioPendientes" name ="FormularioPendientes" class="card shadow mb-0">
                        <div class="card border-left-warning shadow h-60 py-0">
                            <div class="card-header">
                                Seguimiento de Servicios
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">                        
                                    <div class="col-md-3">
                                        <label for="filtro-ingeniero" class="mr-2">Filtrar por Ingeniero:</label>
                                        <input type="text" id="filtro-ingeniero" class="form-control mr-3" placeholder="Buscar ingeniero...">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button class="btn btn-primary btn-md w-100" style="margin-top: 24px;" onclick="verServiciosPorValidar()">Aplicar filtro</button>
                                    </div>
                                </div>
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
    
    <!-- jQuery (necesario para DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    
    <!-- Bootstrap (para estilos y funcionalidades adicionales) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables Core (versión 2.2.1) -->
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
    
    <!-- Dependencias para exportación -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script> <!-- Para Excel -->
    
    <!-- SweetAlert (para mensajes emergentes) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
    
        $(document).ready(function () {
            verServiciosPorValidar();            
            
            $('#tablaServiciosPorValidar').DataTable({
                destroy: true,
                paging: true,
                pageLength: 10,
                ordering: true,
                searching: true,
                info: true,
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
                }
            });
            $('#tablaServiciosPorValidar').css('font-size', '12px');
            
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
            
        });
        
        function verServiciosPorValidar(){
            var ing = $('#filtro-ingeniero').val();
            $.ajax({
                    url: 'acciones_validaServicios.php',
                    type: 'POST',
                    data: { accion: 'verServiciosTodos', ing  },
                    dataType: 'json', 
                    success: function(registros) {
                        var table = $('#tablaServiciosPorValidar').DataTable();
                        table.clear().draw();
                        
                        registros.forEach(function(Registro) { //fecha_creacion, ot, ov, estatus
                            if(Registro.tiempo != null && Registro.tiempo > 0){

                                table.row.add([
                                    Registro.fecha_creacion,
                                    Registro.ot,
                                    Registro.ov,
                                    Registro.nombre,
                                    Registro.tiempo,
                                    Registro.area+' '+Registro.areaD,
                                    `<button class="btn btn-primary btn-sm" onclick="VerActividades(${Registro.id}, '${Registro.ot}')"><i class="fas fa-eye"></i></button></button>`
                                    ]).draw(false);
                            } 
                                
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error al obtener los registros:', textStatus, errorThrown);
                    }
                });
        }       
        
        function VerActividades(id, ot){
                $('#modalActividades').modal('show');
                
                idServicio =id;
                $('#idServicioAct').val(idServicio);
                $.ajax({
                    url: 'acciones_validaServicios.php',
                    type: 'POST',
                    data: { accion: 'llenaTodasActividades', idServicio:idServicio },
                    dataType: 'json', 
                    success: function(registros) {
                        var table = $('#tablaActividades').DataTable();
                        table.clear().draw();                        
                        registros.forEach(function(Registro) { //tipo, fecha_inicio, fecha_fin, duracion
                            table.row.add([
                                Registro.tipo,
                                Registro.fecha_inicio,
                                Registro.fecha_fin,
                                Registro.duracion,
                                Registro.comentarios                               
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
        
    </script>
</body>
</html>
