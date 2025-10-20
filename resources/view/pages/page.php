<!DOCTYPE html>
<html lang="en" data-layout="topnav">
<head>
    <meta charset="utf-8" />
    <title><?= $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SmileCopilot - O sistema completo para gestão de clínicas odontológicas. Controle consultas, pacientes, agenda, prescrição digital e muito mais.">
    <meta name="author" content="Codemaze Soluções de Mkt e Software">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.smilecopilot.com">
    <meta property="og:title" content="SmileCopilot - Gestão inteligente para clínicas odontológicas">
    <meta property="og:description" content="Otimize a gestão da sua clínica odontológica com SmileCopilot: agenda, pacientes, prescrições digitais e relatórios completos em um só lugar.">
    <meta property="og:image" content="https://www.smilecopilot.com/public/images/img_meta.jpg">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="SmileCopilot - Gestão inteligente para clínicas odontológicas">
    <meta name="twitter:description" content="Otimize a gestão da sua clínica odontológica com SmileCopilot: agenda, pacientes, prescrições digitais e relatórios completos em um só lugar.">
    <meta name="twitter:image" content="https://www.smilecopilot.com/public/images/img_meta.jpg">

    <link rel="shortcut icon" href="/public/assets/images/favicon.ico">  

    <?= $componentsScriptsHeader ?>
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
</style>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        <?= $header ?>

        <?php //echo $menu; ?>

        <div class="content-page">
            <div class="content">                
                <?= $content ?>
            </div> 

        <?= $footer ?>
        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <?= $componentsScriptsFooter ?>
    
</body>

</html>