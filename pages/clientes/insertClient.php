<?php
//require BASE_PATH . "src/auth.php"; 
include_once BASE_PATH . "objects/objects.php";

$siteAdmin = new SITE_ADMIN(); 

?>

<!DOCTYPE html>
<html lang="en">
   <head>

	<?php include_once BASE_PATH . "src/head.php"; ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

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
                                                  <label class="form-label" for="telefone">Telefone</label>
                                                  <input type="text" id="telefone" name="telefone" class="form-control" placeholder="(00) 00000-0000">
                                                </div> 

                                                <!-- Campos de endereço -->
                                                <div class="position-relative mb-3">
                                                    <label for="cep" class="form-label">CEP</label>
                                                    <input type="text" id="cep" name="cep" class="form-control" placeholder="Digite o CEP" maxlength="9" onblur="buscarEndereco()">
                                                </div>

                                                <div class="position-relative mb-3">
                                                    <label for="endereco" class="form-label">Endereço</label>
                                                    <input type="text" id="endereco" name="endereco" class="form-control" style="text-transform: uppercase;  background-color: #e9ecef; pointer-events: none; opacity: 1;" placeholder="" readonly>
                                                </div>

                                                <div class="position-relative position-relative mb-3">
                                                    <label for="numero" class="form-label">Número</label>
                                                    <input type="text" id="numero" name="numero" class="form-control" style="text-transform: uppercase;" placeholder="Digite o número" required>
                                                </div>

                                                <div class="position-relative mb-3" id="campo-complemento">
                                                  <label class="form-label" for="Complemento">Complemento</label>
                                                  <input id="Complemento" name="Complemento" autocomplete="new-Complemento" style="text-transform: uppercase;" type="text" class="form-control" placeholder="" maxlength="20"/>
                                                </div>

                                                <div class="position-relative mb-3">
                                                    <label for="bairro" class="form-label">Bairro</label>
                                                    <input type="text" id="bairro" name="bairro" class="form-control" style="text-transform: uppercase;  background-color: #e9ecef; pointer-events: none; opacity: 1;" placeholder="" readonly>
                                                </div>

                                                <div class="position-relative mb-3">
                                                    <label for="cidade" class="form-label">Cidade</label>
                                                    <input type="text" id="cidade" name="cidade" class="form-control" style="text-transform: uppercase;  background-color: #e9ecef; pointer-events: none; opacity: 1;" placeholder="" readonly>
                                                </div>

                                                <div class="position-relative mb-3">
                                                    <label for="estado" class="form-label">Estado</label>
                                                    <input type="text" id="estado" name="estado" class="form-control" style="text-transform: uppercase;  background-color: #e9ecef; pointer-events: none; opacity: 1;" placeholder="" readonly>
                                                </div>

                                                <div class="position-relative mb-3">
                                                  <label for="observacao" class="form-label">Observações</label>
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
        $(document).ready(function() {
          var telefoneMask = function(val) {
            return '(00) 00000-0000';
          };
      
          var telefoneOptions = {
            onKeyPress: function(val, e, field, options) {
              field.mask(telefoneMask.apply({}, arguments), options);
            }
          };
      
          $('#telefone').mask(telefoneMask, telefoneOptions);
        });
    </script>

    <script>
        $(document).ready(function() {
          $('#cep').mask('00000-000');
        });
    </script>

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
      event.preventDefault(); 
    
      var form = document.getElementById("form");    
    
      Swal.fire({
        title: 'Formulário de Clientes',
        text: 'Tem certeza que deseja cadastrar o cliente?',
        icon: 'warning',
        showDenyButton: true,
        confirmButtonText: 'CONFIRMAR',
        denyButtonText: 'CANCELAR',
        confirmButtonColor: "#4caf50",  // verde
        denyButtonColor: "#9e9e9e",     // cinza
        background: "#f9f9fb",          // fundo claro
        color: "#333",                  // texto escuro
        width: '420px'
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
            background: "#f9f9fb",
            color: "#333",
            width: '420px'
          });
      
          var formData = new FormData($("#form")[0]);
      
          $.ajax({
            url: "/insertClientProc",
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
                  confirmButtonColor: "#4caf50",
                  background: "#f9f9fb",
                  color: "#333"
                }).then(() => {
                  window.location.href = "/clientes";
                });
              } else {
                Swal.fire({
                  title: 'Erro!',
                  text: response.message,
                  icon: 'error',
                  width: '420px',
                  confirmButtonColor: "#f44336",
                  background: "#f9f9fb",
                  color: "#333"
                });
              }
            },
            error: function (xhr, status, error) {
              Swal.close(); // Fecha o loading
            
              Swal.fire({
                title: 'Erro!',
                text: 'Erro ao cadastrar o cliente: ' + error,
                icon: 'error',
                width: '420px',
                confirmButtonColor: "#f44336",
                background: "#f9f9fb",
                color: "#333"
              });
            }
          });
      
        } else if (result.dismiss === Swal.DismissReason.cancel || result.isDenied) {
          Swal.fire({
            title: 'Cancelado',
            text: 'Nenhuma alteração foi feita.',
            icon: 'info',
            width: '420px',
            confirmButtonColor: "#9e9e9e",
            background: "#f9f9fb",
            color: "#333"
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