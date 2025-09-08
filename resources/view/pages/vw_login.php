<?php
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;
    
    // Verifica se o cookie 'token' está presente
    if (isset($_COOKIE['token'])) {
        $secretKey = $_ENV['ENV_SECRET_KEY'] ?? getenv('ENV_SECRET_KEY') ?? '';
    
        try {
            JWT::decode($_COOKIE['token'], new Key($secretKey, 'HS256'));
            header('Location: /inicial');
            exit;
            
        } catch (\Exception $e) {
        
        }
    }
?>

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

    <script src="/public/assets/utils/languageDetector.js"></script>
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
                                <span><img src="/public/assets/images/SmileCopilot-Logo_139x28.png" alt="logo" style="height:28px; width:auto;"></span>
                            </a>
                        </div>

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <h4 class="text-dark-50 text-center pb-0 fw-bold"><?= \App\Core\Language::get('login'); ?></h4>
                                <p class="text-muted mb-4"  style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#novocliente-modal"><?= \App\Core\Language::get('login_desc'); ?></p>
                            </div>

                            <form id="loginForm">

                                <div class="mb-3">
                                    <label for="codigo" class="form-label"><?= \App\Core\Language::get('codigo_clinica'); ?></label>
                                    <input class="form-control" type="number" id="codigo" required="" placeholder="<?= \App\Core\Language::get('codigo_clinica_placeholder'); ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label"><?= \App\Core\Language::get('email'); ?></label>
                                    <input class="form-control" type="email" id="email" required="" placeholder="<?= \App\Core\Language::get('email_placeholder'); ?>">
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


<!-- /.Modal cadastro Novo Cliente -->
<div id="novocliente-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                  <div class="card">
                                <div class="card-body">

                                    <h4 class="header-title mb-3">Cadastre-se e utilize por 7 dias gratis</h4>

                                    <form>
                                        <div id="progressbarwizard">

                                            <ul class="nav nav-pills nav-justified form-wizard-header mb-3">
                                                <li class="nav-item">
                                                    <a href="#account-2" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 py-2">
                                                        <i class="mdi mdi-account-circle font-18 align-middle me-1"></i>
                                                        <span class="d-none d-sm-inline">Account</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#profile-tab-2" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 py-2">
                                                        <i class="mdi mdi-face-man-profile font-18 align-middle me-1"></i>
                                                        <span class="d-none d-sm-inline">Profile</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#finish-2" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 py-2">
                                                        <i class="mdi mdi-checkbox-marked-circle-outline font-18 align-middle me-1"></i>
                                                        <span class="d-none d-sm-inline">Finish</span>
                                                    </a>
                                                </li>
                                            </ul>

                                            <div class="tab-content b-0 mb-0">

                                                <div id="bar" class="progress mb-3" style="height: 7px;">
                                                    <div class="bar progress-bar progress-bar-striped progress-bar-animated bg-success"></div>
                                                </div>

                                                <div class="tab-pane" id="account-2">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="row mb-3">
                                                                <label class="col-md-3 col-form-label" for="userName1">User name</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" class="form-control" id="userName1" name="userName1" value="hyper">
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label class="col-md-3 col-form-label" for="password1"> Password</label>
                                                                <div class="col-md-9">
                                                                    <input type="password" id="password1" name="password1" class="form-control" value="123456789">
                                                                </div>
                                                            </div>

                                                            <div class="row mb-3">
                                                                <label class="col-md-3 col-form-label" for="confirm1">Re Password</label>
                                                                <div class="col-md-9">
                                                                    <input type="password" id="confirm1" name="confirm1" class="form-control" value="123456789">
                                                                </div>
                                                            </div>
                                                        </div> <!-- end col -->
                                                    </div> <!-- end row -->

                                                    <ul class="list-inline wizard mb-0">
                                                        <li class="next list-inline-item float-end">
                                                            <a href="javascript:void(0);" class="btn btn-info">Add More Info <i class="mdi mdi-arrow-right ms-1"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <div class="tab-pane" id="profile-tab-2">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="row mb-3">
                                                                <label class="col-md-3 col-form-label" for="name1"> First name</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" id="name1" name="name1" class="form-control" value="Francis">
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label class="col-md-3 col-form-label" for="surname1"> Last name</label>
                                                                <div class="col-md-9">
                                                                    <input type="text" id="surname1" name="surname1" class="form-control" value="Brinkman">
                                                                </div>
                                                            </div>

                                                            <div class="row mb-3">
                                                                <label class="col-md-3 col-form-label" for="email1">Email</label>
                                                                <div class="col-md-9">
                                                                    <input type="email" id="email1" name="email1" class="form-control" value="cory1979@hotmail.com">
                                                                </div>
                                                            </div>
                                                        </div> <!-- end col -->
                                                    </div> <!-- end row -->
                                                    <ul class="pager wizard mb-0 list-inline">
                                                        <li class="previous list-inline-item">
                                                            <button type="button" class="btn btn-light"><i class="mdi mdi-arrow-left me-1"></i> Back to Account</button>
                                                        </li>
                                                        <li class="next list-inline-item float-end">
                                                            <button type="button" class="btn btn-info">Add More Info <i class="mdi mdi-arrow-right ms-1"></i></button>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <div class="tab-pane" id="finish-2">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="text-center">
                                                                <h2 class="mt-0"><i class="mdi mdi-check-all"></i></h2>
                                                                <h3 class="mt-0">Thank you !</h3>

                                                                <p class="w-75 mb-2 mx-auto">Quisque nec turpis at urna dictum luctus. Suspendisse convallis dignissim eros at volutpat. In egestas mattis dui. Aliquam
                                                                    mattis dictum aliquet.</p>

                                                                <div class="mb-3">
                                                                    <div class="form-check d-inline-block">
                                                                        <input type="checkbox" class="form-check-input" id="customCheck3">
                                                                        <label class="form-check-label" for="customCheck3">I agree with the Terms and Conditions</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> <!-- end col -->
                                                    </div> <!-- end row -->
                                                    <ul class="pager wizard mb-0 list-inline mt-1">
                                                        <li class="previous list-inline-item">
                                                            <button type="button" class="btn btn-light"><i class="mdi mdi-arrow-left me-1"></i> Back to Profile</button>
                                                        </li>
                                                        <li class="next list-inline-item float-end">
                                                            <button type="button" class="btn btn-info">Submit</button>
                                                        </li>
                                                    </ul>
                                                </div>

                                            </div> <!-- tab-content -->
                                        </div> <!-- end #progressbarwizard-->
                                    </form>

                                </div> <!-- end card-body -->
                            </div> <!-- end card-->
                        


            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.Modal cadastro Novo Cliente -->


    <!-- Vendor js -->
    <script src="/public/assets/js/vendor.min.js"></script>
    <!-- Bootstrap Wizard Form js -->
    <script src="/public/assets/vendor/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>
    <!-- Wizard Form Demo js -->
    <script src="/public/assets/js/pages/demo.form-wizard.js"></script>
    <!-- App js -->
    <script src="/public/assets/js/app.min.js"></script>

</body>

<script>
    document.getElementById('loginForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const codigo = document.getElementById('codigo').value;

        const payload = {
            email: email,
            codigo: codigo,
            password: password
        };

        fetch('/logincheck', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '/inicial';
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Erro:', error));
    });
</script>



</html>