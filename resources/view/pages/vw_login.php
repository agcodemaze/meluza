<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title><?= $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="<?= $description; ?>" name="description" />
    <meta name="keywords" content="<?= $keywords; ?>" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $site; ?>/login">
    <meta property="og:title" content="<?= $title; ?>">
    <meta property="og:description" content="<?= $description; ?>">
    <meta property="og:image" content="https://d19jtskssuoraj.cloudfront.net/assets/banner.min-eff05d8611814ee06c7d5723aa6aa185d8db4ef1.jpg">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= $site; ?>/login">
    <meta property="twitter:title" content="<?= $title; ?>">
    <meta property="twitter:description" content="<?= $description; ?>">
    <meta property="twitter:image" content="https://d19jtskssuoraj.cloudfront.net/assets/banner.min-eff05d8611814ee06c7d5723aa6aa185d8db4ef1.jpg">

    

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- Theme Config Js -->
    <script src="/public/assets/js/hyper-config.js"></script>

    <!-- Vendor css -->
    <link href="/public/assets/css/vendor.min.css" rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link href="/public/assets/css/app-saas.min.css" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="/public/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
</head>

<body class="authentication-bg position-relative">
    <div class="position-absolute start-0 end-0 start-0 bottom-0 w-100 h-100">
        <svg xmlns='http://www.w3.org/2000/svg' width='100%' height='100%' viewBox='0 0 800 800'>
            <g fill-opacity='0.22'>
                <circle style="fill: rgba(var(--ct-primary-rgb), 0.1);" cx='400' cy='400' r='600' />
                <circle style="fill: rgba(var(--ct-primary-rgb), 0.2);" cx='400' cy='400' r='500' />
                <circle style="fill: rgba(var(--ct-primary-rgb), 0.3);" cx='400' cy='400' r='300' />
                <circle style="fill: rgba(var(--ct-primary-rgb), 0.4);" cx='400' cy='400' r='200' />
                <circle style="fill: rgba(var(--ct-primary-rgb), 0.5);" cx='400' cy='400' r='100' />
            </g>
        </svg>
    </div>
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5 position-relative">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-lg-5">
                    <div class="card">

                        <!-- Logo -->
                        <div class="card-header py-4 text-center">
                            <a href="index.html">
                                <span><img src="/public/assets/images/SmileCopilot-Logo_139x28.png" alt="logo" height="22"></span>
                            </a>
                        </div>

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <h4 class="text-dark-50 text-center pb-0 fw-bold"><?= \App\Core\Language::get('login'); ?></h4>
                                <p class="text-muted mb-4"><?= \App\Core\Language::get('login_desc'); ?></p>
                            </div>

                            <form action="#">

                                <div class="mb-3">
                                    <label for="codigo" class="form-label"><?= \App\Core\Language::get('codigo_clinica'); ?></label>
                                    <input class="form-control" type="number" id="codigo" required="" placeholder="<?= \App\Core\Language::get('codigo_clinica_placeholder'); ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="emailaddress" class="form-label"><?= \App\Core\Language::get('email'); ?></label>
                                    <input class="form-control" type="email" id="emailaddress" required="" placeholder="<?= \App\Core\Language::get('email_placeholder'); ?>">
                                </div>

                                <div class="mb-3">
                                    <a href="pages-recoverpw.html" class="text-muted float-end"><small><?= \App\Core\Language::get('senha_esqueceu'); ?></small></a>
                                    <label for="password" class="form-label"><?= \App\Core\Language::get('senha'); ?></label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password" class="form-control" placeholder="<?= \App\Core\Language::get('senha_placeholder'); ?>">
                                        <div class="input-group-text" data-password="false">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 mb-0 text-center">
                                    <button class="btn btn-primary" type="submit"> <?= \App\Core\Language::get('entrar'); ?> </button> 
                                </div>

                            </form>
                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->
                    <!-- end row -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <footer class="footer footer-alt">
        <script>document.write(new Date().getFullYear())</script> © Codemaze
    </footer>
    <!-- Vendor js -->
    <script src="/public/assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="/public/assets/js/app.min.js"></script>

</body>

</html>