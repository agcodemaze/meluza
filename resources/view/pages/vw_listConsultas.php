<?php
    date_default_timezone_set('America/Sao_Paulo');
    $dataHoraServidor = date('Y-m-d H:i:s'); // hora atual do servidor

    $_SESSION['PROFISSIONAL_ID'] = $_SESSION['PROFISSIONAL_ID'] ?? 'all';

    $lang = $_SESSION['lang'] ?? 'pt';

    if (isset($_GET['range'])) {
        $intervalo = explode(' ', $_GET['range']);
        $dataini = isset($intervalo[0]) ? $intervalo[0] : date('Y-m-01');
        $dataend = isset($intervalo[1]) ? $intervalo[1] : date('Y-m-t');
        $dataini_br = DateTime::createFromFormat('Y-m-d', $dataini)->format('d/m/Y');
        $dataend_br = DateTime::createFromFormat('Y-m-d', $dataend)->format('d/m/Y');
    } else {
        $dataini = date('Y-m-01'); 
        $dataend = date('Y-m-t');   
        $dataini_br = DateTime::createFromFormat('Y-m-d', $dataini)->format('d/m/Y');
        $dataend_br = DateTime::createFromFormat('Y-m-d', $dataend)->format('d/m/Y');
    }

    $Consultas = \App\Controller\Pages\ListConsulta::listaConsultasByRangAndProfissional($dataini, $dataend);

    $consultasConfirmadasAmount = 0;
    $consultasConcluidasAmount = 0;
    $consultasAgendadasAmount = 0;
    $consultasCanceladasAmount = 0;
    foreach($Consultas as $Consulta) {
        $consultasConfirmadasAmount += ($Consulta["CON_ENSTATUS"] == "CONFIRMADA") ? 1 : 0;
        $consultasConcluidasAmount += ($Consulta["CON_ENSTATUS"] == "CONCLUIDA") ? 1 : 0;
        $consultasAgendadasAmount += ($Consulta["CON_ENSTATUS"] == "AGENDADA") ? 1 : 0;
        $consultasCanceladasAmount += ($Consulta["CON_ENSTATUS"] == "CANCELADA" || $Consulta["CON_ENSTATUS"] == "FALTA") ? 1 : 0;
    }
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
    
    <div class="row" style="margin-top:20px;">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">

                                            <!-- Datepicker à direita -->
                        <div class="col-12 col-md-3 mb-2">
                            <label class="form-label">Escolha um Intervalo de Data</label>
                            <input type="text" id="range-datepicker" class="form-control"
                                value="<?= htmlspecialchars($dataini_br) ?> até <?= htmlspecialchars($dataend_br) ?>">
                        </div>
                        
                        <div class="col-12 col-md-8 mb-2" style="margin-top:20px;">
                            <div class="row g-3">

                                <div class="col-6 col-lg-3">
                                    <div class="border p-3 rounded text-center shadow-sm">
                                        <p class="mb-1 text-muted">Agendadas</p>
                                        <h5 class="mb-0 text-info"><?= $consultasAgendadasAmount; ?></h5>
                                    </div>
                                </div>

                                <div class="col-6 col-lg-3">
                                    <div class="border p-3 rounded text-center shadow-sm">
                                        <p class="mb-1 text-muted">Confirmadas</p>
                                        <h5 class="mb-0 text-success"><?= $consultasConfirmadasAmount; ?></h5>
                                    </div>
                                </div>

                                <div class="col-6 col-lg-3">
                                    <div class="border p-3 rounded text-center shadow-sm">
                                        <p class="mb-1 text-muted">Concluídas</p>
                                        <h5 class="mb-0 text-secondary"><?= $consultasConcluidasAmount; ?></h5>
                                    </div>
                                </div>

                                <div class="col-6 col-lg-3">
                                    <div class="border p-3 rounded text-center shadow-sm">
                                        <p class="mb-1 text-muted ">Canc. / Falta</p>
                                        <h5 class="mb-0"><?= $consultasCanceladasAmount; ?></h5>
                                    </div>
                                </div>

                            </div>
                        </div>


                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <!-- Seção: Anamnese Médica -->
            <div class="card">
        <div class="card-body">
            <h4 class="header-title"><?= \App\Core\Language::get('lista_consultas'); ?></h4> 
            <p class="text-muted font-14">  
                <?= \App\Core\Language::get('consulta_desc'); ?>
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
                                                    <th><?= \App\Core\Language::get('dt_consulta'); ?></th> 
                                                    <th><?= \App\Core\Language::get('status'); ?></th>                                                   
                                                    <th><?= \App\Core\Language::get('telefone'); ?></th>
                                                    <th><?= \App\Core\Language::get('cpfrg'); ?></th>
                                                    <th><?= \App\Core\Language::get('nome_convenio'); ?></th>
                                                    <th><?= \App\Core\Language::get('dentista'); ?></th> 
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($Consultas as $listaConsulta): ?>
                                                    

                                                    <?php
                                                        $dataConsulta = (new DateTimeImmutable($listaConsulta['CON_DTCONSULTA']))->format('d/m/Y');
                                                        $consultaHoraIni = (new DateTime($listaConsulta['CON_HORACONSULTA']))->format('H\hi');

                                                        $hora = $listaConsulta['CON_HORACONSULTA'];
                                                        $duracao = $listaConsulta['CON_NUMDURACAO'];
                                                        $dt = new DateTime($hora);
                                                        $dt->add(new DateInterval("PT{$duracao}M"));
                                                        $consultaHoraFim = $dt->format('H\hi'); 

                                                        if ($listaConsulta['CON_ENSTATUS'] == "CONCLUIDA") {
                                                            $imgIcon = "uil uil-award-alt font-16";
                                                            $classIcon = "avatar-title bg-secondary-lighten rounded-circle text-secondary";
                                                            $iconStyle = "border: 1px solid #a7a6a5ff;";
                                                            $classeBadge = "secondary";
                                                        } elseif ($listaConsulta['CON_ENSTATUS'] == "CANCELADA") {
                                                            $imgIcon = "uil uil-stopwatch-slash font-16";
                                                            $classIcon = "avatar-title bg-warning-lighten rounded-circle text-warning";
                                                            $iconStyle = "border: 1px solid #ce7d04ff;";
                                                            $classeBadge = "warning";
                                                        } elseif ($listaConsulta['CON_ENSTATUS'] == "AGENDADA") {
                                                            $imgIcon = "uil  uil-clock font-16";
                                                            $classIcon = "avatar-title bg-primary-lighten rounded-circle text-primary";
                                                            $iconStyle = "border: 1px solid #4d55c5ff;";
                                                            $classeBadge = "primary";
                                                        } elseif ($listaConsulta['CON_ENSTATUS'] == "FALTA") {
                                                            $imgIcon = "uil uil-asterisk font-16";
                                                            $classIcon = "avatar-title bg-danger-lighten rounded-circle text-danger";
                                                            $iconStyle = "border: 1px solid #811818ff;";
                                                            $classeBadge = "danger";
                                                        } elseif ($listaConsulta['CON_ENSTATUS'] == "CONFIRMADA") {
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

                                                        $numeroDia = (int) date('w', strtotime($listaConsulta['CON_DTCONSULTA']));
                                                        $diaSemana = $semana[$numeroDia];

                                                        $showMaisInfo = "";
                                                        if (!empty($listaConsulta['CON_DCOBSERVACOES'])) {
                                                            $obs = htmlspecialchars($listaConsulta["CON_DCOBSERVACOES"], ENT_QUOTES, 'UTF-8');
                                                            $showMaisInfo = "
                                                            <a style='margin-left:5px; font-size:0.9rem; color: #04b0ffff;'
                                                                title='Mais informações'
                                                                onclick='event.stopPropagation();'
                                                                data-bs-toggle='modal'
                                                                data-bs-target='#info-alert-modal'
                                                                data-observacoes=\"$obs\">
                                                                <i class='ri-information-line' style='font-size: 18px;'></i>
                                                            </a>";
                                                        }

                                                        $whatsStatus = "disabled-link";
                                                        $reagendamentoStatus = "";
                                                        $whatsStatus = ($listaConsulta['CON_ENSTATUS'] == "AGENDADA") ? "" : $whatsStatus;
                                                        $reagendamentoStatus = ($listaConsulta['CON_ENSTATUS'] == "CONCLUIDA") ? "disabled-link" : $reagendamentoStatus;
                                                        
                                                        $idhash = $listaConsulta['CON_DCHASH_CONFIRMACAO_PRESENCA'];
                                                        $urlWhatsConfirmConsul = "https://app.smilecopilot.com/public/external_vw/cst_conf.php?id=$idhash";

                                                    ?>
<tr data-consulta-id="<?= htmlspecialchars($listaConsulta['CON_IDCONSULTA']) ?>" 
    data-consulta-hash="<?= htmlspecialchars($listaConsulta['CON_DCHASH_CONFIRMACAO_PRESENCA']); ?>"
    style="cursor: pointer;" 
    onclick="if(event.target.closest('td.dtr-control')) return false; window.location='/cadastropaciente';">

    <td>
        <div class="avatar-xs d-table">
            <span class="<?= $classIcon; ?>" style="<?= $iconStyle; ?>">
                <i class='<?= $imgIcon; ?>'></i>
            </span>
        </div>
    </td> 

    <td class="text-truncate" style="max-width: 180px;">
        <?= $showMaisInfo ?> 
        <?= htmlspecialchars(ucwords(strtolower((string)$listaConsulta['PAC_DCNOME'])), ENT_QUOTES, 'UTF-8') ?>
    </td>

    <td class="text-truncate" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#editarConsulta-modal">
        <span style="display:none;">
            <?= date(
                'Y-m-d H:i',
                strtotime($dataConsulta . ' ' . str_replace(['h','ás'], ['',''], $consultaHoraIni))
            ) ?>
        </span>
        <?= htmlspecialchars($dataConsulta, ENT_QUOTES, 'UTF-8') ?>
        <?= htmlspecialchars($consultaHoraIni, ENT_QUOTES, 'UTF-8') ?>
        <?= \App\Core\Language::get('as'); ?>
        <?= htmlspecialchars($consultaHoraFim, ENT_QUOTES, 'UTF-8') ?>
    </td>    

    <!-- 🔹 Aqui adicionamos a classe 'status' para o JS atualizar -->
    <td class="text-truncate status" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#editarConsulta-modal">
        <span class="badge badge-<?= $classeBadge; ?>-lighten">
            <?= htmlspecialchars(ucwords(strtolower((string)$listaConsulta['CON_ENSTATUS'])), ENT_QUOTES, 'UTF-8') ?>
        </span>
    </td>                                                

    <td><?= htmlspecialchars(ucwords(strtolower((string)$listaConsulta['PAC_DCTELEFONE'])), ENT_QUOTES, 'UTF-8') ?></td>
    <td><?= htmlspecialchars(ucwords(strtolower((string)$listaConsulta['PAC_DCCPF'])), ENT_QUOTES, 'UTF-8') ?></td>
    <td class="text-truncate" style="max-width: 150px;">
        <?= htmlspecialchars(ucwords(strtolower((string)$listaConsulta['CNV_DCCONVENIO'])), ENT_QUOTES, 'UTF-8') ?>
    </td>
    <td class="text-truncate" style="max-width: 150px;">
        <?= htmlspecialchars(ucwords(strtolower((string)$listaConsulta['DEN_DCNOME'])), ENT_QUOTES, 'UTF-8') ?>
    </td>
    
    <td onclick="event.stopPropagation();">   
        <a href="/editarpaciente?id=<?= htmlspecialchars($listaConsulta['PAC_IDPACIENTE']) ?>" class="action-icon" onclick="event.stopPropagation();"> 
            <i class="mdi mdi-account-outline" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="<?= \App\Core\Language::get('ver_paciente'); ?>"></i>
        </a>             

        <a href="javascript: void(0);" class="action-icon <?= $reagendamentoStatus ?>" onclick="event.stopPropagation();"> 
            <i class="mdi mdi-clock-outline" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="<?= \App\Core\Language::get('reagendar_consulta'); ?>"></i>
        </a>
        
        <a href="javascript: void(0);" 
            class="action-icon <?= $whatsStatus ?>" 
            data-bs-toggle="modal" 
            data-bs-target="#msg-modal"
            data-nome="<?= htmlspecialchars($listaConsulta['PAC_DCNOME']) ?>"
            data-profissional="<?= htmlspecialchars($listaConsulta['DEN_DCNOME']) ?>"
            data-telefone="<?= htmlspecialchars($listaConsulta['PAC_DCTELEFONE']) ?>"
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
        
        <a href="/anamnese" class="action-icon" onclick="event.stopPropagation();"> 
            <i class="mdi mdi-clipboard-list-outline" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="<?= \App\Core\Language::get('ver_anamneses_paciente'); ?>"></i>
        </a> 

        <a href="javascript:void(0);"  
            class="action-icon"
            data-id="<?= htmlspecialchars((string)$listaConsulta['PAC_IDPACIENTE'], ENT_QUOTES, 'UTF-8') ?>"    
            data-dialogTitle="<?= \App\Core\Language::get('lista_pacientes'); ?>"    
            data-dialogMessage="<?= \App\Core\Language::get('tem_certeza_excluir_paciente'); ?> <?= htmlspecialchars((string)$listaConsulta['PAC_DCNOME'], ENT_QUOTES, 'UTF-8') ?>?"   
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
            onclick="event.stopPropagation(); confirmDeleteAttr(this);">
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

<!-- msg modal -->
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
<!-- Info Alert Modal -->

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

<!-- Info Alert Modal -->
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
<!-- Info Alert Modal -->

<!-- stream eventos consultas -->
<script>
    document.addEventListener('DOMContentLoaded', function() {

      // 🔹 Cria a conexão SSE com a rota do seu controller PHP
      const eventSource = new EventSource('/streamevents'); 
      // Ajuste o caminho conforme sua rota MVC (ex: /pages/streamConsultaEventos)

      eventSource.onopen = () => {
        console.log('✅ Conectado ao stream de eventos');
      };

      // 🔹 Quando receber um evento do tipo "statusUpdate"
        eventSource.addEventListener('statusUpdate', (event) => {
            const data = JSON.parse(event.data);
            const consultaId = data.EVE_ID;       // CON_IDCONSULTA
            const novoStatus = data.EVE_DCVALOR;  // novo status
        
            const hashConsulta = consultaId;
            const linha = document.querySelector(`[data-consulta-hash="${hashConsulta}"]`);
            if(linha){
                const statusCell = linha.querySelector('.status span');
                if(statusCell){
                    statusCell.textContent = (novoStatus == 1) ? 'Confirmada' : 'Pendente';
                    statusCell.className = (novoStatus == 1) ? 'badge badge-success-lighten' : 'badge badge-danger-lighten';
                    mostrarAlerta(`Consulta #${linha.dataset.consultaId} foi atualizada para "${statusCell.textContent}"`);
                }
            }
        });

      // 🔹 Quando a conexão for encerrada pelo servidor (após 5 min)
      eventSource.addEventListener('close', (event) => {
        console.log('⚠️ Conexão encerrada pelo servidor. Reabrindo...');
        setTimeout(() => {
          location.reload(); // ou reabrir EventSource manualmente
        }, 2000);
      });

      // 🔹 Tratamento de erro
      eventSource.onerror = (err) => {
        console.error('🚨 Erro no stream:', err);
      };

      // 🔔 Função para exibir alerta bonito
      function mostrarAlerta(mensagem) {
        const alerta = document.createElement('div');
        alerta.className = 'alert alert-success position-fixed top-0 start-50 translate-middle-x mt-3';
        alerta.style.zIndex = '9999';
        alerta.textContent = mensagem;
        document.body.appendChild(alerta);

        setTimeout(() => alerta.remove(), 4000);
      }

    });
</script>

<!-- stream eventos consultas -->

<?php if ($lang  === "pt" || empty($lang)): ?>
    <script src="<?= ASSETS_PATH ?>utils/datatable-Init-ptbr_pacientes.js"></script>
<?php endif; ?>

<?php if ($lang  === "en"): ?>
    <script src="<?= ASSETS_PATH ?>utils/datatable-Init-en_paciente.js"></script>
<?php endif; ?>

<?php if ($lang  === "es"): ?>
    <script src="<?= ASSETS_PATH ?>utils/datatable-Init-es_paciente.js"></script>
<?php endif; ?>


