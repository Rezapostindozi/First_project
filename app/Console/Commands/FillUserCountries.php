<?php

namespace App\Console\Commands;

use App\Enums\HttpStatus;
use App\Services\LoggerService;
use Illuminate\Console\Command;
use App\Models\User;

class FillUserCountries extends Command
{

    protected $signature = 'app:fill-user-countries';

    protected $description = 'Command description';


    public function handle()
    {
        $countries = ['iran', 'indian', 'england'];
        $found = false;

        $users = User::where(function ($query) {
            $query->whereNull('country')
                ->orWhere('country', '');
        })->chunk(100, function ($users) use ($countries , &$found) {
            $found = true;
            foreach ($users as $user) {

                $user->country = $countries[array_rand($countries)];
                $user->save();

                LoggerService::getLogger()->log("User ID {$user->id} updated with country: {$user->country}");
            }
        });

        if (!$found) {
            $this->info('no user found');
            return Command::SUCCESS;
        }

        $this->info('countries added successfully');
        return Command::SUCCESS;


    }
}
