<?php

namespace App\Console\Commands\Generators;

use App\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Console\Traits\Generatable;

class ControllerGeneratorCommand extends Command
{
    use Generatable;

    /**
     * The command name.
     *
     * @var string
     */
    protected $command = 'make:controller';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Generate controller command.';

    /**
     * Handle the command.
     *
     * @param  InputInterface $input
     * @param  OutputInterface $output
     *
     * @return void
     */
    public function handle(InputInterface $input, OutputInterface $output)
    {
        $controllerBase = __DIR__ . '/../../../Controllers';
        $path = $controllerBase . '/';
        $namespace = 'App\\Controllers';

        $fileParts = explode('\\', $this->argument('name'));
        $fileName = array_pop($fileParts);

        $cleanPath = implode('/', $fileParts);

        if (count($fileParts) >= 1) {
            $path = $path . $cleanPath;

            $namespace = $namespace . '\\' . str_replace('/', '\\', $cleanPath);

            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
        }
        
        $target = $path . '/' . $fileName . '.php';

        if (file_exists($target)) {
            return $this->error('Controller already exists!');
        }

        $stub = $this->generateStub('controller', [
            'DummyClass' => $fileName,
            'DummyNamespace' => $namespace,
        ]);

        file_put_contents($target, $stub);

        $this->info('Controller generated!');
    }

    /**
     * Command arguments
     *
     * @return array
     */
    protected function arguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the controller to generate.']
        ];
    }

    /**
     * Command options.
     *
     * @return array
     */
    protected function options()
    {
        return [
            //
        ];
    }
}
