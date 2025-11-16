<?php

namespace Src\Core\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to generate a new model class
 */
class MakeModelCommand extends Command
{
    protected static $defaultName = 'make:model';
    protected static $defaultDescription = 'Create a new model class';

    /**
     * Configure the command options and arguments
     */
    protected function configure(): void
    {
        // Required for Symfony Console 7+ when overriding configure()
        $this->setName(static::$defaultName);
        $this->setDescription(static::$defaultDescription);

        $this->addArgument(
            'name',
            InputArgument::REQUIRED,
            'The name of the model (e.g. Post, User, Product)'
        );
    }

    /**
     * Execute the command
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        // Normalize model name (e.g. post → Post, user-profile → UserProfile)
        $className = ucfirst(trim($name));
        $className = preg_replace('/[^a-zA-Z0-9]/', '', $className); // Sanitize

        if (empty($className)) {
            $output->writeln('<error>Invalid model name provided.</error>');
            return Command::FAILURE;
        }

        $filename = "app/Models/{$className}.php";

        // Prevent overwriting existing model
        if (file_exists($filename)) {
            $output->writeln("<error>Model already exists: {$filename}</error>");
            return Command::FAILURE;
        }

        // Create Models directory if missing
        if (!is_dir('app/Models')) {
            mkdir('app/Models', 0755, true);
        }

        // Generate table name (plural, snake_case)
        $tableName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $className)) . 's';

        // Model stub with proper structure and comments
        $stub = "<?php\n\n";
        $stub .= "namespace App\Models;\n\n";
        $stub .= "use Src\Core\Model;\n\n";
        $stub .= "/**\n";
        $stub .= " * Class {$className}\n";
        $stub .= " * @package App\Models\n";
        $stub .= " */\n";
        $stub .= "class {$className} extends Model\n";
        $stub .= "{\n";
        $stub .= "    /** @var string Table name */\n";
        $stub .= "    protected string \$table = '{$tableName}';\n\n";
        $stub .= "    /** @var array Fillable attributes */\n";
        $stub .= "    protected array \$fillable = [\n";
        $stub .= "        // 'name',\n";
        $stub .= "        // 'email',\n";
        $stub .= "    ];\n\n";
        $stub .= "    /** @var array Casts for attributes */\n";
        $stub .= "    protected array \$casts = [\n";
        $stub .= "        // 'is_active' => 'boolean',\n";
        $stub .= "        // 'data'      => 'array',\n";
        $stub .= "    ];\n";
        $stub .= "}\n";

        // Write the model file
        file_put_contents($filename, $stub);

        // Success output
        $output->writeln("<info>Model created successfully!</info>");
        $output->writeln("<comment>   → {$filename}</comment>");
        $output->writeln("<comment>   → Table: <options=underscore>{$tableName}</></comment>");

        return Command::SUCCESS;
    }
}