<?php

namespace App\Command;

use App\Entity\VinylMix;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\String\ByteString;

#[AsCommand(
    name: 'app:gen:vinylmix',
    description: 'Add a short description for your command',
)]
class GenVinylmixCommand extends Command
{

    public function __construct(
        private EntityManagerInterface $entityManagerInterface,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('amount', null, InputOption::VALUE_OPTIONAL, 'Amount of objects to be created');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $amount = $input->getOption('amount') ?: 10;

        // purge db first
        $vinylMixRepo = $this->entityManagerInterface
            ->getRepository(VinylMix::class);

        $entities = $vinylMixRepo->findAll();
        foreach ($entities as $entity) {
            $this->entityManagerInterface->remove($entity);
        }
        $this->entityManagerInterface->flush();

        for ($i = 0; $i < $amount; $i++) {
            $entity = new VinylMix();

            $genres = ['pop', 'rock', 'heavy-metal'];
            $entity
                ->setTitle("Random title nr {$i}")
            ->setDescription(ByteString::fromRandom(99, "abcdefg ")->toString())
                ->setGenre($genres[array_rand($genres)])
                ->setTrackCount(rand(5, 15))
                ->setVotes(rand(-50, 50));
            $this->entityManagerInterface->persist($entity);
        }
        $this->entityManagerInterface->flush();

        $io->success("{$amount} new object has been created");

        return Command::SUCCESS;
    }
}
