<?php
    use \App\Model\Entity\Profissionais;

    $profissionalId = "";

if (isset($_GET['profissional_id'])) {
    $profissionalId = $_GET['profissional_id'];
    $_SESSION['PROFISSIONAL_ID'] = $profissionalId;
} elseif (isset($_SESSION['PROFISSIONAL_ID'])) {
    $profissionalId = $_SESSION['PROFISSIONAL_ID'];
}
    $objProfissionais = new Profissionais();
    $profissionais = $objProfissionais->getProfissionais(TENANCY_ID);
?>

<div id="preloader">
  <span class="loader"></span>
</div>

<style>
    /* Preloader container */
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
</style>

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
            <img src="../../../public/assets/images/SmileCopilot-LogoMin_43x28.png" alt="small logo">
          </span>
        </a>
        <a href="/inicial" class="logo-dark">
          <span class="logo-lg">
            <img src="../../../public/assets/images/SmileCopilot-Logo_139x28.png" alt="dark logo" style="height:28px; width:auto;">
          </span>
          <span class="logo-sm">
            <img src="../../../public/assets/images/SmileCopilot-LogoMin_43x28.png" alt="small logo">
          </span>
        </a>
      </div>

      <!-- 3 ícones -->
      <ul class="d-flex align-items-center gap-2 mb-0 list-unstyled">
        <li>
          <a class="nav-link" href="/listapaciente" 
             data-bs-toggle="popover" data-bs-placement="right" 
             data-bs-trigger="hover"
             data-bs-custom-class="info-popover" 
             data-bs-content="<?= \App\Core\Language::get('acesse_a_lista_pacientes'); ?>">
             <button type="button" class="btn btn-soft-info"><i class="ri-user-3-line me-1"></i><span><?= \App\Core\Language::get('pacientes'); ?></span></button>
          </a>
        </li>
        <li>
          <a class="nav-link" href="/cadastropaciente" 
             data-bs-toggle="popover" data-bs-placement="right" 
             data-bs-trigger="hover"
             data-bs-custom-class="info-popover" 
             data-bs-content="<?= \App\Core\Language::get('acompanhe_consultas_agendadas'); ?>">
            <button type="button" class="btn btn-soft-info"><i class="ri-file-list-3-line me-1"></i><span><?= \App\Core\Language::get('consultas'); ?></span></button>
          </a>
        </li>
        <li>
          <a class="nav-link" href="/agenda" 
             data-bs-toggle="popover" data-bs-placement="right" 
             data-bs-trigger="hover"
             data-bs-custom-class="info-popover" 
             data-bs-content="<?= \App\Core\Language::get('confira_agenda'); ?>">
            <button type="button" class="btn btn-soft-info"><i class="ri-calendar-2-line me-1"></i><span><?= \App\Core\Language::get('agenda'); ?></span></button>
          </a>
        </li>
      </ul>    
    </div>

    

    <ul class="d-flex align-items-center gap-2 mb-0 list-unstyled ms-auto">

    <form method="GET" id="formProfissional">
        <li class="dropdown">
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
        </li>
    </form>

      <!-- Ícone Configurações -->
      <li>
        <a class="nav-link" href="/configuracoes" 
           data-bs-toggle="popover" data-bs-placement="bottom" 
           data-bs-trigger="hover"
           data-bs-custom-class="info-popover" 
           data-bs-content="Alertas">
          <i class="ri-alert-line font-22 text-info"></i>
        </a>
      </li>

      <!-- Ícone Lista -->
      <li>
        <a class="nav-link" href="/listapaciente" 
           data-bs-toggle="popover" data-bs-placement="bottom" 
           data-bs-trigger="hover"
           data-bs-custom-class="info-popover" 
           data-bs-content="Lista de Pacientes">
          <i class="ri-file-list-3-line font-22 text-info"></i>
        </a>
      </li>

      <!-- Ícone Alertas -->
      <li>
        <a class="nav-link" href="/alertas" 
           data-bs-toggle="popover" data-bs-placement="bottom" 
           data-bs-trigger="hover"
           data-bs-custom-class="info-popover" 
           data-bs-content="Configurações">
          <i class="ri-settings-3-line font-22 text-info"></i>
        </a>
      </li>
    </ul>

    <!-- Avatar do usuário -->
    <ul class="topbar-menu d-flex align-items-center gap-2 mb-0">
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
          <div class="dropdown-header noti-title">
            <h6 class="text-overflow m-0"><?= \App\Core\Language::get('bem_vindo'); ?></h6> 
          </div>
          <a href="javascript:void(0);" class="dropdown-item">
            <i class="mdi mdi-account-circle me-1"></i>
            <span><?= \App\Core\Language::get('minha_conta'); ?></span>
          </a>
          <a href="javascript:void(0);" class="dropdown-item">
            <i class="mdi mdi-account-edit me-1"></i>
            <span><?= \App\Core\Language::get('configuracoes'); ?></span>
          </a>
          <a href="javascript:void(0);" class="dropdown-item">
            <i class="mdi mdi-lifebuoy me-1"></i>
            <span><?= \App\Core\Language::get('suporte'); ?></span>
          </a>
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



