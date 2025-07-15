<!DOCTYPE html>
<?php
?>

<html lang = "sp">
<head>
    <meta http-equiv = "X-UA-Compatible" content = "IE = edge">
    <meta name = "viewport" content = "width = device-width, initial-scale = 1, shrink-to-fit = no">
    <meta name = "description" content = "">
    <meta name = "author" content = "">

    <title>MESS TICKETS</title>

    <!-- Custom fonts for this template-->
    <link href = "vendor/fontawesome-free/css/all.min.css" rel = "stylesheet" type = "text/css">
    <link href = "https://fonts.googleapis.com/css?family = Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel = "stylesheet">

    <!-- Custom styles for this template-->
    <link href = "css/sb-admin-2.min.css" rel = "stylesheet">    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>

<body id = "page-top">

    <!-- Page Wrapper -->
    <div id = "wrapper">
        <?php
            session_start();
            include 'menu.php';
        ?>

        <!-- Content Wrapper -->
        <div id = "content-wrapper" class = "d-flex flex-column">

            <!-- Main Content -->
            <div id = "content">
            
                <?php
                    include 'encabezado.php';
                ?>

                <!-- Boton MODAL -->
                <div class="container-fluid">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop" id= "ModalTiempo">
                        Registrar Tiempo en Ticket
                    </button>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Registro de tiempo en ticket de servicio</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                          
                        <!-- Formulario -->
                        <form method="post" action="">
                            <div class="row">
                                <div class="col-sm-1"></div>
                                <div class="col-sm-3">
                                    <label for="id_usuario">Usuario:</label><br>
                                    <input type="text" name="usuario" id="id_usuario" value="<?php echo $ingeniero['usuario_nombre']; ?>" disabled class="form-control">
                                </div>
                                <div class="col-sm-3">
                                    <label for="fecha_inicio">Fecha y Hora de Inicio:</label><br>
                                    <input type="datetime-local" name="fecha_inicio" id="fecha_inicio" required class="form-control">
                                </div>
                                <div class="col-sm-3">
                                    <label for="fecha_fin">Fecha y Hora de Fin:</label><br>
                                    <input type="datetime-local" name="fecha_fin" id="fecha_fin" required class="form-control" onChange="calculo_horas()">
                                </div>
                            </div>
                            <br><hr><br>
                            
                            <div class="row">
                                <div class="col-sm-1"></div>
                                <div class="col-sm-3">
                                    <label for="tiempo">Tiempo Trabajado:</label><br>
                                    <input type="text" name="tiempo" id="tiempo" required class="form-control">
                                </div>
                                
                                <div class="col-sm-3">
                                    <label for="descripcion">Descripción</label><br>
                                    <textarea name="descripcion" id="descripcion" rows="2" cols="50" required class="form-control"></textarea>
                                </div>
                                <div class="col-sm-3">
                                    idTicket<input type="text" name="id_ticket" value="<?php echo $id_ticket; ?>"><br>
                                    id_Ing<input type="text" name="id_Ing" value="<?php echo $id_ticket; ?>"><br>
                                    <input type="submit" name="submit" class="btn btn-success" value="Guardar">
                                </div>
                            </div>
                        </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                      </div>
                    </div>
                  </div>
                </div>

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; MESS</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>    
    
    <script type="text/javascript">
        $(document).ready(function () {});
    
        // CALCULO DE HORAS
        function calculo_horas() {
            var fecha_Ini = new Date($('#fecha_inicio').val());
            var fecha_Fin = new Date($('#fecha_fin').val());
            var dif = fecha_Fin.getTime() - fecha_Ini.getTime();
            var hrs = dif / 3600000;
            document.getElementById('tiempo').value = hrs.toFixed(2);
        }
    </script>
</body>

</html>