<?php

date_default_timezone_set('America/Sao_Paulo');
$dataHoraServidor = date('Y-m-d H:i:s'); // hora atual do servidor

$urlWhatsConfirmConsul = "https://cliente.meluza.com.br"; //deverá ser alterado

//logica para horarios disponiveis
$horarios = [];
$inicio = $configuracoes[0]["CFG_DCHORA_EXPEDIENTE_INI"];
$fim = $configuracoes[0]["CFG_DCHORA_EXPEDIENTE_END"];
$hora = new DateTime($inicio);
$horaFim = new DateTime($fim);
$intervalo = new DateInterval('PT30M');

while ($hora <= $horaFim) {
    $horarios[] = $hora->format('H:i');
    $hora->add($intervalo);
}
//logica para horarios disponiveis

//trecho q reutilizo a url para criar os links de atalho para dias da semana
$currentUrl = $_SERVER['REQUEST_URI'];
$parsedUrl = parse_url($currentUrl);
$path = $parsedUrl['path'];
$query = $parsedUrl['query'] ?? '';
parse_str($query, $queryParams);
unset($queryParams['dia']);
$newQuery = http_build_query($queryParams);
$newUrl = $path . ($newQuery ? '?' . $newQuery : '');

$lang = $_GET['lang'] ?? 'pt';

if (isset($_GET['profissional_id'])) {
    $_SESSION['PROFISSIONAL_ID'] = $_GET['profissional_id'];
}

$profissionalId = $_SESSION['PROFISSIONAL_ID'] ?? 'all';
$dia = $_GET['dia'] ?? '1';

if ($profissionalId === "all" && !empty($dia)) {
    $consultasHoje = \App\Controller\Pages\Home::getConsultasByDayPredef($dia);
} elseif ($profissionalId === "all" && empty($dia)) {
    $consultasHoje = \App\Controller\Pages\Home::getConsultasByDayPredef("1"); 
} elseif ($profissionalId === "" && empty($dia)) {
    $consultasHoje = \App\Controller\Pages\Home::getConsultasByDayPredef(""); 
} elseif (!empty($profissionalId) && !empty($dia)) {
    $consultasHoje = \App\Controller\Pages\Home::getConsultasByDayProfPredef($profissionalId, $dia);
} elseif (!empty($profissionalId) && empty($dia)) {
    $consultasHoje = \App\Controller\Pages\Home::getConsultasByDayProfPredef($profissionalId, $dia);
}

$botaoStyleDeactiveOntem = "btn-soft-secondary ";
$botaoStyleDeactiveHoje = "btn-soft-secondary ";
$botaoStyleDeactiveAmanha = "btn-soft-secondary ";
$botaoStyleDeactiveTodos = "btn-soft-secondary ";

if(isset($_GET['dia'])) {
    if($_GET['dia'] == "1") { 
        $botaoStyleDeactiveHoje = "btn btn-primary"; 
        $textTitulo = "de_hoje";
    }elseif ($_GET['dia'] == "2") {
        $botaoStyleDeactiveUltimos6Meses = "btn btn-primary";
        $textTitulo = "ultimos_6_meses_desc";
    }elseif ($_GET['dia'] == "4") {
        $botaoStyleDeactiveUltimos12Meses = "btn btn-primary";
        $textTitulo = "ultimos_12_meses_desc";
    }elseif($_GET['dia'] == "3") {
        $botaoStyleDeactiveProximos6Meses = "btn btn-primary";
        $textTitulo = "proximos_6_meses_desc";
    }
} else {
    $botaoStyleDeactiveHoje = "btn btn-primary";
    $textTitulo = "de_hoje";
}

$totalConsultas = 0;
$confirmadas = 0;
$canceladas = 0;
$concluida = 0;

$totalConsultas = count($consultasHoje);

foreach ($consultasHoje as $consulta) {
    if ($consulta['CON_ENSTATUS'] === "CONFIRMADA") {
        $confirmadas++;
    }
    if ($consulta['CON_ENSTATUS'] === "CANCELADA") {
        $canceladas++;
    }
    if ($consulta['CON_ENSTATUS'] === "CONCLUIDA") {
        $concluida++;
    }
}

foreach ($consultasHoje as $c) {
    $consultasTimeline[] = [
        "data_hora" => $c['CON_DTCONSULTA'] . ' ' . $c['CON_HORACONSULTA'],
        "paciente" => $c['PAC_DCNOME'],
        "duracao" => (int)$c['CON_NUMDURACAO'],
        "status" => $c['CON_ENSTATUS']
    ];
}

?>

<style>
    #timeline {
        width: 100%;
        height: 300px;
        overflow-y: auto;
        border-radius: 12px;
        background: #f9fafb;
        padding: 10px;
    }

    /* Remove borda padrão da timeline */
    .vis-timeline {
        border: none !important;
    }

    /* Estilo dos itens */
    .vis-item {
        font-size: 13px;
        line-height: 16px;
        border-radius: 10px !important;
        padding: 6px 10px !important;
        border: none !important;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        color: #fff !important;
    }

    /* Texto do eixo do tempo */
    .vis-time-axis .vis-text {
        font-size: 12px;
        color: #666;
    }

    /* Linha do tempo atual */
    .vis-current-time {
        background-color: #FF5252 !important;
        width: 2px !important;
    }
  /* cor vermelha para datas bloqueadas */
  .flatpickr-day.data-bloqueada {
    background: #f7584dff !important;
    color: white !important;
    cursor: not-allowed !important;
  }

    #alternative-page-datatable td {
        padding-top: 4px;
        padding-bottom: 4px;
        vertical-align: middle; 
        }


    .table td, .table th {
        white-space: normal;
        word-break: break-word;
    }

</style>
<style>
    /* Avatar do usuário */
    /* Botões de ação */
    .action-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        margin: 0 2px;
        border-radius: 6px;
        border: 1px solid #ccc;
        color: #555;
        transition: all 0.2s;
    }

    .action-icon:hover {
        background-color: #d2d5d6ff;
        border: 1px solid #aaa7a7ff;
        color: #000;
    }

    /* Botão delete */
    .action-icon i.mdi-delete {
        color: #f16a6a;
    }

    /* Tabela responsiva com hover */
    #alternative-page-datatable tbody tr:hover {
        background-color: #f9f9f9;
        cursor: pointer;
    }

    /* Truncar texto longo */
    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .disabled-link {
        pointer-events: none; 
        opacity: 0.3;         
        cursor: default;      
    }
</style>
<!-- Start Content-->
<div class="container-fluid" style="max-width:100% !important; padding-left:10px; padding-right:10px;">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                </div>
                <h4 class="page-title"><?= \App\Core\Language::get('consultas'); ?> <?= \App\Core\Language::get($textTitulo); ?> </h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
     

    <div class="row">
        <div class="col-12">
            <div class="card widget-inline">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-sm-6 col-lg-3">
                            <div class="card rounded-0 shadow-none m-0">
                                <div class="card-body text-center py-2">
                                    <i class="ri-pie-chart-line text-muted font-24"></i>
                                    <h3><span><?= $totalConsultas; ?></span></h3>
                                    <p class="text-muted font-15 mb-0"><?= \App\Core\Language::get('total_de'); ?><br><?= \App\Core\Language::get('consultas'); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card rounded-0 shadow-none m-0 border-start border-light">
                                <div class="card-body text-center py-2">
                                    <i class="ri-calendar-check-line text-muted font-24"></i>
                                    <h3><span><?= $confirmadas; ?></span></h3> 
                                    <p class="text-muted font-15 mb-0"><?= \App\Core\Language::get('consultas'); ?><br><?= \App\Core\Language::get('confirmadas'); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card rounded-0 shadow-none m-0 border-start border-light">
                                <div class="card-body text-center py-2">
                                    <i class="ri-close-circle-line text-muted font-24"></i>
                                    <h3><span><?= $canceladas; ?></span></h3>
                                    <p class="text-muted font-15 mb-0"><?= \App\Core\Language::get('consultas'); ?><br><?= \App\Core\Language::get('canceladas'); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card rounded-0 shadow-none m-0 border-start border-light">
                                <div class="card-body text-center py-2">
                                    <i class="ri-checkbox-circle-line text-muted font-24"></i>
                                    <h3><span><?= $concluida; ?></h3>
                                    <p class="text-muted font-15 mb-0"><?= \App\Core\Language::get('consultas'); ?><br><?= \App\Core\Language::get('concluidas'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end row -->
                </div>
            </div> <!-- end card-box-->
        </div> <!-- end col-->
    </div>
    <!-- end row-->

<div class="row">        
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <h4 class="header-title mb-2 mb-md-0">
                    <?= \App\Core\Language::get('timeline_de_atendimento'); ?>
                </h4>
                <span id="relogio" class="fw-bold text-muted"></span>
            </div>
            <?php if ($totalConsultas > 0): ?>
            <div id="timeline" style="height: 250px; overflow-y: auto; border: 0px solid #ccc; padding: 0 15px;"></div>
            <?php endif; ?>
            <?php if ($totalConsultas == 0): ?>
                <div style="height: 50px; overflow-y: auto; align-items: center; justify-content: center;  border: 0px solid #ccc; padding: 0 15px;">Não há consultas agendadas</div>                
            <?php endif; ?>
        </div>
    </div><!-- end col-->    
</div>
<!-- end row-->
    
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <h4 class="header-title mb-2 mb-md-0"><?= \App\Core\Language::get('agenda'); ?></h4>
                <!-- Botões -->
                <div class="d-flex flex-column flex-md-row gap-1 w-100 w-md-auto mt-2 mt-md-0 ms-md-5">
                    <button type="button" class="btn <?= $botaoStyleDeactiveUltimos12Meses ?> btn-sm w-100 w-md-auto" 
                            onclick="window.location.href='<?= $newUrl.'&dia=4'; ?>'">
                        <i class="ri-calendar-2-line me-2"></i>
                        <?= \App\Core\Language::get('ultimos_2_anos'); ?>                        
                    </button>
                    <button type="button" class="btn <?= $botaoStyleDeactiveUltimos6Meses ?> btn-sm w-100 w-md-auto" 
                            onclick="window.location.href='<?= $newUrl.'&dia=2'; ?>'">
                        <i class="ri-calendar-2-line me-2"></i>
                        <?= \App\Core\Language::get('ultimos_6_meses'); ?>
                    </button>
                    <button type="button" class="btn <?= $botaoStyleDeactiveHoje ?> btn-sm w-100 w-md-auto" 
                            onclick="window.location.href='<?= $newUrl.'&dia=1'; ?>'">
                        <i class="ri-calendar-2-line me-2"></i>
                        <?= \App\Core\Language::get('hoje'); ?>
                    </button>
                    <button type="button" class="btn <?= $botaoStyleDeactiveProximos6Meses ?> btn-sm w-100 w-md-auto" 
                            onclick="window.location.href='<?= $newUrl.'&dia=3'; ?>'">
                            <i class="ri-checkbox-multiple-line me-2"></i>
                        <?= \App\Core\Language::get('proximos_6_meses'); ?>
                    </button>
                    <!-- Botão afastado com responsividade -->
                    <button type="button" class="btn btn-sm w-100 w-md-auto ms-md-5 mt-2 mt-md-0" style="background-color: #0cadc2ff; color: white; border-color: #135fd1ff;" data-bs-toggle="modal" data-bs-target="#novaConsulta-modal">
                        <i class="ri-user-3-line me-2"></i>
                        <?= \App\Core\Language::get('cadastrar_consulta_ini'); ?>
                    </button>
                </div>
            </div>
            <div class="card-header bg-light-lighten border-top border-bottom border-light py-1 text-center">
                <p class="m-0"><b><?= $confirmadas; ?></b> <?= \App\Core\Language::get('consultas'); ?> <?= \App\Core\Language::get('confirmadas'); ?> <?= \App\Core\Language::get('de'); ?> <?= $totalConsultas; ?></p>
            </div>
            <div class="card-body pt-2">
                <div class="table-responsive">
                    <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th></th>
                                <th><?= \App\Core\Language::get('nome_completo'); ?></th>
                                <th><?= \App\Core\Language::get('data_consulta'); ?></th>
                                <th><?= \App\Core\Language::get('status'); ?></th>
                                <th><?= \App\Core\Language::get('telefone'); ?></th>
                                <th><?= \App\Core\Language::get('profissional'); ?></th>
                                <th><?= \App\Core\Language::get('especialidade'); ?></th> 
                                <th><?= \App\Core\Language::get('plano_saude_odonto'); ?></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($consultasHoje as $index => $consulta): ?>
                                <?php
                                    if ($consulta['CON_ENSTATUS'] == "CONCLUIDA") {
                                        $imgIcon = "uil uil-award-alt font-16";
                                        $classIcon = "avatar-title bg-secondary-lighten rounded-circle text-secondary";
                                        $iconStyle = "border: 1px solid #a7a6a5ff;";
                                        $classeBadge = "secondary";
                                    } elseif ($consulta['CON_ENSTATUS'] == "CANCELADA") {
                                        $imgIcon = "uil uil-stopwatch-slash font-16";
                                        $classIcon = "avatar-title bg-warning-lighten rounded-circle text-warning";
                                        $iconStyle = "border: 1px solid #ce7d04ff;";
                                        $classeBadge = "warning";
                                    } elseif ($consulta['CON_ENSTATUS'] == "AGENDADA") {
                                        $imgIcon = "uil  uil-clock font-16";
                                        $classIcon = "avatar-title bg-primary-lighten rounded-circle text-primary";
                                        $iconStyle = "border: 1px solid #4d55c5ff;";
                                        $classeBadge = "primary";
                                    } elseif ($consulta['CON_ENSTATUS'] == "FALTA") {
                                        $imgIcon = "uil uil-asterisk font-16";
                                        $classIcon = "avatar-title bg-danger-lighten rounded-circle text-danger";
                                        $iconStyle = "border: 1px solid #811818ff;";
                                        $classeBadge = "danger";
                                    } elseif ($consulta['CON_ENSTATUS'] == "CONFIRMADA") {
                                        $imgIcon = "uil uil-check font-16"; 
                                        $classIcon = "avatar-title bg-success-lighten rounded-circle text-success";
                                        $iconStyle = "border: 1px solid #3dbd4eff;";
                                        $classeBadge = "success";
                                    }

                                    $semana = [
                                        0 => 'domingo',
                                        1 => 'segunda-feira',
                                        2 => 'terça-feira',
                                        3 => 'quarta-feira',
                                        4 => 'quinta-feira',
                                        5 => 'sexta-feira',
                                        6 => 'sábado'
                                    ];

                                    $numeroDia = (int) date('w', strtotime($consulta['CON_DTCONSULTA']));
                                    $diaSemana = $semana[$numeroDia];

                                    $dataConsulta = (new DateTimeImmutable($consulta['CON_DTCONSULTA']))->format('d/m/Y');
                                    $consultaHoraIni = (new DateTime($consulta['CON_HORACONSULTA']))->format('H\hi');

                                    $hora = $consulta['CON_HORACONSULTA'];
                                    $duracao = $consulta['CON_NUMDURACAO'];
                                    $dt = new DateTime($hora);
                                    $dt->add(new DateInterval("PT{$duracao}M"));
                                    $consultaHoraFim = $dt->format('H\hi'); 

                                    $showMaisInfo = "";

                                    if (!empty($consulta['CON_DCOBSERVACOES'])) {
                                        $obs = htmlspecialchars($consulta["CON_DCOBSERVACOES"], ENT_QUOTES, 'UTF-8');
                                        $showMaisInfo = "
                                        <a style='margin-left:5px; font-size:0.9rem; color: #04b0ffff;'
                                            title='Mais informações'
                                            data-bs-toggle='modal'
                                            data-bs-target='#info-alert-modal'
                                            data-observacoes=\"$obs\">
                                            <i class='ri-information-line' style='font-size: 18px;'></i>
                                        </a>";
                                    }

                                    $whatsStatus = "disabled-link";
                                    $whatsStatus = ($consulta['CON_ENSTATUS'] == "AGENDADA") ? "" : $whatsStatus;
                                    
                                ?> 
                            <tr>
                                <td>
                                    <div class="avatar-xs d-table">
                                        <span class="<?= $classIcon; ?>" style="<?= $iconStyle; ?>">
                                            <i class='<?= $imgIcon; ?>'></i>
                                        </span>
                                    </div>
                                </td>                          
                                <td class="text-truncate" style="cursor: pointer; max-width: 180px;" data-bs-toggle="modal" data-bs-target="#editarConsulta-modal">
                                    <?= $showMaisInfo ?> <?= htmlspecialchars(ucwords(strtolower((string)$consulta['PAC_DCNOME'])), ENT_QUOTES, 'UTF-8') ?>
                                </td>
                                <td class="text-truncate" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#editarConsulta-modal">
                                    <span style="display:none;">
                                        <?= date(
                                            'Y-m-d H:i',
                                            strtotime($dataConsulta . ' ' . str_replace(['h','ás'], ['',''], $consultaHoraIni))
                                        ) ?>
                                    </span>
                                    <?= htmlspecialchars($dataConsulta, ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars($consultaHoraIni, ENT_QUOTES, 'UTF-8') ?> <?= \App\Core\Language::get('as'); ?> <?= htmlspecialchars($consultaHoraFim, ENT_QUOTES, 'UTF-8') ?>
                                </td>
                                <td class="text-truncate" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#editarConsulta-modal">
                                    <span class="badge badge-<?= $classeBadge; ?>-lighten"><?= htmlspecialchars(ucwords(strtolower((string)$consulta['CON_ENSTATUS'])), ENT_QUOTES, 'UTF-8') ?></span>
                                </td>
                                <td class="text-truncate" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#editarConsulta-modal">
                                    <?= htmlspecialchars((string)$consulta['PAC_DCTELEFONE'], ENT_QUOTES, 'UTF-8') ?>
                                </td>
                                <td class="text-truncate" style="cursor: pointer; max-width: 150px;" data-bs-toggle="modal" data-bs-target="#editarConsulta-modal">
                                    <?= htmlspecialchars(ucwords(strtolower((string)$consulta['DEN_DCNOME'])), ENT_QUOTES, 'UTF-8') ?>
                                </td>
                                <td class="text-truncate" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#editarConsulta-modal">
                                    <?= htmlspecialchars(ucwords(strtolower((string)$consulta['CON_NMESPECIALIDADE'])), ENT_QUOTES, 'UTF-8') ?>
                                </td>
                                <td class="text-truncate" style="cursor: pointer; max-width: 150px;" data-bs-toggle="modal" data-bs-target="#editarConsulta-modal">
                                    <?= htmlspecialchars(ucwords(strtolower((string)$consulta['CNV_DCCONVENIO'])), ENT_QUOTES, 'UTF-8') ?>
                                </td>
                                <td class="table-action" style="width: 90px;">
                                    <a href="/cadastropaciente" class="action-icon" onclick="event.stopPropagation();"> <i class="mdi mdi-account-outline" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="<?= \App\Core\Language::get('ver_paciente'); ?>"></i></a> 
                                    <a href="javascript:void(0);" 
                                        class="action-icon <?= $whatsStatus ?>" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#msg-modal"
                                        data-nome="<?= htmlspecialchars($consulta['PAC_DCNOME']) ?>"
                                        data-profissional="<?= htmlspecialchars($consulta['DEN_DCNOME']) ?>"
                                        data-telefone="<?= htmlspecialchars($consulta['PAC_DCTELEFONE']) ?>"
                                        data-data="<?= htmlspecialchars($dataConsulta) ?>"
                                        data-dia="<?= htmlspecialchars($diaSemana) ?>"
                                        data-hora="<?= htmlspecialchars($consultaHoraIni) ?>"
                                        data-link="<?= htmlspecialchars($urlWhatsConfirmConsul) ?>">
                                        <i class="mdi mdi-whatsapp" 
                                            data-bs-toggle="popover" 
                                            data-bs-trigger="hover" 
                                            style="color: #25D366;"
                                            data-bs-content="<?= \App\Core\Language::get('pedir_confirmacao_whats_botao'); ?>">
                                        </i>
                                    </a>                            
                                    <a href="javascript:void(0);"  
                                            class="action-icon"
                                            data-id="<?= htmlspecialchars((string)$consulta['CON_IDCONSULTA'], ENT_QUOTES, 'UTF-8') ?>"    
                                            data-dialogTitle="<?= \App\Core\Language::get('consultas_lista'); ?>"    
                                            data-dialogMessage="<?= \App\Core\Language::get('tem_certeza_excluir_consulta'); ?> <?= htmlspecialchars((string)$consulta['PAC_DCNOME'], ENT_QUOTES, 'UTF-8') ?>?"   
                                            data-dialogUriToProcess="/deleteTaskProc"   
                                            data-dialogUriToRedirect="/inicial"   
                                            data-dialogConfirmButton="<?= \App\Core\Language::get('confirmar'); ?>"
                                            data-dialogCancelButton="<?= \App\Core\Language::get('cancelar'); ?>" 
                                            data-dialogErrorMessage="<?= \App\Core\Language::get('erro_ao_excluir'); ?>"
                                            data-dialogErrorTitle="<?= \App\Core\Language::get('erro'); ?>"    
                                            data-dialogCancelTitle="<?= \App\Core\Language::get('Cancelado'); ?>"                                                          
                                            data-dialogCancelMessage="<?= \App\Core\Language::get('cancelado_nenhuma_alteracao'); ?>"     
                                            data-dialogSuccessTitle="<?= \App\Core\Language::get('sucesso'); ?>"                                                             
                                            data-dialogProcessTitle="<?= \App\Core\Language::get('aguarde'); ?>" 
                                            data-dialogProcessMessage="<?= \App\Core\Language::get('processando_solicitacao'); ?>"                                                             
                                            onclick="event.stopPropagation(); confirmDeleteAttr(this);"> <!-- Chama o método js confirmDeleteAttr com sweetalert -->
                                            <i class="mdi mdi-delete" style="color: #f16a6aff;" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="<?= \App\Core\Language::get('excluir_consulta'); ?>"></i>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>


                </div> <!-- end table-responsive-->
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<!-- end row-->
</div> <!-- container -->
<!-- msg modal -->
<style>
    @media (min-width: 992px) {
        #msg-modal .modal-dialog.modal-sm.modal-right {
            max-width: 450px; /* largura maior no PC */
            top: auto;        /* remove top fixo */
            margin: 1.5rem 0 0 auto; /* apenas margem lateral e topo leve */
        }
    }
</style>

<div id="msg-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-right"> <!-- largura maior -->
        <div class="modal-content d-flex flex-column"> <!-- garante coluna -->
            <div class="modal-header border-0">
                <h5 class="modal-title"><?= \App\Core\Language::get('msg_para_whatsapp'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body" style="padding: 1rem; overflow-y: auto; max-height: 80vh;"> 
                <!-- empurra só os cards e permite scroll -->

                <?php if (!empty($modeloMsgsWhatsapp)) : ?>
                    <?php foreach ($modeloMsgsWhatsapp as $msg) : ?>
                        <div class="card mb-2 shadow-sm border">
                            <div class="card-body p-2">
                                <h6 class="card-title mb-1"><?= htmlspecialchars($msg['WMS_DCTITULO']) ?></h6>
                                <p class="card-text small text-muted msg-template"
                                   data-template="<?= htmlspecialchars($msg['WMS_DCMESSAGE_PT']) ?>"
                                   style="white-space: pre-wrap; margin-bottom: 0.25rem;">
                                   <!-- Texto será preenchido via JS -->
                                </p>
                                <a href="https://wa.me/12345678997?text=mensagem" 
                                   class="btn btn-sm btn-success" 
                                   target="_blank">
                                    <i class="mdi mdi-send"></i> <?= \App\Core\Language::get('enviar'); ?> 
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="alert alert-info text-center">
                        <?= \App\Core\Language::get('nenhuma_mensagem'); ?> 
                    </div>
                <?php endif; ?>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- msg modal -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var msgModal = document.getElementById('msg-modal');
        if (!msgModal) return;

        msgModal.addEventListener('shown.bs.modal', function(event) {
            let button = event.relatedTarget;
            if (!button) return;

            let nome = button.getAttribute('data-nome');
            let profissional = button.getAttribute('data-profissional');
            let data = button.getAttribute('data-data');
            let dia = button.getAttribute('data-dia');
            let hora = button.getAttribute('data-hora');
            let telefone = button.getAttribute('data-telefone');
            let linkConfirm = button.getAttribute('data-link');

            msgModal.querySelectorAll('.card').forEach(function(card) {
                let msgEl = card.querySelector('.msg-template');
                if (!msgEl) return;

                let template = msgEl.getAttribute('data-template');
                if (!template) return;

                let msg = template
                    .replace(/\[Nome\]/g, nome)
                    .replace(/\[profissional\]/g, profissional)
                    .replace(/\[data\]/g, data)
                    .replace(/\[dia\]/g, dia)
                    .replace(/\[hora\]/g, hora)
                    .replace(/\[link_de_confirmacao\]/g, linkConfirm);

                msgEl.innerText = msg.replace(/\\n/g, "\n");

                let a = card.querySelector('a.btn-success');
                if(a) {
                    let empresa = "<?= addslashes($nomeEmpresa ?? '') ?>";
                    msg = msg.replace(/\\n/g, "\n");
                    let msgFinal = msg + "\n\n" + empresa;
                    a.href = "https://wa.me/" + telefone + "?text=" + encodeURIComponent(msgFinal);
                }
            });
        });
    });

</script>
<!-- msg modal -->

<!-- /.Modal Alteração de consulta -->
<div id="editarConsulta-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <div class="text-center mt-2 mb-4">
                    <a href="index.html" class="text-success">
                        <span><img src="/public/assets/images/SmileCopilot-Logo_139x28.png" style="height:28px; width:auto;"></span>
                    </a>
                </div>

                <form class="ps-3 pe-3" action="#">

                    <div class="mb-3">
                        <label for="username" class="form-label">Name</label>
                        <input class="form-control" type="email" id="username" required="" placeholder="Michael Zenaty">
                    </div>

                    <div class="mb-3">
                        <label for="emailaddress" class="form-label">Email address</label>
                        <input class="form-control" type="email" id="emailaddress" required="" placeholder="john@deo.com">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input class="form-control" type="password" required="" id="password" placeholder="Enter your password">
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="customCheck1">
                            <label class="form-check-label" for="customCheck1">I accept <a href="#">Terms and Conditions</a></label>
                        </div> 
                    </div>

                    <div class="mb-3 text-center">
                        <button class="btn btn-primary" type="submit">Sign Up Free</button>
                    </div>

                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.Modal Alteração de consulta -->

<!-- /.Modal nova consulta -->
<div id="novaConsulta-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <button type="button"
                    class="btn-close position-absolute top-0 end-0 m-2"
                    style="cursor:pointer; z-index:1055;"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                    onclick="bootstrap.Modal.getOrCreateInstance(document.getElementById('novaConsulta-modal')).hide();">
            </button>

            <div class="modal-body">
                <div class="text-center mt-2 mb-4">
                    <a href="index.html" class="text-success">
                        <span><img src="/public/assets/images/SmileCopilot-Logo_139x28.png" style="height:28px; width:auto;"></span>
                    </a>
                </div>

                <form class="ps-3 pe-3" action="#">

                    <div class="mb-3">
                        <label for="paciente" class="form-label">Paciente</label>
                        <div class="input-group">
                            <select class="select2 form-control" id="paciente" data-placeholder="Escolha um paciente">
                                <option value="">Selecione...</option>
                                <?php foreach($pacientes as $p): ?>
                                    <option value="<?= $p['PAC_IDPACIENTE'] ?>">
                                        <?= htmlspecialchars($p['PAC_DCNOME']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <a href="/cadastropaciente" class="btn btn-success"
                                style="
                                    background-color: #0cadc2ff;
                                    color: white;
                                    border-color: #135fd1ff;
                                    width: 40px;
                                    height: 38px;            
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    font-size: 1.5rem;        
                                    font-weight: bold;
                                    margin-left: 5px;
                                    padding: 0;
                                " data-bs-toggle="popover" data-bs-placement="right" data-bs-trigger="hover"
                        data-bs-custom-class="info-popover" data-bs-title="<?= \App\Core\Language::get('pacientes'); ?>"
                        data-bs-content="<?= \App\Core\Language::get('adicionar_paciente'); ?>">
                                +
                            </a>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="observacao" class="form-label">Observações</label>
                        <textarea class="form-control" id="observacao" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Duração da Consulta</label>
                        <div class="form-check form-radio-danger">
                            <input type="radio" value="30" id="duracao1" name="duracao" class="form-check-input">
                            <label class="form-check-label" for="duracao1">30 Minutos</label>
                        </div>
                        <div class="form-check form-radio-danger">
                            <input type="radio" value="60" id="duracao2" name="duracao" class="form-check-input">
                            <label class="form-check-label" for="duracao2">60 Minutos</label>
                        </div>
                    </div> 

                    <div class="mb-3">
                        <label class="form-label">Data</label>
                        <input type="text" id="basic-datepicker" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Horários disponíveis</label>
                        <div id="horarios-disponiveis">

                        </div>
                    </div>

                    <div class="mb-3 text-center">
                        <button class="btn" style="background-color: #0cadc2ff; color: white; border-color: #135fd1ff;" type="submit">Salvar</button>
                    </div>

                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.Modal nova consulta -->

<!-- Info Alert Modal -->
<div id="info-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body p-4">
                <div class="text-center">
                    <i class="ri-information-line h1 text-info"></i>
                    <h4 class="mt-2">Observações da Consulta</h4>
                    <p class="mt-3" id="modal-observacoes">observações aqui.</p>
                    <button type="button" class="btn btn-info my-2" data-bs-dismiss="modal">Continue</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var modal = document.getElementById('info-alert-modal');
        modal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; 
            var observacoes = button.getAttribute('data-observacoes');

            var modalBody = modal.querySelector('#modal-observacoes');
            modalBody.textContent = observacoes && observacoes.trim() !== "" 
                ? observacoes 
                : "Nenhuma observação cadastrada.";
        });
    });                                
</script>

<script>
    const servidorAgora = '<?php echo $dataHoraServidor; ?>';
    const consultas = <?php echo json_encode($consultasTimeline); ?>;

    const itemsData = consultas.map((c, index) => {
        const start = new Date(c.data_hora);
        const end = new Date(start.getTime() + c.duracao * 60 * 1000);

        // Define cor de fundo com base no status
        let corFundo;
        if(c.status == 'CONFIRMADA'){
            corFundo = 'linear-gradient(135deg, #28af49ff, #1aad75ff)';
        } else if (c.status == 'CANCELADA') {
            corFundo = 'linear-gradient(135deg, #f39821ff, #a8850fff)';
        } else if (c.status == 'CONCLUIDA') {
            corFundo = 'linear-gradient(135deg, #4c4e4eff, #a5acadff)';
        } else if (c.status == 'AGENDADA') {
            corFundo = 'linear-gradient(135deg, #2196F3, #21CBF3)';
        } else if (c.status == 'FALTA') {
            corFundo = 'linear-gradient(135deg, #f74c18ff, #d12727ff)';
        } else {
            corFundo = 'linear-gradient(135deg, #cccccc, #aaaaaa)'; // fallback
        }

        return {
            id: index + 1,
            content: `<strong>${c.paciente}</strong><br>
                      <small>${start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})} - 
                             ${end.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</small>`,
            start: start,
            end: end,
            style: `background: ${corFundo}; color: white; border: 1px solid rgba(25, 118, 210, 0.8);`
        };
    });

    const container = document.getElementById('timeline');
    const items = new vis.DataSet(itemsData);

    const startVisivel = new Date(servidorAgora);
    startVisivel.setHours(startVisivel.getHours() - 2);
    const endVisivel = new Date(servidorAgora);
    endVisivel.setHours(endVisivel.getHours() + 2);

    const options = {
        start: startVisivel,
        end: endVisivel,
        editable: false,
        showCurrentTime: true,
        stack: true,
        horizontalScroll: true,
        zoomMin: 1000 * 60 * 30,
        zoomMax: 1000 * 60 * 60 * 24,
        margin: { item: 10, axis: 5 },
        orientation: 'top',
        locale: '<?= $lang; ?>'
    };

    const timeline = new vis.Timeline(container, items, options);

    timeline.on('select', function(properties) {
        if (properties.items.length > 0) {
            const itemId = properties.items[0];
            const item = items.get(itemId);

            // Preenche modal
            document.getElementById('username').value = item.content.replace(/<[^>]*>?/gm, '');
            document.getElementById('emailaddress').value = item.email || '';
            document.getElementById('password').value = '';
            document.getElementById('customCheck1').checked = false;

            const modal = new bootstrap.Modal(document.getElementById('editarConsulta-modal'));
            modal.show();
        }
    });

    function atualizarTimeline() {
        const agora = new Date();
        timeline.moveTo(agora);
    }

    setInterval(atualizarTimeline, 120000);
</script>

<script>
    function atualizarRelogio() {
        const agora = new Date();
        const horas = agora.getHours().toString().padStart(2, '0');
        const minutos = agora.getMinutes().toString().padStart(2, '0');
        const segundos = agora.getSeconds().toString().padStart(2, '0');
        document.getElementById('relogio').textContent = `${horas}:${minutos}:${segundos}`;
    }

    setInterval(atualizarRelogio, 1000);
    atualizarRelogio();
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Datas bloqueadas
        var datasBloqueadas = [
            "2025-09-15",
            "2025-09-20",
            "2025-09-22"
        ];

        flatpickr("#basic-datepicker", {
            dateFormat: "Y-m-d",
            minDate: "today",
            disable: datasBloqueadas,
            locale: {
                weekdays: {
                    shorthand: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'],
                    longhand: ['Domingo','Segunda-feira','Terça-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sábado']
                },
                months: {
                    shorthand: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
                    longhand: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro']
                },
                firstDayOfWeek: 1,
                rangeSeparator: ' até ',
                weekAbbreviation: 'Sem',
                scrollTitle: 'Scroll para aumentar',
                toggleTitle: 'Clique para alternar',
                time_24hr: true
            },
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                var data = dayElem.dateObj.toISOString().slice(0, 10);
                if (datasBloqueadas.includes(data)) {
                    dayElem.classList.add("data-bloqueada");
                    dayElem.style.backgroundColor = "#ffcccc"; // vermelho
                }
            },
            onChange: function(selectedDates, dateStr, instance) {
                if (!dateStr) return;

                var container = document.getElementById('horarios-disponiveis');
                container.innerHTML = '<p>Carregando horários...</p>';

                // Pega o valor do input de duração
                var duracao = document.getElementById('duracao') ? document.getElementById('duracao').value : '';

                // POST para o PHP
                fetch('/horariosdisp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'data=' + encodeURIComponent(dateStr) + '&duracao=' + encodeURIComponent(duracao)
                })
                .then(response => response.json())
                .then(data => {
                    container.innerHTML = ''; // limpa carregando

                    if (!data || data.length === 0) {
                        container.innerHTML = '<p>Nenhum horário disponível com a duração selecionada.</p>';
                        return;
                    }

                    data.forEach(function(horario) {
                        var label = document.createElement('label');
                        label.className = 'form-check-label d-block mb-1';
                        label.innerHTML = `
                            <input type="radio" name="horarios[]" value="${horario.horario}" class="form-check-input me-2">
                            ${horario.horario}
                        `;
                        container.appendChild(label);
                    });
                })
                .catch(err => {
                    container.innerHTML = '<p>Erro ao buscar horários.</p>';
                    console.error(err);
                });
            }
        });
    });
</script>

<script>
    document.getElementById('searchField').addEventListener('keyup', function() {
        var search = this.value.toLowerCase();
        var rows = document.querySelectorAll("#tabela-consultas tbody tr");
    
        rows.forEach(function(row) {
            var texto = row.innerText.toLowerCase();
            row.style.display = texto.includes(search) ? "" : "none";
        });
    });

    
</script>

<?php if ($lang  === "pt" || empty($lang)): ?>
    <script src="<?= ASSETS_PATH ?>utils/datatable-Init-ptbr.js"></script>
<?php endif; ?>

<?php if ($lang  === "en"): ?>
    <script src="<?= ASSETS_PATH ?>utils/datatable-Init-en.js"></script>
<?php endif; ?>

<?php if ($lang  === "es"): ?>
    <script src="<?= ASSETS_PATH ?>utils/datatable-Init-es.js"></script>
<?php endif; ?>