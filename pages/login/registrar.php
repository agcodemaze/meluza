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
   <body class="register-page bg-light">
      <div class="register-box">
         <div class="register-logo"> <a href="#"><img src="/assets/img/meluza_logo_90.png" alt="Meluza" width="200" style="height:auto;"></a></div>
         <!-- /.register-logo --> 
         <div class="card">
            <div class="card-body register-card-body">
               <p class="register-box-msg">Registrar-se</p>
               <form id="form" name="form" role="form" method="POST" enctype="multipart/form-data">
                  <div class="input-group mb-3">
                     <input id="nome" name="nome" type="text" class="form-control" placeholder="Nome Completo"> 
                     <div class="input-group-text"> <span class="bi bi-person"></span> </div>
                  </div>
                  <div class="input-group mb-3">
                     <input id="email_user" name="email_user" type="email" class="form-control" placeholder="E-mail"> 
                     <div class="input-group-text"> <span class="bi bi-envelope"></span> </div>
                  </div>
                  <div class="input-group mb-3">
                     <input id="senha_user" name="senha_user" type="password" class="form-control" placeholder="Senha"> 
                     <div class="input-group-text"> <span class="bi bi-lock-fill"></span> </div>
                  </div>
                  <!--begin::Row--> 
                  <div class="row">
                     <div class="col-8">
                        <div class="form-check"> <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"> <label class="form-check-label" for="flexCheckDefault">
                           Eu aceito os <a href="#">termos</a> </label> 
                        </div>
                     </div>
                     <!-- /.col --> 
                     <div class="col-4">
                        <div class="d-grid gap-2"> <button id="botao" name="botao" type="submit" class="btn btn-success">Cadastra-se</button> </div>
                     </div>
                     <!-- /.col --> 
                  </div>
                  <!--end::Row--> 
               </form>
               <!-- /.social-auth-links --> 
               <p class="mb-0"> <a href="login.html" class="text-center">
                  Eu já sou cadastrado(a)
                  </a> 
               </p>
            </div>
            <!-- /.register-card-body --> 
         </div>
      </div>

      <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>


  <script>
    function confirmAndSubmit(event) {
      event.preventDefault(); // Impede o envio padrão do formulário
    
      var form = document.getElementById("form");
       
      // Se o form estiver válido, continua o SweetAlert normalmente:
      Swal.fire({
        title: 'Formulário de Usuários',
        text: 'Tem certeza que deseja cadastrar-se?',
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
            url: "/registrarProc",
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
                  //window.location.href = "/login";
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

      <script src="../../js/overlayscrollbars.browser.es6.min.js"></script>
      <script src="../../js/popper.min.js"></script>
      <script src="../../js/bootstrap.min.js"></script>
      <script src="../../js/adminlte.js"></script>

	   <?php include_once BASE_PATH . "src/config.php"; ?>
   
   </body>
</html>