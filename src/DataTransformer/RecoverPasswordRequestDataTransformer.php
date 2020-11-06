<?php


namespace App\DataTransformer;


use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\RecoverPasswordRequest;
use App\Entity\Application;
use App\Entity\Config;
use App\Entity\User;
use App\Utils\EmailUtils;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
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

    /** @var ValidatorInterface */
    private $validator;

    /**
     * PermissionWithUsernameDataTransformer constructor.
     * @param EntityManagerInterface $em
     * @param EmailUtils $mailing
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManagerInterface $em, EmailUtils $mailing, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->mailing = $mailing;
        $this->validator = $validator;
    }


    /**
     * @inheritDoc
     */
    public function transform($object, string $to, array $context = [])
    {
        $this->validator->validate($object);

        /** @var User $user */
        $user = $this->em
            ->getRepository(User::class)
            ->findOneBy(['username' => $object->username]);


        /** @var Application $user */
        $app = $this->em
            ->getRepository(Application::class)
            ->findOneBy(['realm' => $object->realm]);

        if(!$app)
            throw new HttpException(Response::HTTP_NOT_FOUND, "Username not found.");

        if($user){
            foreach ($user->permissions as $permission) {
                if ($permission->account->application->id == $app->id) {
                    $user->recover_password_code = Uuid::uuid4()->toString();
                    $user->recover_password_requested_at = new \DateTime();
                    $this->mailing->sendEmail(
                        $user,
                        'recover_password',
                        'Password recover request for {{ application.name }}'
                    );
                    $this->em->flush();
                    return $user;
                }
            }
        }

        throw new HttpException(Response::HTTP_NOT_FOUND, "Username not found.");
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