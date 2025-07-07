<?php
require BASE_PATH . "src/auth.php"; 
include_once BASE_PATH . "objects/objects.php";

$siteAdmin = new SITE_ADMIN(); 
$clientes = $siteAdmin->getClienteInfo(USER_ID);
$tipos = $siteAdmin->getTiposLocalInfo();
$faxinas = $siteAdmin->getFaxinasInfo(USER_ID);

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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> 
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
                        <div class="toll-free-box text-center" style="cursor: pointer; background-color: #8c52ff; color: white;" data-bs-toggle="modal" data-bs-target="#modalAgendamento">
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
                                             <?php foreach ($faxinas as $item): ?>

                                                <?php
                                                    $dataOriginal = $item["FXA_DTDATA"];
                                                    $dataConvertida = DateTime::createFromFormat('Y-m-d H:i:s', $dataOriginal)->format('d/m/Y H:i:s');

                                                ?>
                                                <div class="d-flex border-top py-2 px-1">
                                                    <div class="col-2"><img src="../../assets/img/avatarAgenda.png" alt="Product Image" class="img-size-50" /></div>
                                                    <div class="col-10">
                                                        <a href="javascript:void(0)" class="fw-bold">
                                                            <?php echo mb_convert_case($item["CLI_DCNOME"], MB_CASE_TITLE, "UTF-8"); ?>
                                                            <span class="badge text-bg-warning float-end">
                                                                R$<?php echo $item["FXA_NMPRECO_COMBINADO"]; ?>
                                                            </span>
                                                        </a>
                                                        <div class="text-truncate">
                                                            <strong>Data: </strong> <?php echo $dataConvertida; ?>
                                                        </div>
                                                        <div class="text-truncate">
                                                            <strong>Tipo de Faxina:</strong> </strong> <?php echo mb_convert_case($item["TLO_DCNOME"], MB_CASE_TITLE, "UTF-8"); ?>
                                                        </div>
                                                        <div class="text-truncate">
                                                            <?php echo mb_convert_case($item["CLI_DCBAIRRO"], MB_CASE_TITLE, "UTF-8"); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
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
            <div class="modal-header d-flex justify-content-between align-items-center" style="background-color: #086683; color: #000000;">
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
                                              <label class="form-label" for="cliente">Cliente</label>
                                              <select id="cliente" name="cliente" class="form-control select2" required>
                                                <option value="">Selecione um cliente</option>
                                                <?php foreach ($clientes as $cliente): ?>
                                                  <option value="<?= $cliente['CLI_IDCLIENTE'] ?>">
                                                    <?= htmlspecialchars(mb_convert_case($cliente['CLI_DCNOME'], MB_CASE_TITLE, 'UTF-8'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                                                  </option>
                                                <?php endforeach; ?>
                                              </select>
                                            </div>

                                            <div class="position-relative mb-3" id="campo-tipo">
                                              <label class="form-label" for="cliente">Tipo de Local</label>
                                              <select id="tipo" name="tipo" class="form-control select2" required>
                                                <option value="">Selecione o tipo</option>
                                                <?php foreach ($tipos as $tipo): ?>
                                                  <option value="<?= $tipo['TLO_IDTIPOLOCAL'] ?>">
                                                    <?= htmlspecialchars(mb_convert_case($tipo['PLO_DCNOME'], MB_CASE_TITLE, 'UTF-8'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                                                  </option>
                                                <?php endforeach; ?>
                                              </select>
                                            </div>

                                            <div class="position-relative mb-3" id="campo-duracao">                                                    
                                              <label class="form-label" for="duracao">Duração Estimada (em horas)</label>
                                              <input type="text" id="duracao" name="duracao" class="form-control" maxlength="2"
                                                     oninput="this.value = this.value.replace(/\D/g, '').slice(0,2); if (parseInt(this.value) > 99) this.value = '99';"
                                                     placeholder="0 a 99">
                                            </div>

                                            <div class="position-relative mb-3" id="campo-preco">                                                    
                                              <label class="form-label" for="preco">Preço</label>
                                              <input type="text" id="preco" name="preco" class="form-control" placeholder="R$ 0,00">
                                            </div>
                                            
                                            <div class="position-relative mb-3">
                                                <label for="data" class="form-label">Data</label>
                                                <input type="text" class="form-control" id="dataHora" name="dataHora" placeholder="Selecione data e hora">
                                            </div>   
                                            
                                            <div class="position-relative mb-3">
                                              <label for="observacao" class="form-label">Observações</label>
                                              <textarea class="form-control" maxlength="300" rows="3" id="observacao" name="observacao" placeholder=""></textarea>
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
                  <img src="../../assets/img//meluza_logo_90.png" alt="Logo" style="height: 30px;">
                <div>
                    <a href="javascript:void(0);" class="btn" style="background-color: #6e6c72; color: white;" data-bs-dismiss="modal">Fechar</a>
                    <button type="button" class="btn" style="background-color: #7eda0d; color: black;" id="botaoAgendar">Agendar</button>
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

<script>
  $(document).ready(function() {
    $('#preco').mask('R$ 000.000.000,00', {reverse: true});
  });
</script>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
    // Inicializa o Select2 ao abrir o modal
    document.addEventListener('DOMContentLoaded', function () {
      // Caso o modal já esteja visível ou o select esteja fora do modal
      $('#cliente').select2({
        dropdownParent: $('#modalAgendamento'),
        placeholder: "Selecione um cliente",
        width: '100%',
        allowClear: true
      });

      // Garante que ao abrir o modal novamente, o Select2 será inicializado corretamente
      $('#modalAgendamento').on('shown.bs.modal', function () {
        $('#cliente').select2({
          dropdownParent: $('#modalAgendamento'),
          placeholder: "Selecione um cliente",
          width: '100%',
          allowClear: true
        });
      });
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

  <script>
    function confirmAndSubmit(event) {
      event.preventDefault(); 
    
      var form = document.getElementById("form");    
    
      Swal.fire({
        title: 'Agendamento de Faxina',
        text: 'Tem certeza que deseja agendar a faxina?',
        icon: 'warning',
        showDenyButton: true,
        confirmButtonText: 'CONFIRMAR',
        denyButtonText: 'CANCELAR',
        confirmButtonColor: "#4caf50",  // verde
        denyButtonColor: "#9e9e9e",     // cinza
        background: "#f9f9fb",          // fundo claro
        color: "#333",                  // texto escuro
        width: '420px'
      }).then((result) => {
        if (result.isConfirmed) {
        
          // Mostra o alerta de carregamento
          Swal.fire({
            title: 'Enviando dados...',
            text: 'Aguarde enquanto processamos o cadastro.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
              Swal.showLoading();
            },
            background: "#f9f9fb",
            color: "#333",
            width: '420px'
          });
      
          var formData = new FormData($("#form")[0]);
      
          $.ajax({
            url: "/insertFaxinaProc",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (response) {
              Swal.close(); // Fecha o loading
            
              if (response.success) {
                Swal.fire({
                  title: 'Sucesso!',
                  text: response.message,
                  icon: 'success',
                  width: '420px',
                  confirmButtonColor: "#4caf50",
                  background: "#f9f9fb",
                  color: "#333"
                }).then(() => {
                  window.location.href = "/agenda";
                });
              } else {
                Swal.fire({
                  title: 'Erro!',
                  text: response.message,
                  icon: 'error',
                  width: '420px',
                  confirmButtonColor: "#f44336",
                  background: "#f9f9fb",
                  color: "#333"
                });
              }
            },
            error: function (xhr, status, error) {
              Swal.close(); // Fecha o loading
            
              Swal.fire({
                title: 'Erro!',
                text: 'Erro ao cadastrar o cliente: ' + error,
                icon: 'error',
                width: '420px',
                confirmButtonColor: "#f44336",
                background: "#f9f9fb",
                color: "#333"
              });
            }
          });
      
        } else if (result.dismiss === Swal.DismissReason.cancel || result.isDenied) {
          Swal.fire({
            title: 'Cancelado',
            text: 'Nenhuma alteração foi feita.',
            icon: 'info',
            width: '420px',
            confirmButtonColor: "#9e9e9e",
            background: "#f9f9fb",
            color: "#333"
          });
        }
      });
    }

    $(document).ready(function () {
      $("#botaoAgendar").on("click", confirmAndSubmit);
    });
  </script>

	   <?php include_once BASE_PATH . "src/config.php"; ?>
   </body>
</html>