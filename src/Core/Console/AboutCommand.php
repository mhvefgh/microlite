<?php

namespace Src\Core\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class AboutCommand extends Command
{
    protected static $defaultName = 'about';
    protected static $defaultDescription = 'Show information about Microlite';

    protected function configure(): void
    {
        $this->setName(static::$defaultName);
        $this->setDescription(static::$defaultDescription);

        
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('');
        $output->writeln('  <fg=#00AFFF;options=bold>███╗   ███╗██╗ ██████╗██████╗  ██████╗ ██╗     ██╗████████╗███████╗</>');
        $output->writeln('  <fg=#00AFFF;options=bold>████╗ ████║██║██╔════╝██╔══██╗██╔═══██╗██║     ██║╚══██╔══╝██╔════╝</>');
        $output->writeln('  <fg=#00AFFF;options=bold>██╔████╔██║██║██║     ██████╔╝██║   ██║██║     ██║   ██║   █████╗  </>');
        $output->writeln('  <fg=#00AFFF;options=bold>██║╚██╔╝██║██║██║     ██╔══██╗██║   ██║██║     ██║   ██║   ██╔══╝  </>');
        $output->writeln('  <fg=#00AFFF;options=bold>██║ ╚═╝ ██║██║╚██████╗██║  ██║╚██████╔╝███████╗██║   ██║   ███████╗</>');
        $output->writeln('  <fg=#00AFFF;options=bold>╚═╝     ╚═╝╚═╝ ╚═════╝╚═╝  ╚═╝ ╚═════╝ ╚══════╝╚═╝   ╚═╝   ╚══════╝</>');
        $output->writeln('');

        $env = env('APP_ENV', 'production');
        $debug = env('APP_DEBUG', false);
        $debugOn = in_array($debug, ['true', '1', 'on', true], true);

        $output->writeln('  <options=bold>Version:</>       <info>1.0.0</info>');
        $output->writeln('  <options=bold>PHP Version:</>   <info>' . PHP_VERSION . '</info>');
        $output->writeln("  <options=bold>Environment:</>   <info>{$env}</info>");
        $output->writeln('  <options=bold>Debug Mode:</>    <info>' . ($debugOn ? 'on' : 'off') . '</info>');
        $output->writeln('');

        $output->writeln('  <fg=cyan;options=bold>Available commands:</>');
        $output->writeln('');

        $table = new Table($output);
        $table
            ->setStyle('compact')
            ->setColumnWidths([20, 60])
            ->setRows([['about', 'Display Microlite information'], ['make:controller', 'Create a new controller class'], ['make:model', 'Create a new model class'], ['cache:clear', 'Clear application cache']]);
        $table->render();

        $output->writeln('');
        $output->writeln('  <comment>Run `./microlite <command> --help` for more info</comment>');
        $output->writeln('');

        return Command::SUCCESS;
    }
}
