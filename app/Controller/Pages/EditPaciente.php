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
        $html = '
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; color: #333; margin: 20px; }
                h1 { background-color: #73e9ff; color: #fff; padding: 15px; border-radius: 5px; }
                .section { margin-bottom: 25px; }
                .question { font-weight: bold; margin-top: 10px; }
                .answer { background-color: #f5f5f5; padding: 8px 10px; border-radius: 4px; margin-bottom: 8px; display: inline-block; }
                .row { display: flex; flex-wrap: wrap; }
                .col { flex: 1; min-width: 250px; padding-right: 15px; }
            </style>
        </head>
        <body>
            <h1>Anamnese Completa</h1>
            <p>Histórico de saúde geral e bucal do paciente.</p>
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
                $html .= '<div class="question">' . htmlspecialchars($p['pergunta']) . '</div>';
                $html .= '<div class="answer">' . htmlspecialchars($resposta) . '</div>';
                $html .= '</div>';
            }
            $html .= '</div>';
        }

        $html .= '
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

        $pdfPath = $pdfDir . '/anamnese.pdf';
        file_put_contents($pdfPath, $pdfOutput);

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
            'pdfPath' => $pdfPath
        ]; 

        return self::getPage('pages/vw_editpaciente', $content);
    }

}
