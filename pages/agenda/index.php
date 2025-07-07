<?php
require BASE_PATH . "src/auth.php"; 
include_once BASE_PATH . "objects/objects.php";

$siteAdmin = new SITE_ADMIN(); 
$clientes = $siteAdmin->getClienteInfo(USER_ID);
$tipos = $siteAdmin->getTiposLocalInfo();
$faxinasValendario = $siteAdmin->getFaxinasCalendarioInfo(USER_ID);

// Verifica se recebeu intervalo via GET
if (isset($_GET['data_inicio']) && isset($_GET['data_fim'])) {
    try {
        $dataInicio = (new DateTime($_GET['data_inicio']))->format('Y-m-d H:i:s');
        $dataFim = (new DateTime($_GET['data_fim']))->format('Y-m-d H:i:s');
    } catch (Exception $e) {
        // Em caso de erro no formato da data, define intervalo padrão (hoje até 2 mes a frente)
        $dataInicio = (new DateTime())->setTime(0, 0)->format('Y-m-d H:i:s');
        $dataFim = (new DateTime())->modify('+1 month')->setTime(23, 59, 59)->format('Y-m-d H:i:s');
    }
} else {
    // Intervalo padrão: (hoje até 2 mes a frente)
    $dataInicio = (new DateTime())->setTime(0, 0)->format('Y-m-d H:i:s');
    $dataFim = (new DateTime())->modify('+1 month')->setTime(23, 59, 59)->format('Y-m-d H:i:s');
}

$faxinas = $siteAdmin->getFaxinasInfo(USER_ID,$dataInicio,$dataFim);

$faxinasAgendadas = "0";
$faxinasConcluidas = "0";
$faxinasTotal = "0";
$faxinasGanhosPrevistos = "0";
$faxinasGanhosAcumulados = "0";

foreach ($faxinas as $item) {
    if (isset($item['FXA_STSTATUS']) && strtoupper($item['FXA_STSTATUS']) === 'CONCLUÍDA') {
        $faxinasConcluidas++;
    }
    if (isset($item['FXA_STSTATUS']) && strtoupper($item['FXA_STSTATUS']) === 'PROGRAMADA') {
        $faxinasAgendadas++;
    }  
 
    if (isset($item['FXA_NMPRECO_COMBINADO']) && is_numeric($item['FXA_NMPRECO_COMBINADO']) && $item['FXA_STSTATUS'] === 'PROGRAMADA') {
        $faxinasGanhosPrevistos += (float)$item['FXA_NMPRECO_COMBINADO'];
    }

    if (isset($item['FXA_NMPRECO_COMBINADO']) && is_numeric($item['FXA_NMPRECO_COMBINADO']) && $item['FXA_STSTATUS'] === 'CONCLUÍDA') {
        $faxinasGanhosAcumulados += (float)$item['FXA_NMPRECO_COMBINADO'];
    }

    $faxinasTotal++;
}

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
    <style>
        .dia-ocupado.day::after {
            content: "";
            display: block;
            width: 9px;
            height: 9px;
            background-color:rgb(255, 0, 0);
            border-radius: 50%;
            margin: 2px auto 0;
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
                                   <span class="info-box-icon shadow-sm" style="background-color: #c9026f; color: #fff;">	<i class="bi bi-calendar"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Faxinas Agendadas</span>
                                        <span class="info-box-number">
                                            <?php echo $faxinasAgendadas; ?> 
                                            <small> Agendadas</small>
                                        </span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <!-- /.col -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon shadow-sm" style="background-color: #c9026f; color: #fff;"> <i class="bi bi-check-lg"></i> </span>
                                    <div class="info-box-content"><span class="info-box-text">Faxinas Concluídas</span> <span class="info-box-number"><?php echo $faxinasConcluidas; ?><small> Concluídas</small></span></div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <!-- /.col -->
                            <!-- fix for small devices only -->
                            <!-- <div class="clearfix hidden-md-up"></div> -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon shadow-sm" style="background-color: #c9026f; color: #fff;"> <i class="bi bi-piggy-bank"></i> </span>
                                    <div class="info-box-content"><span class="info-box-text">Ganhos Acumulados</span> <span class="info-box-number">R$<?php echo $faxinasGanhosAcumulados; ?><small> Recebidos</small></span></div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <!-- /.col -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon shadow-sm" style="background-color: #c9026f; color: #fff;"> <i class="bi bi-currency-dollar"></i> </span>
                                    <div class="info-box-content"><span class="info-box-text">Ganhos Previstos</span> <span class="info-box-number">R$<?php echo $faxinasGanhosPrevistos; ?><small> à receber</small></span></div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <!-- /.col -->
                    </div>
                    <!-- /.row -->
                     <br>
                    <div class="row">
                        <div class="toll-free-box text-center" style="cursor: pointer; background-color: #8c52ff; color: white;" onclick="abrirModalNovo()">
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
                                            
                                                    $dataOriginal = $item["FXA_DTDATA"];
                                                    $data = new DateTime($dataOriginal);

                                                    // Mapeia os dias da semana em inglês para português
                                                    $diasSemana = [
                                                        'Sunday'    => 'Domingo',
                                                        'Monday'    => 'Segunda-feira',
                                                        'Tuesday'   => 'Terça-feira',
                                                        'Wednesday' => 'Quarta-feira',
                                                        'Thursday'  => 'Quinta-feira',
                                                        'Friday'    => 'Sexta-feira',
                                                        'Saturday'  => 'Sábado',
                                                    ];

                                                    // Pega o nome do dia em inglês
                                                    $diaIngles = $data->format('l');

                                                    // Traduz para português
                                                    $diaSemana = $diasSemana[$diaIngles];

                                                    if ($item["FXA_STSTATUS"] == "PROGRAMADA")   $badgeColor = "info";
                                                    if ($item["FXA_STSTATUS"] == "CONCLUÍDA")    $badgeColor = "success";
                                                    if ($item["FXA_STSTATUS"] == "ATRASADA")     $badgeColor = "danger";
                                            
                                                    $nomeCliente = mb_convert_case($item["CLI_DCNOME"], MB_CASE_TITLE, "UTF-8");
                                                    $nomeExibido = mb_strlen($nomeCliente) > 13 ? mb_substr($nomeCliente, 0, 13) . '...' : $nomeCliente;
                                            
                                                ?>
                                                <div class="d-flex flex-wrap border-top py-3 px-1 align-items-center faxina-item"
                                                  data-faxinaId="<?= $item['FXA_IDFAXINA'] ?>"
                                                  data-idcliente="<?= $item['CLI_IDCLIENTE'] ?>"
                                                  data-idtipo="<?= $item['FXA_DCTIPO'] ?>"
                                                  data-duracao="<?= $item['FXA_DCDURACAO_ESTIMADA'] ?>"
                                                  data-preco="<?= $item['FXA_NMPRECO_COMBINADO'] ?>"
                                                  data-data="<?= $item['FXA_DTDATA'] ?>"
                                                  data-rua="<?= $item['CLI_DCENDERECO'] ?>"
                                                  data-bairro="<?= $item['CLI_DCBAIRRO'] ?>"
                                                  data-numero="<?= $item['CLI_DCNUM_ENDERECO'] ?>"
                                                  data-cidade="<?= $item['CLI_DCCIDADE'] ?>"
                                                  data-telefone="<?= $item['CLI_DCTELEFONE'] ?>"
                                                  data-estado="<?= $item['CLI_DCESTADO'] ?>"
                                                  data-complemento="<?= $item['CLI_DCCOMPLEMENTO'] ?>"
                                                  data-observacao="<?= htmlspecialchars($item['FXA_DCOBS'] ?? '') ?>"                                                 
                                                  style="cursor: pointer;"
                                                >
                                                    <div class="me-3 mb-2" style="flex: 0 0 50px;">
                                                        <img src="../../assets/img/avatarAgenda.png" alt="Imagem" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="fw-bold d-flex justify-content-between align-items-center flex-wrap">
                                                            <span><?php echo mb_convert_case($nomeExibido, MB_CASE_TITLE, "UTF-8"); ?></span>
                                                            <span class="badge text-bg-warning mt-1 mt-sm-0">
                                                                R$<?php echo $item["FXA_NMPRECO_COMBINADO"]; ?>
                                                            </span>
                                                        </div>
                                                        <div class="small text-muted">
                                                            <strong>Data:</strong> <?php echo $dataConvertida; ?>
                                                        </div>
                                                        <div class="small text-muted">
                                                            <strong>Dia:</strong> <?php echo $diaSemana; ?>
                                                        </div>
                                                        <div class="small text-muted">
                                                            <strong>Tipo de Faxina:</strong> <?php echo mb_convert_case($item["TLO_DCNOME"], MB_CASE_TITLE, "UTF-8"); ?>
                                                        </div>
                                                        <div class="mt-1">
                                                            <span class="badge text-bg-<?php echo $badgeColor; ?>">
                                                                <?php echo mb_convert_case($item["FXA_STSTATUS"], MB_CASE_TITLE, "UTF-8"); ?>
                                                            </span>
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
              <h4 class="modal-title text-white mb-0">faxina</h4>
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
                                            
                                          <input type="hidden" id="faxinaId" name="faxinaId" value="">

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

                                            <div class="row">
                                              <div class="col-md-6">
                                                <div class="position-relative mb-3" id="campo-tipo">
                                                  <label class="form-label" for="tipo">Tipo de Local</label>
                                                  <select id="tipo" name="tipo" class="form-control select2" required>
                                                    <option value="">Selecione o tipo</option>
                                                    <?php foreach ($tipos as $tipo): ?>
                                                      <option value="<?= $tipo['TLO_IDTIPOLOCAL'] ?>">
                                                        <?= htmlspecialchars(mb_convert_case($tipo['PLO_DCNOME'], MB_CASE_TITLE, 'UTF-8'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                                                      </option>
                                                    <?php endforeach; ?>
                                                  </select>
                                                </div>
                                              </div>
                                                    
                                              <div class="col-md-6">
                                                <div class="position-relative mb-3" id="campo-preco">
                                                  <label class="form-label" for="preco">Preço</label>
                                                  <input type="text" id="preco" name="preco" class="form-control" placeholder="R$ 0,00">
                                                </div>
                                              </div>
                                            </div>

                                            <div class="position-relative mb-3" id="campo-duracao">                                                    
                                              <label class="form-label" for="duracao">Duração Estimada (em horas)</label>
                                              <input type="text" id="duracao" name="duracao" class="form-control" maxlength="2"
                                                     oninput="this.value = this.value.replace(/\D/g, '').slice(0,2); if (parseInt(this.value) > 99) this.value = '99';"
                                                     placeholder="0 a 99">
                                            </div>
                                            
                                            <div class="position-relative mb-3">
                                                <label for="data" class="form-label">Data</label>
                                                <input type="text" class="form-control" id="dataHora" name="dataHora" placeholder="Selecione data e hora">
                                            </div>   
                                            
                                            <div class="position-relative mb-3">
                                              <label for="observacao" class="form-label">Observações</label>
                                              <textarea class="form-control" maxlength="300" rows="3" id="observacao" name="observacao" placeholder=""></textarea>
                                            </div>  
                                            
                                            <div class="position-relative mb-3 text-center">
                                              <small class="d-block mb-2 text-muted" id="texto-endereco" style="font-weight: normal; font-size: 12px;"></small>

                                              <div class="d-flex justify-content-center gap-3">
                                                <a id="iconeUber" href="javascript:void(0);" target="_blank">
                                                  <img src="../../assets/img/uber.png" alt="Uber" style="height: 40px;">
                                                </a>
                                                <a id="icone99" href="javascript:void(0);" target="_blank">
                                                  <img src="../../assets/img/99.png" alt="99" style="height: 40px;">
                                                </a>
                                              </div>
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
    const datasOcupadas = [
        <?php foreach ($faxinasValendario as $item): ?>
            '<?= date('d/m/Y', strtotime($item["FXA_DTDATA"])) ?>',
        <?php endforeach; ?>
    ];
</script>

<script>
    $(document).ready(function () {
        $('#calendario').datepicker({
            format: "dd/mm/yyyy",
            language: "pt-BR",
            todayHighlight: true,
            autoclose: true,
            beforeShowDay: function (date) {
                const dia = String(date.getDate()).padStart(2, '0');
                const mes = String(date.getMonth() + 1).padStart(2, '0');
                const ano = date.getFullYear();
                const dataFormatada = `${dia}/${mes}/${ano}`;

                if (datasOcupadas.includes(dataFormatada)) {
                    return {
                        classes: 'dia-ocupado',
                        tooltip: 'Dia com faxina'
                    };
                }

                return;
            }
        }).on('changeDate', function (e) {
            const date = e.date;

            const dia = String(date.getDate()).padStart(2, '0');
            const mes = String(date.getMonth() + 1).padStart(2, '0');
            const ano = date.getFullYear();

            // Monta as datas início e fim para o mesmo dia
            const dataInicio = `${ano}-${mes}-${dia} 00:00:00`;
            const dataFim = `${ano}-${mes}-${dia} 23:59:59`;

            // Redireciona com os parâmetros na URL
            const baseUrl = window.location.href.split('?')[0];
            const novaUrl = baseUrl + '?data_inicio=' + encodeURIComponent(dataInicio) + '&data_fim=' + encodeURIComponent(dataFim);
            window.location.href = novaUrl;
        });
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
    // Inicializa o Select2 ao abrir o modal
    document.addEventListener('DOMContentLoaded', function () {
      // Caso o modal já esteja visível ou o select esteja fora do modal
      $('#cliente').select2({
        dropdownParent: $('#modalAgendamento'),
        placeholder: "Selecione um clmodalAgendamentoEditariente",
        width: '100%',
        allowClear: true
      });

      // Garante que ao abrir o modal novamente, o Select2 será inicializado corretamente
      $('#modalAgendamentoEditar').on('shown.bs.modal', function () {
        $('#cliente').select2({
          dropdownParent: $('#modalAgendamentoEditar'),
          placeholder: "Selecione um cliente",
          width: '100%',
          allowClear: true
        });
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


<script>
    $(function () {
        const input = $('#intervaloDatas');

        // Função para obter os parâmetros da URL
        function getQueryParam(name) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
        }

        // Tenta obter data_inicio e data_fim da URL
        const dataInicioUrl = getQueryParam('data_inicio');
        const dataFimUrl = getQueryParam('data_fim');

        // Usa os valores da URL se existirem, senão usa padrão
        const start = dataInicioUrl ? moment(dataInicioUrl, 'YYYY-MM-DD HH:mm:ss') : moment().subtract(1, 'months').startOf('day');
        const end = dataFimUrl ? moment(dataFimUrl, 'YYYY-MM-DD HH:mm:ss') : moment().add(1, 'months').endOf('day');

        // Inicializa o date range picker
        input.daterangepicker({
            timePicker: true,
            timePicker24Hour: true,
            startDate: start,
            endDate: end,
            locale: {
                format: 'DD/MM/YYYY HH:mm',
                applyLabel: 'Aplicar',
                cancelLabel: 'Cancelar',
                fromLabel: 'De',
                toLabel: 'Até',
                customRangeLabel: 'Personalizado',
                weekLabel: 'S',
                daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                            'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                firstDay: 1
            }
        });

        // Preenche o input com o texto formatado
        input.val(start.format('DD/MM/YYYY HH:mm') + ' - ' + end.format('DD/MM/YYYY HH:mm'));

        // Evento ao clicar em Aplicar
        input.on('apply.daterangepicker', function (ev, picker) {
            const dataInicio = picker.startDate.format('YYYY-MM-DD HH:mm:ss');
            const dataFim = picker.endDate.format('YYYY-MM-DD HH:mm:ss');

            const baseUrl = window.location.href.split('?')[0];
            const novaUrl = baseUrl + '?data_inicio=' + encodeURIComponent(dataInicio) + '&data_fim=' + encodeURIComponent(dataFim);
            window.location.href = novaUrl;
        });
    });
</script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const isIOS = /iPhone|iPad|iPod/i.test(navigator.userAgent);
    const botao99 = document.getElementById("icone99");
    if (isIOS && botao99) {
      botao99.style.display = "none";
    }
  });
</script>

<script>  
  document.addEventListener("DOMContentLoaded", function () {
    const faxinaItens = document.querySelectorAll('.faxina-item');
    const modalEl = document.getElementById('modalAgendamento');
    const modal = new bootstrap.Modal(modalEl);

    async function buscarCoordenadas(endereco) {
      const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(endereco)}`;
      try {
        const resposta = await fetch(url, {
          headers: {
            'User-Agent': 'Codemaze/1.0 (suporte@codemaze.com.br)'
          }
        });
        const dados = await resposta.json();
        if (dados.length > 0) {
          return { lat: dados[0].lat, lon: dados[0].lon };
        } else {
          return null;
        }
      } catch (erro) {
        console.error('Erro ao buscar coordenadas:', erro);
        return null;
      }
    }

    function gerarLinkUber(lat, lon) {
      const nickname = encodeURIComponent("Cliente");
      return `https://m.uber.com/ul/?action=setPickup&dropoff%5Blatitude%5D=${lat}&dropoff%5Blongitude%5D=${lon}&dropoff%5Bnickname%5D=${nickname}`;
    }

    function gerarLink99(lat, lon) {
      return `https://app.99app.com/open?pickup=my_location&destination=${lat},${lon}`;
    }

    async function atualizarIconesUber99(rua, numero, bairro, cidade, estado) {
      const enderecoCompleto = `${rua} ${numero}, ${cidade}, ${estado}`;
      const uberIcon = document.getElementById('iconeUber');
      const noventaENoveIcon = document.getElementById('icone99');

      // Estado inicial
      uberIcon.href = 'javascript:void(0);';
      noventaENoveIcon.href = 'javascript:void(0);';
      uberIcon.style.opacity = '0.3';
      noventaENoveIcon.style.opacity = '0.3';
      uberIcon.style.pointerEvents = 'none';
      noventaENoveIcon.style.pointerEvents = 'none';
      uberIcon.title = 'Buscando endereço...';
      noventaENoveIcon.title = 'Buscando endereço...';

      try {
        const coords = await buscarCoordenadas(enderecoCompleto);
        if (coords) {
          // Ativa links
          uberIcon.href = gerarLinkUber(coords.lat, coords.lon);
          noventaENoveIcon.href = gerarLink99(coords.lat, coords.lon);
          uberIcon.style.opacity = '1';
          noventaENoveIcon.style.opacity = '1';
          uberIcon.style.pointerEvents = 'auto';
          noventaENoveIcon.style.pointerEvents = 'auto';
          uberIcon.title = 'Chamar Uber para ' + enderecoCompleto;
          noventaENoveIcon.title = 'Chamar 99 para ' + enderecoCompleto;
        } else {
          uberIcon.title = 'Endereço inválido para Uber';
          noventaENoveIcon.title = 'Endereço inválido para 99';
        }
      } catch (error) {
        console.error('Erro ao buscar coordenadas:', error);
        uberIcon.title = 'Erro ao buscar endereço';
        noventaENoveIcon.title = 'Erro ao buscar endereço';
      }
    }

    faxinaItens.forEach(item => {
      item.addEventListener('click', function () {
        document.getElementById('faxinaId').value = this.dataset.faxinaid || '';
        $('#cliente').val(this.dataset.idcliente).trigger('change');
        $('#tipo').val(this.dataset.idtipo).trigger('change');
        document.getElementById('duracao').value = this.dataset.duracao || '';
        document.getElementById('preco').value = formatarPreco(this.dataset.preco) || '';
        document.getElementById('dataHora').value = formatarDataHora(this.dataset.data) || '';
        document.getElementById('observacao').value = this.dataset.observacao || '';

        const rua = this.dataset.rua || '';
        const numero = this.dataset.numero || '';
        const bairro = this.dataset.bairro || '';
        const cidade = this.dataset.cidade || '';
        const estado = this.dataset.estado || '';

        const textoEndereco = document.getElementById('texto-endereco');
        if (textoEndereco) {
          textoEndereco.textContent = `${rua}, ${numero} - ${bairro}, ${cidade} - ${estado}`;
        }

        atualizarIconesUber99(rua, numero, bairro, cidade, estado);
        document.getElementById('botaoAgendar').textContent = 'Salvar';
        modal.show();
      });
    });

    window.abrirModalNovo = function () {
      document.getElementById('faxinaId').value = '';
      $('#cliente').val('').trigger('change');
      $('#tipo').val('').trigger('change');
      document.getElementById('duracao').value = '';
      document.getElementById('preco').value = '';
      document.getElementById('dataHora').value = '';
      document.getElementById('observacao').value = '';

      const textoEndereco = document.getElementById('texto-endereco');
      if (textoEndereco) textoEndereco.textContent = '';

      const uberIcon = document.getElementById('iconeUber');
      const noventaENoveIcon = document.getElementById('icone99');
      if (uberIcon) {
        uberIcon.href = 'javascript:void(0);';
        uberIcon.style.opacity = '0.3';
        uberIcon.style.pointerEvents = 'none';
        uberIcon.title = '';
      }
      if (noventaENoveIcon) {
        noventaENoveIcon.href = 'javascript:void(0);';
        noventaENoveIcon.style.opacity = '0.3';
        noventaENoveIcon.style.pointerEvents = 'none';
        noventaENoveIcon.title = '';
      }
      document.getElementById('botaoAgendar').textContent = 'Cadastrar';
      modal.show();
    };

    function formatarDataHora(dataISO) {
      if (!dataISO) return '';
      const dataCorrigida = dataISO.replace(' ', 'T');
      const dataObj = new Date(dataCorrigida);
      if (isNaN(dataObj.getTime())) return dataISO;

      const dia = String(dataObj.getDate()).padStart(2, '0');
      const mes = String(dataObj.getMonth() + 1).padStart(2, '0');
      const ano = dataObj.getFullYear();
      const hora = String(dataObj.getHours()).padStart(2, '0');
      const minuto = String(dataObj.getMinutes()).padStart(2, '0');

      return `${dia}/${mes}/${ano} ${hora}:${minuto}`;
    }

    function formatarPreco(valor) {
      const numero = parseFloat(valor);
      if (isNaN(numero)) return '';
      return numero.toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL'
      });
    }
  });
</script>








	   <?php include_once BASE_PATH . "src/config.php"; ?>
   </body>
</html>