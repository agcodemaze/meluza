<!DOCTYPE html>
<html lang="en">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
      <title>MELUZA</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
      <meta name="color-scheme" content="light dark">
      <meta name="theme-color" content="#6f42c1" media="(prefers-color-scheme: light)">
      <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)">
      <meta name="title" content="MELUZA | Sistema para Diaristas">
      <meta name="author" content="Equipe MELUZA">
      <meta name="description" content="O MELUZA é um sistema completo para diaristas autônomas gerenciarem agendamentos, clientes e histórico de faxinas com praticidade e tecnologia.">
      <meta name="keywords" content="meluza, diarista, faxina, sistema de agendamento, diaristas autônomas, controle de faxinas, agenda, clientes, painel de diarista">
      <meta name="supported-color-schemes" content="light dark">  
      <link rel="preload" href="css/adminlte.css" as="style">
      <link rel="stylesheet" href="css/overlayscrollbars.min.css">
      <link rel="stylesheet" href="css/materialdesignicons.min.css">
      <link rel="stylesheet" href="css/adminlte.css">
   </head>
   <body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
      <div class="app-wrapper">
         <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
               <ul class="navbar-nav">
                  <li class="nav-item"> <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"> <i class="bi bi-list"></i> </a> </li>
                  <li class="nav-item d-none d-md-block"> <a href="#" class="nav-link">Home</a> </li>
                  <li class="nav-item d-none d-md-block"> <a href="#" class="nav-link">Contact</a> </li>
               </ul>
               <ul class="navbar-nav ms-auto">
                  <li class="nav-item"> <a class="nav-link" data-widget="navbar-search" href="#" role="button"> <i class="bi bi-search"></i> </a> </li>
                  <!--end::Navbar Search--> <!--begin::Messages Dropdown Menu--> 
                  <li class="nav-item dropdown">
                     <a class="nav-link" data-bs-toggle="dropdown" href="#"> <i class="bi bi-chat-text"></i> <span class="navbar-badge badge text-bg-danger">3</span> </a> 
                     <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                        <a href="#" class="dropdown-item">
                           <!--begin::Message--> 
                           <div class="d-flex">
                              <div class="flex-shrink-0"> <img src="/assets/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 rounded-circle me-3"> </div>
                              <div class="flex-grow-1">
                                 <h3 class="dropdown-item-title">
                                    Brad Diesel
                                    <span class="float-end fs-7 text-danger"><i class="bi bi-star-fill"></i></span> 
                                 </h3>
                                 <p class="fs-7">Call me whenever you can...</p>
                                 <p class="fs-7 text-secondary"> <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago</p>
                              </div>
                           </div>
                           <!--end::Message--> 
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                           <!--begin::Message--> 
                           <div class="d-flex">
                              <div class="flex-shrink-0"> <img src="/assets/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 rounded-circle me-3"> </div>
                              <div class="flex-grow-1">
                                 <h3 class="dropdown-item-title">
                                    John Pierce
                                    <span class="float-end fs-7 text-secondary"> <i class="bi bi-star-fill"></i> </span> 
                                 </h3>
                                 <p class="fs-7">I got your message bro</p>
                                 <p class="fs-7 text-secondary"> <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago</p>
                              </div>
                           </div>
                           <!--end::Message--> 
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                           <!--begin::Message--> 
                           <div class="d-flex">
                              <div class="flex-shrink-0"> <img src="/assets/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 rounded-circle me-3"> </div>
                              <div class="flex-grow-1">
                                 <h3 class="dropdown-item-title">
                                    Nora Silvester
                                    <span class="float-end fs-7 text-warning"> <i class="bi bi-star-fill"></i> </span> 
                                 </h3>
                                 <p class="fs-7">The subject goes here</p>
                                 <p class="fs-7 text-secondary"> <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago</p>
                              </div>
                           </div>
                           <!--end::Message--> 
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Messages</a> 
                     </div>
                  </li>
                  <!--end::Messages Dropdown Menu--> <!--begin::Notifications Dropdown Menu--> 
                  <li class="nav-item dropdown">
                     <a class="nav-link" data-bs-toggle="dropdown" href="#"> <i class="bi bi-bell-fill"></i> <span class="navbar-badge badge text-bg-warning">15</span> </a> 
                     <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                        <span class="dropdown-item dropdown-header">15 Notifications</span> 
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item"> <i class="bi bi-envelope me-2"></i> 4 new messages
                        <span class="float-end text-secondary fs-7">3 mins</span> </a> 
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item"> <i class="bi bi-people-fill me-2"></i> 8 friend requests
                        <span class="float-end text-secondary fs-7">12 hours</span> </a> 
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item"> <i class="bi bi-file-earmark-fill me-2"></i> 3 new reports
                        <span class="float-end text-secondary fs-7">2 days</span> </a> 
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">
                        See All Notifications
                        </a> 
                     </div>
                  </li>
                  <!--end::Notifications Dropdown Menu--> <!--begin::Fullscreen Toggle--> 
                  <li class="nav-item"> <a class="nav-link" href="#" data-lte-toggle="fullscreen"> <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i> <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none;"></i> </a> </li>
                  <!--end::Fullscreen Toggle--> <!--begin::User Menu Dropdown--> 
                  <li class="nav-item dropdown user-menu">
                     <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"> <img src="/assets/img/user2-160x160.jpg" class="user-image rounded-circle shadow" alt="User Image"> <span class="d-none d-md-inline">Alexander Pierce</span> </a> 
                     <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                        <!--begin::User Image--> 
                        <li class="user-header text-bg-primary">
                           <img src="/assets/img/user2-160x160.jpg" class="rounded-circle shadow" alt="User Image"> 
                           <p>
                              Alexander Pierce - Web Developer
                              <small>Member since Nov. 2023</small> 
                           </p>
                        </li>
                        <!--end::User Image--> <!--begin::Menu Body--> 
                        <li class="user-body">
                           <!--begin::Row--> 
                           <div class="row">
                              <div class="col-4 text-center"> <a href="#">Followers</a> </div>
                              <div class="col-4 text-center"> <a href="#">Sales</a> </div>
                              <div class="col-4 text-center"> <a href="#">Friends</a> </div>
                           </div>
                           <!--end::Row--> 
                        </li>
                        <!--end::Menu Body--> <!--begin::Menu Footer--> 
                        <li class="user-footer"> <a href="#" class="btn btn-default btn-flat">Profile</a> <a href="#" class="btn btn-default btn-flat float-end">Sign out</a> </li>
                        <!--end::Menu Footer--> 
                     </ul>
                  </li>
                  <!--end::User Menu Dropdown--> 
               </ul>
               <!--end::End Navbar Links--> 
            </div>
            <!--end::Container--> 
         </nav>
         <!--end::Header--> <!--begin::Sidebar-->
         <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <!--begin::Sidebar Brand--> 
            <div class="sidebar-brand">
               <!--begin::Brand Link--> 
               <a href="./index.html" class="brand-link">
                  <!--begin::Brand Image--> <img src="/assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image opacity-75 shadow"> <!--end::Brand Image--> <!--begin::Brand Text--> <span class="brand-text fw-light">AdminLTE 4</span> <!--end::Brand Text--> 
               </a>
               <!--end::Brand Link--> 
            </div>
            <!--end::Sidebar Brand--> <!--begin::Sidebar Wrapper--> 
            <div class="sidebar-wrapper">
               <nav class="mt-2">
                  <!--begin::Sidebar Menu--> 
                  <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation" data-accordion="false" id="navigation">
                     <li class="nav-header">NAVEGAÇÃO</li>
                     <li class="nav-item">
                        <a href="#" class="nav-link">
                           <i class="nav-icon mdi mdi-home"></i>
                           <p>Home</p>
                        </a>
                     </li>
                  </ul>
                  <!--end::Sidebar Menu--> 
               </nav>
            </div>
            <!--end::Sidebar Wrapper--> 
         </aside>
         <!--end::Sidebar--> <!--begin::App Main--> 
         <main class="app-main">
            <!--begin::App Content Header--> 
            <div class="app-content-header">
               <!--begin::Container--> 
               <div class="container-fluid">
                  <!--begin::Row--> 
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
                  <!--end::Row--> 
               </div>
               <!--end::Container--> 
            </div>
            <div class="app-content">
               <!--begin::Container--> 
               <div class="container-fluid">

aaa
               
               </div>
               <!--end::Container--> 
            </div>
            <!--end::App Content--> 
         </main>
         <!--end::App Main--> <!--begin::Footer-->
         <footer class="app-footer">
            <!--begin::To the end--> 
            <div class="float-end d-none d-sm-inline">Anything you want</div>
            <!--end::To the end--> <!--begin::Copyright--> <strong>
            Copyright &copy; 2014-2025&nbsp;
            <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
            </strong>
            All rights reserved.
            <!--end::Copyright--> 
         </footer>
         <!--end::Footer--> 
      </div>
      <script src="./js/overlayscrollbars.browser.es6.min.js"></script>
      <script src="./js/popper.min.js"></script>
      <script src="./js/bootstrap.min.js"></script>
      <script src="./js/adminlte.js"></script>

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
   
      <script>
        document.addEventListener('DOMContentLoaded', () => {
          const cssLink = document.querySelector('link[href*="css/adminlte.css"]');
          if (!cssLink) return;
      
          const cssHref = cssLink.getAttribute('href');
          const deploymentPath = cssHref.slice(0, cssHref.indexOf('css/adminlte.css'));
      
          document.querySelectorAll('img[src^="/assets/"]').forEach(img => {
            const originalSrc = img.getAttribute('src');
            if (originalSrc) {
              const relativeSrc = originalSrc.slice(1);
              img.src = deploymentPath + relativeSrc;
            }
          });
        });
      </script>


   </body>
   <!--end::Body-->
</html>