<?php
    use \App\Model\Entity\Profissionais;
        
    $profissionalId = "";
    // Se vier via GET, salva na sessão
    if (isset($_GET['profissional_id'])) {
        $profissionalId = $_GET['profissional_id'];
        $_SESSION['PROFISSIONAL_ID'] = $profissionalId;
    }

    $objProfissionais = new Profissionais();
    $profissionais = $objProfissionais->getProfissionais(TENANCY_ID);
?>

<!-- ========== Topbar Start ========== -->
<div class="navbar-custom">
            <div class="topbar container-fluid">
                <div class="d-flex align-items-center gap-lg-2 gap-1">

                    <!-- Topbar Brand Logo -->
                    <div class="logo-topbar">
                        <!-- Logo light -->
                        <a href="/inicial" class="logo-light">
                            <span class="logo-lg">
                                <img src="../../../public/assets/images/SmileCopilot-Logo_139x28.png" alt="logo"  style="height:28px; width:auto;">
                            </span>
                            <span class="logo-sm">
                                <img src="../../../public/assets/images/SmileCopilot-LogoMin_43x28.png" alt="small logo">
                            </span>
                        </a>

                        <!-- Logo Dark -->
                        <a href="/inicial" class="logo-dark">
                            <span class="logo-lg">
                                <img src="../../../public/assets/images/SmileCopilot-Logo_139x28.png" alt="dark logo" style="height:28px; width:auto;">
                            </span>
                            <span class="logo-sm">
                                <img src="../../../public/assets/images/SmileCopilot-LogoMin_43x28.png" alt="small logo">
                            </span>
                        </a>
                    </div>
                </div>

                <ul class="topbar-menu d-flex align-items-center gap-3">
                    <li class="dropdown notification-list"                         
                        data-bs-toggle="popover" data-bs-placement="right" data-bs-trigger="hover"
                        data-bs-custom-class="primary-popover" data-bs-title="<?= \App\Core\Language::get('pacientes'); ?>"
                        data-bs-content="<?= \App\Core\Language::get('acesse_a_lista_pacientes'); ?>">
                        <a class="nav-link dropdown-toggle arrow-none" href="/listapaciente">
                            <i class="ri-user-3-line font-22"></i>
                        </a>
                    </li>
                    <li class="dropdown notification-list" 
                        data-bs-toggle="popover" data-bs-placement="right" data-bs-trigger="hover"
                        data-bs-custom-class="primary-popover" data-bs-title="<?= \App\Core\Language::get('consultas'); ?>"
                        data-bs-content="<?= \App\Core\Language::get('acompanhe_consultas_agendadas'); ?>">
                        <a class="nav-link dropdown-toggle arrow-none" href="/cadastropaciente">
                            <i class="ri-file-list-3-line font-22"></i>
                        </a>
                    </li>
                    <li class="dropdown notification-list" 
                        data-bs-toggle="popover" data-bs-placement="right" data-bs-trigger="hover"
                        data-bs-custom-class="primary-popover" data-bs-title="<?= \App\Core\Language::get('agenda'); ?>"
                        data-bs-content="<?= \App\Core\Language::get('confira_agenda'); ?>">
                        <a class="nav-link dropdown-toggle arrow-none" href="/agenda">
                            <i class="ri-calendar-2-line font-22"></i>
                        </a>
                    </li>


                    <li class="dropdown">
                        <a class="nav-link dropdown-toggle arrow-none nav-user px-2" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <span class="account-user-avatar">
                                <img src="../../../public/assets/images/users/avatar_sc.jpg" alt="user-image" width="32" class="rounded-circle">
                            </span>
                            <span class="d-lg-flex flex-column gap-1 d-none">
                                <h5 class="my-0">Codemaze</h5>
                                <h6 class="my-0 fw-normal">Suporte</h6>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated profile-dropdown">
                            <!-- item-->
                            <div class=" dropdown-header noti-title">
                                <h6 class="text-overflow m-0"><?= \App\Core\Language::get('bem_vindo'); ?></h6> 
                            </div>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">
                                <i class="mdi mdi-account-circle me-1"></i>
                                <span><?= \App\Core\Language::get('minha_conta'); ?></span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">
                                <i class="mdi mdi-account-edit me-1"></i>
                                <span><?= \App\Core\Language::get('configuracoes'); ?></span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">
                                <i class="mdi mdi-lifebuoy me-1"></i>
                                <span><?= \App\Core\Language::get('suporte'); ?></span>
                            </a>

                            <!-- item-->
                            <a href="/logoff" class="dropdown-item">
                                <i class="mdi mdi-logout me-1"></i>
                                <span><?= \App\Core\Language::get('sair'); ?></span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
</div>
<!-- ========== Topbar End ========== -->

<!-- ========== Slimbar Start ========== -->
 <form method="GET" id="formProfissional">
<div class="navbar-slim">
    <div class="container-fluid d-flex justify-content-start align-items-center gap-2">        
        <label for="profissional" class="mb-0 fw-semibold"><?= \App\Core\Language::get('selecione_profissional'); ?>:</label>
        <select 
            name="profissional_id" 
            id="profissional" 
            class="form-select" 
            style="width: 250px;" 
            onchange="document.getElementById('formProfissional').submit()">                
            <option value="all">-- <?= \App\Core\Language::get('selecione_todos'); ?> --</option>
            <?php foreach ($profissionais as $profissional): ?>
                <option 
                    value="<?= htmlspecialchars($profissional['DEN_IDDENTISTA'], ENT_QUOTES, 'UTF-8') ?>" 
                    <?= ($profissional['DEN_IDDENTISTA'] == $profissionalId) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($profissional['DEN_DCNOME'], ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>       
    </div>
</div>
</form>
<!-- ========== Slimbar End ========== -->

