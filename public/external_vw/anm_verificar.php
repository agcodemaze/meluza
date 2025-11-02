<?php
  require_once __DIR__ . '/../../vendor/autoload.php';
  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../'); 
  $dotenv->safeLoad();

  use \App\Model\Entity\Conn;
  use \App\Controller\Pages\CadAnamnese; 
  use \App\Model\Entity\Organization;

  $con = new Conn();
  $objOrganization = new Organization();

  $AnmCheck = \App\Controller\Pages\CadAnamnese::getAnamneseByCodAuth($_GET['c']);

  if(!empty($AnmCheck)) {
    $configuracoes = $objOrganization->getConfiguracoes($AnmCheck["TENANCY_ID"]);
    $dataHora = new DateTime($AnmCheck["ANR_DTCREATE_AT"]);
    $dataHoraBR = $dataHora->format('d/m/Y H:i:s');

    $emissor = mb_convert_case(mb_strtolower($configuracoes["CFG_DCNOME_CLINICA"]), MB_CASE_TITLE, "UTF-8");
    $CodAuth = $AnmCheck["ANR_DCCOD_AUTENTICACAO"];
    $status = "✅ Documento autêntico";
  } else {
    $dataHoraBR = "Indefinida";
    $emissor = "Não encontrado";
    $CodAuth = "Inválido";
    $status = "❌ Documento não validado";
  }

    

?>


<!DOCTYPE html>
<html lang="en" data-layout="topnav">
<head>
    <meta charset="utf-8" />
    <title>SmileCopilot</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SmileCopilot - O sistema completo para gestão de clínicas odontológicas. Controle consultas, pacientes, agenda, prescrição digital e muito mais.">
    <meta name="author" content="Codemaze Soluções de Mkt e Software">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://smilecopilot.com">
    <meta property="og:title" content="SmileCopilot - Gestão inteligente para clínicas odontológicas">
    <meta property="og:description" content="Otimize a gestão da sua clínica odontológica com SmileCopilot: agenda, pacientes, prescrições digitais e relatórios completos em um só lugar.">
    <meta property="og:image" content="https://app.smilecopilot.com/public/assets/images/img_meta.jpg">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="SmileCopilot - Gestão inteligente para clínicas odontológicas">
    <meta name="twitter:description" content="Otimize a gestão da sua clínica odontológica com SmileCopilot: agenda, pacientes, prescrições digitais e relatórios completos em um só lugar.">
    <meta name="twitter:image" content="https://app.smilecopilot.com/public/assets/images/img_meta.jpg">

    <link rel="shortcut icon" href="/public/assets/images/favicon.ico">  

    <link href="/public/assets/css/app-saas.min.css" rel="stylesheet" type="text/css" id="app-style" />
    <script src="/public/assets/vendor/ad_sweetalert/jquery-3.7.0.min.js"></script>
    <script src="/public/assets/js/hyper-config.js"></script>
    <link href="/public/assets/css/app-saas.min.css" rel="stylesheet" type="text/css" id="app-style" />
    <link href="/public/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/public/assets/vendor/ad_sweetalert/sweetalert2.min.css" rel="stylesheet">
    <script src="/public/assets/vendor/ad_sweetalert/sweetalert2.all.min.js"></script>
    <script src="/public/assets/utils/languageDetector.js"></script>
</head>

<style>
    .navbar-slim {
        background-color: #f5f5f5ff;
        border-top: 1px solid #ddd;
        height: 56px; /* altura reduzida */
        display: flex;
        align-items: center; /* centraliza verticalmente */
    }
    .navbar-slim .container-fluid {
        display: flex;
        align-items: center; /* garante centralização */
        gap: 0.5rem;
    }
    .navbar-slim .btn {
        font-size: 12px;
        padding: 2px 8px;
    }

    #preloader {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(233, 244, 247, 0.8); /* Fundo levemente transparente */
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999; /* Fica acima de tudo */
    }

    /* Loader */
    .loader {
      width: 48px;
      height: 48px;
      display: inline-block;
      position: relative;
    }
    .loader::after,
    .loader::before {
      content: '';  
      box-sizing: border-box;
      width: 48px;
      height: 48px;
      border: 2px solid #38c6f1ff;
      position: absolute;
      left: 0;
      top: 0;
      animation: rotation 2s ease-in-out infinite alternate;
    }
    .loader::after {
      border-color: rgba(10, 110, 177, 1);
      animation-direction: alternate-reverse;
    }

    @keyframes rotation {
      0% {
        transform: rotate(0deg);
      }
      100% {
        transform: rotate(360deg);
      }
    } 

    :root{
      --card-bg: linear-gradient(135deg,#f8fbff 0%, #ffffff 100%);
      --accent: #e9f7fb;
    }

    body {
      background: linear-gradient(180deg,#f1f6fb 0%, #eef5fb 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
      font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
    }

    .confirm-card{
      width: 100%;
      max-width: 720px;
      background: var(--card-bg);
      border-radius: 14px;
      box-shadow: 0 8px 30px rgba(28,40,70,0.12);
      padding: 22px;
      border: 1px solid rgba(20,40,70,0.04);
    }

    .avatar-circle{
      width: 64px;
      height: 64px;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(180deg,#eef9ff,#dff6fc);
      border: 1px solid rgba(23,162,184,0.12);
      font-weight: 700;
      color: #0b7285;
      font-size: 24px;
    }

    .title {
      font-size: 20px;
      font-weight: 700;
      color: #0f1724;
    }

    .lead-text {
      color: #475569;
      font-size: 15px;
      margin-top: 6px;
    }

    .btn-wide {
      min-width: 120px;
      padding-left: 18px;
      padding-right: 18px;
    }

    .actions {
      gap: 12px;
    }

    .meta {
      font-size: 13px;
      color: #687179;
    }

    /* small subtle shadow on hover for buttons */
    .btn:hover { transform: translateY(-1px); transition: .12s ease; }

    /* feedback badge */
    #feedback {
      display: none;
    }

    :root {
      --primary: #1e88e5;
      --secondary: #f5f7fa;
      --border: #e0e0e0;
      --text: #333;
      --radius: 12px;
    }

    body {
      font-family: 'Segoe UI', Roboto, sans-serif;
      background: var(--secondary);
      color: var(--text);
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 800px;
      margin: 30px auto;
      background: #fff;
      padding: 25px;
      border-radius: var(--radius);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    h1 {
      text-align: center;
      color: var(--primary);
      margin-bottom: 20px;
    }

    .secao {
      margin-top: 30px;
      padding: 20px;
      border: 1px solid var(--border);
      border-radius: var(--radius);
      background: #fafafa;
    }

    .secao h2 {
      color: var(--primary);
      margin-bottom: 8px;
    }

    .secao p {
      color: #555;
      font-size: 0.95rem;
      margin-bottom: 15px;
    }

    .pergunta {
      margin-bottom: 20px;
    }

    .pergunta label {
      display: block;
      font-weight: 600;
      margin-bottom: 6px;
    }

    input[type="text"], textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid var(--border);
      border-radius: var(--radius);
      font-size: 1rem;
      transition: border-color 0.2s ease;
    }

    input[type="text"]:focus, textarea:focus {
      border-color: var(--primary);
      outline: none;
    }

    textarea {
      resize: vertical;
      min-height: 80px;
    }

    .radio-group {
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
    }

    .radio-group label {
      font-weight: normal;
    }

    .btn-enviar {
      background: var(--primary);
      color: white;
      border: none;
      padding: 14px 22px;
      font-size: 1rem;
      border-radius: var(--radius);
      cursor: pointer;
      width: 100%;
      transition: background 0.3s;
    }

    .btn-enviar:hover {
      background: #1565c0;
    }

    @media (max-width: 600px) {
      .container {
        margin: 15px;
        padding: 20px;
      }
      h1 {
        font-size: 1.5rem;
      }
    }
</style>
<body>
  <!-- Begin page -->
  <div class="wrapper">
    <!-- ========== Topbar Start ========== -->
    <div class="navbar-custom">
      <div class="topbar d-flex align-items-center justify-content-between">
        <!-- Logo + Ícones -->
        <div class="d-flex align-items-center gap-3">
          <!-- Topbar Brand Logo -->
          <div class="logo-topbar">
            <a href="/inicial" class="logo-light">
              <span class="logo-lg">
                <img src="../../../public/assets/images/SmileCopilot-Logo_139x28.png" alt="logo" style="height:28px; width:auto;">
              </span>
              <span class="logo-sm">
                <img src="../../../public/assets/images/SmileCopilot-Logo_139x28.png" alt="small logo">
              </span>
            </a>
            <a href="/inicial" class="logo-dark">
              <span class="logo-lg">
                <img src="../../../public/assets/images/SmileCopilot-Logo_139x28.png" alt="dark logo" style="height:28px; width:auto;">
              </span>
              <span class="logo-sm">
                <img src="../../../public/assets/images/SmileCopilot-Logo_139x28.png" alt="small logo">
              </span>
            </a>
          </div>
        </div>
      </div>
    </div>
    

<div class="content-page">
  <div class="content">   

    <main class="report-body container mt-5 pt-4" style="padding-bottom: 1rem;">

      <!-- Dados do Paciente -->
      <section class="patient-info card shadow-sm border-0 mb-4">
        <div class="card-body">
          <h4 class="section-title mb-3">
             Checagem de Autenticidade
          </h4>
          <div class="row gy-3 gx-4">
            <div class="col-md-6 col-lg-4"><p><strong>Emissor:</strong><br><span><?= $emissor ?></span></p></div>
            <div class="col-md-6 col-lg-4"><p><strong>Código de Autenticação:</strong><br><span><?= $CodAuth ?></span></p></div>
            <div class="col-md-6 col-lg-4"><p><strong>Emitido em:</strong><br><span><?= $dataHoraBR ?></span></p></div>
            <div class="col-md-6 col-lg-4"><p><strong>Status:</strong><br><span><?= $status  ?></span></p></div>
          </div>
        </div>
      </section>
    </main>
  </div>
</div>

<!-- Estilo aprimorado -->
<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

  body {
    font-family: 'Poppins', sans-serif;
    background: #f4f6f9;
    color: #333;
    margin: 0;
    padding: 0;
    overflow-x: hidden; /* evita corte lateral no celular */
  }

  .report-body {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 15px; /* garante margem lateral no mobile */
  }

  .card-body p span {
    color: #555;
  }

  .section-title {
    border-bottom: 2px solid #1e88e5;
    display: inline-block;
    padding-bottom: 4px;
  }

  .secao {
    background: #fff;
    border: 1px solid #e0e6ed;
    border-radius: 10px;
  }

  .secao .bar {
    height: 4px;
    width: 50px;
    background: #1e88e5;
    border-radius: 2px;
    margin-bottom: 8px;
  }

  .form-control {
    border-radius: 6px;
    border: 1px solid #d0d6e2;
    transition: all 0.2s;
  }

  .form-control:focus {
    border-color: #1e88e5;
    box-shadow: 0 0 0 0.2rem rgba(30,136,229,0.15);
  }

  .btn-success {
    background: #1e88e5;
    border: none;
    transition: 0.3s;
  }

  .btn-success:hover {
    background: #5eacf0ff;
  }

  /* Garante responsividade total */
  @media (max-width: 768px) {
    .secao {
      padding: 1.2rem;
    }
    .card-body {
      padding: 1rem;
    }
  }

  /* Estilo de impressão */
  @media print {
    body {
      background: #fff !important;
      color: #000;
      -webkit-print-color-adjust: exact;
    }

    .btn-success {
      display: none !important;
    }

    .card,
    .secao {
      border: none !important;
      box-shadow: none !important;
      background: #fff !important;
    }

    @page {
      margin: 20mm;
    }
  }
</style>


</body>
</html>