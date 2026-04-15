<?php

namespace Database\Seeders;

use App\Models\JenisProyek;
use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectDataSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // ═══════════════════════════════════════════════════════════════
            // 1. Monitoring dan Evaluasi Pembangunan Daerah
            // ═══════════════════════════════════════════════════════════════
            'Monitoring dan Evaluasi Pembangunan Daerah' => [
                ['name' => 'Penyusunan Monitoring IKDD Kota Semarang', 'client' => 'Bappeda Kota Semarang', 'location' => 'Kota Semarang'],
                ['name' => 'Penyusunan Model/Indikator Pengukuran Indikator Ekonomi Kota Salatiga', 'client' => 'Bappeda Kota Salatiga', 'location' => 'Kota Salatiga'],
                ['name' => 'Monitoring, Evaluasi dan Pelaporan Pekerjaan Jasa Konstruksi dan Evaluasi Penilaian Kerja Rencana Pembangunan Daerah (RPJPD) Kota Pekalongan', 'client' => 'Bappeda Kota Pekalongan', 'location' => 'Kota Pekalongan'],
                ['name' => 'Penyusunan Monitoring, Indikator dan Pelaksanaan Indikator Penilaian Rencana Pembangunan Daerah (RPJMD) Kota Tegal', 'client' => 'Bappeda Kota Tegal', 'location' => 'Kota Tegal'],
                ['name' => 'Penyusunan Monitoring dan Evaluasi Roadmap Reformasi Birokrasi Kota Surakarta', 'client' => 'Bagian Organisasi Kota Surakarta', 'location' => 'Kota Surakarta'],
                ['name' => 'Penyusunan dan Evaluasi RKPD dan RPD DI Lingkungan Kabupaten Purbalingga', 'client' => 'Bappeda Kabupaten Purbalingga', 'location' => 'Kabupaten Purbalingga'],
                ['name' => 'Penyusunan Evaluasi Dampak Program Pemberdayaan Masyarakat dan Desa Provinsi Jawa Tengah', 'client' => 'Dispermasdesdukcapil Provinsi Jawa Tengah', 'location' => 'Provinsi Jawa Tengah'],
                ['name' => 'Penyusunan Evaluasi RPJMD Kabupaten Grobogan Tahun 2019 - 2021', 'client' => 'Bappeda Kabupaten Grobogan', 'location' => 'Kabupaten Grobogan'],
            ],

            // ═══════════════════════════════════════════════════════════════
            // 2. Penyusunan Naskah Akademis dan Rancangan Peraturan Daerah
            // ═══════════════════════════════════════════════════════════════
            'Penyusunan Naskah Akademis dan Rancangan Peraturan Daerah' => [
                ['name' => 'Penyusunan Naskah Akademik & Rancangan Peraturan Daerah Kota Semarang', 'client' => 'Bappeda Kota Semarang', 'location' => 'Kota Semarang'],
                ['name' => 'Penyusunan Naskah Akademik RPJMD Kabupaten Cilacap Tahun 2025-2029', 'client' => 'Bappeda Kabupaten Cilacap', 'location' => 'Kabupaten Cilacap', 'year' => 2025],
                ['name' => 'Penyusunan Naskah RKPD Kabupaten Banyumas Tahun 2025', 'client' => 'Bappeda Kabupaten Banyumas', 'location' => 'Kabupaten Banyumas', 'year' => 2025],
                ['name' => 'Penyusunan Naskah Akademik dan Kajian Perubahan/Penyusunan OLJP Kabupaten Pemalang', 'client' => 'Bappeda Kabupaten Pemalang', 'location' => 'Kabupaten Pemalang'],
                ['name' => 'Penyusunan Naskah Akademik & RPJMD Kabupaten Pemalang Tahun 2025-2029', 'client' => 'Bappeda Kabupaten Pemalang', 'location' => 'Kabupaten Pemalang', 'year' => 2025],
                ['name' => 'Penyusunan Naskah Akademik & Rancangan Peraturan Daerah Tentang Sistem Perencanaan Pembangunan dan Penganggaran Terpadu Kabupaten Temanggung', 'client' => 'Bappeda Kabupaten Temanggung', 'location' => 'Kabupaten Temanggung'],
                ['name' => 'Penyusunan Perda Naskah Akademik & Raperda Kabupaten Banjarnegara', 'client' => 'Bappeda Kabupaten Banjarnegara', 'location' => 'Kabupaten Banjarnegara'],
                ['name' => 'Penyusunan Naskah Akademik & Ranperda Penyelenggaraan HIV dan AIDS Kabupaten Grobogan', 'client' => 'Bappeda Kabupaten Grobogan', 'location' => 'Kabupaten Grobogan'],
                ['name' => 'Penyusunan Naskah Akademik & RPJMD Kabupaten Grobogan Tahun 2026', 'client' => 'Bappeda Kabupaten Grobogan', 'location' => 'Kabupaten Grobogan', 'year' => 2026],
                ['name' => 'Penyusunan Naskah Akademik & Rancangan Peraturan Daerah Kabupaten Grobogan', 'client' => 'Bappeda Kabupaten Grobogan', 'location' => 'Kabupaten Grobogan'],
                ['name' => 'Penyusunan Naskah Akademik dan Rancangan Peraturan Daerah tentang Penanggulangan Penyalahgunaan Kota Semarang', 'client' => 'Dinas Perencanaan dan penanggulangan Kota Semarang', 'location' => 'Kota Semarang'],
                ['name' => 'Penyusunan Naskah Akademik & Rancangan Peraturan Perdagangan Kabupaten Sukoharjo', 'client' => 'Dinas Perindustrian Naskah Akademik dan Rancangan Peraturan Daerah', 'location' => 'Kabupaten Sukoharjo'],
            ],

            // ═══════════════════════════════════════════════════════════════
            // 3. Perencanaan Pembangunan Sektoral
            // ═══════════════════════════════════════════════════════════════
            'Perencanaan Pembangunan Sektoral' => [
                ['name' => 'Penyusunan Rencana Aksi Daerah Pendidikan Untuk Semua (PUS) Kabupaten Grobogan', 'client' => 'Bappeda Kabupaten Grobogan', 'location' => 'Kabupaten Grobogan'],
                ['name' => 'Penyusunan Kajian PBJ, DS dan PO Kota Semarang', 'client' => 'DPMPTSP Kota Semarang', 'location' => 'Kota Semarang'],
                ['name' => 'Penyusunan Kajian Evaluasi Pelaksanaan Penataan Inovasi Kota Salatiga', 'client' => 'Bappeda Kota Salatiga', 'location' => 'Kota Salatiga'],
                ['name' => 'Penyusunan Kajian Perencanaan Regional Anggaran Divisi Pemberdayaan Masyarakat Kota Surakarta', 'client' => 'Dinas Pemberdayaan Kota Surakarta', 'location' => 'Kota Surakarta'],
                ['name' => 'Penyusunan Masterplan Pendidikan Tahun 2023 – 2033 Kabupaten Pati', 'client' => 'Bappeda Kabupaten Pati', 'location' => 'Kabupaten Pati', 'year' => 2023],
                ['name' => 'Penyusunan Rencana Aksi Daerah Pendidikan Untuk Semua (PUS) Kota Surakarta', 'client' => 'Bappeda Kota Surakarta', 'location' => 'Kota Surakarta'],
                ['name' => 'Penyusunan RAD Pangan dan Gizi Tahun 2025-2030 Kabupaten Purbalingga', 'client' => 'Bappeda Kabupaten Purbalingga', 'location' => 'Kabupaten Purbalingga', 'year' => 2025],
                ['name' => 'Penyusunan RAD Pangan dan Gizi Tahun 2025-2030 Kabupaten Banjarnegara', 'client' => 'Bappeda Kabupaten Banjarnegara', 'location' => 'Kabupaten Banjarnegara', 'year' => 2025],
                ['name' => 'Penyusunan RAD Pangan dan Gizi Tahun 2025-2030 Kabupaten Cilacap', 'client' => 'Bappeda Kabupaten Cilacap', 'location' => 'Kabupaten Cilacap', 'year' => 2025],
                ['name' => 'Penyusunan Sistem Kesehatan Daerah Provinsi Kalimantan Utara', 'client' => 'Bappeda Provinsi Kalimantan Utara', 'location' => 'Provinsi Kalimantan Utara'],
                ['name' => 'Penyusunan Naskah Akademik dan Rancangan Peraturan Daerah tentang Penyelenggaraan Penanggulangan Kota Semarang', 'client' => 'Dinas Perencanaan dan penanggulangan Kota Semarang', 'location' => 'Kota Semarang'],
            ],

            // ═══════════════════════════════════════════════════════════════
            // 4. Penelitian dan Pengkajian
            // ═══════════════════════════════════════════════════════════════
            'Penelitian dan Pengkajian' => [
                ['name' => 'Penyusunan Kajian Dilingkungan Corporate Social Responsibility (CSR) Kabupaten Cilacap', 'client' => 'Bappeda Kabupaten Cilacap', 'location' => 'Kabupaten Cilacap', 'year' => 2022],
                ['name' => 'Penyusunan Kajian Pengembangan Pasar Tradisional Menjadi Pasar Tradisional Modern di Kabupaten Cilacap', 'client' => 'Bappeda Kabupaten Cilacap', 'location' => 'Kabupaten Cilacap'],
                ['name' => 'Penyusunan Kajian Pemberdayaan Desa Wisata Alam, Budaya Kota Pekalongan', 'client' => 'Dinas Pariwisata Kota Pekalongan', 'location' => 'Kota Pekalongan'],
                ['name' => 'Penyusunan Kajian Kebutuhan Sarana dan Prasarana ke-PU Kabupaten Kendal', 'client' => 'Dinas Pekerjaan Umum Kabupaten Kendal', 'location' => 'Kabupaten Kendal'],
                ['name' => 'Penyusunan Kajian Kebutuhan Aksi Kebersihan Suporting Kabupaten Grobogan', 'client' => 'Bappeda Kabupaten Grobogan', 'location' => 'Kabupaten Grobogan'],
                ['name' => 'Penyusunan Kajian Pemberdayaan Masyarakat Aksi Konvergensi Stunting Kabupaten Grobogan', 'client' => 'Bappeda Kabupaten Grobogan', 'location' => 'Kabupaten Grobogan'],
                ['name' => 'Penyusunan Analisis Dinas Kesehatan Kabupaten Jepara Tentang Dampak Sosial Permasalahan Terhadap Kapasitas HIV/AIDS Di Komunitas Industri Kabupaten Jepara', 'client' => 'Dinas Kesehatan Kabupaten Jepara', 'location' => 'Kabupaten Jepara', 'year' => 2017],
                ['name' => 'Penyusunan Kajian Dokumen Unggulan Pemerintah di Kelurahan Jawa', 'client' => 'Bappeda Kabupaten Jepara', 'location' => 'Kabupaten Jepara'],
                ['name' => 'Penyusunan Kajian Identifikasi Kelangsungan Koperasi/Usaha Kota Surakarta', 'client' => 'Bappeda Kota Surakarta', 'location' => 'Kota Surakarta'],
                ['name' => 'Penyusunan Dokumen Kajian Pendidikan Sanitasi Berbasis Kota Tegal', 'client' => 'Bappeda Kota Tegal', 'location' => 'Kota Tegal'],
            ],

            // ═══════════════════════════════════════════════════════════════
            // 5. Pelatihan dan Bimbingan Teknis Aparatur
            // ═══════════════════════════════════════════════════════════════
            'Pelatihan dan Bimbingan Teknis Aparatur' => [
                ['name' => 'Pendukung Teknis Perencanaan dan Pembangunan Daerah Kabupaten Cilacap', 'client' => 'Bappeda Kabupaten Cilacap', 'location' => 'Kabupaten Cilacap'],
                ['name' => 'Pendampingan Penyusunan Dokumen Evaluasi Strategis Manajemen Resiko dan Regulasi – Bersama Tingkat Pengendalian Pendalaman Kabupaten Semarang', 'client' => 'Bappeda Kabupaten Cilacap', 'location' => 'Kabupaten Semarang', 'year' => 2023],
                ['name' => 'Pendampingan dan Penyusunan Rencana/Dokumen Pilot di 5 Desa Inovasi Kabupaten Cilacap', 'client' => 'Bappeda Kabupaten Cilacap', 'location' => 'Kabupaten Cilacap'],
                ['name' => 'Pendampingan Harmonisasi Pembangunan Kabupaten Kabupaten Kendal', 'client' => 'Bappeda Kabupaten Kendal', 'location' => 'Kabupaten Kendal'],
                ['name' => 'Pendampingan Penyusunan Peraturan Kabupaten Kendal', 'client' => 'Bappeda Kabupaten Kendal', 'location' => 'Kabupaten Kendal'],
                ['name' => 'Pendampingan dan Penyusunan RPJMD Tahun 2025-2029 Kabupaten Semarang', 'client' => 'Bappeda Kabupaten Semarang', 'location' => 'Kabupaten Semarang', 'year' => 2025],
                ['name' => 'Pendampingan dan Penyusunan OPD Kota Semarang', 'client' => 'Bappeda Kota Semarang', 'location' => 'Kota Semarang'],
            ],
        ];

        $totalCreated = 0;
        $totalSkipped = 0;

        foreach ($data as $jenisNama => $projects) {
            // Find existing jenis_proyek by name
            $jenis = JenisProyek::where('nama', $jenisNama)->first();

            if (!$jenis) {
                $this->command->warn("Jenis Proyek '{$jenisNama}' tidak ditemukan, skip " . count($projects) . " proyek.");
                continue;
            }

            $this->command->info("📁 {$jenisNama} (ID: {$jenis->id})");

            foreach ($projects as $item) {
                // Skip duplicate by exact name
                if (Project::where('name', $item['name'])->exists()) {
                    $this->command->line("   ⏭ Skip (sudah ada): {$item['name']}");
                    $totalSkipped++;
                    continue;
                }

                Project::create([
                    'name'            => $item['name'],
                    'client'          => $item['client'] ?? null,
                    'location'        => $item['location'] ?? null,
                    'year'            => $item['year'] ?? null,
                    'jenis_proyek_id' => $jenis->id,
                    'is_active'       => true,
                ]);

                $this->command->line("   ✅ {$item['name']}");
                $totalCreated++;
            }
        }

        $this->command->newLine();
        $this->command->info("════════════════════════════════════════");
        $this->command->info("✅ Total proyek ditambahkan: {$totalCreated}");
        if ($totalSkipped > 0) {
            $this->command->warn("⏭  Total proyek dilewati: {$totalSkipped}");
        }
        $this->command->info("════════════════════════════════════════");
    }
}
