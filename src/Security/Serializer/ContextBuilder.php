<?php


namespace App\Security\Serializer;


use ApiPlatform\Core\Exception\RuntimeException;
use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ContextBuilder implements SerializerContextBuilderInterface {

    /** @var SerializerContextBuilderInterface */
    private $decorated;

    /** @var AuthorizationCheckerInterface  */
    private $authorizationChecker;

    /**
     * ContextBuilder constructor.
     * @param SerializerContextBuilderInterface $decorated
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(SerializerContextBuilderInterface $decorated, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->decorated = $decorated;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @inheritDoc
     */
    public function createFromRequest(Request $request, bool $normalization, array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);

        $context['enable_max_depth'] = true;

        if (!isset($context['groups'])) $context['groups'] = [];

        $context['groups'][] = $normalization? 'public:read': 'public:write';
        if ($this->authorizationChecker->isGranted('ROLE_USER')) {
            $context['groups'][] = $normalization? 'user:read': 'user:write';
        }
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $context['groups'][] = $normalization? 'admin:read': 'admin:write';
        }
        if ($this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN')) {
            $context['groups'][] = $normalization? 'super:read': 'super:write';
        }

        return $context;
    }
}