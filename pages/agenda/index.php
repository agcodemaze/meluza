<?php
require BASE_PATH . "src/auth.php"; 
include_once BASE_PATH . "objects/objects.php";

$siteAdmin = new SITE_ADMIN(); 

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<?php include_once BASE_PATH . "src/head.php"; ?>
    <link href="../../vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.pt-BR.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- Tema opcional para estilo Bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">   
</head>

    <style>
        #calendario .datepicker {
            width: 100% !important;
        }

        #calendario {
            width: 100%;
            max-width: 100%;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .datepicker-inline {
        width: 100% !important;
        }

        .datepicker table {
            width: 100% !important;
            table-layout: fixed;
            font-size: 1.2rem;
        }

        .datepicker td, .datepicker th {
            width: 14.28% !important; /* 100 / 7 dias */
            text-align: center;
            padding: 1rem;
        }
    </style>

   <body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
      <div class="app-wrapper">
                  
	      <?php include_once BASE_PATH . "src/topbar.php"; ?>
	      <?php include_once BASE_PATH . "src/menu.php"; ?>

         <main class="app-main">
            <div class="app-content-header">
               <div class="container-fluid">
                  <div class="row">
                     <div class="col-sm-6">
                        <h3 class="mb-0">Agenda</h3>
                     </div>
                     <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                           <li class="breadcrumb-item"><a href="/inicial">Inicial</a></li>
                           <li class="breadcrumb-item active" aria-current="page"></li>
                        </ol>
                     </div>
                  </div>
               </div>
            </div>
            <div class="app-content">
                <div class="container-fluid">

                    <div class="row">
                      <div class="col-md-6">
                        <label>Selecione o Intervalo</label>
                        <input type="text" class="form-control" id="intervaloDatas" placeholder="Selecione o intervalo">
                      </div>
                    </div>
                    <br>

                    <div class="row">
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box">
                                   <span class="info-box-icon shadow-sm" style="background-color: #00c1fb; color: #fff;"><i class="bi bi-gear-fill"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Faxinas Agendadas</span>
                                        <span class="info-box-number">
                                            10
                                            <small></small>
                                        </span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <!-- /.col -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon shadow-sm" style="background-color: #00c1fb; color: #fff;"> <i class="bi bi-hand-thumbs-up-fill"></i> </span>
                                    <div class="info-box-content"><span class="info-box-text">Faxinas Concluídas</span> <span class="info-box-number">41,410</span></div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <!-- /.col -->
                            <!-- fix for small devices only -->
                            <!-- <div class="clearfix hidden-md-up"></div> -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon shadow-sm" style="background-color: #00c1fb; color: #fff;"> <i class="bi bi-cart-fill"></i> </span>
                                    <div class="info-box-content"><span class="info-box-text">Ganhos Acumulados</span> <span class="info-box-number">760</span></div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <!-- /.col -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon shadow-sm" style="background-color: #00c1fb; color: #fff;"> <i class="bi bi-people-fill"></i> </span>
                                    <div class="info-box-content"><span class="info-box-text">Clientes Cadastrados</span> <span class="info-box-number">2,000</span></div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <!-- /.col -->
                    </div>
                    <!-- /.row -->
                     <br>
<div class="row">
    <div class="toll-free-box text-center" style="cursor: pointer; background-color: #00c1fb;" data-bs-toggle="modal" data-bs-target="#modalAgendamento">
        <h4 class="text-reset"><i class="mdi mdi-deskphone"></i> Agendar Uma Nova Faxina</h4>
    </div>
</div>
                    <br>  
                    <div class="row">
                        <div class="col-12">
                            <div id="calendario" class="calendar-widget"></div>
                        </div>
                    </div>
                    <!-- end row -->
                    <br>          
                    <div class="row">
                            <div class="col-12">
                               <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Faxinas</h3>
                                        <div class="card-tools">
                                        </div>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body p-0">
                                        <div class="px-2">
                                            <div class="d-flex border-top py-2 px-1">
                                                <div class="col-2"><img src="/assets/img/default-150x150.png" alt="Product Image" class="img-size-50" /></div>
                                                <div class="col-10">
                                                    <a href="javascript:void(0)" class="fw-bold">
                                                        Samira Neves Orlando
                                                        <span class="badge text-bg-warning float-end">
                                                            R$180,00
                                                        </span>
                                                    </a>
                                                    <div class="text-truncate">
                                                        <strong>Data:</strong> 12/01/2025 ás 08:00
                                                    </div>
                                                    <div class="text-truncate">
                                                        <strong>Tipo de Faxina:</strong> Apartamento
                                                    </div>
                                                    <div class="text-truncate">
                                                        Bairro Jd Campos Verdes
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.item -->
                                            <div class="d-flex border-top py-2 px-1">
                                                <div class="col-2"><img src="/assets/img/default-150x150.png" alt="Product Image" class="img-size-50" /></div>
                                                <div class="col-10">
                                                    <a href="javascript:void(0)" class="fw-bold">
                                                        Juvenal Candido Motta
                                                        <span class="badge text-bg-info float-end">
                                                            R$220,00
                                                        </span>
                                                    </a>
                                                    <div class="text-truncate">
                                                        <strong>Data:</strong> 12/01/2025 ás 08:00
                                                    </div>
                                                    <div class="text-truncate">
                                                        <strong>Tipo de Faxina:</strong> Casa Grande
                                                    </div>
                                                    <div class="text-truncate">
                                                        Bairro Jd Campos Verdes
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                    </div>
                    <!-- end row -->
                </div>
            </div>
         </main>
	      <?php include_once BASE_PATH . "src/footer.php"; ?>
      </div>


    <!-- Modal Criar faxina-->
    <div id="modalAgendamento" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="primary-header-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center" style="background-color: #8c52ff;">
              <h4 class="modal-title text-white mb-0">Agendar uma faxina</h4>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div> 
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                        <div class="card">
                            <div class="card-body">


                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="tooltips-validation-preview">
                                            <form id="form" name="form" role="form" method="POST" enctype="multipart/form-data">                                       

                                                <div class="position-relative mb-3" id="campo-nome">
                                                  <label class="form-label" for="nome">Nome Completo</label>
                                                  <input id="nome" name="nome" autocomplete="new-nome" style="text-transform: uppercase;" type="text" class="form-control" placeholder="" maxlength="50"  oninput="this.value = this.value.replace(/[^\p{L} ]/gu, '')" required/>
                                                </div>

                                                <div class="position-relative mb-3" id="campo-telefone">                                                    
                                                  <label class="form-label" for="telefone">Telefone</label>
                                                  <input type="text" id="telefone" name="telefone" class="form-control" placeholder="(00) 00000-0000">
                                                </div> 

                                                

                                                <div class="position-relative mb-3">
                                                    <label for="data" class="form-label">Data</label>
                                                    <input type="text" class="form-control" id="dataHora" name="dataHora" placeholder="Selecione data e hora">
                                                </div>                                           
                                            </form>
                                        </div> <!-- end preview-->                                        
                                    </div> <!-- end tab-content-->


                            
                            </div> <!-- end card-body -->
                        </div> <!-- end card-->
                        </div> <!-- end col-->
                    </div> <!-- end row-->
                </div>
                <div class="modal-footer d-flex justify-content-between align-items-center">
                  <img src="../../img_pwa/logo_icon.png" alt="Logo" style="height: 30px;">
                <div>
                    <a href="javascript:void(0);" class="btn" style="background-color: #8c52ff; color: white;" data-bs-dismiss="modal">Fechar</a>
                    <button type="button" class="btn" style="background-color: #2be4c6; color: black;" id="botaoSalvar">Agendar</button>
                  </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->




        <script src="../../js/overlayscrollbars.browser.es6.min.js"></script>
        <script src="../../js/popper.min.js"></script>
        <script src="../../js/bootstrap.min.js"></script>
        <script src="../../js/adminlte.js"></script>
        <script src="https://cdn.datatables.net/plug-ins/1.13.4/sorting/datetime-moment.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        
<script>
    $(document).ready(function () {
        $('#calendario').datepicker({
            format: "dd/mm/yyyy",
            language: "pt-BR",
            todayHighlight: true,
            autoclose: true
        })
    });
</script>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- Inicialização -->
<script>
    flatpickr("#dataHora", {
        enableTime: true,              
        dateFormat: "d/m/Y H:i",       
        time_24hr: true,               
        locale: "pt",                  
        defaultHour: 12,               
        defaultMinute: 0,              
        minuteIncrement: 5             
    });
</script>

        <script>
            $(function() {
              $('#intervaloDatas').daterangepicker({
                locale: {
                  format: 'DD/MM/YYYY',
                  applyLabel: 'Aplicar',
                  cancelLabel: 'Cancelar',
                  daysOfWeek: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
                  monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
                  firstDay: 0
                }
              });
            });
        </script>

	   <?php include_once BASE_PATH . "src/config.php"; ?>
   </body>
</html>