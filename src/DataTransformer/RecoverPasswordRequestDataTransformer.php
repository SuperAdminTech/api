<?php


namespace App\DataTransformer;


use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\RecoverPasswordRequest;
use App\Entity\Config;
use App\Entity\User;
use App\Utils\EmailUtils;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Exception\RfcComplianceException;

/**
 * Class RecoverPasswordRequestDataTransformer
 * @package App\DataTransformer
 */
class RecoverPasswordRequestDataTransformer implements DataTransformerInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var EmailUtils */
    private $mailing;

    /**
     * PermissionWithUsernameDataTransformer constructor.
     * @param EntityManagerInterface $em
     * @param EmailUtils $mailing
     */
    public function __construct(EntityManagerInterface $em, EmailUtils $mailing)
    {
        $this->em = $em;
        $this->mailing = $mailing;
    }


    /**
     * @inheritDoc
     */
    public function transform($object, string $to, array $context = [])
    {
        /** @var User $user */
        $user = $this->em
            ->getRepository(User::class)
            ->findOneBy(['username' => $object->username]);

        if($user){
            $user->recover_password_code = Uuid::uuid4()->toString();
            $user->recover_password_requested_at = new \DateTime();
            $this->mailing->sendEmail(
                $user,
                'recover_password',
                'Password recover request for {{ application.name }}'
            );
            $this->em->flush();
        }

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

        return User::class === $to && RecoverPasswordRequest::class == $context['input']['class'];
    }
}