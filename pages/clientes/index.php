<?php
//require BASE_PATH . "src/auth.php"; 
include_once BASE_PATH . "objects/objects.php";

$siteAdmin = new SITE_ADMIN(); 

?>

<!DOCTYPE html>
<html lang="en">
   <head>

	<?php include_once BASE_PATH . "src/head.php"; ?>
        <script src="../../js/jquery-3.6.0.min.js"></script>
    <link href="../../js/rateit.css" rel="stylesheet">

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
                           <li class="breadcrumb-item"><a href="#">Inicial</a></li>
                           <li class="breadcrumb-item active" aria-current="page">
                              Clientes
                           </li>
                        </ol>
                     </div>
                  </div>
               </div>
            </div>
            <div class="app-content">
                <div class="container-fluid">

                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">Clientes Cadastrados</h3>
                            <div class="card-tools">
                                <ul class="pagination pagination-sm float-end">
                                    <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                                </ul>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="width: 10px;">#</th>
                                        <th>Nome</th>
                                        <th>Telefone</th>
                                        <th style="width: 40px;">Label</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="align-middle">
                                        <td>1.</td>
                                        <td>Update software</td>
                                        <td>
                                        <div 
                                            class="rateit rateit-mdi" 
                                            data-rateit-mode="font"
                                            data-rateit-icon="󰓒"
                                            data-rateit-value="5" 
                                            data-rateit-ispreset="true" 
                                            data-rateit-resetable="false"
                                            data-id="1" 
                                        ></div>
                                        </td>
                                        <td><span class="badge text-bg-danger">55%</span></td>
                                    </tr>                                    
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>               
                </div>
            </div>
         </main>

	      <?php include_once BASE_PATH . "src/footer.php"; ?>
      </div>

    <script>
        $(document).ready(function() {
            $('.rateit').on('rated', function(event) {
                var rating = $(this).rateit('value');
                var id = $(this).data('id');
                $.ajax({
                    url: '/insertFornecedorRateProc',
                    type: 'POST',
                    data: {
                        id: id,
                        nota: rating
                    },
                    success: function(response) {
                        window.location.href = '/fornecedores';
                    },
                    error: function() {                    
                    }
                });
            });
        });
    </script>
    <script src="../../js/overlayscrollbars.browser.es6.min.js"></script>
    <script src="../../js/popper.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/adminlte.js"></script>
    <script src="../../vendor/jquery.rateit/scripts/jquery.rateit.min.js"></script>
	<?php include_once BASE_PATH . "src/config.php"; ?>
   
   </body>
</html>