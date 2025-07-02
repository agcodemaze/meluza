<!DOCTYPE html>
<html lang="en">
   <head>
      
    <!-- HEAD META BASIC LOAD-->
	<?php include_once BASE_PATH . "src/headMeta.php"; ?>
	<!-- HEAD META BASIC LOAD -->

   </head>
   <body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
      <div class="app-wrapper">
         <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
               <ul class="navbar-nav">
                  <li class="nav-item"> <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"> <i class="bi bi-list"></i> </a> </li>
                  <li class="nav-item d-none d-md-block"> <a href="#" class="nav-link">Home</a> </li>
                  <li class="nav-item d-none d-md-block"> <a href="#" class="nav-link">Inicial</a> </li>
               </ul>
               <ul class="navbar-nav ms-auto">
                  <li class="nav-item dropdown user-menu">
                     <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"> <img src="../../assets/img/avatar.jpg" class="user-image rounded-circle shadow" alt="User Image"> <span class="d-none d-md-inline">Terezinha de Jesus</span> </a> 
                     <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                        <li class="user-header text-bg-primary">
                           <img src="../../assets/img/avatar.jpg" class="rounded-circle shadow" alt="User Image"> 
                           <p>
                              Terezinha de Jesus
                              <small>Membro desde Nov. 2023</small> 
                           </p>
                        </li>
                        <li class="user-footer"> 
                           <a href="#" class="btn btn-default btn-flat float-end">Sair</a> 
                        </li>
                     </ul>
                  </li>
               </ul>
            </div>
         </nav>
         <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <div class="sidebar-brand">
               <a href="./index.html" class="brand-link">
                  <img src="/assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image opacity-75 shadow"> <!--end::Brand Image--> <!--begin::Brand Text--> <span class="brand-text fw-light">AdminLTE 4</span> <!--end::Brand Text--> 
               </a>
            </div>
            <div class="sidebar-wrapper">
               <nav class="mt-2">
                  <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation" data-accordion="false" id="navigation">
                     <li class="nav-header">NAVEGAÇÃO</li>
                     <li class="nav-item">
                        <a href="#" class="nav-link">
                           <i class="nav-icon mdi mdi-home"></i>
                           <p>Home</p>
                        </a>
                     </li>
                     <li class="nav-item">
                        <a href="#" class="nav-link">
                           <i class="nav-icon mdi mdi-account-multiple"></i>
                           <p>Clientes</p>
                        </a>
                     </li>
                     <li class="nav-item">
                        <a href="#" class="nav-link">
                           <i class="nav-icon mdi mdi-calendar-check"></i>
                           <p>Agenda</p>
                        </a>
                     </li>
                     <li class="nav-item">
                        <a href="#" class="nav-link">
                           <i class="nav-icon mdi mdi-file-chart"></i>
                           <p>Relatório</p>
                        </a>
                     </li>
                  </ul>
               </nav>
            </div>
         </aside>
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
         <footer class="app-footer">
            <strong>
            Meluza &copy; 2014-2025&nbsp;
            <a href="https://codemaze.com.br" class="text-decoration-none">Codemaze</a>.
         </footer>
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
   <!--end::Body-->
</html>