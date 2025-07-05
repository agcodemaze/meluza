<?php
require BASE_PATH . "src/auth.php"; 
include_once BASE_PATH . "objects/objects.php";

$siteAdmin = new SITE_ADMIN(); 

$clientes = $siteAdmin->getClienteInfo(USER_ID);

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
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <a href="/cliente" class="btn btn-sm" style="background-color: #00c1fb; color: black; font-weight: bold;">
                                + Novo Cliente
                            </a>
                            <div class="card-tools">
                                <ul class="pagination pagination-sm float-end mb-0">
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
                                        <th style="width: 50px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $contador = 1; ?>
                                <?php foreach ($clientes as $cliente): ?>

                                    <?php
                                        $uberLink = "";
                                        $uberRua     = $cliente['CLI_DCENDERECO'];
                                        $uberNumero  = $cliente['CLI_DCNUM_ENDERECO'];
                                        $uberCidade  = $cliente['CLI_DCCIDADE'];
                                        $uberEstado  = $cliente['CLI_DCESTADO'];
                                        $uberBairro  = $cliente['CLI_DCBAIRRO']; // corrigido

                                        $endereco = "Rua dos Estudantes, 505, Hortolândia, SP";
                                        $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($endereco);
                                        var_dump($url);                                
                                        $response = file_get_contents($url);
                                        $data = json_decode($response, true);
                                                                        
                                        if (!empty($data)) {
                                            $latitude = $data[0]['lat'];
                                            $longitude = $data[0]['lon'];

                                            $uberLink = "https://m.uber.com/ul/?action=setPickup&dropoff[latitude]=$lat&dropoff[longitude]=$lng&dropoff[nickname]=Cliente";
                                        }

                                        $telefone = $cliente['CLI_DCTELEFONE'];
                                        $mensagem = "Olá, tudo bem?";                                                                        
                                        $linkWhatsapp = "https://wa.me/55{$telefone}?text=" . rawurlencode($mensagem);
                            
                                    ?>

                                    <tr class="align-middle">
                                        <td><?= $contador++; ?></td>
                                        <td>
                                            <?= htmlspecialchars(
                                                mb_strlen($cliente['CLI_DCNOME'], 'UTF-8') > 20 
                                                ? mb_substr(mb_convert_case($cliente['CLI_DCNOME'], MB_CASE_TITLE, 'UTF-8'), 0, 20, 'UTF-8') . '...' 
                                                : mb_convert_case($cliente['CLI_DCNOME'], MB_CASE_TITLE, 'UTF-8'), 
                                                ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'
                                            ) ?>
                                        </td>
                                        <td>
                                            <div style="display: flex; gap: 8px; align-items: center;">
                                                <a href="<?php echo $linkWhatsapp; ?>" target="_blank"><i class="mdi mdi-whatsapp" style="font-size: 28px; color: #25D366;" title="WhatsApp"></i></a>
                                                <a href="<?php echo $uberLink; ?>" target="_blank"><i class="mdi mdi-car" style="font-size: 28px; color: #000000;" title="Ir com Uber"></i></a>
                                                <a href="javascript:void(0);" 
                                                   class="action-icon" 
                                                   data-id="<?= htmlspecialchars($cliente['CLI_IDCLIENTE']); ?>" 
                                                   data-foto="foto" 
                                                   onclick="confirmDeleteAttr(this)">
                                                   <i class="mdi mdi-delete" style="font-size: 28px; color:rgb(235, 73, 73);" title="Excluir Cliente"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
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
        function confirmDeleteAttr(element) {
            const id = element.getAttribute('data-id');
            const fileName = element.getAttribute('data-foto');
            confirmDelete(id, fileName);
        }

        function confirmDelete(id, fileName) {
            Swal.fire({
                title: 'Lista de Clientes',
                text: "Tem certeza que deseja excluir o cliente?",
                icon: 'warning',
                showDenyButton: true,
                confirmButtonText: 'CONFIRMAR',
                denyButtonText: 'CANCELAR',
                confirmButtonColor: "#4caf50",   
                denyButtonColor: "#9e9e9e",      
                background: "#f9f9fb",           
                color: "#333",                   
                width: '420px'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Aguarde...',
                        text: 'Excluindo o cliente...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        background: "#f9f9fb",
                        color: "#333",
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                
                    $.ajax({
                        url: "/deleteFornecedorProc",
                        type: "POST",
                        data: { id: id, filename: fileName },
                        dataType: "json",
                        success: function (jsonResponse) {
                            if (jsonResponse.success) {
                                Swal.fire({
                                    title: 'Sucesso!',
                                    text: jsonResponse.message,
                                    icon: 'success',
                                    width: '420px',
                                    confirmButtonColor: "#4caf50",
                                    background: "#f9f9fb",
                                    color: "#333"
                                }).then(() => {
                                    window.location.href = "/fornecedores";
                                });
                            } else {
                                Swal.fire({
                                    title: 'Erro!',
                                    text: jsonResponse.message || 'Erro ao excluir o cliente.',
                                    icon: 'error',
                                    width: '420px',
                                    confirmButtonColor: "#f44336",  
                                    background: "#f9f9fb",
                                    color: "#333"
                                });
                            }
                        },
                        error: function () {
                            Swal.fire({
                                title: 'Erro!',
                                text: 'Erro ao excluir o cliente.',
                                icon: 'error',
                                width: '420px',
                                confirmButtonColor: "#f44336",
                                background: "#f9f9fb",
                                color: "#333"
                            });
                        }
                    });
                } else if (result.isDenied) {
                    Swal.fire({
                        title: 'Cancelado',
                        text: 'Nenhuma alteração foi feita.',
                        icon: 'info',
                        width: '420px',
                        confirmButtonColor: "#9e9e9e",
                        background: "#f9f9fb",
                        color: "#333"
                    });
                }
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