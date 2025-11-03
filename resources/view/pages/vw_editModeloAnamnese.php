<?php
date_default_timezone_set('America/Sao_Paulo');
$dataHoraServidor = date('Y-m-d H:i:s');
$lang = $_SESSION['lang'] ?? 'pt';

use \App\Controller\Pages\EncryptDecrypt; 
use \App\Model\Entity\Organization;

$objOrganization = new Organization();
$configuracoes = $objOrganization->getConfiguracoes(TENANCY_ID);

$nomeClinica = mb_convert_case(mb_strtolower($configuracoes["CFG_DCNOME_CLINICA"]), MB_CASE_TITLE, "UTF-8");

$key = $_ENV['ENV_SECRET_KEY'] ?? getenv('ENV_SECRET_KEY') ?? '';

$modeloJson = $anamneseInfo["ANM_JSON_MODELO"] ?? '{}';
$tituloModelo = $anamneseInfo["ANM_DCTITULO"] ?? '';

?>

<style>
  .builder-area { border: 2px dashed #ccc; padding: 15px; min-height: 200px; border-radius: 10px; background: #f9f9f9; }
  .field-item { background: #fff; border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; border-radius: 6px; cursor: grab; }
  .field-item:hover { background: #f1f1f1; }
  .id-preview { font-size: 0.9em; color: #777; }
</style>

<div class="container-fluid" style="max-width:100% !important; padding-left:10px; padding-right:10px;">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="tab-content">
            <div class="tab-pane show active" id="input-types-preview">
              <div class="row">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <h4 class="mb-4">Editar Modelo de Anamnese</h4>

                        <div class="mb-4">
                          <label for="titulo" class="form-label fw-bold">Nome do Modelo</label>
                          <input type="text" id="titulo" class="form-control" placeholder="Digite o nome do modelo" 
                                 value="<?= htmlspecialchars($tituloModelo) ?>">
                        </div>

                        <div class="row">
                    <div class="col-md-3">
                      <h5>Adicionar Campos de Entrada</h5>

                      <!-- Grupo principal de campos -->
                      <div id="field-types" class="d-flex flex-column gap-2 mb-3" style="max-width: 280px;">
                        <button class="btn btn-info text-white w-100 d-flex align-items-center justify-content-center" data-type="text">
                          <i class="ri-text me-2"></i> Campo de Texto
                        </button>
                        <button class="btn btn-info text-white w-100 d-flex align-items-center justify-content-center" data-type="textarea">
                          <i class="ri-file-text-line me-2"></i> Área de Texto
                        </button>
                        <button class="btn btn-info text-white w-100 d-flex align-items-center justify-content-center" data-type="radio">
                          <i class="ri-list-radio me-2"></i> Múltiplas Opções
                        </button>
                      </div>

                      <!-- Novo grupo de ações -->
                      <div id="field-actions" class="d-flex flex-column gap-2 p-3" style="max-width: 280px; border: 2px dashed #0dcaf0; border-radius: 8px;">
                        <button id="exportar" class="btn btn-success text-white w-100 d-flex align-items-center justify-content-center">
                          <i class="ri-save-line me-2"></i> Salvar
                        </button>
                        <button type="button" class="btn btn-secondary w-100 d-flex align-items-center justify-content-center" onclick="history.back()">
                          <i class="ri-arrow-left-line me-2"></i> Voltar
                        </button>
                      </div>
                    </div>

                          <div class="col-md-9">
                            <h5>Monte seu Modelo de Anamnese</h5>
                            <div id="builder" class="builder-area"></div>

                            <div class="d-flex justify-content-between mt-3">
                              <button type="button" class="btn btn-secondary" onclick="history.back()">
                                <i class="ri-arrow-left-line"></i> Voltar
                              </button>
                              <button id="exportar" class="btn btn-success text-white">
                                <i class="ri-save-line"></i> Salvar
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div> <!-- end card body-->
                  </div>
                </div>
              </div> <!-- end row -->
            </div> <!-- end preview-->
          </div> <!-- end tab-content-->
        </div>
      </div>
    </div>
  </div>
</div>
<br>

<script>
  const builder = document.getElementById("builder");

  const tipoLabels = {
    text: "CAIXA DE TEXTO",
    textarea: "CAIXA DE TEXTO LONGA",
    radio: "MULTIPLAS OPÇÕES"
  };

  const modeloSalvo = <?= $modeloJson ?: '{}' ?>;

  function gerarId(pergunta) {
    return pergunta
      .toLowerCase()
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "")
      .replace(/[^a-z0-9\s]/g, "")
      .trim()
      .replace(/\s+/g, "_");
  }

  function addField(tipo, dados = {}) {
    const labelTipo = tipoLabels[tipo] || tipo; 
    const div = document.createElement("div");
    div.className = "field-item";
    div.innerHTML = `
      <div class="d-flex justify-content-between align-items-center">
        <strong>${labelTipo}</strong>
        <button class="btn btn-sm btn-secondary remove">X</button>
      </div>
      <div class="mt-2">
        <label>Pergunta:</label>
        <input type="text" class="form-control pergunta" placeholder="Digite a pergunta" value="${dados.pergunta || ''}">
        <div class="id-preview"></div>
      </div>
      <div class="mt-2">
        <label><input type="checkbox" class="obrigatorio" ${dados.obrigatorio ? 'checked' : ''}> Obrigatório.</label>
      </div>
      <div class="mt-2 tipo-extra"></div>
      <input type="hidden" class="tipo" value="${tipo}">
      <input type="hidden" class="id" value="${dados.id || ''}">
    `;

    const extra = div.querySelector(".tipo-extra");
    if (tipo === "radio") {
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

  if (modeloSalvo?.secoes?.length) {
    const secao = modeloSalvo.secoes[0];
    if (secao?.perguntas?.length) {
      secao.perguntas.forEach(pergunta => addField(pergunta.tipo, pergunta));
    }
  }

  document.querySelectorAll("#field-types button").forEach(btn => {
    btn.addEventListener("click", () => addField(btn.dataset.type));
  });

  Sortable.create(builder, { animation: 150 });

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

    const modelo = {
      secoes: [{
        titulo,
        descricao: "Modelo de anamnese editado.",
        perguntas
      }],
      titulo,
      versao: 2
    };

    const id = "<?= $anamneseInfo["ANM_IDANAMNESE_MODELO"] ?>";
    const idioma = "<?= $lang ?>";
    const payload = { titulo, modelo, idioma, id };

    Swal.fire({
      title: 'Salvando alterações...',
      allowOutsideClick: false,
      didOpen: () => { Swal.showLoading() }
    });

    try {
      const response = await fetch('/updatemodeloanamneseProc', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });

      const result = await response.json();
      if (result.success) {
        Swal.fire({
          title: 'Sucesso!',
          text: 'Modelo atualizado com sucesso!',
          icon: 'success',
          confirmButtonText: 'OK'
        }).then(() => {
          window.location.href = '/listmodeloanamnese';
        });
      } else {
        Swal.fire('Erro', result.message || 'Ocorreu um erro ao salvar.', 'error');
      }
    } catch (err) {
      Swal.fire('Erro', 'Não foi possível conectar ao servidor.', 'error');
      console.error(err);
    }
  });
</script>
