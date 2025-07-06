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
    /* Destaques no calendário */
    .highlight {
        background-color: rgb(30, 35, 41) !important; 
        border-radius: 50% !important; 
        color: rgb(228, 194, 43) !important; 
    }

    .calendar-widget td.highlight {
        background-color: rgb(30, 35, 41) !important;
        border-radius: 50% !important;
        color: rgb(228, 154, 43) !important;
    }

    /* Destaque personalizado para datepicker */
    .datepicker table tr td.highlight {
        position: relative;
        color: white !important;
        font-weight: bold;
        z-index: 1;
    }

    .datepicker table tr td.highlight::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        width: 32px;
        height: 32px;
        background-color: #007bff;
        border-radius: 50%;
        transform: translate(-50%, -50%);
        z-index: -1;
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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="header-title">Agenda</h4>
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false"> </a>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-md-7">
                                <div id="calendario" class="calendar-widget"></div>
                            </div>
                            <!-- end col-->
                            <div class="col-md-5">
                                <h5 style="color: #2be4c6;">Faxinas Para Hoje</h5>
                                <div style="max-height: 300px; overflow-y: auto;">
                                    <ul class="list-unstyled mt-1">
                                        <li
                                            class="mb-4 border rounded p-3"
                                            style="cursor: pointer;"
                                        >
                                            <p class="text-muted mb-1 font-13">
                                                <i class="mdi mdi-calendar"></i>
                                                00:00:00
                                            </p>
                                            <h4 class="mb-1">Faxina cliente 1</h4>
                                            <span class="font-13">
                                                descrição
                                            </span>
                                        </li>                                   
                                    </ul>
                                </div>
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
                $.fn.dataTable.moment('DD/MM/YYYY HH:mm:ss');
                $.fn.dataTable.moment('DD/MM/YYYY');

                $('#tabela-os').DataTable({
                    "language": {
                        "url": "../../assets/js/pt-BR.json"
                    },
                    "pageLength": 20,
                    "ordering": true,
                    "searching": false,
                    "lengthChange": false,
                    "order": [[2, "asc"]] 
                });
            });
        </script>

	   <?php include_once BASE_PATH . "src/config.php"; ?>

       
   
   </body>
</html>