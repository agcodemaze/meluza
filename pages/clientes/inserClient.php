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
                        <h3 class="mb-0">Página</h3>
                     </div>
                     <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item active" aria-current="page">
                              Página
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
                                <h4 class="page-title">Cadastro de Cliente</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Novo Usuário</h4>
                                    <p class="text-muted font-14">Nesta seção você pode cadastrar um novo usuário para ter acesso ao sistema.
                                    </p>

                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="tooltips-validation-preview">
                                        <form id="form" name="form" role="form" method="POST" enctype="multipart/form-data">
  
                                            <div class="position-relative mb-3">
                                              <label class="form-label" for="email_user">E-mail</label>
                                              <input id="email_user" name="email_user" pattern="^[^\s@]+@[^\s@]+\.[^\s@]+$" autocomplete="off" spellcheck="false" style="text-transform: uppercase;" type="email" class="form-control" placeholder="" maxlength="50" oninput="this.value = this.value.replace(/[^A-Za-z0-9._@-]/g, '')" required />
                                            </div>                                          

                                            <div class="position-relative mb-3" id="campo-nome">
                                              <label class="form-label" for="nome">Nome Completo</label>
                                              <input id="nome" name="nome" autocomplete="new-nome" style="text-transform: uppercase;" type="text" class="form-control" placeholder="" maxlength="50"  oninput="this.value = this.value.replace(/[^\p{L} ]/gu, '')" required/>
                                            </div>

                                            <div class="position-relative mb-3" id="campo-data-nascimento">
                                              <label class="form-label" for="dataNascimento">Data de Nascimento</label>
                                              <input id="dataNascimento" name="dataNascimento" type="date" class="form-control" required/>
                                            </div>

                                            <div class="position-relative mb-3" id="campo-sexo">
                                              <label class="form-label" for="sexo">Sexo</label>
                                              <select id="sexo" name="sexo" class="form-control" required>
                                                <option value="">SELECIONE...</option>
                                                <option value="MASCULINO">MASCULINO</option>
                                                <option value="FEMININO">FEMININO</option>
                                                <option value="OUTRO">OUTRO</option>
                                              </select>
                                            </div>

                                            <div class="position-relative mb-3">
                                              <label class="form-label" for="bloco">Bloco</label>
                                              <input id="bloco" name="bloco" type="text" class="form-control" placeholder="" maxlength="2" required oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '').replace(/^0+/, '');" <?php if(isset($readonly)){echo $readonly;} ?>/>
                                            </div>

                                            <div class="position-relative mb-3">
                                              <label class="form-label" for="apartamento">Apartamento</label>
                                              <input id="apartamento" name="apartamento" type="text" class="form-control" placeholder="" maxlength="4" oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '').replace(/^0+/, '');" required <?php if(isset($readonly)){echo $readonly;} ?>/>
                                            </div> 

                                            <div class="position-relative mb-3" id="campo-telefone">
                                              <label class="form-label" for="telefone">DDD + Telefone (Whatsapp)</label>
                                              <input id="telefone" name="telefone" autocomplete="new-telefone" type="text" class="form-control" placeholder="Ex.: 11982734359" pattern="^\d{11}$" minlength="11" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required />
                                            </div> 

                                            <div class="position-relative mb-3" id="campo-senha">
                                              <label class="form-label" for="senha_user">Senha (mínimo 8 dígitos e ao menos uma letra maiúscula)</label>
                                              <input id="senha_user" name="senha_user" autocomplete="new-password" type="password" class="form-control" placeholder="" minlength="8" maxlength="12" pattern="^(?=.*[A-Z])(?=.*\d).{8,12}$" title="A senha deve ter no mínimo 8 caracteres e conter pelo menos uma letra maiúscula e um número." required />
                                            </div>

                                            <div class="position-relative mb-3" id="campo-foto">
                                              <label class="form-label" for="foto">Foto</label>
                                              <input id="foto" name="foto" type="file" class="form-control"  accept=".jpg,.jpeg,.png,.gif" />
                                            </div> 


                                              <h6 class="font-15 mt-3">Nível de Acesso</h6>
                                              <div class="mt-2">                                                      
                                                <div class="form-check form-check-inline form-radio-info mb-2">
                                                  <input value="INQUILINO" type="radio" id="funcao1" name="funcao" class="form-check-input" checked required>
                                                  <label class="form-check-label" for="funcao1">Inquilino</label>
                                                </div>
                                                <div class="form-check form-check-inline form-radio-danger mb-2">
                                                  <input value="PROPRIETARIO/MORADOR" type="radio" id="funcao2" name="funcao" class="form-check-input">
                                                  <label class="form-check-label" for="funcao2">Proprietário/Morador</label>
                                                </div> 
                                                <div class="form-check form-check-inline form-radio-danger mb-2">
                                                  <input value="PROPRIETARIO" type="radio" id="funcao3" name="funcao" class="form-check-input">
                                                  <label class="form-check-label" for="funcao3">Proprietário</label>
                                                </div> 
                                                <div class="form-check form-check-inline form-radio-danger mb-2">
                                                  <input value="CONSELHEIRO/MORADOR" type="radio" id="funcao4" name="funcao" class="form-check-input">
                                                  <label class="form-check-label" for="funcao4">Conselheiro/Morador</label>
                                                </div> 

                                                
                                                <div class="form-check form-check-inline form-radio-danger mb-2">
                                                  <input value="PARCEIRO" type="radio" id="funcao5" name="funcao" class="form-check-input">
                                                  <label class="form-check-label" for="funcao5">Parceiro Comercial</label>
                                                </div> 


                                              </div>

                                            
                                            <br>
                                            
                                            <button class="btn" style="background-color: #8c52ff; color: white;" onclick="window.history.back()" type="button">Voltar</button>         
                                            <button class="btn" style="background-color: #2be4c6; color: black;" type="submit" id="botao" name="botao">Salvar</button>                                            
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