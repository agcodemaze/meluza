<?php
date_default_timezone_set('America/Sao_Paulo');
$dataHoraServidor = date('Y-m-d H:i:s'); // hora atual do servidor

?>

<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                </div>
                <h4 class="page-title"><?= \App\Core\Language::get('cadastro_paciente'); ?></h4>
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
                        <div class="col-sm-4">
                            <div class="text-center mt-sm-0 mt-3 text-sm-end">
                                <a href="/anamnese" class="btn btn-light">
                                    <i class="mdi mdi-clipboard-list-outline me-1"></i> Anamneses
                                </a>
                            </div>
                        </div> <!-- end col-->
                    </div> <!-- end row -->
                </div> <!-- end card-body/ profile-user-box-->
            </div><!--end profile/ card -->
        </div> <!-- end col-->
    </div>
    <!-- end row -->                
    
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
<br>


