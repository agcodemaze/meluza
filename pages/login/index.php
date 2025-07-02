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
            <div class="card-body login-card-body">
               <p class="login-box-msg">Login</p>
               <form action="../index3.html" method="post">
                  <div class="input-group mb-3">
                     <input type="email" class="form-control" placeholder="E-mail"> 
                     <div class="input-group-text"> <span class="bi bi-envelope"></span> </div>
                  </div>
                  <div class="input-group mb-3">
                     <input type="password" class="form-control" placeholder="Senha"> 
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
               <div class="social-auth-links text-center mb-3 d-grid gap-2">
                  <p>- OR -</p>
                  <a href="#" class="btn btn-primary"> <i class="bi bi-facebook me-2"></i> Entre usando o Facebook
                  </a> <a href="#" class="btn btn-danger"> <i class="bi bi-google me-2"></i> Entre usando o Google+
                  </a> 
               </div>
               <!-- /.social-auth-links --> 
               <p class="mb-1"> <a href="forgot-password.html">Esqueci minha senha</a> </p>
               <p class="mb-0"> <a href="register.html" class="text-center">
                  Registrar-se
                  </a> 
               </p>
            </div>
            <!-- /.login-card-body --> 
         </div>
      </div>

      <script src="../../js/overlayscrollbars.browser.es6.min.js"></script>
      <script src="../../js/popper.min.js"></script>
      <script src="../../js/bootstrap.min.js"></script>
      <script src="../../js/adminlte.js"></script>

	   <?php include_once BASE_PATH . "src/config.php"; ?>
   
   </body>
</html>