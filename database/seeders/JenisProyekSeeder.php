<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisProyek;
use App\Models\PengalamanProyek;

class JenisProyekSeeder extends Seeder
{
    public function run(): void
    {
        if (JenisProyek::count() > 0) {
            $this->command->info('Data sudah ada, skipping.');
            return;
        }

        $data = [
            [
                'nama'   => 'Perencanaan Pembangunan Daerah',
                'warna'  => 'warning',
                'ikon'   => 'fas fa-map-marked-alt',
                'urutan' => 1,
                'items'  => [
                    'Penyusunan RPJPD 2025 – 2045 Kabupaten Pemalang',
                    'Penyusunan RPJPD 2025 – 2045 Kabupaten Sukoharjo',
                    'Penyusunan RPJPD 2025 – 2045 Kabupaten Kendal',
                    'Revisi RPJPD Tahun 2005 – 2025 Kota Pekanbaru',
                    'Penyusunan RPJMD 2026 – 2029 Kabupaten Cilacap',
                    'Penyusunan RPJMD 2026 – 2029 Kabupaten Semarang',
                    'Penyusunan RPJMD 2026 – 2029 Kabupaten Kendal',
                    'Penyusunan RPJMD 2017 – 2022 Kota Salatiga',
                    'Penyusunan RPJMD 2016 – 2021 Kabupaten Grobogan',
                    'Penyusunan RPJMD 2016 – 2021 Kabupaten Sragen',
                    'Penyusunan RKPD tahun 2026 Kabupaten Grobogan',
                    'Penyusunan RKPD tahun 2025 Kabupaten Grobogan',
                    'Penyusunan RKPD tahun 2024 Kabupaten Grobogan',
                    'Penyusunan Renstra 2026 – 2029 OPD Kabupaten Karanganyar',
                    'Penyusunan Renstra 2026 – 2029 Dinas Kesehatan Kabupaten',
                    'Penyusunan Renstra 2017 – 2022 OPD Kota Salatiga',
                ],
            ],
            [
                'nama'   => 'Perencanaan Pembangunan Sektoral',
                'warna'  => 'primary',
                'ikon'   => 'fas fa-industry',
                'urutan' => 2,
                'items'  => [],
            ],
            [
                'nama'   => 'Penelitian dan Pengkajian',
                'warna'  => 'primary',
                'ikon'   => 'fas fa-microscope',
                'urutan' => 3,
                'items'  => [],
            ],
            [
                'nama'   => 'Monitoring dan Evaluasi Pembangunan Daerah',
                'warna'  => 'primary',
                'ikon'   => 'fas fa-chart-bar',
                'urutan' => 4,
                'items'  => [],
            ],
            [
                'nama'   => 'Pelatihan dan Bimbingan Teknis Aparatur',
                'warna'  => 'primary',
                'ikon'   => 'fas fa-chalkboard-teacher',
                'urutan' => 5,
                'items'  => [],
            ],
            [
                'nama'   => 'Pendampingan Penyusunan Perencanaan dan Evaluasi Pembangunan Daerah',
                'warna'  => 'primary',
                'ikon'   => 'fas fa-hands-helping',
                'urutan' => 6,
                'items'  => [],
            ],
            [
                'nama'   => 'Pemberdayaan Masyarakat',
                'warna'  => 'primary',
                'ikon'   => 'fas fa-users',
                'urutan' => 7,
                'items'  => [],
            ],
            [
                'nama'   => 'Penyusunan Naskah Akademis dan Rancangan Peraturan Daerah',
                'warna'  => 'primary',
                'ikon'   => 'fas fa-file-contract',
                'urutan' => 8,
                'items'  => [],
            ],
        ];

        foreach ($data as $row) {
            $jenis = JenisProyek::create([
                'nama'      => $row['nama'],
                'warna'     => $row['warna'],
                'ikon'      => $row['ikon'],
                'urutan'    => $row['urutan'],
                'is_active' => true,
            ]);

            foreach ($row['items'] as $idx => $nama) {
                PengalamanProyek::create([
                    'jenis_proyek_id' => $jenis->id,
                    'nama'            => $nama,
                    'urutan'          => $idx + 1,
                    'is_active'       => true,
                ]);
            }
        }

        $this->command->info('Seeded: ' . JenisProyek::count() . ' jenis, ' . PengalamanProyek::count() . ' item pengalaman.');
    }
}
