<?php

namespace App\Console;

class Kernel
{
    protected $defaultCommands = [
       \App\Console\Commands\Generators\ConsoleGeneratorCommand::class,
       \App\Console\Commands\Generators\ControllerGeneratorCommand::class,
       \App\Console\Commands\Generators\ModelGeneratorCommand::class,
       \App\Console\Commands\Generators\TransformerGeneratorCommand::class,
    ];

    public function getCommands()
    {   
        return array_merge($this->defaultCommands);
    }
}
