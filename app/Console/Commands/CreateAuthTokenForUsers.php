<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateAuthTokenForUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-auth-token-for-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            if($user->tokens->isNotEmpty()) {
                continue;
            }
            $token = $user->createToken('auth-token')->plainTextToken;

            $this->info("Token for {$user->name} ({$user->email}):");
            $this->line($token);
        }
    }
}
