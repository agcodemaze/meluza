<?php
require BASE_PATH . "src/auth.php"; 
include_once BASE_PATH . "objects/objects.php";

$siteAdmin = new SITE_ADMIN(); 

?>

<!DOCTYPE html>
<html lang="en">
   <head>

	<?php include_once BASE_PATH . "src/head.php"; ?>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" media="print" onload="this.media='all'" />
        <!--end::Fonts-->
        <!--begin::Third Party Plugin(OverlayScrollbars)-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
        <!--end::Third Party Plugin(OverlayScrollbars)-->
        <!--begin::Third Party Plugin(Bootstrap Icons)-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
        <!--end::Third Party Plugin(Bootstrap Icons)-->
        <!--begin::Required Plugin(AdminLTE)-->
        <link rel="stylesheet" href="./css/adminlte.css" />
        <!--end::Required Plugin(AdminLTE)-->
        <!-- apexcharts -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous" />

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
                           <li class="breadcrumb-item"><a href="/inicial">Inicial</a></li>
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
                     <div class="row">
                        <div class="col-12">
                           <p class="text-center"><strong>Consumo da sua Licença</strong></p>
                           <div class="progress-group">
                               Clientes cadastrados
                               <span class="float-end"><b>160</b>/200</span>
                               <div class="progress progress-sm">
                                   <div class="progress-bar" style="width: 45%; background-color: #00c1fb;"></div>
                               </div>
                           </div>
                           <!-- /.progress-group -->
                           <div class="progress-group">
                               Serviços Executados (mês)
                               <span class="float-end"><b>5</b>/12</span>
                               <div class="progress progress-sm">
                                   <div class="progress-bar" style="width: 45%; background-color: #00c1fb;"></div>
                               </div>
                           </div>
                        </div>
                     </div>
                     <br>
                     <div class="row">
                         <div class="col-12">
                            <div class="card">
                              <!-- Info Boxes Style 2 -->
                              <div class="info-box mb-3" style="background-color: #7eda0d; color: #000000;">
                                  <span class="info-box-icon" style="background-color: rgba(0, 0, 0, 0.15); color: #000000;">
                                      <i class="bi bi-tag-fill"></i>
                                  </span>
                                  <div class="info-box-content">
                                      <span class="info-box-text">Próxima Faxina</span>
                                      <span class="info-box-number">Quarta-feira 25/08 08:30</span>
                                  </div>
                              </div>
                             <!-- /.info-box -->
                           <div class="info-box mb-3" style="background-color: #7eda0d; color: #000000;">
                               <span class="info-box-icon" style="background-color: rgba(0, 0, 0, 0.15); color: #000000;">
                                   <i class="bi bi-tag-fill"></i>
                               </span>
                               <div class="info-box-content">
                                   <span class="info-box-text">Duração Média</span>
                                   <span class="info-box-number">04 horas e 32 minutos</span>
                               </div>
                           </div>
                           </div><!-- /.card -->                                
                        </div>
                     </div>
                     <!-- end row -->   
                     <br>              
                     <div class="card-body">
                         <!--begin::Row-->
                         <div class="row"> 
                              <div class="col-md-12">
                                 <p class="text-center"><strong>Faxinas Executadas x Ganhos (últimos 12 meses)</strong></p>
                                 <div id="sales-chart"></div>
                             </div>
                         </div>
                         <!--end::Row-->
                     </div>
                     <br>
                     <div class="card-body">
                         <!--begin::Row-->
                         <div class="row">
                           <div class="col-12"><div id="pie-chart"></div></div>
                           <!-- /.col -->
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
        <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->

        <!-- OPTIONAL SCRIPTS -->
        <!-- apexcharts -->
       <script src="../../vendor/apexcharts/apexcharts.min.js"></script>
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