<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var string $password */
        $password = config('app.admin.password');

        Admin::create([
            'email' => config('app.admin.email'),
            'password' => Hash::make($password),
        ]);
    }
}
