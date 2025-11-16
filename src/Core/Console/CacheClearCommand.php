<?php

namespace Src\Core\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to clear the application cache
 */
class CacheClearCommand extends Command
{
    protected static $defaultName = 'cache:clear';
    protected static $defaultDescription = 'Clear all application caches (views, config, routes, etc.)';

    /**
     * Configure the command
     */
    protected function configure(): void
    {
        // Required for Symfony Console 7+ when overriding configure()
        $this->setName(static::$defaultName);
        $this->setDescription(static::$defaultDescription);
    }

    /**
     * Execute the cache clear command
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $cacheDir = __DIR__ . '/../../storage/cache';

        if (!is_dir($cacheDir)) {
            $output->writeln('<comment>No cache directory found at:</comment>');
            $output->writeln('   <fg=cyan>' . realpath($cacheDir) . '</>');
            $output->writeln('<info>Nothing to clear.</info>');
            return Command::SUCCESS;
        }

        $files = glob($cacheDir . '/*');
        $count = is_array($files) ? count($files) : 0;

        if ($count === 0) {
            $output->writeln('<comment>Cache directory is already empty.</comment>');
            return Command::SUCCESS;
        }

        // Safely delete all files in cache directory
        foreach ($files as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }

        $output->writeln('<info>Application cache cleared!</info>');
        $output->writeln("<comment>   → Removed <options=bold>{$count}</> file(s) from cache.</comment>");

        return Command::SUCCESS;
    }
}