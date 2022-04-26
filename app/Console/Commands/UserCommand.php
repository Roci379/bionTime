<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User; 
use Hash; 


class UserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to create admin user';

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
     * @return int
     */
    public function handle()
    {
        $new_user = new User; 

        $new_user->first_name = $this->ask('First name: ');
        $new_user->last_name = $this->ask('Last name: ');
        $new_user->phone = $this->ask('Phone number: ');
        $new_user->email = $this->ask('Email: ');
        $new_user->password = Hash::make($this->secret('Choose a password: '));
        $new_user->admin = true; 
        $new_user->event_visibility = true; 

        $new_user->save();
        
        
        $this->info('User created succesfully');
    }
}
