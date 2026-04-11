<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Keunggulan;
use App\Models\Setting;

class KeunggulanSeeder extends Seeder
{
    public function run(): void
    {
        if (Keunggulan::count() > 0) {
            $this->command->info('Keunggulan table already has data, skipping.');
            return;
        }

        $defaults = [
            ['icon' => 'fas fa-award',        'label' => 'Pengalaman',        'order' => 1],
            ['icon' => 'fas fa-users',         'label' => 'Tim Profesional',   'order' => 2],
            ['icon' => 'fas fa-check-circle',  'label' => 'Kualitas Terjamin', 'order' => 3],
            ['icon' => 'fas fa-handshake',     'label' => 'Kepuasan Klien',    'order' => 4],
        ];

        foreach ($defaults as $row) {
            Keunggulan::create([
                'label'       => Setting::get('about_h' . $row['order'] . '_label', $row['label']),
                'icon'        => Setting::get('about_h' . $row['order'] . '_icon',  $row['icon']),
                'description' => '',
                'order'       => $row['order'],
                'is_active'   => true,
            ]);
        }

        $this->command->info('Keunggulan seeded: ' . Keunggulan::count() . ' items.');
    }
}
