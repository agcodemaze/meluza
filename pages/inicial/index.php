<!DOCTYPE html>
<html lang="en">
   <head>

   <!-- HEAD META BASIC LOAD-->
	<?php include_once BASE_PATH . "src/head.php"; ?>
	<!-- HEAD META BASIC LOAD -->

   </head>
   <body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
      <div class="app-wrapper">
                  
         <!-- HEAD META BASIC LOAD-->
	      <?php include_once BASE_PATH . "src/topbar.php"; ?>
	      <!-- HEAD META BASIC LOAD -->

         <!-- HEAD META BASIC LOAD-->
	      <?php include_once BASE_PATH . "src/menu.php"; ?>
	      <!-- HEAD META BASIC LOAD -->


         <main class="app-main">
            <div class="app-content-header">
               <div class="container-fluid">
                  <div class="row">
                     <div class="col-sm-6">
                        <h3 class="mb-0">Dashboard v2</h3>
                     </div>
                     <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                           <li class="breadcrumb-item"><a href="#">Home</a></li>
                           <li class="breadcrumb-item active" aria-current="page">
                              Dashboard v2
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

         <!-- HEAD META BASIC LOAD-->
	      <?php include_once BASE_PATH . "src/footer.php"; ?>
	      <!-- HEAD META BASIC LOAD -->

      </div>
      <script src="../../js/overlayscrollbars.browser.es6.min.js"></script>
      <script src="../../js/popper.min.js"></script>
      <script src="../../js/bootstrap.min.js"></script>
      <script src="../../js/adminlte.js"></script>

      <script>
        const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper"
        const Default = {
          scrollbarTheme: "os-theme-light",
          scrollbarAutoHide: "leave",
          scrollbarClickScroll: true
        }
        document.addEventListener("DOMContentLoaded", function () {
          const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER)
          if (
            sidebarWrapper &&
            OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined
          ) {
            OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
              scrollbars: {
                theme: Default.scrollbarTheme,
                autoHide: Default.scrollbarAutoHide,
                clickScroll: Default.scrollbarClickScroll
              }
            })
          }
        })
      </script>
   
   </body>
</html>