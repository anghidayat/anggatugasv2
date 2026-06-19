<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::firstOrCreate(
            ['email' => 'admin@streetfoodies.com'],
            [
                'name'           => 'Admin StreetFoodies',
                'password'       => Hash::make('admin123'),
                'role'           => 'admin',
                'is_active'      => true,
                'email_verified' => true,
            ]
        );

        // Demo Vendor
        User::firstOrCreate(
            ['email' => 'vendor@streetfoodies.com'],
            [
                'name'           => 'Pak Budi Pedagang',
                'password'       => Hash::make('vendor123'),
                'role'           => 'vendor',
                'is_active'      => true,
                'email_verified' => true,
            ]
        );

        // Demo Buyer
        User::firstOrCreate(
            ['email' => 'buyer@streetfoodies.com'],
            [
                'name'           => 'Siti Pembeli',
                'password'       => Hash::make('buyer123'),
                'role'           => 'buyer',
                'is_active'      => true,
                'email_verified' => true,
            ]
        );

        // Default Categories
        $categories = [
            ['name' => 'Bakso & Mie', 'icon' => '🍜'],
            ['name' => 'Gorengan', 'icon' => '🍤'],
            ['name' => 'Nasi & Lauk', 'icon' => '🍚'],
            ['name' => 'Sate & Bakar', 'icon' => '🍢'],
            ['name' => 'Minuman', 'icon' => '🥤'],
            ['name' => 'Jajanan Pasar', 'icon' => '🍡'],
            ['name' => 'Seafood', 'icon' => '🦐'],
            ['name' => 'Dessert & Es', 'icon' => '🍧'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat['name']], $cat);
        }
    }
}
