<?php


namespace App\Tests\Utils;

use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

trait ApiUtilsTrait {

    /** @var boolean $contentTypeJson */
    private $contentTypeJson = false;

    /** @var string $token */
    private $token = null;

    /** @var KernelBrowser $client  */
    private $client = null;

    /**
     * @param string $email
     * @param string $password
     * @param string $realm
     * @return $this
     */
    protected function login($email = 'test@example.com', $password = 'secret', $realm = 'default'): self {
        $credentials = ['username' => $email, 'password' => $password, 'realm' => $realm];
        $resp = $this->json()->request('POST', '/app/token', $credentials);
        if($resp->getStatusCode() != Response::HTTP_OK)
            throw new AuthenticationException("Authentication for user $email with password $password failed");
        $data = json_decode($resp->getContent());
        $this->token = $data->token;
        return $this;
    }

    /**
     * @return $this
     */
    protected function logout(): self {
        return $this->setToken(null);
    }

    /**
     * @return string
     */
    protected function getUserToken(){
        return $_ENV['USER_TOKEN'];
    }

    /**
     * @return string
     */
    protected function getAdminToken(){
        return $_ENV['ADMIN_TOKEN'];
    }

    /**
     * @param $token
     * @return $this
     */
    protected function setToken($token){
        $this->token = $token;
        return $this;
    }

    /**
     * @return $this
     */
    protected function json(): self {
        $this->contentTypeJson = true;
        return $this;
    }
    /**
     * @param KernelBrowser $client
     * @return KernelBrowser
     */
    private function addAuthenticationToken(KernelBrowser $client): KernelBrowser {
        if($this->token)
            $client->setServerParameter('HTTP_Authorization', "Bearer $this->token");
        return $client;
    }

    /**
     * @param KernelBrowser $client
     * @return KernelBrowser
     */
    private function addJsonHeaders(KernelBrowser $client): KernelBrowser {
        $client->setServerParameter('HTTP_Accept', 'application/ld+json');
        if($this->contentTypeJson) {
            $client->setServerParameter('CONTENT_TYPE', "application/ld+json");
        }
        return $client;
    }

    /**
     * @param $method
     * @param $uri
     * @param null $json
     * @return Response
     */
    protected function request($method, $uri, $json = null): Response
    {
        $client = $this->createApiClient();
        if (!$json) {
            $client->request($method, $uri);
        }
        else {
            $client->request($method, $uri, [], [], [], json_encode($json));
        }
        return $client->getResponse();
    }

    /**
     * @param $method
     * @param $uri
     * @param $files
     * @return Response
     */
    protected function upload($method, $uri, $files): Response {
        $client = $this->createApiClient();

        $files_upload = [];
        foreach ($files as $param => $fileName) {
            $projectDir = $client
                ->getContainer()
                ->getParameter('kernel.project_dir');
            $assetFilename = $projectDir . '/fixtures/assets/' . $fileName;
            $uploadFilename = sys_get_temp_dir() . '/' . uniqid('upload_') . $fileName;
            copy($assetFilename, $uploadFilename);
            $uf = new UploadedFile(
                $uploadFilename,
                'image.png',
                'image/png',
                null
            );
            $files_upload[$param] = $uf;
        }
        $client->request(
            $method,
            $uri,
            [],
            $files_upload
        );
        return $client->getResponse();
    }


    /**
     * @return KernelBrowser
     */
    protected function createApiClient(): KernelBrowser {
        self::ensureKernelShutdown();
        $client = self::createClient();
        $client = $this->addJsonHeaders($client);
        $client = $this->addAuthenticationToken($client);
        $this->client = $client;
        return $client;
    }

    /**
     * @return KernelBrowser
     */
    protected function getApiClient(): KernelBrowser {
        if (!$this->client) return $this->createApiClient();
        return $this->client;
    }
}