<?php
date_default_timezone_set('America/Sao_Paulo');
$dataHoraServidor = date('Y-m-d H:i:s'); // hora atual do servidor

$lang = $_SESSION['lang'] ?? 'pt';

?>

<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                </div>
                <h4 class="page-title"><?= \App\Core\Language::get('pacientes'); ?></h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    
    <div class="row">
        <div class="col-12">
            <!-- Seção: Anamnese Médica -->
            <div class="card">
        <div class="card-body">
            <h4 class="header-title"><?= \App\Core\Language::get('lista_pacientes'); ?></h4> 
            <p class="text-muted font-14">  
                <?= \App\Core\Language::get('paciente_desc'); ?>
            </p>

            <div class="tab-content">
                <div class="tab-pane show active" id="input-types-preview">
                    <div class="row">
                        <div class="col-12">

                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th><?= \App\Core\Language::get('nome_completo'); ?></th>
                                                    <th><?= \App\Core\Language::get('telefone'); ?></th>
                                                    <th><?= \App\Core\Language::get('cpfrg'); ?></th>
                                                    <th><?= \App\Core\Language::get('nome_convenio'); ?></th>
                                                    <th><?= \App\Core\Language::get('ultima_consulta'); ?></th> 
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($listaPacientes as $listaPaciente): ?>
                                                <tr style="cursor: pointer;" onclick="if(event.target.closest('td.dtr-control')) return false; window.location='/cadastropaciente';">
                                                    <td class="text-truncate text-uppercase" style="max-width: 250px;"><?= $listaPaciente['PAC_DCNOME']; ?></td>
                                                    <td><?= $listaPaciente['PAC_DCTELEFONE']; ?></td>
                                                    <td><?= $listaPaciente['PAC_DCCPF']; ?></td>
                                                    <td class="text-truncate text-uppercase" style="max-width: 150px;">SULAMÉRICA ODONTO</td>
                                                    <td>05/08/2025</td>
                                                    <td>   
                                                        <a href="/cadastropaciente" class="action-icon" onclick="event.stopPropagation();"> <i class="mdi mdi-eye-outline" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="<?= \App\Core\Language::get('ver_paciente'); ?>"></i></a>             
                                                        <a href="javascript: void(0);" class="action-icon" onclick="event.stopPropagation();"> <i class="mdi mdi-clock-outline" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="<?= \App\Core\Language::get('agendar_consulta'); ?>"></i></a>
                                                        <a href="javascript: void(0);" class="action-icon" onclick="event.stopPropagation();"> <i class="mdi mdi-chat-processing-outline" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="<?= \App\Core\Language::get('conversar_whatsapp'); ?>"></i></a>
                                                        <a href="/anamnese" class="action-icon" onclick="event.stopPropagation();"> <i class="mdi mdi-clipboard-list-outline" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="<?= \App\Core\Language::get('ver_anamneses_paciente'); ?>"></i></a> 
                                                        <a href="javascript:void(0);"  
                                                            class="action-icon"
                                                            data-id="<?= htmlspecialchars((string)$listaPaciente['PAC_IDPACIENTE'], ENT_QUOTES, 'UTF-8') ?>"    
                                                            data-dialogTitle="<?= \App\Core\Language::get('lista_pacientes'); ?>"    
                                                            data-dialogMessage="<?= \App\Core\Language::get('tem_certeza_excluir_paciente'); ?> <?= htmlspecialchars((string)$listaPaciente['PAC_DCNOME'], ENT_QUOTES, 'UTF-8') ?>?"   
                                                            data-dialogUriToProcess="/deleteTaskProc"   
                                                            data-dialogUriToRedirect="/listapaciente"   
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
                                                            <i class="mdi mdi-delete" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="<?= \App\Core\Language::get('excluir_paciente'); ?>"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div> <!-- end row -->
                                </div> <!-- end card body-->
                            </div> <!-- end card -->
                        </div>
                        <!-- end col-12 -->
                    </div> <!-- end row -->               
                </div> <!-- end preview-->
            </div> <!-- end tab-content-->
        </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->
</div>
<br>

<?php if ($lang  === "pt" || empty($lang)): ?>
    <script src="<?= ASSETS_PATH ?>utils/datatable-Init-ptbr.js"></script>
<?php endif; ?>

<?php if ($lang  === "en"): ?>
    <script src="<?= ASSETS_PATH ?>utils/datatable-Init-en.js"></script>
<?php endif; ?>

<?php if ($lang  === "es"): ?>
    <script src="<?= ASSETS_PATH ?>utils/datatable-Init-es.js"></script>
<?php endif; ?>


