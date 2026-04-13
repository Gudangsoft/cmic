<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\JenisProyek;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with(['jenisProyek', 'clientModel'])->latest()->get();
        $settings = \App\Models\Setting::pluck('value', 'key');

        $stats = [
            'total'       => $projects->count(),
            'aktif'       => $projects->where('is_active', true)->count(),
            'nonaktif'    => $projects->where('is_active', false)->count(),
            'with_image'  => $projects->whereNotNull('image')->count(),
            'with_gallery'=> $projects->filter(fn($p) => !empty($p->gallery))->count(),
        ];

        return view('admin.projects.index', compact('projects', 'stats', 'settings'));
    }

    public function updateIntro(Request $request)
    {
        $request->validate([
            'pengalaman_section_title'    => 'nullable|string|max:150',
            'pengalaman_section_subtitle' => 'nullable|string',
            'pengalaman_section_image'    => 'nullable|image|max:2048',
        ]);
        foreach (['pengalaman_section_title', 'pengalaman_section_subtitle'] as $key) {
            \App\Models\Setting::set($key, $request->input($key));
        }
        if ($request->hasFile('pengalaman_section_image')) {
            $old = \App\Models\Setting::get('pengalaman_section_image');
            if ($old) Storage::disk('public')->delete($old);
            $path = $request->file('pengalaman_section_image')->store('settings', 'public');
            \App\Models\Setting::set('pengalaman_section_image', $path);
        } elseif ($request->boolean('pengalaman_section_image_delete')) {
            $old = \App\Models\Setting::get('pengalaman_section_image');
            if ($old) Storage::disk('public')->delete($old);
            \App\Models\Setting::set('pengalaman_section_image', null);
        }
        return redirect()->route('admin.projects.index')->with('success', 'Pengaturan halaman Pengalaman berhasil disimpan.');
    }

    public function export()
    {
        $projects = Project::with(['jenisProyek', 'clientModel'])->latest()->get();

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Proyek');

        // Header row
        $headers = [['No', 'Nama Proyek', 'Klien / Pemberi Tugas', 'Lokasi', 'Tahun', 'Bidang / Jenis', 'Deskripsi', 'Status', 'Tanggal Dibuat']];
        $sheet->fromArray($headers, null, 'A1');

        // Style header
        $lastCol = 'I';
        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1859A9']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'BBCDE0']]],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(22);

        // Data rows
        $data = [];
        foreach ($projects as $i => $p) {
            $data[] = [
                $i + 1,
                $p->name,
                $p->clientModel?->name ?? $p->client ?? '',
                $p->location ?? '',
                $p->year ?? '',
                $p->jenisProyek?->nama ?? $p->category ?? '',
                $p->description ?? '',
                $p->is_active ? 'Aktif' : 'Nonaktif',
                $p->created_at->format('d/m/Y'),
            ];
        }
        if ($data) {
            $sheet->fromArray($data, null, 'A2');
        }

        // Zebra style data rows
        $totalRows = count($data) + 1;
        for ($r = 2; $r <= $totalRows; $r++) {
            $bg = ($r % 2 === 0) ? 'F0F4FA' : 'FFFFFF';
            $sheet->getStyle("A{$r}:{$lastCol}{$r}")->applyFromArray([
                'fill'    => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bg]],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D9E4F0']]],
            ]);
        }

        // Auto column width
        foreach (range('A', $lastCol) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet->getColumnDimension('G')->setWidth(45); // Deskripsi

        $filename = 'proyek_' . now()->format('Ymd_His') . '.xlsx';
        $writer   = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function importTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Proyek');

        $headers = [['Nama Proyek', 'Klien / Pemberi Tugas', 'Lokasi', 'Tahun', 'Bidang / Jenis', 'Deskripsi', 'Status']];
        $sheet->fromArray($headers, null, 'A1');

        $sheet->getStyle('A1:G1')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1859A9']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(22);

        // Helper notes in row 2 (italic, grey)
        $notes = [['(Wajib)', '(Opsional: nama klien)', '(Opsional: kota/provinsi)', '(Opsional: tahun, mis. 2024)', '(Opsional: nama bidang)', '(Opsional: deskripsi singkat)', '(Aktif / Nonaktif)']];
        $sheet->fromArray($notes, null, 'A2');
        $sheet->getStyle('A2:G2')->applyFromArray([
            'font' => ['italic' => true, 'color' => ['rgb' => '94a3b8'], 'size' => 9],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F8FAFF']],
        ]);

        // Example data
        $example = [['Pembangunan Gedung Dinas XYZ', 'PT. Contoh Klien', 'Jakarta', 2024, 'Bangunan Gedung', 'Perencanaan & pengawasan pembangunan gedung kantor 5 lantai', 'Aktif']];
        $sheet->fromArray($example, null, 'A3');
        $sheet->getStyle('A3:G3')->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0F4FA']],
        ]);

        // Auto size
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet->getColumnDimension('F')->setWidth(45);

        $writer = new Xlsx($spreadsheet);
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'template_import_proyek.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file.required' => 'File wajib dipilih.',
            'file.mimes'    => 'Format file harus .xlsx, .xls, atau .csv.',
            'file.max'      => 'Ukuran file maksimal 5 MB.',
        ]);

        $uploadedFile = $request->file('file');
        $ext          = strtolower($uploadedFile->getClientOriginalExtension());

        try {
            if ($ext === 'csv') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                $reader->setInputEncoding('UTF-8');
                $reader->setDelimiter(',');
            } else {
                $reader = IOFactory::createReaderForFile($uploadedFile->getRealPath());
            }
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($uploadedFile->getRealPath());
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membaca file: ' . $e->getMessage());
        }

        $sheet = $spreadsheet->getActiveSheet();
        $rows  = $sheet->toArray(null, true, true, false);

        if (count($rows) < 2) {
            return back()->with('error', 'File kosong atau tidak ada baris data.');
        }

        // Build maps for lookup
        $jenisMap  = JenisProyek::get(['id', 'nama'])->keyBy(fn($j) => mb_strtolower(trim($j->nama)));
        $clientMap = Client::get(['id', 'name'])->keyBy(fn($c) => mb_strtolower(trim($c->name)));

        $imported = 0;
        $skipped  = 0;
        $errors   = [];

        foreach (array_slice($rows, 1) as $idx => $row) {
            $rowNum = $idx + 2;
            $name   = trim($row[0] ?? '');

            if (empty($name)) {
                $skipped++;
                continue;
            }

            // Skip helper/note row
            if (str_starts_with($name, '(')) {
                $skipped++;
                continue;
            }

            $clientName  = trim($row[1] ?? '');
            $location    = trim($row[2] ?? '');
            $yearRaw     = trim($row[3] ?? '');
            $year        = is_numeric($yearRaw) ? (int)$yearRaw : null;
            $jenisName   = mb_strtolower(trim($row[4] ?? ''));
            $description = trim($row[5] ?? '');
            $statusRaw   = mb_strtolower(trim($row[6] ?? 'aktif'));
            $isActive    = in_array($statusRaw, ['aktif', 'active', '1', 'ya', 'yes', 'true']);

            $clientId = null;
            if ($clientName !== '') {
                $clientId = $clientMap[mb_strtolower($clientName)]?->id;
            }

            $jenisId = null;
            if ($jenisName !== '') {
                $jenisId = $jenisMap[$jenisName]?->id;
            }

            try {
                Project::create([
                    'name'            => $name,
                    'client'          => $clientName ?: null,
                    'client_id'       => $clientId,
                    'location'        => $location ?: null,
                    'year'            => $year,
                    'jenis_proyek_id' => $jenisId,
                    'description'     => $description ?: null,
                    'is_active'       => $isActive,
                ]);
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Baris {$rowNum}: " . $e->getMessage();
            }
        }

        $msg = "Import berhasil: {$imported} proyek ditambahkan.";
        if ($skipped)       $msg .= " ({$skipped} baris dilewati)";
        if (count($errors)) $msg .= ' Gagal: ' . implode('; ', array_slice($errors, 0, 3));

        return redirect()->route('admin.projects.index')->with('success', $msg);
    }

    public function create()
    {
        $clients  = Client::active()->orderBy('name')->get(['id', 'name']);
        $jenisAll = JenisProyek::orderBy('urutan')->orderBy('id')->get(['id', 'nama', 'warna']);
        return view('admin.projects.form', [
            'project'  => new Project(),
            'clients'  => $clients,
            'jenisAll' => $jenisAll,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'client_id'       => 'nullable|exists:clients,id',
            'client'          => 'nullable|string|max:255',
            'location'        => 'nullable|string|max:255',
            'year'            => 'nullable|integer|min:1990|max:2099',
            'jenis_proyek_id' => 'nullable|exists:jenis_proyek,id',
            'description'     => 'nullable|string',
            'image'           => 'nullable|image|max:2048',
            'gallery_new'     => 'nullable|array|max:10',
            'gallery_new.*'   => 'image|max:2048',
        ]);

        // Auto-fill client text from client model if chosen
        if ($request->filled('client_id')) {
            $c = Client::find($request->client_id);
            if ($c) $validated['client'] = $c->name;
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('projects', 'public');
        }

        $gallery = [];
        if ($request->hasFile('gallery_new')) {
            foreach ($request->file('gallery_new') as $file) {
                $gallery[] = $file->store('projects/gallery', 'public');
            }
        }
        $validated['gallery']   = $gallery ?: null;
        $validated['is_active'] = $request->boolean('is_active');
        Project::create($validated);
        return redirect()->route('admin.projects.index')->with('success', 'Proyek berhasil ditambahkan.');
    }

    public function show(string $id) {}

    public function edit(Project $project)
    {
        $clients  = Client::active()->orderBy('name')->get(['id', 'name']);
        $jenisAll = JenisProyek::orderBy('urutan')->orderBy('id')->get(['id', 'nama', 'warna']);
        return view('admin.projects.form', compact('project', 'clients', 'jenisAll'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'client_id'       => 'nullable|exists:clients,id',
            'client'          => 'nullable|string|max:255',
            'location'        => 'nullable|string|max:255',
            'year'            => 'nullable|integer|min:1990|max:2099',
            'jenis_proyek_id' => 'nullable|exists:jenis_proyek,id',
            'description'     => 'nullable|string',
            'image'           => 'nullable|image|max:2048',
            'gallery_new'     => 'nullable|array',
            'gallery_new.*'   => 'image|max:2048',
            'gallery_keep'    => 'nullable|array',
        ]);

        if ($request->filled('client_id')) {
            $c = Client::find($request->client_id);
            if ($c) $validated['client'] = $c->name;
        }

        if ($request->hasFile('image')) {
            if ($project->image) Storage::disk('public')->delete($project->image);
            $validated['image'] = $request->file('image')->store('projects', 'public');
        }

        $kept = $request->input('gallery_keep', []);
        foreach ($project->gallery ?? [] as $old) {
            if (!in_array($old, $kept)) Storage::disk('public')->delete($old);
        }
        $gallery = $kept;
        if ($request->hasFile('gallery_new')) {
            $remaining = 10 - count($gallery);
            foreach (array_slice($request->file('gallery_new'), 0, max(0, $remaining)) as $file) {
                $gallery[] = $file->store('projects/gallery', 'public');
            }
        }
        $validated['gallery']   = count($gallery) ? $gallery : null;
        $validated['is_active'] = $request->boolean('is_active');
        $project->update($validated);
        return redirect()->route('admin.projects.index')->with('success', 'Proyek berhasil diperbarui.');
    }

    public function destroy(Project $project)
    {
        if ($project->image) Storage::disk('public')->delete($project->image);
        foreach ($project->gallery ?? [] as $img) Storage::disk('public')->delete($img);
        $project->delete();
        return redirect()->route('admin.projects.index')->with('success', 'Proyek berhasil dihapus.');
    }
}

