<?php
//require BASE_PATH . "src/auth.php"; 
include_once BASE_PATH . "objects/objects.php";

$siteAdmin = new SITE_ADMIN(); 

?>

<!DOCTYPE html>
<html lang="en">
   <head>

	<?php include_once BASE_PATH . "src/head.php"; ?>


    <link href="../../vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.pt-BR.min.js"></script>

   </head>

    <style>
        #calendario .datepicker {
            width: 100% !important;
        }

        #calendario {
            width: 100%;
            max-width: 100%;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .datepicker-inline {
        width: 100% !important;
        }

        .datepicker table {
            width: 100% !important;
            table-layout: fixed;
            font-size: 1.2rem;
        }

        .datepicker td, .datepicker th {
            width: 14.28% !important; /* 100 / 7 dias */
            text-align: center;
            padding: 1rem;
        }
    </style>

   <body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
      <div class="app-wrapper">
                  
	      <?php include_once BASE_PATH . "src/topbar.php"; ?>
	      <?php include_once BASE_PATH . "src/menu.php"; ?>

         <main class="app-main">
            <div class="app-content-header">
               <div class="container-fluid">
                  <div class="row">
                     <div class="col-sm-6">
                        <h3 class="mb-0">Agenda</h3>
                     </div>
                     <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                           <li class="breadcrumb-item"><a href="/inicial">Inicial</a></li>
                           <li class="breadcrumb-item active" aria-current="page">
                              
                           </li>
                        </ol>
                     </div>
                  </div>
               </div>
            </div>
            <div class="app-content">
                <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon text-bg-primary shadow-sm"> <i class="bi bi-gear-fill"></i> </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Serviços Agendados</span>
                                        <span class="info-box-number">
                                            10
                                            <small></small>
                                        </span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <!-- /.col -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon text-bg-danger shadow-sm"> <i class="bi bi-hand-thumbs-up-fill"></i> </span>
                                    <div class="info-box-content"><span class="info-box-text">Serviços Concluídos</span> <span class="info-box-number">41,410</span></div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <!-- /.col -->
                            <!-- fix for small devices only -->
                            <!-- <div class="clearfix hidden-md-up"></div> -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon text-bg-success shadow-sm"> <i class="bi bi-cart-fill"></i> </span>
                                    <div class="info-box-content"><span class="info-box-text">Ganhos Acumulados</span> <span class="info-box-number">760</span></div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <!-- /.col -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon text-bg-warning shadow-sm"> <i class="bi bi-people-fill"></i> </span>
                                    <div class="info-box-content"><span class="info-box-text">Clientes Cadastrados</span> <span class="info-box-number">2,000</span></div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-12">
                                <div id="calendario" class="calendar-widget"></div>
                            </div>
                        </div>
                        <!-- end row -->
                    </div>
                    <!-- end card body-->

                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-12">
                               <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Recently Added Products</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse"><i data-lte-icon="expand" class="bi bi-plus-lg"></i> <i data-lte-icon="collapse" class="bi bi-dash-lg"></i></button>
                                            <button type="button" class="btn btn-tool" data-lte-toggle="card-remove"><i class="bi bi-x-lg"></i></button>
                                        </div>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body p-0">
                                        <div class="px-2">
                                            <div class="d-flex border-top py-2 px-1">
                                                <div class="col-2"><img src="/assets/img/default-150x150.png" alt="Product Image" class="img-size-50" /></div>
                                                <div class="col-10">
                                                    <a href="javascript:void(0)" class="fw-bold">
                                                        Samsung TV
                                                        <span class="badge text-bg-warning float-end">
                                                            $1800
                                                        </span>
                                                    </a>
                                                    <div class="text-truncate">
                                                        Samsung 32" 1080p 60Hz LED Smart HDTV.
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.item -->
                                            <div class="d-flex border-top py-2 px-1">
                                                <div class="col-2"><img src="/assets/img/default-150x150.png" alt="Product Image" class="img-size-50" /></div>
                                                <div class="col-10">
                                                    <a href="javascript:void(0)" class="fw-bold">
                                                        Bicycle
                                                        <span class="badge text-bg-info float-end">
                                                            $700
                                                        </span>
                                                    </a>
                                                    <div class="text-truncate">
                                                        26" Mongoose Dolomite Men's 7-speed, Navy Blue.
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.item -->
                                            <div class="d-flex border-top py-2 px-1">
                                                <div class="col-2"><img src="/assets/img/default-150x150.png" alt="Product Image" class="img-size-50" /></div>
                                                <div class="col-10">
                                                    <a href="javascript:void(0)" class="fw-bold">
                                                        Xbox One
                                                        <span class="badge text-bg-danger float-end">
                                                            $350
                                                        </span>
                                                    </a>
                                                    <div class="text-truncate">
                                                        Xbox One Console Bundle with Halo Master Chief Collection.
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.item -->
                                            <div class="d-flex border-top py-2 px-1">
                                                <div class="col-2"><img src="/assets/img/default-150x150.png" alt="Product Image" class="img-size-50" /></div>
                                                <div class="col-10">
                                                    <a href="javascript:void(0)" class="fw-bold">
                                                        PlayStation 4
                                                        <span class="badge text-bg-success float-end">
                                                            $399
                                                        </span>
                                                    </a>
                                                    <div class="text-truncate">
                                                        PlayStation 4 500GB Console (PS4)
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.item -->
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer text-center">
                                        <a href="javascript:void(0)" class="uppercase">
                                            View All Products
                                        </a>
                                    </div>
                                    <!-- /.card-footer -->
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>
                        <!-- end row -->
                    </div>
                    <!-- end card body-->
                </div>
            </div>

         </main>

	      <?php include_once BASE_PATH . "src/footer.php"; ?>

      </div>
        <script src="../../js/overlayscrollbars.browser.es6.min.js"></script>
        <script src="../../js/popper.min.js"></script>
        <script src="../../js/bootstrap.min.js"></script>
        <script src="../../js/adminlte.js"></script>
        <script src="https://cdn.datatables.net/plug-ins/1.13.4/sorting/datetime-moment.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script>
    $(document).ready(function () {
        $('#calendario').datepicker({
            format: "dd/mm/yyyy",
            language: "pt-BR",
            todayHighlight: true
        });
    });
</script>

	   <?php include_once BASE_PATH . "src/config.php"; ?>

       
   
   </body>
</html>