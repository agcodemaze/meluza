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

      <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
        <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
        <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
        <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
        <script src="./js/adminlte.js"></script>
        <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
        <script>
            const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper";
            const Default = {
                scrollbarTheme: "os-theme-light",
                scrollbarAutoHide: "leave",
                scrollbarClickScroll: true,
            };
            document.addEventListener("DOMContentLoaded", function () {
                const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
                if (sidebarWrapper && OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined) {
                    OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                        scrollbars: {
                            theme: Default.scrollbarTheme,
                            autoHide: Default.scrollbarAutoHide,
                            clickScroll: Default.scrollbarClickScroll,
                        },
                    });
                }
            });
        </script>
        <!--end::OverlayScrollbars Configure--><!-- Image path runtime fix -->
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                // Find the link tag for the main AdminLTE CSS file.
                const cssLink = document.querySelector('link[href*="css/adminlte.css"]');
                if (!cssLink) {
                    return; // Exit if the link isn't found
                }

                // Extract the base path from the CSS href.
                // e.g., from "../css/adminlte.css", we get "../"
                // e.g., from "./css/adminlte.css", we get "./"
                const cssHref = cssLink.getAttribute("href");
                const deploymentPath = cssHref.slice(0, cssHref.indexOf("css/adminlte.css"));

                // Find all images with absolute paths and fix them.
                document.querySelectorAll('img[src^="/assets/"]').forEach((img) => {
                    const originalSrc = img.getAttribute("src");
                    if (originalSrc) {
                        const relativeSrc = originalSrc.slice(1); // Remove leading '/'
                        img.src = deploymentPath + relativeSrc;
                    }
                });
            });
        </script>
        <!-- OPTIONAL SCRIPTS -->
        <!-- apexcharts -->
        <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js" integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous"></script>
        <script>
            // NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
            // IT'S ALL JUST JUNK FOR DEMO
            // ++++++++++++++++++++++++++++++++++++++++++

            /* apexcharts
             * -------
             * Here we will create a few charts using apexcharts
             */

            //-----------------------
            // - MONTHLY SALES CHART -
            //-----------------------

            const sales_chart_options = {
                series: [
                    {
                        name: "Digital Goods",
                        data: [28, 48, 40, 19, 86, 27, 90],
                    },
                    {
                        name: "Electronics",
                        data: [65, 59, 80, 81, 56, 55, 40],
                    },
                ],
                chart: {
                    height: 180,
                    type: "area",
                    toolbar: {
                        show: false,
                    },
                },
                legend: {
                    show: false,
                },
                colors: ["#0d6efd", "#20c997"],
                dataLabels: {
                    enabled: false,
                },
                stroke: {
                    curve: "smooth",
                },
                xaxis: {
                    type: "datetime",
                    categories: ["2023-01-01", "2023-02-01", "2023-03-01", "2023-04-01", "2023-05-01", "2023-06-01", "2023-07-01"],
                },
                tooltip: {
                    x: {
                        format: "MMMM yyyy",
                    },
                },
            };

            const sales_chart = new ApexCharts(document.querySelector("#sales-chart"), sales_chart_options);
            sales_chart.render();

            //---------------------------
            // - END MONTHLY SALES CHART -
            //---------------------------

            function createSparklineChart(selector, data) {
                const options = {
                    series: [{ data }],
                    chart: {
                        type: "line",
                        width: 150,
                        height: 30,
                        sparkline: {
                            enabled: true,
                        },
                    },
                    colors: ["var(--bs-primary)"],
                    stroke: {
                        width: 2,
                    },
                    tooltip: {
                        fixed: {
                            enabled: false,
                        },
                        x: {
                            show: false,
                        },
                        y: {
                            title: {
                                formatter() {
                                    return "";
                                },
                            },
                        },
                        marker: {
                            show: false,
                        },
                    },
                };

                const chart = new ApexCharts(document.querySelector(selector), options);
                chart.render();
            }

            const table_sparkline_1_data = [25, 66, 41, 89, 63, 25, 44, 12, 36, 9, 54];
            const table_sparkline_2_data = [12, 56, 21, 39, 73, 45, 64, 52, 36, 59, 44];
            const table_sparkline_3_data = [15, 46, 21, 59, 33, 15, 34, 42, 56, 19, 64];
            const table_sparkline_4_data = [30, 56, 31, 69, 43, 35, 24, 32, 46, 29, 64];
            const table_sparkline_5_data = [20, 76, 51, 79, 53, 35, 54, 22, 36, 49, 64];
            const table_sparkline_6_data = [5, 36, 11, 69, 23, 15, 14, 42, 26, 19, 44];
            const table_sparkline_7_data = [12, 56, 21, 39, 73, 45, 64, 52, 36, 59, 74];

            createSparklineChart("#table-sparkline-1", table_sparkline_1_data);
            createSparklineChart("#table-sparkline-2", table_sparkline_2_data);
            createSparklineChart("#table-sparkline-3", table_sparkline_3_data);
            createSparklineChart("#table-sparkline-4", table_sparkline_4_data);
            createSparklineChart("#table-sparkline-5", table_sparkline_5_data);
            createSparklineChart("#table-sparkline-6", table_sparkline_6_data);
            createSparklineChart("#table-sparkline-7", table_sparkline_7_data);

            //-------------
            // - PIE CHART -
            //-------------

            const pie_chart_options = {
                series: [700, 500, 400, 600, 300, 100],
                chart: {
                    type: "donut",
                },
                labels: ["Chrome", "Edge", "FireFox", "Safari", "Opera", "IE"],
                dataLabels: {
                    enabled: false,
                },
                colors: ["#0d6efd", "#20c997", "#ffc107", "#d63384", "#6f42c1", "#adb5bd"],
            };

            const pie_chart = new ApexCharts(document.querySelector("#pie-chart"), pie_chart_options);
            pie_chart.render();

            //-----------------
            // - END PIE CHART -
            //-----------------
        </script>
        <!--end::Script-->
   
   </body>
</html>