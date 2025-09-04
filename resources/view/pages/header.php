<!-- ========== Topbar Start ========== -->
<div class="navbar-custom">
            <div class="topbar container-fluid">
                <div class="d-flex align-items-center gap-lg-2 gap-1">

                    <!-- Topbar Brand Logo -->
                    <div class="logo-topbar">
                        <!-- Logo light -->
                        <a href="/" class="logo-light">
                            <span class="logo-lg">
                                <img src="../../../public/assets/images/logo.png" alt="logo">
                            </span>
                            <span class="logo-sm">
                                <img src="../../../public/assets/images/logo-sm.png" alt="small logo">
                            </span>
                        </a>

                        <!-- Logo Dark -->
                        <a href="/" class="logo-dark">
                            <span class="logo-lg">
                                <img src="../../../public/assets/images/logo-dark.png" alt="dark logo">
                            </span>
                            <span class="logo-sm">
                                <img src="../../../public/assets/images/logo-dark-sm.png" alt="small logo">
                            </span>
                        </a>
                    </div>
                </div>

                <ul class="topbar-menu d-flex align-items-center gap-3">
                    <li class="dropdown notification-list"                         
                        data-bs-toggle="popover" data-bs-placement="right" data-bs-trigger="hover"
                        data-bs-custom-class="primary-popover" data-bs-title="Pacientes"
                        data-bs-content="Acesse a lista de pacientes, edite informações ou adicione novos cadastros.">
                        <a class="nav-link dropdown-toggle arrow-none" href="/cadastropaciente">
                            <i class="ri-user-3-line font-22"></i>
                        </a>
                    </li>
                    <li class="dropdown notification-list" 
                        data-bs-toggle="popover" data-bs-placement="right" data-bs-trigger="hover"
                        data-bs-custom-class="primary-popover" data-bs-title="Consultas"
                        data-bs-content="Acompanhe suas consultas agendadas ou marque uma nova.">
                        <a class="nav-link dropdown-toggle arrow-none" href="/cadastropaciente">
                            <i class="ri-file-list-3-line font-22"></i>
                        </a>
                    </li>
                    <li class="dropdown notification-list" 
                        data-bs-toggle="popover" data-bs-placement="right" data-bs-trigger="hover"
                        data-bs-custom-class="primary-popover" data-bs-title="Agenda"
                        data-bs-content="Confira a sua agenda.">
                        <a class="nav-link dropdown-toggle arrow-none" href="/cadastropaciente">
                            <i class="ri-calendar-2-line font-22"></i>
                        </a>
                    </li>


                    <li class="dropdown">
                        <a class="nav-link dropdown-toggle arrow-none nav-user px-2" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <span class="account-user-avatar">
                                <img src="../../../public/assets/images/users/avatar-1.jpg" alt="user-image" width="32" class="rounded-circle">
                            </span>
                            <span class="d-lg-flex flex-column gap-1 d-none">
                                <h5 class="my-0">Codemaze</h5>
                                <h6 class="my-0 fw-normal">Suporte</h6>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated profile-dropdown">
                            <!-- item-->
                            <div class=" dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Bem vindo(a)</h6>
                            </div>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">
                                <i class="mdi mdi-account-circle me-1"></i>
                                <span>Minha Conta</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">
                                <i class="mdi mdi-account-edit me-1"></i>
                                <span>Configurações</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">
                                <i class="mdi mdi-lifebuoy me-1"></i>
                                <span>Suporte</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">
                                <i class="mdi mdi-logout me-1"></i>
                                <span>Sair</span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
</div>
<!-- ========== Topbar End ========== -->