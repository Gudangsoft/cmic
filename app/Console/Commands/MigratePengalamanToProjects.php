<?php

namespace App\Console\Commands;

use App\Models\PengalamanProyek;
use App\Models\Project;
use Illuminate\Console\Command;

class MigratePengalamanToProjects extends Command
{
    protected $signature   = 'migrate:pengalaman-to-projects';
    protected $description = 'Migrasikan item pengalaman_proyek (yang belum terhubung) ke tabel projects';

    public function handle(): int
    {
        $items = PengalamanProyek::whereNull('project_id')->get();

        if ($items->isEmpty()) {
            $this->info('Tidak ada item yang perlu dimigrasi.');
            return 0;
        }

        $migrated = 0;
        foreach ($items as $item) {
            $project = Project::create([
                'name'            => $item->nama,
                'client'          => $item->pemberi_tugas,
                'location'        => $item->lokasi,
                'year'            => $item->tahun,
                'jenis_proyek_id' => $item->jenis_proyek_id,
                'description'     => $item->deskripsi,
                'image'           => $item->gambar,
                'gallery'         => $item->galeri,
                'is_active'       => $item->is_active,
            ]);

            // Hubungkan item lama ke project baru agar relasi tidak putus
            $item->update(['project_id' => $project->id]);
            $migrated++;
        }

        $this->info("Migrasi selesai: {$migrated} item dipindahkan ke tabel projects.");
        return 0;
    }
}
