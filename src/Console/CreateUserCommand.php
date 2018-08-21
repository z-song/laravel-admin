<?php

namespace Encore\Admin\Console;

use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Role;
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
        $username = $this->ask('Please enter a username to login');

        $password = bcrypt($this->secret('Please enter a password to login'));

        $name = $this->ask('Please enter a name to display');

        $roles = Role::all();

        /** @var array $selected */
        $selected = $this->choice('Please choose a role for the user', $roles->pluck('name')->toArray(), null, null, true);

        $roles = $roles->filter(function ($role) use ($selected) {
            return in_array($role->name, $selected);
        });

        $user = new Administrator(compact('username', 'password', 'name'));

        $user->save();

        $user->roles()->attach($roles);

        $this->info("User [$name] created successfully.");
    }
}
