<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class OrgPhotoSeeder extends Seeder
{
    private array $gradients = [
        ['#0057A8', '#003d7a'],
        ['#1976D2', '#0D47A1'],
        ['#00897B', '#004D40'],
        ['#43A047', '#1B5E20'],
        ['#7B1FA2', '#4A148C'],
        ['#E53935', '#B71C1C'],
        ['#F57C00', '#E65100'],
        ['#00ACC1', '#006064'],
    ];

    public function run(): void
    {
        $dir = storage_path('app/public/org-photos');
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $orgData = json_decode(Setting::get('about_org_data') ?? '[]', true) ?: [];

        foreach ($orgData as $i => &$member) {
            $initial  = mb_strtoupper(mb_substr($member['nama'] ?? 'X', 0, 1));
            $gradient = $this->gradients[$i % count($this->gradients)];
            $filename = 'org-photos/member_' . ($i + 1) . '_' . time() . '.png';
            $filepath = storage_path('app/public/' . $filename);

            $this->generateAvatar($filepath, $initial, $gradient[0], $gradient[1]);

            $member['foto'] = $filename;
            $this->command->info("Generated photo for: {$member['nama']}");
        }
        unset($member);

        Setting::set('about_org_data', json_encode(array_values($orgData)));
        $this->command->info('Done – ' . count($orgData) . ' photos generated.');
    }

    private function generateAvatar(string $path, string $initial, string $colorTop, string $colorBot): void
    {
        $size = 400;
        $img  = imagecreatetruecolor($size, $size);
        imagesavealpha($img, true);

        // Parse top/bottom gradient colors
        [$r1, $g1, $b1] = $this->hex2rgb($colorTop);
        [$r2, $g2, $b2] = $this->hex2rgb($colorBot);

        // Fill gradient background row by row
        for ($y = 0; $y < $size; $y++) {
            $ratio = $y / $size;
            $r = (int)($r1 + ($r2 - $r1) * $ratio);
            $g = (int)($g1 + ($g2 - $g1) * $ratio);
            $b = (int)($b1 + ($b2 - $b1) * $ratio);
            $lineColor = imagecolorallocate($img, $r, $g, $b);
            imagefilledrectangle($img, 0, $y, $size - 1, $y, $lineColor);
        }

        // Circular mask (anti-alias trick: draw on larger canvas then scale)
        $mask = imagecreatetruecolor($size, $size);
        imagesavealpha($mask, true);
        $trans = imagecolorallocatealpha($mask, 0, 0, 0, 127);
        imagefill($mask, 0, 0, $trans);
        $white = imagecolorallocate($mask, 255, 255, 255);
        imagefilledellipse($mask, $size / 2, $size / 2, $size, $size, $white);

        // Create circular output
        $out  = imagecreatetruecolor($size, $size);
        imagesavealpha($out, true);
        $bgTrans = imagecolorallocatealpha($out, 0, 0, 0, 127);
        imagefill($out, 0, 0, $bgTrans);

        // Apply circular clip
        for ($x = 0; $x < $size; $x++) {
            for ($y = 0; $y < $size; $y++) {
                $alpha = (imagecolorat($mask, $x, $y) >> 24) & 0x7F;
                if ($alpha < 64) {
                    $srcColor = imagecolorat($img, $x, $y);
                    $r = ($srcColor >> 16) & 0xFF;
                    $g = ($srcColor >> 8) & 0xFF;
                    $b = $srcColor & 0xFF;
                    $c = imagecolorallocate($out, $r, $g, $b);
                    imagesetpixel($out, $x, $y, $c);
                }
            }
        }

        // Draw subtle white ring inside edge
        $ringColor = imagecolorallocatealpha($out, 255, 255, 255, 80);
        imagearc($out, $size / 2, $size / 2, $size - 16, $size - 16, 0, 360, $ringColor);

        // Draw initial letter (using GD's built-in font, size 5)
        // Use a large font: create text by repeating across different sizes
        $white = imagecolorallocate($out, 255, 255, 255);
        // GD font 5 is ~9x15 pixels; scale via imagettftext if font available
        $fontSize    = 5;
        $charW       = imagefontwidth($fontSize);
        $charH       = imagefontheight($fontSize);
        $scale       = 9;
        $scaledW     = $charW * $scale;
        $scaledH     = $charH * $scale;

        // Draw on temp canvas then scale up
        $tmp   = imagecreatetruecolor($charW, $charH);
        $tmpBg = imagecolorallocatealpha($tmp, 0, 0, 0, 127);
        imagefill($tmp, 0, 0, $tmpBg);
        $tmpW  = imagecolorallocate($tmp, 255, 255, 255);
        imagechar($tmp, $fontSize, 0, 0, $initial, $tmpW);

        $scaledX = (int)(($size - $scaledW) / 2);
        $scaledY = (int)(($size - $scaledH) / 2);
        imagecopyresized($out, $tmp, $scaledX, $scaledY, 0, 0, $scaledW, $scaledH, $charW, $charH);

        imagepng($out, $path);
        imagedestroy($img);
        imagedestroy($mask);
        imagedestroy($out);
        imagedestroy($tmp);
    }

    private function hex2rgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }
}
