<?php

namespace Src\Core\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to generate a new controller class
 */
class MakeControllerCommand extends Command
{
    protected static $defaultName = 'make:controller';
    protected static $defaultDescription = 'Create a new controller class';

    /**
     * Configure the command options and arguments
     */
    protected function configure(): void
    {
        $this->setName(static::$defaultName); // Required for Symfony Console ^7
        $this->setDescription(static::$defaultDescription);

        $this->addArgument(
            'name',
            InputArgument::REQUIRED,
            'The name of the controller (e.g. UserController)'
        );
    }

    /**
     * Execute the command
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        // Ensure controller name ends with "Controller"
        if (!str_ends_with($name, 'Controller')) {
            $name .= 'Controller';
        }

        $filename = "app/Controllers/{$name}.php";

        // Check if controller already exists
        if (file_exists($filename)) {
            $output->writeln("<error>Controller already exists: {$filename}</error>");
            return Command::FAILURE;
        }

        // Create Controllers directory if it doesn't exist
        if (!is_dir('app/Controllers')) {
            mkdir('app/Controllers', 0755, true);
        }

        // Controller stub content
        $stub = "<?php\n\n";
        $stub .= "namespace App\Controllers;\n\n";
        $stub .= "use Src\Core\Controller;\n";
        $stub .= "use Src\Core\Request;\n\n";
        $stub .= "class {$name} extends Controller\n";
        $stub .= "{\n";
        $stub .= "    /**\n";
        $stub .= "     * Display the index page\n";
        $stub .= "     */\n";
        $stub .= "    public function index(Request \$request): string\n";
        $stub .= "    {\n";
        $stub .= "        return view('welcome');\n";
        $stub .= "    }\n";
        $stub .= "}\n";

        // Write file
        file_put_contents($filename, $stub);

        $output->writeln("<info>Controller created successfully:</info>");
        $output->writeln("<comment>   → {$filename}</comment>");

        return Command::SUCCESS;
    }
}