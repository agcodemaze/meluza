<?php
date_default_timezone_set('America/Sao_Paulo');
$dataHoraServidor = date('Y-m-d H:i:s'); // hora atual do servidor

$lang = $_SESSION['lang'] ?? 'pt';

use \App\Controller\Pages\EncryptDecrypt; 
use \App\Model\Entity\Organization;

$objOrganization = new Organization();
$configuracoes = $objOrganization->getConfiguracoes(TENANCY_ID);

$nomeClinica = mb_convert_case(mb_strtolower($configuracoes["CFG_DCNOME_CLINICA"]), MB_CASE_TITLE, "UTF-8");

$key = $_ENV['ENV_SECRET_KEY'] ?? getenv('ENV_SECRET_KEY') ?? '';

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
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Configurações</a></li>
                        <li class="breadcrumb-item active"><?= \App\Core\Language::get('lista_modelo_de_anamnese'); ?>'s</li>
                    </ol>
                </div>
                <h4 class="page-title"><?= \App\Core\Language::get('lista_modelo_de_anamnese'); ?>'s</h4>
            </div>
            <!-- Seção: Anamnese Médica -->
            <div class="card">
        <div class="card-body">
            <h4 class="header-title"></h4>
            <p class="text-muted font-14">
                <?= \App\Core\Language::get('lista_modelo_de_anamnese_descricao'); ?>
            </p>
            <div class="text-end mb-3"> <!-- <== margem inferior adicionada -->
                <a href="/cadmodeloanamnese" class="btn btn-info">
                    <?= \App\Core\Language::get('criar_novo_modelo'); ?>
                </a>
            </div>

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
                                                    <th><?= \App\Core\Language::get('titulo'); ?></th>
                                                    <th><?= \App\Core\Language::get('descricao'); ?></th>
                                                    <th><?= \App\Core\Language::get('criado_em'); ?></th>
                                                    <th><?= \App\Core\Language::get('atualizado_em'); ?></th>
                                                    <th><?= \App\Core\Language::get('status'); ?></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($listaAnamnese as $anamnese): ?>

                                                <?php
                                                    $anamnese['ANM_DTCREATE_AT'] = date('d/m/Y H:i:s', strtotime($anamnese['ANM_DTCREATE_AT']));
                                                    $anamnese['ANM_DTUPDATE_AT'] = date('d/m/Y H:i:s', strtotime($anamnese['ANM_DTUPDATE_AT']));
                                                ?>

                                                <tr style="cursor: pointer;" onclick="if(event.target.closest('td.dtr-control')) return false; window.location='/editmodeloanamnese?id=<?= htmlspecialchars($anamnese['ANM_IDANAMNESE_MODELO']) ?>';">
                                                    <td>
                                                        <div class="avatar-xs d-table">
                                                            <span class="avatar-title bg-info-lighten rounded-circle text-info" style="border: 1px solid #4d55c5ff;">
                                                                <i class='uil-clipboard-notes font-16'></i>
                                                            </span>
                                                        </div>
                                                    </td> 
                                                    <td class="text-truncate" style="max-width: 250px;"><?= htmlspecialchars(ucwords(strtolower((string)$anamnese['ANM_DCTITULO'])), ENT_QUOTES, 'UTF-8') ?></td>
                                                    <td class="text-truncate" style="max-width: 250px;"><?= htmlspecialchars(ucwords(strtolower((string)$anamnese['ANM_DCDESCRICAO'])), ENT_QUOTES, 'UTF-8') ?></td>
                                                    <td><?= htmlspecialchars($anamnese['ANM_DTCREATE_AT']) ?></td>
                                                    <td><?= htmlspecialchars($anamnese['ANM_DTCREATE_AT']) ?></td>
                                                    <td class="text-truncate" style="max-width: 250px;"><?= htmlspecialchars(ucwords(strtolower((string)$anamnese['ANM_STSTATUS'])), ENT_QUOTES, 'UTF-8') ?></td>
                                                    <td>  
                                                        <a href="javascript: void(0);" class="action-icon" onclick="event.stopPropagation();"> <i class="mdi mdi-play-circle-outline text-success" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="<?= \App\Core\Language::get('tornar_modelo_ativo'); ?>"></i></a>

                                                        <a href="javascript:void(0);"  
                                                            class="action-icon"
                                                            data-id="<?= htmlspecialchars((string)$anamnese['ANM_IDANAMNESE_MODELO'], ENT_QUOTES, 'UTF-8') ?>"    
                                                            data-dialogTitle="<?= \App\Core\Language::get('lista_modelo_de_anamnese'); ?>"    
                                                            data-dialogMessage="<?= \App\Core\Language::get('tem_certeza_excluir_anamnese'); ?> <?= htmlspecialchars((string)$anamnese['ANM_DCTITULO'], ENT_QUOTES, 'UTF-8') ?>?"   
                                                            data-dialogUriToProcess="/deletemodeloanamnese"   
                                                            data-dialogUriToRedirect="/listmodeloanamnese"   
                                                            data-dialogConfirmButton="<?= \App\Core\Language::get('confirmar'); ?>"
                                                            data-dialogCancelButton="<?= \App\Core\Language::get('cancelar'); ?>" 
                                                            data-dialogErrorMessage="<?= \App\Core\Language::get('erro_ao_excluir'); ?>"
                                                            data-dialogErrorTitle="<?= \App\Core\Language::get('erro'); ?>"    
                                                            data-dialogCancelTitle="<?= \App\Core\Language::get('Cancelado'); ?>"                                                          
                                                            data-dialogCancelMessage="<?= \App\Core\Language::get('cancelado_nenhuma_alteracao'); ?>"     
                                                            data-dialogSuccessTitle="<?= \App\Core\Language::get('sucesso'); ?>"                                                             
                                                            data-dialogProcessTitle="<?= \App\Core\Language::get('aguarde'); ?>" 
                                                            data-dialogProcessMessage="<?= \App\Core\Language::get('processando_solicitacao'); ?>"                                                             
                                                            onclick="event.stopPropagation(); confirmDeleteAttr(this);"> 
                                                            <i class="mdi mdi-delete" data-bs-toggle="popover" style="color: #f16a6aff;" data-bs-trigger="hover" data-bs-content="<?= \App\Core\Language::get('excluiexcluir_modelo_anamneser_anamnese'); ?>"></i>
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


