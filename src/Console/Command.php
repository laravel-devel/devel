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
            preg_match_all(
                '/(\"[\s\S]+?\")|(\'[\s\S]+?\')|([\S]+)/',
                $command,
                $matches
            );

            $command = array_map(function ($item) {
                return trim($item, " '");
            }, $matches[0]);
        }
        
        
        $process = new Process($command, $dir, null, null, null);
        $process->run();

        if (!$process->isSuccessful()) {
            $commandStr = implode(' ', $command);
            $this->error("Error while running `{$commandStr}`!");

            throw new ProcessFailedException($process);
        }
    }
}
