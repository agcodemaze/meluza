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
   <body class="register-page bg-body-secondary">
      <div class="register-box">
         <div class="register-logo"> <a href="#"><img src="/assets/img/meluza_logo_90.png" alt="Meluza" class="brand-image opacity-75 shadow"></div>
         <!-- /.register-logo --> 
         <div class="card">
            <div class="card-body register-card-body">
               <p class="register-box-msg">Registrar-se</p>
               <form action="../index3.html" method="post">
                  <div class="input-group mb-3">
                     <input id="nome" type="text" class="form-control" placeholder="Nome Completo"> 
                     <div class="input-group-text"> <span class="bi bi-person"></span> </div>
                  </div>
                  <div class="input-group mb-3">
                     <input id="email" type="email" class="form-control" placeholder="E-mail"> 
                     <div class="input-group-text"> <span class="bi bi-envelope"></span> </div>
                  </div>
                  <div class="input-group mb-3">
                     <input id="senha" type="password" class="form-control" placeholder="Senha"> 
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
                        <div class="d-grid gap-2"> <button type="submit" class="btn btn-primary">Entrar</button> </div>
                     </div>
                     <!-- /.col --> 
                  </div>
                  <!--end::Row--> 
               </form>
               <div class="social-auth-links text-center mb-3 d-grid gap-2">
                  <p>- OU -</p>
                  <a href="#" class="btn btn-primary"> <i class="bi bi-facebook me-2"></i> Entre usando o Facebook
                  </a> <a href="#" class="btn btn-danger"> <i class="bi bi-google me-2"></i> Entre usando o Google+
                  </a> 
               </div>
               <!-- /.social-auth-links --> 
               <p class="mb-0"> <a href="login.html" class="text-center">
                  Eu já sou cadastrado(a)
                  </a> 
               </p>
            </div>
            <!-- /.register-card-body --> 
         </div>
      </div>

      <script src="../../js/overlayscrollbars.browser.es6.min.js"></script>
      <script src="../../js/popper.min.js"></script>
      <script src="../../js/bootstrap.min.js"></script>
      <script src="../../js/adminlte.js"></script>

	   <?php include_once BASE_PATH . "src/config.php"; ?>
   
   </body>
</html>