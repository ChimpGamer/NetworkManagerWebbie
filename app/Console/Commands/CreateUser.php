<?php

namespace App\Console\Commands;

use App\Models\Group;
use App\Models\User;
use Illuminate\Console\Command;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to create a new user with administrator privileges for the web interface.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->warn('This command will create a user with administrator privileges.');
        if ($this->confirm('Do you wish to continue?')) {
            $this->info('It is highly recommended to use your own ONLINE minecraft username!');
            $username = $this->ask('What is the username?');
            $password = $this->secret('What is the password?');

            Group::firstOrCreate(
                ['name' => 'administrator'], ['administrator' => true]
            );

            User::create([
                'username' => $username,
                'password' => $password,
                'usergroup' => 'administrator',
            ]);

            $this->info("User $username was created");
        }
    }
}
