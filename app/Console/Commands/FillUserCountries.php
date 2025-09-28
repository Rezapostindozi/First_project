<?php

namespace App\Console\Commands;

use App\Enums\HttpStatus;
use App\Services\Loggerservice;
use Illuminate\Console\Command;
use App\Models\User;

class FillUserCountries extends Command
{

    protected $signature = 'app:fill-user-countries';

    protected $description = 'Command description';


    public function handle()
    {
        $Countries = ['iran', 'indian', 'england'];
        $users = User::where(function ($query) {
            $query->whereNull('country')
                  ->orWhere('country', '');
        })->get();




        if ($users->isEmpty()) {
            $this->info('no user found');
            return Command::SUCCESS;
        }

        foreach ($users as $user) {
            $user->country = $Countries[array_rand($Countries)];
            Loggerservice::getLogger()->log("user added successfully");
            $user->save();

        }

        $this->info('countries added successfully');
        return Command::SUCCESS;


    }
}
