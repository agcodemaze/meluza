<!DOCTYPE html>
<html lang="en" data-layout="topnav">

<head>
    <meta charset="utf-8" />
    <title><?= $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />

    <link rel="shortcut icon" href="/public/assets/images/favicon.ico">    

    <?= $componentsScriptsHeader ?>
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