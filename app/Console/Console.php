<?php

namespace App\Console;

use App\Console\Kernel;
use League\Container\ContainerInterface;
use Symfony\Component\Console\Application;

class Console extends Application
{
    public function __construct()
    {
        parent::__construct();
    }

    public function boot(Kernel $kernel)
    {
        foreach ($kernel->getCommands() as $command) {
            $this->add(new $command($command));
        }
    }

    
}
