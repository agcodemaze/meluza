<?php 

namespace App\Model\Entity;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class S3 
{
    private S3Client $client;
    private string $bucket;

    /**
     * Construtor: inicializa o S3Client
     * @param string $bucket Nome do bucket S3
     * @param string $region Região do bucket (ex: sa-east-1)
     * @param string $key Access Key
     * @param string $secret Secret Key
     */
    public function __construct(string $bucket, string $region, string $key, string $secret)
    {
        $this->bucket = $bucket;
        $this->client = new S3Client([
            'version' => 'latest',
            'region'  => $region,
            'credentials' => [
                'key'    => $key,
                'secret' => $secret,
            ],
        ]);
    }

        /**
     * Lista todos os buckets disponíveis na conta AWS
     * @return array
     */
    public function listBuckets(): array
    {
        try {
            $result = $this->client->listBuckets();
            return $result->toArray();
        } catch (AwsException $e) {
            throw new \Exception("Erro ao listar buckets: " . $e->getAwsErrorMessage());
        }
    }

    /**
     * Faz upload de um arquivo para o S3
     * @param string $sourceFile Caminho do arquivo local
     * @param string $key Nome do arquivo no S3 (pode incluir pastas)
     * @param string $acl Permissão ('public-read' ou 'private')
     * @return string URL do arquivo (link público ou link temporário)
     */
    public function upload(string $sourceFile, string $key, string $acl = 'private'): string
    {
        try {
            $result = $this->client->putObject([
                'Bucket' => $this->bucket,
                'Key'    => $key,
                'SourceFile' => $sourceFile,
                'ACL'    => $acl,
            ]);

            if ($acl === 'public-read') {
                return $result['ObjectURL'];
            }

            // Retorna link temporário de 1 hora se privado
            return $this->getPresignedUrl($key, '+1 hour');

        } catch (AwsException $e) {
            throw new \Exception("Erro ao enviar arquivo: " . $e->getMessage());
        }
    }

    /**
     * Gera link temporário para download de arquivo privado
     * @param string $key Nome do arquivo no S3
     * @param string $expires Tempo de expiração (ex: '+20 minutes', '+1 hour')
     * @return string URL temporária
     */
    public function getPresignedUrl(string $key, string $expires = '+5 days'): string
    {
        try {
            $cmd = $this->client->getCommand('GetObject', [
                'Bucket' => $this->bucket,
                'Key'    => $key
            ]);

            $request = $this->client->createPresignedRequest($cmd, $expires);
            return (string) $request->getUri();

        } catch (AwsException $e) {
            throw new \Exception("Erro ao gerar link presign: " . $e->getMessage());
        }
    }

    /**
     * Exclui um arquivo do bucket S3
     * @param string $key Caminho/nome do arquivo no bucket
     * @return bool
     */
    public function delete(string $key): bool
    {
        try {
            $this->client->deleteObject([
                'Bucket' => $this->bucket,
                'Key'    => $key
            ]);
            return true;
        } catch (AwsException $e) {
            throw new \Exception("Erro ao excluir arquivo: " . $e->getAwsErrorMessage());
        }
    }
}
