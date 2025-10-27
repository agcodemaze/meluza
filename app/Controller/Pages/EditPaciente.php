<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Utils\Auth;
use \App\Model\Entity\Organization;
use \App\Model\Entity\Paciente;
use Dompdf\Dompdf;

class EditPaciente extends Page {

    public static function editCadPaciente($id) {

        Auth::authCheck(); //verifica se já tem login válido (jwt)
        $objOrganization = new Organization();
        $clinicaInfo = $objOrganization->getConfiguracoes(TENANCY_ID);

        $pacientesObj = new Paciente();
        $convenios = $pacientesObj->getConvenios(); 

        $pacienteInfo = $pacientesObj->getPacientesById(TENANCY_ID, $id); 
        $pacienteInfoConsultas = $pacientesObj->getTimelinePacientesConsultasById(TENANCY_ID, $id);

        // --- BUSCA ANAMNESE ---
        $anamneses = $pacientesObj->getAnamnesesRespostaModeloByIdPaciente(TENANCY_ID,$id);
        $modelo = json_decode($anamneses["ANM_JSON_MODELO"], true);
        $respostas = json_decode($anamneses["ANR_JSON_RESPOSTAS"], true);

        // Junta todas as perguntas
        $todas_perguntas = [];
        foreach ($modelo['secoes'] as $secao) {
            $todas_perguntas = array_merge($todas_perguntas, $secao['perguntas']);
        }

        // --- MONTA HTML DO PDF ---
        $logoFile = __DIR__ . '/../../../public/assets/images/SmileCopilot-Logo_139x28.png';
        if (file_exists($logoFile)) {
            $logoData = base64_encode(file_get_contents($logoFile));           
        }

        // Rodapé com infos da assinatura eletrônica
        $assinaturaData = date('d/m/Y \à\s H:i'); 
        $codigoAuth = strtoupper(substr(bin2hex(random_bytes(6)),0,12)); 
        $codigoFormatado = preg_replace('/(.{4})(?!$)/', '$1-', strtoupper($codigoAuth));
        $verificarUrl = 'https://app.smilecopilot.com/verificar?c=' . urlencode($codigoFormatado);

        //cabecalho informações
        $nomeClinica = $clinicaInfo["CFG_DCNOME_CLINICA"];
        $enderecoClinica = $clinicaInfo["CFG_DCENDERECO_CLINICA"]." ".$clinicaInfo["CFG_DCNUMERO_CLINICA"] ." - ".$clinicaInfo["CFG_DCBAIRRO_CLINICA"];
        $cidadeEstadoClinica = $clinicaInfo["CFG_DCCIDADE_CLINICA"]."/".$clinicaInfo["CFG_DCESTADO_CLINICA"]." - ".$clinicaInfo["CFG_DCPAIS_CLINICA"];

        $html = '
            <html>
            <head>
                <meta charset="UTF-8">
                <style>
                    body { font-family: Arial, sans-serif; color: #333; margin: 20px; line-height: 1.5; }

                    /* Cabeçalho */
                    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 1px solid #ccc; padding-bottom: 10px; }
                    .clinic-info { text-align: left; }
                    .clinic-name { font-size: 20px; font-weight: bold; color: #000; margin-bottom: 2px; }
                    .clinic-address { font-size: 14px; color: #555; }
                    .logo { max-height: 50px; }

                    /* Título principal */
                    h1 { background-color: #6b6d6dff; color: #fff; padding: 15px; border-radius: 5px; text-align: center; margin-bottom: 20px; font-size: 18px; }

                    /* Info do paciente e data */
                    .info { margin-bottom: 20px; background-color: #f9f9f9; padding: 10px 15px; border-radius: 5px; border: 1px solid #ddd; }
                    .info div { margin-bottom: 5px; font-size: 14px; }
                    .info span.label { font-weight: bold; color: #0d6efd; }

                    /* Seções e perguntas */
                    .section { margin-bottom: 20px; padding: 10px; border-left: 4px solid #0dcaf0; background-color: #fefefe; border-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
                    .question { font-weight: bold; margin-top: 5px; color: #0d6efd; }
                    .answer { background-color: #e7f7ff; padding: 8px 12px; border-radius: 4px; margin-bottom: 8px; display: block; }

                    /* Layout em colunas */
                    .row { display: flex; flex-wrap: wrap; margin-top: 10px; }
                    .col { flex: 1; min-width: 250px; padding-right: 15px; }

                    /* Rodapé */
                    .footer { text-align: center; font-size: 12px; color: #888; margin-top: 30px; border-top: 1px solid #ccc; padding-top: 10px; }
                    .signature { margin-top: 40px; font-size: 14px; font-weight: bold; }
                </style>
            </head>
            <body>
                <!-- Cabeçalho -->
            <div class="header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 1px solid #ccc; padding-bottom: 10px;">
                <!-- Bloco da clínica à esquerda -->
                <div class="clinic-info" style="text-align: left;">
                    <div class="clinic-name" style="font-size: 20px; font-weight: bold; color: #000; margin-bottom: 2px;">'.$nomeClinica.'</div>
                    <div class="clinic-address" style="font-size: 14px; color: #555;">'.$enderecoClinica.'</div>
                    <div class="clinic-address" style="font-size: 14px; color: #555;">'.$cidadeEstadoClinica.'</div>
                </div>

                <!-- Logo do sistema à direita -->
                <div class="logo-container" style="text-align: right; flex-shrink: 0;">
                    <img src="data:image/png;base64,'.$logoData.'" class="logo" alt="Logo do Sistema" style="max-height: 50px;">
                </div>
            </div>

                <!-- Título -->
                <h1>Anamnese</h1>

                <!-- Informações do paciente e data -->
                <div class="info">
                    <div><span class="label">Paciente:</span> '.$pacienteInfo[0]['PAC_DCNOME'].'</div>
                    <div><span class="label">Data de Nascimento:</span> '.$pacienteInfo[0]["PAC_DTDATANASC"].'</div>
                    <div><span class="label">CPF:</span> '.($pacienteInfo[0]["PAC_DCCPF"] ?? "Não informado").'</div>
                    <div><span class="label">Data de Geração:</span> '.date("d/m/Y H:i").'</div>
                </div>

                <!-- Perguntas e respostas -->
                <div class="row">
            ';

            $metade = ceil(count($todas_perguntas) / 2);
            $colunas = array_chunk($todas_perguntas, $metade);
            foreach ($colunas as $col) {
                $html .= '<div class="col">';
                foreach ($col as $p) {
                    $id = $p['id'];
                    $resposta = $respostas[$id] ?? 'Não informado';
                    $html .= '<div class="section">';
                    $html .= '<div class="question">'.htmlspecialchars($p['pergunta']).'</div>';
                    $html .= '<div class="answer">'.htmlspecialchars($resposta).'</div>';
                    $html .= '</div>';
                }
                $html .= '</div>';
            }

            $html .= '
                </div>

                <!-- Rodapé -->
                <div style="margin-top:30px; padding-top:10px; border-top:1px solid #ddd; text-align:center; font-family: Arial, sans-serif; font-size:11px; color:#555;">
                  <div>Documento assinado digitalmente em '.$assinaturaData.'</div>
                  <div style="margin-top:6px;">
                    <strong>Código de autenticação:</strong>
                    <span style="display:inline-block; background:#f5f5f7; border:1px solid #e1e1e5; padding:4px 8px; border-radius:4px; font-family:monospace; color:#222;">
                      '.$codigoFormatado.'
                    </span>
                  </div>
                  <div style="margin-top:6px; color:#777;">
                    A validade pode em: <a href="'.$verificarUrl.'" style="color:#0d6efd; text-decoration:none;">'.$verificarUrl.'</a>
                  </div>
                </div>
            </body>
            </html>
            ';



        // --- GERA PDF ---
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $pdfOutput = $dompdf->output();
        
        $pdfDir = __DIR__ . '/../../../public/tmp';
        if (!is_dir($pdfDir)) {
            mkdir($pdfDir, 0777, true);
        }

        $pdfName = TENANCY_ID."_".$anamneses["ANR_DCCOD_AUTENTICACAO"].".pdf";
        $pdfPath = $pdfDir . '/'.$pdfName;
        file_put_contents($pdfPath, $pdfOutput);

        $pdfUrl= "https://app.smilecopilot.com/public/tmp/$pdfName ";

        // Caminho que o navegador vai acessar:

        // --- COMPONENTES SCRIPTS ---
        $componentsScriptsHeader = '
            <script src="'.ASSETS_PATH.'js/hyper-config.js"></script>
            <link href="'.ASSETS_PATH.'css/vendor.min.css" rel="stylesheet" type="text/css" />
            <link href="'.ASSETS_PATH.'css/app-saas.min.css" rel="stylesheet" type="text/css" id="app-style" />
            <link href="'.ASSETS_PATH.'css/icons.min.css" rel="stylesheet" type="text/css" />
            <script src="'.ASSETS_PATH.'js/serviceworkerpwa.js"></script>
            <link rel="manifest" href="/manifest.json">
            <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
        ';

        $componentsScriptsFooter = '
            <script src="'.ASSETS_PATH.'js/vendor.min.js"></script>            
            <script src="'.ASSETS_PATH.'js/app.min.js"></script>
            <script src="'.ASSETS_PATH.'vendor/jquery-mask-plugin/jquery.mask.min.js"></script>
        ';

        // --- VIEW ---
        $content = [
            'title' => $objOrganization->title,
            'description' => $objOrganization->description,
            'site' => $objOrganization->site,
            'componentsScriptsHeader' => $componentsScriptsHeader,
            'componentsScriptsFooter' => $componentsScriptsFooter,
            'convenios' => $convenios,
            'pacienteInfo' => $pacienteInfo,
            'pacienteInfoConsultas' => $pacienteInfoConsultas,
            'pdfUrl' => $pdfUrl
        ]; 

        return self::getPage('pages/vw_editpaciente', $content);
    }

}
