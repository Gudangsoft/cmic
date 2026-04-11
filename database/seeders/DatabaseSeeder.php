<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Setting;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Project;
use App\Models\Client;
use App\Models\Menu;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@cmic.co.id'],
            ['name' => 'Administrator', 'password' => Hash::make('admin123')]
        );

        $defaults = [
            'company_name'    => 'PT. Citra Muda Indo Consultant',
            'company_tagline' => 'Solusi Terbaik untuk Perencanaan & Pengawasan Konstruksi',
            'company_address' => 'Jakarta, Indonesia',
            'company_phone'   => '+62-21-0000-0000',
            'company_email'   => 'info@cmic.co.id',
            'company_about'   => 'PT. Citra Muda Indo Consultant (CMIC) adalah perusahaan konsultan profesional yang bergerak di bidang perencanaan, pengawasan, dan manajemen konstruksi.',
            'about_visi'      => 'Menjadi perusahaan konsultan terkemuka di Indonesia yang memberikan solusi terbaik dalam perencanaan dan pengawasan konstruksi dengan mengutamakan kualitas, inovasi, dan integritas.',
            'about_misi'      => "Memberikan layanan konsultansi berkualitas tinggi\nMengembangkan sumber daya manusia yang kompeten\nMenerapkan teknologi terkini dalam setiap proyek\nMenjaga kepercayaan klien dengan profesionalisme tinggi",
            'about_h1_icon'   => 'fas fa-award',
            'about_h1_label'  => 'Berpengalaman',
            'about_h2_icon'   => 'fas fa-users',
            'about_h2_label'  => 'Tim Profesional',
            'about_h3_icon'   => 'fas fa-check-circle',
            'about_h3_label'  => 'Terpercaya',
            'about_h4_icon'   => 'fas fa-handshake',
            'about_h4_label'  => 'Berkomitmen',
        ];
        foreach ($defaults as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $services = [
            ['title' => 'Perencanaan Konstruksi', 'description' => 'Layanan perencanaan teknis komprehensif untuk berbagai jenis proyek konstruksi.', 'icon' => 'fa-drafting-compass', 'order' => 1],
            ['title' => 'Pengawasan Konstruksi', 'description' => 'Pengawasan pelaksanaan pekerjaan konstruksi secara profesional.', 'icon' => 'fa-hard-hat', 'order' => 2],
            ['title' => 'Manajemen Konstruksi', 'description' => 'Pengelolaan proyek konstruksi secara menyeluruh.', 'icon' => 'fa-tasks', 'order' => 3],
            ['title' => 'Studi Kelayakan', 'description' => 'Analisis kelayakan teknis, ekonomis, dan finansial.', 'icon' => 'fa-chart-line', 'order' => 4],
            ['title' => 'Detail Desain Teknis', 'description' => 'Penyusunan gambar teknis detail dan spesifikasi.', 'icon' => 'fa-pencil-ruler', 'order' => 5],
            ['title' => 'Konsultansi Lingkungan', 'description' => 'Kajian dampak lingkungan AMDAL dan UKL-UPL.', 'icon' => 'fa-leaf', 'order' => 6],
        ];
        foreach ($services as $s) {
            Service::updateOrCreate(['title' => $s['title']], array_merge($s, ['is_active' => true]));
        }

        $team = [
            ['name' => 'Ir. Ahmad Fauzi, MT', 'position' => 'Direktur Utama', 'education' => 'S2 Teknik Sipil', 'expertise' => 'Manajemen Proyek', 'order' => 1],
            ['name' => 'Dra. Siti Rahayu', 'position' => 'Direktur Operasional', 'education' => 'S1 Teknik Sipil', 'expertise' => 'Pengawasan Konstruksi', 'order' => 2],
            ['name' => 'Ir. Budi Santoso, MT', 'position' => 'Team Leader', 'education' => 'S2 Teknik Sipil', 'expertise' => 'Jalan & Jembatan', 'order' => 3],
            ['name' => 'Irwansyah, ST', 'position' => 'Ahli Teknik Sipil', 'education' => 'S1 Teknik Sipil', 'expertise' => 'Perencanaan Struktur', 'order' => 4],
        ];
        foreach ($team as $t) {
            TeamMember::updateOrCreate(['name' => $t['name']], array_merge($t, ['is_active' => true]));
        }

        $projects = [
            ['name' => 'Perencanaan Jalan Lingkungan Kecamatan Cengkareng', 'client' => 'Dinas PU DKI Jakarta', 'location' => 'Jakarta Barat', 'year' => 2023, 'category' => 'Jalan', 'is_active' => true],
            ['name' => 'Pengawasan Pembangunan Gedung Perkantoran', 'client' => 'PT. Karya Mandiri', 'location' => 'Jakarta Selatan', 'year' => 2023, 'category' => 'Gedung', 'is_active' => true],
            ['name' => 'DED Drainase Kawasan Industri', 'client' => 'Pemerintah Kota Tangerang', 'location' => 'Tangerang', 'year' => 2022, 'category' => 'Air', 'is_active' => true],
            ['name' => 'Manajemen Konstruksi Jembatan Akses', 'client' => 'Kementerian PUPR', 'location' => 'Jawa Barat', 'year' => 2022, 'category' => 'Jembatan', 'is_active' => true],
            ['name' => 'Perencanaan Teknis SPAM', 'client' => 'PDAM Tirta Musi', 'location' => 'Palembang', 'year' => 2021, 'category' => 'Air', 'is_active' => true],
        ];
        foreach ($projects as $p) {
            Project::updateOrCreate(['name' => $p['name']], $p);
        }

        $clients = [
            ['name' => 'Kementerian PUPR', 'order' => 1, 'is_active' => true],
            ['name' => 'Dinas PU DKI Jakarta', 'order' => 2, 'is_active' => true],
            ['name' => 'Bina Marga', 'order' => 3, 'is_active' => true],
            ['name' => 'PT. PLN (Persero)', 'order' => 4, 'is_active' => true],
            ['name' => 'PT. Waskita Karya', 'order' => 5, 'is_active' => true],
            ['name' => 'PT. Hutama Karya', 'order' => 6, 'is_active' => true],
        ];
        foreach ($clients as $c) {
            Client::updateOrCreate(['name' => $c['name']], $c);
        }

        // Default navigation menus
        $menus = [
            ['label' => 'Beranda',         'route_name' => 'home',       'order' => 1],
            ['label' => 'Tentang Kami',    'route_name' => 'tentang',    'order' => 2],
            ['label' => 'Lingkup Layanan', 'route_name' => 'layanan',    'order' => 3],
            ['label' => 'SDM',             'route_name' => 'sdm',        'order' => 4],
            ['label' => 'Pengalaman',      'route_name' => 'pengalaman', 'order' => 5],
            ['label' => 'Klien',           'route_name' => 'klien',      'order' => 6],
            ['label' => 'Galeri',          'route_name' => 'galeri',     'order' => 7],
            ['label' => 'Kontak Kami',     'route_name' => 'kontak',     'order' => 8],
        ];
        foreach ($menus as $m) {
            Menu::updateOrCreate(
                ['route_name' => $m['route_name']],
                array_merge($m, ['is_active' => true, 'target' => '_self'])
            );
        }
    }
}
