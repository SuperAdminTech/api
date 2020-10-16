<?php


namespace App\DataTransformer;


use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\VerifyEmail;
use App\Entity\Account;
use App\Entity\Application;
use App\Entity\Permission;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class VerifyEmailDataTransformer
 * @package App\DataTransformer
 */
class VerifyEmailDataTransformer implements DataTransformerInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * PermissionWithUsernameDataTransformer constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * @inheritDoc
     */
    public function transform($object, string $to, array $context = [])
    {
        /** @var User $user */
        $user = $this->em
            ->getRepository(User::class)
            ->findOneBy(['email_verification_code' => $object->code]);
        if(!$user) throw new HttpException(Response::HTTP_NOT_FOUND, "Provided code not found");

        $user->email_validated = true;
        return $user;
    }

    /**
     * @inheritDoc
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        // in the case of an input, the value given here is an array (the JSON decoded).
        // if it's a book we transformed the data already
        if ($data instanceof User) {
            return false;
        }

        return User::class === $to && VerifyEmail::class == $context['input']['class'];
    }
}