<?php

namespace Devel\Console;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Devel\Models\Auth\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'users:reset-password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset password for a user.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('user');
        $password = $this->argument('password') ?: \Str::random(16);
        
        $this->info("Resetting password for \"{$name}\"...");

        $table = (new User())->getTable();

        if (!DB::table($table)->where('name', $name)->exists()) {
            $this->error("User with name \"{$name}\" not found!");

            exit(1);
        }

        DB::table($table)->where('name', $name)->update([
            'password' => Hash::make($password),
        ]);

        $this->info("Done! Your new password is: {$password}");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['user', InputArgument::REQUIRED, 'A user name.'],
            ['password', InputArgument::OPTIONAL, 'New password. Leave blank if you want a random password to be generated instead.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            // ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
