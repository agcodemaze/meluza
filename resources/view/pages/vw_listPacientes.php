<?php
date_default_timezone_set('America/Sao_Paulo');
$dataHoraServidor = date('Y-m-d H:i:s'); // hora atual do servidor

$lang = $_SESSION['lang'] ?? 'pt';

?>
<style>
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


<!-- CSS personalizado -->
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
</style>


<!-- Start Content-->
<div class="container-fluid" style="max-width:100% !important; padding-left:10px; padding-right:10px;">
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
                                                    <th></th>
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
                                                    <td>
                                                        <div class="avatar-xs d-table">
                                                            <span class="avatar-title bg-info-lighten rounded-circle text-info" style="border: 1px solid #4d55c5ff;">
                                                                <i class='uil uil-user font-16'></i>
                                                            </span>
                                                        </div>
                                                    </td> 
                                                    <td class="text-truncate" style="max-width: 250px;"><?= htmlspecialchars(ucwords(strtolower((string)$listaPaciente['PAC_DCNOME'])), ENT_QUOTES, 'UTF-8') ?></td>
                                                    <td><?= htmlspecialchars(ucwords(strtolower((string)$listaPaciente['PAC_DCTELEFONE'])), ENT_QUOTES, 'UTF-8') ?></td>
                                                    <td><?= htmlspecialchars(ucwords(strtolower((string)$listaPaciente['PAC_DCCPF'])), ENT_QUOTES, 'UTF-8') ?></td>
                                                    <td class="text-truncate" style="max-width: 150px;">Sulamérica Odonto</td>
                                                    <td>05/08/2025</td>
                                                    <td>   
                                                        <a href="/cadastropaciente" class="action-icon" onclick="event.stopPropagation();"> <i class="mdi mdi-eye-outline" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="<?= \App\Core\Language::get('ver_paciente'); ?>"></i></a>             
                                                        <a href="javascript: void(0);" class="action-icon" onclick="event.stopPropagation();"> <i class="mdi mdi-clock-outline" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="<?= \App\Core\Language::get('agendar_consulta'); ?>"></i></a>
                                                        <a href="javascript: void(0);" class="action-icon" onclick="event.stopPropagation();"> <i class="mdi mdi-whatsapp" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="<?= \App\Core\Language::get('conversar_whatsapp'); ?>"></i></a>
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
                                                            <i class="mdi mdi-delete" data-bs-toggle="popover" style="color: #f16a6aff;" data-bs-trigger="hover" data-bs-content="<?= \App\Core\Language::get('excluir_paciente'); ?>"></i>
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
    <script src="<?= ASSETS_PATH ?>utils/datatable-Init-ptbr_pacientes.js"></script>
<?php endif; ?>

<?php if ($lang  === "en"): ?>
    <script src="<?= ASSETS_PATH ?>utils/datatable-Init-en_paciente.js"></script>
<?php endif; ?>

<?php if ($lang  === "es"): ?>
    <script src="<?= ASSETS_PATH ?>utils/datatable-Init-es_paciente.js"></script>
<?php endif; ?>


