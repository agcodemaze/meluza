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
  .builder-area { border: 2px dashed #ccc; padding: 15px; min-height: 200px; border-radius: 10px; background: #f9f9f9; }
  .field-item { background: #fff; border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; border-radius: 6px; cursor: grab; }
  .field-item:hover { background: #f1f1f1; }
  .id-preview { font-size: 0.9em; color: #777; }
</style>


<!-- Start Content-->
<div class="container-fluid" style="max-width:100% !important; padding-left:10px; padding-right:10px;">
    <div class="row">
        <div class="col-12">
            <!-- Seção: Anamnese Médica -->
            <div class="card">
        <div class="card-body">


            <div class="tab-content">
                <div class="tab-pane show active" id="input-types-preview">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">

                                        <h4 class="mb-4">Construtor de Modelos</h3>  
                                        <div class="mb-4">
                                            <label for="titulo" class="form-label fw-bold">Digite o Nome do Modelo</label>
                                            <input type="text" id="titulo" class="form-control" placeholder="Ex: Anamnese para paciente novo">
                                        </div>  
                                        <div class="row">
                                            <div class="col-md-3">
                                            <h5>Tipos de Campo</h5>
                                            <div id="field-types" class="d-flex flex-column gap-2">
                                                <button class="btn btn-outline-info" data-type="text">Campo de Texto</button>
                                                <button class="btn btn-outline-info" data-type="textarea">Área de Texto</button>
                                                <button class="btn btn-outline-info" data-type="radio">Uma Escolha</button>
                                                <button class="btn btn-outline-info" data-type="checkbox">Múltipla escolha</button>
                                            </div>
                                            </div>
                                            <div class="col-md-9">
                                            <h5>Monte seu Modelo de Anamnese</h5>
                                            <div id="builder" class="builder-area"></div>
                                            <button id="exportar" class="btn btn-success mt-3">Salvar</button>
                                            
                                            </div>
                                        </div>



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


<script>
const builder = document.getElementById("builder");

// Função que gera um ID limpo a partir da pergunta
function gerarId(pergunta) {
  return pergunta
    .toLowerCase()
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "") // remove acentos
    .replace(/[^a-z0-9\s]/g, "") // remove símbolos
    .trim()
    .replace(/\s+/g, "_");
}

// Adiciona um novo campo
function addField(tipo, dados = {}) {
  const div = document.createElement("div");
  div.className = "field-item";
  div.innerHTML = `
    <div class="d-flex justify-content-between align-items-center">
      <strong>${tipo.toUpperCase()}</strong>
      <button class="btn btn-sm btn-danger remove">X</button>
    </div>
    <div class="mt-2">
      <label>Pergunta:</label>
      <input type="text" class="form-control pergunta" placeholder="Digite a pergunta" value="${dados.pergunta || ''}">
      <div class="id-preview"></div>
    </div>
    <div class="mt-2">
      <label><input type="checkbox" class="obrigatorio" ${dados.obrigatorio ? 'checked' : ''}> Marque se o campo for de preenchimento obrigatório.</label>
    </div>
    <div class="mt-2 tipo-extra"></div>
    <input type="hidden" class="tipo" value="${tipo}">
    <input type="hidden" class="id" value="${dados.id || ''}">
  `;

  const extra = div.querySelector(".tipo-extra");
  if (tipo === "radio" || tipo === "checkbox") {
    extra.innerHTML = `
      <label>Opções (separadas por vírgula):</label>
      <input type="text" class="form-control opcoes" value="${(dados.opcoes || []).join(', ')}">
    `;
  }

  const inputPergunta = div.querySelector(".pergunta");
  const idInput = div.querySelector(".id");
  const idPreview = div.querySelector(".id-preview");

  const atualizarId = () => {
    let idGerado = idInput.value.trim() || gerarId(inputPergunta.value);
    idInput.value = idGerado;
    idPreview.textContent = idGerado ? `🆔 ID automático: ${idGerado}` : "";
  };

  inputPergunta.addEventListener("input", atualizarId);
  atualizarId();

  div.querySelector(".remove").onclick = () => div.remove();
  builder.appendChild(div);
}

// Botões de tipo de campo
document.querySelectorAll("#field-types button").forEach(btn => {
  btn.addEventListener("click", () => addField(btn.dataset.type));
});

// Ativa drag & drop
Sortable.create(builder, { animation: 150 });

// Salvar JSON via POST
document.getElementById("exportar").addEventListener("click", async () => {
  const titulo = document.getElementById("titulo").value.trim();
  if (!titulo) {
    Swal.fire('Atenção', 'Por favor, informe um nome para o modelo.', 'warning');
    return;
  }

  const perguntas = [];
  builder.querySelectorAll(".field-item").forEach(item => {
    const id = item.querySelector(".id").value.trim();
    const tipo = item.querySelector(".tipo").value;
    const pergunta = item.querySelector(".pergunta").value.trim();
    const obrigatorio = item.querySelector(".obrigatorio").checked;
    const opcoes = item.querySelector(".opcoes")?.value.split(",").map(o => o.trim()).filter(o => o) || [];

    if (!id || !pergunta) return;

    const obj = { id, tipo, pergunta, obrigatorio };
    if (opcoes.length) obj.opcoes = opcoes;
    perguntas.push(obj);
  });

  if (!perguntas.length) {
    Swal.fire('Atenção', 'Adicione pelo menos uma pergunta ao formulário.', 'warning');
    return;
  }

  const modelo = {
    secoes: [{
      titulo,
      descricao: "Modelo personalizado de anamnese.",
      perguntas
    }],
    titulo,
    versao: 1
  };

  const idioma = "<?= $lang ?>";
    // Monta objeto a enviar
  const payload = { titulo, modelo, idioma };

  // Mostra loading
  Swal.fire({
    title: 'Salvando...',
    allowOutsideClick: false,
    didOpen: () => { Swal.showLoading() }
  });

  try {
    const response = await fetch('/cadmodeloanamneseProc', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const result = await response.json();

    if (result.success) {
      Swal.fire('Sucesso', 'Modelo de anamnese salvo com sucesso!', 'success');      
    } else {
      Swal.fire('Erro', result.message || 'Ocorreu um erro ao salvar.', 'error');
    }
  } catch (err) {
    Swal.fire('Erro', 'Não foi possível conectar ao servidor.', 'error');
    console.error(err);
  }
});
</script>


