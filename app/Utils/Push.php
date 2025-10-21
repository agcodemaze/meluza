<?php
namespace App\Utils;

class Push {

        public static function sendPushEncomendaByUserId($title,$message,$buttonDesc,$externalUserId) 
        {
            $content = array(
                "en" => $message
            );

            $UrlRedir = "https://app.smilecopilot.com";
            $buttonDesc = "Clique aqui para retirar.";

            $externalUserIdsArray = array_map(
                fn($id) => trim($id, '" '),
                explode(',', $externalUserId)
            );
       
            $data = [
                "app_id" => "fe361fb5-f7a3-4a47-b210-d74a11802559",
                "headings" => [
                    "pt" => $title,
                    "en" => $title
                ],
                "contents" => [
                    "pt" => $message,
                    "en" => $message
                ],
                "url" => "$UrlRedir",
                "web_buttons" => [
                    [
                        "id" => "visit-site",
                        "text" => "$buttonDesc",
                        "icon" => "https://app.smilecopilot.com/public/assets/images/SmileCopilot-LogoMin_43x28.png",
                        "url" => "$UrlRedir"
                    ]
                ],
                "include_aliases" => [
                    "external_id" => $externalUserIdsArray
                ],
                "target_channel" => "push"
            ];

            $ONESIGNALKEY = $_ENV['ENV_ONESIGNAL_APIKEY'] ?? getenv('ENV_ONESIGNAL_APIKEY') ?? '';
        
            $fields = json_encode($data);
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Authorization: Basic ' . $ONESIGNALKEY
            ));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
        
            return [
                'success' => ($httpCode === 200),
                'http_code' => $httpCode,
                'response' => json_decode($response, true),
                'error' => $error
            ];        
        }
}
