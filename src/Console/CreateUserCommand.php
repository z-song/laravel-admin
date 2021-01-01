<?php

namespace Encore\Admin\Console;

use Illuminate\Console\Command;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userModel = config('admin.database.users_model');
        $username = $this->ask('Please enter a username to login');
        $password = bcrypt($this->secret('Please enter a password to login'));
        $name = $this->ask('Please enter a name to display');

        $user = new $userModel(compact('username', 'password', 'name'));

        $user->save();

        $this->info("User [$name] created successfully.");
    }
}
