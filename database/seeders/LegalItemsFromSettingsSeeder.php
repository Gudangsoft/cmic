<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use App\Models\LegalItem;

class LegalItemsFromSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $map = [
            'legal_npwp'  => ['NPWP',              'fas fa-id-card'],
            'legal_nib'   => ['NIB / SIUP / TDP',  'fas fa-file-alt'],
            'legal_siujk' => ['SIUJK',             'fas fa-hard-hat'],
            'legal_akta'  => ['Akta Pendirian',    'fas fa-scroll'],
            'legal_sbu'   => ['Sertifikasi / SBU', 'fas fa-certificate'],
            'legal_iso'   => ['ISO / Sertifikasi', 'fas fa-award'],
        ];

        $order = 1;
        foreach ($map as $key => [$label, $icon]) {
            $val = Setting::get($key);
            if (!empty($val)) {
                LegalItem::create([
                    'label'      => $label,
                    'value'      => $val,
                    'icon'       => $icon,
                    'order'      => $order,
                    'is_visible' => true,
                ]);
                $this->command->info("Seeded: $label = $val");
                $order++;
            }
        }

        $this->command->info("Done – $order item(s) seeded.");
    }
}
