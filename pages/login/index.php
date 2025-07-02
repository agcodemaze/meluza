<?php
$lifetime = 60 * 60 * 24 * 7; 
session_set_cookie_params([
    'lifetime' => $lifetime,
    'path' => '/',
    'domain' => 'condomaze.com.br', 
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true, 
    'samesite' => 'Lax' 
]);

ini_set('session.gc_maxlifetime', $lifetime);
ini_set('session.cookie_lifetime', $lifetime);

session_start();

include_once BASE_PATH . "objects/objects.php";


?>

<!DOCTYPE html>
<html lang="en">
   <head>

	<?php include_once BASE_PATH . "src/head.php"; ?>

   </head>
<style>
  .register-box {
    width: 100%;
    max-width: 400px;
    margin: auto;
  }

  @media (max-width: 768px) {
    .register-box {
      width: 80vw !important; 
    }
  }
</style>
   <body class="register-page bg-light">
      <div id="splash-screen" style="position: fixed; z-index: 9999; top: 0; left: 0; width: 100%; height: 100%; background-color: #ffffff; display: flex; align-items: center; justify-content: center;">
        <img src="/assets/img/meluza_logo_90.png" alt="Meluza" style="width: 150px; height: auto;">
      </div>

      <div id="main-content" style="display:none;">
         <div class="register-box">
         <div class="register-logo"> <a href="#"><img src="/assets/img/meluza_logo_90.png" alt="Meluza" width="200" style="height:auto;"></a></div>
         <!-- /.register-logo --> 
         <div class="card">
            <div class="card-body login-card-body">
               <p class="login-box-msg">Seja bem vindo(a)</p>
               <form id="loginForm">
                  <div class="input-group mb-3">
                     <input id="email" type="email" class="form-control" placeholder="E-mail"> 
                     <div class="input-group-text"> <span class="bi bi-envelope"></span> </div>
                  </div>
                  <div class="input-group mb-3">
                     <input id="password" type="password" class="form-control" placeholder="Senha"> 
                     <div class="input-group-text"> <span class="bi bi-lock-fill"></span> </div>
                  </div>
                  <!--begin::Row--> 
                  <div class="row">
                     <div class="col-8">
                        <div class="form-check"> <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"> <label class="form-check-label" for="flexCheckDefault">
                           Manter conectado
                           </label> 
                        </div>
                     </div>
                     <!-- /.col --> 
                     <div class="col-4">
                        <div class="d-grid gap-2"> <button type="submit" class="btn" style="background-color: #7eda0d ; color: #000000;">Entrar</button> </div>
                     </div>
                     <!-- /.col --> 
                  </div>
                  <!--end::Row--> 
               </form>
               <!-- /.social-auth-links --> 
               <p class="mb-1"> <a href="forgot-password.html">Esqueci minha senha</a> </p>
               <p class="mb-0"> 
                  <a href="/registrar" class="text-center">
                  Registrar-se
                  </a> 
               </p>
            </div>
            <!-- /.login-card-body --> 
         </div>
         </div>
      </div>

      <script>
        window.addEventListener("load", function () {
          setTimeout(() => {
            document.getElementById('splash-screen').style.display = 'none';
            document.getElementById('main-content').style.display = 'block';
          }, 2000); 
        });
      </script>

                  <script>
                    document.getElementById('loginForm').addEventListener('submit', function(event) {
                        event.preventDefault();

                        const email = document.getElementById('email').value;
                        const password = document.getElementById('password').value;

                        fetch('pages/login/login.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ email, senha: password })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const userinfo = data.userinfo;                           
                                 enviarParaConciliar(userinfo);                 
                            } else {
                                alert(data.message);
                            }
                        })
                        .catch(error => console.error('Erro:', error));
                    });

                    function enviarParaConciliar(userinfo) {
                        fetch('pages/login/genJwt.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                        userinfo: userinfo
                                    })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                window.location.href = `/inicial`;
                            } else {
                                alert(data.message || 'Erro ao processar usuário.');
                            }
                        })
                        .catch(error => {
                            console.error('Erro:', error);
                            alert('Erro ao comunicar com o servidor.');
                        });
                    }
                  </script>

      <script src="../../js/overlayscrollbars.browser.es6.min.js"></script>
      <script src="../../js/popper.min.js"></script>
      <script src="../../js/bootstrap.min.js"></script>
      <script src="../../js/adminlte.js"></script>

	   <?php include_once BASE_PATH . "src/config.php"; ?>
   
   </body>
</html>