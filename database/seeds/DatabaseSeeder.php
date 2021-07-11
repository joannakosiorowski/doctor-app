<?php

use Illuminate\Database\Seeder;
use App\{Role, Department};

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name'=>'doctor']);
        Role::create(['name'=>'admin']);
        Role::create(['name'=>'patient']);
        // $this->call(UsersTableSeeder::class);
        Department::create(['department'=>'dentist']);
        Department::create(['department'=>'neurologist']);
        Department::create(['department'=>'cardiologist']);

    }
}
