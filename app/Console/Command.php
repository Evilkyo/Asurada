<?php

namespace App\Console;

use Symfony\Component\Console\Command\Command as Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends Console
{
    private $input;

    private $output;

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName($this->command)->setDescription($this->description);

        $this->addArguments();
        $this->addOptions();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        return $this->handle($input, $output);
    }

    protected function argument($name)
    {
        return $this->input->getArgument($name);
    }

    protected function option($name)
    {
        return $this->input->getOption($name);
    }

    protected function addArguments()
    {
        foreach ($this->arguments() as $argument) {
            $this->addArgument($argument[0], $argument[1], $argument[2]);
        }
    }

    protected function addOptions()
    {
        if (is_array($this->options())) {
            foreach ($this->options() as $option) {
                $this->addOption($option[0], $option[1], $option[2], $option[3], $option[4]);
            }
        }
    }

    protected function info($value)
    {
        return $this->output->writeln('<info>' . $value . '</info>');
    }

    protected function error($value)
    {
        return $this->output->writeln('<error>' . $value . '</error>');
    }
}
