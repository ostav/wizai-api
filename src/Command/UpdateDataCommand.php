<?php

namespace App\Command;

use App\Entity\Post;
use App\Entity\User;
use App\Fetcher\GorestFetcher;
use App\Gateway\GorestGateway;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:update-data',
    description: 'Call the gorest api and to update the data into the db',
)]
class UpdateDataCommand extends Command
{
    public function __construct(
        private GorestGateway $gateway,
        private EntityManagerInterface $entityManager
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $users = $this->gateway->getUsers();
        $usersArray =[];
        foreach ($users as $user) {
            $userEntity = (new User())
                ->setName($user['name'])
                ->setEmail($user['email'])
                ->setStatus($user['status'])
                ->setGender($user['gender']);
            $this->entityManager->persist($userEntity);
            $usersArray[$user['id']] = $userEntity;
        }
        $this->entityManager->flush();

        $posts = $this->gateway->getPosts();
        foreach ($posts as $post) {
            $postEntity = (new Post())
                ->setTitle($post['title'])
                ->setBody($post['body']);
            if (array_key_exists($post['user_id'], $usersArray)) {
                $postEntity->setUser($usersArray[$post['user_id']]);
                $this->entityManager->persist($postEntity);
            }
        }
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
