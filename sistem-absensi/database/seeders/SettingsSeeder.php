<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Seed data default untuk tabel settings (branding & tampilan).
     * Aman dijalankan berkali-kali (updateOrInsert).
     */
    public function run(): void
    {
        $now = now();

        $defaults = [
            [
                'key'   => 'primary_color',
                'value' => '#123D91',
            ],
            [
                'key'   => 'company_logo',
                'value' => 'images/logo-sip.png',
            ],
            [
                'key'   => 'company_name',
                'value' => 'PT Selada Indonesia Produktif',
            ],
        ];

        foreach ($defaults as $item) {
            DB::table('settings')->updateOrInsert(
                ['key' => $item['key']],
                [
                    'value'      => $item['value'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $this->command->info('Settings seeded: ' . count($defaults) . ' records.');
    }
}
