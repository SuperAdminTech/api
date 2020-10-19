<?php


namespace App\DataTransformer;


use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\RecoverPassword;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class RecoverPasswordDataTransformer
 * @package App\DataTransformer
 */
class RecoverPasswordDataTransformer implements DataTransformerInterface
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
            ->findOneBy(['recover_password_code' => $object->code]);
        if (!$user) {
            throw new HttpException(Response::HTTP_NOT_FOUND, "Provided code not found.");
        }

        if (!$user->recover_password_requested_at) {
            throw new HttpException(Response::HTTP_NOT_FOUND, "Provided code expired.");
        }
        $req_time = $user->recover_password_requested_at->getTimestamp();
        $now = (new \DateTime())->getTimestamp();
        if ($now - $req_time > User::RECOVER_PASSWORD_EXPIRES_IN) {
            throw new HttpException(Response::HTTP_NOT_FOUND, "Provided code expired.");
        }

        $user->recover_password_code = null;
        $user->recover_password_requested_at = null;
        // validates email too, so user clicked a secret code in the email as well.
        $user->email_validated = true;
        $user->plain_password = $object->password;
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

        return User::class === $to && RecoverPassword::class == $context['input']['class'];
    }
}