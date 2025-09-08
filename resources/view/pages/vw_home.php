<?php
date_default_timezone_set('America/Sao_Paulo');
$dataHoraServidor = date('Y-m-d H:i:s'); // hora atual do servidor

$lang = $_GET['lang'] ?? 'pt';

// Agora cada consulta tem 'data_hora' completa
$consultas = [
    ["data_hora" => "2025-09-04 19:00", "paciente" => "JOÃO ALBERTO MEDEIROS", "duracao" => 30],
    ["data_hora" => "2025-09-04 19:20", "paciente" => "MARIA DORALINA DE JESUS", "duracao" => 45],
    ["data_hora" => "2025-09-04 19:30", "paciente" => "CARLOS LIMA", "duracao" => 60], // dia seguinte
    ["data_hora" => "2025-09-04 20:30", "paciente" => "ANA FLAVIA DE ARAÚJO", "duracao" => 30]    // dia anterior
];
?>

<style>
    #timeline {
        width: 100%;
        height: 250px;
        border: 0px solid #ccc;
    }
    .vis-item {
        font-size: 12px;
        line-height: 12px;
        padding: 2px 4px;
        border-radius: 3px;
        cursor: pointer;
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
                <h4 class="header-title mb-2 mb-md-0"><?= \App\Core\Language::get('timeline_de_atendimento'); ?></h4>
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
                                <p class="m-0"><b>14</b> <?= \App\Core\Language::get('consultas'); ?> <?= \App\Core\Language::get('confirmadas'); ?> <?= \App\Core\Language::get('de'); ?> 16</p>
                            </div>
                            <div class="card-body pt-2">
                                <div class="table-responsive">
                                    <table class="table table-centered table-nowrap table-hover mb-0">
                                        <tbody>
                                            <tr style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#editarConsulta-modal"> <!-- TR com chamada para edição de consulta via Modal -->
                                                <td>
                                                    <h5 class="font-14 my-1"><a href="javascript:void(0);" class="text-body">Rodolfo Hernandes Silva...</a></h5>
                                                    <span class="text-muted font-13">25/06/2025 16h00 <?= \App\Core\Language::get('as'); ?> 16h30</span>
                                                </td>
                                                <td>
                                                    <span class="text-muted font-13"><?= \App\Core\Language::get('status'); ?></span> <br />
                                                    <span class="badge badge-danger-lighten">Cancelada</span>
                                                </td>
                                                <td>
                                                    <span class="text-muted font-13"><?= \App\Core\Language::get('telefone'); ?></span>
                                                    <h5 class="font-14 mt-1 fw-normal">(11) 98273-4350</h5>
                                                </td>
                                                <td>
                                                    <span class="text-muted font-13"><?= \App\Core\Language::get('profissional'); ?></span>
                                                    <h5 class="font-14 mt-1 fw-normal">Solange Lima de Oliveira</h5>
                                                </td>
                                                <td>
                                                    <span class="text-muted font-13"><?= \App\Core\Language::get('especialidade'); ?></span>
                                                    <h5 class="font-14 mt-1 fw-normal">Ortodontia</h5> 
                                                </td>
                                                <td>
                                                    <span class="text-muted font-13"><?= \App\Core\Language::get('plano_saude_odonto'); ?></span>
                                                    <h5 class="font-14 mt-1 fw-normal">Uniodonto</h5>
                                                </td>
                                                <td class="table-action" style="width: 90px;">
                                                    <a href="javascript: void(0);" class="action-icon"> <i class="mdi mdi-send-check-outline" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="<?= \App\Core\Language::get('pedir_confirmacao_whats_botao'); ?>"></i></a>
                                                    <a href="javascript: void(0);" class="action-icon"> <i class="mdi mdi-calendar-month-outline" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="<?= \App\Core\Language::get('reagendar_consulra'); ?>"></i></a> 
                                                    <a href="javascript: void(0);" class="action-icon"> <i class="mdi mdi-delete" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="<?= \App\Core\Language::get('excluir_consulta'); ?>"></i></a>
                                                </td>
                                            </tr>
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
    const consultas = <?php echo json_encode($consultas); ?>;

    const itemsData = consultas.map((c, index) => {
        const start = new Date(c.data_hora); // data + hora completa
        const end = new Date(start.getTime() + c.duracao * 60 * 1000); // adiciona duração

         const corFundo = 'linear-gradient(to bottom, rgba(33, 150, 243, 0.8), rgba(33, 150, 243, 0.4))';

        return {
            id: index + 1,
            content: c.paciente,
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
        zoomMax: 1000 * 60 * 60*24,
        margin: { item: 10, axis: 5 },
        orientation: 'top',
        locale: '<?= $lang; ?>' 
    };

    // Inicializa timeline
    const timeline = new vis.Timeline(container, items, options);

    // Evento de clique
    timeline.on('select', function(properties) {
        if(properties.items.length > 0){
            const itemId = properties.items[0];
            const item = items.get(itemId);

            // Preencher campos do modal (exemplo)
            document.getElementById('username').value = item.content; // paciente
            document.getElementById('emailaddress').value = item.email || ''; // se tiver email
            document.getElementById('password').value = ''; // limpa senha
            document.getElementById('customCheck1').checked = false;

            // Abre o modal
            const modal = new bootstrap.Modal(document.getElementById('editarConsulta-modal'));
            modal.show();
        }
    });

    function atualizarTimeline() {
        const agora = new Date();
        timeline.moveTo(agora);
    }

    // Atualiza a cada segundo
    setInterval(atualizarTimeline, 120000);
</script>


