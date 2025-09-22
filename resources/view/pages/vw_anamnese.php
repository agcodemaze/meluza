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
                <h4 class="page-title"><?= \App\Core\Language::get('cadastro_paciente'); ?></h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    
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
<br>


