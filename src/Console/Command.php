<?php

namespace Devel\Console;

use Illuminate\Console\Command as IlluminateCommand;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Command extends IlluminateCommand
{
    /**
     * Run an external command.
     *
     * @param string|array $command
     * @param string $dir
     * @return void
     */
    protected function runExternal($command, string $dir = null): void
    {
        if (is_string($command)) {
            $command = explode(' ', $command);
        }
        
        $process = new Process($command, $dir);
        $process->run();

        if (!$process->isSuccessful()) {
            $commandStr = implode(' ', $command);
            $this->error("Error while running `{$commandStr}`!");

            throw new ProcessFailedException($process);
        }
    }
}
