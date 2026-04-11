<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\ClientType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PemerintahKabupatenSeeder extends Seeder
{
    protected array $kabupatens = [
        ['name' => 'Kabupaten Rembang',  'website' => 'https://www.rembangkab.go.id',  'color' => [0,90,155]],
        ['name' => 'Kabupaten Gresik',   'website' => 'https://www.gresikkab.go.id',   'color' => [20,120,60]],
        ['name' => 'Kabupaten Karawang', 'website' => 'https://www.karawangkab.go.id', 'color' => [160,30,30]],
        ['name' => 'Kabupaten Bekasi',   'website' => 'https://www.bekasikab.go.id',   'color' => [130,70,0]],
        ['name' => 'Kabupaten Bintan',   'website' => 'https://www.bintankab.go.id',   'color' => [80,0,160]],
        ['name' => 'Kabupaten Bulungan', 'website' => 'https://www.bulungankab.go.id', 'color' => [0,130,130]],
    ];

    public function run(): void
    {
        // Ensure the storage directory exists
        Storage::disk('public')->makeDirectory('clients');

        // Create or find client type
        $type = ClientType::firstOrCreate(
            ['name' => 'Pemerintah Daerah / Kab'],
            ['order' => 1, 'is_active' => true]
        );

        foreach ($this->kabupatens as $i => $data) {
            // Skip if already exists
            if (Client::where('name', $data['name'])->exists()) {
                $this->command->line("  Skip: {$data['name']} (sudah ada)");
                continue;
            }

            $logoPath = $this->generateDummyLogo($data['name'], $data['color']);

            Client::create([
                'client_type_id' => $type->id,
                'name'           => $data['name'],
                'website'        => $data['website'],
                'logo'           => $logoPath,
                'order'          => $i + 1,
                'is_active'      => true,
            ]);

            $this->command->info("  + {$data['name']}");
        }

        $this->command->info('Selesai! ' . count($this->kabupatens) . ' klien pemerintah kabupaten ditambahkan.');
    }

    protected function generateDummyLogo(string $name, array $rgb): string
    {
        $w = 300;
        $h = 150;

        $img = imagecreatetruecolor($w, $h);

        // Background color
        $bg = imagecolorallocate($img, $rgb[0], $rgb[1], $rgb[2]);
        imagefill($img, 0, 0, $bg);

        // Subtle inner border
        $border = imagecolorallocatealpha($img, 255, 255, 255, 80);
        imagerectangle($img, 4, 4, $w - 5, $h - 5, $border);

        // White text
        $white  = imagecolorallocate($img, 255, 255, 255);
        $yellow = imagecolorallocate($img, 245, 197, 24);

        // Draw "PEMKAB" label on top
        $font = 3; // built-in GD font
        $label = 'PEMERINTAH KABUPATEN';
        $lw = imagefontwidth($font) * strlen($label);
        imagestring($img, $font, (int)(($w - $lw) / 2), 18, $label, $yellow);

        // Draw the kabupaten name (split into lines if needed)
        $shortName = strtoupper(str_replace('Kabupaten ', '', $name));
        $font2 = 5;
        $sw = imagefontwidth($font2) * strlen($shortName);
        imagestring($img, $font2, (int)(($w - $sw) / 2), 55, $shortName, $white);

        // Draw divider line
        $divider = imagecolorallocate($img, 245, 197, 24);
        imageline($img, 40, 88, $w - 40, 88, $divider);

        // Draw website text
        $domain = 'go.id';
        $font3 = 2;
        $dw = imagefontwidth($font3) * strlen($domain);
        imagestring($img, $font3, (int)(($w - $dw) / 2), 98, $domain, $yellow);

        // Save to storage
        $filename  = 'clients/kab_' . Str::slug(str_replace('Kabupaten ', '', $name)) . '.png';
        $fullPath  = storage_path('app/public/' . $filename);

        imagepng($img, $fullPath);
        imagedestroy($img);

        return $filename;
    }
}
