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

                  <div class="card-body">
                     <div class="row">
                            <div class="col-12">
                               <div class="card">

                                 <!-- Info Boxes Style 2 -->
                                <div class="info-box mb-3 text-bg-warning">
                                    <span class="info-box-icon"> <i class="bi bi-tag-fill"></i> </span>
                                    <div class="info-box-content"><span class="info-box-text">Inventory</span> <span class="info-box-number">5,200</span></div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                                <div class="info-box mb-3 text-bg-success">
                                    <span class="info-box-icon"> <i class="bi bi-heart-fill"></i> </span>
                                    <div class="info-box-content"><span class="info-box-text">Mentions</span> <span class="info-box-number">92,050</span></div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                                <div class="info-box mb-3 text-bg-danger">
                                    <span class="info-box-icon"> <i class="bi bi-cloud-download"></i> </span>
                                    <div class="info-box-content"><span class="info-box-text">Downloads</span> <span class="info-box-number">114,381</span></div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                                <div class="info-box mb-3 text-bg-info">
                                    <span class="info-box-icon"> <i class="bi bi-chat-fill"></i> </span>
                                    <div class="info-box-content"><span class="info-box-text">Direct Messages</span> <span class="info-box-number">163,921</span></div>
                                    <!-- /.info-box-content -->
                                </div>

                                </div>
                                <!-- /.card -->
                            </div>
                     </div>
                    <!-- end row -->
                  </div>

                  <div class="card-body">
                      <!--begin::Row-->
                      <div class="row"> 
                          <!-- /.col -->
                          <div class="col-md-4">
                              <p class="text-center"><strong>Consumo da sua Licença</strong></p>
                              <div class="progress-group">
                                  Clientes cadastrados
                                  <span class="float-end"><b>160</b>/200</span>
                                  <div class="progress progress-sm"><div class="progress-bar text-bg-primary" style="width: 80%;"></div></div>
                              </div>
                              <!-- /.progress-group -->
                              <div class="progress-group">
                                  Serviços Executados (mês)
                                  <span class="float-end"><b>5</b>/12</span>
                                  <div class="progress progress-sm"><div class="progress-bar text-bg-danger" style="width: 45%;"></div></div>
                              </div>
                          </div>
                          <!-- /.col -->
                           <div class="col-md-8">
                              <p class="text-center"><strong>Faxinas Executadas x Ganhos (últimos 12 meses)</strong></p>
                              <div id="sales-chart"></div>
                          </div>
                      </div>
                      <!--end::Row-->
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

      
   
   </body>
</html>