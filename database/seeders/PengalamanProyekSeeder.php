<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisProyek;
use App\Models\PengalamanProyek;

class PengalamanProyekSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Perencanaan Pembangunan Sektoral' => [
                'Penyusunan Sistem Kesehatan Daerah Provinsi Kalimantan Utara',
                'Penyusunan RAD Pangan dan Gizi Tahun 2025-2030 Kabupaten Cilacap',
                'Penyusunan RAD Pangan dan Gizi Tahun 2025-2029 Kabupaten Banjarnegara',
                'Penyusunan RAD Pangan dan Gizi Tahun 2025-2030 Kabupaten Pemalang',
                'Penyusunan Rencana Aksi Tahunan Pendidikan Untuk Semua (PUS)',
                'Penyusunan Masterplan Pendidikan Tahun 2023 – 2043 Kabupaten Pati',
                'Penyusunan Kajian RAD Penanganan Tuberkulosis Kabupaten Pemalang',
                'Penyusunan Kajian Perencanaan Kegiatan Dinas Perhubungan Mengacu pada Tatralog Kota Surakarta',
            ],
            'Penelitian dan Pengkajian' => [
                'Penyusunan Kajian Strategi Pencapaian Realisasi Investasi Kota Semarang',
                'Penyusunan Kajian IPM, IDG dan IPG Kota Semarang',
                'Penyusunan Kajian Identifikasi Ketimpangan Kesejahteraan Kota Surakarta',
                'Penyusunan Dokumen Kajian Pemberian Bantuan Sosial Skala Kota Tegal',
                'Penyusunan Kajian Ekonomi Unggulan Pedesaan di Kabupaten Jepara',
                'Penyusunan Kajian Dinas Kesehatan Kabupaten Jepara Tentang Dampak Sosial Perusahaan Terhadap Kejadian HIV/AIDS Di Kawasan Industri Kabupaten Jepara Bagian Selatan',
                'Penyusunan Kajian Penyusunan Aksi Konvergensi Stunting Kabupaten Grobogan',
                'Penyusunan Kajian Kebutuhan Sarana Prasarana SD Kabupaten Kendal',
                'Penyusunan Kajian Kebutuhan Sarana Prasarana SMP Kabupaten Kendal',
                'Penyusunan Kajian Pemekaran Desa Sikasur Kec. Belik Kab Pemalang',
                'Penyusunan Kajian Pengembangan Pasar Tradisional Menjadi Pasar Tradisional Modern di Kabupaten Cilacap',
                'Penyusunan Kajian Optimalisasi Corporate Social Responsibility (CSR) Kabupaten Cilacap',
            ],
            'Monitoring dan Evaluasi Pembangunan Daerah' => [
                "Penyusunan Monitoring SDG's Kota Semarang",
                'Penyusunan Monitoring dan Evaluasi SKM dan FKP di Lingkungan Pemerintah Kota Surakarta',
                'Penyusunan Monitoring dan Evaluasi Road Map Reformasi Birokrasi Kota Surakarta',
                'Penyusunan Monitoring, Evaluasi dan Pelaporan, Pekerjaan Evaluasi Rencana Kerja Pembangunan Daerah (RKPD) Kota Tegal',
                'Monitoring, Evaluasi dan Pelaporan Pekerjaan Jasa Konsultansi Evaluasi Paruh Waktu Rencana Kerja Pemerintah Daerah (RKPD) Kota Pekalongan',
            ],
            'Pelatihan dan Bimbingan Teknis Aparatur' => [
                'Penyusunan Modul dan Pelatihan Penghitungan Indikator Ekonomi Kota Salatiga',
                'Bimbingan Teknis Perencanaan Pembangunan Daerah Kabupaten Cilacap',
            ],
            'Pendampingan Penyusunan perencanaan dan evaluasi Pembangunan daerah' => [
                'Pendampingan dan Penyusunan ARG Kota Semarang',
                'Pendampingan Penyusunan Rancangan RPJMD Kota Salatiga Tahun 2017 – 2022',
                'Pendampingan Penyusunan Dokumen Sasaran Strategis (Manajemen Resiko dan Register Resiko - Rencana Tindak Pengendalian Pemda) Kabupaten Semarang Tahun 2025-2029',
                'Pendampingan Tenaga Ahli Penyusunan RPJMD Tahun 2025-2029 Kabupaten Semarang',
                'Pendampingan Penyusunan Renstra Kabupaten Kendal',
                'Pendampingan Perencanaan Pembangunan Daerah Kabupaten Kendal',
                'Pendampingan dan Penyusunan Business Plan di 5 Desa Inovasi Kabupaten Cilacap',
            ],
            'Pemberdayaan Masyarakat' => [
                'Penyusunan Evaluasi Dampak Program Pemberdayaan Masyarakat dan Desa Provinsi Jawa Tengah',
            ],
            'Penyusunan Naskah Akademis dan Rancangan Peraturan Daerah' => [
                'Penyusunan Naskah Akademik Undang-Undang Perlindungan Konsumen Provinsi DKI Jakarta',
                'Penyusunan Naskah Akademik dan Rancangan Peraturan Daerah tentang Penyelenggaraan Perpustakaan Kota Semarang',
                'Penyusunan Naskah Akademik Rancangan Peraturan Daerah Kota Semarang',
                'Penyusunan Naskah Akademik RPJMD Kabupaten Grobogan Tahun 2016 – 2021',
                'Penyusunan Naskah Akademik Rancangan Perda Penanggulangan HIV dan AIDS Kabupaten Grobogan',
                'Penyusunan Perda Naskah Akademik Disabilitas Kabupaten Rembang',
                'Penyusunan Naskah Akademik Dan Rancangan Peraturan Daerah Tentang Sistem Perencanaan Pembangunan Dan Penganggaran Terpadu Kabupaten Rembang',
                'Penyusunan Naskah Akademik RPJMD Kabupaten Pemalang Tahun 2025-2029',
                'Penyusunan Naskah Akademik Raperda Surat Izin Usaha Perdagangan (SIUP) Kabupaten Pemalang',
                'Penyusunan Naskah RKPD Kabupaten Banyumas Tahun 2014',
                'Penyusunan Naskah Akademik RPJMD Kabupaten Cilacap Tahun 2025-2029',
            ],
        ];

        $added = 0;
        foreach ($data as $jenisNama => $items) {
            $jenis = JenisProyek::where('nama', $jenisNama)->first();
            if (!$jenis) {
                $this->command->warn("Jenis tidak ditemukan: {$jenisNama}");
                continue;
            }

            // Get current max urutan for this jenis
            $maxUrutan = PengalamanProyek::where('jenis_proyek_id', $jenis->id)->max('urutan') ?? 0;

            foreach ($items as $idx => $nama) {
                PengalamanProyek::create([
                    'jenis_proyek_id' => $jenis->id,
                    'nama'            => $nama,
                    'urutan'          => $maxUrutan + $idx + 1,
                    'is_active'       => true,
                ]);
                $added++;
            }

            $cnt = count($items);
            $this->command->info("  ✓ {$jenis->nama}: +{$cnt} items");
        }

        $this->command->info("Total ditambahkan: {$added} item.");
    }
}
