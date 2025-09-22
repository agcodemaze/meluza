<?php
date_default_timezone_set('America/Sao_Paulo');
$dataHoraServidor = date('Y-m-d H:i:s'); // hora atual do servidor

?>

<!-- Start Content-->
<div class="container-fluid" style="max-width:95% !important; padding-left:20px; padding-right:20px;">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">        
            <div class="page-title-box">
                <div class="page-title-right">
                </div>
                <h4 class="page-title">
                  <a href="javascript:history.back()" class="text-decoration-none me-2">
                    ← Voltar
                  </a>
                  <?= \App\Core\Language::get('cadastro_paciente'); ?></h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
                <div class="col-sm-12">
                    <!-- Profile -->
                    <div class="card bg-primary">
                        <div class="card-body profile-user-box">
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="avatar-lg position-relative d-inline-block">
                                                <img src="../../../public/assets/images/users/avatar-10.jpg" alt="" class="rounded-circle img-thumbnail">
                                                <!-- Botão pequeno com ícone de câmera -->
                                                <button type="button" class="btn btn-sm btn-primary position-absolute bottom-0 start-50 translate-middle-x" 
                                                        style="font-size: 12px; padding: 4px 6px;">
                                                    <i class="ri-camera-line"></i>
                                                </button>
                                            </div>                                                                                                  </div>
                                        <div class="col">
                                            <div>
                                                <h4 class="mt-1 mb-1 text-white">Aline Medeiros dos Santos</h4>
                                                <p class="font-13 text-white-50">
                                                    <i class="ri-whatsapp-line"></i> <!-- pequeno espaço após o ícone -->
                                                    (11) 98273-4350  
                                                    <span class="ms-2"><?= \App\Core\Language::get('informacoes_basicas'); ?>: 049.967.919-93</span> <!-- leve espaço antes do CPF -->
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- end col-->
                            </div> <!-- end row -->
                        </div> <!-- end card-body/ profile-user-box-->
                    </div><!--end profile/ card -->
                </div> <!-- end col-->
    </div>
    <!-- end row -->  

    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a href="#infobasica" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                <i class="mdi mdi-home-variant d-md-none d-block"></i>
                <span class="d-none d-md-block">Informações Básicas</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#prontuario" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                <i class="mdi mdi-account-circle d-md-none d-block"></i>
                <span class="d-none d-md-block">Prontuário</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#anamnese" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                <i class="mdi mdi-clipboard-list-outline d-md-none d-block"></i>
                <span class="d-none d-md-block">Anamnese</span>
            </a>
        </li>
    </ul>
    
    <div class="tab-content">
        <!-- ------- ABA INFO BASIC -------- --> 
        <div class="tab-pane show active" id="infobasica">
            <div class="row">
                <div class="col-12">
                <form class="needs-validation" id="form" name="form" role="form" method="POST" enctype="multipart/form-data" novalidate>
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title"><?= \App\Core\Language::get('informacoes_basicas'); ?></h4>
                            <p class="text-muted font-14">
                                <?= \App\Core\Language::get('secao_cad_basico'); ?>
                            </p>                
                            <div class="tab-content">
                                <div class="tab-pane show active" id="input-types-preview"> 
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label for="nome" class="form-label"><?= \App\Core\Language::get('nome_completo'); ?></label>
                                                <input type="text" id="nome" name="nome" class="form-control" style="text-transform: uppercase;">
                                            </div>
                                            <div class="mb-3">
                                                <label for="sexo" class="form-label"><?= \App\Core\Language::get('sexo'); ?></label>                                         
                                                <select class="form-select" id="sexo" name="sexo" required>
                                                    <option value=""><?= \App\Core\Language::get('selecione'); ?></label> </option>
                                                    <option value="MASCULINO"><?= \App\Core\Language::get('masculino'); ?></option>
                                                    <option value="FEMININO"><?= \App\Core\Language::get('feminino'); ?></option>
                                                </select>
                                            </div>
                                        </div> <!-- end col -->
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label for="telefone" class="form-label"><?= \App\Core\Language::get('telefone'); ?></label>
                                                <input type="text" id="telefone" name="nomtelefonee" class="form-control" data-toggle="input-mask" data-mask-format="(00) 00000-0000">
                                            </div>
                                            <div class="mb-3">
                                                <label for="dtnascimento" class="form-label"><?= \App\Core\Language::get('data_nascimento'); ?></label> 
                                                <input type="text" id="dtnascimento" name="dtnascimento" class="form-control" data-toggle="input-mask" data-mask-format="00/00/0000">
                                            </div>
                                        </div> <!-- end col -->
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label for="email" class="form-label"><?= \App\Core\Language::get('email'); ?></label>
                                                <input type="text" id="email" name="email" class="form-control" style="text-transform: uppercase;">
                                            </div>
                                            <div class="mb-3">
                                                <label for="documento" class="form-label"><?= \App\Core\Language::get('cpfrg'); ?></label></label>
                                                <input type="text" id="documento" name="documento" class="form-control" style="text-transform: uppercase;">
                                            </div>
                                        </div> <!-- end col -->
                                    </div>
                                    <!-- end row-->
                                </div> <!-- end preview-->
                            </div> <!-- end tab-content-->
                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title"><?= \App\Core\Language::get('informacoes_administrativas'); ?> </h4> 
                            <p class="text-muted font-14"> 
                                <?= \App\Core\Language::get('descricao_administrativas'); ?>
                            </p>
                            <div class="tab-content">
                                <div class="tab-pane show active" id="input-types-preview">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label for="nomeconvenio" class="form-label"><?= \App\Core\Language::get('nome_convenio'); ?></label>                                        
                                                <select class="form-control select2" id="nomeconvenio" name="nomeconvenio" data-toggle="select2">
                                                    <option value=""><?= \App\Core\Language::get('selecione'); ?></option>
                                                    <?php foreach ($convenios as $convenio): ?>
                                                        <option value="<?= $convenio['CNV_IDCONVENIO'] ?>">
                                                            <?= strtoupper($convenio['CNV_DCCONVENIO']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="dtcadastro" class="form-label"><?= \App\Core\Language::get('cliente_desde'); ?></label>
                                                <input type="text" id="dtcadastro" name="dtcadastro" class="form-control">
                                            </div>
                                        </div> <!-- end col -->
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label for="plano" class="form-label"><?= \App\Core\Language::get('plano_produto'); ?></label>
                                                <input type="text" id="plano" name="plano" class="form-control" style="text-transform: uppercase;">
                                            </div>
                                            <div class="mb-3">
                                                <label for="comoconheceu" class="form-label"><?= \App\Core\Language::get('como_nos_conheceu'); ?></label>                                         
                                                <select class="form-select" id="comoconheceu" name="comoconheceu" required>
                                                    <option value=""><?= \App\Core\Language::get('selecione'); ?></option>
                                                    <option value="GOOGLE"><?= \App\Core\Language::get('google'); ?></option>
                                                    <option value="INSTAGRAM"><?= \App\Core\Language::get('instagram'); ?></option>
                                                    <option value="FACEBOOK"><?= \App\Core\Language::get('facebook'); ?></option>
                                                    <option value="YOUTUBE"><?= \App\Core\Language::get('youtube'); ?></option>
                                                    <option value="APP"><?= \App\Core\Language::get('app_convenio'); ?></option>
                                                    <option value="INDICACAO"><?= \App\Core\Language::get('paciente_indicou'); ?></option>
                                                    <option value="OUTRO"><?= \App\Core\Language::get('outro'); ?></option>
                                                </select>
                                            </div>
                                        </div> <!-- end col -->
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label for="numerocarteirinha" class="form-label"><?= \App\Core\Language::get('numero_carteirinha'); ?></label>
                                                <input type="text" id="numerocarteirinha" name="numerocarteirinha" class="form-control" style="text-transform: uppercase;">
                                            </div>
                                            <div class="mb-3">
                                                <label for="observacoes" class="form-label"><?= \App\Core\Language::get('observações'); ?></label>
                                                <input type="text" id="observacoes" name="observacoes" class="form-control">
                                            </div>
                                        </div> <!-- end col -->
                                    </div>
                                    <!-- end row-->
                                </div> <!-- end preview-->
                            </div> <!-- end tab-content-->
                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                    <div class="tab-content">
                        <div class="tab-pane show active" id="input-types-preview">
                            <div class="row">
                                <div class="col-lg-4">
                                    <button type="button" onclick="window.history.back()" class="btn btn-danger"><i class="mdi mdi-keyboard-backspace me-1"></i> <span><?= \App\Core\Language::get('voltar'); ?></span> </button>
                                    <button type="button" class="btn btn-info"><i class="mdi mdi-content-save-outline me-1"></i> <span><?= \App\Core\Language::get('salvar'); ?></span> </button>
                                </div> <!-- end col -->
                            </div> <!-- end row-->
                        </div> <!-- end preview-->
                    </div> <!-- end tab-content-->            
                </form>                                           
                </div><!-- end col -->
            </div><!-- end row -->
        </div>
        <!-- ------- ABA INFO BASIC -------- --> 

        <!-- ------- ABA PRONTUÁRIO -------- -->                                                 
        <div class="tab-pane" id="prontuario">
            <p>...</p>
        </div>
        <!-- ------- ABA PRONTUÁRIO -------- -->   
        
        <!-- ------- ABA ANAMNESE -------- -->   
        <div class="tab-pane" id="anamnese">
            <div class="row">
                <div class="col-12">
                    <form class="needs-validation" id="form" name="form" role="form" method="POST" enctype="multipart/form-data" novalidate>
                        <!-- Seção: Anamnese Médica -->
                        <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Anamnese Médica</h4> 
                            <p class="text-muted font-14"> 
                                Responda às perguntas sobre seu histórico de saúde geral.
                            </p>
                            <div class="tab-content">
                                <div class="tab-pane show active" id="input-types-preview">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label d-block">Possui problemas cardíacos ou circulatórios?</label>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="cardiaco" id="cardiaco-sim" value="sim">
                                                    <label class="form-check-label" for="cardiaco-sim">Sim</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="cardiaco" id="cardiaco-nao" value="nao" checked>
                                                    <label class="form-check-label" for="cardiaco-nao">Não</label>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label d-block">Possui diabetes?</label>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="diabetes" id="diabetes-sim" value="sim">
                                                    <label class="form-check-label" for="diabetes-sim">Sim</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="diabetes" id="diabetes-nao" value="nao" checked>
                                                    <label class="form-check-label" for="diabetes-nao">Não</label>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label d-block">Tem alergia a algum medicamento?</label>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="alergia" id="alergia-sim" value="sim">
                                                    <label class="form-check-label" for="alergia-sim">Sim</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="alergia" id="alergia-nao" value="nao" checked>
                                                    <label class="form-check-label" for="alergia-nao">Não</label>
                                                </div>
                                                <input type="text" id="alergia_desc" name="alergia_desc" class="form-control mt-2" placeholder="Qual?">
                                            </div>
                                        </div> <!-- end col -->
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label d-block">É fumante?</label>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="fumante" id="fumante-sim" value="sim">
                                                    <label class="form-check-label" for="fumante-sim">Sim</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="fumante" id="fumante-nao" value="nao" checked>
                                                    <label class="form-check-label" for="fumante-nao">Não</label>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label d-block">Faz uso de alguma medicação?</label>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="medicacao" id="medicacao-sim" value="sim">
                                                    <label class="form-check-label" for="medicacao-sim">Sim</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="medicacao" id="medicacao-nao" value="nao" checked>
                                                    <label class="form-check-label" for="medicacao-nao">Não</label>
                                                </div>
                                                <input type="text" id="medicacao_desc" name="medicacao_desc" class="form-control mt-2" placeholder="Qual?">
                                            </div>
                                            <div class="mb-3">
                                                <label for="obs_medica" class="form-label">Outras Observações Médicas</label>
                                                <textarea id="obs_medica" name="obs_medica" class="form-control" rows="3"></textarea>
                                            </div>
                                        </div> <!-- end col -->
                                    </div>
                                    <!-- end row-->
                                </div> <!-- end preview-->
                            </div> <!-- end tab-content-->
                        </div> <!-- end card-body -->
                        </div> <!-- end card -->
                        <!-- Seção: Anamnese Odontológica -->
                        <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Anamnese Odontológica</h4> 
                            <p class="text-muted font-14"> 
                                Perguntas sobre sua saúde bucal.
                            </p>
                            <div class="tab-content">
                                <div class="tab-pane show active" id="input-types-preview">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="motivo_consulta" class="form-label">Motivo da Consulta</label>
                                                <input type="text" id="motivo_consulta" name="motivo_consulta" class="form-control">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label d-block">Sente sensibilidade (quente/frio)?</label>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="sensibilidade" id="sensibilidade-sim" value="sim">
                                                    <label class="form-check-label" for="sensibilidade-sim">Sim</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="sensibilidade" id="sensibilidade-nao" value="nao" checked>
                                                    <label class="form-check-label" for="sensibilidade-nao">Não</label>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label d-block">Hábitos (roer unhas, morder caneta, etc.)?</label>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="habitos" id="habitos-sim" value="sim">
                                                    <label class="form-check-label" for="habitos-sim">Sim</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="habitos" id="habitos-nao" value="nao" checked>
                                                    <label class="form-check-label" for="habitos-nao">Não</label>
                                                </div>
                                                <input type="text" id="habitos_desc" name="habitos_desc" class="form-control mt-2" placeholder="Quais?">
                                            </div>
                                        </div> <!-- end col -->
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label d-block">Última visita ao dentista?</label>
                                                <input type="text" id="ultima_visita" name="ultima_visita" class="form-control" placeholder="Ex: 6 meses atrás">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label d-block">Já teve sangramento na gengiva?</label>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="sangramento" id="sangramento-sim" value="sim">
                                                    <label class="form-check-label" for="sangramento-sim">Sim</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="sangramento" id="sangramento-nao" value="nao" checked>
                                                    <label class="form-check-label" for="sangramento-nao">Não</label>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="obs_odontologica" class="form-label">Outras Observações Odontológicas</label>
                                                <textarea id="obs_odontologica" name="obs_odontologica" class="form-control" rows="3"></textarea>
                                            </div>
                                        </div> <!-- end col -->
                                    </div>
                                    <!-- end row-->
                                </div> <!-- end preview-->
                            </div> <!-- end tab-content-->
                        </div> <!-- end card-body -->
                        </div> <!-- end card -->
                        <!-- Botões -->
                        <div class="tab-content">
                        <div class="tab-pane show active" id="input-types-preview">
                            <div class="row">
                                <div class="col-lg-4">
                                    <button type="button" onclick="window.history.back()" class="btn btn-danger"><i class="mdi mdi-keyboard-backspace me-1"></i> <span>Voltar</span> </button>
                                    <button type="submit" class="btn btn-info"><i class="mdi mdi-content-save-outline me-1"></i> <span>Salvar</span> </button>
                                </div> <!-- end col -->
                            </div> <!-- end row-->
                        </div> <!-- end preview-->
                        </div> <!-- end tab-content-->            
                    </form>                                       
                </div><!-- end col -->
            </div><!-- end row -->
        </div>
        <!-- ------- ABA ANAMNESE -------- -->   
    </div> <!-- close content -->   
</div> <!-- close fluid -->   
<br>


