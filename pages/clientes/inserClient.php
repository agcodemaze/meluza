<?php
//require BASE_PATH . "src/auth.php"; 
include_once BASE_PATH . "objects/objects.php";

$siteAdmin = new SITE_ADMIN(); 

?>

<!DOCTYPE html>
<html lang="en">
   <head>

	<?php include_once BASE_PATH . "src/head.php"; ?>

   </head>
   <body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
      <div class="app-wrapper">
                  
	      <?php include_once BASE_PATH . "src/topbar.php"; ?>
	      <?php include_once BASE_PATH . "src/menu.php"; ?>

         <main class="app-main">
            <div class="app-content-header">
               <div class="container-fluid">
                  <div class="row">
                     <div class="col-sm-6">
                        <h3 class="mb-0">Clientes</h3>
                     </div>
                     <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item active" aria-current="page">
                              
                           </li>
                        </ol>
                     </div>
                  </div>
               </div>
            </div>
            <div class="app-content">
               <div class="container-fluid">
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                </div>
                                <h4 class="page-title"></h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Novo Cliente</h4>
                                    <p class="text-muted font-14">Obaa! Cadastre seu novo cliente aqui.
                                    </p>

                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="tooltips-validation-preview">
                                            <form id="form" name="form" role="form" method="POST" enctype="multipart/form-data">                                       

                                                <div class="position-relative mb-3" id="campo-nome">
                                                  <label class="form-label" for="nome">Nome Completo</label>
                                                  <input id="nome" name="nome" autocomplete="new-nome" style="text-transform: uppercase;" type="text" class="form-control" placeholder="" maxlength="50"  oninput="this.value = this.value.replace(/[^\p{L} ]/gu, '')" required/>
                                                </div>

                                                <div class="position-relative mb-3" id="campo-telefone">
                                                  <label class="form-label" for="telefone">DDD + Telefone (Whatsapp)</label>
                                                  <input id="telefone" name="telefone" autocomplete="new-telefone" type="text" class="form-control" placeholder="Ex.: 11982734359" pattern="^\d{11}$" minlength="11" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required />
                                                </div> 

                                                <!-- Campos de endereço -->
                                                <div class="position-relative mb-3">
                                                    <label for="cep" class="form-label">CEP</label>
                                                    <input type="text" id="cep" name="cep" class="form-control" data-toggle="input-mask" data-mask-format="00000-000" placeholder="Digite o CEP" maxlength="9" onblur="buscarEndereco()">
                                                </div>

                                                <div class="position-relative mb-3">
                                                    <label for="endereco" class="form-label">Endereço</label>
                                                    <input type="text" id="endereco" name="endereco" class="form-control" style="text-transform: uppercase;" placeholder="" readonly>
                                                </div>

                                                <div class="position-relative position-relative mb-3">
                                                    <label for="numero" class="form-label">Número</label>
                                                    <input type="text" id="numero" name="numero" class="form-control" style="text-transform: uppercase;" placeholder="Digite o número">
                                                </div>

                                                <div class="position-relative mb-3">
                                                    <label for="bairro" class="form-label">Bairro</label>
                                                    <input type="text" id="bairro" name="bairro" class="form-control" style="text-transform: uppercase;" placeholder="" readonly>
                                                </div>

                                                <div class="position-relative mb-3">
                                                    <label for="cidade" class="form-label">Cidade</label>
                                                    <input type="text" id="cidade" name="cidade" class="form-control" style="text-transform: uppercase;" placeholder="" readonly>
                                                </div>

                                                <div class="position-relative mb-3">
                                                    <label for="estado" class="form-label">Estado</label>
                                                    <input type="text" id="estado" name="estado" class="form-control" style="text-transform: uppercase;" placeholder="" readonly>
                                                </div>

                                                <div class="position-relative mb-3">
                                                  <label for="observacao" class="form-label">Observações</label>
                                                  <p class="text-muted font-13">Observações</p>
                                                  <textarea class="form-control" maxlength="300" rows="3" id="observacao" name="observacao" placeholder=""></textarea>
                                                </div> 
                                            
                                                <button class="btn" style="background-color: #6e6c72; color: white;" onclick="window.history.back()" type="button">Voltar</button>         
                                                <button class="btn" style="background-color: #7eda0d; color: black;" type="submit" id="botao" name="botao">Salvar</button>                                            
                                            </form>
                                        </div> <!-- end preview-->                                        
                                    </div> <!-- end tab-content-->
                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div> <!-- end col-->
                    </div> 
               
               </div>
            </div>
         </main>

	      <?php include_once BASE_PATH . "src/footer.php"; ?>

      </div>
    <script src="../../js/overlayscrollbars.browser.es6.min.js"></script>
    <script src="../../js/popper.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/adminlte.js"></script>
	<?php include_once BASE_PATH . "src/config.php"; ?>

    <script>
        function buscarEndereco() {
            var cep = document.getElementById('cep').value.replace(/\D/g, '');
        
            if (cep.length !== 8) {
                alert('CEP inválido!');
                return;
            }
        
            fetch('https://viacep.com.br/ws/' + cep + '/json/')
            .then(response => response.json())
            .then(data => {
                if (data.erro) {
                    alert('CEP não encontrado!');
                } else {
                    document.getElementById('endereco').value = data.logradouro;
                    document.getElementById('bairro').value = data.bairro;
                    document.getElementById('cidade').value = data.localidade;
                    document.getElementById('estado').value = data.uf;
                }
            })
            .catch(error => {
                console.error('Erro ao buscar o CEP:', error);
                alert('Erro ao buscar o CEP!');
            });
        }
    </script>

    <script>
        function confirmAndSubmit(event) {
          event.preventDefault(); // Impede o envio padrão do formulário
        
          var form = document.getElementById("form");
        
          if (!form.checkValidity()) {
            form.reportValidity(); // Mostra o aviso nativo do navegador
            return; // Para aqui se o formulário não for válido
          }
      
          // Se o form estiver válido, continua o SweetAlert normalmente:
          Swal.fire({
            title: 'Formulário de Moradores',
            text: 'Tem certeza que deseja cadastrar o usuário?',
            icon: 'warning',
            showDenyButton: true,
            confirmButtonText: 'CONFIRMAR',
            denyButtonText: 'CANCELAR',
            confirmButtonColor: "#2be4c6",
            denyButtonColor: "#8c52ff",
            background: "#343a40",
            color: "#a1b6c2",
            width: '420px',
            customClass: {
              title: 'swal-title',
              content: 'swal-content',
              confirmButton: 'swal-confirm-btn',
              denyButton: 'swal-deny-btn',
              htmlContainer: 'swal-text'
            }
          }).then((result) => {
            if (result.isConfirmed) {
            
              // Mostra o alerta de carregamento
              Swal.fire({
                title: 'Enviando dados...',
                text: 'Aguarde enquanto processamos o cadastro.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                  Swal.showLoading();
                },
                background: "#343a40",
                color: "#a1b6c2",
                width: '420px'
              });
          
              var formData = new FormData($("#form")[0]);
          
              $.ajax({
                url: "/insertUsuarioProc",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function (response) {
                  Swal.close(); // Fecha o loading
                
                  if (response.success) {
                    Swal.fire({
                      title: 'Sucesso!',
                      text: response.message,
                      icon: 'success',
                      width: '420px',
                      confirmButtonColor: "#2be4c6",
                      background: "#343a40",
                      color: "#a1b6c2",
                      customClass: {
                        title: 'swal-title',
                        content: 'swal-content',
                        htmlContainer: 'swal-text',
                        confirmButton: 'swal-confirm-btn'
                      }
                    }).then(() => {
                      window.location.href = "/listaMoradores";
                    });
                  } else {
                    Swal.fire({
                      title: 'Erro!',
                      text: response.message,
                      icon: 'error',
                      width: '420px',
                      confirmButtonColor: "#2be4c6",
                      background: "#1e1e1e",
                      color: "#a1b6c2",
                      customClass: {
                        title: 'swal-title',
                        content: 'swal-content',
                        htmlContainer: 'swal-text',
                        confirmButton: 'swal-confirm-btn'
                      }
                    });
                  }
                },
                error: function (xhr, status, error) {
                  Swal.close(); // Fecha o loading
                
                  Swal.fire({
                    title: 'Erro!',
                    text: 'Erro ao cadastrar o usuário: ' + error,
                    icon: 'error',
                    width: '420px',
                    confirmButtonColor: "#2be4c6",
                    background: "#343a40",
                    color: "#a1b6c2",
                    customClass: {
                      title: 'swal-title',
                      content: 'swal-content',
                      htmlContainer: 'swal-text',
                      confirmButton: 'swal-confirm-btn'
                    }
                  });
                }
              });
          
            } else if (result.dismiss === Swal.DismissReason.cancel) {
              Swal.fire({
                title: 'Cancelado',
                text: 'Nenhuma alteração foi feita.',
                icon: 'info',
                width: '420px',
                confirmButtonColor: "#8c52ff",
                background: "#343a40",
                color: "#a1b6c2"
              });
            }
          });
        }

        $(document).ready(function () {
          $("#botao").on("click", confirmAndSubmit);
        });
    </script>
   
   </body>
</html>