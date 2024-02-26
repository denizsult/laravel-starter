<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Package;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $packages = [
            ['name' => 'Basic', 'price' => 29.99],
            ['name' => 'Standard', 'price' => 49.99],
            ['name' => 'Premium', 'price' => 79.99],
        ];

        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
        ]);

        foreach ($packages as $packageData) {
            $package = Package::create($packageData);

            $user->subscriptions()->create([
                'package_id' => $package->id,
                'subscription_date' => now(),
                'expiry_date' => now()->addMonths(1),
            ]);
        }
    }
}
