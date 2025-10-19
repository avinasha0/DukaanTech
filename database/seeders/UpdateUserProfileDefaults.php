<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UpdateUserProfileDefaults extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update existing users with default profile settings
        User::whereNull('notifications')->orWhere('notifications', '[]')->chunk(100, function ($users) {
            foreach ($users as $user) {
                $user->update([
                    'timezone' => $user->timezone ?? 'UTC',
                    'language' => $user->language ?? 'en',
                    'theme' => $user->theme ?? 'auto',
                    'notifications' => $user->notifications ?? [
                        'email' => true,
                        'orders' => true,
                        'system' => true,
                        'marketing' => false,
                    ],
                ]);
            }
        });
    }
}