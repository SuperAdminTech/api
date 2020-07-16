<?php


namespace App\Tests\Utils;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

trait ApiUtilsTrait {

    /** @var boolean $contentTypeJson */
    private $contentTypeJson = false;

    /** @var string $token */
    private $token = null;

    /**
     * @param string $email
     * @param string $password
     * @return $this
     * @throws AuthenticationException
     */
    protected function login($email = 'test@example.com', $password = 'secret'): self {
        $client = static::createClient();
        $credentials = ['email' => $email, 'password' => $password];
        $jsonCredentials = json_encode($credentials);
        $client->request('POST', '/login', [], [], ['CONTENT_TYPE' => 'application/ld+json'], $jsonCredentials);
        if($client->getResponse()->getStatusCode() != 200)
            throw new AuthenticationException("Authentication for user $email with password $password failed");
        $data = json_decode($client->getResponse()->getContent());
        $this->token = $data->token;
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
        if($this->token != null)
            $client->setServerParameter('HTTP_Authorization', "Bearer $this->token");
        return $client;
    }

    /**
     * @param KernelBrowser $client
     * @return KernelBrowser
     */
    private function addJsonHeaders(KernelBrowser $client): KernelBrowser {
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
    protected function request($method, $uri, $json = null): Response {
        $client = $this->makeClient();
        $client->setServerParameter('HTTP_Accept', 'application/ld+json');
        if($json == null)
            $client->request($method, $uri);
        else
            $client->request($method, $uri, [], [], [], json_encode($json));
        return $client->getResponse();
    }

    /**
     * @param $method
     * @param $uri
     * @param $files
     * @return Response
     */
    protected function upload($method, $uri, $files): Response {
        $client = $this->makeClient();
        $client->setServerParameter('HTTP_Accept', 'application/ld+json');

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
    protected function makeClient(): KernelBrowser {
        $client = self::createClient();
        $client = $this->addJsonHeaders($client);
        $client = $this->addAuthenticationToken($client);
        return $client;
    }
}