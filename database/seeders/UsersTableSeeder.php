<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user =User::create([
            'pkiduser' => 1,
            'name' => 'Daniel',
            'lastname' => 'Jojoa',
            'email' => 'danielinsandara@udenar.edu.co',
            'password' => Hash::make('1234'),
            'identification_number' => '1234',
            'phone_number' => '3157119750',
            'active' => true,
            'deleted' => false,
        
        ]);
        $user->assignRole('super-admin');    
    }
}
