<?php


namespace App\Tests\Utils;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

trait ApiUtilsTrait {

    /** @var boolean $acceptJson */
    private $acceptJson = false;

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
        $resp = $this->json()->request('POST', '/public/token', $credentials);
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
        $this->acceptJson = true;
        return $this;
    }

    /**
     * @return string[]
     */
    private function getAuthenticationHeaders(): array {
        if(!$this->token) return [];
        return ['HTTP_Authorization' => "Bearer $this->token"];
    }

    /**
     * @param null $data
     * @return string[]
     */
    private function getJsonHeaders($data = null): array {
        $headers = [];
        if($this->acceptJson) $headers = ['HTTP_Accept' => 'application/ld+json'];
        if ($data) $headers['CONTENT_TYPE'] = "application/ld+json";
        return $headers;
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

        $server = $this->getJsonHeaders($json);
        $auth = $this->getAuthenticationHeaders();
        $headers = array_merge($server, $auth);

        if ($json) $client->request($method, $uri, [], [], $headers, json_encode($json));
        else $client->request($method, $uri, [], [], $headers);

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
        $this->client = $client;
        return $client;
    }

    /**
     * @return KernelBrowser
     */
    protected function getApiClient(): KernelBrowser {
        if(!$this->client) $this->createApiClient();
        return $this->client;
    }




}