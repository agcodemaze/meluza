<?php
return [

    'login' => 'pages/login/index.php',
    'recuperar_senha' => 'pages/login/recuperar_senha.php',
    'processa_recuperacao' => 'pages/login/processa_recuperacao.php',
    'redefinir_senha' => 'pages/login/redefinir_senha.php',
    'atualizar_senha' => 'pages/login/atualizar_senha.php',

    'push' => 'pages/push/OneSignalSDKWorker.js',

    'limparNotificacoes' => 'pages/notificacoes/limparNotificacoes.php',    

    'editInfo' => 'pages/inicial_info/editInfo.php',
    'editInfoProc' => 'pages/inicial_info/editInfoProc.php',  
    
    'condominio' => 'pages/login/condominio.php',
    'logoff' => 'pages/logoff/index.php',
    'errors' => 'pages/errors/index.php',

    'inicial' => 'pages/inicial/index.php',
    'inicialupdateStatusCheckbox' => 'pages/inicial/updateStatusCheckbox.php', 
    'sendMsgProc' => 'pages/inicial/sendMsgProc.php',
    'deleteReclamacaoProc' => 'pages/inicial/deleteReclamacaoProc.php',

    'auditoria' => 'pages/auditoria/index.php',

    'indicadores' => 'pages/dashboard/index.php',
    'indicadoresUpload' => 'pages/dashboard/upload.php',
    'indicadoresBarGraph' => 'pages/dashboard/barGraph.php',
    'indicadoresDashboardJS' => 'pages/dashboard/dashboard.js',

    'fundoReservaAreaGraph' => 'pages/inicial/fundoReservaAreaGraph.php', 
    
    'configuracoes' => 'pages/configuracoes/index.php',
    'configuracoesUpdateProc' => 'pages/configuracoes/updateConfigProc.php',

    'controlevisitantes' => 'pages/ctrlVagasVisitante/index.php',
    'controlevisitantesSlots' => 'pages/ctrlVagasVisitante/vagas/slots.json',
    'controlevisitantesSlotsUpdate' => 'pages/ctrlVagasVisitante/update_slot.php', 
    'liberarVaga' => 'pages/ctrlVagasVisitante/liberarVaga.php',   
    
    'controlevisitas' => 'pages/listaVisitantes/index.php',
    'visitantesLog' => 'pages/listaVisitantes/visitantesLog.php',    
    'controlevisitasDeleteVisitante' => 'pages/listaVisitantes/deleteVisitanteProc.php',
    'controlevisitasInserirVisitante' => 'pages/listaVisitantes/insertListaVisitantes.php',
    'controlevisitasInserirVisitanteProc' => 'pages/listaVisitantes/listVisitantesProc.php',
    'controlevisitaslistVisitanteVagaProc' => 'pages/listaVisitantes/listVisitanteVagaProc.php',
    'viewEditVisitante' => 'pages/listaVisitantes/viewEditVisitante.php',
    'viewEditVisitanteProc' => 'pages/listaVisitantes/viewEditVisitanteProc.php',  
    'viewVisitantesSalaoFestas' => 'pages/listaVisitantes/viewVisitantesSalaoFestas.php',  
    'registroEntradaVisitanteProc' => 'pages/listaVisitantes/registroEntradaVisitanteProc.php',    

    'listaVisitantesUsuario' => 'pages/listaVisitantesUsuario/index.php', 
    'deleteVisitanteUsuarioProc' => 'pages/listaVisitantesUsuario/deleteVisitanteUsuarioProc.php',
    'insertListaVisitantesUsuario' => 'pages/listaVisitantesUsuario/insertListaVisitantesUsuario.php',
    'listVisitantesUsuarioProc' => 'pages/listaVisitantesUsuario/listVisitantesUsuarioProc.php',
    'updateStatusCheckboxConviFesta' => 'pages/listaVisitantesUsuario/updateStatusCheckboxConviFesta.php',
    'cadVisitas' => 'pages/listaVisitantesUsuario/insertListaVisitantesExterno.php',
    'cadVisitasProc' => 'pages/listaVisitantesUsuario/insertListaVisitantesExternoProc.php',
    'cadVisitasFimPreCadastro' => 'pages/listaVisitantesUsuario/insertListaVisitantesExternoFimCad.php',
    'cadVisitasFimPreCadastroProcessado' => 'pages/listaVisitantesUsuario/insertListaVisitantesExternoErroProcessado.php',
    'cadVisitasFimPreCadastroInvalido' => 'pages/listaVisitantesUsuario/insertListaVisitantesExternoErroInvalido.php',

    'termoPrivacidadeSalvarAceite' => 'pages/termoPrivacidade/salvar_aceite.php',
    'termoPrivacidade' => 'pages/termoPrivacidade/termos.php',

    'chatbotApi' => 'pages/chatbot/chatbotApi.php',    

    'fornecedorAvaliacao' => 'pages/fornecedorAvaliacao/index.php',
    'updatePublicidade' => 'pages/fornecedorAvaliacao/updatePublicidade.php',
    'updatePublicidadeProc' => 'pages/fornecedorAvaliacao/updatePublicidadeProc.php',    
    'fornecedorAvaliacaoListaPublicacao' => 'pages/fornecedorAvaliacao/listaPublicacao.php',
    'fornecedorAvaliacaoInsertPublicidade' => 'pages/fornecedorAvaliacao/insertPublicidade.php',
    'fornecedorAvaliacaoInsertPublicidadeProc' => 'pages/fornecedorAvaliacao/insertPublicidadeProc.php',
    'fornecedorAvaliacaoUpdateStatusCheckboxPub' => 'pages/fornecedorAvaliacao/updateStatusCheckboxPub.php',
    'fornecedorAvaliacaoDeleteCampanhaProc' => 'pages/fornecedorAvaliacao/deleteCampanhaProc.php',
    'updateClickAudienciaProc' => 'pages/fornecedorAvaliacao/updateClickAudienciaProc.php',

    'instrucoesAdequacoes' => 'pages/instrucoesAdequacoes/index.php',
    'deleteArtigoProc' => 'pages/instrucoesAdequacoes/deleteArtigoProc.php',
    'insertArtigo' => 'pages/instrucoesAdequacoes/insertArtigo.php',
    'insertArtigoProc' => 'pages/instrucoesAdequacoes/insertArtigoProc.php',

    'listaConvidados' => 'pages/listaConvidados/index.php',
    'insertListaConvidados' => 'pages/listaConvidados/insertListaConvidados.php',
    'listConvidadosProc' => 'pages/listaConvidados/listConvidadosProc.php',  
    'updateStatusCheckbox' => 'pages/listaConvidados/updateStatusCheckbox.php',  
     
    'pendenciasAndamento' => 'pages/pendenciasAndamento/index.php',
    'pendenciasAndamentoDelete' => 'pages/pendenciasAndamento/deletePendenciaProc.php',
    'pendenciasAndamentoInsert' => 'pages/pendenciasAndamento/insertPendencia.php',
    'pendenciasAndamentoInsertProc' => 'pages/pendenciasAndamento/insertPendenciaProc.php',

    'uploadRelatorio' => 'pages/uploadRelatorio/index.php',
   
    'api_encomenda' => 'api_encomenda.php',    
    'encomendas' => 'pages/encomendas/index.php',
    'entregues' => 'pages/encomendas/entregues.php',
    'encomendasDelete' => 'pages/encomendas/deleteEncomendaProc.php',
    'insertPacoteProc' => 'pages/encomendas/insertPacoteProc.php',
    'updateStatusCheckboxDisponivel' => 'pages/encomendas/updateStatusCheckboxDisponivel.php',
    'updateStatusCheckboxEntregar' => 'pages/encomendas/updateStatusCheckboxEntregar.php',
    'updateStatusCheckbox' => 'pages/encomendas/updateStatusCheckbox.php',
    

    

    
    'fornecedores' => 'pages/adm_fornecedores/index.php',
    'insertFornecedor' => 'pages/adm_fornecedores/insertFornecedor.php',
    'insertFornecedorProc' => 'pages/adm_fornecedores/insertFornecedorProc.php',
    'deleteFornecedorProc' => 'pages/adm_fornecedores/deleteFornecedorProc.php',
    'viewEditFornecedor' => 'pages/adm_fornecedores/viewEditFornecedor.php',
    'editFornecedorProc' => 'pages/adm_fornecedores/editFornecedorProc.php',  
    'insertFornecedorRateProc' => 'pages/adm_fornecedores/insertFornecedorRateProc.php',  

    'funcionario' => 'pages/adm_funcionarios/index.php', 
    'insertFuncionario' => 'pages/adm_funcionarios/insertFuncionario.php', 
    'insertFuncionarioProc' => 'pages/adm_funcionarios/insertFuncionarioProc.php', 
    'deleteFuncionarioProc' => 'pages/adm_funcionarios/deleteFuncionarioProc.php', 
    'editFuncionario' => 'pages/adm_funcionarios/editFuncionario.php', 
    'editFuncionarioProc' => 'pages/adm_funcionarios/editFuncionarioProc.php', 
    'insertFuncionarioProcEmailCheck' => 'pages/adm_funcionarios/insertFuncionarioProcEmailCheck.php', 

    'tasks' => 'pages/adm_tasks/index.php', 
    'insertTasks' => 'pages/adm_tasks/insertTasks.php', 
    'insertTaskProc' => 'pages/adm_tasks/insertTaskProc.php', 
    'deleteTaskProc' => 'pages/adm_tasks/deleteTaskProc.php', 
    'viewEditTask' => 'pages/adm_tasks/viewEditTask.php', 
    'editTaskProc' => 'pages/adm_tasks/editTaskProc.php', 
    'deleteModelo' => 'pages/adm_tasks/deleteModeloProc.php',  
    'insertOsAutomatico' => 'pages/adm_tasks/insertOsAutomatico.php', 
    'deleteOsAutomatico' => 'pages/adm_tasks/deleteOsAutomatico.php', 

    'adm_orcamentos' => 'pages/adm_orcamentos/index.php',
    'insertOrcamentoProc' => 'pages/adm_tasks/insertOrcamentoProc.php',
    'viewOrcamentoRequests' => 'pages/adm_tasks/viewOrcamentoRequests.php',
    'formularioPropostaSindico' => 'pages/adm_tasks/formularioPropostaSindico.php', 
    'insertPropostaProc' => 'pages/adm_tasks/insertPropostaProc.php', 

    'formularioProposta' => 'pages/adm_tasks/formularioProposta.php',  
    'viewOrcamentoSolicitacaoEmailProc' => 'pages/adm_tasks/viewOrcamentoSolicitacaoEmailProc.php', 
    'deleteProposta' => 'pages/adm_tasks/deleteProposta.php', 
    'editOrcamentoProc' => 'pages/adm_tasks/editOrcamentoProc.php', 
    'formularioPropostaAvisoEmAnalise' => 'pages/adm_tasks/formularioPropostaAvisoEmAnalise.php', 
    'formularioPropostaAvisoCancelada' => 'pages/adm_tasks/formularioPropostaAvisoCancelada.php', 
    
    'editProposta' => 'pages/adm_tasks/editProposta.php', 
    'formularioPropostaAgradecimento' => 'pages/adm_tasks/formularioPropostaAgradecimento.php', 
    'viewOrcamentoSolicitacaoEmailRespostaProc' => 'pages/adm_tasks/viewOrcamentoSolicitacaoEmailRespostaProc.php', 
    'editPropostaStatus' => 'pages/adm_tasks/editPropostaStatus.php', 
    
    'adm_agendador_tarefas' => 'pages/adm_agendador_tarefas/index.php', 
    'dash_agendador_tarefas_js' => 'pages/adm_agendador_tarefas/dash_agendador_tarefas.js', 
    'insertOSProc' => 'pages/adm_agendador_tarefas/insertOSProc.php', 
    'deleteOSProc' => 'pages/adm_agendador_tarefas/deleteOSProc.php', 
    'updateOSProc' => 'pages/adm_agendador_tarefas/updateOSProc.php', 

    'adm_relatorio_administrativo' => 'pages/adm_dashboard_administracao/index.php',
    'dashboard_dashboard_radial_radar' => 'pages/adm_dashboard_administracao/dashboard_dashboard_radial_radar.js',
    'ocupacaoSemanal' => 'pages/adm_dashboard_administracao/ocupacaoSemanal.js',
    'dashboard_dashboard_pie' => 'pages/adm_dashboard_administracao/dashboard_dashboard_pie.js',

     'adm_funcoes_sistemas' => 'pages/adm_funcoes_sistemas/index.php',
     'updateStatusCheckbox' => 'pages/adm_funcoes_sistemas/updateStatusCheckbox.php',

     'reclamacoes_sugestoes' => 'pages/reclamacoes_sugestoes/index.php',
    
    'adm_tutoriais' => 'pages/adm_tutoriais/index.php',
    'insertGuiaRapidoProc' => 'pages/adm_tutoriais/insertGuiaRapidoProc.php',
    'updateStatusGuiaCheckbox' => 'pages/adm_tutoriais/updateStatusGuiaCheckbox.php',
    'deleteGuiaProc' => 'pages/adm_tutoriais/deleteGuiaProc.php',
    'updateGuiaProc' => 'pages/adm_tutoriais/updateGuiaProc.php',
    
    'suporte' => 'pages/suporte/index.php',
    'insertTicketProc' => 'pages/suporte/insertTicketProc.php',
    'deleteTicketProc' => 'pages/suporte/deleteTicketProc.php',

    'resumoFinanceiro' => 'pages/adm_res_financeiro/index.php',
    'insertResMesProc' => 'pages/adm_res_financeiro/insertResMesProc.php',
    'insertResMes' => 'pages/adm_res_financeiro/insertResMes.php',
    'deleteResMesProc' => 'pages/adm_res_financeiro/deleteResMesProc.php',

    'pub_dashboard_parceiro' => 'pages/pub_dashboard_parceiro/index.php',
    'dashboard_dashboard_radial_radar_visitas_dia' => 'pages/pub_dashboard_parceiro/dashboard_dashboard_radial_radar.js',
    'dashboard_visitas_dia' => 'pages/pub_dashboard_parceiro/dashboard_visitas_dia.js', 
    'graphVisitasDia' => 'pages/pub_dashboard_parceiro/graphVisitasDia.php',



    //ADMINISTRAÇÃO
    'login' => 'pages/login/index.php',
    'logoff' => 'pages/logoff/index.php',
    'errors' => 'pages/errors/index.php',
    'inicial' => 'pages/inicial/index.php',
    
    'listaClientes' => 'pages/ADM_listaClientes/index.php',
    'insertCliente' => 'pages/ADM_listaClientes/insertCliente.php',
    'insertClienteProc' => 'pages/ADM_listaClientes/insertClienteProc.php',
    'deleteClienteProc' => 'pages/ADM_listaClientes/deleteClienteProc.php',
    'editClienteProc' => 'pages/ADM_listaClientes/editClienteProc.php',
    'editCliente' => 'pages/ADM_listaClientes/editCliente.php',

    'listaCondominio' => 'pages/ADM_listaCondominios/index.php',
    'insertCondominio' => 'pages/ADM_listaCondominios/insertCondominio.php',
    'insertCondominioProc' => 'pages/ADM_listaCondominios/insertCondominioProc.php',
    'deleteCondominioProc' => 'pages/ADM_listaCondominios/deleteCondominioProc.php',
    'editCondominioProc' => 'pages/ADM_listaCondominios/editCondominioProc.php',
    'editCondominio' => 'pages/ADM_listaCondominios/editCondominio.php',
   
    'planosPublicidade' => 'pages/ADM_vendas/index.php',
    

    


    




];
?>