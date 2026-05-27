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
                    <div id="collapseFormularioInicio" name="collapseFormularioInicio" class="collapse mb-3">
                        <div class="card border-start border-success border-2 shadow-sm">
                            <div class="card-header bg-transparent py-2 fs-6 d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-success">Servicio Nuevo</span>
                                <span id="totalHorasBadge" class="badge bg-secondary">Total: 0 hrs</span>
                            </div>
                            <div class="card-body p-2.5">
                                <form id="form" name="form">
                                    <div class="row g-2">
                                        
                                        <div class="col-12 col-sm-6">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text bg-light text-secondary fw-semibold" style="width: 75px; justify-content: center;">Ejecución</span>
                                                <input type="date" id="fecha_ejecucion" name="fecha_ejecucion" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-6">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text bg-light text-secondary fw-semibold" style="width: 75px; justify-content: center;">Tipo</span>
                                                <select id="tipo_servicio" name="tipo_servicio" class="form-select" required>
                                                    <option value="">Selecciona...</option>
                                                    <option value="Externo">Externo</option>
                                                    <option value="Interno">Interno</option>
                                                </select>
                                            </div>
                                        </div>

                                        <input type="hidden" id="ov" name="ov" value="">

                                        <div class="col-12 mt-3">
                                            <div class="bg-light p-2 rounded border">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <small class="fw-bold text-secondary text-uppercase" style="font-size: 0.75rem;">Desglose de Actividades</small>
                                                    <button type="button" class="btn btn-outline-success btn-xs py-0 px-2" style="font-size: 0.75rem;" onclick="agregarRenglonDinamico()">
                                                        <i class="bi bi-plus-circle"></i> + Agregar Renglón
                                                    </button>
                                                </div>
                                                
                                                <div id="contenedorDinamico">
                                                    <div class="row g-1 mb-2 alineacion-renglon align-items-center">
                                                        <div class="col-5">
                                                            <select name="area[]" class="form-select form-select-sm" required>
                                                                <option value="">Área...</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-4">
                                                            <input type="text" name="ot[]" class="form-control form-control-sm" placeholder="OT" required>
                                                        </div>
                                                        <div class="col-2">
                                                            <input type="number" name="tiempo[]" class="form-control form-control-sm campo-tiempo" placeholder="Hrs" min="0.1" max="4" step="0.1" onchange="calcularTotalHoras()" required>
                                                        </div>
                                                        <div class="col-1 text-center">
                                                            <button type="button" class="btn btn-sm text-danger p-0" onclick="eliminarRenglonDinamico(this)" disabled>
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-12 mt-2">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text bg-light text-secondary fw-semibold d-none d-sm-flex" style="width: 75px; justify-content: center;">Obs.</span>
                                                <textarea id="comentarios" name="comentarios" rows="1" class="form-control" placeholder="Comentarios u observaciones..."></textarea>
                                            </div>
                                            <input type="hidden" id="coordenadas" name="coordenadas">
                                        </div>
                                    </div>
                                    
                                    <input id="accion" name="accion" type="hidden" value="nuevo">
                                    
                                    <div class="text-center mt-3">
                                        <button type="button" class="btn btn-success btn-sm px-5 shadow-sm fw-semibold" id="Confirmar" onClick="validarYEnviar()">
                                            Confirmar Registro
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- SIN AUTORIZAR-->
                    <div id="collapseSinAutorizar" name="collapseSinAutorizar" class="collapse mb-3">
                        <div class="card border-start border-warning border-2 shadow-sm">        
                            <div class="card-header bg-transparent fw-bold text-warning py-2 fs-6">
                                Servicios sin autorizar
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table id="tablaSinAutorizar" name="tablaSinAutorizar" class="table table-striped table-hover table-sm align-middle mb-0" style="font-size: 0.85rem;">
                                        <thead>
                                            <tr class="table-light text-secondary">
                                                <th class="">Fecha</th>
                                                <th class="py-2">OTs</th>
                                                <th class="py-2">Ing</th>
                                                <th class="py-2">T. Serv</th>
                                                <th class="pe-2 py-2 text-end">Acciones</th> 
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
    <!-- Funciones Globales -->
    <script src="../loginMaster/funcionesGlobales.js"></script>

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
            $('#tablaSinAutorizar').css('font-size', '11px');
            
            // Cuando cualquier botón con "data-bs-toggle=collapse" sea clickeado
            $('[data-bs-toggle="collapse"]').on('click', function () {
                // Cierra todos los collapses
                $('.collapse').collapse('hide');
                
                // Abre el collapse clickeado
                var target = $(this).data('bs-target');
                $(target).collapse('show');
            });

        });                
        
        async function nuevoServicio(){
            const form = document.querySelector("#form"); // Obtener el formulario
            if (!form.checkValidity()) {  // Verificar si el formulario es válido
                Swal.fire({
                    icon: "warning",
                    text: "Por favor, completa todos los campos requeridos.",
                });
                return;  // No continuar si no es válido
            }  
            
            const datosGerencia = await verificarGerencia(); 
            let cuantos = 0;
            let inf_adicional = '';

            if (datosGerencia) {
                cuantos = datosGerencia.cuantos;
                inf_adicional = datosGerencia.inf_adicional;
                console.log('Acceso concedido:', cuantos, inf_adicional);
            } else {
                console.log('Sin acceso de gerencia');
            }
            
            // [NUEVO]: Serializamos todo el formulario dinámico completo
            let datosFormulario = $('#form').serialize();
            
            // Le concatenamos los parámetros adicionales calculados y la acción
            datosFormulario += `&accion=nuevoServicio&cuantos=${cuantos}&inf_adicional=${encodeURIComponent(inf_adicional)}`;
            
            $.ajax({
                    url: 'acciones_inicio.php',
                    method: 'POST',
                    dataType: 'json',
                    data: datosFormulario, // [NUEVO]: Enviamos el paquete completo unificado
                    success: function(data) {
                        Swal.fire({
                            icon: "success",
                            text: "Se procesó con éxito.",
                        });
                        llenaTablaSinAuto();
                        limpiarFormulario(); 
                        
                        // Ocultar y mostrar contenedores
                        $('#collapseFormularioInicio').collapse('hide');
                        $('#collapseSinAutorizar').collapse('show');
                        //enviaCorreo();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            icon: "info",
                            text: "¡Atención! Tu servicio no se pudo procesar.",
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
            noEmpleado = <?php echo $_COOKIE["noEmpleado"]; ?>;   
            
            $.ajax({
                    url: 'acciones_inicio.php',
                    type: 'POST',
                    data: { accion: 'llenaTablaSinAuto'},
                    dataType: 'json', 
                    success: function(registros2) {
                        var table = $('#tablaSinAutorizar').DataTable();
                        
                        table.clear().draw();
                        
                        registros2.forEach(function(Registro) {
                            BotonesG = '';
                            Botones = '';
                            if (rolUsuario == 2 || rolUsuario == 3 || rolUsuario == 4) {
                                if (noEmpleado == 521) {
                                    if (Registro.autoriza_gerencia == "Por Autorizar") {
                                        BotonesG = `<button class="btn btn-success btn-sm" onclick="autorizarServicioG(${Registro.id}, 'Autorizado')"><i class="fa fa-check"></i></button>
                                            <button class="btn btn-danger btn-sm" onclick="autorizarServicioG(${Registro.id}, 'Rechazado')"><i class="fa fa-times"></i></button>`;                                        
                                    }
                                    else{
                                        BotonesG = `<span class="badge text-bg-success">Autorizo Gcia</span>`;
                                    }
                                    if (Registro.autoriza_jefe == "Por Autorizar") {
                                        Botones = `<span class="badge text-bg-warning">Validando</span>`;
                                    }else{
                                        Botones = `<span class="badge text-bg-success">Autorizo Jefe</span>`;
                                    }
                                } else {
                                    if (Registro.autoriza_jefe == "Por Autorizar") {
                                        Botones = `<button class="btn btn-primary btn-sm" onclick="autorizarServicio(${Registro.id}, 'Autorizado')"><i class="fa fa-check"></i></button>
                                            <button class="btn btn-danger btn-sm" onclick="autorizarServicio(${Registro.id}, 'Rechazado')"><i class="fa fa-times"></i></button>`;
                                    }else{
                                        Botones = `<span class="badge text-bg-success">Autorizado</span>`;
                                    }
                                    if (Registro.autoriza_gerencia == "Por Autorizar") {
                                        BotonesG = `<span class="badge text-bg-warning">Validando Gcia</span>`;
                                    }else{
                                        BotonesG = `<span class="badge text-bg-success">Autorizo Gcia</span>`;
                                    }                                    
                                }
                            }
                            else{                                
                                if (Registro.autoriza_jefe == "Por Autorizar") {
                                    Botones = `<span class="badge text-bg-warning">Validando</span>`;
                                }else{
                                    Botones = `<span class="badge text-bg-success">Autorizado</span>`;
                                }
                                if (Registro.autoriza_gerencia == "Por Autorizar") {
                                    BotonesG = `<span class="badge text-bg-warning">Validando Gcia</span>`;
                                }else{
                                    BotonesG = `<span class="badge text-bg-success">Autorizado Gcia</span>`;
                                }                                    
                            }
                            
                            table.row.add([
                                Registro.fecha_creacion,
                                (Registro.ots || '').replace(/,/g, '<br>'), 
                                Registro.ingeniero,
                                Registro.tipo_s,
                                Botones + BotonesG 
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

        function autorizarServicioG(idServicio, estatus) {
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
                        data: {accion: 'autorizarServicioG', idServicio, estatus},
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

        async function verificarGerencia() {
            try {
                // 1. Esperamos la respuesta
                const respuesta = await validaOpcionesSistema('hrsExtra', 'autorizaGerencia');
                
                // 2. Usamos Optional Chaining (?.) para evitar errores si data no existe
                const data = respuesta?.data?.[0];
                const cuantos = data ? parseInt(data.cuantos) : 0;

                //alert('Cuantos: ' + cuantos);

                if (cuantos <= 0) {            
                    return null; // Es más claro devolver null que undefined
                } else {            
                    // Devolvemos el objeto completo (cuantos e inf_adicional)
                    return data;
                }
            } catch (error) {
                //console.error("Error en verificarGerencia:", error);
                return null;
            }
        }

// Variable global para almacenar las áreas una vez cargadas desde el servidor
let listaAreasGlobal = [];

function muestraDepto() {
    var accion = "verDepto";
    
    $.ajax({
        url: 'acciones_inicio.php',
        method: 'POST',
        dataType: 'json',
        data: { accion: accion },
        success: function(data) {
            // Guardamos los datos en nuestra variable global
            listaAreasGlobal = data;
            
            // Llenamos el primer select que ya existe en el HTML
            llenarSelectAreas($('select[name="area[]"]'));
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.fire({
                icon: "error",
                text: "¡No se pudieron cargar las áreas!",
            });
        }
    });
}

// Función auxiliar para rellenar CUALQUIER select de áreas con la variable global
function llenarSelectAreas(elementoSelect) {
    // Limpiamos las opciones previas dejando solo la opción por defecto
    elementoSelect.html('<option value="">Área...</option>');
    
    // Recorremos la variable global y añadimos las opciones
    listaAreasGlobal.forEach(function(area) {
        var option = $('<option></option>')
            .attr('value', area.clave)
            .text(area.clave + ' - ' + area.area);
        elementoSelect.append(option);
    });
}

// MODIFICACIÓN A TU FUNCIÓN ANTERIOR:
function agregarRenglonDinamico() {
    const contenedor = document.getElementById('contenedorDinamico');
    const nuevoRenglon = document.createElement('div');
    nuevoRenglon.className = 'row g-1 mb-2 alineacion-renglon align-items-center';
    
    // Nota que el select inicia vacío, lo llenaremos justo abajo mediante jQuery
    nuevoRenglon.innerHTML = `
        <div class="col-5">
            <select name="area[]" class="form-select form-select-sm" required>
                <option value="">Área...</option>
            </select>
        </div>
        <div class="col-4">
            <input type="text" name="ot[]" class="form-control form-control-sm" placeholder="OT" required>
        </div>
        <div class="col-2">
            <input type="number" name="tiempo[]" class="form-control form-control-sm campo-tiempo" placeholder="Hrs" min="0.1" max="4" step="0.1" onchange="calcularTotalHoras()" required>
        </div>
        <div class="col-1 text-center">
            <button type="button" class="btn btn-sm text-danger p-0" onclick="eliminarRenglonDinamico(this)">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    `;
    
    contenedor.appendChild(nuevoRenglon);
    
    // Buscamos el select de ESTE nuevo renglón específico y lo llenamos al instante
    const nuevoSelect = $(nuevoRenglon).find('select[name="area[]"]');
    llenarSelectAreas(nuevoSelect);
    
    actualizarBotonesEliminar();
}

// Función para eliminar un renglón específico
function eliminarRenglonDinamico(boton) {
    boton.closest('.row').remove();
    calcularTotalHoras();
    actualizarBotonesEliminar();
}

// Controla que no se pueda borrar el último renglón obligatorio
function actualizarBotonesEliminar() {
    const renglones = document.querySelectorAll('#contenedorDinamico .row');
    if (renglones.length === 1) {
        renglones[0].querySelector('.text-danger').disabled = true;
    } else {
        renglones.forEach(r => r.querySelector('.text-danger').disabled = false);
    }
}

// Suma el tiempo de todas las OTs agregadas en tiempo real
function calcularTotalHoras() {
    let total = 0;
    const camposTiempo = document.querySelectorAll('.campo-tiempo');
    
    camposTiempo.forEach(campo => {
        const valor = parseFloat(campo.value);
        if (!isNaN(valor)) {
            total += valor;
        }
    });
    
    const badge = document.getElementById('totalHorasBadge');
    badge.innerText = `Total: ${total.toFixed(1)} hrs`;
    
    if (total > 4) {
        badge.className = "badge bg-danger text-white";         
    } else {
        badge.className = "badge bg-secondary text-white";
    }
    
    return total;
}

// Validación antes de enviar
function validarYEnviar() {
    const totalHoras = calcularTotalHoras();
    
    if (totalHoras > 4) {
        //alert(`❌ Error: La sumatoria de horas (${totalHoras.toFixed(1)} hrs) no puede ser mayor a 4 horas.`);
        swal.fire({
            icon: "error",
            title: "¡Error!",
            text: `La sumatoria de horas (${totalHoras.toFixed(1)} hrs) no puede ser mayor a 4 horas.`,
        });
        return;
    }
    
    const formulario = document.getElementById('form');
    if (formulario.checkValidity()) {
        nuevoServicio(); 
    } else {
        formulario.reportValidity();
    }
}
    </script>
</body>
</html>
