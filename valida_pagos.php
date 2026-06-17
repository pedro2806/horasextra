<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>RR HH - Validación de Pagos</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/horasextra.css" rel="stylesheet">    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.dataTables.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>

<body id="page-top">

    <div id="wrapper">
        <?php
            include 'menu.php';
        ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
            
                <?php
                    if(isset($_SESSION['nombre'])) { }
                    include 'encabezado.php';
                ?>
                
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Solicitudes de Tiempo Extra</h1>                        
                    </div>


                    <div class="row">
                        <div class="card shadow mb-4 w-100">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Validación de Pagos</h6>                                    
                            </div>
                            <div class="card-body">
                                
                                <ul class="nav nav-tabs" id="pagoHorasTabs" role="tablist">
                                    <li class="nav-item">
                                        <button class="nav-link active" id="proceso-tab" data-bs-toggle="tab" data-bs-target="#proceso-pane" type="button" role="tab">
                                            <i class="bi bi-clock-history"></i> 1. Por Validar
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" id="autorizado-tab" data-bs-toggle="tab" data-bs-target="#autorizado-pane" type="button" role="tab">
                                            <i class="bi bi-hand-thumbs-up"></i> 2. Autorizadas
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" id="por-pagar-tab" data-bs-toggle="tab" data-bs-target="#por-pagar-pane" type="button" role="tab">
                                            <i class="bi bi-file-earmark-excel text-success"></i> 3. Por Pagar (Nómina)
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" id="pagado-tab" data-bs-toggle="tab" data-bs-target="#pagado-pane" type="button" role="tab">
                                            <i class="bi bi-check-circle-fill text-primary"></i> 4. Historial Pagados
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content border border-top-0 p-3 bg-white" id="pagoHorasTabsContent">
                                    
                                    <div class="tab-pane fade show active" id="proceso-pane" role="tabpanel">
                                        <div class="table-responsive">
                                            <table id="tabla_proceso" class="table table-striped table-hover w-100">
                                                <thead>
                                                    <tr>
                                                        <th>No. Empleado</th>
                                                        <th>Usuario</th>
                                                        <th>OT/Área</th>
                                                        <th>Fecha Registro</th>
                                                        <th>Horas</th>
                                                        <th>Comentarios</th>
                                                        <th>Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="autorizado-pane" role="tabpanel">
                                        <div class="table-responsive">
                                            <table id="tabla_autorizado" class="table table-striped table-hover w-100">
                                                <thead>
                                                    <tr>
                                                        <th>No. Empleado</th>
                                                        <th>Usuario</th>
                                                        <th>OT/Área</th>
                                                        <th>Fecha Registro</th>
                                                        <th>Horas</th>
                                                        <th>Comentarios</th>
                                                        <th>Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="por-pagar-pane" role="tabpanel">
                                        <div class="table-responsive">
                                            <table id="tabla_por_pagar" class="table table-striped table-hover w-100">
                                                <thead>
                                                    <tr>
                                                        <th>No. Empleado</th>
                                                        <th>Usuario</th>
                                                        <th>OT/Área</th>
                                                        <th>Fecha Registro</th>
                                                        <th>Horas</th>
                                                        <th>Comentarios</th>
                                                        <th>Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="pagado-pane" role="tabpanel">
                                        <div class="table-responsive">
                                            <table id="tabla_pagado" class="table table-striped table-hover w-100">
                                                <thead>
                                                    <tr>
                                                        <th>No. Empleado</th>
                                                        <th>Usuario</th>
                                                        <th>OT/Área</th>
                                                        <th>Fecha Registro</th>
                                                        <th>Horas</th>
                                                        <th>Comentarios</th>
                                                        <th>Estatus Gral</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div> 
                            </div> 
                        </div> 
                    </div> 

                </div>
            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; MESS 2026</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>    

    <script type="text/javascript">      
        var tblProceso, tblAutorizado, tblPorPagar, tblPagado;

        $(document).ready(function() {
            
            // 1. Pestaña: Por Validar
            tblProceso = $('#tabla_proceso').DataTable({
                "ajax": {
                    "url": "controlador_horas_extra.php",
                    "type": "POST",
                    "data": { accion: 'listar_proceso' }
                },
                "columns": [
                    { "data": "noEmpleado" }, { "data": "usuario" }, { "data": "ot_desglose" }, { "data": "fecha_inicio" }, { "data": "duracion" }, { "data": "comentarios" },
                    { 
                        "data": null,
                        "render": function(data, type, row) {
                            return `<button class="btn btn-primary btn-sm btn-cambiar" data-id="${row.id}" data-estatus="Autorizado">
                                        <i class="bi bi-patch-check"></i> Autorizar
                                    </button>`;
                        }
                    }
                ]
            });

            // 2. Pestaña: Autorizadas
            tblAutorizado = $('#tabla_autorizado').DataTable({
                "ajax": {
                    "url": "controlador_horas_extra.php",
                    "type": "POST",
                    "data": { accion: 'listar_autorizados' }
                },
                "columns": [
                    { "data": "noEmpleado" }, { "data": "usuario" }, { "data": "ot_desglose" }, { "data": "fecha_inicio" }, { "data": "duracion" }, { "data": "comentarios" },
                    { 
                        "data": null,
                        "render": function(data, type, row) {
                            return `<button class="btn btn-warning btn-sm btn-cambiar" data-id="${row.id}" data-estatus="Por Pagar">
                                        <i class="bi bi-send-check"></i> Enviar a Nómina
                                    </button>`;
                        }
                    }
                ]
            });

            // 3. Pestaña: Por Pagar (CON EXCEL INTEGRADO)
            tblPorPagar = $('#tabla_por_pagar').DataTable({
                "dom": 'Bfrtip', // Define la posición de los botones
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="bi bi-file-earmark-excel"></i> Descargar Excel de Nómina',
                        className: 'btn btn-success btn-sm mb-2',
                        title: 'Layout_Horas_Extra_Nomina',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5] // Exporta solo las columnas informativas (no los botones de acción)
                        }
                    }
                ],
                "ajax": {
                    "url": "controlador_horas_extra.php",
                    "type": "POST",
                    "data": { accion: 'listar_por_pagar' }
                },
                "columns": [
                    { "data": "noEmpleado" }, { "data": "usuario" }, { "data": "ot_desglose" }, { "data": "fecha_inicio" }, { "data": "duracion" }, { "data": "comentarios" },
                    { 
                        "data": null,
                        "render": function(data, type, row) {
                            return `<button class="btn btn-success btn-sm btn-cambiar" data-id="${row.id}" data-estatus="Pagado">
                                        <i class="bi bi-cash-stack"></i> Confirmar Pago
                                    </button>`;
                        }
                    }
                ]
            });

            // 4. Pestaña: Historial Pagados
            tblPagado = $('#tabla_pagado').DataTable({
                "ajax": {
                    "url": "controlador_horas_extra.php",
                    "type": "POST",
                    "data": { accion: 'listar_pagados' }
                },
                "columns": [
                    { "data": "noEmpleado" }, { "data": "usuario" }, { "data": "ot_desglose" }, { "data": "fecha_inicio" }, { "data": "duracion" }, { "data": "comentarios" },
                    { 
                        "data": "estatus_gral",
                        "render": function(data) {
                            return `<span class="badge bg-success"><i class="bi bi-check-all"></i> ${data}</span>`;
                        }
                    }
                ]
            });

            // Forzar ajuste de columnas al cambiar pestañas
            $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
            });

            // ESCUCHADOR UNIFICADO PARA AVANZAR EL FLUJO
            $(document).on('click', '.btn-cambiar', function() {
                var idRegistro = $(this).data('id');
                var objetivoEstatus = $(this).data('estatus');
                
                $.ajax({
                    url: 'controlador_horas_extra.php',
                    type: 'POST',
                    data: { accion: 'cambiar_estatus', id: idRegistro, nuevo_estatus: objetivoEstatus },
                    dataType: 'json',
                    success: function(response) {
                        if(response.success) {
                            swal("¡Éxito!", response.message, "success");
                            // Recargar las cuatro instancias para ver los movimientos inmediatos
                            tblProceso.ajax.reload(null, false);
                            tblAutorizado.ajax.reload(null, false);
                            tblPorPagar.ajax.reload(null, false);
                            tblPagado.ajax.reload(null, false);
                        } else {
                            swal("Atención", response.message, "warning");
                        }
                    },
                    error: function() {
                        swal("Error", "Error al procesar la actualización del flujo.", "error");
                    }
                });
            });
        });
    </script>
</body>
</html>