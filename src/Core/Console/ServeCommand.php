<?php

namespace Src\Core\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class ServeCommand extends Command
{
    protected static $defaultName = 'serve';
    protected static $defaultDescription = 'Start the built-in PHP development server';

    protected function configure(): void
    {
        $this->setName('serve');
        $this->setDescription('Start the built-in PHP development server');

        $this->addOption('host', null, InputOption::VALUE_OPTIONAL, 'The host address to serve the application on', 'localhost');
        $this->addOption('port', null, InputOption::VALUE_OPTIONAL, 'The port to serve the application on', '8000');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $host = $input->getOption('host');
        $port = $input->getOption('port');

        // Validate port
        if (!is_numeric($port) || $port < 1 || $port > 65535) {
            $output->writeln('<error>Invalid port number. Must be between 1 and 65535.</error>');
            return Command::FAILURE;
        }

        $publicPath = realpath(__DIR__ . '/../../../public');

        if (!is_dir($publicPath)) {
            $output->writeln('<error>Public directory not found!</error>');
            return Command::FAILURE;
        }

        $url = "http://{$host}:{$port}";

        $output->writeln('<info>Microlite development server started:</info>');
        $output->writeln("   <href={$url}>{$url}</>");
        $output->writeln('');
        $output->writeln('   Press Ctrl+C to stop the server');
        $output->writeln('');

        // Start PHP built-in server
        $process = new Process(['php', '-S', "{$host}:{$port}", '-t', $publicPath]);
        $process->setWorkingDirectory($publicPath);
        $process->setTimeout(null);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        return Command::SUCCESS;
    }
}