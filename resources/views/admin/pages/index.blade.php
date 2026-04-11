@extends('layouts.admin')
@section('title', 'Halaman / Pages')
@section('page-title', 'Halaman / Pages')

@section('breadcrumb')
<li class="breadcrumb-item active">Halaman</li>
@endsection

@section('content')
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card card-table">
    <div class="cht-header d-flex justify-content-between align-items-center">
        <div><i class="fas fa-file-alt me-2"></i>Daftar Halaman Website</div>
        <a href="{{ route('admin.pages.create') }}" class="add-btn">
            <i class="fas fa-plus me-1"></i>Buat Halaman
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover" id="pageTable">
            <thead class="table-light">
                <tr>
                    <th width="40">#</th>
                    <th>Judul Halaman</th>
                    <th>Slug / URL</th>
                    <th width="100">Banner</th>
                    <th width="60">Urutan</th>
                    <th width="80">Status</th>
                    <th width="80">Update</th>
                    <th width="110">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $i => $page)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>
                        <div class="fw-semibold">{{ $page->title }}</div>
                        @if($page->meta_description)
                            <small class="text-muted">{{ Str::limit($page->meta_description, 70) }}</small>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <code class="small text-primary">/halaman/{{ $page->slug }}</code>
                            <a href="{{ route('page.show', $page->slug) }}" target="_blank"
                               class="btn btn-sm btn-outline-secondary py-0 px-1" title="Lihat di website">
                                <i class="fas fa-external-link-alt" style="font-size:10px;"></i>
                            </a>
                        </div>
                    </td>
                    <td>
                        @if($page->banner_image)
                            <img src="{{ asset('storage/'.$page->banner_image) }}"
                                 style="width:80px;height:45px;object-fit:cover;border-radius:4px;">
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge bg-light text-dark border">{{ $page->order }}</span>
                    </td>
                    <td>
                        @if($page->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <small class="text-muted">{{ $page->updated_at->format('d/m/Y') }}</small>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.pages.edit', $page) }}" class="btn-action btn-edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('page.show', $page->slug) }}" target="_blank"
                               class="btn-action" style="background:#e0f2fe;color:#0284c7;" title="Preview">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form id="del-{{ $page->id }}" action="{{ route('admin.pages.destroy', $page) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="button" class="btn-action btn-delete" title="Hapus"
                                    onclick="confirmDelete(document.getElementById('del-{{ $page->id }}'), 'Hapus halaman &quot;{{ $page->title }}&quot;?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="fas fa-file-alt fa-3x mb-3 d-block opacity-25"></i>
                        Belum ada halaman.
                        <a href="{{ route('admin.pages.create') }}">Buat halaman pertama</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="alert alert-info mt-3 d-flex align-items-start gap-3">
    <i class="fas fa-info-circle fa-lg mt-1"></i>
    <div>
        <strong>Cara menggunakan Halaman:</strong>
        Setelah membuat halaman, salin URL slug-nya kemudian tambahkan ke
        <a href="{{ route('admin.menus.index') }}"><strong>Menu Navigasi</strong></a>
        agar halaman dapat diakses dari navbar website.
        Contoh: slug <code>tentang-perusahaan</code> → URL: <code>/halaman/tentang-perusahaan</code>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function(){
    if($('#pageTable tbody tr').length > 4)
        $('#pageTable').DataTable({
            language:{search:'Cari:',lengthMenu:'Tampilkan _MENU_ data',info:'_START_-_END_ dari _TOTAL_',paginate:{previous:'‹',next:'›'}},
            pageLength:15,
            order:[[4,'asc']]
        });
});
</script>
@endpush
