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
    <meta property="og:image" content="https://www.smilecopilot.com/public/assets/images/img_meta.jpg">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="SmileCopilot - Gestão inteligente para clínicas odontológicas">
    <meta name="twitter:description" content="Otimize a gestão da sua clínica odontológica com SmileCopilot: agenda, pacientes, prescrições digitais e relatórios completos em um só lugar.">
    <meta name="twitter:image" content="https://www.smilecopilot.com/public/assets/images/img_meta.jpg">

    <link rel="shortcut icon" href="/public/assets/images/favicon.ico">  

    <?= $componentsScriptsHeader ?>
    <script src="/public/assets/utils/languageDetector.js"></script>

    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>

    <!-- PUSH NOTIFICATION -->
<script>
  window.OneSignalDeferred = window.OneSignalDeferred || [];
  OneSignalDeferred.push(async function(OneSignal) {
    await OneSignal.init({ appId: "fe361fb5-f7a3-4a47-b210-d74a11802559" });
    OneSignal.Debug.setLogLevel("debug");


    const externalId = "<?php echo TENANCY_ID . '-' . USER_ID; ?>";

    try {
      await OneSignal.login(externalId);
      console.log("Usuário logado com external_id:", externalId);

    } catch (e) {      
      if (e.errors?.[0]?.code === "user-2") {
        console.warn("Alias já está em uso, assumindo que já está vinculado.");
        
      } else {
        console.error("Erro ao fazer login:", e);
      }
    }

    const isPushEnabled = await OneSignal.Notification.isPushEnabled();
    if (!isPushEnabled) {
      await OneSignal.Slidedown.promptPush();
    }

    const permission = await OneSignal.Notification.getPermission();
    if (permission === 'denied') {
      // Exibe instruções personalizadas
      OneSignal.PushUnblock.show(); // ou um modal seu com orientação
    }

  });
</script>

    <!-- <script src="/public/assets/js/onesignalload.js"></script> -->
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