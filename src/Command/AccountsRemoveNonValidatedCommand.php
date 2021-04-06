<?php

namespace App\Command;

use App\Entity\Permission;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AccountsRemoveNonValidatedCommand extends Command
{
    protected static $defaultName = 'app:accounts:remove:non-validated';
    protected static $defaultDescription = 'Remove accounts after one hour of register if not validated';

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
        ;
    }

    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    /**
     * AccountsRemoveNonValidatedCommand constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->em = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        //TODO find all new users non validated
        $uRepo = $this->em->getRepository(User::class);
        $nonValidatedUsers = $uRepo->findBy([
           'email_validated' => false
        ]);
        $totalUsersRemoved = 0;
        $totalAccountsRemoved = 0;
        foreach ($nonValidatedUsers as $user){
            //if non validated for more than one hour remove them
            if($this->hasExpired($user->created_at)){
                $io->writeln('User '.$user->username.' has expired');
                //if user is only user in this account, remove account too
                $pRepo = $this->em->getRepository(Permission::class);
                $userPermissions = $pRepo->findBy([
                    'user' => $user
                ]);
                foreach ($userPermissions as $permission){
                    $account = $permission->account;
                    //find other users in this account
                    $usersInAccount = $pRepo->findBy([
                        'account' => $account
                    ]);

                    if(count($usersInAccount) == 1){
                        //remove account
                        $io->writeln('Only user in '.$account->name.'. Removing account');
                        $this->em->remove($account);
                        $totalAccountsRemoved++;
                    }
                }

                $this->em->remove($user);
                $this->em->flush();
                $totalUsersRemoved++;
            }

        }

        $io->success('Done. '.$totalUsersRemoved.' users and '.$totalAccountsRemoved.' accounts removed');

        return 0;
    }

    private function hasExpired($created_at){
        $now = new \DateTime();
        if($created_at->getTimestamp() + 3600 < $now->getTimeStamp()) return true;
        return false;
    }
}
