<?php

namespace App\Command;

use App\Repository\VinylMixRepository;
use App\Service\MixRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:talk-to-me',
    description: 'A self aware command that will do own thing',
)]
class TalkToMeCommand extends Command
{

    public function __construct(
        // private MixRepository $mixRepository
        private VinylMixRepository $mixRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::OPTIONAL, 'your name')
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Should the command yell at you!');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('name') ?: 'Whoever you are';



        $message = sprintf('Hi %s', $arg1);
        if ($input->getOption('yell')) {
            $message = strtoupper($message);
        }
        $io->success($message);

        if ($io->confirm('Do you want mix recommendation?')) {
            $mixes = $this->mixRepository->getAll();
            $mix = $mixes[array_rand($mixes)];
            $io->note('I recommend mix: ' . $mix['title']);
        }

        return Command::SUCCESS;
    }
}
