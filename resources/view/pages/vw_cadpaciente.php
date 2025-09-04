<?php
date_default_timezone_set('America/Sao_Paulo');
$dataHoraServidor = date('Y-m-d H:i:s'); // hora atual do servidor

// Agora cada consulta tem 'data_hora' completa
$consultas = [
    ["data_hora" => "2025-09-04 11:00", "paciente" => "JOÃO ALBERTO MEDEIROS", "duracao" => 30],
    ["data_hora" => "2025-09-04 11:20", "paciente" => "MARIA DORALINA DE JESUS", "duracao" => 45],
    ["data_hora" => "2025-09-04 11:30", "paciente" => "CARLOS LIMA", "duracao" => 60], // dia seguinte
    ["data_hora" => "2025-09-04 12:30", "paciente" => "ANA FLAVIA DE ARAÚJO", "duracao" => 30]    // dia anterior
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
                <h4 class="page-title">Cadastro de Paciente</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

                    
    
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Informações Básicas</h4>
                    <p class="text-muted font-14">
                        Seção para cadastro de dados básicos de contato do paciente.
                    </p>

                    <div class="tab-content">
                        <div class="tab-pane show active" id="input-types-preview">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="nome" class="form-label">Nome Completo</label>
                                        <input type="text" id="nome" name="nome" class="form-control" style="text-transform: uppercase;">
                                    </div>
                                    <div class="mb-3">
                                        <label for="sexo" class="form-label">Sexo</label>                                        
                                        <select class="form-select" id="sexo" name="sexo" required>
                                            <option value="">SELECIONE</option>
                                            <option value="MASCULINO">MASCULINO</option>
                                            <option value="FEMININO">FEMININO</option>
                                        </select>
                                    </div>
                                </div> <!-- end col -->

                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="telefone" class="form-label">DDD + Telefone</label>
                                        <input type="text" id="telefone" name="nomtelefonee" class="form-control" data-toggle="input-mask" data-mask-format="(00) 00000-0000">
                                    </div>
                                    <div class="mb-3">
                                        <label for="dtnascimento" class="form-label">Data de Nascimento</label>
                                        <input type="text" id="dtnascimento" name="dtnascimento" class="form-control" data-toggle="input-mask" data-mask-format="00/00/0000">
                                    </div>
                                </div> <!-- end col -->

                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">E-mail</label>
                                        <input type="text" id="email" name="email" class="form-control" style="text-transform: uppercase;">
                                    </div>
                                    <div class="mb-3">
                                        <label for="documento" class="form-label">CPF/RG</label>
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
                    <h4 class="header-title">Informações Administrativas </h4>
                    <p class="text-muted font-14">
                        "Preencha os dados administrativos para o paciente, incluindo plano, número da carteirinha e etc.
                    </p>

                    <div class="tab-content">
                        <div class="tab-pane show active" id="input-types-preview">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="nomeconvenio" class="form-label">Nome do Convênio</label>                                        
                                        <select class="form-control select2" id="nomeconvenio" name="nomeconvenio" data-toggle="select2">
                                            <option value="">SELECIONE</option>
                                            <?php foreach ($convenios as $convenio): ?>
                                                <option value="<?= $convenio['CNV_IDCONVENIO'] ?>">
                                                    <?= strtoupper($convenio['CNV_DCCONVENIO']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="dtcadastro" class="form-label">Cliente Desde</label>
                                        <input type="text" id="dtcadastro" name="dtcadastro" class="form-control">
                                    </div>
                                </div> <!-- end col -->

                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="plano" class="form-label">Plano/Produto</label>
                                        <input type="text" id="plano" name="plano" class="form-control" style="text-transform: uppercase;">
                                    </div>
                                    <div class="mb-3">
                                        <label for="comoconheceu" class="form-label">Como nos Conheceu?</label>                                        
                                        <select class="form-select" id="comoconheceu" name="comoconheceu" required>
                                            <option value="">SELECIONE</option>
                                            <option value="GOOGLE">GOOGLE</option>
                                            <option value="INSTAGRAM">INSTAGRAM</option>
                                            <option value="FACEBOOK">FACEBOOK</option>
                                            <option value="YOUTUBE">YOUTUBE</option>
                                            <option value="APP">APP DO CONVÊNIO</option>
                                            <option value="INDICACAO">PACIENTE INDICOU</option>
                                            <option value="OUTRO">OUTRO</option>
                                        </select>
                                    </div>
                                </div> <!-- end col -->

                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="numerocarteirinha" class="form-label">Nº da Carteirinha</label>
                                        <input type="text" id="numerocarteirinha" name="numerocarteirinha" class="form-control" style="text-transform: uppercase;">
                                    </div>
                                    <div class="mb-3">
                                        <label for="observacoes" class="form-label">Observações</label>
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
                            <button type="button" onclick="window.history.back()" class="btn btn-danger"><i class="mdi mdi-keyboard-backspace me-1"></i> <span>Voltar</span> </button>
                            <button type="button" class="btn btn-info"><i class="mdi mdi-content-save-outline me-1"></i> <span>Salvar</span> </button>
                        </div> <!-- end col -->
                    </div> <!-- end row-->
                </div> <!-- end preview-->
            </div> <!-- end tab-content-->            

        </div><!-- end col -->
    </div><!-- end row -->
</div>
<br>


