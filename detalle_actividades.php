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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>MESS Log Horas Extras</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

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
                    
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h2 class="h3 mb-0 text-gray-800">Horas Extras</h2>
                    </div>

                    <div id ="FormularioFin" name ="FormularioFin" class="card shadow mb-1"></div>
                        <div class="card border-left-info shadow h-60 py-0">
                            <div class="card-header">
                                Mis actividades
                            </div>
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col-12 col-md-3 col-sm-6 mb-2">
                                        <table id="tablaActividades" name="tablaActividades" class="table responsive table-striped table-sm">
                                            <thead >
                                                <tr class="table-primary">
                                                    <th>Servicio</th>
                                                    <th>Fecha Creacion</th>
                                                    <th>OV</th>
                                                    <th>OT</th>
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
                                </div>
                            </div>
                            <div class="card-footer">
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
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
    
        $(document).ready(function () {

            $('#tablaActividades').DataTable();
        });
        
        function showModalActividades(type, id) {
                var myModal = new bootstrap.Modal(document.getElementById('modalActividades'));
                myModal.show();
                
                $.ajax({
                    url: 'acciones_tiempo.php',
                    type: 'POST',
                    data: { accion: 'llenaTablaActividades'  },
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
                        //console.error('Error al obtener los registros:', textStatus, errorThrown);
                    }
                });
            
        }

    </script>
</body>
</html>