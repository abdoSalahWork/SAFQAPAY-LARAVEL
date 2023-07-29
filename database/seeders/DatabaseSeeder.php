<?php

namespace Database\Seeders;

use App\Models\setting\Bank;
use App\Models\setting\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $countries = Country::get();
        $banks = Bank::get();

        Config::set('model_dir.countries', $countries);
        Config::set('model_dir.banks', $banks);

        Artisan::call('migrate:fresh');
        $this->call(InstallSeeder::class);
        $this->call(AdminSeeder::class);

        // \App\Models\User::factory(10)->create();
    }
}
