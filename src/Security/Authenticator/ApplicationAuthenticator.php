<?php


namespace App\Security\Authenticator;

use App\Entity\Application;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class ApplicationAuthenticator extends AbstractGuardAuthenticator {

    /** @var EntityManagerInterface $em */
    private $em;

    /** @var UserPasswordEncoderInterface $encoder */
    private $encoder;

    /** @var AuthenticationSuccessHandlerInterface $successHandler */
    private $successHandler;

    /** @var AuthenticationFailureHandlerInterface $failureHandler */
    private $failureHandler;

    /**
     * ApplicationAuthenticator constructor.
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     * @param AuthenticationSuccessHandlerInterface $successHandler
     * @param AuthenticationFailureHandlerInterface $failureHandler
     */
    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, AuthenticationSuccessHandlerInterface $successHandler, AuthenticationFailureHandlerInterface $failureHandler)
    {
        $this->em = $em;
        $this->encoder = $encoder;
        $this->successHandler = $successHandler;
        $this->failureHandler = $failureHandler;
    }


    /**
     * @inheritDoc
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'message' => 'Authentication Required'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @inheritDoc
     */
    public function supports(Request $request)
    {
        return $request->getPathInfo() === "/public/token" &&
            $request->getMethod() === 'POST' &&
            in_array($request->getContentType(), ['json', 'jsonld']);
    }

    /**
     * @inheritDoc
     */
    public function getCredentials(Request $request)
    {
        return json_decode($request->getContent());
    }

    /**
     * @inheritDoc
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (!$credentials) return null;
        $application = $this->em
            ->getRepository(Application::class)
            ->findOneBy(['realm' => $credentials->realm]);

        $users = $this->em->getRepository(User::class)->findBy(['username' => $credentials->username]);
        /** @var User $user */
        foreach ($users as $user) {
            foreach ($user->permissions as $permission){
                if ($permission->account->application->id == $application->id){
                    return $user;
                }
            }
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->encoder->isPasswordValid($user, $credentials->password);
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return $this->failureHandler->onAuthenticationFailure($request, $exception);
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return $this->successHandler->onAuthenticationSuccess($request, $token);
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function supportsRememberMe()
    {
        return false;
    }
}