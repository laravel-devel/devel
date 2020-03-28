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
     * @param string $command
     * @param string $dir
     * @return void
     */
    protected function runExternal(string $command, string $dir = null): void
    {
        $command = explode(' ', $command);
        $process = new Process($command, $dir);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->error("Error while running `{$command}`!");

            throw new ProcessFailedException($process);
        }
    }
}
