<?php
date_default_timezone_set('America/Sao_Paulo');
$dataHoraServidor = date('Y-m-d H:i:s'); // hora atual do servidor

$lang = $_GET['lang'] ?? 'pt';

$totalConsultas = 0;
$confirmadas = 0;

$totalConsultas = count($consultasHoje);

foreach ($consultasHoje as $consulta) {
    if ($consulta['CON_ENSTATUS'] === "CONFIRMADA") {
        $confirmadas++;
    }
}




// Agora cada consulta tem 'data_hora' completa
$consultassss = [
    ["data_hora" => "2025-09-09 19:20", "paciente" => "JOÃO ALBERTO MEDEIROS", "duracao" => 30],
    ["data_hora" => "2025-09-09 19:20", "paciente" => "MARIA DORALINA DE JESUS", "duracao" => 45],
    ["data_hora" => "2025-09-09 19:30", "paciente" => "CARLOS LIMA", "duracao" => 60], // dia seguinte
    ["data_hora" => "2025-09-09 20:30", "paciente" => "ANA FLAVIA DE ARAÚJO", "duracao" => 30]    // dia anterior
];
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
</style>


<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                </div>
                <h4 class="page-title"><?= \App\Core\Language::get('consultas'); ?> <?= \App\Core\Language::get('programadas'); ?> </h4>
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
                                    <i class="ri-briefcase-line text-muted font-24"></i>
                                    <h3><span>29</span></h3>
                                    <p class="text-muted font-15 mb-0"><?= \App\Core\Language::get('total_de'); ?><br><?= \App\Core\Language::get('consultas'); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card rounded-0 shadow-none m-0 border-start border-light">
                                <div class="card-body text-center py-2">
                                    <i class="ri-list-check-2 text-muted font-24"></i>
                                    <h3><span>715</span></h3>
                                    <p class="text-muted font-15 mb-0"><?= \App\Core\Language::get('consultas'); ?><br><?= \App\Core\Language::get('confirmadas'); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card rounded-0 shadow-none m-0 border-start border-light">
                                <div class="card-body text-center py-2">
                                    <i class="ri-group-line text-muted font-24"></i>
                                    <h3><span>31</span></h3>
                                    <p class="text-muted font-15 mb-0"><?= \App\Core\Language::get('consultas'); ?><br><?= \App\Core\Language::get('canceladas'); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card rounded-0 shadow-none m-0 border-start border-light">
                                <div class="card-body text-center py-2">
                                    <i class="ri-line-chart-line text-muted font-24"></i>
                                    <h3><span>93%</span> <i class="mdi mdi-arrow-up text-success"></i></h3>
                                    <p class="text-muted font-15 mb-0"><?= \App\Core\Language::get('pacientes'); ?><br><?= \App\Core\Language::get('cadastrados'); ?></p>
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
            <div id="timeline" style="height: 250px; overflow-y: auto; border: 0px solid #ccc; padding: 0 15px;"></div>
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
                                <div class="d-flex flex-column flex-md-row gap-1 w-100 w-md-auto ms-md-5">
                                    <button type="button" class="btn btn-soft-secondary btn-sm w-100 w-md-auto">
                                        <?= \App\Core\Language::get('ontem'); ?>
                                    </button>
                                    <button type="button" class="btn btn-primary btn-sm w-100 w-md-auto">
                                        <?= \App\Core\Language::get('hoje'); ?>
                                    </button>
                                    <button type="button" class="btn btn-soft-secondary btn-sm w-100 w-md-auto">
                                        <?= \App\Core\Language::get('amanha'); ?>
                                    </button>
                                </div>
                            </div>
                            <div class="card-header bg-light-lighten border-top border-bottom border-light py-1 text-center">
                                <p class="m-0"><b><?= $confirmadas; ?></b> <?= \App\Core\Language::get('consultas'); ?> <?= \App\Core\Language::get('confirmadas'); ?> <?= \App\Core\Language::get('de'); ?> <?= $totalConsultas; ?></p>
                            </div>
                            <div class="card-body pt-2">
                                <div class="table-responsive">
                                    <table class="table table-centered table-nowrap table-hover mb-0">
                                        <tbody>
                                            <?php foreach ($consultasHoje as $consulta): ?>
                                                <?php
                                                    if ($consulta['CON_ENSTATUS'] == "CONCLUIDA") {
                                                        $imgIcon = "uil uil-award-alt font-16";
                                                        $classIcon = "avatar-title bg-warning-lighten rounded-circle text-warning";
                                                        $iconStyle = "border: 2px solid #a7a6a5ff;";
                                                        $classeBadge = "secondary";
                                                    } elseif ($consulta['CON_ENSTATUS'] == "CANCELADA") {
                                                        $imgIcon = "uil uil-stopwatch-slash font-16";
                                                        $classIcon = "avatar-title bg-warning-lighten rounded-circle text-warning";
                                                        $iconStyle = "border: 2px solid #ce7d04ff;";
                                                        $classeBadge = "warning";
                                                    } elseif ($consulta['CON_ENSTATUS'] == "AGENDADA") {
                                                        $imgIcon = "uil  uil-clock font-16";
                                                        $classIcon = "avatar-title bg-primary-lighten rounded-circle text-primary";
                                                        $iconStyle = "border: 2px solid #4d55c5ff;";
                                                        $classeBadge = "primary";
                                                    } elseif ($consulta['CON_ENSTATUS'] == "FALTA") {
                                                        $imgIcon = "uil uil-asterisk font-16";
                                                        $classIcon = "avatar-title bg-danger-lighten rounded-circle text-danger";
                                                        $iconStyle = "border: 2px solid #811818ff;";
                                                        $classeBadge = "danger";
                                                    } elseif ($consulta['CON_ENSTATUS'] == "CONFIRMADA") {
                                                        $imgIcon = "uil uil-check font-16"; // corrigido aqui
                                                        $classIcon = "avatar-title bg-success-lighten rounded-circle text-success";
                                                        $iconStyle = "border: 2px solid #3dbd4eff;";
                                                        $classeBadge = "success";
                                                    }
                                                ?>

                                            <tr style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#editarConsulta-modal"> <!-- TR com chamada para edição de consulta via Modal -->
                                                <td>
                                                    <div class="avatar-sm d-table">
                                                        <span class="<?= $classIcon; ?>" style="<?= $iconStyle; ?>">
                                                            <i class='<?= $imgIcon; ?>'></i>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <h5 class="font-14 my-1"><a href="javascript:void(0);" class="text-body"><?= htmlspecialchars((string)$consulta['PAC_DCNOME'], ENT_QUOTES, 'UTF-8') ?></a></h5>
                                                    <span class="text-muted font-13"><?= htmlspecialchars((string)$consulta['CON_DTCONSULTA'], ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars((string)$consulta['CON_HORACONSULTA'], ENT_QUOTES, 'UTF-8') ?> <?= \App\Core\Language::get('as'); ?> <?= htmlspecialchars((string)$consulta['CON_HORACONSULTA'], ENT_QUOTES, 'UTF-8') ?></span>
                                                </td>
                                                <td>
                                                    <span class="text-muted font-13"><?= \App\Core\Language::get('status'); ?></span> <br />
                                                    <span class="badge badge-<?= $classeBadge; ?>-lighten"><?= htmlspecialchars((string)$consulta['CON_ENSTATUS'], ENT_QUOTES, 'UTF-8') ?></span>
                                                </td>
                                                <td>
                                                    <span class="text-muted font-13"><?= \App\Core\Language::get('telefone'); ?></span>
                                                    <h5 class="font-14 mt-1 fw-normal"><?= htmlspecialchars((string)$consulta['PAC_DCTELEFONE'], ENT_QUOTES, 'UTF-8') ?></h5>
                                                </td>
                                                <td>
                                                    <span class="text-muted font-13"><?= \App\Core\Language::get('profissional'); ?></span>
                                                    <h5 class="font-14 mt-1 fw-normal"><?= htmlspecialchars((string)$consulta['DEN_DCNOME'], ENT_QUOTES, 'UTF-8') ?></h5>
                                                </td>
                                                <td>
                                                    <span class="text-muted font-13"><?= \App\Core\Language::get('especialidade'); ?></span>
                                                    <h5 class="font-14 mt-1 fw-normal"><?= htmlspecialchars((string)$consulta['CON_NMESPECIALIDADE'], ENT_QUOTES, 'UTF-8') ?></h5> 
                                                </td>
                                                <td>
                                                    <span class="text-muted font-13"><?= \App\Core\Language::get('plano_saude_odonto'); ?></span>
                                                    <h5 class="font-14 mt-1 fw-normal"><?= htmlspecialchars((string)$consulta['CNV_DCCONVENIO'], ENT_QUOTES, 'UTF-8') ?></h5>
                                                </td>
                                                <td class="table-action" style="width: 90px;">
                                                    <a href="javascript: void(0);" class="action-icon"> <i class="mdi mdi-send-check-outline" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="<?= \App\Core\Language::get('pedir_confirmacao_whats_botao'); ?>"></i></a>
                                                    <a href="javascript: void(0);" class="action-icon"> <i class="mdi mdi-calendar-month-outline" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="<?= \App\Core\Language::get('reagendar_consulra'); ?>"></i></a> 
                                                    <a href="javascript: void(0);" class="action-icon"> <i class="mdi mdi-delete" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="<?= \App\Core\Language::get('excluir_consulta'); ?>"></i></a>
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

<!-- /.Modal Alteração de consulta -->
<div id="editarConsulta-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <div class="text-center mt-2 mb-4">
                    <a href="index.html" class="text-success">
                        <span><img src="../../../public/assets/images/logo.png" alt="" height="18"></span>
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


<script>
    const servidorAgora = '<?php echo $dataHoraServidor; ?>';
    const consultas = <?php echo json_encode($consultassss); ?>;

    const itemsData = consultas.map((c, index) => {
        const start = new Date(c.data_hora);
        const end = new Date(start.getTime() + c.duracao * 60 * 1000);

        const corFundo = 'linear-gradient(135deg, #2196F3, #21CBF3)';

        return {
            id: index + 1,
            content: `<strong>${c.paciente}</strong><br>
                      <small>${start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})} - 
                             ${end.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</small>`,
            start: start,
            end: end,
            style: `background: ${corFundo};`
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
        zoomMin: 1000 * 60 * 30,   // 30 min
        zoomMax: 1000 * 60 * 60*24, // 24h
        margin: { item: 10, axis: 5 },
        orientation: 'top',
        locale: '<?= $lang; ?>'
    };

    const timeline = new vis.Timeline(container, items, options);

    timeline.on('select', function(properties) {
        if (properties.items.length > 0) {
            const itemId = properties.items[0];
            const item = items.get(itemId);

            // Preenche modal (exemplo)
            document.getElementById('username').value = item.content.replace(/<[^>]*>?/gm, ''); // remove tags html
            document.getElementById('emailaddress').value = item.email || '';
            document.getElementById('password').value = '';
            document.getElementById('customCheck1').checked = false;

            // Abre modal
            const modal = new bootstrap.Modal(document.getElementById('editarConsulta-modal'));
            modal.show();
        }
    });

    // Mantém timeline atualizada no horário
    function atualizarTimeline() {
        const agora = new Date();
        timeline.moveTo(agora);
    }

    // Atualiza a cada 2 minutos
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
