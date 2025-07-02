<?php
require BASE_PATH . "src/auth.php"; 
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

                     aaa
               
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
   
   </body>
</html>