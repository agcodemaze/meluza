<?php

namespace App\Controller\Pages;

use App\Utils\View;
use App\Utils\Auth;
use App\Model\Entity\S3 as S3Entity;

class S3Controller
{
    private S3Entity $s3;

    /**
     * Verificar como proteger a verificacao de auth. pq com o anamnese nao tem
     * autenticação e nao envia para o s3 se tiver com proteção do auth
     */
    public function __construct()
    {
       // if(!TENANCY_ID) { //se não tiver confirmação de auth. 
        //    Auth::authCheck(); 
       // }

        $ENV_AWS_BUCKET = $_ENV['ENV_AWS_BUCKET'] ?? getenv('ENV_AWS_BUCKET') ?? '';
        $ENV_AWS_REGION = $_ENV['ENV_AWS_REGION'] ?? getenv('ENV_AWS_REGION') ?? '';
        $ENV_AWS_KEY = $_ENV['ENV_AWS_KEY'] ?? getenv('ENV_AWS_KEY') ?? '';
        $ENV_AWS_SECRET = $_ENV['ENV_AWS_SECRET'] ?? getenv('ENV_AWS_SECRET') ?? '';

        $this->s3 = new S3Entity(
            $ENV_AWS_BUCKET, 
            $ENV_AWS_REGION, 
            $ENV_AWS_KEY, 
            $ENV_AWS_SECRET
        );
    }

    public function uploadFile($localFilePath = null, $remoteKey = null)
    {
        if (!$localFilePath) {
            if (!isset($_FILES['arquivo']) || $_FILES['arquivo']['error'] !== UPLOAD_ERR_OK) {
                return json_encode(['error' => 'Arquivo não enviado ou erro no upload']);
            }

            $localFilePath = $_FILES['arquivo']['tmp_name'];
            $remoteKey = "uploads/" . basename($_FILES['arquivo']['name']);
        }

        try {
            $link = $this->s3->upload($localFilePath, $remoteKey, 'private');
            return json_encode(['success' => true, 'link' => $link]);
        } catch (\Exception $e) {
            return json_encode(['error' => $e->getMessage()]);
        }
    }


    public function getDownloadLink(string $key)
    {
        try {
            $link = $this->s3->getPresignedUrl($key, '+30 minutes');
            return json_encode(['success' => true, 'link' => $link]);
        } catch (\Exception $e) {
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    public function deleteFile(string $key)
    {
        try {
            $this->s3->delete($key);
            return json_encode([
                'success' => true,
                'message' => "Arquivo '{$key}' excluído com sucesso!"
            ]);
        } catch (\Exception $e) {
            return json_encode([
                'error' => $e->getMessage()
            ]);
        }
    }
    
}
